<?php

namespace Database\Seeders;

use App\Models\Rating;
use App\Models\Student;
use App\Models\Tutor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::all();
        $tutors = Tutor::all();

        foreach ($tutors as $tutor) {
            // 60% chance for the tutor to receive ratings
            if (rand(0, 100) < 60) {
                // Random number of ratings between 1 and 5 for this tutor
                $numRatings = rand(1, 5);

                // Select unique students for this tutor's ratings
                $studentSubset = $students->random(min($numRatings, $students->count()));

                foreach ($studentSubset as $student) {
                    // Create the rating for this tutor by the selected student
                    Rating::factory()->create([
                        'tutor_id' => $tutor->id,
                        'student_id' => $student->id,
                    ]);
                }
            }
        }
    }
}
