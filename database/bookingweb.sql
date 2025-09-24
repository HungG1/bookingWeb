-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th9 23, 2025 lúc 05:51 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `bookingweb`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `amenities`
--

CREATE TABLE `amenities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `amenities`
--

INSERT INTO `amenities` (`id`, `name`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'Hồ bơi trong nhà', 'fas fa-swimming-pool', '2025-09-23 08:27:49', '2025-09-23 08:27:49'),
(2, 'Wifi', 'fas fa-wifi', '2025-09-23 08:28:13', '2025-09-23 08:28:13'),
(3, 'Chỗ đỗ xe miễn phí', 'fas fa-parking', '2025-09-23 08:28:22', '2025-09-23 08:28:22'),
(4, 'Bữa sáng', 'fas fa-coffee', '2025-09-23 08:28:33', '2025-09-23 08:28:33'),
(5, 'Điều hòa không khí	', 'fas fa-snowflake', '2025-09-23 08:28:42', '2025-09-23 08:28:42'),
(6, 'Hệ thống cách âm', 'fas fa-volume-mute', '2025-09-23 08:28:52', '2025-09-23 08:28:52'),
(7, 'Ban công', 'far fa-window-maximize', '2025-09-23 08:29:04', '2025-09-23 08:29:04'),
(8, 'Nhà hàng', 'fas fa-utensils', '2025-09-23 08:29:21', '2025-09-23 08:29:21');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `amenity_hotel`
--

CREATE TABLE `amenity_hotel` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `amenity_id` bigint(20) UNSIGNED NOT NULL,
  `hotel_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `amenity_hotel`
--

