{{-- resources/views/livewire/pages/booking-process.blade.php --}}

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- Cột trái: Form nhập thông tin theo các bước --}}
    <div class="lg:col-span-2 space-y-6">

        @if (session()->has('error'))<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert"><p>{{ session('error') }}</p></div>@endif
         @if (session()->has('warning'))<div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4 rounded" role="alert"><p>{{ session('warning') }}</p></div>@endif
         @if (session()->has('success'))<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert"><p>{{ session('success') }}</p></div>@endif
         @if (session()->has('discount_success'))<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert"><p>{{ session('discount_success') }}</p></div>@endif
         @if (session()->has('discount_error'))<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert"><p>{{ session('discount_error') }}</p></div>@endif
        {{-- Step 1: Thông tin đặt phòng cơ bản --}}
        @if ($step === 1)
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h2 class="text-lg leading-6 font-medium text-gray-900 mb-4">1. Thông tin đặt phòng</h2>
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    {{-- Ngày nhận phòng --}}
                    <div class="sm:col-span-3">
                        <label for="checkInDate" class="block text-sm font-medium text-gray-700">Ngày nhận phòng *</label>
                        <input type="date" id="checkInDate" wire:model.lazy="checkInDate"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('checkInDate') border-red-500 @enderror"
                               min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}">
                        @error('checkInDate') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    {{-- Ngày trả phòng --}}
                    <div class="sm:col-span-3">
                        <label for="checkOutDate" class="block text-sm font-medium text-gray-700">Ngày trả phòng *</label>
                        <input type="date" id="checkOutDate" wire:model.lazy="checkOutDate"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('checkOutDate') border-red-500 @enderror"
                               min="{{ $checkInDate ? \Carbon\Carbon::parse($checkInDate)->addDay()->format('Y-m-d') : \Carbon\Carbon::today()->addDay()->format('Y-m-d') }}">
                        @error('checkOutDate') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    {{-- Số lượng khách --}}
                    <div class="sm:col-span-3">
                        <label for="numberOfGuests" class="block text-sm font-medium text-gray-700">Số lượng khách *</label>
                        <input type="number" id="numberOfGuests" wire:model.lazy="numberOfGuests" min="1"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('numberOfGuests') border-red-500 @enderror">
                        {{-- Hiển thị cảnh báo nếu vượt sức chứa VÀ sức chứa có giá trị --}}
                        @if($room && $room->capacity && $numberOfGuests > $room->capacity)
                            <p class="mt-1 text-sm text-yellow-600">Lưu ý: Số khách vượt quá sức chứa ({{ $room->capacity }}).</p>
                        @endif
                        @error('numberOfGuests') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                     {{-- Yêu cầu đặc biệt --}}
                    <div class="sm:col-span-6">
                        <label for="specialRequests" class="block text-sm font-medium text-gray-700">Yêu cầu đặc biệt</label>
                        <textarea id="specialRequests" wire:model.lazy="specialRequests" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('specialRequests') border-red-500 @enderror"
                                  placeholder="Ví dụ: phòng tầng cao, không hút thuốc..."></textarea>
                        @error('specialRequests') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 text-right">
                 <button wire:click="nextStep" wire:loading.attr="disabled" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                    Tiếp tục
                    <svg wire:loading wire:target="nextStep" class="animate-spin ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </button>
            </div>
        </div>
        @endif

        {{-- Step 2: Thông tin liên hệ & Thời gian đến --}}
        @if ($step === 2)
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
             <div class="px-4 py-5 sm:p-6">
                 <h2 class="text-lg leading-6 font-medium text-gray-900 mb-4">2. Thông tin liên hệ</h2>
                 {{-- ... (Phần thông tin user và thời gian đến giữ nguyên) ... --}}
                  @auth <div class="space-y-2 text-sm mb-4"> <p><strong>Tên:</strong> {{ Auth::user()->name }}</p> <p><strong>Email:</strong> {{ Auth::user()->email }}</p> <p><strong>Số điện thoại:</strong> {{ Auth::user()->phone_number ?? 'Chưa cập nhật' }}</p> </div> @if (!Auth::user()->phone_number)<p class="text-red-600 text-sm mb-4"> Vui lòng <a href="{{-- route('user.profile.edit') --}}" class="font-medium underline hover:text-red-800">cập nhật số điện thoại</a>.</p>@endif <div> <label for="arrivalTime" class="block text-sm font-medium text-gray-700">Thời gian đến dự kiến</label> <input type="time" id="arrivalTime" wire:model.lazy="arrivalTime" class="mt-1 block w-full max-w-xs rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('arrivalTime') border-red-500 @enderror"> <p class="text-xs text-gray-500 mt-1">Giúp KS chuẩn bị tốt hơn.</p> @error('arrivalTime') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror </div> @else <p>Vui lòng <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">đăng nhập</a>.</p> @endauth
             </div>
             <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-between">
                 <button wire:click="prevStep" class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Quay lại</button>
                 <button wire:click="nextStep" wire:loading.attr="disabled" @guest disabled @endguest @auth @if(!Auth::user()->phone_number) disabled @endif @endauth class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                     Tiếp tục
                    <svg wire:loading wire:target="nextStep" class="animate-spin ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </button>
             </div>
        </div>
        @endif

        {{-- Step 3: Thanh toán --}}
        @if ($step === 3)
         <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h2 class="text-lg leading-6 font-medium text-gray-900 mb-4">3. Thanh toán</h2>
                {{-- Mã giảm giá --}}
                <div class="mb-6 pb-4 border-b border-gray-200">
                     <label for="discountCode" class="block text-sm font-medium text-gray-700 mb-1">Mã giảm giá</label>
                     <div class="flex gap-x-2 items-start">
                         <div class="flex-grow">
                            <input type="text" id="discountCode" wire:model.defer="discountCode" @if($appliedDiscountCode) disabled @endif placeholder="Nhập mã (nếu có)" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm disabled:bg-gray-100 disabled:cursor-not-allowed @error('discountCode') border-red-500 @enderror">
                            @error('discountCode') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                         </div>
                         @if($appliedDiscountCode)
                             <button wire:click="removeDiscountCode" type="button" wire:loading.attr="disabled" class="inline-flex items-center px-3 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50">Xóa</button>
                         @else
                            <button wire:click="applyDiscountCode" type="button" wire:loading.attr="disabled" class="inline-flex items-center px-3 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 disabled:opacity-50">
                                Áp dụng
                                <svg wire:loading wire:target="applyDiscountCode" class="animate-spin ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"></circle><path fill="currentColor" d="M4 12a8 8 0 018-8 V0 C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" class="opacity-75"></path></svg>
                            </button>
                         @endif
                     </div>
                     {{-- Hiển thị lỗi/thành công của discount riêng biệt --}}
                     {{-- (Đã chuyển ra ngoài ở đầu trang) --}}
                 </div>

                 {{-- Chọn phương thức thanh toán (Đã sửa) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Chọn phương thức thanh toán *</label>
                    <fieldset class="mt-2"> <legend class="sr-only">Phương thức thanh toán</legend>
                        <div class="space-y-3">
                             {{-- Bank Transfer --}}
                            <label class="relative flex items-start p-3 border rounded-md hover:border-indigo-500 cursor-pointer @if($paymentMethod === 'bank_transfer') border-indigo-500 bg-indigo-50 @else border-gray-200 @endif">
                                <div class="flex items-center h-5"><input id="bank_transfer" wire:model="paymentMethod" value="bank_transfer" type="radio" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"></div>
                                <div class="ml-3 text-sm"><span class="font-medium text-gray-900">Chuyển khoản ngân hàng</span><p class="text-gray-500 text-xs">Thanh toán trước qua chuyển khoản.</p></div>
                            </label>
                             {{-- Pay at Hotel --}}
                            <label class="relative flex items-start p-3 border rounded-md hover:border-indigo-500 cursor-pointer @if($paymentMethod === 'pay_at_hotel') border-indigo-500 bg-indigo-50 @else border-gray-200 @endif">
                                <div class="flex items-center h-5"><input id="pay_at_hotel" wire:model="paymentMethod" value="pay_at_hotel" type="radio" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"></div>
                                <div class="ml-3 text-sm"><span class="font-medium text-gray-900">Thanh toán khi nhận phòng</span><p class="text-gray-500 text-xs">Thanh toán tại quầy lễ tân.</p></div>
                            </label>
                        </div>
                    </fieldset>
                    @error('paymentMethod') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-between">
                 <button wire:click="prevStep" class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Quay lại</button>
                 <button wire:click="createBooking" wire:loading.attr="disabled" wire:target="createBooking" class="inline-flex items-center justify-center py-2 px-6 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50">
                     <svg wire:loading wire:target="createBooking" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"> <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                     <span wire:loading.remove wire:target="createBooking">Hoàn tất đặt phòng</span>
                     <span wire:loading wire:target="createBooking">Đang xử lý...</span>
                 </button>
            </div>
        </div>
        @endif

    </div>{{-- End Cột trái --}}


    {{-- Cột phải: Tóm tắt đặt phòng --}}
    <div class="lg:col-span-1 space-y-6">
        @if($hotel && $room)
            {{-- Box Thông tin Khách sạn và Phòng --}}
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Tóm tắt đặt phòng</h3>
                    <div class="flex items-center gap-x-4 mb-4 border-b border-gray-200 pb-4">@if($hotel->images && count($hotel->images))<img src="{{ asset('storage/'.$hotel->images[0]) }}" alt="{{ $hotel->name }}" class="w-16 h-16 object-cover rounded-lg flex-shrink-0"/>@else <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 shrink-0"><svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div> @endif<div><h4 class="font-semibold text-gray-900">{{ $hotel->name }}</h4><p class="text-sm text-gray-500">{{ $hotel->address }}</p></div></div>
                    <div class="mb-4 border-b border-gray-200 pb-4"> <h4 class="font-semibold mb-1 text-gray-800">Phòng: {{ $room->room_type_name ?? $room->name ?? '(Không có tên)' }}</h4> @php $capacityValue = $room->max_occupancy ?? $room->capacity ?? null; @endphp @if($capacityValue) <p class="text-sm text-gray-500">Sức chứa tối đa: {{ $capacityValue }} người</p> @else <p class="text-sm text-gray-500">Sức chứa: (Không xác định)</p> @endif </div>
                    <dl class="space-y-1 text-sm text-gray-700"><div class="flex justify-between"><dt>Nhận phòng:</dt><dd class="font-medium">{{ $checkInDate ? \Carbon\Carbon::parse($checkInDate)->format('d/m/Y') : 'N/A' }}</dd></div><div class="flex justify-between"><dt>Trả phòng:</dt><dd class="font-medium">{{ $checkOutDate ? \Carbon\Carbon::parse($checkOutDate)->format('d/m/Y') : 'N/A' }}</dd></div> <div class="flex justify-between"> <dt>Số đêm:</dt> <dd class="font-medium">{{ $nights }}</dd> </div> <div class="flex justify-between"><dt>Số khách:</dt><dd class="font-medium">{{ $numberOfGuests }}</dd></div>@if($arrivalTime)<div class="flex justify-between"><dt>Giờ đến:</dt><dd class="font-medium">{{ $arrivalTime }}</dd></div>@endif @if($specialRequests)<div class="pt-2 border-t border-gray-200 mt-2"><dt class="font-medium mb-1">Yêu cầu:</dt><dd class="text-gray-600 whitespace-pre-line text-xs">{{ $specialRequests }}</dd></div>@endif</dl>
               </div>
           </div>

             {{-- ===== KHÔI PHỤC LẠI PHẦN HIỂN THỊ GIÁ ===== --}}
             <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Chi tiết giá</h3>
                    <dl class="space-y-1 text-sm text-gray-700 mb-4"><div class="flex justify-between"><dt>Giá gốc @if($nights > 0) ({{ $nights }} đêm) @endif:</dt><dd class="font-medium">{{ number_format($baseAmount, 0, ',', '.') }}đ</dd></div>@if($discountAmount > 0)<div class="flex justify-between text-green-600"><dt>Giảm giá @if($appliedDiscountCode) ({{ $appliedDiscountCode->code }}) @endif:</dt><dd class="font-medium">-{{ number_format($discountAmount, 0, ',', '.') }}đ</dd></div>@endif<div class="flex justify-between"><dt>Thuế ({{ $taxRate }}%):</dt><dd class="font-medium">{{ number_format($taxAmount, 0, ',', '.') }}đ</dd></div><div class="flex justify-between"><dt>Phí DV ({{ $serviceFeeRate }}%):</dt><dd class="font-medium">{{ number_format($serviceFeeAmount, 0, ',', '.') }}đ</dd></div></dl>
                    <div class="border-t border-gray-200 pt-4 flex justify-between items-baseline font-bold text-lg"><span class="text-gray-900">Tổng cộng</span><span class="text-indigo-600">{{ number_format($totalAmount, 0, ',', '.') }}đ</span></div>
                     <div x-data="{ open: false }" class="mt-3 text-xs"> <button @click="open = !open" class="text-indigo-600 hover:text-indigo-800"><span x-show="!open">Xem chi tiết thuế & phí</span><span x-show="open">Ẩn chi tiết</span></button><div x-show="open" x-collapse class="mt-1 space-y-1 text-gray-500"><p>Bao gồm:</p><ul class="list-disc list-inside pl-2"><li>Thuế ({{$taxRate}}%): {{ number_format($taxAmount, 0, ',', '.') }}đ</li><li>Phí DV ({{$serviceFeeRate}}%): {{ number_format($serviceFeeAmount, 0, ',', '.') }}đ</li></ul></div></div>
               </div>
            </div>
             {{-- ===== KẾT THÚC PHẦN GIÁ ===== --}}

        @else
             <div class="bg-white shadow rounded-lg p-6 text-center text-gray-500">Đang tải...</div>
        @endif
    </div> {{-- End Cột phải --}}

</div> {{-- End Grid --}}