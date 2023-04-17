<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseCurricularSeries extends Model
{
    use SoftDeletes;

    protected $table = 'tbbase_curricular';

    protected $primaryKey = 'tbbase_curricular_id';

    protected $guarded = [];
}
