<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Decisao extends Model
{
    protected $table = 'decisoes';

    protected $fillable = [
        'hae_id',
        'avaliador_id',
        'decisao',
        'comentario'
    ];

    public function hae()
    {
        return $this->belongsTo(Haes::class, 'hae_id');
    }

    public function avaliador()
    {
        return $this->belongsTo(User::class, 'avaliador_id');
    }
}