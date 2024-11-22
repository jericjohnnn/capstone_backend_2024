<?php

namespace Database\Seeders;

use App\Models\OnlineMeetingPlatform;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OnlineMeetingPlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OnlineMeetingPlatform::create(['name' => 'Zoom']);
        OnlineMeetingPlatform::create(['name' => 'Google Meet']);
    }
}
