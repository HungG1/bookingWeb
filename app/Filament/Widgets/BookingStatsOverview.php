<?php

namespace App\Filament\Widgets;

use App\Models\Booking; // Import Booking model
use App\Models\Hotel;   // Import Hotel model
use App\Models\Room;    // Import Room model
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BookingStatsOverview extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        // Lấy các số liệu thống kê
        $totalHotels = Hotel::count();
        // Lưu ý: Room::count() đếm số lượng *loại phòng* hoặc bản ghi phòng.
        // Nếu bạn muốn đếm tổng số phòng vật lý, dùng Room::sum('number_of_rooms')
        $totalRooms = Room::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();

        return [
            // Stat cho Tổng số khách sạn
            Stat::make('Tổng số khách sạn', $totalHotels)
                ->description('Số lượng khách sạn đang quản lý')
                ->descriptionIcon('heroicon-m-building-office-2') // Chọn icon phù hợp
                ->color('primary'),

            // Stat cho Tổng số phòng (loại phòng)
            Stat::make('Tổng số loại phòng', $totalRooms)
                ->description('Số lượng loại phòng trên hệ thống')
                ->descriptionIcon('heroicon-m-key') // Chọn icon phù hợp
                ->color('info'),

            // Stat cho Booking chờ xử lý
             Stat::make('Booking chờ xử lý', $pendingBookings)
                ->description('Số booking cần xác nhận')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            // Stat cho Booking đã xác nhận
             Stat::make('Booking đã xác nhận', $confirmedBookings)
                 ->description('Số booking đã được duyệt')
                 ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),

            // Bạn có thể giữ lại hoặc thêm các Stat khác ở đây
            // Ví dụ: Stat::make('Doanh thu hôm nay', ...),
        ];
    }
}