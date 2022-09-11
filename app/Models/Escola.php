<?php

namespace App\Models;

use App\Models\Audiencia\AudienciaEquipamento;
use App\Models\Audiencia\EventoAcesso;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Escola extends Model
{
    // use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    protected static function booted()
    {
        static::addGlobalScope('ordemAlfabetica', function ($builder) {
            $builder->orderBy('tbescolas_descricao');
        });
        static::addGlobalScope('ativa', function ($builder) {
            $builder->where('tbescolas_ativo', 'S');
        });
    }

    // protected static function booted() {
    //     static::addGlobalScope('essencial', function (Builder $builder) {
    //         $builder->select([
    //             'tbescolas_id',
    //             'tbescolas_descricao',
    //             // 'tbescolas_endereco',
    //             // 'tbescolas_telefone',
    //             // 'tbescolas_endereconumero',
    //             // 'tbescolas_cidade',
    //             // 'tbescolas_bairro',
    //             // 'tbescolas_cep',
    //             // 'tbescolas_email',
    //             // 'tbescolas_ativo',
    //             // 'tbescolas_cnpj',
    //             // 'tbescolas_latitude',
    //             // 'tbescolas_longitude',
    //             // 'tbescolas_secretaria',
    //             // 'tbescolas_seguimento',
    //             // 'tbescolas_hora_aberto',
    //             // 'tbescolas_hora_fechado',
    //             // 'tbescolas_datacadastro',
    //             // 'tbescolas_historia',
    //             // 'tbescolas_arquivo_historia',
    //             //'tbescolas_logo',
    //             //'tbescolas_imagem_fachada',
    //             // 'tbufs_id',
    //             'tbfuncionarios_id',
    //             // 'tbescolas_cod_prodesp',
    //             //'imagem_fachada',
    //             //'imagem_logo',
    //             //'arquivo',
    //         ]);
    //     });
    // }

    protected $table = 'tbescolas';

    protected $primaryKey = 'tbescolas_id';

    protected $guarded = [];

    public $timestamps = false;

    protected $attributes = [
        'tbufs_id'        => 26, //SP
        'tbescolas_ativo' => 'S', //ATIVO
    ];

    public static $setores = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

    protected $hidden = ['tbescolas_logo', 'tbescolas_imagem_fachada', 'imagem_fachada', 'imagem_logo', 'arquivo'];

    public function alunosComTransporte()
    {
        return $this->alunos()->select([
            'tbcadastro_alunos.tbcadastro_alunos_id',
            'tbcadastro_alunos.tbpessoas_id',
            'tbcadastro_alunos.tbcadastro_alunos_ra',
            'tbcadastro_alunos.tbcadastro_alunos_transporteEscolar',
            'tbcadastro_alunos.tbcadastro_alunos_deficiencia',
            'tbcadastro_alunos.tbcadastro_alunos_cuidadosespeciais',
            'tbcadastro_alunos.tbcadastro_alunos_mobilidade_reduzida',
            'tbcadastro_alunos.tbcadastro_alunos_mobilidade_reduzida_permanente',
        ])->where('tbcadastro_alunos.tbcadastro_alunos_transporteEscolar', 'S')->with([
            'pessoa' => function ($q) {
                return $q->select([
                    'tbpessoas.tbpessoas_id',
                    'tbpessoas.tbpessoas_nome',
                    'tbpessoas.tbpessoas_enderecolatitude',
                    'tbpessoas.tbpessoas_enderecolongitude',
                ]);
            },
        ]);
    }

    public function publicacoesCardapios()
    {
        return $this->hasMany('App\Models\CardapioPublicacao', 'tbescolas_id', 'tbescolas_id')->with(['cardapio']);
    }

    public function detalhes()
    {
        return $this->hasOne(self::class, 'tbescolas_id', 'tbescolas_id')->withoutGlobalScope('essencial')->select([
            'tbescolas_id',
            'tbescolas_descricao',
            'tbescolas_descricao_prodesp',
            'tbescolas_endereco',
            'tbescolas_telefone',
            'tbescolas_endereconumero',
            'tbescolas_enderecocomplemento',
            'tbescolas_cidade',
            'tbescolas_bairro',
            'tbescolas_cep',
            'tbescolas_email',
            'tbescolas_ativo',
            'tbescolas_cnpj',
            'tbescolas_latitude',
            'tbescolas_longitude',
            'tbescolas_secretaria',
            'tbescolas_seguimento',
            'tbescolas_hora_aberto',
            'tbescolas_hora_fechado',
            'tbescolas_datacadastro',
            'tbescolas_historia',
            'tbescolas_arquivo_historia',
            'tbescolas_cod_prodesp',
            'tbfuncionarios_id',
            'tbufs_id',
        ]);
    }

    public function salasDeAula()
    {
        return $this->hasMany('App\Models\BigDataEscolas', 'tbescolas_id', 'tbescolas_id');
    }

    public function turmas()
    {
        return $this->hasMany(Turma::class, 'tbescolas_id', 'tbescolas_id')
            ->select(['tbturmas.tbturmas_id', 'tbturmas.tbturmas_descricao', 'tbturmas.tbescolas_id', 'tbturmas.tbturmas_codigo', 'tbturmas.tbanos_series_id'])
            ->orderBy('tbturmas_descricao', 'ASC');
    }

    public function turmasRegulares()
    {
        return $this->turmas()->where('tbturmas.tbturmas_reforco', 'N');
    }

    public function turmasReforco()
    {
        return $this->turmas()->where('tbturmas.tbturmas_reforco', 'S');
    }

    public function turmasDetalhes()
    {
        return $this->turmas()->select()->with(['serie', 'periodo']);
    }

    public function funcionarios()
    {
        return $this->hasMany('App\Models\Funcionario', 'tbescolas_id', 'tbescolas_id')
            ->select([
                'tbfuncionarios.tbescolas_id',
                'tbfuncionarios.tbfuncionarios_id',
                'tbfuncionarios.tbfuncionarios_matricula',
                'tbfuncionarios.tbpessoas_id',
                'tbfuncionarios.tbperfis_id',
            ])
            ->whereNotIn('tbfuncionarios.tbperfis_id', [
                11,                 //Professor
                12, 13, 19, 26, 29, //Coordenador
            ])
            ->with(['pessoa', 'perfil']);
    }

    public function coordenadores()
    {
        return $this->belongsToMany(
            'App\Models\Coordenador',
            'tbcoordenador_acesso',
            'tbescolas_id',
            'tbfuncionarios_id',
            'tbescolas_id',
            'tbfuncionarios_id'
        )->with([
            'pessoa' => function ($q) {
                return $q->select(['tbpessoas.tbpessoas_id', 'tbpessoas.tbpessoas_nome']);
            },
            'perfil',
        ]);
    }

    // public function professores()
    // {
    //     return $this->hasManyDeepFromRelations($this->turmas(), (new Turma)->professores())

    //         //return $this->hasMany('App\Models\Professor', 'tbescolas_id', 'tbescolas_id')
    //         ->select([
    //             'tbfuncionarios.tbescolas_id',
    //             'tbfuncionarios.tbfuncionarios_id',
    //             'tbfuncionarios.tbfuncionarios_matricula',
    //             'tbfuncionarios.tbfuncionarios_status',
    //             'tbfuncionarios.tbpessoas_id',
    //             'tbfuncionarios.tbperfis_id',
    //         ])
    //         ->where('tbfuncionarios.tbperfis_id', 11)
    //         ->distinct()
    //         ->with([
    //             'pessoa' => function ($q) {
    //                 return $q->select(['tbpessoas.tbpessoas_id', 'tbpessoas.tbpessoas_nome']);
    //             },
    //         ]);
    // }

    public function diretor()
    {
        return $this->hasOne(Funcionario::class, 'tbescolas_id', 'tbescolas_id')
            ->where('tbperfis_id', 19)
            ->with(['pessoa']);
    }

    // public function professores()
    // {
    //     return $this->hasMany('App\Models\Professor', 'tbescolas_id', 'tbescolas_id')
    //         ->select([
    //             'tbfuncionarios.tbescolas_id',
    //             'tbfuncionarios.tbfuncionarios_id',
    //             'tbfuncionarios.tbfuncionarios_matricula',
    //             'tbfuncionarios.tbpessoas_id',
    //             'tbfuncionarios.tbperfis_id',
    //         ])
    //         ->with([
    //             'pessoa' => function ($q) {
    //                 return $q->select(['tbpessoas.tbpessoas_id', 'tbpessoas.tbpessoas_nome']);
    //             },
    //         ]);
    // }

    // public function alunos()
    // {
    //     return $this->hasManyDeepFromRelations($this->turmas(), (new Turma)->alunos())
    //         ->where('tbturmas.tbturmas_ativo', 'S')
    //         ->where('tbalunos_localizacao.tbalunos_localizacao_ativa', true)
    //         ->select([
    //             'tbcadastro_alunos.tbcadastro_alunos_id',
    //             'tbcadastro_alunos.tbcadastro_alunos_ra',
    //             'tbcadastro_alunos.tbpessoas_id',
    //         ])->with([
    //             'pessoa' => function ($q) {
    //                 return $q->select(['tbpessoas.tbpessoas_id', 'tbpessoas.tbpessoas_nome']);
    //             },
    //         ]);
    // }

    public function respostasEnqueteCovid()
    {
        return $this->hasManyThrough(
            'App\Models\EnqueteCovid',
            'App\Models\Turma',
            'tbescolas_id',
            'tbturmas_id',
            'tbescolas_id',
            'tbturmas_id'
        );
    }

    public function setor()
    {
        return $this->hasOne('App\Models\EscolaSetor', 'tbescolas_setores_id', 'tbescolas_setores_id');
    }

    public function grupo()
    {
        return $this->hasOne('App\Models\EscolaGrupo', 'tbgrupos_escolares_id', 'tbgrupos_escolares_id')->select([
            'tbgrupos_escolares_id',
            'tbgrupos_escolares_nome',
        ]);
    }

    public function responsavel()
    {
        return $this->hasOne('App\Models\Funcionario', 'tbfuncionarios_id', 'tbfuncionarios_id');
    }

    public function leitoresFaciais()
    {
        return $this->hasMany(AudienciaEquipamento::class, 'tbescolas_id', 'tbescolas_id');
    }

    public function acessoEventos()
    {
        return $this->hasManyThrough(
            EventoAcesso::class,
            AudienciaEquipamento::class,
            'tbescolas_id',
            'leitorID',
            'tbescolas_id',
            'leitorID'
        );
    }

    public function series()
    {
        return $this->hasManyThrough(
            'App\Models\Serie',
            'App\Models\Turma',
            'tbescolas_id',
            'tbanos_series_id',
            'tbescolas_id',
            'tbanos_series_id',
        )->distinct();
    }

    public function escolaInscricoes()
    {
        return $this->hasMany('App\Inscricao', 'escola_requerida_1', 'tbescolas_id');
    }

    // Para testes de importação
    // public function turmas()
    // {
    //     return $this->hasMany(TurmasImportadas::class, 'tbescolas_id', 'tbescolas_id')
    //         ->select(['tbturmasImportadas.tbturmas_id', 'tbturmasImportadas.tbturmas_descricao', 'tbturmasImportadas.tbescolas_id', 'tbturmasImportadas.tbturmas_codigo', 'tbturmasImportadas.tbanos_series_id'])
    //         ->where('tbturmasImportadas.tbturmas_reforco', 'N')
    //         ->orderBy('tbturmas_descricao', 'ASC');
    // }

    public function anexos()
    {
        return $this->hasMany('App\Models\EscolaAnexos', 'tbescolas_id', 'tbescolas_id');
    }
}
