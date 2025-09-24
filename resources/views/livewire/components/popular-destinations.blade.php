<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    @forelse($destinations as $dest)
        <a href="{{ route('hotels.list', ['location' => $dest->address]) }}" 
           class="group relative block overflow-hidden rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300">
            <div class="aspect-video overflow-hidden">
                @if($dest->image_path)
                    <img src="{{ asset('storage/' . $dest->image_path) }}" 
                         class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300"
                         alt="{{ $dest->address }}">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                        <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                @endif
            </div>
            
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent p-4 flex flex-col justify-end">
                <div class="text-white">
                    <h3 class="text-lg font-bold mb-1">{{ $dest->address }}</h3>
                    <div class="flex items-center justify-between text-sm">
                        <span>{{ $dest->total_hotels }} properties</span>
                        <div class="flex items-center bg-white/20 px-2 py-1 rounded-full">
                            <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            4.8
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="absolute top-3 right-3">
                <span class="px-3 py-1 bg-white/90 text-blue-600 text-xs font-semibold rounded-full shadow-sm">
                    {{ $dest->total_bookings }} bookings
                </span>
            </div>
        </a>
    @empty
        <div class="col-span-full text-center py-12">
            <p class="text-gray-500 text-lg">Không tìm thấy điểm đến phổ biến nào</p>
        </div>
    @endforelse
</div>