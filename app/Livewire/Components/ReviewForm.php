<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Hotel;
use Illuminate\Support\Facades\Log;

class ReviewForm extends Component
{
    public Hotel $hotel;
    public Booking $booking;

    public $rating = 5;
    public $title = '';
    public $comment = '';

    protected function rules()
    {
        return [
            'rating'  => 'required|integer|min:1|max:5',
            'title'   => 'required|string|min:3|max:100',
            'comment' => 'required|string|min:10|max:1000',
        ];
    }

    protected function messages()
    {
        return [
            'rating.required' => 'Vui lòng chọn số sao đánh giá.',
            'title.required'  => 'Vui lòng nhập tiêu đề đánh giá.',
            'comment.required'=> 'Vui lòng nhập nội dung đánh giá.',
        ];
    }

    public function mount(Hotel $hotel, Booking $booking)
    {
        $this->hotel   = $hotel;
        $this->booking = $booking;
    }

    public function submitReview()
    {
        $this->validate();

        try {
            Review::create([
                'booking_id'=> $this->booking->id,
                'user_id'   => Auth::id(),
                'hotel_id'  => $this->hotel->id,
                'rating'    => $this->rating,
                'title'     => $this->title,
                'comment'   => $this->comment,
                'status'    => 'pending',
            ]);

            // Reset form và emit sự kiện
            $this->reset(['rating', 'title', 'comment']);
            $this->dispatch('reviewSubmitted');

        } catch (\Exception $e) {
            Log::error('Error submitting review: '.$e->getMessage());
            session()->flash('review_form_error', 'Có lỗi xảy ra khi gửi đánh giá.');
        }
    }

    public function render()
    {
        return view('livewire.components.review-form');
    }
}