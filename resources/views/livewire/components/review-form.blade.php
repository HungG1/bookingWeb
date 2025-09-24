<div class="bg-white rounded-lg shadow-lg p-6">
    <h3 class="text-xl font-bold mb-4">Viết đánh giá</h3>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="submitReview">
        <!-- Rating Stars -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Đánh giá của bạn</label>
            <div class="flex items-center space-x-1">
                @for($i = 1; $i <= 5; $i++)
                    <button type="button" 
                            wire:click="$set('rating', {{ $i }})"
                            class="focus:outline-none">
                        <svg class="w-8 h-8 {{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300' }} cursor-pointer hover:text-yellow-400" 
                             fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </button>
                @endfor
            </div>
            @error('rating') 
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Title -->
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Tiêu đề</label>
            <input type="text" id="title" 
                   wire:model="title"
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                   placeholder="Tóm tắt ngắn gọn trải nghiệm của bạn">
            @error('title') 
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Comment -->
        <div class="mb-4">
            <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Chi tiết đánh giá</label>
            <textarea id="comment" 
                      wire:model="comment"
                      rows="4"
                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                      placeholder="Chia sẻ trải nghiệm của bạn để giúp đỡ người khác"></textarea>
            @error('comment') 
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit"
                class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-200">
            Gửi đánh giá
        </button>
    </form>
</div>
