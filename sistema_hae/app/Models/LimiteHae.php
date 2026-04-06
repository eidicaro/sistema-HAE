<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LimiteHae extends Model
{
    protected $table = 'limites_hae';

    protected $fillable = [
        'tipo',
        'carga_total'
    ];
}
