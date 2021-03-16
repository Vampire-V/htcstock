<?php

namespace App\Http\Resources\KPI;

use Illuminate\Http\Resources\Json\JsonResource;

class RuleResource extends JsonResource
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
            'category_id' => $this->category_id,
            'description' => $this->description,
            'measurement' => $this->measurement,
            'target_unit_id' => $this->target_unit_id,
            'calculate_type' => $this->calculate_type,

            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'categorys' => $this->category
        ];
    }
}
