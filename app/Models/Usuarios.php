<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{
    protected $table = 'tbusuarios';

    protected $primaryKey = 'tbusuarios_id';

    protected $guarded = [];
    
    public $timestamps = false;

    public $fillable = ['tbusuarios_id','tbusuarios_login','tbusuarios_senha','tbusuarios_apelido','tbPerfil_id','tbusuarios_ativo','tbusuarios_tipo','tbusuarios_usa_rede_social','tbusuarios_token'];
}
