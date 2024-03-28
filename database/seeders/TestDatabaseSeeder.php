<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use App\Models\User;

class TestDatabaseSeeder extends Seeder
{
    public function run()
    {

        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        Category::create([
            'name' => 'test category'
        ]);

    }
}

