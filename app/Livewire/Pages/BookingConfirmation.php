<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule; // Thêm Rule nếu chưa có

class BookingConfirmation extends Component
{
    // Khai báo đúng Type Hint cho Route Model Binding
    public Booking $booking;

    public $pageTitle = 'Xác nhận Đặt phòng';
    public $paymentMethod; // Lưu phương thức thanh toán được chọn/hiển thị

    // Chỉ giữ lại các phương thức thanh toán thực tế
    public $paymentMethods = [
        'bank_transfer' => ['name' => 'Chuyển khoản ngân hàng', 'description' => 'Thanh toán trước qua chuyển khoản.'],
        'pay_at_hotel' => ['name' => 'Thanh toán khi nhận phòng', 'description' => 'Thanh toán tại quầy lễ tân khi đến.'],
    ];

    /**
     * Mount component - PHẢI nhận Booking $booking
     */
    public function mount(Booking $booking)
    {
        Log::debug('BookingConfirmation mounting...', ['booking_id' => $booking->id]);
        // Kiểm tra quyền truy cập
        if ($booking->user_id !== Auth::id()) {
             Log::warning('Unauthorized access attempt to booking confirmation.', ['booking_id' => $booking->id, 'auth_user' => Auth::id()]);
            abort(404); // Hoặc 403
        }

        $this->booking = $booking; // Gán đối tượng Booking
        $this->paymentMethod = $booking->payment_method ?? array_key_first($this->paymentMethods);
        // Đảm bảo $paymentMethod hợp lệ
        if (!array_key_exists($this->paymentMethod, $this->paymentMethods)) {
             $this->paymentMethod = array_key_first($this->paymentMethods);
        }
        $this->pageTitle = 'Xác nhận Đặt phòng #' . $booking->id;
         Log::debug('BookingConfirmation mounted successfully.', ['booking_id' => $this->booking->id]);
    }

    /**
     * Xác nhận phương thức thanh toán (chủ yếu cho bank_transfer hoặc để cập nhật lựa chọn)
     */
    public function confirmPayment()
    {
        $this->validate([
            'paymentMethod' => ['required', Rule::in(array_keys($this->paymentMethods))]
        ], [
            'paymentMethod.required' => 'Vui lòng chọn phương thức thanh toán.',
            'paymentMethod.in' => 'Phương thức không hợp lệ.',
        ]);

        try {
             // Chỉ cập nhật nếu có thay đổi và trạng thái cho phép
            if ($this->booking->payment_method !== $this->paymentMethod && $this->booking->payment_status === 'unpaid') {
                 $this->booking->update(['payment_method' => $this->paymentMethod]);
                 Log::info('Payment method updated for booking.', ['booking_id' => $this->booking->id, 'new_method' => $this->paymentMethod]);
            }

            // Xử lý riêng cho bank_transfer (hiển thị thông báo)
            if ($this->paymentMethod === 'bank_transfer') {
                session()->flash('payment_info', 'Vui lòng chuyển khoản tới [Số TK] tại [Ngân hàng] với nội dung [Mã ĐP ' . $this->booking->id . '].');
                 return $this->redirectRoute('booking.confirmation', ['booking' => $this->booking->id], navigate: true); // Tải lại trang bằng navigate của Livewire
            }
             // Trường hợp pay_at_hotel không cần xử lý gì thêm ở đây vì đã được xác nhận lúc tạo booking

            // Nếu có logic chuyển cổng thanh toán thì sẽ xử lý ở đây

        } catch (\Exception $e) {
            Log::error('Error confirming payment method: ' . $e->getMessage(), ['booking_id' => $this->booking->id]);
            session()->flash('error', 'Lỗi khi xác nhận thanh toán.');
        }
    }

    /**
     * Hủy đặt phòng
     */
     public function cancelBooking()
     {
         try {
             if ($this->booking->user_id !== Auth::id()) abort(403);
              // Chỉ cho phép hủy nếu trạng thái là pending hoặc confirmed
             if (!in_array($this->booking->status, ['pending', 'confirmed'])) {
                 session()->flash('error', 'Không thể hủy đặt phòng ở trạng thái này.');
                 return;
             }
              $this->booking->update(['status' => 'cancelled_by_user']);
              return redirect()->route('user.bookings')->with('message', 'Đặt phòng #' . $this->booking->id . ' đã được hủy.');
         } catch (\Exception $e) {
             Log::error('Error cancelling booking from confirmation: ' . $e->getMessage(), ['booking_id' => $this->booking->id]);
             session()->flash('error', 'Lỗi khi hủy đặt phòng.');
         }
     }

    /**
     * Render view
     */
    public function render()
    {
        // $this->booking->refresh(); // Không cần thiết nếu dùng Route Model Binding và không thay đổi $booking nhiều
        return view('livewire.pages.booking-confirmation');
    }
}