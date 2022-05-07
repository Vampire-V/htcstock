<?php

namespace App\Http\Requests\KPI;

use App\Enum\UserEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDeptInCharge extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasRole(UserEnum::OPERATIONKPI);
    }

        /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'dept' => 'required'
        ];
    }
}
