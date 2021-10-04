<?php
   
namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController;
use App\Http\Requests\UserRequest;
use App\Http\Resources\AuthResource;
use App\Models\User;
use App\Models\Role;
use App\Rules\Is_identity;
use App\Rules\Is_vnPhone;
use Validator;
use Exception;

class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        try{
            $message = [
                'required' => 'Yêu cầu nhập trường này',
                'unique' => 'Trường này đã tồn tại',
                'date' => 'Định dạng ngày không đúng',
                'numeric' => 'Trường này phải là kiểu số',
                'same' => 'Mật khẩu nhập lại chưa đúng',
                'social_insurance.size' => 'Mã bảo hiểm phải là 10 ký tự',
                'username.min' => 'Tên đăng nhập ít nhất 6 ký tự',
                'password.min' => 'Mật khẩu ít nhất 6 ký tự',
                'exists' => 'Trường này không tồn tại'
            ];
    
            $validator = Validator::make($request->all(), [
                'identity_card' => ['required', new Is_identity, 'unique:users'],
                'social_insurance' => ['required', 'string', 'size:10', 'unique:users'],
                'username' => 'required|string|min:6|unique:users',
                'password' => 'required|min:6',
                'fullname' => 'required|string',
                'birthday' => 'required|date',
                'gender' => ['required', Rule::in([0,1])],
                'address' => 'required|string',
                'phone' => ['required', new Is_vnPhone, 'unique:users'],
                'role_id' => 'required|exists:roles,id',
                'village_id' => 'required|exists:villages,id',
                'c_password' => 'required|same:password',
            ], $message);
       
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

            // Retrieve the validated input...
            $validated = $validator->validated();
    
            // Hash password
            $validated['password'] = bcrypt($validated['password']);

            $user = new AuthResource(User::create($validated));

            // Token for access
            $user['token'] =  $user->createToken('Acess token')->accessToken;

            return $this->sendResponse($user, 'User register successfully.');
        }
        catch (Exception $e) {
            return $this->sendError('Something went wrong', [$e->getMessage()]);
        }
    }
}