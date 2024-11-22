<?php

namespace Database\Factories;

use App\Models\BookingDate;
use App\Models\BookingMessage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class BookingDateFactory extends Factory
{
    protected $model = BookingDate::class;

    private $bookedTimes = []; // Store booked times to avoid overlap

    public function definition()
    {
        // Set the start date to now, ensuring it's in the future
        $startDate = now()->addDays(rand(0, 60)); // Random date within the next 60 days

        // Generate unique start times
        $startTime = $this->generateUniqueStartTime($startDate);

        // Determine end time based on start time
        $endTime = $this->determineEndTime($startTime);

        return [
            'booking_message_id' => BookingMessage::factory(),
            'start_time' => $startTime->format('Y-m-d H:i:s'),
            'end_time' => $endTime->format('Y-m-d H:i:s'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function generateUniqueStartTime($date)
    {
        $maxAttempts = 10; // Limit attempts to find a unique time
        $attempts = 0;

        do {
            // Set the base time, rounding to the nearest :00 or :30
            $minutes = rand(0, 1) ? 0 : 30; // Randomly choose :00 or :30
            $hours = rand(0, 23); // Random hour of the day

            // Generate start time
            $startTime = Carbon::create($date->year, $date->month, $date->day, $hours, $minutes, 0);

            // Check for overlapping time slots in booked times
            if (!$this->isTimeOverlapping($startTime)) {
                $this->bookedTimes[] = $startTime; // Add the time to the booked list
                return $startTime;
            }

            $attempts++;
        } while ($attempts < $maxAttempts);

        // Fallback to a valid time if no unique time was found
        return Carbon::create($date->year, $date->month, $date->day, 11, 0, 0); // Default to 11:00 AM
    }

    private function isTimeOverlapping($startTime)
    {
        // Check for overlap with existing booked times
        foreach ($this->bookedTimes as $bookedTime) {
            // If the start time is during the blocked time
            if ($startTime->isBetween($bookedTime->copy()->setTime(12, 0), $bookedTime->copy()->setTime(13, 0))) {
                return true; // Overlap found
            }
        }

        return false; // No overlap
    }

    private function determineEndTime($startTime)
    {
        if ($startTime->hour < 12) {
            // If start time is before 12:00 PM, set end time to 12:00 PM
            return Carbon::create($startTime->year, $startTime->month, $startTime->day, 12, 0, 0);
        } else {
            // If start time is 1:00 PM or later, set end time to 2 hours later
            return (clone $startTime)->modify('+2 hours');
        }
    }
}
