<?php

namespace App\Models\KPI;

use App\Http\Filters\KPI\RuleFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    protected $table = 'kpi_rules';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'category_id',
        'description',
        'measurement',
        'target_unit_id',
        'calculate_type'
    ];

    // protected $hidden = ['category'];
    // add the rules attribute to the array
    // protected $appends = array('categorys');

    // code for $this->rule attribute
    // public function getCategorysAttribute($value)
    // {
    //     $category = null;
    //     if ($this->category) {
    //         $category = $this->category;
    //     }
    //     return $category;
    // }

    // override the toArray function (called by toJson)
    // public function toArray()
    // {
    //     return parent::toArray();
    // }


    public function category()
    {
        return $this->belongsTo(RuleCategory::class, 'category_id')->withDefault();
    }

    public function ruleTemplate()
    {
        return $this->hasOne(RuleTemplate::class)->withDefault();
    }

    public function targetUnit()
    {
        return $this->belongsTo(TargetUnit::class, 'target_unit_id')->withDefault();
    }

    public function evaluateDetail()
    {
        return $this->hasOne(EvaluateDetail::class)->withDefault();
    }

    // service เรียกใช้ Filter
    public function scopeFilter(Builder $builder, $request)
    {
        return (new RuleFilter($request))->filter($builder);
    }
}
