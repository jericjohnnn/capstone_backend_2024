<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(
            [
                AdminRoleSeeder::class,
                UserTypeSeeder::class,
                SubjectSeeder::class,
                OnlineMeetingPlatformSeeder::class,
                AdminAccountSeeder::class,
                StudentSeeder::class,
                TutorSeeder::class,
                RatingSeeder::class,
                ReportSeeder::class,
                NotificationSeeder::class,
                BookingSeeder::class,
            ]
        );
    }
}
