<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Booking; 

class LatestBookingsTable extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 1; 

    public function table(Table $table): Table
    {
        return $table
            // Cung cấp câu truy vấn Eloquent làm tham số
            ->query(
                Booking::query() 
                    ->latest() 
                    ->limit(5) 
            )
            ->columns([
                // Định nghĩa các cột bạn muốn hiển thị ở đây
                Tables\Columns\TextColumn::make('id')
                    ->label('ID'),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Khách hàng')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hotel.name') // Giả sử có relationship 'hotel'
                    ->label('Khách sạn'),
                Tables\Columns\TextColumn::make('check_in_date')
                     ->label('Ngày nhận')
                     ->date('d/m/Y'), // Định dạng ngày
                Tables\Columns\BadgeColumn::make('status') // Hiển thị status dạng badge
                    ->label('Trạng thái')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'confirmed',
                        'danger' => fn ($state) => in_array($state, ['cancelled_by_user', 'cancelled_by_admin', 'no_show']),
                        'primary' => 'checked_in',
                        'info' => 'checked_out',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) { // Tùy chọn: Format tên status
                        'pending' => 'Chờ xác nhận',
                        'confirmed' => 'Đã xác nhận',
                        'cancelled_by_user' => 'Khách hủy',
                        'cancelled_by_admin' => 'Admin hủy',
                        'checked_in' => 'Đã nhận phòng',
                        'checked_out' => 'Đã trả phòng',
                        'no_show' => 'Không đến',
                        default => $state,
                    }),
                // Thêm các cột khác nếu cần...
            ]);
    }
}