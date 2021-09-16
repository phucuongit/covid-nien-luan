<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\Is_identity;

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
            'identity_card' => ['required', new Is_identity],
            'social_insurance' => 'required|string',
            'username' => 'min:6',
            'password' => 'min:6',
            'fullname' => 'required|string',
            'birthday' => 'required|date',
            'gender' => ['required', Rule::in([0,1])],
            'address' => 'required|string',
            'phone' => 'required',
            'role_id' => 'required|numeric|min:0',
            'village_id' => 'required|numeric|min:0',
        ];
    }
}
