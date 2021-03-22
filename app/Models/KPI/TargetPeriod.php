<?php

namespace App\Models\KPI;

use App\Relations\TargetPeriodTrait;
use Illuminate\Database\Eloquent\Model;

class TargetPeriod extends Model
{
    use TargetPeriodTrait;
    protected $table = 'kpi_target_periods';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'year'
    ];
}
