<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parecer extends Model
{
    protected $table = 'parecer';
    protected $fillable = ['hae_id', 'relator_id', 'comentario'];

    public function hae()
    {
        return $this->belongsTo(Haes::class, 'hae_id');
    }

    public function relator()
    {
        return $this->belongsTo(User::class, 'relator_id');
    }
}

