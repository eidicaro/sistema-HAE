<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoHae extends Model
{
    public $table = 'tipo_haes';
    protected $fillable = [
        'nome',
        'descricao',
        'limite',
        'ativo',
    ];

    public function hae() {
        return $this->hasMany(Haes::class);
    }
}
