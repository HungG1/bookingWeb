{{-- blog-page.blade.php --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Danh mục</h3>
                <ul class="space-y-2">
                    @foreach($categories as $category)
                        <li>
                            <a href="#" class="flex items-center justify-between hover:text-blue-600">
                                <span>{{ $category->name }}</span>
                                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-full text-xs">
                                    {{ $category->posts_count }}
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($posts as $post)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        @if($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" 
                                 alt="{{ $post->title }}" 
                                 class="w-full h-48 object-cover">
                        @endif
                        <div class="p-4">
                            <div class="flex items-center text-sm text-gray-500 mb-2">
                                <span>{{ $post->category->name }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $post->published_at->format('d/m/Y') }}</span>
                            </div>
                            <h2 class="text-xl font-semibold mb-2">
                                <a href="{{ route('blog.detail', $post->slug) }}" 
                                   class="hover:text-blue-600">
                                    {{ $post->title }}
                                </a>
                            </h2>
                            <p class="text-gray-600 text-sm mb-4">
                                {{ Str::limit(strip_tags($post->content), 100) }}
                            </p>
                            <div class="flex items-center text-sm">
                                <div class="flex-shrink-0">
                                    <span class="text-gray-500">By Admin</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</div>
