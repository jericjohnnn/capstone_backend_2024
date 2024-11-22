<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        // Create 25 notifications for randomly chosen users
        Notification::factory()->count(25)->create();

        // Optional: Create 15 notifications specifically for students
        Notification::factory()->count(15)->create([
            'user_id' => User::factory()->student()->create()->id,
        ]);

        // Optional: Create 10 notifications specifically for tutors
        Notification::factory()->count(10)->create([
            'user_id' => User::factory()->tutor()->create()->id,
        ]);
    }
}
