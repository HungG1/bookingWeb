<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Discount;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class BookingProcess extends Component
{
    // --- Thuộc tính Dữ liệu & Trạng thái ---
    public $hotel;
    public $room;
    public $checkInDate;
    public $checkOutDate;
    public $numberOfGuests = 1;
    public $specialRequests = '';
    public $discountCode = '';
    public $appliedDiscountCode = null;
    public $paymentMethod = 'pay_at_hotel';
    public $arrivalTime = null;
    public $step = 1;

    // --- Thuộc tính Tính toán (cần public để view truy cập) ---
    public $nights = 1;
    public $baseAmount = 0;
    public $discountAmount = 0;
    public $taxRate = 8;          // Có thể lấy từ config hoặc settings
    public $serviceFeeRate = 10; // Có thể lấy từ config hoặc settings
    public $taxAmount = 0;
    public $serviceFeeAmount = 0;
    public $totalAmount = 0;

    protected $validPaymentMethods = ['bank_transfer', 'pay_at_hotel'];

    // --- Validation ---
    protected function rules() {
        return [
            'checkInDate' => 'required|date|after_or_equal:today',
            'checkOutDate' => 'required|date|after:checkInDate',
            'numberOfGuests' => 'required|integer|min:1',
            'specialRequests' => 'nullable|string|max:1000',
            'paymentMethod' => ['required', Rule::in($this->validPaymentMethods)],
            'arrivalTime' => 'nullable|date_format:H:i',
        ];
    }

    protected function messages() {
         return [
            'paymentMethod.required' => 'Vui lòng chọn phương thức thanh toán.',
            'paymentMethod.in' => 'Phương thức thanh toán không hợp lệ.',
            'arrivalTime.date_format' => 'Thời gian đến dự kiến không đúng định dạng (HH:mm).',
            'checkInDate.required' => 'Vui lòng chọn ngày nhận phòng.',
            'checkInDate.date' => 'Ngày nhận phòng không hợp lệ.',
            'checkInDate.after_or_equal' => 'Ngày nhận phòng phải là hôm nay hoặc sau hôm nay.',
            'checkOutDate.required' => 'Vui lòng chọn ngày trả phòng.',
            'checkOutDate.date' => 'Ngày trả phòng không hợp lệ.',
            'checkOutDate.after' => 'Ngày trả phòng phải sau ngày nhận phòng.',
            'numberOfGuests.required' => 'Vui lòng nhập số lượng khách.',
            'numberOfGuests.integer' => 'Số lượng khách phải là số nguyên.',
            'numberOfGuests.min' => 'Số lượng khách phải ít nhất là 1.',
            'specialRequests.max' => 'Yêu cầu đặc biệt không được vượt quá 1000 ký tự.',
        ];
     }

    protected $validationAttributes = [
        'paymentMethod' => 'phương thức thanh toán',
        'arrivalTime' => 'thời gian đến dự kiến',
        'discountCode' => 'mã giảm giá',
        'checkInDate' => 'ngày nhận phòng',
        'checkOutDate' => 'ngày trả phòng',
        'numberOfGuests' => 'số lượng khách',
        'specialRequests' => 'yêu cầu đặc biệt',
    ];

    // --- Lifecycle Hooks & Methods ---
    public function mount($hotelId, $roomId = null)
    {
        Log::info('BookingProcess mounting...', ['hotelId' => $hotelId, 'roomId' => $roomId]); // Dùng info cho dễ thấy
        try {
            $this->hotel = Hotel::with('rooms')->findOrFail($hotelId);
            $this->room = $roomId ? $this->hotel->rooms()->find($roomId) : $this->hotel->rooms()->orderBy('base_price', 'asc')->first();
            if (!$this->room) { throw new ModelNotFoundException('Room not found.'); }

            $this->checkInDate = request('checkIn', Carbon::now()->addDay()->format('Y-m-d'));
            $this->checkOutDate = request('checkOut', Carbon::now()->addDays(2)->format('Y-m-d'));
            $this->numberOfGuests = max(1, (int)request('adults', 1) + (int)request('children', 0));

            $this->calculateTotalAmount(); // Tính giá trị ban đầu
            Log::info('BookingProcess mount completed.', ['nights' => $this->nights]);

        } catch (ModelNotFoundException $e) {
             Log::warning("BookingProcess mount failed: " . $e->getMessage(), ['hotelId' => $hotelId, 'roomId' => $roomId]);
             session()->flash('error', 'Không tìm thấy thông tin khách sạn hoặc phòng.');
             $this->hotel = null; $this->room = null;
        } catch (\Exception $e) {
            Log::error("Error mounting BookingProcess: " . $e->getMessage());
            session()->flash('error', 'Lỗi tải trang.');
            $this->hotel = null; $this->room = null;
        }
    }

    public function updated($propertyName)
    {
        // <<< LOG QUAN TRỌNG: Kiểm tra giá trị nhận được >>>
        Log::info(">>> Property '$propertyName' updated with value:", [$this->{$propertyName}]);

        if (!$this->hotel || !$this->room){
             Log::warning('Updated hook skipped: hotel or room missing.');
             return;
        }

        if (in_array($propertyName, ['checkInDate', 'checkOutDate', 'numberOfGuests'])) {
             Log::info('>>> Calling calculateTotalAmount from updated hook...');
            $this->calculateTotalAmount();
             Log::info('<<< Values after calculation in updated hook:', [
                 'nights' => $this->nights,
                 'baseAmount' => $this->baseAmount,
                 'totalAmount' => $this->totalAmount
             ]);
        }
        if (array_key_exists($propertyName, $this->rules())) {
            try { $this->validateOnly($propertyName); } catch (ValidationException $e) {}
        }
    }

    public function calculateTotalAmount()
    {
        Log::info('---------- calculateTotalAmount START ----------');
        if (!$this->room || !$this->checkInDate || !$this->checkOutDate) {
            Log::warning('calculateTotalAmount: Missing prerequisites.');
            $this->baseAmount = $this->taxAmount = $this->serviceFeeAmount = $this->totalAmount = $this->nights = 0;
            return;
        }

        try {
            $checkIn = Carbon::parse($this->checkInDate);
            $checkOut = Carbon::parse($this->checkOutDate);
            Log::info('Calculating for dates:', ['checkIn' => $checkIn->toDateString(), 'checkOut' => $checkOut->toDateString()]);

            if ($checkOut->lte($checkIn)) {
                Log::warning('Invalid date range for calculation.');
                // Đặt giá trị mặc định khi có lỗi về ngày
                $this->nights = 0;
                $this->baseAmount = $this->taxAmount = $this->serviceFeeAmount = $this->totalAmount = 0;
                return;
            }

            // Fix #1: Đảm bảo số đêm luôn dương bằng cách đặt checkIn trước checkOut
            $this->nights = abs($checkOut->diffInDays($checkIn));
            Log::info('Nights calculated and assigned:', ['nights' => $this->nights]);

            $roomBasePrice = abs($this->room->base_price ?? 0); // Fix #2: Đảm bảo giá cơ bản luôn dương
            $this->baseAmount = $roomBasePrice * $this->nights; // Gán trực tiếp
            Log::info('Base amount calculated:', ['base' => $this->baseAmount]);

            $this->taxAmount = round(($this->baseAmount * $this->taxRate) / 100);
            $this->serviceFeeAmount = round(($this->baseAmount * $this->serviceFeeRate) / 100);
            $calculatedTotal = $this->baseAmount - $this->discountAmount + $this->taxAmount + $this->serviceFeeAmount;
            $this->totalAmount = max(0, $calculatedTotal); // Gán trực tiếp
            Log::info('Final amounts calculated:', ['tax'=>$this->taxAmount, 'fee'=>$this->serviceFeeAmount, 'total'=>$this->totalAmount]);

        } catch (\Exception $e) {
            Log::error('Error during calculation: ' . $e->getMessage());
            $this->baseAmount = $this->taxAmount = $this->serviceFeeAmount = $this->totalAmount = $this->nights = 0;
        }
        Log::info('---------- calculateTotalAmount END ----------');
    }

    protected function updateCalculatedValues()
    {
        $calculatedData = $this->calculateBookingAmounts();
        $this->nights = $calculatedData['nights'];
        $this->baseAmount = $calculatedData['baseAmount'];
        $this->taxAmount = $calculatedData['taxAmount'];
        $this->serviceFeeAmount = $calculatedData['serviceFeeAmount'];
        $this->totalAmount = $calculatedData['totalAmount'];
    }

    protected function calculateBookingAmounts(): array
    {
        $defaultValues = ['nights' => 0, 'baseAmount' => 0, 'taxAmount' => 0, 'serviceFeeAmount' => 0, 'totalAmount' => 0];
        if (!$this->room || !$this->checkInDate || !$this->checkOutDate) return $defaultValues;

        try {
            $checkIn = Carbon::parse($this->checkInDate);
            $checkOut = Carbon::parse($this->checkOutDate);
            if ($checkOut->lte($checkIn)) return $defaultValues;

            // Fix #3: Đảm bảo số đêm luôn dương
            $nights = abs($checkOut->diffInDays($checkIn));
            $roomBasePrice = abs($this->room->base_price ?? 0); // Fix #4: Đảm bảo giá cơ bản luôn dương
            $baseAmount = $roomBasePrice * $nights;
            $taxAmount = round(($baseAmount * $this->taxRate) / 100);
            $serviceFeeAmount = round(($baseAmount * $this->serviceFeeRate) / 100);
            $calculatedTotal = $baseAmount - $this->discountAmount + $taxAmount + $serviceFeeAmount; // Dùng discountAmount hiện tại
            $totalAmount = max(0, $calculatedTotal);

            return compact('nights', 'baseAmount', 'taxAmount', 'serviceFeeAmount', 'totalAmount');
        } catch (\Exception $e) {
            Log::error('Error in calculateBookingAmounts: ' . $e->getMessage());
            return $defaultValues;
        }
    }

    public function selectRoom($roomId)
    {
        if (!$this->hotel) return;
        try {
            $selectedRoom = $this->hotel->rooms()->findOrFail($roomId);
            if (!$this->room || $this->room->id !== $selectedRoom->id) {
                $this->room = $selectedRoom;
                $this->removeDiscountCode(); // Reset discount khi đổi phòng
                $this->updateCalculatedValues(); // Tính lại giá với phòng mới
                session()->flash('success', 'Đã chọn phòng: ' . ($this->room->room_type_name ?? $this->room->name));
            }
        } catch (ModelNotFoundException $e) {
            session()->flash('error', 'Không thể chọn phòng này.');
        }
    }

    public function applyDiscountCode()
    {
        if (empty($this->discountCode)) { session()->flash('discount_error', 'Vui lòng nhập mã.'); return; }
        if (!$this->room) { session()->flash('discount_error', 'Chưa chọn phòng.'); return; }

        $currentAmounts = $this->calculateBookingAmounts(); // Lấy baseAmount mới nhất
        if ($currentAmounts['baseAmount'] <= 0) { session()->flash('discount_error', 'Không áp dụng được khi giá gốc bằng 0.'); return; }

        Log::info('Applying discount code:', ['code' => $this->discountCode]);
        try {
            $discount = Discount::where('code', $this->discountCode)
                ->where('status', 'active')
                ->where('expires_at', '>', Carbon::now())
                ->first();

            if (!$discount) {
                session()->flash('discount_error', 'Mã giảm giá không hợp lệ hoặc hết hạn.');
                $this->removeDiscountCode(); return;
            }

            $calculatedDiscount = 0;
            if ($discount->discount_type === 'percentage') {
                $calculatedDiscount = ($currentAmounts['baseAmount'] * $discount->discount_value) / 100;
            } elseif ($discount->discount_type === 'fixed_amount') {
                $calculatedDiscount = $discount->discount_value;
            } else { throw new \Exception("Loại giảm giá không hợp lệ: " . $discount->discount_type); }

            $this->discountAmount = min(max(0, $calculatedDiscount), $currentAmounts['baseAmount']);
            $this->appliedDiscountCode = $discount;
            $this->updateCalculatedValues(); // Tính lại tổng tiền sau khi áp dụng discount

            session()->forget('discount_error');
            session()->flash('discount_success', 'Đã áp dụng mã giảm giá!');

        } catch (\Exception $e) {
            Log::error('Error applying discount code: ' . $e->getMessage(), ['code' => $this->discountCode]);
            session()->flash('discount_error', 'Lỗi khi áp dụng mã.');
            $this->removeDiscountCode();
        }
    }

    public function removeDiscountCode()
    {
        $this->discountCode = '';
        $this->discountAmount = 0;
        $this->appliedDiscountCode = null;
        $this->updateCalculatedValues(); // Tính lại tổng tiền
        session()->forget(['discount_error', 'discount_success']);
    }

    public function nextStep()
    {
        if (!$this->hotel || !$this->room) { session()->flash('error', 'Lỗi thông tin khách sạn/phòng.'); return; }

        if ($this->step === 1) {
            try { $this->validate(['checkInDate'=>'required|date|after_or_equal:today', 'checkOutDate'=>'required|date|after:checkInDate', 'numberOfGuests'=>'required|integer|min:1', 'specialRequests'=>'nullable|string|max:1000']); }
            catch (ValidationException $e) { return; }
            if (!$this->checkRoomAvailability()) { session()->flash('error', 'Phòng không còn trống.'); return; }
            $this->step = 2;
        } elseif ($this->step === 2) {
            if (!Auth::check()) { session()->flash('error', 'Vui lòng đăng nhập.'); return redirect()->route('login'); }
            if (!Auth::user()->phone_number) { session()->flash('error', 'Vui lòng cập nhật số điện thoại.'); return; }
            try { $this->validate(['arrivalTime'=>'nullable|date_format:H:i']); }
            catch (ValidationException $e) { return; }
            $this->step = 3;
        } elseif ($this->step === 3) {
            // Ở bước 3, bấm nút "Hoàn tất" sẽ gọi createBooking()
        }
    }

    public function prevStep()
    {
        if ($this->step > 1) {
            $this->step--;
            $this->resetErrorBag();
            session()->forget(['error', 'warning', 'success', 'discount_error', 'discount_success']);
        }
    }

    public function checkRoomAvailability(): bool
    {
        if (!$this->room || !$this->checkInDate || !$this->checkOutDate) return false;
        try {
            $checkIn = Carbon::parse($this->checkInDate);
            $checkOut = Carbon::parse($this->checkOutDate);
            if ($checkOut->lte($checkIn)) return false;

            $conflictingBookings = Booking::where('room_id', $this->room->id)
                ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
                ->where(function ($q) use ($checkIn, $checkOut) {
                    $q->where('check_in_date', '<', $checkOut)->where('check_out_date', '>', $checkIn);
                })
                ->exists(); // Dùng exists() hiệu quả hơn count() khi chỉ cần biết có tồn tại hay không

            return !$conflictingBookings; // Trả về true nếu KHÔNG có booking trùng lặp

        } catch (\Exception $e) {
            Log::error("Error checking room availability: " . $e->getMessage());
            return false;
        }
    }

    public function createBooking()
    {
        if (!$this->hotel || !$this->room) { session()->flash('error', 'Lỗi thông tin.'); return; }
        if (!Auth::check()) { session()->flash('error', 'Vui lòng đăng nhập.'); return redirect()->route('login'); }

        $validatedData = [];
        try {
            $validatedData = $this->validate(); // Validate tất cả rules trước khi tạo
            if (!$this->checkRoomAvailability()) { session()->flash('error', 'Phòng không còn trống.'); return; }
            // Tính toán lại lần cuối để đảm bảo giá trị đúng nhất
            $this->updateCalculatedValues();
             if ($this->baseAmount <= 0 && $this->totalAmount <= 0 && $this->discountAmount <=0) { session()->flash('error', 'Giá trị đặt phòng không hợp lệ.'); return; }

            DB::beginTransaction();
            $booking = new Booking();
             $booking->fill([
                 'user_id' => Auth::id(),
                 'hotel_id' => $this->hotel->id,
                 'room_id' => $this->room->id,
                 'check_in_date' => $this->checkInDate,
                 'check_out_date' => $this->checkOutDate,
                 'arrival_time' => $this->arrivalTime,
                 'num_adults' => $this->numberOfGuests,
                 'num_children' => 0, // Cần cập nhật nếu có logic cho trẻ em
                 'customer_name' => Auth::user()->name,
                 'customer_email' => Auth::user()->email,
                 'customer_phone' => Auth::user()->phone_number,
                 'customer_notes' => $this->specialRequests,
                 'base_price' => abs($this->baseAmount), // Fix #5: Đảm bảo giá trị dương
                 'discount_amount' => abs($this->discountAmount), // Fix #6: Đảm bảo giá trị dương
                 'tax_amount' => abs($this->taxAmount), // Fix #7: Đảm bảo giá trị dương
                 'service_fee_amount' => abs($this->serviceFeeAmount), // Fix #8: Đảm bảo giá trị dương
                 'final_price' => abs($this->totalAmount), // Fix #9: Đảm bảo giá trị dương
                 'payment_method' => $this->paymentMethod,
                 'payment_status' => 'unpaid',
                 'status' => 'pending', // Luôn là pending ban đầu, admin/hệ thống sẽ confirm sau
                 'discount_code_id' => $this->appliedDiscountCode?->id,
             ]);
            $booking->save();
            DB::commit();

            Log::info('Booking created successfully', ['booking_id' => $booking->id, 'user_id' => Auth::id()]);
            session()->flash('success', 'Đặt phòng thành công! Mã đặt phòng: #' . $booking->id . '. Vui lòng chờ xác nhận.');
            return redirect()->route('booking.confirmation', $booking);

        } catch (ValidationException $e) {
            session()->flash('error', 'Vui lòng kiểm tra lại thông tin.');
             Log::warning('Booking creation validation failed', ['errors' => $e->errors()]);
        } catch (\Exception $e) {
            DB::rollBack();
             Log::error('Booking creation failed: ' . $e->getMessage(), [ 'user_id' => Auth::id(), 'data' => $validatedData ?? 'N/A' ]);
             session()->flash('error', 'Lỗi khi tạo đặt phòng. Vui lòng thử lại.');
        }
    }

    public function render()
    {
        $availableRooms = collect(); // Khởi tạo collection rỗng
        try {
            // Chỉ truy vấn nếu $this->hotel tồn tại (đã kiểm tra ở trên)
             $availableRooms = $this->hotel->rooms()->orderBy('base_price', 'asc')->get();
        } catch(\Exception $e) {
             Log::error('Failed to fetch available rooms in BookingProcess render: '.$e->getMessage());
        }

        return view('livewire.pages.booking-process', [
            'availableRooms' => $availableRooms, // Luôn có giá trị (ít nhất là collection rỗng)
            'nights' => abs($this->nights), // Fix #10: Đảm bảo hiển thị số đêm dương
            'baseAmount' => abs($this->baseAmount), // Fix #11: Đảm bảo hiển thị giá trị dương
            'taxAmount' => abs($this->taxAmount), // Fix #12: Đảm bảo hiển thị giá trị dương
            'serviceFeeAmount' => abs($this->serviceFeeAmount), // Fix #13: Đảm bảo hiển thị giá trị dương
            'discountAmount' => abs($this->discountAmount), // Fix #14: Đảm bảo hiển thị giá trị dương
            'totalAmount' => abs($this->totalAmount), // Fix #15: Đảm bảo hiển thị giá trị dương
            'appliedDiscountCode' => $this->appliedDiscountCode
        ]);
    }
}