{{-- hotel-detail.blade.php --}}
@php use Carbon\Carbon; @endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- 1. Tên, Địa chỉ, Đánh giá --}}
    <div class="mb-4 md:mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">{{ $hotel->name }}</h1>
        <div class="flex flex-wrap items-center text-xs sm:text-sm text-gray-600 gap-x-4 gap-y-1">
            {{-- Star Rating --}}
            <div class="flex items-center flex-shrink-0">
                 @for($i = 1; $i <= 5; $i++)
                    <svg class="w-4 h-4 {{ $i <= $hotel->star_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"> <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/> </svg>
                 @endfor
                 <span class="ml-1.5 font-medium text-gray-700">{{ $hotel->star_rating }} sao</span>
            </div>
            {{-- Address --}}
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-1.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span>{{ $hotel->address }}</span>
            </div>
        </div>
    </div>

    {{-- 2. Image Gallery Grid --}}
    @if($hotel->images && count($hotel->images) > 0)
        @php
            $totalImages = count($hotel->images);
            $displayImages = array_slice($hotel->images, 0, 5); // Lấy tối đa 5 ảnh đầu tiên
            $remainingImages = $totalImages - count($displayImages);
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-4 md:grid-rows-2 gap-2 h-64 md:h-96 mb-8 rounded-lg overflow-hidden">
            {{-- Ảnh lớn nhất (ô đầu tiên) --}}
            @if(isset($displayImages[0]))
                <div class="col-span-2 row-span-2">
                    <img src="{{ asset('storage/' . $displayImages[0]) }}" alt="{{ $hotel->name }} image 1"
                         class="w-full h-full object-cover cursor-pointer hover:opacity-90 transition-opacity"
                         onclick="openImageModal('{{ asset('storage/' . $displayImages[0]) }}')">
                </div>
            @endif

            {{-- Các ảnh nhỏ còn lại --}}
            @foreach(array_slice($displayImages, 1) as $index => $imagePath)
                 @if($index < 4) {{-- Hiển thị tối đa 4 ảnh nhỏ (bao gồm cả ô overlay nếu có) --}}
                    {{-- Ô ảnh nhỏ bình thường --}}
                    <div class="relative col-span-1 row-span-1 {{ $index >= 2 ? 'hidden md:block' : '' }}"> {{-- Ẩn ảnh thứ 4, 5 trên mobile --}}
                        <img src="{{ asset('storage/' . $imagePath) }}" alt="{{ $hotel->name }} image {{ $index + 2 }}"
                             class="w-full h-full object-cover cursor-pointer hover:opacity-90 transition-opacity"
                             onclick="openImageModal('{{ asset('storage/' . $imagePath) }}')">

                         {{-- Overlay cho ảnh cuối cùng nếu còn nhiều ảnh khác --}}
                         @if($index === 3 && $remainingImages > 0)
                            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center text-white text-lg md:text-xl font-bold cursor-pointer hover:bg-opacity-60 transition-opacity"
                                 onclick="openImageModal('{{ asset('storage/' . $displayImages[0]) }}')"> {{-- Mở modal từ ảnh đầu tiên --}}
                                +{{ $remainingImages }}
                            </div>
                         @endif
                    </div>
                 @endif
            @endforeach

             {{-- Placeholder nếu có ít hơn 5 ảnh --}}
            @if($totalImages < 5)
                 @for ($i = $totalImages; $i < 5; $i++)
                      @if($i > 0 && $i < 3) {{-- Chỉ cần placeholder cho ảnh 2, 3 nếu thiếu --}}
                          <div class="col-span-1 row-span-1 bg-gray-100 rounded {{ $i >= 2 ? 'hidden md:block' : '' }}"></div>
                     @elseif($i >= 3) {{-- Placeholder cho ảnh 4, 5 --}}
                         <div class="hidden md:block col-span-1 row-span-1 bg-gray-100 rounded"></div>
                     @endif
                 @endfor
            @endif

        </div>
    @endif

    {{-- 3. Layout chính (2 cột) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Cột trái: Mô tả, Tiện ích, Phòng, Đánh giá --}}
        <div class="lg:col-span-2 space-y-8">

            {{-- Mô tả Khách sạn --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                @if(!empty($hotel->description))
                    <h2 class="text-xl font-bold mb-4">Giới thiệu {{ $hotel->name }}</h2>
                    <div class="prose prose-sm max-w-none text-gray-700 link:text-blue-600">
                        {!! $hotel->description !!}
                    </div>
                @else
                    <p class="text-gray-500">Chưa có mô tả chi tiết cho khách sạn này.</p>
                @endif
            </div>

            {{-- Tiện ích Khách sạn --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Tiện nghi chính</h2>
                @if($hotel->amenities && $hotel->amenities->isNotEmpty())
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-4 gap-y-3">
                        @foreach($hotel->amenities as $amenity)
                            <div class="flex items-center text-gray-700 text-sm">
                                {{-- Icon Placeholder (Thay bằng icon thật nếu có) --}}
                                <svg class="w-4 h-4 mr-2 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>{{ $amenity->name }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                     <p class="text-gray-500 text-sm">Thông tin tiện nghi đang được cập nhật.</p>
                @endif
            </div>

            {{-- Phòng có sẵn & Form tìm kiếm --}}
            <div class="bg-white rounded-lg shadow-md p-6" id="available-rooms">
                <h2 class="text-xl font-bold mb-6">Chọn phòng phù hợp</h2>
                {{-- Form tìm kiếm --}}
                 <div class="bg-blue-50 rounded-lg p-4 mb-6 border border-blue-100">
                     <form wire:submit.prevent="updateSearch" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-3 items-end">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Ngày nhận phòng</label>
                            <input type="date" wire:model.live="checkInDate" min="{{ date('Y-m-d') }}" class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('checkInDate') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Ngày trả phòng</label>
                            <input type="date" wire:model.live="checkOutDate" min="{{ $checkInDate ? Carbon::parse($checkInDate)->addDay()->format('Y-m-d') : date('Y-m-d', strtotime('+1 day')) }}" class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('checkOutDate') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Người lớn</label>
                            <select wire:model.live="adults" class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                                @for($i = 1; $i <= 10; $i++) <option value="{{ $i }}">{{ $i }}</option> @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Trẻ em</label>
                            <select wire:model.live="children" class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                                @for($i = 0; $i <= 5; $i++) <option value="{{ $i }}">{{ $i }}</option> @endfor
                           </select>
                        </div>
                        <div class="xl:col-start-5"> 
                            <label class="block text-xs font-medium text-gray-700 mb-1">Số phòng</label>
                            <select wire:model.live="rooms" class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                                @for($i = 1; $i <= 5; $i++) <option value="{{ $i }}">{{ $i }}</option> @endfor
                            </select>
                        </div>
                         {{-- Nút tìm lại nếu cần --}}
                         {{-- <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Cập nhật</button> --}}
                    </form>
                </div>
                {{-- End Form tìm kiếm --}}

                {{-- Danh sách phòng --}}
                <div class="space-y-6">
                    @if($availableRooms->isNotEmpty())
                        @foreach($availableRooms as $roomData)
                            <div wire:click.prevent="viewRoomDetails({{ $roomData['id'] }})"
                                class="border rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-all duration-200 cursor-pointer group">
                                <div class="flex flex-col md:flex-row">
                                    {{-- Ảnh phòng --}}
                                    <div class="md:w-1/3 flex-shrink-0 bg-gray-100 overflow-hidden">
                                        @if($roomData['room']->images && count($roomData['room']->images) > 0)
                                            <img src="{{ asset('storage/' . $roomData['room']->images[0]) }}"
                                                alt="{{ $roomData['room']->room_type_name }}"
                                                class="w-full h-48 md:h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            {{-- Placeholder Image --}}
                                            <div class="w-full h-48 md:h-full flex items-center justify-center">
                                                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    {{-- Thông tin phòng --}}
                                    <div class="p-4 md:p-6 flex-grow flex flex-col justify-between">
                                        {{-- ... (Hiển thị tên, occupancy, amenities, giá, nút chọn phòng như cũ) ... --}}
                                        <div>
                                            <h3 class="text-lg font-semibold mb-1 text-gray-800 group-hover:text-blue-600 transition-colors">{{ $roomData['room']->room_type_name }}</h3>
                                            <div class="text-xs text-gray-500 mb-3">Tối đa {{ $roomData['room']->max_occupancy }} người lớn</div>
                                            {{-- Tiện ích (giới hạn) --}}
                                            <div class="flex flex-wrap gap-x-3 gap-y-1 mb-3">
                                                @foreach($roomData['room']->amenities->take(4) as $amenity)
                                                    <span class="inline-flex items-center text-xs text-gray-600" title="{{ $amenity->name }}">
                                                        {{-- Icon tiện ích (nếu có) --}}
                                                        <svg class="w-3 h-3 mr-1 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                        {{ Str::limit($amenity->name, 15) }}
                                                    </span>
                                                @endforeach
                                                @if($roomData['room']->amenities->count() > 4)
                                                    <span class="text-xs text-blue-600 cursor-pointer hover:underline" title="Xem thêm tiện nghi">+{{ $roomData['room']->amenities->count() - 4 }}</span>
                                                @endif
                                            </div>
                                            @if($roomData['room']->description)
                                            <p class="text-xs text-gray-600 mb-3 line-clamp-2">{{ Str::limit(strip_tags($roomData['room']->description), 120) }}</p>
                                            @endif
                                            {{-- Link xem chi tiết (thay thế cho click cả card nếu muốn) --}}
                                            {{-- <button wire:click.stop="viewRoomDetails({{ $roomData['id'] }})" class="text-xs text-blue-600 hover:underline font-medium">Xem chi tiết phòng</button> --}}
                                        </div>
                                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mt-4">
                                            <div class="text-left mb-3 sm:mb-0">
                                                <div class="text-lg font-bold text-red-600">{{ number_format($roomData['price_per_night']) }}đ <span class="text-xs font-normal text-gray-500">/đêm</span></div>
                                                @if($checkInDate && $checkOutDate && ($nights = Carbon::parse($checkInDate)->diffInDays($checkOutDate)) > 0)
                                                    <div class="text-xs text-gray-500">
                                                        Tổng {{ $nights }} đêm:
                                                        <span class="font-semibold text-gray-800">{{ number_format($roomData['total_price']) }}đ</span>
                                                        <span class="text-gray-400">(chưa gồm thuế, phí)</span>
                                                    </div>
                                                @endif
                                            </div>
                                            {{-- Nút Chọn Phòng Chính --}}
                                            <button wire:click.stop="selectRoom({{ $roomData['id'] }})" {{-- Dùng wire:click.stop để không kích hoạt modal --}}
                                                    class="w-full sm:w-auto px-5 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 {{ $selectedRoomId == $roomData['id'] ? 'bg-green-600 hover:bg-green-700 focus:ring-green-500' : '' }}">
                                                {{ $selectedRoomId == $roomData['id'] ? 'Đã chọn' : 'Chọn phòng' }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-8 text-gray-500 border border-dashed rounded-lg">
                            Không tìm thấy phòng trống phù hợp với lựa chọn của bạn. <br> Vui lòng thử thay đổi ngày hoặc số lượng khách/phòng.
                        </div>
                    @endif
                </div>
                {{-- End Danh sách phòng --}}
            </div>

            {{-- Đánh giá Khách hàng --}}
            <div class="bg-white rounded-lg shadow-md p-6" id="reviews">
                 <h2 class="text-xl font-bold mb-6">Đánh giá từ khách hàng ({{ $reviewCount }})</h2>
                 {{-- Phần tóm tắt điểm và form review --}}
                 <div class="flex flex-col md:flex-row gap-8 mb-8">
                     {{-- Phân bố điểm --}}
                     <div class="flex-shrink-0 md:w-1/3">
                          <div class="flex items-center mb-4">
                             <div class="text-4xl font-bold text-gray-800 mr-2">{{ number_format($averageRating,1) }}<span class="text-2xl text-gray-400">/5</span></div>
                             <div class="text-sm text-gray-500 leading-tight">Tuyệt hảo <br> ({{ $reviewCount }} đánh giá)</div>
                          </div>
                          {{-- Thanh % điểm --}}
                          <div class="space-y-1">
                             @foreach($ratingBreakdown as $star => $count)
                                 <div class="flex items-center text-xs">
                                     <span class="w-10 text-gray-500">{{ $star }} sao</span>
                                     <div class="flex-1 mx-2 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                         <div class="h-full bg-yellow-400" style="width: {{ $reviewCount > 0 ? ($count/$reviewCount*100) : 0 }}%"></div>
                                     </div>
                                     <span class="w-6 text-right text-gray-500">{{ $count }}</span>
                                 </div>
                             @endforeach
                         </div>
                     </div>
                     {{-- Form viết review --}}
                     <div class="flex-grow">
                         @if($bookingToReview)
                              <livewire:components.review-form :hotel="$hotel" :booking="$bookingToReview" key="review-form-{{ $bookingToReview->id }}" />
                         @else
                             <div class="h-full flex items-center justify-center bg-gray-50 p-6 rounded-lg border border-dashed">
                                 <p class="text-center text-gray-500 text-sm">Bạn cần hoàn thành chuyến đi tại khách sạn này để viết đánh giá.</p>
                             </div>
                         @endif
                     </div>
                 </div>
                 {{-- Đường kẻ ngang --}}
                 <hr class="border-gray-200 mb-8">
                 {{-- Danh sách các review đã có --}}
                 <div class="space-y-6">
                     @forelse($hotel->reviews as $review)
                         <div class="flex space-x-4">
                             {{-- Avatar --}}
                             <img src="{{ $review->user->avatar ? asset('storage/'.$review->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($review->user->name).'&background=EBF4FF&color=76A9FA&size=48' }}"
                                  alt="{{ $review->user->name }}"
                                  class="w-10 h-10 rounded-full object-cover flex-shrink-0 mt-1"/>
                             {{-- Nội dung review --}}
                             <div class="flex-1">
                                 <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-1">
                                      <div>
                                          <span class="font-semibold text-gray-800 text-sm">{{ $review->user->name }}</span>
                                          {{-- Có thể thêm thông tin loại phòng đã ở nếu muốn --}}
                                      </div>
                                      <div class="text-xs text-gray-400 mt-1 sm:mt-0">{{ $review->created_at->isoFormat('DD/MM/YYYY') }}</div>
                                  </div>
                                 {{-- Sao đánh giá --}}
                                 <div class="flex mb-2">
                                      @for($i = 1; $i <= 5; $i++)
                                         <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }} mr-0.5" fill="currentColor" viewBox="0 0 20 20"> <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/> </svg>
                                      @endfor
                                  </div>
                                 {{-- Tiêu đề & Nội dung comment --}}
                                 @if(!empty($review->title))
                                      <h4 class="font-semibold text-gray-700 mb-1 text-sm">{{ $review->title }}</h4>
                                 @endif
                                 <p class="text-gray-600 text-sm mb-3">{{ $review->comment }}</p>
                                 {{-- Phản hồi từ admin --}}
                                 @if($review->admin_reply)
                                      <div class="mt-3 p-3 bg-gray-100 border-l-4 border-blue-300 rounded-r-md">
                                         <h5 class="text-xs font-semibold text-blue-700 mb-1">Phản hồi từ khách sạn</h5>
                                         <p class="text-gray-600 text-sm">{{ $review->admin_reply }}</p>
                                     </div>
                                 @endif
                             </div>
                        </div>
                     @empty
                         <p class="text-center text-gray-500 py-6">Hiện chưa có đánh giá nào.</p>
                     @endforelse
                 </div>
            </div>

        </div> {{-- End Cột trái --}}

        {{-- Cột phải: Tóm tắt đặt phòng, Bản đồ --}}
        <div class="lg:col-span-1">
            <div class="sticky top-24 space-y-6"> {{-- Class sticky để giữ cố định khi cuộn --}}

                 {{-- Khung tóm tắt đặt phòng --}}
                 <div class="bg-white rounded-lg shadow-lg p-6 border border-blue-200">
                     @if($selectedRoomId && ($priceDetails = $this->calculatePrice()))
                          @php
                              $selectedRoomData = $availableRooms->firstWhere('id', $selectedRoomId);
                              $nights = Carbon::parse($checkInDate)->diffInDays($checkOutDate);
                          @endphp
                         <h3 class="text-lg font-semibold mb-4">Yêu cầu đặt phòng</h3>
                         {{-- Thông tin cơ bản --}}
                         <div class="space-y-2 text-sm mb-4 border-b border-gray-200 pb-4">
                             <div class="flex justify-between"><span>Nhận phòng:</span> <span class="font-medium text-right">{{ Carbon::parse($checkInDate)->isoFormat('dddd, DD/MM/YYYY') }}</span></div>
                             <div class="flex justify-between"><span>Trả phòng:</span> <span class="font-medium text-right">{{ Carbon::parse($checkOutDate)->isoFormat('dddd, DD/MM/YYYY') }}</span></div>
                             <div class="flex justify-between"><span>Số đêm:</span> <span class="font-medium text-right">{{ $nights }}</span></div>
                         </div>
                          {{-- Thông tin phòng đã chọn --}}
                         <div class="space-y-2 text-sm mb-4 border-b border-gray-200 pb-4">
                              @if($selectedRoomData)
                             <div class="flex justify-between">
                                 <span>Loại phòng:</span>
                                 <span class="font-medium text-right">{{ $selectedRoomData['room']->room_type_name }}</span>
                             </div>
                             @endif
                             <div class="flex justify-between">
                                 <span>Khách:</span>
                                 <span class="font-medium text-right">{{ $adults }} người lớn{{ $children > 0 ? ', '.$children.' trẻ em' : '' }}</span>
                            </div>
                             <div class="flex justify-between">
                                 <span>Số phòng:</span>
                                 <span class="font-medium text-right">{{ $rooms }}</span>
                            </div>
                         </div>

                          {{-- Chi tiết giá --}}
                         <div class="space-y-1 text-sm mb-4 border-b border-gray-200 pb-4">
                             <div class="flex justify-between">
                                 <span>Giá {{ $nights }} đêm (x{{ $rooms }} phòng)</span>
                                 <span>{{ number_format($priceDetails['basePrice']) }}đ</span>
                             </div>
                             <div class="flex justify-between">
                                 <span>Thuế (VAT 8%)</span>
                                 <span>{{ number_format($priceDetails['tax']) }}đ</span>
                              </div>
                             <div class="flex justify-between">
                                 <span>Phí dịch vụ (10%)</span>
                                 <span>{{ number_format($priceDetails['serviceFee']) }}đ</span>
                             </div>
                             @if($priceDetails['discount'] > 0)
                             <div class="flex justify-between text-green-600">
                                 <span>Giảm giá khuyến mãi</span>
                                 <span>- {{ number_format($priceDetails['discount']) }}đ</span>
                             </div>
                              @endif
                         </div>
                          {{-- Mã giảm giá --}}
                         <div class="mb-4">
                             {{-- <label for="promoCodeSidebar" class="block text-sm font-medium text-gray-700 mb-1">Mã giảm giá</label> --}}
                             <div class="flex gap-2">
                                 <input type="text" wire:model.lazy="promoCode" id="promoCodeSidebar" placeholder="Nhập mã giảm giá (nếu có)" class="flex-grow rounded-md border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                                 <button wire:click="applyPromoCode" wire:loading.attr="disabled" wire:target="applyPromoCode" class="px-3 py-1.5 bg-gray-200 text-gray-700 text-xs font-medium rounded-md hover:bg-gray-300 disabled:opacity-50">
                                     <span wire:loading wire:target="applyPromoCode">...</span>
                                     <span wire:loading.remove wire:target="applyPromoCode">Áp dụng</span>
                                </button>
                             </div>
                         </div>

                          {{-- Tổng cộng --}}
                         <div class="flex justify-between items-center mb-5">
                             <span class="font-semibold text-gray-800">Tổng thanh toán</span>
                             <span class="text-xl font-bold text-red-600">{{ number_format($priceDetails['finalPrice']) }}đ</span>
                         </div>
                         {{-- Nút Đặt phòng --}}
                         <button wire:click="proceedToBooking" wire:loading.attr="disabled"
                                 class="w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-70">
                             <span wire:loading wire:target="proceedToBooking">Đang xử lý...</span>
                             <span wire:loading.remove wire:target="proceedToBooking">Tiến hành đặt phòng</span>
                         </button>

                     @else
                         {{-- Thông báo khi chưa chọn phòng --}}
                         <h3 class="text-lg font-semibold mb-2">Vui lòng chọn phòng</h3>
                         <p class="text-sm text-gray-500 mb-4">Chọn loại phòng và số lượng phòng bạn muốn đặt từ danh sách bên trái để xem chi tiết giá và tiếp tục.</p>
                         <a href="#available-rooms" class="inline-block px-4 py-2 bg-blue-100 text-blue-700 text-sm font-medium rounded-md hover:bg-blue-200 transition-colors">
                             Xem phòng có sẵn
                         </a>
                     @endif
                 </div>                
                </div>
            </div>

            <!-- Room Details Modal -->
            <div x-data="{ isOpen: false }" 
            x-show="isOpen" 
            x-on:open-room-modal.window="isOpen = true" 
            x-on:close-room-modal.window="isOpen = false"
            x-on:keydown.escape.window="isOpen = false"
            class="fixed inset-0 z-50 overflow-y-auto" 
            style="display: none;">

            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

            <!-- Modal Container -->
            <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative max-w-4xl w-full bg-white rounded-lg shadow-xl overflow-hidden max-h-[90vh]">
                <!-- Close Button -->
                <button @click="isOpen = false" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 z-10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                
                <!-- Modal Content -->
                <div class="overflow-y-auto max-h-[90vh]">
                    @if($viewingRoom)
                    <!-- Image Gallery Principal (Sử dụng Alpine.js) -->
                    <div x-data="{ activeImage: 0, images: {{ json_encode($viewingRoom->images ?? []) }} }" class="space-y-2">
                        <!-- Ảnh chính -->
                        <div class="relative h-64 sm:h-80 bg-gray-100 rounded overflow-hidden">
                            @if($viewingRoom->images && count($viewingRoom->images) > 0)
                                <template x-for="(image, index) in images" :key="index">
                                    <img :src="'{{ asset('storage/') }}/' + image" 
                                        alt="{{ $viewingRoom->room_type_name }}"
                                        class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300"
                                        :class="{ 'opacity-100': activeImage === index, 'opacity-0': activeImage !== index }">
                                </template>
                                
                                <!-- Navigation arrows if multiple images -->
                                @if(count($viewingRoom->images) > 1)
                                    <button @click="activeImage = (activeImage - 1 + images.length) % images.length" 
                                            class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-50 rounded-full p-1 hover:bg-opacity-75">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                    </button>
                                    <button @click="activeImage = (activeImage + 1) % images.length" 
                                            class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-50 rounded-full p-1 hover:bg-opacity-75">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>
                                @endif
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Image Thumbnails Grid -->
                        @if($viewingRoom->images && count($viewingRoom->images) > 0)
                            <div class="grid grid-cols-5 sm:grid-cols-8 gap-1">
                                @foreach($viewingRoom->images as $index => $image)
                                    <div @click="activeImage = {{ $index }}"
                                        class="cursor-pointer border-2 overflow-hidden rounded transition-all"
                                        :class="{ 'border-blue-500': activeImage === {{ $index }}, 'border-transparent': activeImage !== {{ $index }} }">
                                        <img src="{{ asset('storage/' . $image) }}" 
                                            alt="{{ $viewingRoom->room_type_name }} thumbnail {{ $index+1 }}" 
                                            class="w-full h-14 object-cover">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                            
                        <!-- Room Details Content -->
                        <div class="p-6">
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $viewingRoom->room_type_name }}</h2>
                            
                            <!-- Room specs -->
                            <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-4">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <span>{{ $viewingRoom->size ?? '22' }} m²</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span>Tối đa {{ $viewingRoom->max_occupancy }} người</span>
                                </div>
                            </div>
                            
                            <!-- Room description -->
                            @if($viewingRoom->description)
                                <div class="prose prose-sm max-w-none text-gray-700 mb-6">
                                    {!! $viewingRoom->description !!}
                                </div>
                            @endif
                            
                            <!-- Room amenities -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-3">Tiện nghi phòng</h3>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-4 gap-y-2">
                                    @foreach($viewingRoom->amenities as $amenity)
                                        <div class="flex items-center text-gray-700 text-sm">
                                            <svg class="w-4 h-4 mr-2 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span>{{ $amenity->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Price and booking -->
                            @php
                                $checkInDate = $checkInDate ?? now()->addDay()->format('Y-m-d'); // Đảm bảo có giá trị mặc định
                                $checkOutDate = $checkOutDate ?? now()->addDays(2)->format('Y-m-d'); // Đảm bảo có giá trị mặc định

                                $nights = 0;
                                if ($checkInDate && $checkOutDate && Carbon::parse($checkInDate)->lt(Carbon::parse($checkOutDate)) ) {
                                    $nights = Carbon::parse($checkInDate)->diffInDays(Carbon::parse($checkOutDate));
                                }
                                $nights = max(1, $nights); 

                                $roomDataForPrice = $availableRooms->firstWhere('id', $viewingRoom->id);
                                $pricePerNight = $roomDataForPrice ? $roomDataForPrice['price_per_night'] : $viewingRoom->base_price;
                                $totalPrice = $roomDataForPrice ? $roomDataForPrice['total_price'] : ($viewingRoom->base_price * $nights * $this->rooms);
                            @endphp
                                                        
                            <div class="border-t pt-4 mt-6">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                    <div class="mb-4 sm:mb-0">
                                        <div class="text-lg font-bold text-red-600">{{ number_format($pricePerNight) }}đ <span class="text-xs font-normal text-gray-500">/đêm</span></div>
                                        @if($checkInDate && $checkOutDate && $nights > 0)
                                            <div class="text-xs text-gray-500">
                                                Tổng {{ $nights }} đêm:
                                                <span class="font-semibold text-gray-800">{{ number_format($totalPrice) }}đ</span>
                                                <span class="text-gray-400">(chưa gồm thuế, phí)</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            </div>
        </div>
    </div> 
</div> 


