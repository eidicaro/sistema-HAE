<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RelatorioArquivo;
use App\Models\RelatorioResultado;

class Relatorio extends Model
{
    const STATUS_PENDENTE = 'pendente';
    const STATUS_APROVADO = 'aprovado';
    const STATUS_ENVIADO = 'enviado';
    const STATUS_RECUSADO = 'reprovado';


    protected $fillable = [
        'hae_id',
        'titulo',
        'sumario',
        'resultados_texto',
        'status'
    ];

    public function hae()
    {
        return $this->belongsTo(Haes::class);
    }

    public function arquivos()
    {
        return $this->hasMany(RelatorioArquivo::class);
    }

    public function resultados()
    {
        return $this->hasMany(RelatorioResultado::class);
    }
}
