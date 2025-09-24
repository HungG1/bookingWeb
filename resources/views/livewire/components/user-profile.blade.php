{{-- user-profile.blade.php --}}
<div>
    {{-- Thông báo thành công --}}
    @if (session('message'))
        <div class="rounded-md bg-green-50 p-4 mb-6">
            {{-- ... icon và text message ... --}}
             <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                         <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
                </div>
             </div>
        </div>
    @endif

    {{-- Phần thông tin cá nhân --}}
    <div class="bg-white overflow-hidden shadow rounded-lg divide-y divide-gray-200">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Thông tin cá nhân</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Cập nhật thông tin của bạn.</p>
        </div>

        <div class="px-4 py-5 sm:p-6">
            {{-- Phần hiển thị/upload Avatar --}}
            <div class="mb-6"> {{-- Thêm margin bottom --}}
                <label class="block text-sm font-medium text-gray-700">Ảnh đại diện</label>
                <div class="mt-1 flex items-center">
                    <div class="mr-4 flex-shrink-0"> {{-- Thêm flex-shrink-0 --}}
                        {{-- Hiển thị ảnh tạm khi đang chọn --}}
                        @if ($tempAvatar)
                            <img src="{{ $tempAvatar->temporaryUrl() }}" alt="Preview" class="h-16 w-16 rounded-full object-cover">
                        {{-- Hiển thị ảnh đã lưu --}}
                        @elseif ($avatar) 
                            {{-- SỬA LẠI CÁCH LẤY URL --}}
                            <img src="{{ Storage::disk('public')->url($avatar) }}" alt="Avatar" class="h-16 w-16 rounded-full object-cover">
                        {{-- Ảnh mặc định --}}
                        @else
                            <span class="h-16 w-16 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center"> {{-- Thêm flex center --}}
                                <svg class="h-12 w-12 text-gray-300" fill="currentColor" viewBox="0 0 24 24"> {{-- Chỉnh size SVG --}}
                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /> {{-- Thêm hình người --}}
                                </svg>
                            </span>
                        @endif
                    </div>
                    
                    {{-- Nút thay đổi avatar --}}
                    @if($editable)
                        <div>
                            <label for="avatar-upload" class="cursor-pointer rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                Thay đổi
                            </label>
                            <input id="avatar-upload" wire:model="tempAvatar" type="file" class="sr-only" accept="image/*"> {{-- Dùng sr-only thay hidden --}}
                            <div wire:loading wire:target="tempAvatar" class="mt-1 text-sm text-gray-500">Đang tải lên...</div>
                            @error('tempAvatar') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror {{-- Thêm block --}}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Form cập nhật thông tin --}}
            <form wire:submit.prevent="updateProfile" class="mt-6 grid grid-cols-6 gap-y-4 gap-x-6"> {{-- Chỉnh gap --}}
                {{-- Trường Họ tên --}}
                <div class="col-span-6 sm:col-span-3">
                    <label for="name" class="block text-sm font-medium text-gray-700">Họ tên</label>
                    <input type="text" wire:model.lazy="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" {{ $editable ? '' : 'disabled' }}>
                    @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Trường Email --}}
                <div class="col-span-6 sm:col-span-3">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" wire:model.lazy="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" {{ $editable ? '' : 'disabled' }}>
                    @error('email') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Trường Số điện thoại --}}
                <div class="col-span-6 sm:col-span-3">
                    <label for="phoneNumber" class="block text-sm font-medium text-gray-700">Số điện thoại</label> 
                    <input type="text" wire:model.lazy="phoneNumber" id="phoneNumber" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" {{ $editable ? '' : 'disabled' }}>
                    @error('phoneNumber') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Trường Địa chỉ --}}
                <div class="col-span-6">
                    <label for="address" class="block text-sm font-medium text-gray-700">Địa chỉ</label>
                    <input type="text" wire:model.lazy="address" id="address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" {{ $editable ? '' : 'disabled' }}>
                    @error('address') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Nút Cập nhật thông tin --}}
                @if($editable)
                    <div class="col-span-6 flex justify-end pt-4"> {{-- Thêm pt-4 --}}
                        <button type="submit" 
                                wire:loading.attr="disabled" 
                                wire:target="updateProfile, tempAvatar"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50">
                            <span wire:loading.remove wire:target="updateProfile, tempAvatar">Cập nhật thông tin</span>
                            <span wire:loading wire:target="updateProfile, tempAvatar">Đang lưu...</span>
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>

    {{-- Phần Đổi mật khẩu --}}
    @if($editable)
        <div class="mt-6 bg-white overflow-hidden shadow rounded-lg divide-y divide-gray-200">
             <div class="px-4 py-5 sm:px-6">
                 <h3 class="text-lg leading-6 font-medium text-gray-900">Đổi mật khẩu</h3>
                 <p class="mt-1 max-w-2xl text-sm text-gray-500">Đảm bảo tài khoản của bạn sử dụng mật khẩu mạnh.</p>
             </div>

             {{-- Thông báo đổi MK thành công --}}
             @if (session('password_message'))
                 <div class="border-t border-gray-200"> {{-- Thêm border --}}
                     <div class="px-4 py-3 sm:px-6">
                         <div class="rounded-md bg-green-50 p-4">
                            {{-- ... icon và text password_message ... --}}
                              <div class="flex">
                                 <div class="flex-shrink-0">
                                     <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                     </svg>
                                 </div>
                                 <div class="ml-3">
                                     <p class="text-sm font-medium text-green-800">{{ session('password_message') }}</p>
                                 </div>
                              </div>
                         </div>
                     </div>
                 </div>
             @endif

             {{-- Form đổi mật khẩu --}}
             <div class="px-4 py-5 sm:p-6">
                 <form wire:submit.prevent="updatePassword" class="grid grid-cols-6 gap-y-4 gap-x-6"> {{-- Chỉnh gap --}}
                     {{-- Mật khẩu hiện tại --}}
                     <div class="col-span-6 sm:col-span-4">
                         <label for="currentPassword" class="block text-sm font-medium text-gray-700">Mật khẩu hiện tại</label>
                         <input type="password" wire:model.lazy="currentPassword" id="currentPassword" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                         @error('currentPassword') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                     </div>

                    {{-- Mật khẩu mới --}}
                     <div class="col-span-6 sm:col-span-4">
                         <label for="newPassword" class="block text-sm font-medium text-gray-700">Mật khẩu mới</label>
                         <input type="password" wire:model.lazy="newPassword" id="newPassword" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                         @error('newPassword') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                     </div>

                     {{-- Xác nhận mật khẩu mới --}}
                     <div class="col-span-6 sm:col-span-4">
                         <label for="newPasswordConfirmation" class="block text-sm font-medium text-gray-700">Xác nhận mật khẩu mới</label>
                         <input type="password" wire:model.lazy="newPasswordConfirmation" id="newPasswordConfirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                         {{-- Error hiển thị chung cho newPasswordConfirmation nếu validate same:newPassword bị lỗi --}}
                         @error('newPasswordConfirmation') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror 
                     </div>

                     {{-- Nút Đổi mật khẩu --}}
                     <div class="col-span-6 flex justify-end pt-4"> {{-- Thêm pt-4 --}}
                         <button type="submit" 
                                 wire:loading.attr="disabled" 
                                 wire:target="updatePassword"
                                 class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50">
                             <span wire:loading.remove wire:target="updatePassword">Đổi mật khẩu</span>
                             <span wire:loading wire:target="updatePassword">Đang lưu...</span>
                         </button>
                     </div>
                 </form>
             </div>
         </div>
    @endif
</div>