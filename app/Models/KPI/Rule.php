<?php

namespace App\Models\KPI;

use App\Http\Filters\KPI\RuleFilter;
use App\Relations\RuleTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    use RuleTrait;
    protected $table = 'kpi_rules';
    public $timestamps = true;

    protected $casts = [
        'base_line' => 'float',
        'max' => 'float'
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
    public function scopeFilter(Builder $builder, $request)
    {
        return (new RuleFilter($request))->filter($builder);
    }
    
}
