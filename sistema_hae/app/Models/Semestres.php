<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semestres extends Model
{
    protected $fillable = ['nome', 'data_inicio', 'data_fim', 'ativo'];

    //relacionamento
    
    public function haes()
    {
        return $this->hasMany(Haes::class);
    }
}
