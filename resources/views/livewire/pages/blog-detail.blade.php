<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="flex items-center text-sm text-gray-500 mb-6">
            <a href="{{ route('blog.list') }}" class="hover:text-blue-600">Blog</a>
            <svg class="h-5 w-5 mx-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
            <span>{{ $post->category->name }}</span>
        </nav>

        <!-- Article Header -->
        <article class="bg-white rounded-lg shadow-lg overflow-hidden">
            @if($post->image)
                <div class="relative h-96">
                    <img src="{{ asset('storage/' . $post->image) }}" 
                         alt="{{ $post->title }}" 
                         class="w-full h-full object-cover">
                </div>
            @endif

            <div class="p-8">
                <div class="flex items-center text-sm text-gray-500 mb-4">
                    @if($post->category)
                        <span>{{ $post->category->name }}</span>
                        <span class="mx-2">•</span>
                    @endif
                    
                    @if($post->published_at)
                        <span>{{ $post->published_at->format('d/m/Y') }}</span>
                        <span class="mx-2">•</span>
                    @endif
                    
                    @if($post->author)
                        <span>By Admin</span>
                    @endif
                </div>

                <h1 class="text-4xl font-bold mb-6">{{ $post->title }}</h1>

                <!-- Article Content -->
                <div class="prose prose-lg max-w-none">
                    {!! $post->content !!}
                </div>

                <!-- Tags if you have them -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Chia sẻ bài viết</h3>
                    <div class="flex space-x-4">
                        <!-- Social Share Buttons -->
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                           target="_blank"
                           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            <i class="fab fa-facebook-f mr-2"></i>Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" 
                           target="_blank"
                           class="bg-blue-400 text-white px-4 py-2 rounded-lg hover:bg-blue-500">
                            <i class="fab fa-twitter mr-2"></i>Twitter
                        </a>
                    </div>
                </div>
            </div>
        </article>

        <!-- Related Posts -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-6">Bài viết liên quan</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($post->category->posts()
                    ->where('id', '!=', $post->id)
                    ->where('status', 'published')
                    ->latest()
                    ->take(3)
                    ->get() as $relatedPost)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        @if($relatedPost->image)
                            <img src="{{ asset('storage/' . $relatedPost->image) }}" 
                                 alt="{{ $relatedPost->title }}" 
                                 class="w-full h-48 object-cover">
                        @endif
                        <div class="p-4">
                            <h3 class="font-semibold mb-2">
                                <a href="{{ route('blog.detail', $relatedPost->slug) }}" 
                                   class="hover:text-blue-600">
                                    {{ $relatedPost->title }}
                                </a>
                            </h3>
                            <div class="text-sm text-gray-500">
                                {{ $relatedPost->published_at->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
