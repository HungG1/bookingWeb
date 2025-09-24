<div class="bg-white rounded-lg shadow-lg p-6">
    <h2 class="text-2xl font-bold mb-6">Chi tiết đặt phòng</h2>

    <!-- Hotel & Room Info -->
    <div class="border-b pb-6 mb-6">
        <h3 class="font-semibold text-lg mb-4">Thông tin khách sạn & phòng</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class="text-gray-600">Khách sạn:</div>
                <div class="font-medium">{{ $booking->hotel->name }}</div>
            </div>
            <div>
                <div class="text-gray-600">Loại phòng:</div>
                <div class="font-medium">{{ $booking->room->room_type_name }}</div>
            </div>
            <div>
                <div class="text-gray-600">Số đêm:</div>
                <div class="font-medium">{{ $nights }} đêm</div>
            </div>
            <div>
                <div class="text-gray-600">Số phòng:</div>
                <div class="font-medium">{{ $booking->num_rooms }} phòng</div>
            </div>
        </div>
    </div>

    <!-- Dates & Guests -->
    <div class="border-b pb-6 mb-6">
        <h3 class="font-semibold text-lg mb-4">Thời gian & Số khách</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class="text-gray-600">Nhận phòng:</div>
                <div class="font-medium">{{ $checkIn->format('d/m/Y') }}</div>
            </div>
            <div>
                <div class="text-gray-600">Trả phòng:</div>
                <div class="font-medium">{{ $checkOut->format('d/m/Y') }}</div>
            </div>
            <div>
                <div class="text-gray-600">Số người lớn:</div>
                <div class="font-medium">{{ $booking->num_adults }} người</div>
            </div>
            <div>
                <div class="text-gray-600">Số trẻ em:</div>
                <div class="font-medium">{{ $booking->num_children }} người</div>
            </div>
        </div>
    </div>

    <!-- Customer Info -->
    <div class="border-b pb-6 mb-6">
        <h3 class="font-semibold text-lg mb-4">Thông tin khách hàng</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class="text-gray-600">Họ tên:</div>
                <div class="font-medium">
<div class="bg-white rounded-lg shadow-lg p-6">
    <h2 class="text-2xl font-bold mb-6">Chi tiết đặt phòng</h2>

    <!-- Hotel & Room Info -->
    <div class="border-b pb-6 mb-6">
        <h3 class="font-semibold text-lg mb-4">Thông tin khách sạn & phòng</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class="text-gray-600">Khách sạn:</div>
                <div class="font-medium">{{ $booking->hotel->name }}</div>
            </div>
            <div>
                <div class="text-gray-600">Loại phòng:</div>
                <div class="font-medium">{{ $booking->room->room_type_name }}</div>
            </div>
            <div>
                <div class="text-gray-600">Số đêm:</div>
                <div class="font-medium">{{ $nights }} đêm</div>
            </div>
            <div>
                <div class="text-gray-600">Số phòng:</div>
                <div class="font-medium">{{ $booking->num_rooms }} phòng</div>
            </div>
        </div>
    </div>

    <!-- Dates & Guests -->
    <div class="border-b pb-6 mb-6">
        <h3 class="font-semibold text-lg mb-4">Thời gian & Số khách</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class="text-gray-600">Nhận phòng:</div>
                <div class="font-medium">{{ $checkIn->format('d/m/Y') }}</div>
            </div>
            <div>
                <div class="text-gray-600">Trả phòng:</div>
                <div class="font-medium">{{ $checkOut->format('d/m/Y') }}</div>
            </div>
            <div>
                <div class="text-gray-600">Số người lớn:</div>
                <div class="font-medium">{{ $booking->num_adults }} người</div>
            </div>
            <div>
                <div class="text-gray-600">Số trẻ em:</div>
                <div class="font-medium">{{ $booking->num_children }} người</div>
            </div>
        </div>
    </div>

    <!-- Customer Info -->
    <div class="border-b pb-6 mb-6">
        <h3 class="font-semibold text-lg mb-4">Thông tin khách hàng</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class="text-gray-600">Họ tên:</div>
                // ...existing code...
                <div class="font-medium">{{ $booking->customer_name }}</div>
            </div>
            <div>
                <div class="text-gray-600">Email:</div>
                <div class="font-medium">{{ $booking->customer_email }}</div>
            </div>
            <div>
                <div class="text-gray-600">Số điện thoại:</div>
                <div class="font-medium">{{ $booking->customer_phone }}</div>
            </div>
            <div>
                <div class="text-gray-600">Ghi chú đặc biệt:</div>
                <div class="font-medium">{{ $booking->special_requests ?? 'Không có' }}</div>
            </div>
        </div>
    </div>

    <!-- Price Details -->
    <div class="border-b pb-6 mb-6">
        <h3 class="font-semibold text-lg mb-4">Chi tiết giá</h3>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-600">Giá phòng ({{ $nights }} đêm x {{ $booking->num_rooms }} phòng)</span>
                <span class="font-medium">{{ number_format($booking->room_total) }} VNĐ</span>
            </div>
            @if($booking->addons_total > 0)
            <div class="flex justify-between">
                <span class="text-gray-600">Dịch vụ bổ sung</span>
                <span class="font-medium">{{ number_format($booking->addons_total) }} VNĐ</span>
            </div>
            @endif
            @if($booking->discount_amount > 0)
            <div class="flex justify-between text-green-600">
                <span>Giảm giá</span>
                <span>-{{ number_format($booking->discount_amount) }} VNĐ</span>
            </div>
            @endif
            <div class="flex justify-between font-bold text-lg pt-3 border-t">
                <span>Tổng cộng</span>
                <span>{{ number_format($booking->total_amount) }} VNĐ</span>
            </div>
        </div>
    </div>

    <!-- Payment Status -->
    <div class="mb-6">
        <h3 class="font-semibold text-lg mb-4">Trạng thái thanh toán</h3>
        <div class="flex items-center">
            @if($booking->payment_status === 'paid')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                    </svg>
                    Đã thanh toán
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                    <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                    </svg>
                    Chờ thanh toán
                </span>
            @endif
            @if($booking->payment_method)
                <span class="ml-4 text-gray-600">
                    Phương thức: {{ $booking->payment_method }}
                </span>
            @endif
        </div>
    </div>

    <!-- Actions -->
    @if($booking->status === 'pending')
        <div class="flex justify-end space-x-4">
            <button wire:click="cancelBooking" 
                    class="px-4 py-2 border border-red-600 text-red-600 rounded-md hover:bg-red-50">
                Hủy đặt phòng
            </button>
            @if($booking->payment_status !== 'paid')
                <button wire:click="proceedToPayment" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Thanh toán
                </button>
            @endif
        </div>
    @endif
</div>