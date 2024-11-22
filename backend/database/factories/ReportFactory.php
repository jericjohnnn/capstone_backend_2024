<?php

namespace Database\Factories;

use App\Models\Report;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Report::class;

    public function definition(): array
    {
        // Randomly select a complainant and an offender
        $complainant = User::inRandomOrder()->first();

        // Fetch either a student or a tutor as the offender
        $offender = User::inRandomOrder()
            ->where('id', '!=', $complainant->id)
            ->first();

        // Get the offense_status based on whether the offender is a tutor or a student
        $offenseStatus = null;

        if ($offender->user_type_id == UserType::where('type', 'Tutor')->first()->id) {
            $offenderDetails = Tutor::where('user_id', $offender->id)->first();
        } else {
            $offenderDetails = Student::where('user_id', $offender->id)->first();
        }

        $offenseStatus = $offenderDetails ? $offenderDetails->offense_status : null;

        // Determine the report status based on the offense_status
        $status = $offenseStatus === 'Banned' ? 'Resolved' : $this->faker->randomElement(['Pending', 'Resolved']);

        return [
            'complainant_id' => $complainant->id,
            'offender_id' => $offender->id,
            'title' => $this->faker->sentence(),
            'message' => $this->faker->paragraph(),
            'status' => $status,
        ];
    }
}
