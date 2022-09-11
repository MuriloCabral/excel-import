<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrequenciaFuncionarioConsolidado extends Model
{
    protected $table = 'tbfrequencia_funcionario_consolidado';

    protected $primaryKey = 'tbfrequencia_funcionario_consolidado_id';

    protected $guarded = [];

    protected $casts = [
        //'tbconfiguracaoquadroaula_ids' => 'array'
    ];

    public function funcionario()
    {
        return $this->belongsTo('App\Models\Funcionario', 'tbfuncionarios_id', 'tbfuncionarios_id');
    }

    public function responsavel()
    {
        return $this->belongsTo('App\Models\Funcionario', 'responsavel_id', 'tbfuncionarios_id');
    }

    public function sede()
    {
        return $this->belongsTo('App\Models\Escola', 'sede_id', 'tbescolas_id');
    }

    public function lotacao()
    {
        return $this->belongsTo('App\Models\Escola', 'lotacao_id', 'tbescolas_id');
    }
}
