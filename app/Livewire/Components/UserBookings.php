<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\ModelNotFoundException; // Thêm import

class UserBookings extends Component
{
    use WithPagination;

    public $statusFilter = 'all';
    public $searchTerm = '';
    public $showCancelModal = false;
    public $bookingToCancel = null;

    // Thuộc tính mới để xem chi tiết
    public $selectedBooking = null; // Lưu booking đang được xem chi tiết
    public $showDetailModal = false; // Điều khiển hiển thị modal chi tiết

    protected $queryString = [
        'statusFilter' => ['except' => 'all'],
        'searchTerm' => ['except' => '']
    ];

    // --- Hủy Booking ---
    public function confirmCancellation($bookingId)
    {
        $this->bookingToCancel = $bookingId;
        $this->showCancelModal = true;
        $this->showDetailModal = false; // Đóng modal chi tiết nếu đang mở
        $this->selectedBooking = null;
    }

    public function cancelBooking()
    {
        // ... (code hủy booking giữ nguyên) ...
         try {
            $booking = Booking::where('id', $this->bookingToCancel)
                ->where('user_id', Auth::id())
                // Chỉ cho hủy các trạng thái phù hợp (ví dụ: pending, confirmed)
                ->whereIn('status', ['pending', 'confirmed'])
                ->firstOrFail();

            // Thêm cột cancelled_at nếu bạn có trong DB
             $booking->update(['status' => 'cancelled_by_user'/*, 'cancelled_at' => now()*/]);

            $this->closeModal(); // Gọi hàm đóng chung

            session()->flash('message', 'Đặt phòng #' . $booking->id . ' đã được hủy.');
         } catch (ModelNotFoundException $e) {
             session()->flash('error', 'Không tìm thấy đặt phòng hoặc không thể hủy.');
             $this->closeModal();
        } catch (\Exception $e) {
             session()->flash('error', 'Không thể hủy đặt phòng: ' . $e->getMessage());
             $this->closeModal();
        }
    }

    // --- Xem Chi tiết Booking ---
    public function showBookingDetails($bookingId)
    {
        try {
            $this->selectedBooking = Booking::with(['hotel', 'room', 'discountCode']) // Eager load relationships cần thiết
                ->where('user_id', Auth::id())
                ->findOrFail($bookingId);
            $this->showDetailModal = true;
             $this->showCancelModal = false; // Đóng modal hủy nếu đang mở
             $this->bookingToCancel = null;
        } catch (ModelNotFoundException $e) {
            session()->flash('error', 'Không tìm thấy thông tin đặt phòng.');
            $this->selectedBooking = null;
            $this->showDetailModal = false;
        }
    }

    // --- Đóng Modal ---
    public function closeModal() // Hàm đóng chung cho các modal
    {
        $this->showCancelModal = false;
        $this->bookingToCancel = null;
        $this->showDetailModal = false;
        $this->selectedBooking = null;
    }

    // --- Render View ---
    public function render()
    {
        $query = Booking::with(['hotel', 'room']) // Eager load để tránh N+1 query trong view
            ->where('user_id', Auth::id());

        // Filter và Search (giữ nguyên)
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }
        if (!empty($this->searchTerm)) {
             $query->where(function($q) {
                 $searchTermLike = '%' . $this->searchTerm . '%';
                 $q->where('id', 'like', $searchTermLike) // Tìm theo ID booking
                   ->orWhereHas('hotel', function($subq) use ($searchTermLike) {
                       $subq->where('name', 'like', $searchTermLike); // Tìm theo tên hotel
                   })
                   ->orWhereHas('room', function($subq) use ($searchTermLike) {
                        // Giả sử có cột name trong Room model
                       $subq->where('name', 'like', $searchTermLike); // Tìm theo tên phòng
                   });
                   // Thêm tìm theo customer_name nếu cần
                   // ->orWhere('customer_name', 'like', $searchTermLike);
             });
        }

        $bookings = $query->latest('created_at')->paginate(10); // Sắp xếp theo ngày tạo mới nhất

        // Trạng thái (giữ nguyên)
        $statuses = [ /* ... */ ]; // Giữ nguyên mảng statuses

        return view('livewire.components.user-bookings', [
            'bookings' => $bookings,
            'statuses' => $statuses // Giữ nguyên
        ]);
    }
}