<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\Is_identity;
use App\Rules\Is_vnPhone;

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
            'identity_card' => ['required', new Is_identity, 'unique:users'],
            'social_insurance' => ['required', 'string', 'size:15', 'unique:users'],
            'username' => 'string|min:6|unique:users',
            'password' => 'min:6',
            'fullname' => 'required|string',
            'birthday' => 'required|date',
            'gender' => ['required', Rule::in([0,1])],
            'address' => 'required|string',
            'phone' => ['required', new Is_vnPhone, 'unique:users'],
            'role_id' => 'required|exists:roles,id',
            'village_id' => 'required|exists:villages,id',
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
            'same' => 'Mật khẩu nhập lại chưa đúng',
            'username.min' => 'Tên đăng nhập ít nhất 6 ký tự',
            'password.min' => 'Mật khẩu ít nhất 6 ký tự',
            'exists' => 'Trường này không tồn tại',
            'social_insurance.size' => 'Mã bảo hiểm phải là 15 ký tự'
        ];
    }
}
