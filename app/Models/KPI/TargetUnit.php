<?php

namespace App\Models\KPI;

use App\Relations\TargetUnitTrait;
use Illuminate\Database\Eloquent\Model;

class TargetUnit extends Model
{
    use TargetUnitTrait;
    protected $table = 'kpi_target_units';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];
}
