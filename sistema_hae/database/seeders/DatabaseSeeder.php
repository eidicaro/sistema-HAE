<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Semestres;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ======== USUARIOS DE TESTE =========
        User::create([
            'name' => 'prof',
            'email' => 'prof',
            'password' => '123', // Laravel já faz hash automático
            'role' => 'professor',
        ]);
        User::create([
            'name' => 'coord',
            'email' => 'coord',
            'password' => '123',
            'curso' => 'AMS - Análise e Desenvolvimento de Sistemas',
            'role' => 'coordenador',
        ]);
        User::create([
            'name' => 'dir',
            'email' => 'dir',
            'password' => '123', 
            'role' => 'direcao',
        ]);

        // ================== USUARIOS =================
        // COORDENADORES
        //ads
        User::create([
            'name' => 'coord ads',
            'email' => 'f132coord.adsa@cps.sp.gov.br',
            'password' => 'adscoord123',
            'curso' => 'AMS - Análise e Desenvolvimento de Sistemas',
            'role' => 'coordenador',
        ]);

        //automação
        User::create([
            'name' => 'coord Automação Industrial',
            'email' => 'f132coord.aui@cps.sp.gov.br',
            'password' => 'auicoord123',
            'curso' => 'Automação Industrial',
            'role' => 'coordenador',
        ]);

        //Gestão Empresarial
        User::create([
            'name' => 'coord ge',
            'email' => 'f132coord.gem@cps.sp.gov.br',
            'password' => 'gemcoord123',
            'curso' => 'Gestão Empresarial',
            'role' => 'coordenador',
        ]);

        //Gestão da Tecnologia da Informação
        User::create([
            'name' => 'coord gti',
            'email' => 'f132coord.gti@cps.sp.gov.br',
            'password' => 'gticoord123',
            'curso' => 'Gestão da Tecnologia da Informação',
            'role' => 'coordenador',
        ]);

        //Manutenção Industrial
        User::create([
            'name' => 'coord mni',
            'email' => 'f132coord.mni@cps.sp.gov.br',
            'password' => 'mnicoord123',
            'curso' => 'Manutenção Industrial',
            'role' => 'coordenador',
        ]);

        //Produção Fonográfica
        User::create([
            'name' => 'coord pfo',
            'email' => 'f132coord.pfo@cps.sp.gov.br',
            'password' => 'pfocoord123',
            'curso' => 'Produção Fonográfica',
            'role' => 'coordenador',
        ]);

        //processos gerenciais
        User::create([
            'name' => 'coord pgea',
            'email' => 'f132coord.pgea@cps.sp.gov.br',
            'password' => 'pgeacoord123',
            'curso' => 'AMS - Processos Gerenciais',
            'role' => 'coordenador',
        ]);
    }
}