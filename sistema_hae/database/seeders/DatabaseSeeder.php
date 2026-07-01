<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Semestres;
use App\Models\TipoHae;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
                // ====== DIRECAO ======
                User::create([
                    'name' => 'dir',
                    'email' => 'dir',
                    'password' => '123', 
                    'role' => 'direcao',
                ]);

                User::create([
                    'name' => 'prof',
                    'email' => 'prof',
                    'password' => '123', 
                    'role' => 'professor',
                ]);

                TipoHae::create([
                    'nome' => 'teste',
                    'descricao' => null,
                    
                ]);
    }
}