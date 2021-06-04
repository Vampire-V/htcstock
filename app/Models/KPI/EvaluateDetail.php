<?php

namespace App\Models\KPI;

use App\Http\Filters\KPI\EvaluationDetailFilter;
use App\Relations\EvaluateDetailTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class EvaluateDetail extends Model
{
    use EvaluateDetailTrait;
    protected $table = 'kpi_evaluate_details';
    protected $casts = [
        'weight' => 'float',
        'weight_category' => 'float',
        'target' => 'float',
        'base_line' => 'float',
        'max_result' => 'float',
        'actual' => 'float',
        'amount' => 'float'
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'evaluate_id',
    //     'rule_id',
    //     'target',
    //     'actual',
    //     'weight',
    //     'weight_category',
    //     'base_line',
    //     'max_result'
    // ];

    protected $guarded = [];

    // service เรียกใช้ Filter
    public function scopeSetActualFilter(Builder $builder, $request)
    {
        return (new EvaluationDetailFilter($request))->filter($builder);
    }
}
