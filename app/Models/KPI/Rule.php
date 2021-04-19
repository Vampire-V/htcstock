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
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'name',
    //     'category_id',
    //     'description',
    //     'measurement',
    //     'target_unit_id',
    //     'calculate_type',
    //     'kpi_rule_types_id'
    // ];
    protected $guarded = [];

    // service เรียกใช้ Filter
    public function scopeFilter(Builder $builder, $request)
    {
        return (new RuleFilter($request))->filter($builder);
    }
}
