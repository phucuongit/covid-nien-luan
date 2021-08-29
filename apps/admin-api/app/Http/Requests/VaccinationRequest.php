<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VaccinationRequest extends FormRequest
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
            'user_id' => 'required|numeric|min:0', 
            'create_by' => 'required|numeric|min:0', 
            'vaccine_type_id' => 'required|numeric|min:0',
        ];
    }
    // public function messages()
    // {
    //     return [
    //         'success.attribute' => 'false',
    //     ];
    // }
}
