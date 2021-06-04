<?php

namespace App\Http\Resources\KPI;

use Illuminate\Http\Resources\Json\JsonResource;

class RuleTemplateResource extends JsonResource
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
            'template_id' => $this->template_id,
            'rule_id' => $this->rule_id,
            'weight' => $this->weight,
            'weight_category' => $this->weight_category,
            'parent_rule_template_id' => $this->parent_rule_template_id,
            'field' => $this->field,
            'target_config' => $this->target_config,
            'base_line' => $this->base_line,
            'max_result' => $this->max_result,
            'amount' => $this->amount,

            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'templates' => new TemplateResource($this->template),
            'rules' => new RuleResource($this->rule)
        ];
    }
}
