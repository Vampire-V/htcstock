<?php

namespace App\Models\KPI;

use App\Http\Filters\KPI\RuleFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RuleLog extends Model
{
    protected $table = 'kpi_rule_log';
    public $timestamps = true;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = \auth()->id();
            $model->ip = \request()->ip();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    // service เรียกใช้ Filter
    public function scopeFilter(Builder $builder, $request)
    {
        return (new RuleFilter($request))->filter($builder);
    }

}
