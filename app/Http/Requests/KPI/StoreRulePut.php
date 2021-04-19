<?php

namespace App\Http\Requests\KPI;

use Illuminate\Foundation\Http\FormRequest;

class StoreRulePut extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_id' => 'required',
            'name' => 'required|max:255',
            // 'measurement' => 'required',
            'user_actual' => 'required',
            'calculate_type' => 'required',
            'kpi_rule_types_id' => 'required'
        ];
    }
}
