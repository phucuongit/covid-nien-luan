<?php

namespace App\Http\Controllers\API\AdminProfile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\UserResource as UserResource;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Validator;
use App\Rules\Is_identity;
use Illuminate\Validation\Rule;

class AdminProfileController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = new UserResource(Auth::user());
        return $this->sendResponse($user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try{
            $validator = Validator::make(
                $request->except(['username']), [
                    'identity_card' => 
                        ['numeric', new Is_identity],
                    'social_insurance' => 'string',
                    'password' => 'min:6',
                    'fullname' => 'string',
                    'birthday' => 'date',
                    'gender' => [Rule::in([0,1])],
                    'address' => 'string',
                    'phone' => 'string',
                    'role_id' => 'numeric|min:0',
                    'village_id' => 'numeric|min:0',
            ]);
            $validated = $validator->validated();
            // Hash password
            if (isset($validated['password']))
                $validated['password'] = bcrypt($validated['password']);
            $result =
                Auth::user()->update($validated);
            return $this->sendResponse($result, "Successfully");
        }
        catch (Exception $e) {
            return $this->sendError('Something went wrong', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        try{
            $userResult = Auth::user()->delete();
            return $this->sendResponse($userResult, "Successfully");
        }
        catch (Exception $e) {
            return $this->sendError('Something went wrong', ['error' => $e->getMessage()]);
        }
    }
}
