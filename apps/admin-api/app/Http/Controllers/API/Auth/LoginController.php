<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\LoginResource;
use App\Models\User;
use Validator;

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
}