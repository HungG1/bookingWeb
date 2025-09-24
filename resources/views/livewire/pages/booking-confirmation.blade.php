{{-- resources/views/livewire/pages/booking-confirmation.blade.php --}}

{{-- Xóa bỏ: Không cần định nghĩa $pageTitle ở đây nữa --}}
{{-- @php
    $pageTitle = $pageTitle ?? 'Xác nhận Đặt phòng';
@endphp --}}

{{-- Xóa bỏ: Không cần gọi layout ở đây nếu component không dùng layout --}}
{{-- <x-app-layout> --}}

    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        {{-- Hiển thị thông báo session (Thành công, Lỗi, Thông tin) --}}
        @if (session()->has('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
                <div class="flex">
                    <div class="py-1"><svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                    <div><p class="text-sm">{{ session('success') }}</p></div>
                </div>
            </div>
        @endif
        @if (session()->has('payment_info'))
           <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
                <div class="flex">
                   <div class="py-1"><svg class="fill-current h-6 w-6 text-blue-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM9 9a1 1 0 0 0 0 2v3a1 1 0 0 0 1 1h1a1 1 0 1 0 0-2v-3a1 1 0 0 0-1-1H9z"/></svg></div>
                   <div><p class="font-bold">Hướng dẫn thanh toán</p><p class="text-sm">{!! nl2br(e(session('payment_info'))) !!}</p></div>
               </div>
           </div>
       @endif
        @if (session()->has('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
                 <p class="text-sm">{{ session('error') }}</p>
            </div>
        @endif
        {{-- Kết thúc phần thông báo --}}
    
    
        <div class="mb-6">
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">{{ $pageTitle ?? 'Xác nhận Đặt phòng' }}</h1>
            @if($booking) {{-- Thêm kiểm tra booking tồn tại --}}
                <p class="text-sm text-gray-600">Vui lòng kiểm tra lại thông tin đặt phòng của bạn.</p>
                <p class="text-sm text-gray-500">Mã đặt phòng: <span class="font-medium text-gray-700">#{{ $booking->id }}</span></p>
                <p class="text-sm text-gray-500">Trạng thái: <span class="font-semibold">{{ $booking->status }}</span> | Thanh toán: <span class="font-semibold">{{ $booking->payment_status }}</span></p>
            @endif
        </div>
    
        @if($booking) {{-- Chỉ hiển thị nội dung nếu booking tồn tại --}}
            {{-- Booking Summary --}}
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="px-4 py-5 sm:p-6">
                     <h2 class="text-lg font-medium text-gray-900 mb-4">Thông tin đặt phòng</h2>
                     <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        {{-- Hotel Info --}}
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Khách sạn</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $booking->hotel->name ?? 'N/A' }}</dd>
                            <dd class="mt-1 text-xs text-gray-500">{{ $booking->hotel->address ?? 'N/A' }}</dd>
                        </div>
    
                        {{-- === SỬA LỖI TRUY CẬP BIẾN $room === --}}
                        {{-- Room Info - Truy cập qua $booking->room --}}
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Loại phòng</dt>
                             {{-- Kiểm tra $booking->room tồn tại trước khi truy cập thuộc tính --}}
                             @if($booking->room)
                                 {{-- Ưu tiên hiển thị room_type_name hoặc name --}}
                                 <dd class="mt-1 text-sm text-gray-900">{{ $booking->room->room_type_name ?? $booking->room->name ?? '(Chưa có tên)' }}</dd>
                                 {{-- Lấy và hiển thị sức chứa --}}
                                 @php
                                    $capacityValue = $booking->room->max_occupancy ?? $booking->room->capacity ?? null;
                                 @endphp
                                 @if($capacityValue)
                                     <dd class="mt-1 text-xs text-gray-500">Sức chứa tối đa: {{ $capacityValue }} người</dd>
                                 @else
                                     <dd class="mt-1 text-xs text-gray-500">Sức chứa: (Không xác định)</dd>
                                 @endif
                             @else
                                 <dd class="mt-1 text-sm text-gray-900">(Lỗi tải thông tin phòng)</dd>
                             @endif
                        </div>
                        {{-- === KẾT THÚC SỬA LỖI === --}}
    
                        {{-- Dates --}}
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Ngày nhận phòng</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $booking->check_in_date?->format('d/m/Y') ?? 'N/A' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Ngày trả phòng</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $booking->check_out_date?->format('d/m/Y') ?? 'N/A' }}</dd>
                            @if($booking->check_in_date && $booking->check_out_date)
                                 @php $nights = max(1, $booking->check_out_date->diffInDays($booking->check_in_date)); @endphp
                                 <dd class="mt-1 text-xs text-gray-500">({{ $nights }} đêm)</dd>
                            @endif
                        </div>
    
                         {{-- Customer Info --}}
                         <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Thông tin khách</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $booking->customer_name }}</dd>
                            <dd class="mt-1 text-xs text-gray-500">{{ $booking->customer_email }}</dd>
                            <dd class="mt-1 text-xs text-gray-500">{{ $booking->customer_phone }}</dd>
                            <dd class="mt-1 text-xs text-gray-500">Số khách: {{ $booking->num_adults }} người lớn @if($booking->num_children > 0), {{ $booking->num_children }} trẻ em @endif</dd>
                        </div>
    
                        {{-- Notes & Arrival Time --}}
                        <div class="sm:col-span-1">
                            @if($booking->customer_notes)
                                <dt class="text-sm font-medium text-gray-500">Yêu cầu đặc biệt</dt>
                                <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $booking->customer_notes }}</dd>
                            @endif
                             @if($booking->arrival_time)
                                <dt class="text-sm font-medium text-gray-500 mt-2">Thời gian đến dự kiến</dt>
                                 <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($booking->arrival_time)->format('H:i') }}</dd>
                             @endif
                        </div>
                    </dl>
                </div>
                {{-- Price Summary --}}
                <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                    <h3 class="text-base font-medium text-gray-900 mb-3">Chi tiết thanh toán</h3>
                    <dl class="space-y-1 text-sm">
                         {{-- ... (Nội dung chi tiết giá giữ nguyên, dùng $booking->...) ... --}}
                         <div class="flex justify-between"><dt class="text-gray-600">Giá gốc phòng</dt><dd class="text-gray-900">{{ number_format($booking->base_price, 0, ',', '.') }}đ</dd></div> @if($booking->discount_amount > 0)<div class="flex justify-between text-green-600"><dt>Giảm giá @if($booking->discountCode) ({{ $booking->discountCode->code }}) @endif</dt><dd>-{{ number_format($booking->discount_amount, 0, ',', '.') }}đ</dd></div> @endif<div class="flex justify-between"><dt class="text-gray-600">Thuế</dt><dd class="text-gray-900">{{ number_format($booking->tax_amount ?? 0, 0, ',', '.') }}đ</dd></div><div class="flex justify-between"><dt class="text-gray-600">Phí dịch vụ</dt><dd class="text-gray-900">{{ number_format($booking->service_fee_amount ?? 0, 0, ',', '.') }}đ</dd></div><div class="flex justify-between font-semibold text-base pt-2 border-t border-gray-200 mt-2"><dt class="text-gray-900">Tổng cộng</dt><dd class="text-indigo-600">{{ number_format($booking->final_price, 0, ',', '.') }}đ</dd></div>
                    </dl>
                </div>
            </div>
    
            {{-- ... (Loading State Overlay giữ nguyên) ... --}}
             <div wire:loading.flex wire:target="confirmPayment, cancelBooking" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50"><div class="text-white text-lg flex items-center"><svg class="animate-spin h-6 w-6 text-white mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"> <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Đang xử lý...</div></div>
        @else
            {{-- Hiển thị nếu $booking không load được --}}
            <p class="text-center text-red-500">Không thể tải thông tin đặt phòng.</p>
        @endif
    
    </div>
    
    {{-- </x-app-layout> --}}
    
