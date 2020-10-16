<?php

namespace App\Http\Requests\Legal;

use Illuminate\Foundation\Http\FormRequest;

class StoreMould extends FormRequest
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
            'purchase_order' => 'required',
            'quotation' => 'required',
            'coparation_sheet' => 'required',
            'drawing' => 'required',

            'scope_of_work' => 'required',
            // 'to_manufacture' => 'required',
            // 'of' => 'required',
            'quotation_no' => 'required',
            'dated' => 'required',
            'delivery_date' => 'required',

            'comercial_term_id' => 'required',
            
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
            'purchase_order.required' => 'Please enter purchase_order',
            'coparation_sheet.required' => 'Please enter coparation_sheet',
            'quotation.required' => 'Please enter quotation',
            'drawing.required' => 'Please enter drawing',

            'scope_of_work.required' => 'Please enter scope_of_work',
            // 'to_manufacture.required' => 'Please enter to_manufacture',
            // 'of.required' => 'Please enter of',
            'quotation_no.required' => 'Please enter quotation_no',
            'dated.required' => 'Please enter dated',
            'delivery_date.required' => 'Please enter delivery_date',

            'comercial_term_id.required' => 'Please enter comercial_term_id',

            'payment_type_id.required' => 'Please enter payment_term',
            'value_of_contract.required' => 'Please enter value_of_contract',

            'warranty.required' => 'Please enter warranty',
        ];
    }
}
