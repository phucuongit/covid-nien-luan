<?php
   
namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Validator;
   
class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required',
            'gender' => 'required',
            'username' => 'required',
            'password' => 'required|min:6',
            'c_password' => 'required|same:password',
            'role_id' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(), 400);       
        }
        $input = $request->all();
        //Hash password
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        //Token for access
        $success['token'] =  $user->createToken('Acess token')->accessToken;
        $success['name'] =  $user->username;
   
        return $this->sendResponse($success, 'User register successfully.');
    }
}