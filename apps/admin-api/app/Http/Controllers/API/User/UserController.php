<?php

namespace App\Http\Controllers\API\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Http\Requests\UserRequest;
use App\Models\Vaccination;
use App\Models\Test_result;
use App\Models\User;
use App\Models\Image;
use App\Rules\Is_identity;
use App\Rules\Is_vnPhone;

use Exception;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try{
            $inputs = $request->all();
            $userQuery = User::filter($inputs);
            $users = new UserCollection(
                $userQuery->paginate(20)->appends(request()->query())
            );
            return $this->sendResponse($users->response()->getData(true));
        }
        catch (Exception $e) {
            return $this->sendError('Something went wrong', [$e->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        try{
            // Validate
            $validated = $request->validated();
            // Hash password
            if (isset($validated['password']))
                $validated['password'] = bcrypt($validated['password']);
            // Create
            $userResult = new UserResource(User::create($validated));
            return $this->sendResponse($userResult, "Successfully");
        }
        catch (Exception $e) {
            return $this->sendError('Something went wrong', [$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        try{
            $userResult = new UserResource($user);
            return $this->sendResponse($userResult);
        }
        catch (Exception $e) {
            return $this->sendError('Something went wrong', [$e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(request $request, User $user)
    {
        try{
            $message = [
                'unique' => 'Trường này đã tồn tại',
                'date' => 'Định dạng ngày không đúng',
                'numeric' => 'Trường này phải là kiểu số',
                'same' => 'Mật khẩu nhập lại chưa đúng',
                'social_insurance.size' => 'Mã bảo hiểm phải là 10 ký tự',
                'username.min' => 'Tên đăng nhập ít nhất 6 ký tự',
                'password.min' => 'Mật khẩu ít nhất 6 ký tự',
                'exists' => 'Trường này không tồn tại'
            ];
    
            $validator = Validator::make($request->except(['username']), [
                'identity_card' => [new Is_identity, 'unique:users'],
                'social_insurance' => ['string', 'size:10', 'unique:users'],
                'username' => 'string|min:6|unique:users',
                'password' => 'min:6',
                'fullname' => 'string',
                'birthday' => 'date',
                'gender' => Rule::in([0,1]),
                'address' => 'string',
                'phone' => [new Is_vnPhone, 'unique:users'],
                'role_id' => 'exists:roles,id',
                'village_id' => 'exists:villages,id',
            ], $message);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

            // Retrieve the validated input...
            $validated = $validator->validated();
    
            // Hash password
            if (isset($validated['password']))
                $validated['password'] = bcrypt($validated['password']);
                
            $result = 
                $user->update($validated);
            return $this->sendResponse($result, "Successfully");
        }
        catch (Exception $e) {
            return $this->sendError('Something went wrong', [$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try{            
            // Delete user image's file
            $imageNames = $user->images()->get('name');
            foreach ($imageNames as $index => $row)
            {
                Storage::disk('public')->delete('images/'.$row['name']);
            }
            // Delete images in DB
            $imageResult = $user->images()->delete();
            // Delete user
            $userResult = $user->delete() && $imageResult;
            return $this->sendResponse($userResult, "Successfully");
        }
        catch (Exception $e) {
            return $this->sendError('Something went wrong', [$e->getMessage()]);
        }
    }
}
