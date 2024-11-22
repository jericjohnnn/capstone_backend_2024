<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminRole;

class AdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the roles to insert
        AdminRole::create(['role' => 'Admin']);
        AdminRole::create(['role' => 'Viewer']);
    }
}
