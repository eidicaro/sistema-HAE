<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HaeAdministracao extends Model
{
    protected $table = 'hae_administracao';

    protected $fillable = [
        'hae_id',
        'tipo_admin',
        'encontros',
        'relatorios',
        'acoes_interdisciplinares',
        'formacoes',
        'materiais',
        'indicador',
        'outra_acao'
    ];

    public function hae() {
        return $this->belongsTo(Haes::class);
    }
}