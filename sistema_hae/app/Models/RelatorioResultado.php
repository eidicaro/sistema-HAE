<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelatorioResultado extends Model
{
    use HasFactory;

    protected $table = 'relatorio_resultados';

    protected $fillable = [
        'relatorio_id',
        'campo',
        'previsto',
        'realizado',
    ];

    // 🔗 relacionamento com Relatorio
    public function relatorio()
    {
        return $this->belongsTo(Relatorio::class);
    }
}
