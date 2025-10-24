<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin User
        $johnDoe = User::create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@carriergo.com',
            'phone' => '+1-555-0101',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'otp' => 123456,
            'otp_expiry' => now()->addHours(1)->toDateTimeString(),
            'image' => '',
            'start_date' => now()->subMonths(6)->toDateString(),
            'end_date' => null,
        ]);
        $johnDoe->assignRole('Super Admin');

        // Admin User
        $janeSmith = User::create([
            'firstname' => 'Jane',
            'lastname' => 'Smith',
            'email' => 'jane.smith@carriergo.com',
            'phone' => '+1-555-0102',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'otp' => 654321,
            'otp_expiry' => now()->addHours(1)->toDateTimeString(),
            'image' => '',
            'start_date' => now()->subMonths(3)->toDateString(),
            'end_date' => null,
        ]);
        $janeSmith->assignRole('Admin');

        // Employee User
        $mikeJohnson = User::create([
            'firstname' => 'Mike',
            'lastname' => 'Johnson',
            'email' => 'mike.johnson@carriergo.com',
            'phone' => '+1-555-0103',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'otp' => 789012,
            'otp_expiry' => now()->addHours(1)->toDateTimeString(),
            'image' => '',
            'start_date' => now()->subMonths(1)->toDateString(),
            'end_date' => null,
        ]);
        $mikeJohnson->assignRole('Employee');
    }
}
