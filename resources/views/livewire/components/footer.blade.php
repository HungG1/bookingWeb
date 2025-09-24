<footer class="bg-gray-200 border-t border-gray-300 mt-auto">
    <div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-lg font-semibold mb-4">Về chúng tôi</h3>
                <p class="text-gray-600 text-sm">MyBooking - Nền tảng đặt phòng khách sạn trực tuyến hàng đầu.</p>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-4">Liên kết</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="text-gray-600 hover:text-blue-600">Điều khoản sử dụng</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-blue-600">Chính sách bảo mật</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-blue-600">FAQ</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-4">Liên hệ</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li>Email: Hungptpi00167@gamil.com</li>
                    <li>Điện thoại: 0962568279</li>
                </ul>
            </div>
            <div>
                <livewire:components.newsletter />
            </div>
        </div>
        <div class="border-t border-gray-300 mt-8 pt-8 text-center text-sm text-gray-600">
            © {{ date('Y') }} MyBooking. Đã đăng ký Bản quyền.
        </div>
    </div>
</footer>