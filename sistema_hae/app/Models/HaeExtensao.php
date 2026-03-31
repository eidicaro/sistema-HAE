<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HaeExtensao extends Model
{
    protected $table = 'hae_extensao';

    protected $fillable = [
        'hae_id',
        'tipo_extensao',
        'pessoas',
        'instituicoes',
        'eventos',
        'beneficiarios',
        'indicador',
        'outra_acao'
    ];

    public function hae() {
        return $this->belongsTo(Haes::class);
    }
}