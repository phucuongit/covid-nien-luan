<?php
   
namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
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
        $validator = Validator::make($request->all(), [
            'fullname' => 'required',
            'gender' => 'required',
            'username' => 'required',
            'password' => 'required|min:6|max:32',
            'c_password' => 'required|same:password',
            'role_id' => 'required|numeric|min:0'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(), 404);       
        }
        $input = $request->all();
        // Hash password
        $input['password'] = bcrypt($input['password']);
        try{
            $user = User::create($input);
            // Token for access
            $success['token'] =  $user->createToken('Acess token')->accessToken;
            $success['username'] =  $user->username;
        }
        catch (Exception $e) {
            return $this->sendError('Something went wrong', ['error' => $e->getMessage()]);
        }
   
        return $this->sendResponse($success, 'User register successfully.');
    }
}