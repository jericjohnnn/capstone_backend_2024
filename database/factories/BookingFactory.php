<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Tutor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = Booking::class;

     public function definition()
     {
         // Randomly pick a learning mode
         $learningMode = $this->faker->randomElement(['Face to Face', 'In School', 'Online']);

         // Define location and online meeting platform based on the learning mode
         $location = $learningMode === 'Face to Face'
             ? $this->faker->address()
             : ($learningMode === 'In School' ? 'CCTC' : null);

         $onlineMeetingPlatform = $learningMode === 'Online'
             ? $this->faker->randomElement(['Zoom', 'Google Meet', 'Skype'])
             : null;

         return [
             'tutor_id' => Tutor::inRandomOrder()->first()->id,
             'student_id' => Student::inRandomOrder()->first()->id,
             'learning_mode' => $learningMode,
             'subject' => Subject::inRandomOrder()->first()->name,
             'location' => $location,
             'online_meeting_platform' => $onlineMeetingPlatform,
             'contact_number' => $this->faker->phoneNumber(),
             'status' => $this->faker->randomElement(['Pending', 'Ongoing', 'Completed']),
         ];
     }
}
