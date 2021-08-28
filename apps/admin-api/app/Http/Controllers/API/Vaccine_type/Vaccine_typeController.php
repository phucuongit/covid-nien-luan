<?php

namespace App\Http\Controllers\API\Vaccine_type;

use App\Http\Controllers\Controller;
use App\Models\Vaccine_type;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\Vaccine_type as Vaccine_typeResources;
use App\Http\Requests\Vaccine_typeRequest;

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
        $validatedData = $request->validated();
        $Vaccine_typeResult = new Vaccine_typeResources(Vaccine_type::create($validatedData));
        return $this->sendResponse($Vaccine_typeResult);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vaccine_type  $vaccine_type
     * @return \Illuminate\Http\Response
     */
    public function show(Vaccine_type $vaccine_type)
    {
        $Vaccine_typeResult = new Vaccine_typeResources($vaccine_type);
        return $this->sendResponse($Vaccine_typeResult);
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
        $validatedData = $request->validated();
        $Vaccine_typeResult = tap($vaccine_type)
                        ->update($validatedData);
        return $this->sendResponse($Vaccine_typeResult);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vaccine_type  $vaccine_type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vaccine_type $vaccine_type)
    {
        $Vaccine_typeResult = $vaccine_type
                        ->delete();
        return $this->sendResponse($vaccine_type);
    }
}
