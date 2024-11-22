<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'user_type_id' => $this->faker->randomElement([1, 2]),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // You might want to change this default password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the user is a student.
     */
    public function student(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'user_type_id' => UserType::where('type', 'Student')->first()->id
            ];
        });
    }

    /**
     * Indicate that the user is a tutor.
     */
    public function tutor(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'user_type_id' => UserType::where('type', 'Tutor')->first()->id
            ];
        });
    }

    /**
     * Indicate that the email is unverified.
     */
    public function unverified(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
