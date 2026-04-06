<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'prof',
            'email' => 'prof',
            'password' => '123', // Laravel já faz hash automático
            'role' => 'professor',
        ]);
        User::create([
            'name' => 'prof2',
            'email' => 'prof2',
            'password' => '123', // Laravel já faz hash automático
            'role' => 'professor',
        ]);
        User::create([
            'name' => 'coord',
            'email' => 'coord',
            'password' => '123', // Laravel já faz hash automático
            'role' => 'coordenador',
        ]);
        User::create([
            'name' => 'dir',
            'email' => 'dir',
            'password' => '123', // Laravel já faz hash automático
            'role' => 'direcao',
        ]);
    }
}