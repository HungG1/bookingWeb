<div class="space-y-6">
    <h2 class="text-2xl font-bold">Chọn phòng</h2>

    @if(count($availableRooms) > 0)
        @foreach($availableRooms as $roomData)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="flex flex-col md:flex-row">
                    <div class="w-full md:w-1/3">
                        @if($roomData['room']->images && count($roomData['room']->images) > 0)
                            <img src="{{ asset('storage/' . $roomData['room']->images[0]) }}" 
                                alt="{{ $roomData['room']->room_type_name }}" 
                                class="w-full h-full object-cover">
                        @endif
                    </div>
                    
                    <div class="w-full md:w-2/3 p-6">
                        <div class="flex flex-col h-full justify-between">
                            <div>
                                <h3 class="text-xl font-bold mb-2">{{ $roomData['room']->room_type_name }}</h3>
                                <p class="text-gray-600 mb-4">{{ $roomData['room']->description }}</p>
                                
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach($roomData['room']->amenities as $amenity)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100">
                                            {{ $amenity->name }}
                                        </span>
                                    @endforeach
                                </div>

                                <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                                    <div>
                                        <span class="font-medium">Sức chứa tối đa:</span> 
                                        {{ $roomData['room']->max_occupancy }} người
                                    </div>
                                    <div>
                                        <span class="font-medium">Diện tích:</span>
                                        {{ $roomData['room']->room_size ?? 'N/A' }} m²
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between mt-4">
                                <div>
                                    <div class="text-gray-600">Giá mỗi đêm từ</div>
                                    <div class="text-2xl font-bold text-blue-600">
                                        {{ number_format($roomData['price_per_night']) }}đ
                                    </div>
                                </div>
                                
                                <button wire:click="selectRoom({{ $roomData['id'] }})" 
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-150 ease-in-out
                                    {{ $selectedRoomId === $roomData['id'] ? 'bg-blue-800' : '' }}">
                                    {{ $selectedRoomId === $roomData['id'] ? 'Đã chọn' : 'Chọn phòng' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        Không tìm thấy phòng phù hợp với yêu cầu của bạn.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>