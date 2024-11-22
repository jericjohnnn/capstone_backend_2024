<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\Tutor;
use App\Models\TutorCertificate;
use App\Models\TutorSchool;
use App\Models\TutorWorkDay;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TutorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tutorAccount = Tutor::create([
            'user_id' => User::create([
                'user_type_id' => 2,
                'email' => 'tutor@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'), // Predefined password
                'remember_token' => null,
            ])->id,
            'first_name' => 'Tutor',
            'last_name' => 'Account',
            'address' => 'Abucayan, Balamban, Cebu',
            'birthdate' => '2001-01-01',
            'gender' => 'Male',
            'contact_number' => '09123456789',
            'tutor_rate' => 300,
            'biography' => "I'm a dedicated software engineer with a passion for teaching. I've been tutoring students of all ages in programming and computer science for 3 years. I believe in creating a supportive and engaging learning environment where students can thrive. Whether you're a beginner or looking to advance your skills, I'm here to help you achieve your goals. Let's connect and explore the exciting world of software development together!",
            'school_id_number' => '20212345',
            'course' => 'BSIT',
            'year' => "4",
            'contacted_status' => 1,
            'offense_status' => 'Unflagged',
            'approval_status' => 'Accepted'
        ]);

        TutorCertificate::create([
            'tutor_id' => $tutorAccount->id,
            'title' => 'TESDA National Certificate II',
            'issuer' => 'Technical Education and Skills Development Authority',
            'date_issued' => '2023-01-15'
        ]);

        TutorCertificate::create([
            'tutor_id' => $tutorAccount->id,
            'title' => 'Web Development Certification',
            'issuer' => 'freeCodeCamp',
            'date_issued' => '2023-06-20'
        ]);

        TutorSchool::create([
            'tutor_id' => $tutorAccount->id,
            'name' => 'University of San Carlos',
            'course' => 'Bachelor of Science in Information Technology',
            'start_date' => '2020-06-01',
            'end_date' => '2024-05-30'
        ]);

        TutorSchool::create([
            'tutor_id' => $tutorAccount->id,
            'name' => 'Cebu Institute of Technology - University',
            'course' => 'Bachelor of Science in Computer Science',
            'start_date' => '2016-06-01',
            'end_date' => '2020-05-30'
        ]);

        $tutorAccount->workDays()->create([]);

        $subjectIds = Subject::inRandomOrder()->take(rand(1, 3))->pluck('id');
        $tutorAccount->subjects()->attach($subjectIds);


        // this is an email verified seeder account. for unverified, you have to remove email_verified_at in factory
        // Tutor::factory(50)->create()->each(function ($tutor) {
        //     // Create 2 certificates for each tutor
        //     TutorCertificate::factory(2)->create([
        //         'tutor_id' => $tutor->id
        //     ]);

        //     // Create 2 schools for each tutor
        //     TutorSchool::factory(2)->create([
        //         'tutor_id' => $tutor->id
        //     ]);

        //     // Create work days for each tutor
        //     TutorWorkDay::factory()->create([
        //         'tutor_id' => $tutor->id
        //     ]);

        //     // Assign between 1 to 3 random subjects to each tutor
        //     $subjectIds = Subject::inRandomOrder()->take(rand(1, 3))->pluck('id');
        //     $tutor->subjects()->attach($subjectIds);
        // });
    }
}
