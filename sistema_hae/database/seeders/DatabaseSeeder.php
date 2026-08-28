<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('SEED_DIRECAO_EMAIL');
        $password = env('SEED_DIRECAO_PASSWORD');

        if (! $email || ! $password) {
            $this->command?->warn(
                'Usuário de direção não criado. Defina SEED_DIRECAO_EMAIL e SEED_DIRECAO_PASSWORD.'
            );

            return;
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => env('SEED_DIRECAO_NAME', 'Direção'),
                'password' => $password,
                'role' => User::ROLE_DIRECAO,
            ]
        );
    }
}
