<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\BookingDate;
use App\Models\BookingMessage;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 5 pending bookings
        Booking::factory()
            ->count(20)
            ->state(['status' => 'Pending'])
            ->has(
                BookingMessage::factory()
                    ->count(1)
                    ->has(BookingDate::factory()->count(2), 'dates'),  // Each message has 2 dates
                'messages'
            )
            ->create();

        // Create 3 completed bookings
        Booking::factory()
            ->count(10)
            ->state(['status' => 'Completed'])
            ->has(
                BookingMessage::factory()
                    ->count(1)
                    ->has(BookingDate::factory()->count(2), 'dates'),  // Each message has 2 dates
                'messages'
            )
            ->create();

        // Create 4 ongoing bookings
        Booking::factory()
            ->count(15)
            ->state(['status' => 'Ongoing'])
            ->has(
                BookingMessage::factory()
                    ->count(1)
                    ->has(BookingDate::factory()->count(2), 'dates'),  // Each message has 2 dates
                'messages'
            )
            ->create();
    }
}
