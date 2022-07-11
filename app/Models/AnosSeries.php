<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnosSeries extends Model
{
    use HasFactory;

    protected $table = 'tbanos_series';
    protected $primaryKey = 'tbanos_series_id';
    protected $guarded = [];
}
