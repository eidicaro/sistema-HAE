<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Semestres;

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
            'curso' => 'AMS - Análise e Desenvolvimento de Sistemas',
            'role' => 'coordenador',
        ]);
        User::create([
            'name' => 'coord2',
            'email' => 'coord2',
            'password' => '123', // Laravel já faz hash automático
            'curso' => 'Automação Industrial',
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