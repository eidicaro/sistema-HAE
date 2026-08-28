<?php

namespace Tests\Feature;

use App\Exports\HaesExport;
use App\Models\Haes;
use App\Models\Relatorio;
use App\Models\RelatorioArquivo;
use App\Models\Semestres;
use App\Models\SubtipoHae;
use App\Models\TipoHae;
use App\Models\User;
use Database\Seeders\UsuariosTesteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HaeWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_home_is_available(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_manual_test_users_seeder_creates_expected_profiles(): void
    {
        $this->seed(UsuariosTesteSeeder::class);
        $this->seed(UsuariosTesteSeeder::class);

        $this->assertDatabaseCount('users', 4);
        $this->assertSame(2, User::where('role', User::ROLE_PROFESSOR)->count());
        $this->assertSame(2, User::where('role', User::ROLE_COORDENADOR)->count());
        $this->assertDatabaseCount('tipo_haes', 3);
        $this->assertSame(3, TipoHae::where('ativo', true)->count());
        $this->assertDatabaseCount('subtipo_haes', 6);
        $this->assertDatabaseHas('tipo_haes', [
            'nome' => 'Extensão (teste manual)',
            'limite' => 100,
            'ativo' => true,
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'coordenador.teste1@fatec.sp.gov.br',
            'curso' => Haes::CURSOS[0],
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'coordenador.teste2@fatec.sp.gov.br',
            'curso' => Haes::CURSOS[1],
        ]);
    }

    public function test_responses_include_security_headers(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'DENY')
            ->assertHeader('Referrer-Policy', 'same-origin')
            ->assertHeader('Content-Security-Policy');

        $professor = User::factory()->create(['role' => User::ROLE_PROFESSOR]);

        $this->actingAs($professor)
            ->get(route('professor'))
            ->assertOk()
            ->assertHeader('Cache-Control', 'no-store, private');
    }

    public function test_login_is_rate_limited(): void
    {
        $payload = ['email' => 'inexistente@fatec.sp.gov.br', 'password' => 'senha-invalida'];

        for ($tentativa = 1; $tentativa <= 5; $tentativa++) {
            $this->post('/login/professor', $payload)->assertRedirect();
        }

        $this->post('/login/professor', $payload)->assertTooManyRequests();
    }

    public function test_login_does_not_reveal_that_account_belongs_to_another_profile(): void
    {
        $direcao = User::factory()->create([
            'role' => User::ROLE_DIRECAO,
            'password' => 'Senha1234',
        ]);

        $this->post('/login/professor', [
            'email' => $direcao->email,
            'password' => 'Senha1234',
        ])
            ->assertRedirect()
            ->assertSessionHas('erro', 'E-mail, senha ou perfil inválido.');

        $this->assertGuest();
    }

    public function test_profiles_cannot_access_another_profiles_routes(): void
    {
        $professor = User::factory()->create(['role' => User::ROLE_PROFESSOR]);
        $direcao = User::factory()->create(['role' => User::ROLE_DIRECAO]);

        $this->actingAs($professor)->get(route('semestres.index'))->assertForbidden();
        $this->actingAs($direcao)->get(route('hae.create'))->assertForbidden();
    }

    public function test_assigned_reviewer_can_view_hae_but_unrelated_professor_cannot(): void
    {
        $autor = User::factory()->create(['role' => User::ROLE_PROFESSOR]);
        $relator = User::factory()->create(['role' => User::ROLE_PROFESSOR]);
        $outro = User::factory()->create(['role' => User::ROLE_PROFESSOR]);
        $hae = $this->criarHae($autor);
        $hae->relatores()->attach($relator);

        $this->actingAs($relator)->get(route('hae.show', $hae))->assertOk();
        $this->actingAs($outro)->get(route('hae.show', $hae))->assertForbidden();
    }

    public function test_professor_cannot_update_another_professors_hae(): void
    {
        $autor = User::factory()->create(['role' => User::ROLE_PROFESSOR]);
        $outro = User::factory()->create(['role' => User::ROLE_PROFESSOR]);
        $hae = $this->criarHae($autor, ['status' => Haes::STATUS_DILIGENCIA]);

        $this->actingAs($outro)->put(route('hae.update', $hae), [])->assertForbidden();
    }

    public function test_professor_can_submit_valid_hae_to_active_semester(): void
    {
        $professor = User::factory()->create(['role' => User::ROLE_PROFESSOR]);
        $semestre = Semestres::create([
            'nome' => '2026/1',
            'data_inicio' => '2026-01-01',
            'data_fim' => '2026-06-30',
            'ativo' => true,
        ]);
        $tipo = TipoHae::create(['nome' => 'Extensão', 'limite' => 20, 'ativo' => true]);
        $subtipo = SubtipoHae::create([
            'tipo_hae_id' => $tipo->id,
            'nome' => 'Projeto comunitário',
            'ativo' => true,
        ]);

        $this->actingAs($professor)->post(route('hae.store'), [
            'tipo_hae_id' => $tipo->id,
            'subtipo_hae_id' => $subtipo->id,
            'edital' => '1',
            'curso' => Haes::CURSOS[0],
            'titulo' => 'Projeto institucional',
            'carga_horaria' => 4,
            'resumo' => 'Resumo',
            'justificativa' => 'Justificativa',
            'resultados_esperados' => 'Resultados esperados',
            'indicadores' => 'Indicadores de avaliação',
            'mes_1' => 'Planejamento',
            'mes_2' => 'Execução inicial',
            'mes_3' => 'Execução intermediária',
            'mes_4' => 'Conclusão',
            'mes_5' => 'Avaliação',
            'horarios_hae' => 'Segundas, das 19h às 21h',
        ])->assertRedirect(route('professor'));

        $this->assertDatabaseHas('haes', [
            'user_id' => $professor->id,
            'semestre_id' => $semestre->id,
            'subtipo_hae_id' => $subtipo->id,
            'status' => Haes::STATUS_PENDENTE,
            'resultados_esperados' => 'Resultados esperados',
            'indicadores' => 'Indicadores de avaliação',
            'mes_5' => 'Avaliação',
            'horarios_hae' => 'Segundas, das 19h às 21h',
        ]);
    }

    public function test_hae_form_keeps_all_institutional_questions(): void
    {
        $professor = User::factory()->create(['role' => User::ROLE_PROFESSOR]);

        $response = $this->actingAs($professor)->get(route('hae.create'))->assertOk();

        foreach ([
            'resultados_esperados',
            'indicadores',
            'mes_1',
            'mes_2',
            'mes_3',
            'mes_4',
            'mes_5',
            'horarios_hae',
        ] as $campo) {
            $response->assertSee('name="'.$campo.'"', false);
        }
    }

    public function test_report_follows_owner_and_direction_workflow(): void
    {
        $autor = User::factory()->create(['role' => User::ROLE_PROFESSOR]);
        $outro = User::factory()->create(['role' => User::ROLE_PROFESSOR]);
        $direcao = User::factory()->create(['role' => User::ROLE_DIRECAO]);
        $hae = $this->criarHae($autor, ['status' => Haes::STATUS_EM_EXECUCAO]);
        $dados = [
            'titulo' => 'Relatório final',
            'sumario' => 'Síntese da execução',
            'resultados_texto' => 'Resultados alcançados',
        ];

        $this->actingAs($outro)
            ->post(route('relatorio.store', $hae), $dados)
            ->assertForbidden();

        $this->actingAs($autor)
            ->post(route('relatorio.store', $hae), $dados)
            ->assertRedirect(route('hae.show', $hae));

        $relatorio = Relatorio::where('hae_id', $hae->id)->firstOrFail();
        $this->assertSame(Relatorio::STATUS_ENVIADO, $relatorio->status);

        $this->actingAs($direcao)
            ->post(route('relatorio.aprovar', $relatorio))
            ->assertSessionHas('sucesso');

        $this->assertDatabaseHas('haes', [
            'id' => $hae->id,
            'status' => Haes::STATUS_FINALIZADA,
        ]);
    }

    public function test_report_rejects_unsafe_attachment_type(): void
    {
        Storage::fake('local');
        $professor = User::factory()->create(['role' => User::ROLE_PROFESSOR]);
        $hae = $this->criarHae($professor, ['status' => Haes::STATUS_EM_EXECUCAO]);

        $this->actingAs($professor)
            ->post(route('relatorio.store', $hae), [
                'titulo' => 'Relatório final',
                'sumario' => 'Síntese',
                'resultados_texto' => 'Resultados',
                'arquivo_principal' => UploadedFile::fake()->createWithContent(
                    'conteudo.html',
                    '<script>alert(1)</script>'
                ),
            ])
            ->assertSessionHasErrors('arquivo_principal');

        $this->assertDatabaseMissing('relatorios', ['hae_id' => $hae->id]);
        Storage::disk('local')->assertDirectoryEmpty('relatorios');
    }

    public function test_report_file_cannot_be_downloaded_by_unrelated_user(): void
    {
        Storage::fake('local');
        $autor = User::factory()->create(['role' => User::ROLE_PROFESSOR]);
        $outro = User::factory()->create(['role' => User::ROLE_PROFESSOR]);
        $hae = $this->criarHae($autor, ['status' => Haes::STATUS_EM_EXECUCAO]);
        $relatorio = Relatorio::create([
            'hae_id' => $hae->id,
            'titulo' => 'Relatório',
            'sumario' => 'Síntese',
            'resultados_texto' => 'Resultados',
            'status' => Relatorio::STATUS_ENVIADO,
        ]);
        Storage::disk('local')->put('relatorios/documento.docx', 'conteudo');
        $arquivo = RelatorioArquivo::create([
            'relatorio_id' => $relatorio->id,
            'caminho' => 'relatorios/documento.docx',
            'tipo' => 'principal',
        ]);

        $this->actingAs($outro)
            ->get(route('arquivo.download', $arquivo))
            ->assertForbidden();

        $this->actingAs($autor)
            ->get(route('arquivo.ver', $arquivo))
            ->assertDownload();
    }

    public function test_spreadsheet_export_neutralizes_formula_injection(): void
    {
        $professor = User::factory()->create(['role' => User::ROLE_PROFESSOR]);
        $hae = $this->criarHae($professor, ['titulo' => '=HYPERLINK("https://example.test")']);
        $hae->load(['user', 'tipoHae', 'semestre', 'relatores']);

        $exportacao = new HaesExport($hae->semestre_id);
        $linha = $exportacao->map($hae);

        $this->assertSame('\'=HYPERLINK("https://example.test")', $linha[10]);
        $this->assertCount(count($exportacao->headings()), $linha);
    }

    public function test_direction_cannot_create_professor_with_password_shorter_than_six_characters(): void
    {
        $direcao = User::factory()->create(['role' => User::ROLE_DIRECAO]);

        $this->actingAs($direcao)
            ->post(route('direcao.professores.store'), [
                'name' => 'Professor Teste',
                'email' => 'professor@fatec.sp.gov.br',
                'password' => '12345',
                'password_confirmation' => '12345',
            ])
            ->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', ['email' => 'professor@fatec.sp.gov.br']);
    }

    public function test_main_pages_render_for_each_profile(): void
    {
        $professor = User::factory()->create(['role' => User::ROLE_PROFESSOR]);
        $coordenador = User::factory()->create([
            'role' => User::ROLE_COORDENADOR,
            'curso' => Haes::CURSOS[0],
        ]);
        $direcao = User::factory()->create(['role' => User::ROLE_DIRECAO]);
        $hae = $this->criarHae($professor);
        $hae->relatores()->attach($coordenador);

        $this->actingAs($professor)->get(route('professor'))->assertOk();
        $this->actingAs($professor)->get(route('hae.create'))->assertOk();
        $this->actingAs($coordenador)->get(route('coordenador'))->assertOk();

        $this->actingAs($direcao)->get(route('direcao'))->assertOk();
        $this->actingAs($direcao)->get(route('direcao.relatores'))->assertOk();
        $this->actingAs($direcao)->get(route('direcao.resultados'))->assertOk();
        $this->actingAs($direcao)->get(route('semestres.index'))->assertOk();
        $this->actingAs($direcao)->get(route('direcao.tipos-hae.index'))->assertOk();
        $this->actingAs($direcao)->get(route('direcao.tipos-hae.create'))->assertOk();
        $this->actingAs($direcao)->get(route('direcao.professores.index'))->assertOk();
        $this->actingAs($direcao)->get(route('direcao.professores.create'))->assertOk();
        $this->actingAs($direcao)->get(route('direcao.professores.edit', $professor))->assertOk();
    }

    public function test_direction_limit_calculation_is_scoped_to_hae_semester(): void
    {
        $direcao = User::factory()->create(['role' => User::ROLE_DIRECAO]);
        $professor = User::factory()->create(['role' => User::ROLE_PROFESSOR]);
        $tipo = TipoHae::create(['nome' => 'Projetos', 'limite' => 10, 'ativo' => true]);
        $anterior = Semestres::create([
            'nome' => '2025/2',
            'data_inicio' => '2025-08-01',
            'data_fim' => '2025-12-31',
            'ativo' => false,
        ]);
        $atual = Semestres::create([
            'nome' => '2026/1',
            'data_inicio' => '2026-01-01',
            'data_fim' => '2026-06-30',
            'ativo' => true,
        ]);

        $this->criarHae($professor, [
            'tipo_hae_id' => $tipo->id,
            'semestre_id' => $anterior->id,
            'carga_horaria' => 100,
            'status' => Haes::STATUS_EM_EXECUCAO,
        ]);
        $pendente = $this->criarHae($professor, [
            'tipo_hae_id' => $tipo->id,
            'semestre_id' => $atual->id,
            'carga_horaria' => 5,
        ]);

        $this->actingAs($direcao)
            ->post(route('direcao.decisao', $pendente), ['acao' => 'aprovada'])
            ->assertSessionHas('sucesso');

        $this->assertDatabaseHas('haes', [
            'id' => $pendente->id,
            'status' => Haes::STATUS_EM_EXECUCAO,
        ]);
    }

    public function test_export_uses_safe_filename_when_semester_contains_slash(): void
    {
        $direcao = User::factory()->create(['role' => User::ROLE_DIRECAO]);
        Semestres::create([
            'nome' => '2026/1',
            'data_inicio' => '2026-01-01',
            'data_fim' => '2026-06-30',
            'ativo' => true,
        ]);

        $this->actingAs($direcao)
            ->get(route('direcao.exportarcsv'))
            ->assertOk()
            ->assertDownload('relatorio_HAEs_2026-1.xlsx');
    }

    public function test_subtypes_share_the_parent_type_hour_limit(): void
    {
        $professor = User::factory()->create(['role' => User::ROLE_PROFESSOR]);
        $semestre = Semestres::create([
            'nome' => '2026/2',
            'data_inicio' => '2026-08-01',
            'data_fim' => '2026-12-31',
            'ativo' => true,
        ]);
        $tipo = TipoHae::create(['nome' => 'Projetos', 'limite' => 10, 'ativo' => true]);
        $subtipoA = SubtipoHae::create(['tipo_hae_id' => $tipo->id, 'nome' => 'Projeto A', 'ativo' => true]);
        $subtipoB = SubtipoHae::create(['tipo_hae_id' => $tipo->id, 'nome' => 'Projeto B', 'ativo' => true]);

        $this->criarHae($professor, [
            'semestre_id' => $semestre->id,
            'tipo_hae_id' => $tipo->id,
            'subtipo_hae_id' => $subtipoA->id,
            'carga_horaria' => 8,
        ]);

        $this->actingAs($professor)
            ->post(route('hae.store'), [
                'tipo_hae_id' => $tipo->id,
                'subtipo_hae_id' => $subtipoB->id,
                'edital' => '1',
                'curso' => Haes::CURSOS[0],
                'titulo' => 'Segunda proposta',
                'carga_horaria' => 3,
                'resumo' => 'Resumo',
                'justificativa' => 'Justificativa',
            ])
            ->assertSessionHasErrors([
                'carga_horaria' => 'A carga horária solicitada excede o limite disponível para este tipo de HAE.',
            ]);

        $this->assertDatabaseMissing('haes', ['titulo' => 'Segunda proposta']);
    }

    public function test_subtype_must_belong_to_selected_parent_type(): void
    {
        $professor = User::factory()->create(['role' => User::ROLE_PROFESSOR]);
        Semestres::create([
            'nome' => '2026/2',
            'data_inicio' => '2026-08-01',
            'data_fim' => '2026-12-31',
            'ativo' => true,
        ]);
        $tipoA = TipoHae::create(['nome' => 'Tipo A', 'limite' => 20, 'ativo' => true]);
        $tipoB = TipoHae::create(['nome' => 'Tipo B', 'limite' => 20, 'ativo' => true]);
        $subtipoB = SubtipoHae::create(['tipo_hae_id' => $tipoB->id, 'nome' => 'Subtipo B', 'ativo' => true]);

        $this->actingAs($professor)
            ->post(route('hae.store'), [
                'tipo_hae_id' => $tipoA->id,
                'subtipo_hae_id' => $subtipoB->id,
                'edital' => '1',
                'curso' => Haes::CURSOS[0],
                'titulo' => 'Combinação inválida',
                'carga_horaria' => 3,
                'resumo' => 'Resumo',
                'justificativa' => 'Justificativa',
            ])
            ->assertSessionHasErrors('subtipo_hae_id');
    }

    public function test_only_direction_can_create_and_manage_subtypes(): void
    {
        $direcao = User::factory()->create(['role' => User::ROLE_DIRECAO]);
        $professor = User::factory()->create(['role' => User::ROLE_PROFESSOR]);
        $tipo = TipoHae::create(['nome' => 'Atividades acadêmicas', 'limite' => 30, 'ativo' => true]);

        $this->actingAs($professor)
            ->post(route('direcao.tipos-hae.subtipos.store', $tipo), [
                'nome' => 'Monitoria',
                'ativo' => '1',
            ])
            ->assertForbidden();

        $this->actingAs($direcao)
            ->post(route('direcao.tipos-hae.subtipos.store', $tipo), [
                'nome' => 'Monitoria',
                'descricao' => 'Acompanhamento de estudantes',
                'ativo' => '1',
            ])
            ->assertSessionHas('success');

        $subtipo = SubtipoHae::where('tipo_hae_id', $tipo->id)->firstOrFail();
        $this->assertTrue($subtipo->ativo);

        $this->actingAs($direcao)
            ->get(route('direcao.tipos-hae.edit', $tipo))
            ->assertOk()
            ->assertSee('Monitoria');

        $this->actingAs($direcao)
            ->post(route('direcao.tipos-hae.subtipos.toggle', [$tipo, $subtipo]))
            ->assertSessionHas('success');

        $this->assertFalse($subtipo->fresh()->ativo);
    }

    public function test_authorized_user_can_download_hae_pdf(): void
    {
        $professor = User::factory()->create(['role' => User::ROLE_PROFESSOR]);
        $outro = User::factory()->create(['role' => User::ROLE_PROFESSOR]);
        $hae = $this->criarHae($professor, ['titulo' => 'Projeto com ação']);

        $this->actingAs($outro)
            ->get(route('hae.pdf', $hae))
            ->assertForbidden();

        $response = $this->actingAs($professor)
            ->get(route('hae.pdf', $hae))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf')
            ->assertDownload('hae-'.$hae->id.'-projeto-com-acao.pdf');

        $this->assertStringStartsWith('%PDF', $response->getContent());
    }

    private function criarHae(User $professor, array $attributes = []): Haes
    {
        $semestre = Semestres::find($attributes['semestre_id'] ?? null)
            ?? Semestres::create([
                'nome' => '2026/1',
                'data_inicio' => '2026-01-01',
                'data_fim' => '2026-06-30',
                'ativo' => true,
            ]);
        $tipo = TipoHae::find($attributes['tipo_hae_id'] ?? null)
            ?? TipoHae::create(['nome' => 'Extensão', 'limite' => 200, 'ativo' => true]);
        $subtipo = SubtipoHae::find($attributes['subtipo_hae_id'] ?? null)
            ?? SubtipoHae::firstOrCreate(
                ['tipo_hae_id' => $tipo->id, 'nome' => 'Subtipo geral'],
                ['ativo' => true]
            );

        return Haes::create(array_merge([
            'user_id' => $professor->id,
            'semestre_id' => $semestre->id,
            'tipo_hae_id' => $tipo->id,
            'subtipo_hae_id' => $subtipo->id,
            'edital_aceito' => true,
            'curso' => Haes::CURSOS[0],
            'titulo' => 'Projeto de teste',
            'carga_horaria' => 4,
            'resumo' => 'Resumo',
            'justificativa' => 'Justificativa',
            'resultados_esperados' => null,
            'indicadores' => null,
            'mes_1' => null,
            'mes_2' => null,
            'mes_3' => null,
            'mes_4' => null,
            'mes_5' => null,
            'horarios_hae' => null,
            'status' => Haes::STATUS_PENDENTE,
        ], $attributes));
    }
}
