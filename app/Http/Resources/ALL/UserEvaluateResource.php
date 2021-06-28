<?php

namespace App\Http\Resources\ALL;

use App\Http\Resources\KPI\EvaluateResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserEvaluateResource extends JsonResource
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
            'username' => $this->username,
            'evaluates' => EvaluateResource::collection($this->evaluates),
        ];
    }
}
