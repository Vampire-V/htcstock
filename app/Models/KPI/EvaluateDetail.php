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
        'actual' => 'float'
    ];

    protected $guarded = [];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = \auth()->id();
        });

        static::updating(function ($model) {
            $model->updated_by = \auth()->id();
        });
    }

    // service เรียกใช้ Filter
    public function scopeSetActualFilter(Builder $builder, $request)
    {
        return (new EvaluationDetailFilter($request))->filter($builder);
    }
}
