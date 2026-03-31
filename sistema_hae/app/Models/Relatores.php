<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relatores extends Model
{
    protected $table = 'relatores';

    protected $fillable = [
        'hae_id',
        'user_id'
    ];

    public function hae() {
        return $this->belongsTo(Haes::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
