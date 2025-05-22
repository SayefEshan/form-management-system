<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@miaki.co'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        // Assign admin role
        $admin->assignRole('admin');

        // Create regular users
        User::firstOrCreate(
            ['email' => 'user@miaki.co'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('user123'),
                'email_verified_at' => now(),
            ]
        );

        // Create additional users using factory
        User::factory(3)->create();
    }
}
