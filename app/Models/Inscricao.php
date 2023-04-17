<?php

namespace App\Models;

use App\Models\Escola;
use App\Models\InscricaoEscola;
use App\Models\InscricaoRecusada;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

class Inscricao extends Model
{
    use SoftDeletes;

    protected $table = 'tbinscricoes';

    protected $primaryKey = 'tbinscricoes_id';

    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'tipo' => 'integer',
    ];

    const PORTAL_PROFESSOR      = 0;
    const PORTAL_COMUNIDADE     = 1;
    const PORTAL_ALUNO          = 2;

    const STATUS_RECUSADA               = 0;
    const STATUS_AGUARDANDO_VAGA        = 1;
    const STATUS_AGUARDANDO_RESPONSAVEL = 2;
    const STATUS_AGUARDANDO_MATRICULA   = 3;
    const STATUS_MATRICULA              = 4;
    const STATUS_APROVADA               = 5;

    const TIPO_NOVO = 1;
    const TIPO_TRANS= 2;

    const TIPO      = [
        self::TIPO_NOVO     => 'Aluno novo',
        self::TIPO_TRANS    => 'Transferência',
    ];

    const STATUS    = [
        self::STATUS_RECUSADA               => 'Recusada',
        self::STATUS_AGUARDANDO_VAGA        => 'Aguardando vaga disponível',
        self::STATUS_AGUARDANDO_RESPONSAVEL => 'Aguardando resposta do responsável',
        self::STATUS_AGUARDANDO_MATRICULA   => 'Aguardando matrícula',
        self::STATUS_MATRICULA              => 'Matriculado',
        self::STATUS_APROVADA               => 'Aprovada',
    ];

    const ORIGEM    = [
        self::PORTAL_PROFESSOR   => 'Portal do professor',
        self::PORTAL_COMUNIDADE  => 'Portal da comunidade',
        self::PORTAL_ALUNO       => 'Portal do aluno'
    ];

    public function aluno()
    {
        return $this->belongsTo('\App\Models\Aluno', 'tbcadastro_alunos_id', 'tbcadastro_alunos_id');
    }

    public function deficiencia()
    {
        return $this->belongsTo('\App\Models\AlunoDeficiencia', 'tbpessoas_deficiencias_id', 'tbpessoas_deficiencias_id');
    }

    public function escola()
    {
        return $this->belongsTo('\App\Models\Escola', 'escola_atual_id', 'tbescolas_id')->with('grupo');
    }

    public function escolasRequeridas()
    {
        return $this->hasMany(InscricaoEscola::class, 'tbinscricoes_id', 'tbinscricoes_id')->with(['escola.grupo', 'escola.detalhes']);
    }

    public function escolaRequerida1()
    {
        return $this->belongsTo('\App\Models\Escola', 'escola_requerida_1', 'tbescolas_id')->with(['grupo','detalhes']);
    }

    public function escolaRequerida2()
    {
        return $this->belongsTo('\App\Models\Escola', 'escola_requerida_2', 'tbescolas_id')->with(['grupo','detalhes']);
    }

    public function escolaRequerida3()
    {
        return $this->belongsTo('\App\Models\Escola', 'escola_requerida_3', 'tbescolas_id')->with(['grupo','detalhes']);
    }

    public function funcionario()
    {
        return $this->hasOne('\App\Models\Funcionario', 'tbfuncionarios_id', 'tbfuncionarios_id')->with('pessoa');
    }

    public function anexos()
    {
        return $this->hasMany('App\Models\AlunoAnexo', 'tbinscricoes_id', 'tbinscricoes_id')->select([
            'tbaluno_anexo_id',
            'tbinscricoes_id',
            //'tbaluno_anexo_file',
            'tbaluno_anexo_filename',
            'tbaluno_anexo_filetype',
            'tbaluno_anexo_tipo_documento',
        ]);
    }

    public function anosSeries()
    {
        return $this->hasOne('\App\Models\Serie', 'tbanos_series_id', 'serie');
    }

    public function setPosicaoNoGrid($v){
        $this->attributes['posicaoNoGrid'] = $v;
    }

    public function inscricaoObservacoes()
    {
        return $this->hasMany('\App\Models\InscricaoObservacao', 'tbinscricoes_id', 'tbinscricoes_id')->with('escola', 'anexos')->orderBy('tbinscricao_observacoes_id', 'desc');
    }

    public function solicitacaoAnexos()
    {
        return $this->hasMany('\App\Models\SolicitacaoAnexos', 'tbinscricoes_id', 'tbinscricoes_id')->select([
            'tbsolicitacao_anexos_id',
            'tbinscricoes_id',
            //'tbsolicitacao_anexos_file',
            'tbsolicitacao_anexos_filename',
            'tbsolicitacao_anexos_filetype'
        ]);
    }

    public function solicitacaoRecusada()
    {
        return $this->hasOne(InscricaoRecusada::class, 'tbinscricoes_id', 'tbinscricoes_id');
    }
}
