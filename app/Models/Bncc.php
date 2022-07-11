<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bncc extends Model
{
    protected $table = 'bncc';

    protected $primaryKey = 'id';

    protected $guarded = [];

    public $timestamps = false;

    public $fillable = ['tipo_bncc','disciplina','componente','ano_faixa','campos_de_atuacao','praticas_de_linguagem','objetos_de_conhecimento','habilidades'];

    public function bnccSeries()
    {
        return $this->belongsToMany(
            AnosSeries::class, 
            'bncc_series', 
            'bncc_id',
            'serie_id'
        );
    }
}
