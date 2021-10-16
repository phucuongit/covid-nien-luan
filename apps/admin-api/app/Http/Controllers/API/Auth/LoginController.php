<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\AuthResource;
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
        try{
            if(Auth::attempt(['username' => $request->username, 'password' => $request->password])){
                $user = Auth::user();
                $loginData = new AuthResource($user);
                $loginData['token'] =  $user->createToken('Access token')->accessToken;
                return $this->sendResponse($loginData, 'User login successfully.');
            }
            else{
                return $this->sendError('Unauthorised.', 401);
            }
        }
        catch (Exception $e) {
            return $this->sendError('Something went wrong', [$e->getMessage()]);
        }
    }
}