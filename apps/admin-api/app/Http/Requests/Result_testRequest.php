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
                'required|exists:users,id',
            'create_by' => 
                'required|exists:users,id',
        ];
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required' => 'Yêu cầu nhập trường này',
            'exists' => 'Trường này không tồn tại'
        ];
    }
}
