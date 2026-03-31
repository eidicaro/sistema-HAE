<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HaePlantao extends Model
{
    protected $table = 'hae_plantao';

    protected $fillable = [
        'hae_id',
        'tipo_plantao',
        'alunos_atendidos',
        'simulados',
        'relatorios',
        'acoes',
        'indicador',
        'outra_acao'
    ];

    public function hae() {
        return $this->belongsTo(Haes::class);
    }
}
