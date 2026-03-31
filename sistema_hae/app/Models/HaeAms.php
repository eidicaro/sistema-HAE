<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HaeAms extends Model
{
    protected $table = 'hae_ams';

    protected $fillable = [
        'hae_id',
        'tipo_ams',
        'escolas',
        'eventos',
        'encontros_alunos',
        'indicador',
        'outra_acao'
    ];

    public function hae() {
        return $this->belongsTo(Haes::class);
    }
}
