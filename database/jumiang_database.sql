-- ===================================================================
-- JUMIANG SMART TOURISM & UMKM
-- DATABASE INITIALIZATION & SEED SCRIPT (MySQL 8.4 / MariaDB compatible)
-- Studi Kasus: Pantai Jumiang, Desa Tanjung, Kec. Pademawu, Pamekasan
-- Semua data awal dilabeli secara transparan: [DATA DEMO / SIMULASI]
-- ===================================================================

CREATE DATABASE IF NOT EXISTS `jumiang_tourism` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `jumiang_tourism`;

SET FOREIGN_KEY_CHECKS = 0;

-- Drop tables if exist
DROP TABLE IF EXISTS `audit_logs`;
DROP TABLE IF EXISTS `partnerships`;
DROP TABLE IF EXISTS `reservations`;
DROP TABLE IF EXISTS `financial_transactions`;
DROP TABLE IF EXISTS `financial_categories`;
DROP TABLE IF EXISTS `financial_accounts`;
DROP TABLE IF EXISTS `payments`;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `inventory_logs`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `product_categories`;
DROP TABLE IF EXISTS `umkm_merchants`;
DROP TABLE IF EXISTS `map_markers`;
DROP TABLE IF EXISTS `galleries`;
DROP TABLE IF EXISTS `facilities`;
DROP TABLE IF EXISTS `attractions`;
DROP TABLE IF EXISTS `destinations`;
DROP TABLE IF EXISTS `user_profiles`;
DROP TABLE IF EXISTS `role_user`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `roles`;

-- 1. ROLES
CREATE TABLE `roles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL UNIQUE,
  `display_name` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. USERS
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(191) NOT NULL UNIQUE,
  `phone_number` VARCHAR(30) NULL,
  `password` VARCHAR(255) NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `remember_token` VARCHAR(100) NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. ROLE_USER
CREATE TABLE `role_user` (
  `user_id` BIGINT UNSIGNED NOT NULL,
  `role_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`, `role_id`),
  CONSTRAINT `fk_ru_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ru_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. USER_PROFILES
CREATE TABLE `user_profiles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL UNIQUE,
  `address` TEXT NULL,
  `avatar_url` VARCHAR(255) NULL,
  `bio` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_up_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. DESTINATIONS
CREATE TABLE `destinations` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(191) NOT NULL UNIQUE,
  `description` LONGTEXT NOT NULL,
  `village` VARCHAR(100) NOT NULL DEFAULT 'Tanjung',
  `district` VARCHAR(100) NOT NULL DEFAULT 'Pademawu',
  `regency` VARCHAR(100) NOT NULL DEFAULT 'Pamekasan',
  `operating_hours` VARCHAR(100) NOT NULL,
  `ticket_price_demo` DECIMAL(12,2) NOT NULL DEFAULT 10000.00,
  `is_demo_data` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. ATTRACTIONS
CREATE TABLE `attractions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `destination_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `description` TEXT,
  `safety_level` ENUM('SAFE', 'WARNING', 'RESTRICTED') NOT NULL DEFAULT 'SAFE',
  `cover_image` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_att_dest` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. FACILITIES
CREATE TABLE `facilities` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `destination_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `category` VARCHAR(50) NOT NULL,
  `condition` ENUM('BAIK', 'PERBAIKAN', 'RUSAK') NOT NULL DEFAULT 'BAIK',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_fac_dest` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. GALLERIES
CREATE TABLE `galleries` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `destination_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `caption` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_gal_dest` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. MAP_MARKERS (Leaflet GIS WGS84)
CREATE TABLE `map_markers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category` ENUM('WISATA', 'UMKM', 'FASILITAS', 'HOMESTAY', 'PARKIR') NOT NULL DEFAULT 'WISATA',
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT,
  `latitude` DECIMAL(10,8) NOT NULL,
  `longitude` DECIMAL(11,8) NOT NULL,
  `icon_type` VARCHAR(50) NOT NULL DEFAULT 'default',
  `related_entity_type` VARCHAR(100) NULL,
  `related_entity_id` BIGINT UNSIGNED NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_marker_cat` (`category`, `is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. UMKM_MERCHANTS
