<?php

namespace App\Http\Requests\Legal;

use App\Models\Legal\LegalSubtypeContract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreLeaseContract extends FormRequest
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
        $rules = [
            'sub_type_contract_id' => 'required',
            'quotation' => 'required',
            'coparation_sheet' => 'required',
            'insurance_policy' => 'required',
            'cer_of_ownership' => 'required',

            'scope_of_work' => 'required',
            'location' => 'required',
            // 'purchase_order_no' => 'required',
            'quotation_no' => 'required',
            'dated' => 'required',
            'contract_period' => 'required',

            'payment_type_id' => 'required',

        ];
        $subtype = LegalSubtypeContract::find($this->request->get('sub_type_contract_id'));
        
        if (!\collect(['wh-contract','st-contract'])->contains($subtype->slug)) {
            $rules["insurance_policy"] = '';
            $rules["cer_of_ownership"] = '';
        }
        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'sub_type_contract_id.required' => 'Please enter Sub-type of Contract',
            'quotation.required' => 'Please enter Quotation ',
            'coparation_sheet.required' => 'Please enter Coparation Sheet',
            'insurance_policy.required' => 'Please enter Insurance Policy',
            'cer_of_ownership.required' => 'Please enter Certificate Of Ownership',

            'scope_of_work.required' => 'Please enter Scope of Work',
            'location.required' => 'Please enter location',
            // 'purchase_order_no.required' => 'Please enter Purchase Order No.',
            'quotation_no.required' => 'Please enter Quotation no',
            'dated.required' => 'Please enter dated',
            'contract_period.required' => 'Please enter Contract period',
            
            'payment_type_id.required' => 'Please enter Payment Terms',

        ];
    }
}
