<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HaeEstudos extends Model
{
    protected $table = 'hae_estudos';

    protected $fillable = [
        'hae_id',
        'tipo_estudo',
        'alunos',
        'propostas',
        'prototipos',
        'artigos',
        'resumos',
        'indicador',
        'outra_acao'
    ];

    public function hae() {
        return $this->belongsTo(Haes::class);
    }
}
