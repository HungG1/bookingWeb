<div class="flex items-center space-x-3">
    @auth
        @if ($user)
            <a href="{{ route('dashboard') }}" class="flex items-center group transition-all duration-200 p-1.5 rounded-full hover:bg-gray-100">
                <div class="relative">
                    @if ($user->avatar)
                        <img class="h-9 w-9 rounded-full object-cover ring-2 ring-blue-200" 
                             src="{{ Storage::disk('public')->url($user->avatar) }}" 
                             alt="{{ $user->name }}">
                    @else
                        <div class="h-9 w-9 rounded-full bg-gray-100 flex items-center justify-center ring-2 ring-blue-200">
                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="ml-2 mr-3 hidden lg:block">
                    <p class="text-sm font-medium text-gray-700">{{ $user->name }}</p>
                </div>
            </a>
        @endif
    @else
        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 transition-all duration-200 border border-gray-300 rounded-full hover:border-blue-200 hover:bg-blue-50">
            Đăng nhập
        </a>
    @endauth
</div>