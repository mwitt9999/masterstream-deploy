<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class StoreDeploymentPostRequest extends FormRequest
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
        return ['commit_hash.required' => 'Please select a version', 'server_id.required' => 'Please select at least one server'];
    }

    public function rules()
    {
        $rules = [
            'commit_hash' => 'required',
        ];

        if(!$this->request->has('ip')) {
            $rules['server_id'] = 'required';
        }else{
            foreach($this->request->get('server_id') as $key => $val)
            {
                $rules['server_id.'.$key] = 'required';
            }
        }

        return $rules;
    }
}
