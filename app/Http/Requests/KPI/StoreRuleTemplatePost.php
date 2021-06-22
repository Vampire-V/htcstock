<?php

namespace App\Http\Requests\KPI;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreRuleTemplatePost extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'messages' => $validator->errors()->all()
            ], 422)
        );
    }

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
            // 'template_id' => 'required',
            'rule_id' => 'required',
            // 'weight' => 'required',
            // 'weight_category' => 'required',
            // 'target_config' => 'required',
            // 'base_line' => 'required',
            // 'max_result' => 'required'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            // 'template_id.required' => 'A template is required',
            'rule_id.required' => 'A Rule Name is required',
            // 'weight' =>  'A weight is required',
            // 'weight_category' =>  'A Weight category is required',
            // 'target_config' =>  'A Target config is required',
            // 'base_line' =>  'A Base line is required',
            // 'max_result' =>  'A Max is required'
        ];
    }
}
