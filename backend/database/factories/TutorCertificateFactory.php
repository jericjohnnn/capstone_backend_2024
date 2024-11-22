<?php

namespace Database\Factories;

use App\Models\TutorCertificate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TutorCertificate>
 */
class TutorCertificateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = TutorCertificate::class;

    public function definition(): array
    {
        return [
            // 'image' => 'https://picsum.photos/200/200?random=' . $this->faker->randomNumber(),
            'title' => $this->faker->randomElement([
                'TESDA National Certificate II',
                'Digital Marketing Certificate',
                'Web Development Certification',
                'Teaching English Certification',
                'Math Proficiency Certificate'
            ]),
            'issuer' => $this->faker->company(),
            'date_issued' => $this->faker->dateTimeBetween('-2 years', 'now')
        ];
    }
}
