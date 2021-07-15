<?php

namespace App\Http\Resources\KPI;

use Illuminate\Http\Resources\Json\JsonResource;

class EvaluateResource extends JsonResource
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
            'user_id' => $this->user_id,
            'period_id'=> $this->period_id,
            'head_id' => $this->head_id,
            'status' => $this->status,
            'template_id' => $this->template_id,
            'current_level' => new UserApproveResource($this->currentlevel),
            'next_level' => new UserApproveResource($this->nextlevel),
            // 'main_rule_condition_1_min' => $this->main_rule_condition_1_min,
            // 'main_rule_condition_1_max' => $this->main_rule_condition_1_max,
            // 'main_rule_condition_2_min' => $this->main_rule_condition_2_min,
            // 'main_rule_condition_2_max' => $this->main_rule_condition_2_max,
            // 'main_rule_condition_3_min' => $this->main_rule_condition_3_min,
            // 'main_rule_condition_3_max' => $this->main_rule_condition_3_max,
            'total_weight_kpi' => $this->total_weight_kpi,
            'total_weight_key_task' => $this->total_weight_key_task,
            'total_weight_omg' => $this->total_weight_omg,
            'cal_kpi' => $this->cal_kpi,
            'cal_key_task' => $this->cal_key_task,
            'cal_omg' => $this->cal_omg,
            'comment' => $this->comment,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'user' => $this->user,
            'period' => $this->targetperiod,
            'template' => $this->template,
            'detail' => EvaluateDetailResource::collection($this->evaluateDetail)
        ];
    }
}
