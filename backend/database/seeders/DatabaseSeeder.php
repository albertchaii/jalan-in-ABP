<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Report;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ===== USERS =====
        // Admin (hanya bisa dibuat via seeder, sesuai proposal)
        $admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@jalan.in',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Sample Operators
        $operator1 = User::create([
            'name' => 'Ahmad Suhendra',
            'email' => 'ahmad.s@jalan.in',
            'password' => Hash::make('password'),
            'role' => 'operator',
            'email_verified_at' => now(),
        ]);

        $operator2 = User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti.a@jalan.in',
            'password' => Hash::make('password'),
            'role' => 'operator',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Budi Cahyo',
            'email' => 'budi.c@jalan.in',
            'password' => Hash::make('password'),
            'role' => 'operator',
            'email_verified_at' => now(),
        ]);

        // Sample regular users
        $user1 = User::create([
            'name' => 'Marcus Aris',
            'email' => 'marcus.aris@jalan.in',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        $user2 = User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti.n@jalan.in',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        $user3 = User::create([
            'name' => 'Dina Permata',
            'email' => 'dina.p@jalan.in',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // ===== REPORTS (sample data for testing filters) =====
        // Membuat 50 laporan dengan tanggal acak antara awal 2025 hingga saat ini
        $statuses = ['Dilaporkan', 'Disurvey', 'Tidak Valid', 'Diproses', 'Selesai'];
        $damageTypes = ['Pothole', 'Crack', 'Uneven', null];
        $users = [$user1->id, $user2->id, $user3->id];
        $operators = [$operator1->id, $operator2->id];

        $startDate = \Carbon\Carbon::create(2025, 1, 1);
        $endDate = \Carbon\Carbon::now();

        for ($i = 0; $i < 50; $i++) {
            $randomDate = \Carbon\Carbon::createFromTimestamp(rand($startDate->timestamp, $endDate->timestamp));
            $status = $statuses[array_rand($statuses)];
            $damageType = $damageTypes[array_rand($damageTypes)];
            
            // Random coordinates around Jakarta
            $lat = -6.2000 + (rand(-100, 100) / 1000);
            $lng = 106.8000 + (rand(-100, 100) / 1000);

            $reportData = [
                'user_id' => $users[array_rand($users)],
                'description' => 'Contoh deskripsi laporan kerusakan jalan otomatis ke-' . ($i + 1),
                'photo_path' => '',
                'latitude' => $lat,
                'longitude' => $lng,
                'damage_type' => $damageType,
                'status' => $status,
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ];

            if (in_array($status, ['Disurvey', 'Tidak Valid', 'Diproses', 'Selesai'])) {
                $reportData['operator_id'] = $operators[array_rand($operators)];
                $reportData['verified_at'] = $randomDate->copy()->addHours(rand(1, 48));
            }

            if ($status === 'Selesai') {
                $reportData['completed_at'] = $randomDate->copy()->addDays(rand(2, 14));
            }

            if ($status === 'Tidak Valid') {
                $reportData['rejection_note'] = 'Laporan ditolak secara otomatis (sample data).';
            }

            Report::create($reportData);
        }
    }
}
