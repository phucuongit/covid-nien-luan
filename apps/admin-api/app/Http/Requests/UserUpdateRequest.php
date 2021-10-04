<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\Is_identity;
use App\Rules\Is_vnPhone;


class UserUpdateRequest extends FormRequest
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
            'identity_card' => 
                [new Is_identity, 'unique:users'],
            'social_insurance' => 
                ['string', 'unique:users'],
            'password' => 'min:6',
            'fullname' => 'string',
            'birthday' => 'date',
            'gender' => [Rule::in([0,1])],
            'address' => 'string',
            'phone' => 
                [new Is_vnPhone, 'unique:users'],
            'role_id' => 'numeric|min:0',
            'village_id' => 'numeric|min:0',
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
            'unique' => 'Trường này đã tồn tại',
            'date' => 'Định dạng ngày không đúng',
            'numeric' => 'Trường này phải là kiểu số',
        ];
    }
}
