<?php

namespace App\Http\Controllers\API\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Resources\LoginResource;

class LoginController extends BaseController
{
     /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['username' => $request->username, 'password' => $request->password])){
            $user = Auth::user();
            $loginData = new LoginResource($user);
            $loginData['token'] =  $user->createToken('Access token')->accessToken;
            return $this->sendResponse($loginData, 'User login successfully.');
        }
        else{
            return $this->sendError('Unauthorised.', 401);
        }
    }

    public function test(){
        return response()->json([
            'name' => 'Abigail',
            'state' => 'CA',
        ]);
    }
}