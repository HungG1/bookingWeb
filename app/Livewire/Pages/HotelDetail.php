<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\Hotel;
use App\Models\Room;
use Carbon\Carbon;
use App\Models\Discount;
use Carbon\CarbonPeriod;
use App\Models\Booking;         
use Illuminate\Support\Facades\Auth;

class HotelDetail extends Component
{
    public $step = 1;
    public $hotel;
    public $checkInDate;
    public $checkOutDate;
    public $adults = 2;
    public $children = 0;
    public $rooms = 1;
    public $selectedRoomId = null;
    public $estimatedArrivalTime = null;
    public $specialRequests = '';
    public $promoCode = '';
    public $showBookingSummary = false;
    public $bookingToReview = null;
    public ?Room $viewingRoom = null;

    protected $listeners = [
        'reviewSubmitted' => 'onReviewSubmitted', 
    ];

    protected $rules = [
        'checkInDate' => 'required|date|after:today',
        'checkOutDate' => 'required|date|after:checkIn',
        'adults' => 'required|integer|min:1',
        'children' => 'required|integer|min:0',
        'rooms' => 'required|integer|min:1',
        'estimatedArrivalTime' => 'nullable|date_format:H:i',
        'specialRequests' => 'nullable|string|max:500',
        'promoCode' => 'nullable|string|max:50'
    ];

    public function viewRoomDetails($roomId)
    {
        $this->viewingRoom = Room::with('amenities')
                            ->where('hotel_id', $this->hotel->id)
                            ->find($roomId);
        if ($this->viewingRoom) {
            $this->dispatch('open-room-modal');
        }
    }

    public function closeModal()
    {
        $this->viewingRoom = null; 
    }

    public function selectRoom($roomId)
    {
        $this->selectedRoomId = $roomId;
        $this->showBookingSummary = true;
        $this->closeModal();
    }

    public function selectRoomAndCloseModal($roomId)
     {
         $this->selectRoom($roomId); 
     }

    public function calculatePrice()
    {
        if (!$this->selectedRoomId) {
            return null;
        }

        $room = Room::find($this->selectedRoomId);
        $nights = Carbon::parse($this->checkInDate)->diffInDays(Carbon::parse($this->checkOutDate)); 
        $basePrice = $room->base_price * $this->rooms * $nights;
        
        // Calculate tax (10% example)
        $tax = $basePrice * 0.08;
        
        // Calculate service fee (5% example)
        $serviceFee = $basePrice * 0.1;

        // Apply discount if promo code exists
        $discount = 0;
        if ($this->promoCode) {
            $promoDiscount = Discount::where('code', $this->promoCode)
                                   ->where('valid_from', '<=', now())
                                   ->where('valid_to', '>=', now())
                                   ->first();
            
            if ($promoDiscount) {
                if ($promoDiscount->type === 'percentage') {
                    $discount = $basePrice * ($promoDiscount->value / 100);
                } else {
                    $discount = $promoDiscount->value;
                }
            }
        }

        $finalPrice = $basePrice + $tax + $serviceFee - $discount;

        return [
            'basePrice' => $basePrice,
            'tax' => $tax,
            'serviceFee' => $serviceFee,
            'discount' => $discount,
            'finalPrice' => $finalPrice,
            'nights' => $nights,
            'pricePerNight' => $room->base_price
        ];
    }

    public function applyPromoCode()
    {
        $this->validate(['promoCode' => 'required|string']);
        // Will automatically recalculate price through the view
    }
    
    protected $queryString = [
        'checkInDate' => ['except' => ''],
        'checkOutDate' => ['except' => ''],
        'adults' => ['except' => 2],
        'children' => ['except' => 0],
        'rooms' => ['except' => 1],
    ];

    public function mount($id)
    {
        $this->hotel = Hotel::with([
            'rooms.amenities',
            'reviews' => fn($q) => $q->where('status', 'approved')->with('user'),
            'amenities',
        ])->findOrFail($id);

        $this->checkInDate  = request('checkIn', Carbon::tomorrow()->format('Y-m-d'));
        $this->checkOutDate = request('checkOut', Carbon::tomorrow()->addDay()->format('Y-m-d'));

        // Lấy booking đã hoàn thành/trả phòng chưa review
        if ($user = Auth::user()) {
            $this->bookingToReview = Booking::where('user_id', $user->id)
                ->where('hotel_id', $this->hotel->id)
                ->whereIn('status', ['completed', 'checked_out'])
                ->whereDoesntHave('review')
                ->latest('check_out_date')
                ->first();
        }
    }

    public function onReviewSubmitted()
    {
        // Reload reviews sau khi submit
        $this->hotel->load(['reviews' => fn($q) => $q->where('status','approved')->with('user')]);
        $this->bookingToReview = null;
    }

