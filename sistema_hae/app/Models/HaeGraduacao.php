<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HaeGraduacao extends Model
{
    protected $table = 'hae_graduacao';

    protected $fillable = [
        'hae_id',
        'tipo_graduacao',
        'orientacoes',
        'bancas_orientador',
        'bancas_membro',
        'indicador'
    ];

    public function hae() {
        return $this->belongsTo(Haes::class);
    }
}
