<?php

namespace App\Models\KPI;

use App\Http\Filters\KPI\EvaluationFilter;
use App\Relations\EvaluateTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Evaluate extends Model
{
    use EvaluateTrait;
    protected $table = 'kpi_evaluates';
    protected $casts = [
        'user_id' => 'int',
        'period_id' => 'int',
        'head_id' => 'int',
        'template_id' => 'int',
        'current_level' => 'int',
        'next_level' => 'int',
        'total_weight_kpi' => 'float',
        'total_weight_key_task' => 'float',
        'total_weight_omg' => 'float',
        'cal_kpi' => 'float',
        'cal_key_task' => 'float',
        'cal_omg' => 'float',
        'kpi_reduce' => 'float',
        'key_task_reduce' => 'float',
        'omg_reduce' => 'float'
    ];

    protected $guarded = [];
    // protected $with = ['nextlevel','currentlevel'];

    // service เรียกใช้ Filter
    public function scopeFilter(Builder $builder, $request)
    {
        return (new EvaluationFilter($request))->filter($builder);
    }
}
