<?php

namespace App\Http\Controllers\API\Vaccination;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use App\Models\Vaccination;
use App\Http\Resources\Vaccination as VaccinationResources;
use App\Http\Requests\VaccinationRequest;
use Illuminate\Http\Request;

class VaccinationController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vaccinationResults = VaccinationResources::collection(Vaccination::all());
        return $this->sendResponse($vaccinationResults);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VaccinationRequest $request)
    {
        $validatedData = $request->validated();
        $vaccinationResult = new VaccinationResources(Vaccination::create($validatedData));
        return $this->sendResponse($vaccinationResult);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Vaccination $vaccination)
    {
        $vaccinationResult = new VaccinationResources($vaccination);
        return $this->sendResponse($vaccinationResult);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VaccinationRequest $request, Vaccination $vaccination)
    {
        $validatedData = $request->validated();
        $vaccinationResult = tap($vaccination)
                        ->update($validatedData);
        return $this->sendResponse($vaccinationResult);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vaccination $vaccination)
    {
        $vaccinationResult = $vaccination
                        ->delete();
        return $this->sendResponse($vaccination);
    }
}
