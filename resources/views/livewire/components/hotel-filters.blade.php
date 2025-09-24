<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold mb-4">Lọc kết quả</h3>
    
    <!-- Star Rating Filter -->
    <div class="mb-6">
        <h4 class="font-medium mb-2">Xếp hạng sao</h4>
        @for ($i = 5; $i >= 1; $i--)
        <div class="flex items-center mb-2">
            <input type="checkbox" id="star-{{ $i }}" wire:model.live="starRating" value="{{ $i }}" 
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
            <label for="star-{{ $i }}" class="ml-2 text-sm text-gray-700 flex items-center">
                @for ($j = 1; $j <= $i; $j++)
                <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
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
            min: @entangle('priceRange.0').live,
            max: @entangle('priceRange.1').live,
            formatPrice(price) {
                return new Intl.NumberFormat('vi-VN').format(price);
            }
        }">
            <div class="mb-4 text-sm">
                <span x-text="formatPrice(min)"></span> - <span x-text="formatPrice(max)"></span> VNĐ
            </div>
            <div class="px-2">
                <input type="range" x-model="min" min="0" max="5000000" step="100000" 
                    class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                <input type="range" x-model="max" min="0" max="5000000" step="100000" 
                    class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer mt-4">
            </div>
        </div>
    </div>
    
    <!-- Amenities Filter -->
    <div class="mb-6">
        <h4 class="font-medium mb-2">Tiện nghi</h4>
        @foreach ($amenities as $amenity)
        <div class="flex items-center mb-2">
            <input type="checkbox" id="amenity-{{ $amenity->id }}" 
                wire:model.live="selectedAmenities" 
                value="{{ $amenity->id }}" 
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
            <label for="amenity-{{ $amenity->id }}" class="ml-2 text-sm text-gray-700">
                {{ $amenity->name }}
            </label>
        </div>
        @endforeach
    </div>

    <!-- Reset Filters Button -->
    <button wire:click="resetFilters" 
        class="w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-200 transition duration-150 ease-in-out">
        Xóa bộ lọc
    </button>
</div>