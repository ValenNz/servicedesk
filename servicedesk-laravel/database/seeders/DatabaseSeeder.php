<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category; 
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; 

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;


    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@servicedesk.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Employee One',
            'email' => 'employee@servicedesk.com',
            'password' => Hash::make('password'),
            'role' => 'employee'
        ]);

        User::create([
            'name' => 'Regular User',
            'email' => 'user@servicedesk.com',
            'password' => Hash::make('password'),
            'role' => 'user'
        ]);

        Category::create([
            'name' => 'Network',
            'description' => 'Network related issues',
            'ticket_count' => 0
        ]);

        Category::create([
            'name' => 'Software',
            'description' => 'Software installation & issues',
            'ticket_count' => 0
        ]);

        Category::create([
            'name' => 'Hardware',
            'description' => 'Hardware problems',
            'ticket_count' => 0
        ]);

        Category::create([
            'name' => 'Access & Account',
            'description' => 'Account access issues',
            'ticket_count' => 0
        ]);
    }
}