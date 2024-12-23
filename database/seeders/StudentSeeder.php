<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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

        // this is an email verified seeder account. for unverified, you have to remove email_verified_at in factory
        Student::factory(50)->create();
    }
}
