<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\Review;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException; // Thêm import

class UserReviews extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $showDeleteModal = false;
    public $reviewToDelete = null;

    // Thuộc tính mới cho Form Đánh giá
    public $showReviewForm = false;
    public $bookingToReview = null; // Lưu object Booking cần review
    public $rating = 5;
    public $reviewTitle = '';
    public $reviewContent = '';

    protected $queryString = ['searchTerm' => ['except' => '']];
    protected $listeners = ['refreshReviews' => '$refresh']; // Giữ nguyên listener

    // --- Validation cho Form Review ---
    protected function reviewFormRules()
    {
         return [
            'rating' => 'required|integer|min:1|max:5',
            'reviewTitle' => 'required|string|max:100',
            'reviewContent' => 'required|string|max:1000',
         ];
    }
    protected function reviewFormMessages()
    {
        return [
            'rating.required' => 'Vui lòng chọn điểm đánh giá.',
            'rating.*' => 'Điểm đánh giá không hợp lệ.',
            'reviewTitle.required' => 'Vui lòng nhập tiêu đề đánh giá.',
            'reviewTitle.max' => 'Tiêu đề không được vượt quá 100 ký tự.',
            'reviewContent.required' => 'Vui lòng nhập nội dung đánh giá.',
            'reviewContent.max' => 'Nội dung không được vượt quá 1000 ký tự.',
        ];
    }
     protected $validationAttributes = [
        'rating' => 'điểm đánh giá',
        'reviewTitle' => 'tiêu đề',
        'reviewContent' => 'nội dung đánh giá',
     ];


    // --- Xóa Review ---
    public function confirmDelete($reviewId) { /* ... (Giữ nguyên) ... */ }
    public function deleteReview() { /* ... (Giữ nguyên) ... */ }

    // --- Form Đánh giá ---
    public function showReviewForm($bookingId)
    {
        try {
            // Tìm booking chưa có review của user này
            $this->bookingToReview = Booking::with('hotel') // Load hotel để hiển thị tên
                ->where('id', $bookingId)
                ->where('user_id', Auth::id())
                ->whereIn('status', ['completed', 'checked_out']) // Chỉ cho review booking đã hoàn thành/trả phòng
                ->whereDoesntHave('review') // Quan trọng: chỉ booking chưa có review
                ->firstOrFail();

            // Reset form và mở modal
            $this->rating = 5;
            $this->reviewTitle = '';
            $this->reviewContent = '';
            $this->resetErrorBag(); // Xóa lỗi validation cũ
            $this->showReviewForm = true;
            $this->showDeleteModal = false; // Đảm bảo modal xóa đóng

        } catch (ModelNotFoundException $e) {
             session()->flash('error', 'Không tìm thấy đặt phòng hợp lệ để đánh giá.');
             $this->bookingToReview = null;
             $this->showReviewForm = false;
        } catch (\Exception $e) {
             logger()->error('Error showing review form: ' . $e->getMessage());
             session()->flash('error', 'Có lỗi xảy ra, vui lòng thử lại.');
             $this->bookingToReview = null;
             $this->showReviewForm = false;
        }
    }

     public function submitReview()
    {
        if (!$this->bookingToReview) {
            session()->flash('error', 'Lỗi: Không có thông tin đặt phòng để đánh giá.');
            return;
        }

        // Validate dữ liệu form
        $validatedData = $this->validate($this->reviewFormRules(), $this->reviewFormMessages(), $this->validationAttributes);

        try {
            Review::create([
                'user_id' => Auth::id(),
                'hotel_id' => $this->bookingToReview->hotel_id,
                'booking_id' => $this->bookingToReview->id,
                'rating' => $validatedData['rating'],
                'title' => $validatedData['reviewTitle'],
                'content' => $validatedData['reviewContent'],
                // Thêm các trường khác nếu cần (is_approved,...)
            ]);

            $this->closeModal(); // Đóng form review
            session()->flash('message', 'Cảm ơn bạn đã gửi đánh giá!');
             // $this->emit('refreshReviews'); // Không cần vì $refresh đã được dùng

        } catch (\Exception $e) {
             logger()->error('Error submitting review: ' . $e->getMessage());
             session()->flash('review_form_error', 'Đã có lỗi xảy ra khi gửi đánh giá.'); // Flash vào key khác để hiển thị trong modal
        }
    }


    // --- Đóng Modal ---
    public function closeModal()
    {
        $this->showDeleteModal = false;
        $this->reviewToDelete = null;
        $this->showReviewForm = false; // Đóng cả form review
        $this->bookingToReview = null;
        $this->resetErrorBag(); // Xóa lỗi khi đóng modal
    }

    // --- Lấy Booking chưa review ---
    public function getUnreviewedBookingsProperty(): Collection
    {
        // ... (Giữ nguyên logic query, đảm bảo whereDoesntHave('review') đúng) ...
         try {
            return Booking::with(['hotel', 'room'])
                ->where('user_id', Auth::id())
                 // Trạng thái nào thì được review?
                ->whereIn('status', ['completed', 'checked_out']) // Ví dụ: đã hoàn thành hoặc trả phòng
                ->whereDoesntHave('review') // Quan hệ tên là 'review' (HasOne)
                ->latest('check_out_date')
                ->take(5) // Giới hạn số lượng hiển thị
                ->get();
         } catch (\Exception $e) {
            logger()->error('Error fetching unreviewed bookings: ' . $e->getMessage());
            return collect();
        }
    }

    // --- Render View ---
    public function render()
    {
        // Query Reviews (giữ nguyên)
         $query = Review::with(['hotel', 'booking']) // Load booking nếu cần hiển thị thông tin liên quan
           ->where('user_id', Auth::id());

         if (!empty($this->searchTerm)) { /* ... (search logic giữ nguyên) ... */ }

         $reviews = $query->latest()->paginate(5); // Giảm số lượng review/trang nếu muốn

        return view('livewire.components.user-reviews', [
            'reviews' => $reviews,
             // Truyền unreviewedBookings vào view
             // Computed property đã được gọi tự động
             // 'unreviewedBookings' => $this->unreviewedBookings
        ]);
    }
}