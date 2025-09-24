<div class="min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full px-6 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <h2 class="text-2xl font-bold text-center mb-6">Đăng nhập</h2>

        <form wire:submit="login">
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input wire:model="form.email" id="email" type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required autofocus>
                @error('form.email') 
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="mt-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu</label>
                <input wire:model="form.password" id="password" type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                @error('form.password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="mt-4">
                <label class="inline-flex items-center">
                    <input wire:model="form.remember" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-600">Ghi nhớ đăng nhập</span>
                </label>
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Đăng nhập
                </button>
            </div>
        </form>

        <p class="mt-4 text-center text-sm text-gray-600">
            Chưa có tài khoản? 
            <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">
                Đăng ký ngay
            </a>
        </p>
    </div>
</div>