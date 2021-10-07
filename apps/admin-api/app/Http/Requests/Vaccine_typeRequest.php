<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Vaccine_typeRequest extends FormRequest
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
            'name' => 'required|string',
            'country' => 'required|string',
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
            'string' => 'Trường này phải là chuỗi',
            'required' => 'Yêu cầu nhập trường này'
        ];
    }
}
