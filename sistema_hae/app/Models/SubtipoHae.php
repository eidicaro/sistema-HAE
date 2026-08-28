<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubtipoHae extends Model
{
    protected $table = 'subtipo_haes';

    protected $fillable = [
        'tipo_hae_id',
        'nome',
        'descricao',
        'ativo',
    ];

    protected function casts(): array
    {
        return [
            'ativo' => 'boolean',
        ];
    }

    public function tipoHae()
    {
        return $this->belongsTo(TipoHae::class);
    }

    public function haes()
    {
        return $this->hasMany(Haes::class);
    }
}
