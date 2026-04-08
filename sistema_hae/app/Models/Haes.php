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
        'fevereiro',
        'marco',
        'abril',
        'maio',
        'junho',
        'status',
        'semestre_id'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONAMENTOS
    |--------------------------------------------------------------------------
    */

    // 🔗 HAE pertence a um usuário (professor)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 🔗 TIPOS DE HAE (1:1)

    public function graduacao()
    {
        return $this->hasOne(HaeGraduacao::class, 'hae_id');
    }

    public function administracao()
    {
        return $this->hasOne(HaeAdministracao::class, 'hae_id');
    }

    public function estudos()
    {
        return $this->hasOne(HaeEstudos::class, 'hae_id');
    }

    public function extensao()
    {
        return $this->hasOne(HaeExtensao::class, 'hae_id');
    }

    public function plantao()
    {
        return $this->hasOne(HaePlantao::class, 'hae_id');
    }

    public function ams()
    {
        return $this->hasOne(HaeAms::class, 'hae_id');
    }

    // 🔗 PARECERES (relatores)
    public function pareceres()
    {
        return $this->hasMany(Parecer::class, 'hae_id');
    }

    // 🔗 DECISÕES (coordenação/direção)
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
}