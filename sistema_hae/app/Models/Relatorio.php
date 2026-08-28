<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relatorio extends Model
{
    public const STATUS_PENDENTE = 'pendente';

    public const STATUS_APROVADO = 'aprovado';

    public const STATUS_ENVIADO = 'enviado';

    public const STATUS_RECUSADO = 'reprovado';

    protected $fillable = [
        'hae_id',
        'titulo',
        'sumario',
        'resultados_texto',
        'status',
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
