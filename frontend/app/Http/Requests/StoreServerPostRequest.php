<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServerPostRequest extends FormRequest
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

    public function messages()
    {
        return ['ip.required' => 'Please enter an IP Address', 'name.required' => 'Please enter a server name'];
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'ip'   => 'required',
        ];
    }
}
