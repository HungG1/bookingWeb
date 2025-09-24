{{-- livewire/components/user-reviews.blade.php --}}
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    {{-- ... (Tiêu đề, Flash Messages giữ nguyên) ... --}}
     <div class="mb-6">
         <h1 class="text-2xl font-bold text-gray-900">Đánh giá của tôi</h1>
         <p class="mt-1 text-sm text-gray-600">Quản lý và chia sẻ trải nghiệm của bạn</p>
     </div>
     @if (session()->has('message')) <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md shadow-sm">{{ session('message') }}</div> @endif
     @if (session()->has('error')) <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md shadow-sm">{{ session('error') }}</div> @endif


    {{-- Pending Reviews Section --}}
    @if($this->unreviewedBookings->isNotEmpty()) {{-- Sử dụng $this->unreviewedBookings --}}
        <div class="bg-white shadow rounded-lg p-4 sm:p-6 mb-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Viết đánh giá cho các chuyến đi gần đây</h2>
            <ul role="list" class="divide-y divide-gray-200">
                 @foreach($this->unreviewedBookings as $booking) {{-- Sử dụng $this->unreviewedBookings --}}
                    <li class="py-4 flex flex-wrap items-center justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $booking->hotel->name ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-500 truncate">Phòng: {{ $booking->room->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">Ngày trả phòng: {{ $booking->check_out_date ? $booking->check_out_date->format('d/m/Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            {{-- SỬA NÚT VIẾT ĐÁNH GIÁ: Gọi phương thức Livewire --}}
                            <button type="button" wire:click="showReviewForm({{ $booking->id }})"
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Viết đánh giá
                            </button>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ... (Search bar giữ nguyên) ... --}}
    <div class="bg-white shadow rounded-lg p-4 mb-6">
        <label for="reviewSearch" class="sr-only">Tìm kiếm đánh giá</label>
         <div class="relative">
             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg></div>
             <input type="text" id="reviewSearch" wire:model.live.debounce.300ms="searchTerm" placeholder="Tìm theo tên khách sạn, tiêu đề, nội dung..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
         </div>
    </div>


    {{-- Reviews List --}}
    <div class="bg-white shadow rounded-lg overflow-hidden">
         {{-- ... (Table thead giữ nguyên) ... --}}
        <table class="min-w-full divide-y divide-gray-200">
         <thead class="bg-gray-50"><tr><th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách sạn</th><th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đánh giá</th><th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày</th><th scope="col" class="relative px-6 py-3"><span class="sr-only">Thao tác</span></th></tr></thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($reviews as $review)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div class="font-medium text-gray-900">{{ $review->hotel->name ?? 'N/A' }}</div>
                         {{-- Hiển thị thêm tên phòng nếu muốn và có booking relation --}}
                         {{-- <div class="text-gray-500 text-xs">Phòng: {{ $review->booking->room->name ?? 'N/A' }}</div> --}}
                    </td>
                    <td class="px-6 py-4">
                         <div class="flex items-center mb-1">
                             @for ($i = 1; $i <= 5; $i++)
                                 <svg class="h-4 w-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }} fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                             @endfor
                             <span class="ml-1 text-xs text-gray-500">({{ $review->rating }}/5)</span>
                         </div>
                         <div class="text-sm font-medium text-gray-900">{{ $review->title }}</div>
                         <div class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $review->content }}</div>
                    </td>
                     <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $review->created_at->diffForHumans() }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                         {{-- Nút sửa đánh giá (Cần route/component riêng) --}}
                        {{-- <a href="{{ route('reviews.edit', $review->id) }}" class="text-indigo-600 hover:text-indigo-900">Sửa</a> --}}
                        <button wire:click="confirmDelete({{ $review->id }})" class="text-red-600 hover:text-red-900">Xóa</button>
                    </td>
                </tr>
            @empty
               {{-- ... (<tr> không có đánh giá) ... --}}
                <tr><td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500">Bạn chưa có đánh giá nào.</td></tr>
            @endforelse
        </tbody>
        </table>
         <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $reviews->links() }}
        </div>
    </div>

    {{-- Modal Xóa Review (giữ nguyên) --}}
    @if($showDeleteModal)
        {{-- ... (Code modal xóa giữ nguyên) ... --}}
         <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true"><div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"><div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeModal"></div><span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span><div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"><div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4"><div class="sm:flex sm:items-start"><div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10"><svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg></div><div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left"><h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Xác nhận xóa</h3><div class="mt-2"><p class="text-sm text-gray-500">Bạn chắc chắn muốn xóa đánh giá này?</p></div></div></div></div><div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"><button wire:click="deleteReview" wire:loading.attr="disabled" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">Xác nhận</button><button wire:click="closeModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Hủy bỏ</button></div></div></div></div>
    @endif

     {{-- <<< THÊM MODAL FORM ĐÁNH GIÁ >>> --}}
     @if($showReviewForm && $bookingToReview)
        <div class="fixed z-20 inset-0 overflow-y-auto" aria-labelledby="modal-title-review" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                 <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>
                 <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                 <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                     {{-- Form bên trong modal --}}
                     <form wire:submit.prevent="submitReview">
                         <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                             <div class="sm:flex sm:items-start">
                                 <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                     <h3 class="text-lg leading-6 font-medium text-gray-900 mb-1" id="modal-title-review">
                                         Viết đánh giá cho {{ $bookingToReview->hotel->name ?? 'khách sạn' }}
                                     </h3>
                                      <p class="text-sm text-gray-500 mb-4">Chia sẻ trải nghiệm của bạn về đặt phòng #{{ $bookingToReview->id }}</p>

                                       {{-- Hiển thị lỗi chung của form review --}}
                                     @if(session()->has('review_form_error'))
                                         <p class="text-sm text-red-600 mb-3">{{ session('review_form_error') }}</p>
                                     @endif

                                     {{-- Rating --}}
                                     <div class="mb-4">
                                         <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">Điểm đánh giá *</label>
                                         <div class="flex space-x-1">
                                             @for ($i = 1; $i <= 5; $i++)
                                                 <button type="button" wire:click="$set('rating', {{ $i }})"
                                                         class="{{ $rating >= $i ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-500">
                                                     <svg class="h-6 w-6 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                                 </button>
                                             @endfor
                                         </div>
                                          @error('rating') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                     </div>

                                     {{-- Title --}}
                                     <div class="mb-4">
                                         <label for="reviewTitle" class="block text-sm font-medium text-gray-700">Tiêu đề *</label>
                                         <input type="text" id="reviewTitle" wire:model.lazy="reviewTitle" maxlength="100"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('reviewTitle') border-red-500 @enderror">
                                         @error('reviewTitle') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                     </div>

                                      {{-- Content --}}
                                     <div>
                                         <label for="reviewContent" class="block text-sm font-medium text-gray-700">Nội dung đánh giá *</label>
                                         <textarea id="reviewContent" wire:model.lazy="reviewContent" rows="4" maxlength="1000"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('reviewContent') border-red-500 @enderror"></textarea>
                                         @error('reviewContent') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                     </div>
                                 </div>
                             </div>
                         </div>
                         <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                             <button type="submit" wire:loading.attr="disabled" wire:target="submitReview"
                                     class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                                 Gửi đánh giá
                             </button>
                             <button wire:click="closeModal" type="button"
                                     class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                 Hủy bỏ
                             </button>
                         </div>
                     </form>
                 </div>
            </div>
        </div>
    @endif
    {{-- <<< KẾT THÚC MODAL FORM ĐÁNH GIÁ >>> --}}

</div>