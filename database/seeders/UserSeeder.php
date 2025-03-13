<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $exim = User::create([
            'name' => "Exim",
            'email' => 'exim@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'exim'
        ]);

        $ppic = User::create([
            'name' => "PPIC",
            'email' => 'ppic@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'ppic'
        ]);

        $gudang = User::create([
            'name' => "Gudang",
            'email' => 'gudang@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'gudang'
        ]);

        $purchasing = User::create([
            'name' => "Purchasing",
            'email' => 'purchasing@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'purchasing'
        ]);

        $manager = User::create([
            'name' => "Manager",
            'email' => 'manager@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'Manager'
        ]);




    }
}
