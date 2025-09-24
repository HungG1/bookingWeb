<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Doanh thu (7 ngày qua)';

    protected int|string|array $columnSpan = [
        'md' => 2,
        'lg' => 2,
    ];

    protected static ?int $sort = 3; 

    protected function getData(): array
    {
        // ... (Giữ nguyên logic getData của bạn) ...
        // (Code getData từ câu trả lời trước)
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $revenueData = Booking::query()
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(final_price) as aggregate')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->pluck('aggregate', 'date')
            ->all();

        $labels = [];
        $data = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $dateString = $currentDate->toDateString();
            $labels[] = $currentDate->format('d/m');
            $data[] = $revenueData[$dateString] ?? 0;
            $currentDate->addDay();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Doanh thu',
                    'data' => $data,
                    'borderColor' => 'rgb(75, 192, 192)',
                    'tension' => 0.1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}