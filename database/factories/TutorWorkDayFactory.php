<?php

namespace Database\Factories;

use App\Models\TutorWorkDay;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TutorWorkDay>
 */
class TutorWorkDayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $shiftType = $this->faker->randomElement(['morning', 'afternoon', 'full-day']);

        switch ($shiftType) {
            case 'morning':
                $startTime = $this->faker->numberBetween(6, 11);
                $endTime = $this->faker->numberBetween($startTime + 1, 12);
                break;

            case 'afternoon':
                $startTime = $this->faker->numberBetween(13, 16);
                $endTime = $this->faker->numberBetween($startTime + 1, 20);
                break;

            case 'full-day':
                $startTime = $this->faker->numberBetween(6, 11);
                $endTime = $this->faker->numberBetween($startTime + 3, 20);
                break;
        }

        return [
            'monday' => $this->faker->boolean(80),      // 80% chance of 'True'
            'tuesday' => $this->faker->boolean(80),     // 80% chance of 'True'
            'wednesday' => $this->faker->boolean(80),   // 80% chance of 'True'
            'thursday' => $this->faker->boolean(80),    // 80% chance of 'True'
            'friday' => $this->faker->boolean(80),      // 80% chance of 'True'
            'saturday' => $this->faker->boolean(40),    // 40% chance of 'True'
            'sunday' => $this->faker->boolean(20),      // 20% chance of 'True'
            'start_time' => sprintf('%02d:00:00', $startTime),
            'end_time' => sprintf('%02d:00:00', $endTime),
        ];
    }
}
