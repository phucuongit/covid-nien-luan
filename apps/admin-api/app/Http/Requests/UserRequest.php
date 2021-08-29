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
            'identify_card' => 'required|numeric',
            'fullname' => 'required|string',
            'birthday' => 'required|date',
            'gender' => ['required', Rule::in([0,1])],
            // 'avatar' => 'required',
            // 'username' => 'required|min:6',
            // 'password' => 'required|min:6',
            'address' => 'required',
            'phone' => 'required',
            'role_id' => 'required|numeric',
        ];
    }
}
