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

    protected function casts(): array
    {
        return [
            'ativo' => 'boolean',
            'limite' => 'integer',
        ];
    }

    public function haes()
    {
        return $this->hasMany(Haes::class);
    }
}
