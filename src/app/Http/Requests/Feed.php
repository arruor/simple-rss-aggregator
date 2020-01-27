<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Feed extends FormRequest
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
            'url' => 'required|unique:posts|min:15|max:255',
            'name' => 'required|min:3',
        ];
    }
}
