<?php

namespace Tests\Feature;

use App\Models\Haes;
use App\Models\Relatorio;
use App\Models\Semestres;
use App\Models\TipoHae;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HaeWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_home_is_available(): void
    {
        $this->get('/')->assertOk();
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

        $this->actingAs($professor)->post(route('hae.store'), [
            'tipo_hae_id' => $tipo->id,
            'edital' => '1',
            'curso' => Haes::CURSOS[0],
            'titulo' => 'Projeto institucional',
            'carga_horaria' => 4,
            'resumo' => 'Resumo',
            'justificativa' => 'Justificativa',
        ])->assertRedirect(route('professor'));

        $this->assertDatabaseHas('haes', [
            'user_id' => $professor->id,
            'semestre_id' => $semestre->id,
            'status' => Haes::STATUS_PENDENTE,
            'especificacoes' => '',
        ]);
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

        return Haes::create(array_merge([
            'user_id' => $professor->id,
            'semestre_id' => $semestre->id,
            'tipo_hae_id' => $tipo->id,
            'edital_aceito' => true,
            'curso' => Haes::CURSOS[0],
            'titulo' => 'Projeto de teste',
            'carga_horaria' => 4,
            'resumo' => 'Resumo',
            'justificativa' => 'Justificativa',
            'especificacoes' => '',
            'cronograma' => null,
            'status' => Haes::STATUS_PENDENTE,
        ], $attributes));
    }
}
