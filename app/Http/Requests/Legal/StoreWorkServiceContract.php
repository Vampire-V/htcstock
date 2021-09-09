<?php

namespace App\Http\Requests\Legal;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreWorkServiceContract extends FormRequest
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
            'quotation' => 'required',
            'coparation_sheet' => 'required',
            'work_plan' => 'required',
            'scope_of_work' => 'required',
            'location' => 'required',
            'quotation_no' => 'required',
            'dated' => 'required',
            'contract_period' => 'required',
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
            'quotation.required' => 'Please enter Quotation',
            'coparation_sheet.required' => 'Please enter AEC/Coparation Sheet',
            'work_plan.required' => 'Please enter Work Plan',

            'scope_of_work.required' => 'Please enter Scope of Work',
            'location.required' => 'Please enter Location',
            'quotation_no.required' => 'Please enter Quotation No',
            'dated.required' => 'Please enter Dated',
            'contract_period.required' => 'Please enter  Contract period',
            // 'untill.required' => 'Please enter untill',

            'payment_type_id.required' => 'Please enter Payment Terms',
            'value_of_contract.required' => 'Please enter value_of_contract',

            'warranty.required' => 'Please enter Warranty',
        ];
    }
}
