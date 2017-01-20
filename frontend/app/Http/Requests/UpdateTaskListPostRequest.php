<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskListPostRequest extends FormRequest
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
        return ['task_id.xxx.required' => 'Please add at least one task to pipeline'];
    }

    public function rules()
    {
        $rules = [];

        if(!\Request::has('task_ids')) {
            $rules['task_id.xxx'] = 'required';
        }

        return $rules;
    }}
