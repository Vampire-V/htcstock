<?php

namespace App\Http\Resources\KPI;

use Illuminate\Http\Resources\Json\JsonResource;

class TemplateResource extends JsonResource
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
            'name' => $this->name,
            'department_id' => $this->department_id,
            'weight_kpi' => $this->weight_kpi,
            'weight_key_task' => $this->weight_key_task,
            'weight_omg' => $this->weight_omg,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'departments' => $this->department,
            'user_created' => $this->user,
            'ruleTemplate' => RuleTemplateResource::collection($this->ruleTemplate)
        ];
    }
}
