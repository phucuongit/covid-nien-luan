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
        $vaccinations = VaccinationResources::collection(Vaccination::all());
        return $this->sendResponse($vaccinations);
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
        $vaccination = new VaccinationResources(Vaccination::create($request->all()));
        return $this->sendResponse($vaccination);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vaccination = new VaccinationResources(Vaccination::findOrFail($id));
        return $this->sendResponse($vaccination);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VaccinationRequest $request, $id)
    {
        $validatedData = $request->validated();
        $vaccination = tap(Vaccination::find($id))
                    ->update($request->all());
        return $this->sendResponse($vaccination);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vaccination = tap(Vaccination::find($id))
                        ->delete();
        return $this->sendResponse($vaccination);
    }
}
