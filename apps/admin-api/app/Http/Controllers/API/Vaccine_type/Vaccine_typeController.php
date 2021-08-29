<?php

namespace App\Http\Controllers\API\Vaccine_type;

use App\Http\Controllers\Controller;
use App\Models\Vaccine_type;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\Vaccine_type as Vaccine_typeResources;
use App\Http\Requests\Vaccine_typeRequest;
use Exception;

class Vaccine_typeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vaccine_typeResults = Vaccine_typeResources::collection(Vaccine_type::all());
        return $this->sendResponse($vaccine_typeResults);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Vaccine_typeRequest $request)
    {
        try{
            $validatedData = $request->validated();
            $Vaccine_typeResult = new Vaccine_typeResources(Vaccine_type::create($validatedData));
            return $this->sendResponse($Vaccine_typeResult);
        }
        catch (Exception $e) {
            return $this->sendError('Something went wrong', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vaccine_type  $vaccine_type
     * @return \Illuminate\Http\Response
     */
    public function show(Vaccine_type $vaccine_type)
    {
        try{
            $Vaccine_typeResult = new Vaccine_typeResources($vaccine_type);
            return $this->sendResponse($Vaccine_typeResult);
        }
        catch (Exception $e) {
            return $this->sendError('Something went wrong', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vaccine_type  $vaccine_type
     * @return \Illuminate\Http\Response
     */
    public function update(Vaccine_typeRequest $request, Vaccine_type $vaccine_type)
    {
        try{
            $validatedData = $request->validated();
            $Vaccine_typeResult = tap($vaccine_type)
                            ->update($validatedData);
            return $this->sendResponse($Vaccine_typeResult);
        }
        catch (Exception $e) {
            return $this->sendError('Something went wrong', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vaccine_type  $vaccine_type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vaccine_type $vaccine_type)
    {
        try{
            $Vaccine_typeResult = $vaccine_type
                            ->delete();
            return $this->sendResponse($vaccine_type);
        }
        catch (Exception $e) {
            return $this->sendError('Something went wrong', ['error' => $e->getMessage()]);
        }
    }
}
