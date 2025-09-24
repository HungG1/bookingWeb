<div class="bg-white rounded-lg shadow-lg p-6 mb-8">
    <h2 class="text-2xl font-bold mb-6">Phòng có sẵn</h2>
    
    @if(count($availableRooms) > 0)
        @foreach($availableRooms as $roomData)
            <div class="border-b border-gray-200 last:border-0 pb-6 mb-6 last:pb-0 last:mb-0">
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="md:w-1/3">
                        @if($roomData['room']->images && count($roomData['room']->images) > 0)
                            <img src="{{ $roomData['room']->images[0] }}" 
                                 alt="{{ $roomData['room']->room_type_name }}" 
                                 class="w-full h-48 object-cover rounded-lg">
                        @endif
                    </div>
                    <div class="md:w-2/3">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-bold mb-2">{{ $roomData['room']->room_type_name }}</h3>
                                <p class="text-gray-600">{{ $roomData['room']->description }}</p>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-blue-600">
                                    {{ number_format($roomData['price_per_night']) }}đ
                                </div>
                                <div class="text-gray-500 text-sm">mỗi đêm</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-user-friends text-gray-400 mr-2"></i>
                                <span>Tối đa {{ $roomData['room']->max_occupancy }} người</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-bed text-gray-400 mr-2"></i>
                                <span>{{ $roomData['room']->number_of_rooms }} phòng có sẵn</span>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach($roomData['room']->amenities as $amenity)
                                <span class="bg-gray-100 text-gray-800 text-sm px-3 py-1 rounded-full">
                                    {{ $amenity->name }}
                                </span>
                            @endforeach
                        </div>

                        <button wire:click="selectRoom({{ $roomData['room']->id }})"
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                            Chọn phòng này
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-bed text-4xl mb-4"></i>
            <p>Không có phòng phù hợp với tiêu chí tìm kiếm của bạn</p>
        </div>
    @endif
</div>