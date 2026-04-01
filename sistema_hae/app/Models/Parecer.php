<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parecer extends Model
{
    protected $table = 'parecer';

    protected $fillable = [
        'hae_id',
        'user_id',
        'tipo',
        'comentario'
    ];

    public function hae()
    {
        return $this->belongsTo(Hae::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

