<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pessoa extends Model
{
    use SoftDeletes;

    protected $table = 'tbpessoas';

    protected $hidden = ['foto', 'tbpessoas_foto'];

    protected $dates = ['tbpessoas_dataCadastro', 'dataNascimento'];

    protected $primaryKey = 'tbpessoas_id';

    public $timestamps = true;

    protected $guarded = []; //permitir incluir/editar todos os campos do BD

    protected $attributes = ['tbpessoas_quilombola' => 'N', 'tbpessoas_nacionalidade' => 'Brasileira'];

    public static $tipos_sexo = [
        'F' => 'Feminino',
        'M' => 'Masculino',
    ];

    //Pessoa
    // - Sexo
    // - Estado Civil
    // - Nome
    // - Naturalidade
    // - dataNasc
    // - email
    // - uf Nasc
    // - STATUS
    // - Mae
    // - Pai
    // - Nacionalidade
    //Documentos:
    // - CPF
    // - RG
    // - Data Expedicao
    // - UF RG
    // - Orgao Expedidor

    //Endereco
    // - Tipo Logradouro
    // - Complemento
    // - CEP
    // - Bairro
    // - Logradouro
    // - Cidade
    // - Numero
    // - UF
    // - Telefone
    // - Celular

    public function getDataNascimentoAttribute()
    {
        return $this->attributes['tbpessoas_dataNasc']
            ? Carbon::createFromFormat('d/m/Y', $this->attributes['tbpessoas_dataNasc'])
            : null;
    }

    public function detalhes()
    {
        return $this->hasOne('App\Models\Pessoa', 'tbpessoas_id', 'tbpessoas_id');
    }

    public function aluno()
    {
        return $this->hasOne('App\Models\Aluno', 'tbpessoas_id', 'tbpessoas_id');
    }

    public function funcionario()
    {
        return $this->hasOne('App\Models\Funcionario', 'tbpessoas_id', 'tbpessoas_id');
    }

    public function professor()
    {
        return $this->belongsTo('App\Models\Professor', 'tbpessoas_id', 'tbpessoas_id');
    }

    public function coordenador()
    {
        return $this->belongsTo('App\Models\Coordenador', 'tbpessoas_id', 'tbpessoas_id');
    }

    public function racas()
    {
        return $this->hasMany('App\Models\PessoaRaca', 'tbpessoas_racas_id', 'tbpessoas_racas_id');
    }

    public function estadoUF()
    {
        return $this->hasOne('App\Models\Estado', 'tbufs_id', 'tbpessoas_ufEnd');
    }

    public function estadoUFnascimento()
    {
        return $this->hasOne('App\Models\Estado', 'tbufs_id', 'tbpessoas_ufNasc');
    }

    public function paises()
    {
        return $this->hasOne('App\Models\Paises', 'tbpaises_id', 'tbpaises_id');
    }

    public function acessos()
    {
        return $this->hasMany('App\Models\Anitta\EventoAcesso', 'acessoUsuarioID', 'tbpessoas_acessoEscolarID');
    }
}
