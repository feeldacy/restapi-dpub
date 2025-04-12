<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::create([
            'name' => 'testingSA',
            'email' => 'testingSuperA@gmail.com',
            'password' => Hash::make('jajaSA')
        ]);

        $superAdmin -> assignRole('superAdmin');

        $admin = User::create([
            'name' => 'testingAdmin',
            'email' => 'testingAdmin@gmail.com',
            'password' => Hash::make('jajaAdmin')
        ]);

        $admin -> assignRole('admin');
    }
}
