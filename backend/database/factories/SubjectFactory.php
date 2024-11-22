<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Subject::class;

    public function definition(): array
    {
        $subjectsWithAbbreviations = [
            'Mathematics' => 'Math',
            'Science' => 'Sci',
            'History' => 'Hist',
            'Literature' => 'Litr',
            'Biology' => 'Bio',
            'Chemistry' => 'Chem',
            'Physics' => 'Phys',
            'Geography' => 'Geo',
            'Computer Science' => 'CS',
            'Art' => 'Art',
            'Music' => 'Music',
            'Physical Education' => 'PE',
            'Economics' => 'Econ',
            'Philosophy' => 'Phil',
            'Psychology' => 'Psych'
        ];

        $subject = $this->faker->unique()->randomElement(array_keys($subjectsWithAbbreviations));

        return [
            'name' => $subject,
            'abbreviation' => $subjectsWithAbbreviations[$subject],
        ];
    }
}
