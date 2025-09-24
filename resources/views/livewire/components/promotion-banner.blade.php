<div class="relative bg-gradient-to-r from-blue-800 to-blue-900 py-20 overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[url('/images/diamond-pattern.svg')]"></div>
    
    <div class="container mx-auto px-4 relative">
        <div class="text-center mb-14">
            <h2 class="text-4xl font-bold text-white mb-4">Ưu đãi trong thời gian có hạn</h2>
            <p class="text-xl text-blue-100 max-w-2xl mx-auto">Mở khóa các ưu đãi độc quyền cho chuyến đi tiếp theo của bạn</p>
        </div>

        @if($promotions->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($promotions as $promotion)
                    <div class="group relative bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden">
                        <div class="aspect-[16/9] bg-blue-50 relative overflow-hidden">
                            @if($promotion->image)
                                <img src="{{ asset('storage/' . $promotion->image) }}" 
                                     class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300"
                                     alt="{{ $promotion->name }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200">
                                    <svg class="w-16 h-16 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <div class="absolute top-0 left-0 bg-red-600 text-white px-4 py-2 text-sm font-bold rounded-br-xl">
                                Save {{ $promotion->discount_percent }}%
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <h3 class="text-xl font-bold text-gray-900">{{ $promotion->name }}</h3>
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $promotion->code }}
                                </span>
                            </div>
                            
                            <p class="text-gray-600 mb-6 line-clamp-2">{{ $promotion->description }}</p>
                            
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center text-gray-500">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $promotion->end_date->diffForHumans() }}
                                </div>
                                <button class="flex items-center text-blue-600 font-medium hover:text-blue-800 transition-colors">
                                    Apply Now
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-blue-100 text-lg">Sắp có chương trình khuyến mãi mới...</p>
            </div>
        @endif
    </div>
</div>