CREATE TABLE `umkm_merchants` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `business_name` VARCHAR(150) NOT NULL,
  `owner_name` VARCHAR(150) NOT NULL,
  `category` VARCHAR(50) NOT NULL DEFAULT 'KULINER',
  `address` TEXT NOT NULL,
  `phone_number` VARCHAR(30) NOT NULL,
  `logo_path` VARCHAR(255) NULL,
  `banner_path` VARCHAR(255) NULL,
  `verification_status` ENUM('PENDING', 'VERIFIED', 'REJECTED', 'SUSPENDED') NOT NULL DEFAULT 'PENDING',
  `rejection_reason` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_umkm_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  INDEX `idx_umkm_status` (`verification_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. PRODUCT_CATEGORIES
CREATE TABLE `product_categories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(120) NOT NULL UNIQUE,
  `icon` VARCHAR(50) NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. PRODUCTS
CREATE TABLE `products` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `umkm_merchant_id` BIGINT UNSIGNED NOT NULL,
  `category_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(191) NOT NULL UNIQUE,
  `description` TEXT,
  `price` DECIMAL(12,2) NOT NULL,
  `stock` INT NOT NULL DEFAULT 0,
  `low_stock_threshold` INT NOT NULL DEFAULT 5,
  `unit` VARCHAR(30) NOT NULL DEFAULT 'pcs',
  `image_path` VARCHAR(255) NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_prod_umkm` FOREIGN KEY (`umkm_merchant_id`) REFERENCES `umkm_merchants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_prod_cat` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`),
  INDEX `idx_prod_umkm_active` (`umkm_merchant_id`, `is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. INVENTORY_LOGS
CREATE TABLE `inventory_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `type` ENUM('IN', 'OUT', 'ADJUSTMENT') NOT NULL,
  `quantity` INT NOT NULL,
  `previous_stock` INT NOT NULL,
  `current_stock` INT NOT NULL,
  `reference_type` VARCHAR(50) NULL,
  `reference_id` BIGINT UNSIGNED NULL,
  `notes` VARCHAR(255) NULL,
  `created_by` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_inv_prod` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_inv_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  INDEX `idx_inv_prod_time` (`product_id`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. ORDERS
CREATE TABLE `orders` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_no` VARCHAR(50) NOT NULL UNIQUE,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `umkm_merchant_id` BIGINT UNSIGNED NOT NULL,
  `total_amount` DECIMAL(12,2) NOT NULL,
  `status` ENUM('PENDING', 'PAID', 'PROCESSING', 'READY_FOR_PICKUP', 'COMPLETED', 'CANCELLED') NOT NULL DEFAULT 'PENDING',
  `delivery_option` ENUM('PICKUP', 'DELIVERY') NOT NULL DEFAULT 'PICKUP',
  `delivery_notes` TEXT NULL,
  `notes` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_ord_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_ord_umkm` FOREIGN KEY (`umkm_merchant_id`) REFERENCES `umkm_merchants` (`id`),
  INDEX `idx_ord_merchant_stat` (`umkm_merchant_id`, `status`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 15. ORDER_ITEMS
CREATE TABLE `order_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` BIGINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `price_at_purchase` DECIMAL(12,2) NOT NULL,
  `quantity` INT NOT NULL,
  `subtotal` DECIMAL(12,2) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_item_ord` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_item_prod` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 16. PAYMENTS (Simulasi Transaksi)
CREATE TABLE `payments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` BIGINT UNSIGNED NOT NULL UNIQUE,
  `payment_method` ENUM('SIMULATION_QRIS', 'SIMULATION_VA', 'CASH_ON_LOCATION') NOT NULL DEFAULT 'SIMULATION_QRIS',
  `payment_status` ENUM('UNPAID', 'PAID', 'EXPIRED', 'REFUNDED') NOT NULL DEFAULT 'UNPAID',
  `simulation_ref_no` VARCHAR(100) NOT NULL,
  `paid_at` TIMESTAMP NULL,
  `is_demo_gateway` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_pay_ord` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 17. FINANCIAL_ACCOUNTS (Pemisahan Akun Kas Usaha vs Kas Pribadi)
CREATE TABLE `financial_accounts` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `umkm_merchant_id` BIGINT UNSIGNED NOT NULL,
  `account_name` VARCHAR(100) NOT NULL,
  `account_type` ENUM('BUSINESS', 'PERSONAL') NOT NULL DEFAULT 'BUSINESS',
  `initial_balance` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `current_balance` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_fa_umkm` FOREIGN KEY (`umkm_merchant_id`) REFERENCES `umkm_merchants` (`id`) ON DELETE CASCADE,
  INDEX `idx_fa_merchant_type` (`umkm_merchant_id`, `account_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 18. FINANCIAL_CATEGORIES
CREATE TABLE `financial_categories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `type` ENUM('INCOME', 'EXPENSE') NOT NULL,
  `is_system_default` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 19. FINANCIAL_TRANSACTIONS (Jurnal Kas Harian)
CREATE TABLE `financial_transactions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `umkm_merchant_id` BIGINT UNSIGNED NOT NULL,
  `financial_account_id` BIGINT UNSIGNED NOT NULL,
  `category_id` BIGINT UNSIGNED NOT NULL,
  `order_id` BIGINT UNSIGNED NULL,
  `transaction_type` ENUM('INCOME', 'EXPENSE') NOT NULL,
  `amount` DECIMAL(14,2) NOT NULL,
  `transaction_date` DATE NOT NULL,
  `description` TEXT NOT NULL,
  `receipt_image` VARCHAR(255) NULL,
  `created_by` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_ft_umkm` FOREIGN KEY (`umkm_merchant_id`) REFERENCES `umkm_merchants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ft_fa` FOREIGN KEY (`financial_account_id`) REFERENCES `financial_accounts` (`id`),
  CONSTRAINT `fk_ft_cat` FOREIGN KEY (`category_id`) REFERENCES `financial_categories` (`id`),
  CONSTRAINT `fk_ft_ord` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_ft_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  INDEX `idx_ft_merchant_date` (`umkm_merchant_id`, `transaction_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 20. RESERVATIONS
CREATE TABLE `reservations` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_code` VARCHAR(50) NOT NULL UNIQUE,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `destination_id` BIGINT UNSIGNED NOT NULL,
  `visit_date` DATE NOT NULL,
  `visitor_count` INT NOT NULL DEFAULT 1,
  `package_name` VARCHAR(100) NOT NULL DEFAULT 'Tiket Masuk Standar',
  `total_price` DECIMAL(12,2) NOT NULL,
  `status` ENUM('PENDING', 'CONFIRMED', 'ATTENDED', 'CANCELLED') NOT NULL DEFAULT 'PENDING',
  `check_in_time` TIMESTAMP NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_res_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_res_dest` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`),
  INDEX `idx_res_date_status` (`visit_date`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 21. PARTNERSHIPS
CREATE TABLE `partnerships` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `partner_name` VARCHAR(150) NOT NULL,
  `partnership_type` VARCHAR(100) NOT NULL,
  `contact_person` VARCHAR(100) NOT NULL,
  `phone_number` VARCHAR(30) NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NULL,
  `status` ENUM('ACTIVE', 'PENDING', 'EXPIRED', 'TERMINATED') NOT NULL DEFAULT 'ACTIVE',
  `document_url` VARCHAR(255) NULL,
  `notes` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 22. AUDIT_LOGS
CREATE TABLE `audit_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NULL,
  `action` VARCHAR(100) NOT NULL,
  `entity_type` VARCHAR(100) NULL,
  `entity_id` BIGINT UNSIGNED NULL,
  `old_values` JSON NULL,
  `new_values` JSON NULL,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` TEXT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_audit_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  INDEX `idx_audit_entity` (`entity_type`, `entity_id`),
  INDEX `idx_audit_time` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================================
-- SEED DATA (DATA DEMO / SIMULASI PANTAI JUMIANG)
-- ===================================================================

-- 1. ROLES
INSERT INTO `roles` (`id`, `name`, `display_name`, `description`) VALUES
(1, 'super_admin', 'Super Admin', 'Pengelola teknis dengan kontrol penuh atas seluruh modul dan sistem.'),
(2, 'pengelola_wisata', 'Pengelola Wisata', 'Badan pengelola destinasi wisata Pantai Jumiang, fasilitas, dan tiket.'),
(3, 'pelaku_umkm', 'Pelaku UMKM', 'Mitra pedagang/pengrajin lokal pengelola produk, stok, dan kas mandiri.'),
(4, 'bendahara', 'Bendahara', 'Pengawas arus kas kawasan, rekonsiliasi pembayaran, dan audit finansial.'),
(5, 'pimpinan', 'Pimpinan / Eksekutif', 'Kepala Desa / Pejabat Dinas pengakses dashboard analitik strategis (EIS).'),
(6, 'wisatawan', 'Wisatawan', 'Pengunjung umum pembeli produk UMKM dan pemesan tiket/paket wisata.');

-- 2. USERS (Password default hash bcrypt untuk: 'Password123!')
-- Hash: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
INSERT INTO `users` (`id`, `name`, `email`, `phone_number`, `password`, `is_active`) VALUES
(1, 'Administrator Jumiang', 'admin@jumiang.test', '081234567890', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
(2, 'Pokdarwis Pantai Jumiang', 'pengelola@jumiang.test', '081234567891', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
(3, 'Ibu Maryam (Sentra Lorjuk)', 'umkm.maryam@jumiang.test', '081234567892', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
(4, 'Pak Hidayat (Batik Pesisir)', 'umkm.hidayat@jumiang.test', '081234567893', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
(5, 'Ibu Sugiarti (Homestay Jumiang)', 'umkm.sugiarti@jumiang.test', '081234567894', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
(6, 'Bendahara Kawasan Jumiang', 'bendahara@jumiang.test', '081234567895', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
(7, 'Kepala Desa Tanjung (Pimpinan)', 'pimpinan@jumiang.test', '081234567896', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
(8, 'Budi Wisatawan (Pengunjung)', 'wisatawan@jumiang.test', '081234567897', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);

-- 3. ASSIGN ROLES
INSERT INTO `role_user` (`user_id`, `role_id`) VALUES
(1, 1), -- Super Admin
(2, 2), -- Pengelola Wisata
(3, 3), -- Pelaku UMKM Maryam
(4, 3), -- Pelaku UMKM Hidayat
(5, 3), -- Pelaku UMKM Sugiarti
(6, 4), -- Bendahara
(7, 5), -- Pimpinan
(8, 6); -- Wisatawan

-- 4. USER PROFILES
INSERT INTO `user_profiles` (`id`, `user_id`, `address`, `bio`) VALUES
(1, 1, 'Kantor Layanan TI Pemkab Pamekasan', 'Super Administrator Sistem Informasi Jumiang Smart Tourism.'),
(2, 2, 'Sekretariat Pokdarwis Desa Tanjung, Pademawu', 'Badan Pengelola Destinasi Bahari dan Fasilitas Pantai Jumiang.'),
(3, 3, 'Sentra Olahan Pesisir RT 02 RW 01 Desa Tanjung', 'Pelaku UMKM spesialis olahan laut lorjuk, rengginang, dan keripik ikan.'),
(4, 4, 'Kampung Batik Tanjung, Pademawu, Pamekasan', 'Pengrajin kain dan busana batik tulis pesisir khas Madura.'),
(5, 5, 'Dusun Jumiang Pesisir, Desa Tanjung', 'Pengelola Homestay Bahari dan penginapan ramah keluarga.'),
(6, 6, 'Balai Desa Tanjung, Pademawu, Pamekasan', 'Pengelola kas dan rekonsiliasi keuangan kawasan wisata.'),
(7, 7, 'Kantor Kepala Desa Tanjung, Pademawu', 'Pimpinan pengambil kebijakan pengembangan potensi desa wisata.'),
(8, 8, 'Jl. Trunojoyo, Surabaya', 'Wisatawan penikmat wisata bahari dan kuliner lokal.');

-- 5. DESTINATION (PANTAI JUMIANG)
INSERT INTO `destinations` (`id`, `name`, `slug`, `description`, `village`, `district`, `regency`, `operating_hours`, `ticket_price_demo`, `is_demo_data`) VALUES
(1, 'Pantai Jumiang Bahari', 'pantai-jumiang-bahari', 'Pantai Jumiang terletak di Desa Tanjung, Kecamatan Pademawu, sekitar 12 km ke arah tenggara dari pusat Kabupaten Pamekasan. Destinasi ini menyajikan perpaduan pemandangan tebing karang tinggi yang menghadap ke Selat Madura dan hamparan pantai landai dengan pasir putih kecokelatan. Pantai ini juga dikenal sebagai sentra penghasil komoditas lorjuk serta ekosistem budidaya rumput laut yang memberdayakan masyarakat pesisir.', 'Tanjung', 'Pademawu', 'Pamekasan', '06:00 - 18:00 WIB', 10000.00, 1);

-- 6. ATTRACTIONS
INSERT INTO `attractions` (`id`, `destination_id`, `name`, `description`, `safety_level`, `cover_image`) VALUES
(1, 1, 'Puncak Tebing Jumiang', 'Spot tebing karang ikonik dengan pemandangan 180 derajat lautan lepas dan formasi bebatuan eksotis.', 'WARNING', 'assets/img/tebing_jumiang.jpg'),
(2, 1, 'Area Pantai Landai & Bermain Pasir', 'Kawasan pesisir pantai dengan ombak tenang yang aman untuk anak-anak dan keluarga.', 'SAFE', 'assets/img/pantai_pasir.jpg'),
(3, 1, 'Sunset Point Jumiang', 'Titik terbaik menikmati panorama matahari terbenam dengan siluet perahu nelayan tradisional.', 'SAFE', 'assets/img/sunset_point.jpg'),
(4, 1, 'Kawasan Hutan Mangrove & Edukasi', 'Jalur treking kayu melintasi ekosistem hutan bakau pelindung abrasi pantai.', 'SAFE', 'assets/img/mangrove.jpg');

-- 7. FACILITIES
INSERT INTO `facilities` (`id`, `destination_id`, `name`, `category`, `condition`) VALUES
(1, 1, 'Musholla Nurul Bahari', 'IBADAH', 'BAIK'),
(2, 1, 'Toilet & Kamar Bilas Bilas Air Tawar', 'SANITASI', 'BAIK'),
(3, 1, 'Gazebo Santai Pesisir (12 Unit)', 'ISTIRAHAT', 'BAIK'),
(4, 1, 'Area Parkir Utama Mobil & Bus Wisata', 'PARKIR', 'BAIK'),
(5, 1, 'Pusat Informasi & Pos Pertolongan Pertama (P3K)', 'LAYANAN', 'BAIK');

-- 8. MAP_MARKERS (WGS84 Coordinates Pantai Jumiang)
-- Pantai Jumiang terletak di kisaran koordinat: Lat -7.2289, Long 113.5381
INSERT INTO `map_markers` (`id`, `category`, `title`, `description`, `latitude`, `longitude`, `icon_type`, `is_active`) VALUES
(1, 'WISATA', 'Puncak Tebing Karang Jumiang', 'Spot pemandangan laut dari atas tebing karang tinggi.', -7.22891200, 113.53812000, 'camera', 1),
(2, 'WISATA', 'Sunset Point Pantai Jumiang', 'Titik menikmati matahari terbenam terbaik di tebing selatan.', -7.22945000, 113.53754000, 'sun', 1),
(3, 'UMKM', 'Gerai Sentra Lorjuk Bu Maryam', 'Pusat oleh-oleh rengginang lorjuk, keripik ikan, dan terasi khas Jumiang.', -7.22780000, 113.53690000, 'shop', 1),
(4, 'UMKM', 'Galeri Batik Pesisir Tanjung', 'Kain batik tulis corak bahari khas nelayan Desa Tanjung.', -7.22720000, 113.53610000, 'palette', 1),
(5, 'HOMESTAY', 'Homestay Bahari Bu Sugiarti', 'Penginapan tepi pantai berfasilitas lengkap dengan suasana desa nelayan.', -7.22650000, 113.53580000, 'home', 1),
(6, 'FASILITAS', 'Musholla Nurul Bahari & Toilet Umum', 'Fasilitas ibadah dan kamar bilas air tawar.', -7.22810000, 113.53730000, 'life-preserver', 1),
(7, 'PARKIR', 'Area Parkir Utama Wisata Pantai Jumiang', 'Kapasitas muat 50 mobil dan 200 sepeda motor.', -7.22690000, 113.53650000, 'truck', 1);

-- 9. UMKM MERCHANTS
INSERT INTO `umkm_merchants` (`id`, `user_id`, `business_name`, `owner_name`, `category`, `address`, `phone_number`, `verification_status`) VALUES
(1, 3, 'Sentra Olahan Lorjuk Bu Maryam', 'Ibu Maryam', 'KULINER', 'Sentra Kuliner Pesisir No. 04, Pantai Jumiang', '081234567892', 'VERIFIED'),
(2, 4, 'Batik Pesisir Jumiang Indah', 'Pak Hidayat', 'KERAJINAN', 'Sentra Seni & Kerajinan Tanjung No. 12, Pamekasan', '081234567893', 'VERIFIED'),
(3, 5, 'Homestay Bahari Jumiang Asri', 'Ibu Sugiarti', 'HOMESTAY', 'Dusun Nelayan Timur, Desa Tanjung, Pademawu', '081234567894', 'VERIFIED');

-- 10. PRODUCT CATEGORIES
INSERT INTO `product_categories` (`id`, `name`, `slug`, `icon`) VALUES
(1, 'Kuliner & Oleh-Oleh Laut', 'kuliner-oleh-oleh-laut', 'bi-cup-hot'),
(2, 'Batik & Busana Lokal', 'batik-busana-lokal', 'bi-palette'),
(3, 'Cenderamata & Kerajinan', 'cenderamata-kerajinan', 'bi-gem'),
(4, 'Penginapan & Homestay', 'penginapan-homestay', 'bi-house-door');

-- 11. PRODUCTS
INSERT INTO `products` (`id`, `umkm_merchant_id`, `category_id`, `name`, `slug`, `description`, `price`, `stock`, `low_stock_threshold`, `unit`, `is_active`) VALUES
(1, 1, 1, 'Rengginang Lorjuk Super Khas Jumiang', 'rengginang-lorjuk-super-khas-jumiang', 'Rengginang gurih dengan taburan lorjuk (kerang bambu) segar tangkapan pesisir Pantai Jumiang. Isi 500 gram, renyah dan nikmat.', 35000.00, 45, 5, 'bungkus', 1),
(2, 1, 1, 'Keripik Ikan Teri Krispi Gurih', 'keripik-ikan-teri-krispi-gurih', 'Olahan teri nasi segar dengan balutan tepung bumbu rempah Madura. Berat bersih 250 gram.', 20000.00, 30, 5, 'bungkus', 1),
(3, 1, 1, 'Terasi Asli Tanjung Madura (Super)', 'terasi-asli-tanjung-madura-super', 'Terasi rebon murni tanpa bahan pengawet buatan, aroma khas dan lezat untuk sambal.', 15000.00, 60, 10, 'pcs', 1),
(4, 2, 2, 'Kain Batik Tulis Motif Bahari Pamekasan', 'kain-batik-tulis-motif-bahari-pamekasan', 'Kain batik tulis halus dengan corak ombak, terumbu karang, dan perahu nelayan Jumiang. Ukuran 2x1.15 meter.', 175000.00, 8, 2, 'lembar', 1),
(5, 3, 4, 'Sewa Kamar Homestay Standard View Laut', 'sewa-kamar-homestay-standard-view-laut', 'Kamar homestay ber-AC, kamar mandi dalam, sarapan khas pesisir, dan pemandangan laut.', 250000.00, 4, 1, 'malam', 1);

-- 12. INVENTORY LOGS
INSERT INTO `inventory_logs` (`id`, `product_id`, `type`, `quantity`, `previous_stock`, `current_stock`, `reference_type`, `notes`, `created_by`) VALUES
(1, 1, 'IN', 50, 0, 50, 'INITIAL_STOCK', 'Stok awal produksi musim tangkap lorjuk', 3),
(2, 1, 'OUT', 5, 50, 45, 'ORDER_PURCHASE', 'Pembelian invoice #INV-JMI-20261102-001', 3),
(3, 2, 'IN', 30, 0, 30, 'INITIAL_STOCK', 'Stok awal produksi keripik teri', 3),
(4, 3, 'IN', 60, 0, 60, 'INITIAL_STOCK', 'Stok fermentasi terasi matang', 3),
(5, 4, 'IN', 10, 0, 10, 'INITIAL_STOCK', 'Hasil celupan canting batik tulis', 4),
(6, 4, 'OUT', 2, 10, 8, 'ORDER_PURCHASE', 'Pembelian invoice #INV-JMI-20261102-002', 4);

-- 13. ORDERS
INSERT INTO `orders` (`id`, `invoice_no`, `user_id`, `umkm_merchant_id`, `total_amount`, `status`, `delivery_option`, `delivery_notes`, `notes`) VALUES
(1, 'INV-JMI-20261102-001', 8, 1, 175000.00, 'COMPLETED', 'PICKUP', 'Ambil di gerai setelah melihat sunset', 'Pesanan paket oleh-oleh rengginang lorjuk'),
(2, 'INV-JMI-20261102-002', 8, 2, 350000.00, 'PAID', 'DELIVERY', 'Tolong antar ke Homestay Bu Sugiarti Kamar No. 2', 'Batik corak bahari warna biru');

-- 14. ORDER_ITEMS
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `price_at_purchase`, `quantity`, `subtotal`) VALUES
(1, 1, 1, 35000.00, 5, 175000.00),
(2, 2, 4, 175000.00, 2, 350000.00);

-- 15. PAYMENTS (Simulasi Digital Gateway)
INSERT INTO `payments` (`id`, `order_id`, `payment_method`, `payment_status`, `simulation_ref_no`, `paid_at`, `is_demo_gateway`) VALUES
(1, 1, 'SIMULATION_QRIS', 'PAID', 'QRIS-SIM-20261102-88912', CURRENT_TIMESTAMP, 1),
(2, 2, 'SIMULATION_VA', 'PAID', 'VA-SIM-9988273612-002', CURRENT_TIMESTAMP, 1);

-- 16. FINANCIAL_ACCOUNTS (Pemisahan Buku Kas Mandiri per UMKM)
INSERT INTO `financial_accounts` (`id`, `umkm_merchant_id`, `account_name`, `account_type`, `initial_balance`, `current_balance`) VALUES
(1, 1, 'Kas Operasional Usaha Lorjuk', 'BUSINESS', 1000000.00, 1525000.00),
(2, 1, 'Tabungan Rumah Tangga Pribadi Bu Maryam', 'PERSONAL', 500000.00, 500000.00),
(3, 2, 'Kas Toko Batik Pesisir', 'BUSINESS', 2000000.00, 2350000.00),
(4, 2, 'Rekening Pribadi Pak Hidayat', 'PERSONAL', 1000000.00, 1000000.00),
(5, 3, 'Kas Operasional Homestay', 'BUSINESS', 1500000.00, 1500000.00);

-- 17. FINANCIAL_CATEGORIES
INSERT INTO `financial_categories` (`id`, `name`, `type`, `is_system_default`) VALUES
(1, 'Penjualan Online Marketplace', 'INCOME', 1),
(2, 'Penjualan Langsung di Gerai Pantai', 'INCOME', 1),
(3, 'Pembelian Bahan Baku & Hasil Tangkap Nelayan', 'EXPENSE', 1),
(4, 'Biaya Kemasan & Plastik Pengemas', 'EXPENSE', 1),
(5, 'Biaya Operasional Listrik & Gas', 'EXPENSE', 1),
(6, 'Gaji / Upah Tenaga Kerja Produksi', 'EXPENSE', 1);

-- 18. FINANCIAL_TRANSACTIONS (Jurnal Kas Terisolasi)
INSERT INTO `financial_transactions` (`id`, `umkm_merchant_id`, `financial_account_id`, `category_id`, `order_id`, `transaction_type`, `amount`, `transaction_date`, `description`, `created_by`) VALUES
(1, 1, 1, 3, NULL, 'EXPENSE', 350000.00, '2026-11-01', 'Pembelian 10 kg lorjuk segar dari perahu nelayan Tanjung', 3),
(2, 1, 1, 4, NULL, 'EXPENSE', 50000.00, '2026-11-01', 'Pembelian standing pouch dan kardus oleh-oleh', 3),
(3, 1, 1, 2, NULL, 'INCOME', 750000.00, '2026-11-02', 'Penjualan offline pengunjung rombongan di gerai pantai', 3),
(4, 1, 1, 1, 1, 'INCOME', 175000.00, '2026-11-02', 'Penerimaan otomatis pesanan #INV-JMI-20261102-001', 3),
(5, 2, 3, 1, 2, 'INCOME', 350000.00, '2026-11-02', 'Penerimaan otomatis pesanan batik #INV-JMI-20261102-002', 4);

-- 19. RESERVATIONS
INSERT INTO `reservations` (`id`, `booking_code`, `user_id`, `destination_id`, `visit_date`, `visitor_count`, `package_name`, `total_price`, `status`, `check_in_time`) VALUES
(1, 'RSV-JMI-20261102-001', 8, 1, '2026-11-08', 4, 'Paket Wisata Keluarga & Tebing', 40000.00, 'CONFIRMED', NULL),
(2, 'RSV-JMI-20261102-002', 8, 1, '2026-11-02', 2, 'Tiket Masuk Reguler', 20000.00, 'ATTENDED', CURRENT_TIMESTAMP);

-- 20. PARTNERSHIPS
INSERT INTO `partnerships` (`id`, `partner_name`, `partnership_type`, `contact_person`, `phone_number`, `start_date`, `end_date`, `status`, `notes`) VALUES
(1, 'Dinas Kebudayaan dan Pariwisata Kab. Pamekasan', 'PEMERINTAH', 'Bpk. Ahmad Fauzi', '081122334455', '2026-01-01', '2028-12-31', 'ACTIVE', 'Program pendampingan desa wisata mandiri bahari'),
(2, 'Universitas Madura (KKN Tematik)', 'AKADEMISI', 'Dr. Subhan', '081299887766', '2026-07-01', '2026-08-31', 'ACTIVE', 'Pelatihan literasi keuangan digital bagi 40 UMKM Pantai Jumiang'),
(3, 'Asosiasi Pengusaha Batik Pamekasan', 'KOMUNITAS', 'Ibu Nurul', '081377665544', '2026-03-15', '2027-03-14', 'ACTIVE', 'Kurasi kualitas batik pesisir untuk etalase digital');

-- 21. AUDIT_LOGS
INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `old_values`, `new_values`, `ip_address`, `user_agent`) VALUES
(1, 1, 'SYSTEM_INIT', 'System', 1, NULL, '{"status": "Initialized Week 01 - 16"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'),
(2, 3, 'PRICE_UPDATE', 'Product', 1, '{"price": 30000}', '{"price": 35000}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'),
(3, 8, 'ORDER_CHECKOUT', 'Order', 1, NULL, '{"invoice_no": "INV-JMI-20261102-001", "total": 175000}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');

SET FOREIGN_KEY_CHECKS = 1;
