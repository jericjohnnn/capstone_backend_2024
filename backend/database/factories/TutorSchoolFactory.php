<?php

namespace Database\Factories;

use App\Models\TutorSchool;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TutorSchool>
 */
class TutorSchoolFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = TutorSchool::class;

    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-6 years', '-2 years');
        return [
            // 'image' => 'https://picsum.photos/200/200?random=' . $this->faker->randomNumber(),
            'name' => $this->faker->randomElement([
                'University of the Philippines',
                'De La Salle University',
                'Ateneo de Manila University',
                'University of Santo Tomas',
                'Far Eastern University'
            ]),
            'course' => $this->faker->randomElement([
                'Bachelor of Science in Education',
                'Bachelor of Elementary Education',
                'Bachelor of Science in Information Technology',
                'Bachelor of Science in Computer Science',
                'Bachelor of Arts in Education'
            ]),
            'start_date' => $startDate,
            'end_date' => $this->faker->optional(0.7)->dateTimeBetween($startDate, 'now')
        ];
    }
}
