<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
        [
            'email' => 'admin@buckhill.co.uk'
        ],
        [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'password' => Hash::make('admin'),
            'address' => fake()->address(),
            'phone_number' => fake()->phoneNumber(),
            'is_admin' => true
        ]);
    }
}
