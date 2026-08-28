<?php

namespace Database\Seeders;

use App\Models\Haes;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class UsuariosTesteSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            throw new RuntimeException(
                'O UsuariosTesteSeeder só pode ser executado em ambiente local ou de testes.'
            );
        }

        $password = (string) env('SEED_TEST_PASSWORD', 'teste123');

        if (mb_strlen($password) < 6) {
            throw new RuntimeException('SEED_TEST_PASSWORD deve ter ao menos 6 caracteres.');
        }

        $usuarios = [
            [
                'name' => 'Professor Teste 1',
                'email' => 'professor.teste1@fatec.sp.gov.br',
                'role' => User::ROLE_PROFESSOR,
                'curso' => null,
            ],
            [
                'name' => 'Professor Teste 2',
                'email' => 'professor.teste2@fatec.sp.gov.br',
                'role' => User::ROLE_PROFESSOR,
                'curso' => null,
            ],
            [
                'name' => 'Coordenador Teste 1',
                'email' => 'coordenador.teste1@fatec.sp.gov.br',
                'role' => User::ROLE_COORDENADOR,
                'curso' => Haes::CURSOS[0],
            ],
            [
                'name' => 'Coordenador Teste 2',
                'email' => 'coordenador.teste2@fatec.sp.gov.br',
                'role' => User::ROLE_COORDENADOR,
                'curso' => Haes::CURSOS[1],
            ],
        ];

        foreach ($usuarios as $usuario) {
            User::updateOrCreate(
                ['email' => $usuario['email']],
                [...$usuario, 'password' => $password]
            );
        }

        $this->command?->info(
            'Criados/atualizados 2 professores e 2 coordenadores para testes manuais.'
        );
    }
}
