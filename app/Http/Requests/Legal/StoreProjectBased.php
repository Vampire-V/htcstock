<?php

namespace App\Http\Requests\Legal;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreProjectBased extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create-legal-contract');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sub_type_contract_id' => 'required',
            'quotation' => 'required',
            'coparation_sheet' => 'required',

            'scope_of_work' => 'required',
            'location' => 'required',
            // 'purchase_order_no' => 'required',
            'quotation_no' => 'required',
            'dated' => 'required',
            'contract_period' => 'required',

            'detail_payment_term' => 'required',

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
            'sub_type_contract_id.required' => 'Please enter subtype',
            'quotation.required' => 'Please enter quotation',
            'coparation_sheet.required' => 'Please enter coparation_sheet',

            'scope_of_work.required' => 'Please enter scope_of_work',
            'location.required' => 'Please enter location',
            // 'purchase_order_no.required' => 'Please enter purchase_order_no',
            'quotation_no.required' => 'Please enter quotation_no',
            'dated.required' => 'Please enter dated',
            'contract_period.required' => 'Please enter contract_period',
            
            'detail_payment_term.required' => 'Please enter payment_term',

        ];
    }
}
