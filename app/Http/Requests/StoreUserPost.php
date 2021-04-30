<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserPost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = User::find($this->route('user'));
        return $this->user()->username === $user->username;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'th.name' => 'required|max:255',
            'email' => 'required|email:rfc,dns',
            'phone' => 'required',
            'division' => 'required',
            'department' => 'required',
            'position' => 'required'
        ];

        foreach (\config('translatable.locales') as $locale) {
            $rules[$locale . '.name'] = 'string';
        }

        return $rules;
    }
}
