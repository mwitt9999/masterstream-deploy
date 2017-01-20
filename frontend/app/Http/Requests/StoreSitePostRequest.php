<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSitePostRequest extends FormRequest
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
        return ['name.required' => 'Please enter a server name', 'github_account_name.required' => 'Please enter a valid Github Account Name', 'github_repository_name.required' => 'Please enter a valid Github Repository Name'];
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'github_account_name' => 'required',
            'github_repository_name' => 'required'
        ];
    }
}
