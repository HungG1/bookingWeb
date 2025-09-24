<div class="bg-white rounded-lg shadow-lg p-6">
    <h2 class="text-2xl font-bold mb-6">Đánh giá từ khách hàng</h2>

    <!-- Rating Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="col-span-1">
            <div class="text-center">
                <div class="text-5xl font-bold mb-2">
                    {{ number_format($hotel->reviews->avg('rating'), 1) }}
                </div>
                <div class="flex justify-center mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= $hotel->reviews->avg('rating') ? 'text-yellow-400' : 'text-gray-300' }}" 
                            fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                <div class="text-gray-500">{{ $hotel->reviews->count() }} đánh giá</div>
            </div>
        </div>
        
        <div class="col-span-2">
            @foreach($ratingBreakdown as $rating => $count)
                <div class="flex items-center mb-2">
                    <div class="w-12 text-sm text-gray-600">{{ $rating }} sao</div>
                    <div class="flex-1 mx-4">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-400 h-2 rounded-full" 
                                style="width: {{ $hotel->reviews->count() > 0 ? ($count / $hotel->reviews->count() * 100) : 0 }}%">
                            </div>
                        </div>
                    </div>
                    <div class="w-12 text-sm text-gray-600">{{ $count }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap items-center justify-between mb-6">
        <div class="flex items-center space-x-4">
            <select wire:model.live="filter" class="rounded-md border-gray-300">
                <option value="all">Tất cả đánh giá</option>
                @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}">{{ $i }} sao</option>
                @endfor
            </select>
            
            <select wire:model.live="sortBy" class="rounded-md border-gray-300">
                <option value="newest">Mới nhất</option>
                <option value="highest">Điểm cao nhất</option>
                <option value="lowest">Điểm thấp nhất</option>
            </select>
        </div>
    </div>

    <!-- Reviews List -->
    <div class="space-y-6">
        @forelse($reviews as $review)
            <div class="border-b border-gray-200 last:border-0 pb-6 last:pb-0">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <div class="flex items-center mb-2">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                         fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="ml-2 text-gray-600">{{ $review->title }}</span>
                        </div>
                        <p class="text-gray-700">{{ $review->comment }}</p>
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ $review->created_at->diffForHumans() }}
                    </div>
                </div>
                <div class="flex items-center text-sm text-gray-500">
                    <span class="font-medium">{{ $review->user->name }}</span>
                    <span class="mx-2">•</span>
                    <span>Đã ở {{ $review->booking->check_out_date->diffInDays($review->booking->check_in_date) }} đêm</span>
                </div>

                @if($review->admin_reply)
                    <div class="mt-4 pl-4 border-l-4 border-blue-500">
                        <div class="text-sm text-gray-600">
                            <span class="font-medium">Phản hồi từ khách sạn:</span>
                            <p class="mt-1">{{ $review->admin_reply }}</p>
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-8 text-gray-500">
                Chưa có đánh giá nào
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($reviews->hasPages())
        <div class="mt-6">
            {{ $reviews->links() }}
        </div>
    @endif
</div>