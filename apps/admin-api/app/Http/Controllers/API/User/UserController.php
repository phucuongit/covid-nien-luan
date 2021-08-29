<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Models\Vaccination;
use App\Models\Test_result;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\User as UserResources;
use App\Http\Requests\UserRequest;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userResults = UserResources::collection(User::all());
        return $this->sendResponse($userResults);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $validatedData = $request->validated();
        $userResult = new UserResources(User::create($validatedData));
        return $this->sendResponse($userResult);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $userResult = new UserResources($user);
        return $this->sendResponse($userResult);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        // $user = User::find($id);
        // $user->fullname = $request->get('fullname');
        // $user->username = $request->get('username');
        // $user->password = $request->get('password');
        // $user->identify_card = $request->get('identify_card');
        // $user->birthday = $request->get('birthday');
        // $user->gender = $request->get('gender');
        // $user->avatar = $request->get('avatar');
        // $user->address = $request->get('address');
        // $user->phone = $request->get('phone');
        // $user->role_id = $request->get('role_id');
        // $user->save();
        // return $user;
        $validatedData = $request->validated();
        $userResult = tap($user)
                        ->update($validatedData);
        return $this->sendResponse($userResult);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $userResult = $user
                        ->delete();
        return $this->sendResponse($user);
    }

    public function ViewProfile($username){
        return DB::select("select * from covid_nienluan.users where covid_nienluan.users.username like concat('%',?,'%')",[$username]);
    }

    public function UserTestResult($username){
        return DB::select ("select a.username,b.id,b.status,b.updated_at,b.user_id,b.create_by,b.created_at from covid_nienluan.users as a join covid_nienluan.result_tests as b on a.id = b.user_id where a.username like concat('%',?,'%');;",[$username]);
        
    }

    public function UserVaccina($username){
        return DB::select ("select a.username,b.id,b.create_by,b.created_at,b.updated_at,b.user_id,b.vaccine_type_id,c.country,c.name from covid_nienluan.users as a join covid_nienluan.vaccinations as b on a.id = b.user_id 
        join covid_nienluan.vaccine_types as c on b.vaccine_type_id = c.id
        where a.username like concat('%',?,'%');",[$username]);
        
    }
}
