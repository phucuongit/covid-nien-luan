<?php

namespace App\Http\Controllers\API\Vaccination;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use App\Models\Vaccination;
use App\Http\Resources\VaccinationResource as VaccinationResource;
use App\Http\Resources\VaccinationCollection as VaccinationCollection;
use App\Http\Requests\VaccinationRequest;
use Illuminate\Support\Facades\Storage;
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
            $vaccinations = 
                new VaccinationCollection(
                    $vaccinationQuery->paginate(20)->appends(request()->query())
                );           
            return $this->sendResponse($vaccinations->response()->getData(true));
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
    public function store(VaccinationRequest $request)
    {
        try {
            $validatedData = $request->validated();
            // Set injection's time
            $currentTime = 
                Vaccination::where('user_id', $validatedData['user_id'])->count();
            $validatedData['time'] = $currentTime + 1;
            
            $vaccinationResult = new VaccinationResource(Vaccination::create($validatedData));
            return $this->sendResponse($vaccinationResult);
        } catch (Exception $e) {
            return $this->sendError('Something went wrong', [$e->getMessage()]);
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
            $vaccinationResult = new VaccinationResource($vaccination);
            return $this->sendResponse($vaccinationResult);
        } catch (Exception $e) {
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
    public function update(VaccinationRequest $request, Vaccination $vaccination)
    {
        try {
            $validatedData = $request->validated();
            $vaccinationResult = $vaccination->update($validatedData);
            return $this->sendResponse($vaccinationResult);
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
    public function destroy(Vaccination $vaccination)
    {
        try {
            // Delete vaccination image's file
            $imageNames = $vaccination->images()->get('name');
            foreach ($imageNames as $index => $row)
            {
                Storage::disk('public')->delete('images/'.$row['name']);
            }
            // Delete images in DB
            $imageResult = $vaccination->images()->delete();
            $vaccinationResult = 
                $vaccination->delete() && $imageResult;
            return $this->sendResponse($vaccinationResult);
        }
        catch (Exception $e) {
            return $this->sendError('Something went wrong', [$e->getMessage()]);
        }
    }
}
