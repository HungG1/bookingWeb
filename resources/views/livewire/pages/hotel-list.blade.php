{{-- hotel-list.blade.php --}}
<div class="bg-gray-100 min-h-screen py-8">
    <div class="container mx-auto px-4">
        <!-- Search Bar at Top -->
        <div class="mb-8 bg-white rounded-lg shadow p-4">
            <livewire:components.search-bar 
                :location="$location"
                :checkInDate="$checkIn" 
                :checkOutDate="$checkOut" 
                :adults="$adults"
                :children="$children"
                :rooms="$rooms" 
            />
        </div>
        
        <!-- Main Content -->
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Filters Sidebar -->
            <div class="w-full lg:w-1/4">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Lọc kết quả</h3>
                    
                    <!-- Star Rating Filter -->
                    <div class="mb-6">
                        <h4 class="font-medium mb-2">Xếp hạng sao</h4>
                        @for ($i = 5; $i >= 1; $i--)
                        <div class="flex items-center mb-2">
                            <input type="checkbox" id="star-{{ $i }}" wire:model="starRating" value="{{ $i }}" 
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="star-{{ $i }}" class="ml-2 text-sm text-gray-700">
                                @for ($j = 1; $j <= $i; $j++)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-400 inline" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                @endfor
                            </label>
                        </div>
                        @endfor
                    </div>
                    
                    <!-- Price Range Filter -->
                    <div class="mb-6">
                        <h4 class="font-medium mb-2">Khoảng giá (VNĐ)</h4>
                        <div x-data="{
                            min: @entangle('priceRange.0'),
                            max: @entangle('priceRange.1'),
                            formatPrice(price) {
                                return new Intl.NumberFormat('vi-VN').format(price);
                            }
                        }">
                            <div class="mb-4 text-sm">
                                <span x-text="formatPrice(min)"></span> - <span x-text="formatPrice(max)"></span> VNĐ
                            </div>
                            <div class="px-2">
                                <input type="range" x-model="min" min="0" max="500000000" step="100000" 
                                    class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                <input type="range" x-model="max" min="0" max="500000000" step="100000" 
                                    class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer mt-4">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Amenities Filter -->
                    <div class="mb-6">
                        <h4 class="font-medium mb-2">Tiện nghi</h4>
                        @foreach ($amenities as $amenity)
                        <div class="flex items-center mb-2">
                            <input type="checkbox" id="amenity-{{ $amenity->id }}" wire:model="selectedAmenities" value="{{ $amenity->id }}" 
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="amenity-{{ $amenity->id }}" class="ml-2 text-sm text-gray-700">
                                {{ $amenity->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Hotels List -->
            <div class="w-full lg:w-3/4">
                <!-- Sort Options -->
                <div class="bg-white rounded-lg shadow p-4 mb-6">
                    <div class="flex flex-wrap items-center justify-between">
                        <div class="mb-2 sm:mb-0">
                            <h2 class="text-xl font-semibold">
                                {{ $hotels->total() }} khách sạn được tìm thấy
                                @if($location)
                                    tại "{{ $location }}"
                                @endif
                            </h2>
                        </div>
                        <div class="flex items-center">
                            <label for="sortBy" class="mr-2 text-sm font-medium">Sắp xếp theo:</label>
                            <select id="sortBy" wire:model="sortBy" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2">
                                <option value="recommended">Đề xuất</option>
                                <option value="price_low">Giá: Thấp đến cao</option>
                                <option value="price_high">Giá: Cao đến thấp</option>
                                <option value="rating">Đánh giá cao nhất</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Hotels -->
                <div class="space-y-6">
                    @forelse ($hotels as $hotel)
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="flex flex-col md:flex-row">
                            <div class="w-full md:w-1/3">
                                @if(!empty($hotel->images) && isset($hotel->images[0])) 
                                    <img src="{{ asset('storage/' . $hotel->images[0]) }}" 
                                         alt="{{ $hotel->name }}" 
                                         class="w-full h-full object-cover" style="min-height: 200px;">
                                @else
                                    <div class="w-full h-full bg-gray-300 flex items-center justify-center" style="min-height: 200px;">
                                        <span class="text-gray-500">Không có hình ảnh</span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Hotel Info -->
                            <div class="w-full md:w-2/3 p-6">
                                <div class="flex flex-col h-full justify-between">
                                    <div>
                                        <div class="flex justify-between items-start">
                                            <h3 class="text-xl font-bold mb-2">{{ $hotel->name }}</h3>
                                            <div class="flex">
                                                @if($hotel->star_rating) 
                                                    @for ($i = 1; $i <= $hotel->star_rating; $i++)
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        </svg>
                                                    @endfor
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <p class="text-gray-600 mb-4">{{ $hotel->address }}</p>
                                        
                                        <!-- Amenities -->
                                        <div class="flex flex-wrap gap-2 mb-4">
                                            @if($hotel->amenities) 
                                                @foreach ($hotel->amenities->take(5) as $amenity)
                                                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                                        {{ $amenity->name }}
                                                    </span>
                                                @endforeach
                                                @if($hotel->amenities->count() > 5)
                                                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                                        +{{ $hotel->amenities->count() - 5 }} khác
                                                    </span>
                                                @endif
                                            @endif
                                         </div>
                                        
                                        <!-- Rating -->
                                        @php
                                            $avgRating = $hotel->reviews->avg('rating');
                                            $reviewCount = $hotel->reviews->count();
                                        @endphp
                                        @if($reviewCount > 0)
                                        <div class="flex items-center mb-4">
                                            <div class="flex items-center justify-center bg-blue-600 text-white rounded-lg px-2 py-1 mr-2">
                                                <span class="font-bold">{{ number_format($avgRating, 1) }}</span>
                                            </div>
                                            <div>
                                                <div class="font-medium">{{ $avgRating >= 4.5 ? 'Tuyệt vời' : ($avgRating >= 4 ? 'Rất tốt' : ($avgRating >= 3.5 ? 'Tốt' : ($avgRating >= 3 ? 'Bình thường' : 'Trung bình'))) }}</div>
                                                <div class="text-sm text-gray-500">{{ $reviewCount }} đánh giá</div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between mt-4">
                                        <div>
                                            @if($hotel->rooms->isNotEmpty())
                                                <div class="text-sm text-gray-500">Giá phòng mỗi đêm từ</div>
                                                <div class="text-2xl font-bold text-blue-600">{{ number_format($hotel->rooms->first()->base_price, 0, ',', '.') }} VNĐ</div>
                                            @else
                                               <div class="text-sm text-gray-500">Chưa có thông tin giá phòng</div>
                                            @endif
                                        </div>
                                        <a href="{{ route('hotels.detail', $hotel->id) }}" class="mt-4 sm:mt-0 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-300">
                                            Xem chi tiết
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white rounded-lg shadow p-8 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <h3 class="text-xl font-semibold mb-2">Không tìm thấy khách sạn phù hợp</h3>
                        <p class="text-gray-600 mb-4">Vui lòng thử lại với các bộ lọc khác hoặc mở rộng tìm kiếm</p>
                        <button wire:click="$set('starRating', []); $set('selectedAmenities', []); $set('priceRange', [0, 5000000]);" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-300">
                            Xóa bộ lọc
                        </button>
                    </div>
                    @endforelse
                </div>
                
                <!-- Pagination -->
                <div class="mt-6">
                    {{ $hotels->links() }}
                </div>
            </div>
        </div>
    </div>
</div>