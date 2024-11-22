<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'role_id' => 1,  // Admin = 1, Viewer = 2
            'name' => 'Admin',
            'email' => 'admin@tudlo.com',
            'email_verified_at' => now(),
            'password' => Hash::make('capstone123'),
        ]);
    }
}
