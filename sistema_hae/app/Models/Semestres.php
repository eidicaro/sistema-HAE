<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semestres extends Model
{
    protected $fillable = ['nome', 'data_inicio', 'data_fim', 'ativo'];

    protected function casts(): array
    {
        return [
            'data_inicio' => 'date',
            'data_fim' => 'date',
            'ativo' => 'boolean',
        ];
    }

    // relacionamento

    public function haes()
    {
        return $this->hasMany(Haes::class);
    }
}
