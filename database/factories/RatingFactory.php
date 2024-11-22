<?php

namespace Database\Factories;

use App\Models\Rating;
use App\Models\Student;
use App\Models\Tutor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rating>
 */
class RatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Rating::class;

    public function definition(): array
    {
        return [
            'tutor_id' => Tutor::inRandomOrder()->first()->id,
            'student_id' => Student::inRandomOrder()->first()->id,
            'comment' => $this->faker->optional()->paragraph(),
            'rate' => $this->faker->numberBetween(1, 5),
        ];
    }
}
