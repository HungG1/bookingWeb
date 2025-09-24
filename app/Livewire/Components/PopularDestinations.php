<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\Hotel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Thêm Log để ghi lỗi nếu cần

class PopularDestinations extends Component
{
    public function render()
    {
        try {
            // Lấy địa chỉ, đếm số lượt booking VÀ đếm số khách sạn DISTINCT tại địa chỉ đó
            $stats = Hotel::select(
                            'hotels.address', // Chọn địa chỉ
                            DB::raw('COUNT(DISTINCT bookings.id) as total_bookings'), // Đếm số booking (dùng DISTINCT nếu có thể có nhiều dòng booking cho cùng hotel trong join)
                            DB::raw('COUNT(DISTINCT hotels.id) as total_hotels') // << ĐẾM SỐ KHÁCH SẠN
                        )
                        // Chỉ join với booking nếu bạn thực sự cần đếm booking
                        // Nếu chỉ cần đếm hotel theo address, không cần join bookings
                         ->leftJoin('bookings', 'hotels.id', '=', 'bookings.hotel_id') // Dùng leftJoin để vẫn giữ address dù không có booking
                         ->whereNotNull('hotels.address') // Bỏ qua hotel không có địa chỉ
                         ->where('hotels.address', '!=', '') // Bỏ qua địa chỉ rỗng
                        ->groupBy('hotels.address') // Nhóm theo địa chỉ
                        ->orderByDesc('total_bookings') // Sắp xếp theo số booking (hoặc total_hotels)
                        ->take(6) // Lấy top 6
                        ->get();

            // Lấy ảnh đại diện hiệu quả hơn: Lấy 1 ảnh cho mỗi địa chỉ trong $stats
            $addresses = $stats->pluck('address')->toArray();
            $imagesByAddress = Hotel::whereIn('address', $addresses)
                                ->select('address', 'images')
                                ->whereNotNull('images') // Chỉ lấy hotel có ảnh
                                ->get()
                                ->keyBy('address'); // Tạo key là address để dễ map

            // Map dữ liệu cuối cùng cho view
            $destinations = $stats->map(function($stat) use ($imagesByAddress) {
                $imagePath = null;
                $hotelWithImage = $imagesByAddress->get($stat->address); // Lấy hotel có ảnh theo address

                if ($hotelWithImage && is_array($hotelWithImage->images) && count($hotelWithImage->images) > 0) {
                    $imagePath = $hotelWithImage->images[0]; // Lấy ảnh đầu tiên
                }

                return (object) [
                    'address'        => $stat->address,
                    'total_bookings' => $stat->total_bookings,
                    'total_hotels'   => $stat->total_hotels, // << Đã có total_hotels từ query
                    'image_path'     => $imagePath,
                ];
            });

        } catch (\Exception $e) {
             Log::error('Error fetching popular destinations data: ' . $e->getMessage());
             $destinations = collect(); // Trả về collection rỗng nếu có lỗi
             // Có thể flash message cho người dùng biết lỗi nếu cần
             // session()->flash('error', 'Không thể tải dữ liệu điểm đến.');
        }

        return view('livewire.components.popular-destinations', [
            'destinations' => $destinations,
        ]);
    }
}