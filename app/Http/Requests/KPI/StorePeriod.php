<?php

namespace App\Http\Requests\KPI;

use App\Enum\UserEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePeriod extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->roles->search(fn ($obj) => $obj->slug === UserEnum::ADMINKPI, $strict = true) !== \false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rule = [
            'name' => ['required', Rule::unique('kpi_target_periods')->where(fn ($query) => $query->where('name', $this->input('name'))->where('year', $this->input('year'))), 'max:255'],
            'year' => 'required|regex:/(^\d{4}$)/'
        ];
        return $rule;
    }
}
