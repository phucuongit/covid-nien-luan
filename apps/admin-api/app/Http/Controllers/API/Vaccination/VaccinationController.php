<?php

namespace App\Http\Controllers\API\Vaccination;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use App\Models\Vaccination;
use App\Http\Resources\Vaccination as VaccinationResources;
use App\Http\Requests\VaccinationRequest;
use Illuminate\Http\Request;
Use Exception;

class VaccinationController extends BaseController
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
            $vaccinationQuery = Vaccination::filter($params);
            $vaccinations = VaccinationResources::collection($vaccinationQuery->get());
            return $this->sendResponse($vaccinations);
        }
        catch (Exception $e) {
            return $this->sendError('Something went wrong', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VaccinationRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $vaccinationResult = new VaccinationResources(Vaccination::create($validatedData));
            return $this->sendResponse($vaccinationResult);
        } catch (Exception $e) {
            return $this->sendError('Something went wrong', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Vaccination $vaccination)
    {
        try {
            $vaccinationResult = new VaccinationResources($vaccination);
            return $this->sendResponse($vaccinationResult);
        } catch (Exception $e) {
            return $this->sendError('Something went wrong', ['error' => $e->getMessage()]);
        }
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
        try {
            $validatedData = $request->validated();
            $vaccinationResult = tap($vaccination)
                            ->update($validatedData);
            return $this->sendResponse($vaccinationResult);
        }
        catch (Exception $e) {
            return $this->sendError('Something went wrong', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vaccination $vaccination)
    {
        try {
            $vaccinationResult = $vaccination
                            ->delete();
            return $this->sendResponse($vaccination);
        }
        catch (Exception $e) {
            return $this->sendError('Something went wrong', ['error' => $e->getMessage()]);
        }
    }
}
