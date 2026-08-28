<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Haes extends Model
{
    public const STATUS_PENDENTE = 'pendente';

    public const STATUS_DILIGENCIA = 'com_diligencia';

    public const STATUS_EM_EXECUCAO = 'em_execucao';

    public const STATUS_FINALIZADA = 'finalizada';

    public const STATUS_RECUSADA = 'recusada';

    public const STATUS_VALIDOS = [
        self::STATUS_PENDENTE,
        self::STATUS_DILIGENCIA,
        self::STATUS_EM_EXECUCAO,
        self::STATUS_FINALIZADA,
        self::STATUS_RECUSADA,
    ];

    public const STATUS_QUE_RESERVAM_CARGA = [
        self::STATUS_PENDENTE,
        self::STATUS_DILIGENCIA,
        self::STATUS_EM_EXECUCAO,
        self::STATUS_FINALIZADA,
    ];

    public const CURSOS = [
        'Automação Industrial',
        'Manutenção Industrial',
        'Gestão Empresarial',
        'Gestão da Tecnologia da Informação',
        'Produção Fonográfica',
        'AMS - Análise e Desenvolvimento de Sistemas',
        'AMS - Processos Gerenciais',
    ];

    protected $table = 'haes';

    protected $fillable = [
        'user_id',
        'edital_aceito',
        'curso',
        'titulo',
        'carga_horaria',
        'resumo',
        'justificativa',
        'cronograma',
        'status',
        'semestre_id',
        'especificacoes',
        'tipo_hae_id',
    ];

    protected function casts(): array
    {
        return [
            'edital_aceito' => 'boolean',
            'carga_horaria' => 'integer',
        ];
    }

    public function podeSerVistaPor(User $user): bool
    {
        return match ($user->role) {
            User::ROLE_PROFESSOR => $this->user_id === $user->id
                || $this->relatores()->whereKey($user->id)->exists(),
            User::ROLE_COORDENADOR => $this->curso === $user->curso
                || $this->relatores()->whereKey($user->id)->exists(),
            User::ROLE_DIRECAO => true,
            default => false,
        };
    }

    //  RELACIONAMENTOS

    // HAE pertence a um usuário (professor)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // PARECERES (relatores)
    public function pareceres()
    {
        return $this->hasMany(Parecer::class, 'hae_id');
    }

    // DECISÕES (coordenação/direção)
    public function decisoes()
    {
        return $this->hasMany(Decisao::class, 'hae_id');
    }

    public function relatores()
    {
        return $this->belongsToMany(User::class, 'relatores', 'hae_id', 'user_id');
    }

    // SEMESTRE
    public function semestre()
    {
        return $this->belongsTo(Semestres::class);
    }

    // RELATORIOS
    public function relatorio()
    {
        return $this->hasOne(Relatorio::class, 'hae_id')->latestOfMany();
    }

    // TIPOS HAE
    public function tipoHae()
    {
        return $this->belongsTo(TipoHae::class);
    }
}
