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

            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'departments' => $this->department
        ];
    }
}
