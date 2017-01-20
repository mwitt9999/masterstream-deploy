<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class StoreBuildPostRequest extends FormRequest
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
        return ['commit_hash.required' => 'Please select a version', 'server_id.required' => 'Please select at least one server', 'pipeline_id.required' => 'Please choose a pipeline to use for your build', 'site_id.required'=> 'Choose a site to build'];
    }

    public function rules()
    {
        $rules = [
            'commit_hash' => 'required',
            'pipeline_id' => 'required',
            'site_id' => 'required',
        ];

        if(!$this->request->has('server_id')) {
            $rules['server_id'] = 'required';
        }

        return $rules;
    }
}
