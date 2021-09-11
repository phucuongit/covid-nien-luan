<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
            'identity_card' => 'required|numeric',
            'social_insurance' => 'required',
            // 'username' => 'required|min:6',
            // 'password' => 'required|min:6',
            'fullname' => 'required|string',
            'birthday' => 'required|date',
            'gender' => ['required', Rule::in([0,1])],
            'address' => 'required',
            'phone' => 'required',
            'role_id' => 'required|numeric',
            'village_id' => 'required|numeric',
        ];
    }
}
