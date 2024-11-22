<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create([
                'user_type_id' => UserType::where('type', 'Student')->first()->id
            ])->id,
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'address' => $this->faker->address(),
            'birthdate' => $this->faker->dateTimeBetween('-30 years', '-18 years'),
            'contact_number' => $this->faker->numerify('09#########'),
            'profile_image' => 'https://picsum.photos/200/200?random=' . $this->faker->randomNumber(),
            'school_id_number' => $this->faker->numerify('20##-#####'),
            'grade_year' => $this->faker->optional()->numberBetween(1, 10),
            'offense_status' => $this->faker->randomElement(['Unflagged', 'Warned', 'Penalized', 'Banned'])
        ];
    }
}
