<?php

namespace App\Models\KPI;

use Illuminate\Database\Eloquent\Model;

class TargetPeriod extends Model
{
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

    /**
     * Get the TargetPeriod that owns the Evaluate.
     */
    public function evaluate()
    {
        return $this->hasOne(Evaluate::class,'period_id')->withDefault();
    }
}
