<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseCurricular extends Model
{
    use SoftDeletes;

    protected $table = 'tbbase_curricular';

    protected $primaryKey = 'tbbase_curricular_id';

    protected $guarded = [];

    public function anoSeries()
    {
        return $this->belongsToMany(
            AnosSeries::class,
            'tbbase_curricular_series',
            'tbbase_curricular_id',
            'bncc_serie',
        );
    }
}
