<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin',
            'password' => '123', // Laravel já faz hash automático
            'type' => 'admin',
        ]);
        User::create([
            'name' => 'prof',
            'email' => 'prof',
            'password' => '123', // Laravel já faz hash automático
            'type' => 'professor',
        ]);
    }
}