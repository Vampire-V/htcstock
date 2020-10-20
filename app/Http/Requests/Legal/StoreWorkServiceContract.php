<?php

namespace App\Http\Requests\Legal;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkServiceContract extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'quotation' => 'required',
            'coparation_sheet' => 'required',
            'work_plan' => 'required',

            'scope_of_work' => 'required',
            'location' => 'required',
            'quotation_no' => 'required',
            'dated' => 'required',
            'contract_period' => 'required',
            'untill' => 'required',

            // 'comercial_term_id' => 'required',

            'payment_type_id' => 'required',
            'value_of_contract' => 'required',

            'warranty' => 'required',
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
            'quotation.required' => 'Please enter quotation',
            'coparation_sheet.required' => 'Please enter coparation_sheet',
            'work_plan.required' => 'Please enter work_plan',

            'scope_of_work.required' => 'Please enter scope_of_work',
            'location.required' => 'Please enter location',
            // 'purchase_order_no.required' => 'Please enter purchase_order_no',
            'quotation_no.required' => 'Please enter quotation_no',
            'dated.required' => 'Please enter dated',
            'contract_period.required' => 'Please enter contract_period',
            'untill.required' => 'Please enter untill',

            'comercial_term_id.required' => 'Please enter comercial_term_id',

            'payment_type_id.required' => 'Please enter payment_term',
            'value_of_contract.required' => 'Please enter value_of_contract',

            'warranty.required' => 'Please enter warranty',
        ];
    }
}
