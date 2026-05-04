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

        //===============================
        //PROFESSORES
        User::create([
            'name' => 'Admárcio R. Machado',
            'email' => 'admarcio.machado@cps.sp.gov.br',
            'password' => '27652477816',   
            'role' => 'professor']);
        User::create([
            'name' => 'Altimar Vieira do Amaral', 
            'email' => 'altimar.amaral@cps.sp.gov.br',
            'password' => '74871480844', 
            'role' => 'professor']);
        User::create([
            'name' => 'André dos Santos Vieira', 
            'email' => 'andre.vieira@cps.sp.gov.br', 
            'password' => '27057855826', 
            'role' => 'professor']);
        User::create([
            'name' => 'André Luiz Formigoni', 
            'email' => 'andre.formigoni@cps.sp.gov.br', 
            'password' => '25035841871', 
            'role' => 'professor']);
        User::create([
            'name' => 'Andréa Luisa M. dos Santos', 
            'email' => 'andrea.santos01@cps.sp.gov.br', 
            'password' => '27949245832', 
            'role' => 'professor']);
        User::create([
            'name' => 'Andrea Pavan Perin', 
            'email' => 'andrea.perin@cps.sp.gov.br', 
            'password' => '21545042802', 
            'role' => 'professor']);
        User::create([
            'name' => 'Antônio Carlos G. de Almeida', 
            'email' => 'antonio.almeida@cps.sp.gov.br', 
            'password' => '55667619849', 
            'role' => 'professor']);
        User::create([
            'name' => 'Arnaldo Gonçalves', 
            'email' => 'arnaldo.goncalves01@cps.sp.gov.br', 
            'password' => '83505288853', 
            'role' => 'professor']);
        User::create([
            'name' => 'Bruno Santos de Miranda', 
            'email' => 'bruno.miranda@cps.sp.gov.br', 
            'password' => '41874094837', 
            'role' => 'professor']);
        User::create([
            'name' => 'Caio Guilherme P. dos S. Kitagaki', 
            'email' => 'caio.kitagaki@cps.sp.gov.br', 
            'password' => '37259062894', 
            'role' => 'professor']);
        User::create([
            'name' => 'Carlos Alexandre Soares', 
            'email' => 'carlos.soares@cps.sp.gov.br', 
            'password' => '29512631806', 
            'role' => 'professor']);
        User::create([
            'name' => 'Cesário de M. Leonel Ferreira', 
            'email' => 'cesario.ferreira@cps.sp.gov.br', 
            'password' => '93081359872', 
            'role' => 'professor']);
        User::create([
            'name' => 'Claudio Sérgio Sartori', 
            'email' => 'claudio.sartori@cps.sp.gov.br', 
            'password' => '10796368899', 
            'role' => 'professor']);
        User::create([
            'name' => 'Daiane Roncato C. Monteiro', 
            'email' => 'daiane.monteiro@cps.sp.gov.br', 
            'password' => '33658620803', 
            'role' => 'professor']);
        User::create([
            'name' => 'Daniel Soares e Marques', 
            'email' => 'daniel.marques01@cps.sp.gov.br', 
            'password' => '5876118478', 
            'role' => 'professor']);
        User::create([
            'name' => 'David Nunes Zaneti de Souza', 
            'email' => 'david.souza@cps.sp.gov.br', 
            'password' => '11960959816', 
            'role' => 'professor']);
        User::create([
            'name' => 'Davison Cardoso Pinheiro', 
            'email' => 'davison.pinheiro@cps.sp.gov.br', 
            'password' => '08209102796', 
            'role' => 'professor']);
        User::create([
            'name' => 'Denis Aparecido N. de Deus', 
            'email' => 'denis.deus@cps.sp.gov.br', 
            'password' => '34220924809', 
            'role' => 'professor']);
        User::create([
            'name' => 'Diego Aparecido C. Albuquerque', 
            'email' => 'diego.albuquerque@cps.sp.gov.br', 
            'password' => '37259061812', 
            'role' => 'professor']);
        User::create([
            'name' => 'Dirlei Paulino Pinto', 
            'email' => 'dirlei.paulino@cps.sp.gov.br', 
            'password' => '30040262871', 
            'role' => 'professor']);
        User::create([
            'name' => 'Dulce Helena Soares Villa Nova', 
            'email' => 'dulce.nova@cps.sp.gov.br', 
            'password' => '25311302844', 
            'role' => 'professor']);
        User::create([
            'name' => 'Edson Ferreira Portela', 
            'email' => 'edson.portela@cps.sp.gov.br', 
            'password' => '08338547808', 
            'role' => 'professor']);
        User::create([
            'name' => 'Eliana Prestes de Souza', 
            'email' => 'eliana.souza02@cps.sp.gov.br', 
            'password' => '32857053886', 
            'role' => 'professor']);
        User::create([
            'name' => 'Élvio Franco de Camargo Aranha', 
            'email' => 'elvio.aranha@cps.sp.gov.br', 
            'password' => '5647469833', 
            'role' => 'professor']);
        User::create([
            'name' => 'Eoná Moro Ribeiro', 
            'email' => 'eona.ribeiro@cps.sp.gov.br', 
            'password' => '19950140870', 
            'role' => 'professor']);
        User::create([
            'name' => 'Evandro Donizette B. de Campos', 
            'email' => 'evandro.campos@cps.sp.gov.br', 
            'password' => '38700214892', 
            'role' => 'professor']);
        User::create([
            'name' => 'Fabiana de Oliveira Lins Kitegroski', 
            'email' => 'fabiana.kitegroski@cps.sp.gov.br', 
            'password' => '35443835866', 
            'role' => 'professor']);
        User::create([
            'name' => 'Fábio Augusto Nogueira', 
            'email' => 'fabio.nogueira@cps.sp.gov.br', 
            'password' => '36968823802', 
            'role' => 'professor']);
        User::create([
            'name' => 'Fabrizio Di Sarno', 
            'email' => 'fabrizio.sarno@cps.sp.gov.br', 
            'password' => '28694191806', 
            'role' => 'professor']);
        User::create([
            'name' => 'Farid Sallum Neto', 
            'email' => 'farid.sallum@cps.sp.gov.br', 
            'password' => '35112457880', 
            'role' => 'professor']);
        User::create([
            'name' => 'Felipe Augusto B. de Almeida', 
            'email' => 'felipe.almeida@cps.sp.gov.br', 
            'password' => '43125198895', 
            'role' => 'professor']);
        User::create([
            'name' => 'Flaviano Agostinho de Lima', 
            'email' => 'flaviano.lima@cps.sp.gov.br', 
            'password' => '07430967892', 
            'role' => 'professor']);
        User::create([
            'name' => 'Gabriel Prestes Américo', 
            'email' => 'gabriel.americo@cps.sp.gov.br', 
            'password' => '45235835875', 
            'role' => 'professor']);
        User::create([
            'name' => 'Giovanni Francesco Guarnieri', 
            'email' => 'giovanni.guarnieri@cps.sp.gov.br', 
            'password' => '39399518892', 
            'role' => 'professor']);
        User::create([
            'name' => 'Jarbas Tavares dos Santos', 
            'email' => 'jarbas.santos@cps.sp.gov.br', 
            'password' => '52034992768', 
            'role' => 'professor']);
        User::create([
            'name' => 'Jayme de Campos Junior', 
            'email' => 'jayme.campos@cps.sp.gov.br', 
            'password' => '11052224890', 
            'role' => 'professor']);
        User::create([
            'name' => 'João Carlos T. dos Santos', 
            'email' => 'joaocarlos.teixeira@fatec.sp.gov.br', 
            'password' => '02177603765', 
            'role' => 'professor']);
        User::create([
            'name' => 'João Fernando de Moraes Sanches', 
            'email' => 'joao.sanches@cps.sp.gov.br', 
            'password' => '27843877800', 
            'role' => 'professor']);
        User::create([
            'name' => 'José Antonio Campos Badin', 
            'email' => 'jose.badin@cps.sp.gov.br', 
            'password' => '01532068883', 
            'role' => 'professor']);
        User::create([
            'name' => 'José Antonio Micheletti', 
            'email' => 'jose.micheletti@fatec.sp.gov.br', 
            'password' => '7193318896', 
            'role' => 'professor']);
        User::create([
            'name' => 'José Camargo Moreira', 
            'email' => 'jose.moreira21@fatec.sp.gov.br', 
            'password' => '02088881875', 
            'role' => 'professor']);
        User::create([
            'name' => 'José Carlos Ferreira', 
            'email' => 'jose.ferreira05@cps.sp.gov.br', 
            'password' => '27288650863', 
            'role' => 'professor']);
        User::create([
            'name' => 'José Carlos M. Junior', 
            'email' => 'jose.martins01@cps.sp.gov.br', 
            'password' => '05506605854', 
            'role' => 'professor']);
        User::create([
            'name' => 'José Carlos P. Junior', 
            'email' => 'jose.pires@cps.sp.gov.br', 
            'password' => '00789173956', 
            'role' => 'professor']);
        User::create([
            'name' => 'José Eduardo dos S. Freire', 
            'email' => 'jose.freire@cps.sp.gov.br', 
            'password' => '15056366803', 
            'role' => 'professor']);
        User::create([
            'name' => 'José Marcio Mathias', 
            'email' => 'jose.mathias@cps.sp.gov.br', 
            'password' => '24641589844', 
            'role' => 'professor']);
        User::create([
            'name' => 'José Maria N. dos Santos', 
            'email' => 'jose.santos09@cps.sp.gov.br', 
            'password' => '14880193801', 
            'role' => 'professor']);
        User::create([
            'name' => 'Jose Norberto Reinprecht', 
            'email' => 'jose.reinprecht@cps.sp.gov.br', 
            'password' => '42594332887', 
            'role' => 'professor']);
        User::create([
            'name' => 'José Rafael Pilan', 
            'email' => 'jose.pilan@cps.sp.gov.br', 
            'password' => '30469581883', 
            'role' => 'professor']);
        User::create([
            'name' => 'José Vicente D. Mascarenhas', 
            'email' => 'jose.mascarenhas@cps.sp.gov.br', 
            'password' => '5038159850', 
            'role' => 'professor']);
        User::create([
            'name' => 'Júlio Cesar de Lemos', 
            'email' => 'julio.lemos@cps.sp.gov.br', 
            'password' => '06730589809', 
            'role' => 'professor']);
        User::create([
            'name' => 'Julio Cesar Gomes de Oliveira', 
            'email' => 'julio.oliveira@cps.sp.gov.br', 
            'password' => '34459463881', 
            'role' => 'professor']);
        User::create([
            'name' => 'Junio Cesar dos Santos Gonçalves', 
            'email' => 'junio.goncalves@cps.sp.gov.br', 
            'password' => '30286048841', 
            'role' => 'professor']);
        User::create([
            'name' => 'Karine de Jesus R. Santana', 
            'email' => 'karine.santana@cps.sp.gov.br', 
            'password' => '09183012621', 
            'role' => 'professor']);
        User::create([
            'name' => 'Loami da Silva Souza', 'email' => 
            'loami.souza@cps.sp.gov.br', 
            'password' => '36089809896', 
            'role' => 'professor']);
        User::create([
            'name' => 'Luan Martins de A. Silva', 
            'email' => 'luan.silva99@fatec.sp.gov.br', 
            'password' => '42338762800', 
            'role' => 'professor']);
        User::create([
            'name' => 'Luana Soares Muzille', 
            'email' => 'luana.muzille@cps.sp.gov.br', 
            'password' => '30895434865', 
            'role' => 'professor']);
        User::create([
            'name' => 'Lucas Correia Meneguette', 
            'email' => 'lucas.meneguette@cps.sp.gov.br', 
            'password' => '36955095897', 
            'role' => 'professor']);
        User::create([
            'name' => 'Luciana Akemi Nakabayashi', 
            'email' => 'luciana.nakabayashi@cps.sp.gov.br', 
            'password' => '18310527845', 
            'role' => 'professor']);
        User::create([
            'name' => 'Luis Antonio G. Fernandes', 
            'email' => 'luis.fernandes@cps.sp.gov.br', 
            'password' => '19727713882', 
            'role' => 'professor']);
        User::create([
            'name' => 'Luis Ricardo O. Santos', 
            'email' => 'luis.santos07@cps.sp.gov.br', 
            'password' => '32495763871', 
            'role' => 'professor']);
        User::create([
            'name' => 'Luiz Antonio V. Pinto', 
            'email' => 'luiz.vargas@cps.sp.gov.br', 
            'password' => '04664989857', 
            'role' => 'professor']);
        User::create([
            'name' => 'Marcelo da Silva Proença', 
            'email' => 'marcelo.proenca@cps.sp.gov.br', 
            'password' => '25869420865', 
            'role' => 'professor']);
        User::create([
            'name' => 'Marcelo de Castro Rebello', 
            'email' => 'marcelo.rebello@cps.sp.gov.br', 
            'password' => '73853038700', 
            'role' => 'professor']);
        User::create([
            'name' => 'Marcelo Menezes', 
            'email' => 'marcelo.menezes01@cps.sp.gov.br', 
            'password' => '3366098848', 'role' => 
            'professor']);
        User::create([
            'name' => 'Marcio Silva de Macedo', 
            'email' => 'marcio.macedo@cps.sp.gov.br', 
            'password' => '14178653857', 
            'role' => 'professor']);
        User::create([
            'name' => 'Marcos Antonio Rosa', 
            'email' => 'marcos.rosa@cps.sp.gov.br', 
            'password' => '04187944800', 
            'role' => 'professor']);
        User::create([
            'name' => 'Marcos Tadeu M. Nunes', 
            'email' => 'marcos.nunes@cps.sp.gov.br', 
            'password' => '15667655870', 'role' => 
            'professor']);
        User::create([
            'name' => 'Maria Aparecida dos Santos', 
            'email' => 'maria.santos08@cps.sp.gov.br', 
            'password' => '80737234687', 
            'role' => 'professor']);
        User::create([
            'name' => 'Maria da Penha S. Gomes', 
            'email' => 'maria.gomes04@cps.sp.gov.br', 
            'password' => '10816996806', 
            'role' => 'professor']);
        User::create([
            'name' => 'Maria do Carmo V. L. Orsi', 
            'email' => 'maria.orsi@cps.sp.gov.br', 
            'password' => '09129777879', 
            'role' => 'professor']);
        User::create([
            'name' => 'Maria José Cardozo', 
            'email' => 'maria.cardozo@cps.sp.gov.br', 
            'password' => '03850893847',
            'role' => 'professor']);
        User::create([
            'name' => 'Mauri César Soares', 
            'email' => 'mauri.soares@cps.sp.gov.br',
            'password' => '03573969879', 
            'role' => 'professor']);
        User::create([
            'name' => 'Maurício Cozer Dias', 
            'email' => 'mauricio.dias@cps.sp.gov.br', 
            'password' => '14978052874', 
            'role' => 'professor']);
        User::create([
            'name' => 'Mauricio Diogo da Silva', 
            'email' => 'mauricio.silva03@cps.sp.gov.br', 
            'password' => '26999792879', 
            'role' => 'professor']);
        User::create([
            'name' => 'Mauricio Perez', 
            'email' => 'mauricio.perez@cps.sp.gov.br', 
            'password' => '37983211830', 
            'role' => 'professor']);
        User::create([
            'name' => 'Mayra Martins Guanaes', 
            'email' => 'mayra.guanaes@cps.sp.gov.br', 
            'password' => '40123778840', 
            'role' => 'professor']);
        User::create([
            'name' => 'Nádia Marcuz', 
            'email' => 'nadia.marcuz@cps.sp.gov.br', 
            'password' => '29968490865', 
            'role' => 'professor']);
        User::create([
            'name' => 'Nelson Guerra', 
            'email' => 'nelson.guerra@cps.sp.gov.br', 
            'password' => '79463398872', 'role' => 
            'professor']);
        User::create([
            'name' => 
            'Nilton José Pereira', 
            'email' => 'nilton.pereira@cps.sp.gov.br', 
            'password' => '7197122899', 
            'role' => 'professor']);
        User::create([
            'name' => 'Olavo Felter Júnior', 
            'email' => 'olavo.felter@cps.sp.gov.br', 
            'password' => '12277813850', 
            'role' => 'professor']);
        User::create([
            'name' => 'Orlando Homen de Mello', 
            'email' => 'orlando.mello@cps.sp.gov.br', 
            'password' => '4558550804', 
            'role' => 'professor']);
        User::create([
            'name' => 'Osvaldo D\'Estefano Rosica', 
            'email' => 'osvaldo.rosica@cps.sp.gov.br', 
            'password' => '3474998803', 
            'role' => 'professor']);
        User::create([
            'name' => 'Otávio dos Santos Gaijutis', 
            'email' => 'otavio.gaijutis@cps.sp.gov.br', 
            'password' => '21403854890', 
            'role' => 'professor']);
        User::create([
            'name' => 'Otávio Luiz Medeiros Tibagy', 
            'email' => 'otavio.tibagy@cps.sp.gov.br', 
            'password' => '29829032884', 
            'role' => 'professor']);
        User::create([
            'name' => 'Paulo Cesar Juliano', 
            'email' => 'paulo.juliano@cps.sp.gov.br', 
            'password' => '89116666887', 
            'role' => 'professor']);
        User::create([
            'name' => 'Paulo Cesar Signori', 
            'email' => 'paulo.signori@cps.sp.gov.br', 
            'password' => '13793782816', 
            'role' => 'professor']);
        User::create([
            'name' => 'Paulo Rubens Rocha Albino', 
            'email' => 'paulo.albino@cps.sp.gov.br', 
            'password' => '27470116826', 'role' => 
            'professor']);
        User::create([
            'name' => 'Pedro Sérgio Rosa', 
            'email' => 'pedro.rosa@cps.sp.gov.br', 
            'password' => '06663773836', 
            'role' => 'professor']);
        User::create([
            'name' => 'Rafael de Sá Mascarenhas', 
            'email' => 'rafael.mascarenhas@cps.sp.gov.br',
            'password' => '43314799861',
            'role' => 'professor']);
        User::create([
            'name' => 'Rafael dos Santos Lima', 
            'email' => 'rafael.lima03@cps.sp.gov.br', 
            'password' => '37272157801', 
            'role' => 'professor']);
        User::create([
            'name' => 'Raquel Ferreira Rocha', 
            'email' => 'raquel.rocha@cps.sp.gov.br', 
            'password' => '16782128810', 
            'role' => 'professor']);
        User::create([
            'name' => 'Renato Corrales Nogueira', 
            'email' => 'renato.nogueira@cps.sp.gov.br', 
            'password' => '13881210857', 
            'role' => 'professor']);
        User::create([
            'name' => 'Ricardo Coura Oliveira', 
            'email' => 'ricardo.oliveira@cps.sp.gov.br', 
            'password' => '14164527889', 
            'role' => 'professor']);
        User::create([
            'name' => 'Rosana Bertila Giacomazzi', 
            'email' => 'rosana.giacomazzi@cps.sp.gov.br', 
            'password' => '9165962818', 
            'role' => 'professor']);
        User::create([
            'name' => 'Rosangela Gonsalves de Araújo',
            'email' => 'rosangela.araujo@cps.sp.gov.br', 
            'password' => '6503956894', 
            'role' => 'professor']);
        User::create([
            'name' => 'Samuel Antonio Vieira', 
            'email' => 'samuel.vieira@cps.sp.gov.br', 
            'password' => '21830922890', 
            'role' => 'professor']);
        User::create([
            'name' => 'Sandra Mauren Ell', 
            'email' => 'sandra.ell@cps.sp.gov.br', 
            'password' => '25221856832', 
            'role' => 'professor']);
        User::create([
            'name' => 'Sandro Gabriel Libretti Prestes', 
            'email' => 'sandro.prestes@cps.sp.gov.br', 
            'password' => '15672486835', 
            'role' => 'professor']);
        User::create([
            'name' => 'Sergio Soares', 
            'email' => 'sergio.soares@cps.sp.gov.br', 
            'password' => '79626181834', 
            'role' => 'professor']);
        User::create([
            'name' => 'Sidinei Aparecido O. Vieira', 
            'email' => 'sidinei.vieira@cps.sp.gov.br', 
            'password' => '26334795880', 
            'role' => 'professor']);
        User::create([
            'name' => 'Tania Regina Tonus', 
            'email' => 'tania.tonus@cps.sp.gov.br', 
            'password' => '09098179878', 
            'role' => 'professor']);
        User::create(['name' => 
            'Thais de Paula Rigoletto', 
            'email' => 'thais.rigoletto@cps.sp.gov.br', 
            'password' => '17392367854', 
            'role' => 'professor']);
        User::create([
            'name' => 'Thiago Rodrigues de S. Pereira', 
            'email' => 'thiago.pereira01@cps.sp.gov.br', 
            'password' => '39747726858', 
            'role' => 'professor']);
        User::create([
            'name' => 'Valeria Cristina Scudeler', 
            'email' => 'valeria.scudeler@cps.sp.gov.br', 
            'password' => '16747509862', 
            'role' => 'professor']);
        User::create([
            'name' => 'Valmir Eduardo G. Cardoso', 
            'email' => 'valmir.cardoso@cps.sp.gov.br', 
            'password' => '64149820597', 
            'role' => 'professor']);
        User::create([
            'name' => 'Valmir Tadeu Fernandes', 
            'email' => 'valmir.fernandes@cps.sp.gov.br', 
            'password' => '04285010879', 
            'role' => 'professor']);
        User::create([
            'name' => 'Vanderlei Guilherme de Macedo', 
            'email' => 'vanderlei.filho01@fatec.sp.gov.br', 
            'password' => '16028671860', 
            'role' => 'professor']);
        User::create([
            'name' => 'Volney Mattos de Oliveira', 
            'email' => 'volney.oliveira@cps.sp.gov.br', 
            'password' => '14202636848', 
            'role' => 'professor']);
        User::create([
            'name' => 'Wagner Rodrigues Ferreira', 
            'email' => 'wagner.ferreira01@cps.sp.gov.br', 
            'password' => '35360700866', 
            'role' => 'professor']);
    }
}