    public function proceedToBooking()
    {
        return redirect()->route('booking.create', [
            'hotelId' => $this->hotel->id,
            'roomId' => $this->selectedRoomId,
            'checkIn' => $this->checkInDate,
            'checkOut' => $this->checkOutDate,
            'adults' => $this->adults,
            'children' => $this->children,
            'rooms' => $this->rooms,
        ]);
    }

    public function updatedCheckInDate()
    {
        if (empty($this->checkOutDate) || Carbon::parse($this->checkInDate)->isAfter(Carbon::parse($this->checkOutDate))) {
            $this->checkOutDate = Carbon::parse($this->checkInDate)->addDay()->format('Y-m-d');
        }
    }

    public function getAvailableRoomsProperty()
    {
        if (! $this->checkInDate || ! $this->checkOutDate) {
            return collect();
        }

        $nights = Carbon::parse($this->checkInDate)
                    ->diffInDays(Carbon::parse($this->checkOutDate));
        $nights = max(1, $nights); // Đảm bảo số đêm ít nhất là 1

        $start  = Carbon::parse($this->checkInDate);
        $end    = Carbon::parse($this->checkOutDate)->subDay(); // Chỉ cần kiểm tra các đêm ở lại
        $period = CarbonPeriod::create($start, $end);
        $dates = $period->toArray(); // Lấy mảng các đối tượng Carbon

        // Lấy ID các phòng có booking trong khoảng ngày đã chọn
        $bookedRoomIds = Booking::where('hotel_id', $this->hotel->id)
            ->where(function ($query) {
                $query->whereBetween('check_in_date', [$this->checkInDate, $this->checkOutDate])
                      ->orWhereBetween('check_out_date', [$this->checkInDate, $this->checkOutDate])
                      ->orWhere(function($q) {
                           $q->where('check_in_date', '<', $this->checkInDate)
                             ->where('check_out_date', '>', $this->checkOutDate);
                      });
            })
            ->whereNotIn('status', ['cancelled_by_user', 'cancelled_by_admin', 'rejected'])
            ->pluck('room_id') // Chỉ lấy room_id
            ->unique(); // Lấy các ID duy nhất

        // Lấy tất cả phòng của khách sạn, loại trừ những phòng đã bị booking hoàn toàn
        $availableRooms = $this->hotel->rooms()
            ->whereNotIn('id', $bookedRoomIds) // Loại bỏ những phòng chắc chắn hết
            ->with('amenities') // Load sẵn amenities
            ->where('is_active', true) // Chỉ lấy phòng đang hoạt động
            ->get();

        return $availableRooms->map(function($room) use ($dates, $nights, $period) {
            $bookedCountOnDates = Booking::where('room_id', $room->id)
                                     ->whereNotIn('status', ['cancelled_by_user', 'cancelled_by_admin', 'rejected'])
                                     ->where(function ($query) use ($period) {
                                         foreach ($period as $day) {
                                             $query->orWhere(function($q) use ($day) {
                                                 $q->where('check_in_date', '<=', $day->format('Y-m-d'))
                                                   ->where('check_out_date', '>', $day->format('Y-m-d'));
                                             });
                                         }
                                     })
                                     ->count(); // Đếm số booking active

            $actualAvailable = max(0, $room->number_of_rooms - $bookedCountOnDates);

             // Nếu không còn phòng nào thì bỏ qua
             if ($actualAvailable <= 0) {
                 return null; // Sẽ được lọc ra bởi filter() sau
             }

            // 2. Tính giá (có thể giữ nguyên logic cũ nếu phức tạp)
            $totalPrice = 0;
            foreach ($period as $day) {
                // Tạm thời dùng giá cơ bản, bạn cần áp dụng logic giá phức tạp ở đây nếu có
                $nightPrice = $room->base_price;
                $totalPrice += $nightPrice;
            }
            $pricePerNight = ($nights > 0) ? round($totalPrice / $nights) : $room->base_price;

            return [
                'id'              => $room->id,
                'room'            => $room, // Trả về cả Room object
                'available'       => $actualAvailable,
                'price_per_night' => $pricePerNight,
                'total_price'     => round($pricePerNight * $nights * $this->rooms), // Nhân với số phòng khách chọn
            ];
        })->filter(); // Loại bỏ các kết quả null (phòng không còn trống)
    }

    public function getAverageRatingProperty()
    {
        return round($this->hotel->reviews->avg('rating'), 1);
    }

    public function getReviewCountProperty()
    {
        return $this->hotel->reviews->count();
    }

    public function getRatingBreakdownProperty()
    {
        $breakdown = [];
        for ($i = 5; $i >= 1; $i--) {
            $breakdown[$i] = $this->hotel->reviews->where('rating', $i)->count();
        }
        return $breakdown;
    }

    public function render()
    {
        return view('livewire.pages.hotel-detail', [
            'availableRooms' => $this->availableRooms,
            'averageRating'  => $this->averageRating,
            'reviewCount'    => $this->reviewCount,
            'ratingBreakdown'=> $this->ratingBreakdown,
        ]);
    }
}