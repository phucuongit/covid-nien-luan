<?php
   
namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController;
use App\Http\Requests\UserRequest;
use App\Http\Resources\LoginResource;
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
        $message = [
            'required' => 'Yêu cầu nhập trường này',
            'unique' => 'Trường này đã tồn tại',
            'date' => 'Định dạng ngày không đúng',
            'numeric' => 'Trường này phải là kiểu số',
            'same' => 'Mật khẩu nhập lại chưa đúng'
        ];

        $validator = Validator::make($request->all(), [
            'identity_card' => ['required', new Is_identity, 'unique:users'],
            'social_insurance' => ['required', 'string', 'unique:users'],
            'username' => 'min:6|string|unique:users',
            'password' => 'min:6',
            'fullname' => 'required|string',
            'birthday' => 'required|date',
            'gender' => ['required', Rule::in([0,1])],
            'address' => 'required|string',
            'phone' => ['required', new Is_vnPhone, 'unique:users'],
            'role_id' => 'required|numeric|min:0',
            'village_id' => 'required|numeric|min:0',
            'c_password' => 'required|same:password',
        ], $message);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(), 404);       
        }

        $input = $request->all();

        // Hash password
        $input['password'] = bcrypt($input['password']);

        try{
            $user = new LoginResource(User::create($input));
            // Token for access
            $user['token'] =  $user->createToken('Acess token')->accessToken;
            return $this->sendResponse($user, 'User register successfully.');
        }
        catch (Exception $e) {
            return $this->sendError('Something went wrong', [$e->getMessage()]);
        }
    }
}