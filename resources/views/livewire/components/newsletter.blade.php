<div>
    <h3 class="text-lg font-semibold mb-4">Đăng ký nhận tin</h3>
    <form wire:submit.prevent="subscribe" class="space-y-4">
        <div>
            <input type="email" 
                   wire:model="email" 
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                   placeholder="Nhập email của bạn">
            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <button type="submit" 
                class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-150 ease-in-out">
            Đăng ký
        </button>
    </form>
    @if (session()->has('success'))
        <div class="mt-2 text-sm text-green-600">
            {{ session('success') }}
        </div>
    @endif
</div>