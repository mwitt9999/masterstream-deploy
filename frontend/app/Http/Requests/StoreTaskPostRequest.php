<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StoreTaskPostRequest extends FormRequest
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
    public function rules(Request $request)
    {
        $rules = [
            'name' => 'required',
            'command' => 'required',
            'output_message' => 'required',
        ];

        if($request->has('run_from_build_directory') && $request->input('run_from_build_directory') == 0) {
                $rules['command_directory'] = 'required';
        }

        if(!$request->has('run_from_build_directory')) {
            $rules['run_from_build_directory'] = 'required';
        }

        return $rules;
    }
}
