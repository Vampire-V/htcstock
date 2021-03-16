<?php

namespace App\Models\KPI;

use Illuminate\Database\Eloquent\Model;

class EvaluateDetail extends Model
{
    protected $table = 'kpi_evaluate_details';
    protected $casts = [
        'weight' => 'float',
        'weight_category' => 'float',
        'target' => 'float',
        'base_line' => 'float',
        'max_result' => 'float',
        'actual' => 'float'
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'evaluate_id',
        'rule_id',
        'target',
        'actual',
        'weight',
        'weight_category',
        'base_line',
        'max_result'
    ];

    /**
     * Get the Evaluate that owns the EvaluateDetail.
     */
    public function evaluate()
    {
        return $this->belongsTo(Evaluate::class,'evaluate_id')->withDefault();
    }

        /**
     * Get the Evaluate that owns the Rule.
     */
    public function rule()
    {
        return $this->belongsTo(Rule::class,'rule_id')->withDefault();
    }
}
