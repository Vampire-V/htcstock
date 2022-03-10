<?php

namespace App\Http\Requests\Legal;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreContractRequest extends FormRequest
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
            'action_id' => 'required',
            'agreement_id' => 'required',
            'company_name' => 'required',
            'company_cer' => 'required',
            'representative' => 'required',
            'representative_cer' => 'required',
            'address' => 'required',
            'priority' => 'required'
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
            'action_id.required' => 'Please select action',
            'agreement_id.required' => 'Please select agreement',
            'company_name.required' => 'Please enter company name',
            'company_cer.required' => 'Please enter company file',
            'representative.required' => 'Please enter representative',
            'representative_cer.required' => 'Please enter representative file',
            'address.required' => 'Please enter address',
            'priority.required' => 'Please select priority'
        ];
    }
}
