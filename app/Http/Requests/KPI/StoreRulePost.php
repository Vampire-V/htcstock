<?php

namespace App\Http\Requests\KPI;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRulePost extends FormRequest
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
        $rule = [
            'category_id' => 'required',
            'name' => 'required|unique:kpi_rules|max:255',
            'user_actual' => 'required',
            'calculate_type' => 'required',
            'kpi_rule_types_id' => 'required',
            'quarter_cal' => 'required'
        ];
        if ($this->route('rule_list')) {
            $rule['name'] = ['required', Rule::unique('kpi_rules')];
        }
        return $rule;
    }
}
