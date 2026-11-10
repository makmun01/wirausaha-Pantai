<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with [DATA DEMO / SIMULASI] Pantai Jumiang.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $roles = [
                ['id' => 1, 'name' => 'super_admin', 'display_name' => 'Super Admin', 'description' => 'Kontrol penuh teknis & RBAC sistem'],
                ['id' => 2, 'name' => 'pengelola_wisata', 'display_name' => 'Pengelola Wisata', 'description' => 'Manajemen destinasi, fasilitas, dan check-in tiket'],
                ['id' => 3, 'name' => 'pelaku_umkm', 'display_name' => 'Pelaku UMKM', 'description' => 'Manajemen produk, stok, pesanan, dan kas mandiri terpisah'],
                ['id' => 4, 'name' => 'bendahara', 'display_name' => 'Bendahara', 'description' => 'Pengawasan arus kas kawasan & rekonsiliasi keuangan'],
                ['id' => 5, 'name' => 'pimpinan', 'display_name' => 'Pimpinan / Eksekutif', 'description' => 'Akses analitik eksekutif EIS'],
                ['id' => 6, 'name' => 'wisatawan', 'display_name' => 'Wisatawan', 'description' => 'Navigasi peta GIS, belanja UMKM, dan reservasi tiket'],
            ];
            foreach ($roles as $role) {
                DB::table('roles')->updateOrInsert(['id' => $role['id']], $role);
            }

            $demoPasswordHash = Hash::make('Password123!');
            $users = [
                ['id' => 1, 'name' => 'Administrator Jumiang [DEMO]', 'email' => 'admin@jumiang.test', 'phone_number' => '081234567890', 'password' => $demoPasswordHash, 'is_active' => 1],
                ['id' => 2, 'name' => 'Pokdarwis Pantai Jumiang [DEMO]', 'email' => 'pengelola@jumiang.test', 'phone_number' => '081234567891', 'password' => $demoPasswordHash, 'is_active' => 1],
                ['id' => 3, 'name' => 'Ibu Maryam (Sentra Lorjuk) [DEMO]', 'email' => 'umkm.maryam@jumiang.test', 'phone_number' => '081234567892', 'password' => $demoPasswordHash, 'is_active' => 1],
                ['id' => 4, 'name' => 'Pak Hidayat (Batik Pesisir) [DEMO]', 'email' => 'umkm.hidayat@jumiang.test', 'phone_number' => '081234567893', 'password' => $demoPasswordHash, 'is_active' => 1],
                ['id' => 5, 'name' => 'Ibu Sugiarti (Homestay Jumiang) [DEMO]', 'email' => 'umkm.sugiarti@jumiang.test', 'phone_number' => '081234567894', 'password' => $demoPasswordHash, 'is_active' => 1],
                ['id' => 6, 'name' => 'Bendahara Kawasan Jumiang [DEMO]', 'email' => 'bendahara@jumiang.test', 'phone_number' => '081234567895', 'password' => $demoPasswordHash, 'is_active' => 1],
                ['id' => 7, 'name' => 'Kepala Desa Tanjung [DEMO]', 'email' => 'pimpinan@jumiang.test', 'phone_number' => '081234567896', 'password' => $demoPasswordHash, 'is_active' => 1],
                ['id' => 8, 'name' => 'Budi Wisatawan [DEMO]', 'email' => 'wisatawan@jumiang.test', 'phone_number' => '081234567897', 'password' => $demoPasswordHash, 'is_active' => 1],
            ];
            foreach ($users as $idx => $user) {
                DB::table('users')->updateOrInsert(['id' => $user['id']], $user);
                $roleId = match ($user['id']) {
                    1 => 1,
                    2 => 2,
                    3, 4, 5 => 3,
                    6 => 4,
                    7 => 5,
                    default => 6,
                };
                DB::table('role_user')->updateOrInsert(
                    ['user_id' => $user['id'], 'role_id' => $roleId],
                    ['user_id' => $user['id'], 'role_id' => $roleId]
                );
            }

            DB::table('destinations')->updateOrInsert(
                ['id' => 1],
                [
                    'name' => 'Pantai Jumiang Bahari [DATA DEMO]',
                    'slug' => 'pantai-jumiang-bahari',
                    'description' => 'Kawasan wisata bahari Pantai Jumiang, Desa Tanjung, Kecamatan Pademawu, Kabupaten Pamekasan, Madura.',
                    'village' => 'Tanjung',
                    'district' => 'Pademawu',
                    'regency' => 'Pamekasan',
                    'operating_hours' => '06:00 - 18:00 WIB',
                    'ticket_price_demo' => 10000.00,
                    'is_demo_data' => 1,
                ]
            );
        });
    }
}
