<?php

namespace App\Http\Controllers\API\Result_test;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Requests\Result_testRequest;
use App\Http\Controllers\API\BaseController;
use App\Http\Resources\Result_testResource;
use App\Http\Resources\Result_testCollection;
use App\Models\Result_test;
use Exception;

class Result_testController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try{
            $params = $request->all();
            $result_testQuery = Result_test::filter($params);
            $result_tests = 
                new Result_testCollection(
                    $result_testQuery->paginate(20)->appends(request()->query())
                );
            return $this->sendResponse($result_tests
                                    ->response()->getData(true));
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
    public function store(Result_testRequest $request)
    {
        try{
            $validatedData = $request->validated();
            $result_testResult = new Result_testResource(Result_test::create($validatedData));
            return $this->sendResponse($result_testResult);
        }
        catch (Exception $e) {
            return $this->sendError('Something went wrong', [$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Result_test  $result_test
     * @return \Illuminate\Http\Response
     */
    public function show(Result_test $result_test)
    {
        try{
            $result_testResult = new Result_testResource($result_test);
            return $this->sendResponse($result_testResult);
        }
        catch (Exception $e) {
            return $this->sendError('Something went wrong', [$e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Result_test  $result_test
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Result_test $result_test)
    {
        try{
            $message = [
                'exists' => 'Trường này đã tồn tại',
                'in' => 'Trường này không hợp lệ',
            ];
    
            $validator = Validator::make(
                $request->all(), [
                'user_id' => 'exists:users,id',
                'create_by' => 'exists:users,id',
                'status' => Rule::in(['positive', 'negative']),
            ], $message);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

            // Retrieve the validated input...
            $validated = $validator->validated();
            
            $result_testResult = $result_test->update($validated);

            return $this->sendResponse($result_testResult, "Successfully");
        }
        catch (Exception $e) {
            return $this->sendError('Something went wrong', [$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Result_test  $result_test
     * @return \Illuminate\Http\Response
     */
    public function destroy(Result_test $result_test)
    {
        try{
            // Delete result_test image's file
            $imageNames = $result_test->images()->get('name');
            foreach ($imageNames as $index => $row)
            {
                Storage::disk('public')->delete('images/'.$row['name']);
            }
            // Delete images in DB
            if ($result_test->images)
                $imageResult = $result_test->images()->delete();
            
            $result_testResult = $result_test->delete();
            return $this->sendResponse($result_testResult);
        }
        catch (Exception $e) {
            return $this->sendError('Something went wrong', [$e->getMessage()]);
        }
    }
}
