<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\Booking;
use Carbon\Carbon;

class BookingSummary extends Component
{
    public $booking;
    public $nights;
    public $checkIn;
    public $checkOut;

    public function mount(Booking $booking)
    {
        $this->booking = $booking;
        $this->checkIn = Carbon::parse($booking->check_in_date);
        $this->checkOut = Carbon::parse($booking->check_out_date);
        $this->nights = $this->checkIn->diffInDays($this->checkOut);
    }

    public function getStatus()
    {
        $statuses = [
            'pending' => ['label' => 'Chờ xác nhận', 'color' => 'yellow'],
            'confirmed' => ['label' => 'Đã xác nhận', 'color' => 'green'],
            'cancelled_by_user' => ['label' => 'Đã hủy bởi khách', 'color' => 'red'],
            'cancelled_by_admin' => ['label' => 'Đã hủy bởi admin', 'color' => 'red'],
            'checked_in' => ['label' => 'Đã nhận phòng', 'color' => 'blue'],
            'checked_out' => ['label' => 'Đã trả phòng', 'color' => 'gray'],
            'no_show' => ['label' => 'Không đến', 'color' => 'red'],
        ];

        return $statuses[$this->booking->status] ?? ['label' => 'Không xác định', 'color' => 'gray'];
    }

    public function getPaymentStatus()
    {
        $statuses = [
            'unpaid' => ['label' => 'Chưa thanh toán', 'color' => 'red'],
            'partially_paid' => ['label' => 'Đã thanh toán một phần', 'color' => 'yellow'],
            'paid' => ['label' => 'Đã thanh toán', 'color' => 'green'],
            'refunded' => ['label' => 'Đã hoàn tiền', 'color' => 'gray'],
            'processing' => ['label' => 'Đang xử lý', 'color' => 'blue'],
        ];

        return $statuses[$this->booking->payment_status] ?? ['label' => 'Không xác định', 'color' => 'gray'];
    }

    public function cancelBooking()
    {
        if ($this->booking->status === 'pending') {
            $this->booking->update([
                'status' => 'cancelled_by_user',
            ]);

            session()->flash('message', 'Đã hủy đặt phòng thành công.');
            return redirect()->route('user.bookings');
        }
    }

    public function render()
    {
        return view('livewire.components.booking-summary');
    }
}