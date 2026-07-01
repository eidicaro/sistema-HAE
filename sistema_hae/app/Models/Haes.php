<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Haes extends Model
{
    protected $table = 'haes';
    const STATUS_PENDENTE = 'pendente';
    const STATUS_DILIGENCIA = 'com_diligencia';
    const STATUS_APROVADA = 'aprovada';
    const STATUS_EM_EXECUCAO = 'em_execucao';
    const STATUS_FINALIZADA = 'finalizada';
    const STATUS_RECUSADA = 'recusada';

    protected $fillable = [
        'user_id',
        'tipo',
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
        return $this->hasOne(Relatorio::class, 'hae_id');
    }

    //TIPOS HAE
    public function tipoHae()
    {
        return $this->belongsTo(TipoHae::class);
    }
}