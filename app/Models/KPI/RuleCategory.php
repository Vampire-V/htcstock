<?php

namespace App\Models\KPI;

use Illuminate\Database\Eloquent\Model;

class RuleCategory extends Model
{
    protected $table = 'kpi_rule_categories';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','description'
    ];

    public function rule()
    {
        return $this->hasMany(Rule::class,'category_id');
    }
}
