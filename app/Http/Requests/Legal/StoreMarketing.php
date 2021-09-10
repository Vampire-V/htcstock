<?php

namespace App\Http\Requests\Legal;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreMarketing extends FormRequest
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
            
            'purpose' => 'required',
            'promote_a_product' => 'required',
            'purchase_order_no' => 'required',
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

            'purpose.required' => 'Please enter purpose',
            'promote_a_product.required' => 'Please enter promote_a_product',
            'purchase_order_no.required' => 'Please enter purchase_order_no',
            'quotation_no.required' => 'Please enter quotation_no',
            'dated.required' => 'Please enter dated',
            'contract_period.required' => 'Please enter contract_period',
            
            'detail_payment_term.required' => 'Please enter payment_term',

        ];
    }
}
