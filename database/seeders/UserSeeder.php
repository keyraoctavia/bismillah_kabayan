<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email'=> 'admin@kasir.com'],
            ['name'=>'Administrator',
            'password'=>Hash::make('admin123'),
            'role'=>'admin',]
        );

         User::updateOrCreate(
            ['email' => 'operator@kasir.com'],
            [
                'name' => 'Operator',
                'password' => Hash::make('operator123'),
                'role' => 'operator',
            ]
        );

        User::updateOrCreate(
            ['email' => 'key@kasir.com'],
            [
                'name' => 'Key',
                'password' => Hash::make('keilopice7'),
                'role' => 'admin',
            ]
        );
    }
}