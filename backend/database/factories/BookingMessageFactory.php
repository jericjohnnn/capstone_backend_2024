<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\BookingMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BookingMessage>
 */
class BookingMessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = BookingMessage::class;

    public function definition()
    {
        return [
            'booking_id' => Booking::factory(),  // Link to a new or existing booking
            'title' => $this->faker->sentence(),  // Example message title
            'message' => $this->faker->paragraph(),  // Example message content
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
