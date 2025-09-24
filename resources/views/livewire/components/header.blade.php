<header class="bg-white shadow-lg sticky top-0 z-50 border-b border-gray-100">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center">
                <span class="text-2xl font-bold text-blue-600 tracking-tight">Booking</span>               
            </a>
            <!-- Main Navigation -->
            <nav class="hidden md:flex space-x-8 items-center">
                <a href="{{ route('home') }}" class="px-3 py-2 text-gray-700 hover:text-blue-600 font-medium transition-all duration-200 hover:bg-blue-50 rounded-[20px]">
                    Trang chủ
                </a>
                <a href="{{ route('hotels.list') }}" class="px-3 py-2 text-gray-700 hover:text-blue-600 font-medium transition-all duration-200 hover:bg-blue-50 rounded-[20px]">
                    Khách sạn
                </a>
                <a href="{{ route('blog.list') }}" class="px-3 py-2 text-gray-700 hover:text-blue-600 font-medium transition-all duration-200 hover:bg-blue-50 rounded-[20px]">
                    Blog
                </a>
            </nav>

            <!-- Right Section -->
            <div class="flex items-center space-x-4">                             
                <livewire:components.authentication />
            </div>
        </div>
    </div>
</header>