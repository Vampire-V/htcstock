<?php

namespace App\Http\Resources\KPI;

use Illuminate\Http\Resources\Json\JsonResource;

class EvaluateDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'evaluate_id' => $this->evaluate_id,
            'rule_id' => $this->rule_id,
            'target' => $this->target,
            'actual' => number_format($this->actual,2),
            'weight' => $this->weight,
            'weight_category' => $this->weight_category,
            'base_line' => $this->base_line,
            'max_result' => $this->max_result,

            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'rules' => new RuleResource($this->rule)
        ];
    }
}
