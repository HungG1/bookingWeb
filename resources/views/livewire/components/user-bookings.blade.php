{{-- livewire/components/user-bookings.blade.php --}}
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    {{-- ... (Phần tiêu đề, flash messages, filters giữ nguyên) ... --}}
    <div class="mb-6">
     <h1 class="text-2xl font-bold text-gray-900">Lịch sử đặt phòng</h1>
     <p class="mt-1 text-sm text-gray-600">Quản lý tất cả các đặt phòng của bạn</p>
    </div>

    @if (session()->has('message')) <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md shadow-sm">{{ session('message') }}</div> @endif
    @if (session()->has('error')) <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md shadow-sm">{{ session('error') }}</div> @endif

    {{-- Filters --}}
    <div class="bg-white shadow rounded-lg p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
             <div>
                 <label for="statusFilter" class="block text-sm font-medium text-gray-700">Trạng thái</label>
                 <select id="statusFilter" wire:model.live="statusFilter" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                     @foreach($statuses as $value => $label)
                         <option value="{{ $value }}">{{ $label }}</option>
                     @endforeach
                 </select>
             </div>
             <div>
                 <label for="bookingSearch" class="block text-sm font-medium text-gray-700">Tìm kiếm</label>
                 <div class="mt-1 relative rounded-md shadow-sm">
                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                         <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                             <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                         </svg>
                     </div>
                     <input type="text" id="bookingSearch" wire:model.live.debounce.300ms="searchTerm" placeholder="Mã, tên KS, tên phòng..." class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md">
                 </div>
             </div>
        </div>
    </div>

    {{-- Bookings List --}}
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                {{-- ... (thead giữ nguyên) ... --}}
                <thead class="bg-gray-50">
                 <tr>
                     <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã ĐP</th>
                     <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách sạn & Phòng</th>
                     <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày</th>
                     <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                     <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                     <th scope="col" class="relative px-6 py-3"><span class="sr-only">Thao tác</span></th>
                 </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($bookings as $booking)
                        <tr>
                            {{-- ... (các td hiển thị thông tin như cũ) ... --}}
                             <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $booking->id }}</td>
                             <td class="px-6 py-4 whitespace-nowrap text-sm">
                                 <div class="font-medium text-gray-900">{{ $booking->hotel->name ?? 'N/A' }}</div>
                                 <div class="text-gray-500">{{ $booking->room->name ?? 'N/A' }}</div>
                             </td>
                             <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                 <div>Nhận: {{ $booking->check_in_date ? $booking->check_in_date->format('d/m/Y') : 'N/A' }}</div>
                                 <div>Trả: {{ $booking->check_out_date ? $booking->check_out_date->format('d/m/Y') : 'N/A' }}</div>
                             </td>
                             <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($booking->final_price ?? 0, 0, ',', '.') }}đ</td>
                             <td class="px-6 py-4 whitespace-nowrap text-sm">
                                 {{-- Status Badge (Giữ nguyên logic @switch) --}}
                                  @switch($booking->status)
                                        @case('pending') <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Chờ xác nhận</span> @break
                                        @case('confirmed') <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Đã xác nhận</span> @break
                                        @case('completed') <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Hoàn thành</span> @break
                                        @case('checked_in') <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-teal-100 text-teal-800">Đã nhận phòng</span> @break
                                        @case('checked_out') <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Đã trả phòng</span> @break
                                        @case('cancelled_by_user') <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Bạn đã hủy</span> @break
                                        @case('cancelled_by_admin') <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Bị từ chối</span> @break
                                        @case('no_show') <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">Không đến</span> @break
                                        @default <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ $booking->status }}</span>
                                   @endswitch
                             </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                {{-- SỬA NÚT CHI TIẾT: Gọi phương thức Livewire --}}
                                <button type="button" wire:click="showBookingDetails({{ $booking->id }})" class="text-indigo-600 hover:text-indigo-900">
                                    Chi tiết
                                </button>

                                {{-- Nút Hủy (giữ nguyên) --}}
                                @if(in_array($booking->status, ['pending', 'confirmed'])) {{-- Cho phép hủy cả confirmed? --}}
                                    <button wire:click="confirmCancellation({{ $booking->id }})" class="text-red-600 hover:text-red-900">
                                        Hủy
                                    </button>
                                @endif

                                {{-- Nút Đánh giá (Sẽ xử lý ở UserReviews) --}}
                                {{-- @if($booking->status === 'completed' || $booking->status === 'checked_out') --}}
                                    {{-- Check xem đã review chưa nếu cần --}}
                                    {{-- <a href="#" class="text-green-600 hover:text-green-900">Đánh giá</a> --}}
                                {{-- @endif --}}
                            </td>
                        </tr>
                    @empty
                        {{-- ... (<tr> không tìm thấy giữ nguyên) ... --}}
                         <tr><td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">Không tìm thấy đặt phòng nào.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $bookings->links() }}
        </div>
    </div>

    {{-- Modal Hủy Đặt phòng (giữ nguyên) --}}
    @if($showCancelModal)
       {{-- ... (Code modal hủy giữ nguyên) ... --}}
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Xác nhận hủy</h3>
                                <div class="mt-2"><p class="text-sm text-gray-500">Bạn chắc chắn muốn hủy đặt phòng này?</p></div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="cancelBooking" wire:loading.attr="disabled" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">Xác nhận</button>
                        <button wire:click="closeModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Hủy bỏ</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- <<< THÊM MODAL XEM CHI TIẾT BOOKING >>> --}}
    @if($showDetailModal && $selectedBooking)
        <div class="fixed z-20 inset-0 overflow-y-auto" aria-labelledby="modal-title-detail" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                {{-- Background overlay --}}
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>

                {{-- Vertical center trick --}}
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                {{-- Modal panel --}}
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center pb-3 border-b mb-4">
                             <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title-detail">
                                 Chi tiết đặt phòng #{{ $selectedBooking->id }}
                             </h3>
                             <button wire:click="closeModal" type="button" class="text-gray-400 hover:text-gray-500">
                                 <span class="sr-only">Đóng</span>
                                 <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                             </button>
                        </div>

                        {{-- Nội dung chi tiết --}}
                        <div class="mt-2">
                             {{-- Tái sử dụng layout từ booking-confirmation nếu muốn --}}
                             <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                 {{-- Hotel & Room --}}
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Khách sạn</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $selectedBooking->hotel->name ?? 'N/A' }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Phòng</dt>
                                     @if($selectedBooking->room)
                                        {{-- Ưu tiên hiển thị room_type_name hoặc name --}}
                                        <dd class="mt-1 text-sm text-gray-900">{{ $selectedBooking->room->room_type_name ?? $selectedBooking->room->name ?? '(Không có tên)' }}</dd>
                                        {{-- Lấy và hiển thị sức chứa (max_occupancy hoặc capacity) --}}
                                        @php
                                            $capacityValueModal = $selectedBooking->room->max_occupancy ?? $selectedBooking->room->capacity ?? null;
                                        @endphp
                                        @if($capacityValueModal)
                                            <dd class="mt-1 text-xs text-gray-500">Sức chứa tối đa: {{ $capacityValueModal }} người</dd>
                                        @endif
                                     @else
                                        <dd class="mt-1 text-sm text-gray-900">(Không có thông tin)</dd>
                                     @endif
                                </div>
                                 {{-- Dates --}}
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Nhận phòng</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $selectedBooking->check_in_date ? $selectedBooking->check_in_date->format('d/m/Y') : 'N/A' }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Trả phòng</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $selectedBooking->check_out_date ? $selectedBooking->check_out_date->format('d/m/Y') : 'N/A' }}</dd>
                                </div>
                                 {{-- Customer --}}
                                  <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Người đặt</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $selectedBooking->customer_name }}</dd>
                                    <dd class="mt-1 text-xs text-gray-500">{{ $selectedBooking->customer_email }}</dd>
                                    <dd class="mt-1 text-xs text-gray-500">{{ $selectedBooking->customer_phone }}</dd>
                                </div>
                                  <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Số khách</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $selectedBooking->num_adults }} người lớn, {{ $selectedBooking->num_children }} trẻ em</dd>
                                 </div>
                                 {{-- Notes & Arrival --}}
                                  <div class="sm:col-span-2">
                                     @if($selectedBooking->customer_notes)
                                         <dt class="text-sm font-medium text-gray-500">Yêu cầu</dt>
                                         <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $selectedBooking->customer_notes }}</dd>
                                     @endif
                                     @if($selectedBooking->arrival_time)
                                          <dt class="text-sm font-medium text-gray-500 mt-2">Giờ đến dự kiến</dt>
                                         <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($selectedBooking->arrival_time)->format('H:i') }}</dd>
                                     @endif
                                 </div>

                                 {{-- Price Details --}}
                                  <div class="sm:col-span-2 pt-4 border-t mt-2">
                                      <dt class="text-sm font-medium text-gray-500 mb-2">Chi tiết giá</dt>
                                      <dl class="space-y-1 text-sm">
                                          <div class="flex justify-between"><span class="text-gray-600">Giá gốc</span><span>{{ number_format($selectedBooking->base_price ?? 0, 0, ',', '.') }}đ</span></div>
                                           @if($selectedBooking->discount_amount > 0)
                                          <div class="flex justify-between text-green-600"><span >Giảm giá @if($selectedBooking->discountCode) ({{ $selectedBooking->discountCode->code }}) @endif</span><span>-{{ number_format($selectedBooking->discount_amount, 0, ',', '.') }}đ</span></div>
                                           @endif
                                          <div class="flex justify-between"><span class="text-gray-600">Thuế</span><span>{{ number_format($selectedBooking->tax_amount ?? 0, 0, ',', '.') }}đ</span></div>
                                          <div class="flex justify-between"><span class="text-gray-600">Phí dịch vụ</span><span>{{ number_format($selectedBooking->service_fee_amount ?? 0, 0, ',', '.') }}đ</span></div>
                                          <div class="flex justify-between font-semibold pt-1 border-t mt-1"><span class="text-gray-900">Tổng cộng</span><span class="text-indigo-600">{{ number_format($selectedBooking->final_price ?? 0, 0, ',', '.') }}đ</span></div>
                                      </dl>
                                  </div>
                                   {{-- Payment Info --}}
                                   <div class="sm:col-span-2 pt-4 border-t mt-2">
                                        <dt class="text-sm font-medium text-gray-500">Thanh toán</dt>
                                        <dd class="mt-1 text-sm text-gray-900">Trạng thái: <span class="font-semibold">{{ $statuses[$selectedBooking->payment_status] ?? ucfirst(str_replace('_',' ', $selectedBooking->payment_status)) }}</span></dd>
                                         @if($selectedBooking->payment_method) <dd class="mt-1 text-xs text-gray-500">Phương thức: {{ $selectedBooking->payment_method }}</dd> @endif
                                         @if($selectedBooking->transaction_id) <dd class="mt-1 text-xs text-gray-500">Mã GD: {{ $selectedBooking->transaction_id }}</dd> @endif
                                   </div>

                            </dl>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="closeModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Đóng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- <<< KẾT THÚC MODAL XEM CHI TIẾT >>> --}}

</div>