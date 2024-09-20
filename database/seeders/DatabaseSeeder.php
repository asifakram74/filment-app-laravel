<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Asif',
            'email' => 'asifakram74@gmail.com',
            'password' => 'ASif@123',
            'phone' => '03214667251',
            'agency' => 'Cross Media Sol',
            'agency_address' => 'Lahore', 
            'city' => 'Lahore', 
            'role' => 'Admin',
            'status' => 'Active',
        ]);
    }
}
