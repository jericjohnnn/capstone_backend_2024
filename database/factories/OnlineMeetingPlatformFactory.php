<?php

namespace Database\Factories;

use App\Models\OnlineMeetingPlatform;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OnlineMeetingPlatform>
 */
class OnlineMeetingPlatformFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = OnlineMeetingPlatform::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Zoom', 'Google Meet'])
        ];
    }
}
