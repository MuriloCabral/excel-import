<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table = 'tbcargos';

    protected $primaryKey = 'tbcargos_id';

    protected $guarded = [];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'tbcargos_id', 'tbcargos_id');
    }

    public function funcaoLaboral()
    {
        return $this->hasOne('App\Models\FuncaoLaboral', 'tbcargos_id', 'tbcargos_id');
    }
}
