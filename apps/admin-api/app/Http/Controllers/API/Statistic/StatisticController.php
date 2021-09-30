<?php

namespace App\Http\Controllers\API\Statistic;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Statistic;
use Illuminate\Http\Request;
use App\Models\Vaccination;
use App\Models\Test_result;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use DB;

class StatisticController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $data = [];

            // Injection statistic
            $data['injection_first_time'] = Vaccination::where('time', 1)->count();
            $data['injection_second_time'] = Vaccination::where('time', 2)->count();
            $data['injection_total_time'] = Vaccination::all()->count();
            
            // Injection in last 7 days
            $data['injection_last_7days'] = 
                    Vaccination::select(
                        DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y') as date"),
                        DB::raw("COUNT(id) as quantity"),
                    )
                    ->where('created_at', '>' , Carbon::now()->subDays(7))
                    ->orderBy('created_at')
                    ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"))
                    ->get();
                    
            // Send
            return $this->sendResponse($data, 'Successfully');
        }
        catch (Exception $e) {
            return $this->sendError('Something went wrong', ['error' => $e->getMessage()]);
        }  
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
     * @param  \App\Models\Statistic  $statistic
     * @return \Illuminate\Http\Response
     */
    public function show(Statistic $statistic)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Statistic  $statistic
     * @return \Illuminate\Http\Response
     */
    public function edit(Statistic $statistic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Statistic  $statistic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Statistic $statistic)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Statistic  $statistic
     * @return \Illuminate\Http\Response
     */
    public function destroy(Statistic $statistic)
    {
        //
    }
}
