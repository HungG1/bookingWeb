<div class="font-sans">
    <!-- Hero Section with Modern Glass Morphism Effect -->
    <div
  class="relative bg-cover bg-center bg-no-repeat h-[600px]"
  style="background-image: url('{{ asset('images/download-2.jpg') }}');"
>
  <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-black/40"></div>
  <div class="relative container mx-auto px-4 h-full flex flex-col justify-center items-center">
            <div class="max-w-4xl text-center mb-8">
                <h1 class="text-5xl md:text-6xl font-bold text-white mb-6 leading-tight drop-shadow-lg">
                    Khám phá điểm đến tuyệt vời cùng <span class="text-blue-400">Booking</span>
                </h1>
                <p class="text-xl md:text-2xl text-white/90 text-center max-w-2xl mx-auto">
                    So sánh giá từ hàng nghìn trang web và tìm ưu đãi tốt nhất cho chuyến đi của bạn
                </p>
            </div>
            
            <!-- Search Bar with Floating Effect -->
            <div class="w-full max-w-6xl transform translate-y-12">
                <livewire:components.search-bar />
            </div>
        </div>
    </div>

    <!-- Featured Destinations Carousel -->
    <section class="pt-24 pb-16 bg-gradient-to-b from-gray-50 to-white">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <span class="text-blue-600 font-semibold text-sm uppercase tracking-wider">Khám phá</span>
                    <h2 class="text-3xl font-bold text-gray-900">Điểm đến hấp dẫn</h2>
                </div>
                <a href="{{ route('hotels.list') }}" class="flex items-center text-blue-600 font-medium hover:text-blue-800 transition-colors">
                    Xem tất cả
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
            <livewire:components.popular-destinations />
        </div>
    </section>

    <!-- Promotion Banner with Animated Elements -->
    <livewire:components.promotion-banner />
 

    <!-- Why Choose Us Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <span class="text-blue-600 font-semibold text-sm uppercase tracking-wider">Lợi ích</span>
                <h2 class="text-3xl font-bold text-gray-900 mt-2">Tại sao chọn Booking?</h2>
                <p class="text-gray-600 mt-4 max-w-2xl mx-auto">Chúng tôi cam kết mang đến trải nghiệm đặt phòng tốt nhất cho chuyến đi của bạn</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Giá tốt nhất</h3>
                    <p class="text-gray-600">So sánh hàng nghìn ưu đãi từ các trang đặt phòng để đảm bảo bạn có được mức giá tốt nhất.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Đặt phòng an toàn</h3>
                    <p class="text-gray-600">Thanh toán an toàn, bảo mật và hỗ trợ mọi lúc khi bạn cần giúp đỡ.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Đánh giá chân thực</h3>
                    <p class="text-gray-600">Hàng triệu đánh giá từ khách hàng thực tế giúp bạn đưa ra lựa chọn đúng đắn.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900">Cẩm nang du lịch</h2>
                <a href="{{ route('blog.list') }}" class="text-blue-600 font-medium hover:underline flex items-center">
                    Xem tất cả bài viết
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
    
            <div class="grid grid-cols-4 gap-6">
                {{-- Giả sử biến $latestPosts chứa 4 bài viết mới nhất được truyền từ Controller/Component --}}
                @forelse($latestPosts as $post)
                    <a href="{{ route('blog.detail', $post->slug) }}" class="block">
                        <article class="h-full flex flex-col bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 max-w-sm mx-auto">
                            <div class="h-48 overflow-hidden rounded-t-xl">
                                @if($post->image)
                                    <img
                                        src="{{ asset('storage/' . $post->image) }}"
                                        class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
                                        alt="{{ $post->title }}"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
    
                            <div class="p-6 flex-grow flex flex-col">
                                <h3 class="text-lg font-semibold mb-2 text-gray-900">{{ $post->title }}</h3>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3 flex-grow">
                                    {{ Str::limit(strip_tags($post->content ?? ''), 150) }}
                                </p>
                                <div class="flex items-center text-sm text-blue-600 font-medium mt-auto">
                                    Đọc tiếp
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </article>
                    </a>
                @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path>
                        </svg> {{-- Đóng thẻ svg đúng cách --}}
                        <p class="text-gray-500">Đang cập nhật bài viết...</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>