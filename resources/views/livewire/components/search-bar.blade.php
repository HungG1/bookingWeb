<div class="w-full bg-white rounded-2xl shadow-xl p-6">
    <form wire:submit.prevent="search" 
          class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-[2fr_1fr_1fr_2fr_auto] gap-3 items-center">
        
        <!-- Location Input -->
        <div class="relative h-full group">
            <div class="relative h-full">
                <input type="text" 
                       wire:model="location" 
                       class="w-full h-[56px] pl-12 pr-4 border-2 border-gray-200 rounded-xl 
                              focus:ring-4 focus:ring-blue-100 focus:border-blue-500
                              transition-all duration-200 placeholder-gray-400 text-gray-700"
                       placeholder="Bạn muốn đi đâu?">
                <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500" 
                     fill="none" 
                     stroke="currentColor" 
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" 
                          stroke-linejoin="round" 
                          stroke-width="2" 
                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" 
                          stroke-linejoin="round" 
                          stroke-width="2" 
                          d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>

        <!-- Check-in Date -->
        <div class="h-full">
            <input type="date" 
                   wire:model="checkInDate" 
                   class="w-full h-[56px] px-4 border-2 border-gray-200 rounded-xl 
                          focus:ring-4 focus:ring-blue-100 focus:border-blue-500
                          text-gray-700 cursor-pointer">
        </div>

        <!-- Check-out Date -->
        <div class="h-full">
            <input type="date" 
                   wire:model="checkOutDate" 
                   class="w-full h-[56px] px-4 border-2 border-gray-200 rounded-xl 
                          focus:ring-4 focus:ring-blue-100 focus:border-blue-500
                          text-gray-700 cursor-pointer">
        </div>

        <!-- Guests Selector -->
        <div class="h-full relative" x-data="{ open: false }">
            <button @click="open = !open" 
                    type="button"
                    class="w-full h-[56px] px-4 text-left border-2 border-gray-200 rounded-xl 
                           hover:border-blue-500 focus:border-blue-500 focus:ring-4 focus:ring-blue-100
                           flex items-center justify-between transition-all duration-200">
                <span class="block truncate text-gray-700">
                    {{ $adults }} Người lớn · {{ $children }} Trẻ em · {{ $rooms }} Phòng
                </span>
                <svg class="w-5 h-5 text-gray-400 ml-2" 
                     :class="{ 'rotate-180': open }" 
                     fill="none" 
                     stroke="currentColor" 
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" 
                          stroke-linejoin="round" 
                          stroke-width="2" 
                          d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <!-- Dropdown Content -->
            <div x-show="open" 
                 x-cloak
                 @click.away="open = false"
                 class="absolute z-20 w-full mt-2 bg-white border-2 border-blue-100 rounded-xl 
                        shadow-lg p-4 space-y-4">
                <!-- Adults Counter -->
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-700">Người lớn</p>
                        <p class="text-sm text-gray-400">Từ 13 tuổi trở lên</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" 
                                wire:click="decreaseAdults"
                                class="w-9 h-9 flex items-center justify-center rounded-lg border-2 border-gray-200 
                                       hover:bg-blue-50 hover:border-blue-200 transition-colors">
                            -
                        </button>
                        <span class="w-8 text-center font-medium">{{ $adults }}</span>
                        <button type="button" 
                                wire:click="increaseAdults"
                                class="w-9 h-9 flex items-center justify-center rounded-lg border-2 border-gray-200 
                                       hover:bg-blue-50 hover:border-blue-200 transition-colors">
                            +
                        </button>
                    </div>
                </div>

                <!-- Children Counter -->
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-700">Trẻ em</p>
                        <p class="text-sm text-gray-400">Từ 2-12 tuổi</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" 
                                wire:click="decreaseChildren"
                                class="w-9 h-9 flex items-center justify-center rounded-lg border-2 border-gray-200 
                                       hover:bg-blue-50 hover:border-blue-200 transition-colors">
                            -
                        </button>
                        <span class="w-8 text-center font-medium">{{ $children }}</span>
                        <button type="button" 
                                wire:click="increaseChildren"
                                class="w-9 h-9 flex items-center justify-center rounded-lg border-2 border-gray-200 
                                       hover:bg-blue-50 hover:border-blue-200 transition-colors">
                            +
                        </button>
                    </div>
                </div>

                <!-- Rooms Counter -->
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-700">Phòng</p>
                        <p class="text-sm text-gray-400">Số phòng bạn cần</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" 
                                wire:click="decreaseRooms"
                                class="w-9 h-9 flex items-center justify-center rounded-lg border-2 border-gray-200 
                                       hover:bg-blue-50 hover:border-blue-200 transition-colors">
                            -
                        </button>
                        <span class="w-8 text-center font-medium">{{ $rooms }}</span>
                        <button type="button" 
                                wire:click="increaseRooms"
                                class="w-9 h-9 flex items-center justify-center rounded-lg border-2 border-gray-200 
                                       hover:bg-blue-50 hover:border-blue-200 transition-colors">
                            +
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Button -->
        <div class="h-[56px]">
            <button type="submit" 
                    class="w-full h-full bg-blue-600 hover:bg-blue-700 text-white font-semibold 
                           rounded-xl transition-all duration-300 flex items-center justify-center 
                           gap-2 px-6 shadow-md hover:shadow-lg">
                <svg class="w-5 h-5" 
                     fill="none" 
                     stroke="currentColor" 
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" 
                          stroke-linejoin="round" 
                          stroke-width="2" 
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Tìm kiếm
            </button>
        </div>
    </form>
</div>