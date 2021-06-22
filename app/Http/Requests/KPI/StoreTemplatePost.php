<?php

namespace App\Http\Requests\KPI;

use Illuminate\Foundation\Http\FormRequest;

class StoreTemplatePost extends FormRequest
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
        $template = [
            'name' => 'required|unique:kpi_templates|max:255'
        ];
        if (strpos($this->path(),"dynamic")) {
            \array_splice($template, 1, 1);
        }
        
        return $template;
    }
}
