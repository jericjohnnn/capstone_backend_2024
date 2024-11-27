<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Subject;
use App\Models\Tutor;
use App\Models\TutorCertificate;
use App\Models\TutorSchool;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(
            [
                AdminRoleSeeder::class,
                UserTypeSeeder::class,
                SubjectSeeder::class,
                OnlineMeetingPlatformSeeder::class,
                AdminAccountSeeder::class,
            ]
        );

        //seed student account
        Student::create([
            'user_id' => User::create([
                'user_type_id' => 1,
                'email' => 'student@gmail.com',
                'email_verified_at' => now(), // Mark as verified
                'password' => Hash::make('password123'), 
                'remember_token' => null,
            ])->id,
            'first_name' => 'Student',
            'last_name' => 'Account',
            'address' => 'Buanoy, Balamban, Cebu',
            'birthdate' => '2009-01-01',
            'contact_number' => '09123456789',
            'school_id_number' => '20212346',
            'grade_year' => '10',
            'offense_status' => 'Unflagged',
        ]);

        //seed tutor account
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
    }
}
