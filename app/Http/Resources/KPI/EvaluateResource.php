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
            'current_level' => $this->current_level,
            'next_level' => $this->next_level,
            'total_weight_kpi' => $this->total_weight_kpi,
            'total_weight_key_task' => $this->total_weight_key_task,
            'total_weight_omg' => $this->total_weight_omg,
            'cal_kpi' => $this->cal_kpi,
            'cal_key_task' => $this->cal_key_task,
            'cal_omg' => $this->cal_omg,
            'kpi_reduce' => $this->kpi_reduce,
            'key_task_reduce' => $this->key_task_reduce,
            'omg_reduce' => $this->omg_reduce,
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
