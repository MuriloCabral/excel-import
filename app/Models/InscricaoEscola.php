<?php

namespace App\Models;

use App\Inscricao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InscricaoEscola extends Model
{
    use SoftDeletes;

    protected $table = 'tbinscricoes_escolas';

    protected $primaryKey = 'tbinscricoes_escolas_id';

    protected $guarded = [];

    const OPCAO_1 = 1;
    const OPCAO_2 = 2;
    const OPCAO_3 = 3;

    const OPCAO = [
        self::OPCAO_1 => '1ª Opção',
        self::OPCAO_2 => '2ª Opção',
        self::OPCAO_3 => '3ª Opção'
    ];

    public function inscricao() {
        return $this->belongsTo(Inscricao::class, 'tbinscricoes_id', 'tbinscricoes_id');
    }

    public function escola() {
        return $this->belongsTo(Escola::class, 'tbescolas_id', 'tbescolas_id');
    }

}
