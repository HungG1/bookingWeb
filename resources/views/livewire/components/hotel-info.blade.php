<div class="bg-white rounded-lg shadow-lg p-6 mb-8">
    <div class="mb-6">
        <h2 class="text-2xl font-bold mb-4">Giới thiệu</h2>
        <div class="prose max-w-none">
            <div class="{{ $showFullDescription ? '' : 'line-clamp-3' }}">
                {!! $hotel->description !!}
            </div>
            @if(strlen($hotel->description) > 200)
                <button wire:click="toggleDescription" 
                        class="text-blue-600 hover:text-blue-800 mt-2">
                    {{ $showFullDescription ? 'Thu gọn' : 'Xem thêm' }}
                </button>
            @endif
        </div>
    </div>

    <div class="border-t border-gray-200 pt-6">
        <h3 class="text-lg font-semibold mb-4">Tiện nghi chung</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($hotel->amenities as $amenity)
                <div class="flex items-center">
                    @if($amenity->icon)
                        <i class="{{ $amenity->icon }} text-gray-600 mr-2"></i>
                    @endif
                    <span>{{ $amenity->name }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="border-t border-gray-200 pt-6 mt-6">
        <h3 class="text-lg font-semibold mb-4">Thông tin liên hệ</h3>
        <div class="space-y-2">
            <p class="flex items-center">
                <i class="fas fa-map-marker-alt text-gray-600 mr-2"></i>
                {{ $hotel->address }}
            </p>
            <p class="flex items-center">
                <i class="fas fa-phone text-gray-600 mr-2"></i>
                {{ $hotel->contact_phone }}
            </p>
            <p class="flex items-center">
                <i class="fas fa-envelope text-gray-600 mr-2"></i>
                {{ $hotel->contact_email }}
            </p>
        </div>
    </div>
</div>