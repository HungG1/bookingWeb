{{-- user-dashboard.blade.php --}}
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Sidebar Navigation -->
                <div class="w-full md:w-64 shrink-0">
                    <div class="bg-white rounded-lg shadow">
                        <nav class="space-y-1">
                            <a href="#" wire:click.prevent="$set('activeTab', 'dashboard')" 
                               class="@if($activeTab === 'dashboard') bg-gray-100 text-gray-900 @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif flex items-center px-4 py-3 text-sm font-medium">
                                <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Tổng quan
                            </a>

                            <a href="#" wire:click.prevent="$set('activeTab', 'bookings')"
                               class="@if($activeTab === 'bookings') bg-gray-100 text-gray-900 @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif flex items-center px-4 py-3 text-sm font-medium">
                                <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Đặt phòng
                            </a>

                            <a href="#" wire:click.prevent="$set('activeTab', 'reviews')"
                               class="@if($activeTab === 'reviews') bg-gray-100 text-gray-900 @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif flex items-center px-4 py-3 text-sm font-medium">
                                <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                                Đánh giá
                            </a>

                            <a href="#" wire:click.prevent="$set('activeTab', 'profile')"
                               class="@if($activeTab === 'profile') bg-gray-100 text-gray-900 @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif flex items-center px-4 py-3 text-sm font-medium">
                                <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Hồ sơ
                            </a>


                                <a href="#" 
                                   wire:click.prevent="logout" 
                                   class="text-red-600 hover:bg-red-50 hover:text-red-900 flex items-center px-5 py-3 text-sm font-medium rounded-md">                                 
                                    <i class="fa-solid fa-right-from-bracket mr-2 w-5 h-3 flex-shrink-0 text-red-400 group-hover:text-red-500" aria-hidden="true"></i>                                  
                                    <span wire:loading.remove wire:target="logout">Đăng xuất</span> 
                                    <span wire:loading wire:target="logout">Đang xử lý...</span> 
                                </a>

                        </nav>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="flex-1">
                    <div class="bg-white rounded-lg shadow">
                        @if($activeTab === 'dashboard')
                            <div class="p-6">
                                <h2 class="text-2xl font-bold mb-6">Tổng quan tài khoản</h2>
                                <livewire:components.user-profile />
                            </div>
                        @elseif($activeTab === 'bookings')
                            <div class="p-6">
                                <h2 class="text-2xl font-bold mb-6">Lịch sử đặt phòng</h2>
                                <livewire:components.user-bookings />
                            </div>
                        @elseif($activeTab === 'reviews')
                            <div class="p-6">
                                <h2 class="text-2xl font-bold mb-6">Đánh giá của bạn</h2>
                                <livewire:components.user-reviews />
                            </div>
                        @elseif($activeTab === 'profile')
                            <div class="p-6">
                                <h2 class="text-2xl font-bold mb-6">Thông tin cá nhân</h2>
                                <livewire:components.user-profile :editable="true" />
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
