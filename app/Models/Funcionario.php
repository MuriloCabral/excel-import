<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Funcionario extends Model
{
    use SoftDeletes;

    protected $table = 'tbfuncionarios';

    protected $primaryKey = 'tbfuncionarios_id';

    public $timestamps = false;

    protected $guarded = [];

    public static $status = ['Ativo', 'Inativo', 'Afastado', 'Licença', 'Licença Médica', 'Não Definido'];

    public function pessoa()
    {
        return $this->belongsTo('App\Models\Pessoa', 'tbpessoas_id', 'tbpessoas_id');
    }

    public function escola()
    {
        return $this->belongsTo('App\Models\Escola', 'tbescolas_id', 'tbescolas_id');
    }

    public function escolas()
    {
        return $this->belongsToMany(
            'App\Models\Escola',
            'tbcoordenador_acesso',
            'tbfuncionarios_id',
            'tbescolas_id',
            'tbfuncionarios_id',
            'tbescolas_id'
        )->withPivot('tbcoordenador_acesso_id', 'access_type_enum');
    }

    public function usuario()
    {
        return $this->belongsTo('App\Models\Usuario', 'tbusuarios_id', 'tbusuarios_id');
    }

    public function acessos()
    {
        return $this->hasMany('App\Models\Acesso', 'tbfuncionarios_id', 'tbfuncionarios_id');
    }

    public function getTurmasIdsAttribute()
    {
        return Arr::pluck($this->turmas, 'tbturmas_id');
    }

    public function disciplinas()
    {
        return $this->belongsToMany(
            'App\Models\Disciplina',
            'tbprofessores_classes',
            'tbfuncionarios_id',
            'tbdisciplinas_id'
        )
            ->withPivot(['tbfuncionarios_id', 'tbturmas_id', 'tbdisciplinas_id'])
            ->select('*', DB::raw('group_concat(tbprofessores_classes.tbturmas_id) as turmasIds'))
            ->groupBy(['tbprofessores_classes.tbfuncionarios_id', 'tbprofessores_classes.tbdisciplinas_id'])
            ->orderBy('tbdisciplinas_descricao');
    }

    public function atividades()
    {
        return $this->hasMany('App\Models\Atividade', 'tbfuncionarios_id', 'tbfuncionarios_id')->withCount([
            'participantes',
            'participantesVisualizaram',
            'participantesEntregaram',
        ]);
    }

    public function perfil()
    {
        return $this->hasOne('App\Models\Perfil', 'tbperfis_id', 'tbperfis_id')->select([
            'tbperfis.tbperfis_id',
            'tbperfis.tbperfis_descricao',
        ]);
    }

    public function consolidadosFrequencia()
    {
        return $this->hasMany('App\Models\FrequenciaFuncionarioConsolidado', 'tbfuncionarios_id', 'tbfuncionarios_id');
    }

    public function consolidadoAtual()
    {
        return $this->hasOne('App\Models\FrequenciaFuncionarioConsolidado', 'tbfuncionarios_id', 'tbfuncionarios_id')->where('ano', 2022);
    }

    public function vagasAtribuicao()
    {
        return $this->hasMany('App\Models\Atribuicao\AtribuicaoVaga', 'tbfuncionarios_id', 'tbfuncionarios_id');
    }

    public function getIsAdminAttribute()
    {
        return $this->tbperfis_id == 1;
    }

    public function getIsProfessorAttribute()
    {
        return $this->tbperfis_id == 11;
    }

    public function getIsCoordenacaoAttribute()
    {
        return $this->tbperfis_id == 9 || $this->tbperfis_id == 10 || $this->tbperfis_id == 12 || $this->tbperfis_id == 13 || $this->tbperfis_id == 19 || $this->tbperfis_id == 26 || $this->tbperfis_id == 27 || $this->tbPerfil_id == 36 || $this->tbperfis_id == 38 || $this->tbPerfil_id == 39 || $this->tbPerfil_id == 40 || $this->tbPerfil_id == 41 || $this->tbPerfil_id == 42 || $this->tbPerfil_id == 43;
    }

    public function getIsMediadorAttribute()
    {
        return $this->tbperfis_id == 31;
    }

    public function getIsAdministradorEscolarAttribute()
    {
        return $this->tbperfis_id == 32;
    }

    public function getIsProfessorEducaMaisAttribute()
    {
        return $this->tbperfis_id == 33;
    }

    public function aulasonline()
    {
        return $this->hasMany('App\Models\AulaOnline', 'tbfuncionarios_id', 'tbfuncionarios_id');
    }

    public function setores()
    {
        return $this->belongsToMany(
            'App\Models\VistoriaSetor',
            'tbvistoria_funcionario_setor',
            'tbfuncionarios_id',
            'tbvistoria_setores_id'
        );
    }

    public function getSetoresIdsAttribute()
    {
        return Arr::pluck($this->setores, 'tbvistoria_setores_id');
    }

    //Turmas dos Professores
    public function turmas()
    {
        return $this->belongsToMany('App\Models\Turma', 'tbprofessores_classes', 'tbfuncionarios_id', 'tbturmas_id')
            ->withPivot(['tbfuncionarios_id', 'tbturmas_id', 'tbdisciplinas_id'])
            ->join('tbdisciplinas as disciplinas', 'tbprofessores_classes.tbdisciplinas_id', '=', 'disciplinas.tbdisciplinas_id')
        //->select('*', DB::raw('group_concat(tbprofessores_classes.tbdisciplinas_id) as disciplinasIds'))
            ->groupBy(['tbprofessores_classes.tbturmas_id']);
    }

    //Turmas dos Gestores
    public function turmasGestores()
    {
        return $this->belongsToMany(
            'App\Models\Turma',
            'tbcoordenador_acesso',
            'tbfuncionarios_id',
            'tbescolas_id',
            'tbfuncionarios_id',
            'tbescolas_id'
        )
        ->join('tbanos_series', 'tbturmas.tbanos_series_id', 'tbanos_series.tbanos_series_id')
        ->join('tbmodalidades_ensino', 'tbanos_series.tbmodalidades_ensino_id', 'tbmodalidades_ensino.tbmodalidades_ensino_id')
        ->where(function ($q) {
            $q
                ->where('tbmodalidades_ensino.tbmodalidades_ensino_tipo', 'I')// se access_type_enum = 2 somente turmas de ensino infantil
                ->where('tbcoordenador_acesso.access_type_enum', 2)
                ->orWhere('tbcoordenador_acesso.access_type_enum', '<', 2);
        });
    }

    public function classes()
    {
        return $this->hasMany('App\Models\ProfessorClasse', 'tbfuncionarios_id', 'tbfuncionarios_id')->with('disciplina');
    }

    // public function cargos()
    // {
    //     return $this->hasOne('App\Models\Cargos', 'tbcargos_id', 'tbcargos_id');
    // }

    public function cargo()
    {
        return $this->hasOne('App\Models\Cargo', 'tbcargos_id', 'tbcargos_id');
    }

    public function motorista()
    {
        return $this->hasOne('App\Models\Motorista', 'tbfuncionarios_id', 'tbfuncionarios_id');
    }

    public function historico() //Histórico de Observações do funcionário
    {
        return $this->hasMany('App\Models\FuncionarioHistorico', 'tbfuncionarios_id', 'tbfuncionarios_id')->orderBy('created_at', 'DESC');
    }

    public function fichaPeb()
    {
        return $this->hasOne('App\Models\Atribuicao\AtribuicaoInscricaoPEB', 'tbfuncionarios_id', 'tbfuncionarios_id');
    }

    public function fichaAseAge()
    {
        return $this->hasOne('App\Models\Atribuicao\AtribuicaoInscricaoASEAGE', 'tbfuncionarios_id', 'tbfuncionarios_id');
    }

    public function fichaAe()
    {
        return $this->hasOne('App\Models\Atribuicao\AtribuicaoInscricaoAE', 'tbfuncionarios_id', 'tbfuncionarios_id');
    }

    public function funcaoLaboral()
    {
        return $this->hasOne('App\Models\FuncaoLaboral', 'tbfuncoes_laborais_id', 'tbfuncoes_laborais_id');
    }

    public function modulos()
    {
        return $this->belongsToMany(
            'App\Models\Modulos',
            'tbfuncionario_modulos',
            'tbfuncionarios_id',
            'tbmodulos_id'
        );
    }

    public function anexos()
    {
        return $this->hasMany('App\Models\FuncionarioAnexos', 'tbfuncionarios_id', 'tbfuncionarios_id');
    }
}
