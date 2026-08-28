<?php

namespace Database\Seeders;

use App\Models\Haes;
use App\Models\TipoHae;
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

        $tiposHae = [
            [
                'nome' => 'Ensino (teste manual)',
                'descricao' => 'Tipo provisório para testar submissões ligadas ao ensino.',
                'limite' => 100,
                'ativo' => true,
                'subtipos' => ['Monitoria', 'Nivelamento'],
            ],
            [
                'nome' => 'Pesquisa (teste manual)',
                'descricao' => 'Tipo provisório para testar submissões ligadas à pesquisa.',
                'limite' => 100,
                'ativo' => true,
                'subtipos' => ['Iniciação científica', 'Grupo de pesquisa'],
            ],
            [
                'nome' => 'Extensão (teste manual)',
                'descricao' => 'Tipo provisório para testar submissões ligadas à extensão.',
                'limite' => 100,
                'ativo' => true,
                'subtipos' => ['Projeto comunitário', 'Evento institucional'],
            ],
        ];

        foreach ($tiposHae as $tipoHae) {
            $subtipos = $tipoHae['subtipos'];
            unset($tipoHae['subtipos']);

            $tipo = TipoHae::updateOrCreate(
                ['nome' => $tipoHae['nome']],
                $tipoHae
            );

            foreach ($subtipos as $subtipo) {
                $tipo->subtipos()->updateOrCreate(
                    ['nome' => $subtipo],
                    ['ativo' => true]
                );
            }
        }

        $this->command?->info(
            'Criados/atualizados 2 professores, 2 coordenadores, 3 tipos e 6 subtipos de HAE para testes manuais.'
        );
    }
}
