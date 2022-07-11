<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BnccSeries extends Model
{
    use HasFactory;

    protected $table = 'bncc_series';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function bncc()
    {
        return $this->belongsTo(Bncc::class, 'bncc_id', 'id');
    }

    public function serie()
    {
        return $this->belongsTo(Serie::class, 'serie_id', 'tbanos_series_id');
    }
}
