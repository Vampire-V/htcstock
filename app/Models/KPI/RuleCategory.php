<?php

namespace App\Models\KPI;

use App\Relations\RuleCategoryTrait;
use Illuminate\Database\Eloquent\Model;

class RuleCategory extends Model
{
    use RuleCategoryTrait;
    protected $table = 'kpi_rule_categories';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'name','description'
    // ];
    protected $guarded = [];
}
