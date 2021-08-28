<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Result_testRequest extends FormRequest
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
            'status' => 
                ['required', Rule::in(['positive', 'negative'])],
            'user_id' => 
                'required|numeric|min:0',
            'create_by' => 
                'required|numeric|min:0',
        ];
    }
}