INSERT INTO `amenity_hotel` (`id`, `amenity_id`, `hotel_id`) VALUES
(6, 1, 1),
(8, 2, 1),
(1, 3, 1),
(3, 4, 1),
(4, 5, 1),
(5, 6, 1),
(2, 7, 1),
(7, 8, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `amenity_room`
--

CREATE TABLE `amenity_room` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `amenity_id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `amenity_room`
--

INSERT INTO `amenity_room` (`id`, `amenity_id`, `room_id`) VALUES
(4, 2, 1),
(2, 5, 1),
(3, 6, 1),
(1, 7, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `hotel_id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL,
  `num_adults` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `num_children` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(255) DEFAULT NULL,
  `customer_notes` text DEFAULT NULL,
  `arrival_time` time DEFAULT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `service_fee_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `final_price` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','cancelled_by_user','cancelled_by_admin','checked_in','checked_out','no_show') NOT NULL DEFAULT 'pending',
  `payment_status` enum('unpaid','paid','partially_paid','refunded','payment_failed') NOT NULL DEFAULT 'unpaid',
  `payment_method` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel_cache_356a192b7913b04c54574d18c28d46e6395428ab', 'i:1;', 1758642543),
('laravel_cache_356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1758642543;', 1758642543),
('laravel_cache_livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3', 'i:1;', 1758635498),
('laravel_cache_livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3:timer', 'i:1758635498;', 1758635498);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Địa điểm check-in', 'dia-diem-check-in', 'Khám phá du lịch', '2025-09-23 08:45:28', '2025-09-23 08:45:28'),
(2, 'Cẩm nang du lịch', 'cam-nang-du-lich', 'Cẩm nang du lịch', '2025-09-23 08:47:19', '2025-09-23 08:47:19');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `discounts`
--

CREATE TABLE `discounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `type` enum('percentage','fixed') NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `valid_from` datetime NOT NULL,
  `valid_to` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hotels`
--

CREATE TABLE `hotels` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `description` longtext DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `star_rating` tinyint(3) UNSIGNED DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `hotels`
--

INSERT INTO `hotels` (`id`, `name`, `address`, `description`, `images`, `star_rating`, `contact_email`, `contact_phone`, `is_active`, `created_at`, `updated_at`, `is_featured`) VALUES
(1, 'Mixay Boutique Hotel Da Nang', '41 Võ Nghĩa, Đà Nẵng, Việt Nam', '<p>Nằm ở Đà Nẵng và cách Bãi biển Mỹ Khê 4 phút đi bộ, Mixay Boutique Hotel Da Nang cung cấp dịch vụ tiền sảnh, các phòng không hút thuốc, khu vườn, Wi-Fi miễn phí ở toàn bộ chỗ nghỉ và sân hiên. Ngoài nhà hàng, chỗ nghỉ còn có xe đạp miễn phí, hồ bơi trong nhà và phòng xông hơi khô. Khách sạn có phòng gia đình.<br><br>Khách sạn sẽ cung cấp cho khách các phòng được trang bị điều hòa có tủ quần áo, ấm đun nước, minibar, két an toàn, TV màn hình phẳng, ban công và phòng tắm riêng với vòi sen. Tại Mixay Boutique Hotel Da Nang, các phòng đều đi kèm với khu vực ghế ngồi.<br><br>Chỗ nghỉ có phục vụ bữa sáng thực đơn buffet hoặc kiểu lục địa.<br><br>Thành thạo tiếng Anh và tiếng Việt, đội ngũ nhân viên luôn túc trực để hỗ trợ khách tại lễ tân.<br><br>Mixay Boutique Hotel Da Nang cách Cầu sông Hàn 2.2 km và Cầu khóa Tình yêu Đà Nẵng 2.9 km. Chỗ nghỉ cách Sân bay Quốc tế Đà Nẵng 6 km và cung cấp dịch vụ đưa đón sân bay mất phí.</p>', '[\"hotels\\/01K5VK8APVVRASVQ9PSZA003JB.jpg\",\"hotels\\/01K5VK8AQEX9TB5QD8CNFRG2SG.jpg\"]', 5, 'A@gmail.com', '4234234234', 1, '2025-09-23 08:32:11', '2025-09-23 08:32:11', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_04_15_015736_create_hotels_table', 1),
(5, '2025_04_15_015805_create_rooms_table', 1),
(6, '2025_04_15_015813_create_amenities_table', 1),
(7, '2025_04_15_015823_create_amenity_hotel_table', 1),
(8, '2025_04_15_015837_create_amenity_room_table', 1),
(9, '2025_04_15_015843_create_price_rules_table', 1),
(10, '2025_04_15_015854_create_promotions_table', 1),
(11, '2025_04_15_015903_create_bookings_table', 1),
(12, '2025_04_15_015913_create_room_availabilities_table', 1),
(13, '2025_04_15_015924_create_reviews_table', 1),
(14, '2025_04_15_015933_create_categories_table', 1),
(15, '2025_04_15_015947_create_posts_table', 1),
(16, '2025_04_15_015957_create_payment_histories_table', 1),
(17, '2025_04_15_080455_remove_admin_user_id_from_posts_table', 1),
(18, '2025_04_21_102140_add_is_featured_to_hotels_table', 1),
(19, '2025_04_22_153129_create_discounts_table', 1),
(20, '2025_04_23_162017_add_arrival_time_tax_fee_to_bookings_table', 1),
(21, '2025_04_24_090749_add_image_to_promotions_table', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payment_histories`
--

CREATE TABLE `payment_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `status` enum('success','failed','pending','refunded') NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `posts`
--

INSERT INTO `posts` (`id`, `category_id`, `title`, `slug`, `content`, `image`, `status`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'Ghi nhớ những trải nghiệm du lịch biển mới ngày hè', 'ghi-nho-nhung-trai-nghiem-du-lich-bien-moi-ngay-he', '<p>&nbsp;</p><h3>1. Nha Trang và loạt môn thể thao dưới nước thú vị</h3><p>Không biết các bạn có từng du lịch biển Nha Trang và “mắt tròn mắt dẹt” ngắm nhìn nhiều người đang trải nghiệm một trò chơi “siêu anh hùng” - bay vút lên cao trên những cột nước mạnh mẽ với động tác nhẹ nhàng, phóng khoáng?&nbsp;</p><p>Có thể nói, chơi ván bay nước - hay còn gọi là Fly-board là một trong những trải nghiệm độc đáo ở Nha Trang, sử dụng động cơ ép nước áp lực siêu mạnh để có thể đẩy được người chơi lên không trung, tầm độ cao 6-9m. Tất nhiên, chúng sẽ có thiết bị điều khiển cột nước và thậm chí là hướng bay để bạn có thể thực sự “bay lượn” và nhào lộn dưới nước hoặc trên không trung.</p><p>Một trải nghiệm độc đáo khác ở Nha Trang không thể không kể đến chính là lướt sóng thuỷ lực lần đầu tiên có mặt tại Việt Nam. Bộ môn lướt sóng nhân tạo bằng thủy lực, trò chơi dựa vào sức nước nhân tạo nhằm đảm bảo bạn có thể “cưỡi sóng” bất kỳ mọi lúc, mọi nơi mà không cần chờ gió lên như bộ môn lướt sóng truyền thống. Bộ môn này đã có tại 35 nước trên thế giới, tuy vậy ở Việt Nam nó vẫn còn khá mới mẻ. Vậy thì, sao không trải nghiệm liền để là một trong những người tiên phong trải nghiệm trò chơi tuyệt vời này khi đến du lịch Nha Trang hè này thôi nào?&nbsp; &nbsp;</p><p>Đến thành phố biển xinh đẹp này rồi, chắc chắn bạn phải đến Vinpearl Land Nha Trang, nhất định rồi! Đây không chỉ là địa chỉ trải nghiệm độc đáo ở Nha Trang mà còn là một điểm vui chơi siêu đa dạng, đáp ứng được tất tần tật mọi nhu cầu và đối tượng du khách khi đến với Nha Trang du lịch. <figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:600,&quot;url&quot;:&quot;https://ik.imagekit.io/tvlk/blog/2020/05/du-lich-bien-2-EDIT.jpg?tr=q-70,c-at_max,w-500,h-300,dpr-2&quot;,&quot;width&quot;:898}\" data-trix-content-type=\"image\" data-trix-attributes=\"{&quot;caption&quot;:&quot;Vinpearl Land Nha Trang là chốn vui chơi lý tưởng cho cả gia đình.&quot;}\" class=\"attachment attachment--preview\"><img src=\"https://ik.imagekit.io/tvlk/blog/2020/05/du-lich-bien-2-EDIT.jpg?tr=q-70,c-at_max,w-500,h-300,dpr-2\" width=\"898\" height=\"600\"><figcaption class=\"attachment__caption attachment__caption--edited\">Vinpearl Land Nha Trang là chốn vui chơi lý tưởng cho cả gia đình.</figcaption></figure>Bạn cần không gian mới lạ cho cả trẻ em và người lớn tuổi? Bạn cần “ốc đảo” riêng cho một chuyến hẹn hò lãng mạn cùng người bạn kia của mình? Hay cần một khu tổ hợp với rất nhiều không gian và điểm chụp ảnh sống ảo cùng bè bạn? Dù cho là yêu cầu nào thì Vinpearl Land Nha Trang cũng vẫn sẽ có thể làm bạn hài lòng và có chuyến đi trên cả tuyệt vời!&nbsp;</p><h3>2. Đến Đà Nẵng và hòa mình vào gió, núi, mây trời</h3><p>Ngoài Bà Nà Hills với loạt trải nghiệm độc đáo, ngỡ như lạc vào thế giới cổ tích, nức tiếng gần xa thì Đà Nẵng cũng mang tới trải nghiệm một môn thể thao cũng mạo hiểm hấp dẫn không kém là cano kéo dù nước. <figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:527,&quot;url&quot;:&quot;https://ik.imagekit.io/tvlk/blog/2020/06/du-lich-bien-3-edit-1.jpg?tr=q-70,c-at_max,w-500,h-300,dpr-2&quot;,&quot;width&quot;:1000}\" data-trix-content-type=\"image\" data-trix-attributes=\"{&quot;caption&quot;:&quot;Vui chơi ở Vườn Thượng Uyển&quot;}\" class=\"attachment attachment--preview\"><img src=\"https://ik.imagekit.io/tvlk/blog/2020/06/du-lich-bien-3-edit-1.jpg?tr=q-70,c-at_max,w-500,h-300,dpr-2\" width=\"1000\" height=\"527\"><figcaption class=\"attachment__caption attachment__caption--edited\">Vui chơi ở Vườn Thượng Uyển</figcaption></figure>Dù bay nương theo sức gió để vận hành nên từ trên độ cao lơ lửng 70 - 100m, bạn có thể thỏa thích chiêm ngưỡng cảnh sắc bao la xung quanh, tận hưởng cảm giác nghiêng mình theo gió, và đặc biệt sẽ cực thu hút với những ai đam mê độ cao, khi cảm nhận bản thân đang trôi giữa mây trời và phía dưới chân là biển bao la.&nbsp;</p><h3>3. Đắm mình vào vẻ đẹp của các hòn đảo Phú Quốc</h3><p>Quần đảo Phú Quốc với muôn vàn hòn đảo xinh đẹp, làm lay động lòng người nhưng nếu không đủ thời gian thăm thú hết thì nên dành sự ưu tiên cho đảo nào đây? <figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:600,&quot;url&quot;:&quot;https://ik.imagekit.io/tvlk/blog/2020/06/du-lich-bien-7-edit.jpg?tr=q-70,c-at_max,w-500,h-300,dpr-2&quot;,&quot;width&quot;:801}\" data-trix-content-type=\"image\" data-trix-attributes=\"{&quot;caption&quot;:&quot;Vẻ đẹp tráng lệ của hòn Mây Rút Phú Quốc.&quot;}\" class=\"attachment attachment--preview\"><img src=\"https://ik.imagekit.io/tvlk/blog/2020/06/du-lich-bien-7-edit.jpg?tr=q-70,c-at_max,w-500,h-300,dpr-2\" width=\"801\" height=\"600\"><figcaption class=\"attachment__caption attachment__caption--edited\">Vẻ đẹp tráng lệ của hòn Mây Rút Phú Quốc.</figcaption></figure>Nếu thích ngắm nước biển trong xanh, thấy từng rặng dừa in bóng trên mặt nước hay bãi cát dài trắng mịn, thưởng thức bữa hải sản tươi rói trên tàu và thoải mái nhảy ùm xuống biển thì bạn nên chọn đi tour các đảo phía Nam. Trong một ngày bạn sẽ được chiêm ngưỡng bốn đảo: Hòn Móng Tay, Hòn Gầm Ghì, Hòn Mây Rút, Hòn Thơm…&nbsp;</p><p>Còn nếu bạn thích văn hoá, muốn trải nghiệm và tham quan các làng nghề, đặc biệt trại ong, vườn tiêu Phú Quốc và muốn thử chèo Kayak trên biển thì nên chọn đi tour phía Bắc đảo. Còn nếu chỉ muốn hoà mình vào biển, biển, biển, muốn phải ồ à ngợi khen mà vẫn có thời gian tận hưởng thì nên đi ba đảo Hòn Móng Tay, Hòn Gầm Ghì, Hòn Mây Rút là cũng đủ tuyệt vời rồi nhé.</p><p>Ngoài ra, bạn cũng có thể chọn trải nghiệm thú vị khác là ngắm nhìn hoàng hôn giữa lòng đại dương kết hợp trải nghiệm câu mực sau khi mặt trời lặn hoặc vui chơi ở Vinpearl Land Phú Quốc - mới được tân trang và nhiều trò chơi thú vị.&nbsp;</p>', 'posts/01K5VMAVBFWVK2DR2D4GTMJF7F.jpg', 'published', '2025-09-23 08:47:54', '2025-09-23 08:51:02', '2025-09-23 08:51:02');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `price_rules`
--

CREATE TABLE `price_rules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `days_of_week` varchar(255) DEFAULT NULL,
  `price_modifier_type` enum('fixed_amount','percentage') NOT NULL,
  `price_modifier_value` decimal(10,2) NOT NULL,
  `priority` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `promotions`
--

CREATE TABLE `promotions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `discount_type` enum('percentage','fixed_amount') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `min_spend` decimal(10,2) DEFAULT NULL,
  `usage_limit` int(10) UNSIGNED DEFAULT NULL,
  `used_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `promotions`
--

INSERT INTO `promotions` (`id`, `name`, `description`, `image`, `code`, `discount_type`, `discount_value`, `start_date`, `end_date`, `min_spend`, `usage_limit`, `used_count`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Kỳ nghỉ ngắn ngày chất lượng', 'Tiết kiệm đến 20% với Ưu Đãi Mùa Du Lịch', 'promotions/01K5VKN68RY1FFP0FTFBB8WW8Z.jpg', NULL, 'percentage', 20.00, '2025-09-22 17:00:00', '2025-09-29 17:00:00', 3000000.00, 100, 0, 1, '2025-09-23 08:39:13', '2025-09-23 08:39:13');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `hotel_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `comment` text NOT NULL,
  `admin_reply` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `rooms`
--

CREATE TABLE `rooms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hotel_id` bigint(20) UNSIGNED NOT NULL,
  `room_type_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `number_of_rooms` int(10) UNSIGNED NOT NULL,
  `max_occupancy` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `rooms`
--

INSERT INTO `rooms` (`id`, `hotel_id`, `room_type_name`, `description`, `base_price`, `number_of_rooms`, `max_occupancy`, `images`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Suite Studio with Garden View, Balcony and Bathtub', 'Kích thước phòng 36 m²\n1 giường đôi cực lớn \nGiường thoải mái, 9 – Dựa trên 71 đánh giá\nThe air-conditioned suite has 1 bedroom and 1 bathroom with a bath and a shower. The suite\'s kitchenette, which features an electric kettle, is available for cooking and storing food. Boasting a balcony with garden views, this suite also offers soundproof walls and a flat-screen TV. The unit has 1 bed.', 5000000.00, 10, 2, '[\"rooms\\/01K5VKB8ACW5XVYXC3AYQZZ1XK.jpg\",\"rooms\\/01K5VKB8AEDNDYQH84CCKN013Y.jpg\"]', 1, '2025-09-23 08:33:47', '2025-09-23 08:33:47');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `room_availabilities`
--

CREATE TABLE `room_availabilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `available_count` int(10) UNSIGNED NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('OZjDHlzaEKUWqmEJGaSh62LvvvE7pOywGD6qtqAM', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoieXVhSnNPYlZaa3g1cGc4dTREdTdaVlh2em1haENiQWdyazdXS1hGVSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjA6IiQyeSQxMiRZOG15VVo4S082SFJtLnkyNldmRmJPd0tuc2ttZ2QuSHR4ZVM5Q2d1Z3g1L2dxMjBEeTcwQyI7czo4OiJmaWxhbWVudCI7YTowOnt9fQ==', 1758642684);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `phone_number`, `address`, `avatar`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@gmail.com', NULL, '$2y$12$Y8myUZ8KO6HRm.y26WfFbOwKnskmgd.HtxeS9Cgugx5/gq20Dy70C', NULL, NULL, NULL, 'Kj9dSq4SftlqnD8i402A0bTIUzxHRTxz6wbL4jAUrRohwNfysGf7PdzRWo79', '2025-09-22 08:08:45', '2025-09-23 06:41:55'),
(2, 'hung', 'hung@gmail.com', NULL, '$2y$12$n1McYgyrOuu8BQ2QL2IBWe.WwAZ4gV1XPoWc7Igl9ZUp30Zs6QEZW', NULL, NULL, NULL, NULL, '2025-09-23 06:06:29', '2025-09-23 06:06:29');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `amenities`
--
ALTER TABLE `amenities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `amenities_name_unique` (`name`);

--
-- Chỉ mục cho bảng `amenity_hotel`
--
ALTER TABLE `amenity_hotel`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `amenity_hotel_amenity_id_hotel_id_unique` (`amenity_id`,`hotel_id`),
  ADD KEY `amenity_hotel_hotel_id_foreign` (`hotel_id`);

--
-- Chỉ mục cho bảng `amenity_room`
--
ALTER TABLE `amenity_room`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `amenity_room_amenity_id_room_id_unique` (`amenity_id`,`room_id`),
  ADD KEY `amenity_room_room_id_foreign` (`room_id`);

--
-- Chỉ mục cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookings_user_id_foreign` (`user_id`),
  ADD KEY `bookings_hotel_id_foreign` (`hotel_id`),
  ADD KEY `bookings_room_id_foreign` (`room_id`),
  ADD KEY `bookings_check_in_date_check_out_date_index` (`check_in_date`,`check_out_date`),
  ADD KEY `bookings_transaction_id_index` (`transaction_id`);

--
-- Chỉ mục cho bảng `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Chỉ mục cho bảng `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `discounts_code_unique` (`code`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Chỉ mục cho bảng `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Chỉ mục cho bảng `payment_histories`
--
ALTER TABLE `payment_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_histories_booking_id_foreign` (`booking_id`),
  ADD KEY `payment_histories_transaction_id_index` (`transaction_id`);

--
-- Chỉ mục cho bảng `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `posts_slug_unique` (`slug`),
  ADD KEY `posts_category_id_foreign` (`category_id`);

--
-- Chỉ mục cho bảng `price_rules`
--
ALTER TABLE `price_rules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `price_rules_room_id_foreign` (`room_id`);

--
-- Chỉ mục cho bảng `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `promotions_code_unique` (`code`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_booking_id_foreign` (`booking_id`),
  ADD KEY `reviews_user_id_foreign` (`user_id`),
  ADD KEY `reviews_hotel_id_foreign` (`hotel_id`);

--
-- Chỉ mục cho bảng `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rooms_hotel_id_foreign` (`hotel_id`);

--
-- Chỉ mục cho bảng `room_availabilities`
--
ALTER TABLE `room_availabilities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `room_availabilities_room_id_date_unique` (`room_id`,`date`);

--
-- Chỉ mục cho bảng `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `amenities`
--
ALTER TABLE `amenities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `amenity_hotel`
--
ALTER TABLE `amenity_hotel`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `amenity_room`
--
ALTER TABLE `amenity_room`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `hotels`
--
ALTER TABLE `hotels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT cho bảng `payment_histories`
--
ALTER TABLE `payment_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `price_rules`
--
ALTER TABLE `price_rules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `room_availabilities`
--
ALTER TABLE `room_availabilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `amenity_hotel`
--
ALTER TABLE `amenity_hotel`
  ADD CONSTRAINT `amenity_hotel_amenity_id_foreign` FOREIGN KEY (`amenity_id`) REFERENCES `amenities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `amenity_hotel_hotel_id_foreign` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `amenity_room`
--
ALTER TABLE `amenity_room`
  ADD CONSTRAINT `amenity_room_amenity_id_foreign` FOREIGN KEY (`amenity_id`) REFERENCES `amenities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `amenity_room_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_hotel_id_foreign` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `payment_histories`
--
ALTER TABLE `payment_histories`
  ADD CONSTRAINT `payment_histories_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `price_rules`
--
ALTER TABLE `price_rules`
  ADD CONSTRAINT `price_rules_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `reviews_hotel_id_foreign` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_hotel_id_foreign` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `room_availabilities`
--
ALTER TABLE `room_availabilities`
  ADD CONSTRAINT `room_availabilities_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
