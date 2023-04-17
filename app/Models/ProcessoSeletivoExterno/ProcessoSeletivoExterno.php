<?php

namespace App\Models\ProcessoSeletivoExterno;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcessoSeletivoExterno extends Model
{
    use SoftDeletes;

    const STATUS_AGUARDANDO_CONFERENCIA = 0;

    const STATUS_INSCRICAO_INDEFERIDA = 1;

    const STATUS_INSCRICAO_DEFERIDA = 2;

    const STATUS = [
        self::STATUS_AGUARDANDO_CONFERENCIA => 'Aguardando Conferência',
        self::STATUS_INSCRICAO_INDEFERIDA   => 'Inscrição Indeferida',
        self::STATUS_INSCRICAO_DEFERIDA     => 'Inscrição Deferida',
    ];

    protected $table = 'processo_seletivo_externo';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    protected $casts = [
        'afrodescendente' => 'boolean',
        'pcd'             => 'boolean',
        'ciente'          => 'boolean',
    ];

    protected static function booted()
    {
        static::addGlobalScope('atual', fn ($query) => $query->where('ano', 2023)); //date('Y')
        static::addGlobalScope('laudo_file', fn ($query) => $query->select([
            'id',
            'uuid',
            'nome',
            'data_nascimento',
            'campo_atuacao',
            'cpf',
            'n_filhos',
            'rg',
            'rg_orgao_emissor',
            'rg_uf_orgao_emissor',
            'telefone',
            'endereco',
            'numero',
            'complemento',
            'cep',
            'bairro',
            'cidade',
            'uf',
            'estado_civil',
            'pcd',
            'afrodescendente',
            'status',
            //'laudo_file',
            'laudo_filename',
            'laudo_filetype',
            'deleted_at',
            'created_at',
            'updated_at',
            'email',
            'funcionario_id_rh',
            'recurso',
            'parecer',
            'ciente',
            'recurso_status',
            'recurso_gabarito',
            'parecer_recurso_gabarito',
            'ciente_recurso_gabarito',
            'recurso_gabarito_status',
            'processo_seletivo_externo_prova_nota',
            'processo_seletivo_externo_prova_status',
            'recurso_classificao_preliminar',
            'parecer_recurso_classificao_preliminar',
            'ciente_recurso_classificao_preliminar',
            'recurso_classificao_preliminar_status',
            'ano',
            'nome_procurador',
            'rg_procurador',
            'remote_path',
        ]));
    }

    public function certificados()
    {
        return $this->hasMany(ProcessoSeletivoExternoCertificado::class, 'processo_seletivo_externo_id');
    }

    public function todasInscricoes()
    {
        return $this->hasMany(self::class, 'cpf', 'cpf')
            ->select(['id', 'cpf', 'campo_atuacao']);
    }
}
