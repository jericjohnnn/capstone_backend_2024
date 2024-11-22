<?php

namespace Database\Factories;

use App\Models\Tutor;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tutor>
 */
class TutorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Tutor::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create([
                'user_type_id' => UserType::where('type', 'Tutor')->first()->id
            ])->id,
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'address' => $this->faker->address(),
            'birthdate' => $this->faker->dateTimeBetween('-30 years', '-20 years'),
            'gender' => $this->faker->randomElement(['Male', 'Female', 'Other']),
            'contact_number' => $this->faker->numerify('09#########'),
            'profile_image' => 'https://picsum.photos/200/200?random=' . $this->faker->randomNumber(),
            'tutor_rate' => $this->faker->numberBetween(300, 1000),
            'biography' => $this->faker->paragraph(),
            'school_id_number' => $this->faker->numerify('20##-#####'),
            'course' => $this->faker->randomElement(['BSED', 'BEED', 'BSIT', 'BSHM', 'BSENTREP']),
            'year' => $this->faker->randomElement([1, 2, 3, 4]),
            'contacted_status' => $this->faker->boolean(),
            'offense_status' => $this->faker->randomElement(['Unflagged', 'Warned', 'Penalized', 'Banned']),
            'approval_status' => $this->faker->randomElement(['Pending', 'Accepted', 'Rejected']),
        ];
    }
}
