<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelatorioArquivo extends Model
{
    use HasFactory;

    protected $table = 'relatorio_arquivos';

    protected $fillable = [
        'relatorio_id',
        'caminho',
        'tipo',
    ];

    // 🔗 relacionamento com Relatorio
    public function relatorio()
    {
        return $this->belongsTo(Relatorio::class);
    }
}
