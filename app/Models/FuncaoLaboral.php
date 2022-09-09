<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FuncaoLaboral extends Model
{
    use SoftDeletes;

    protected $table = 'tbfuncoes_laborais';

    protected $primaryKey = 'tbfuncoes_laborais_id';

    public $timestamps = true;

    protected $guarded = [];
}
