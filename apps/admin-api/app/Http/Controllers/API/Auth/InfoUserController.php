<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\Vaccination;
use App\Models\Test_result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InfoUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Vaccination::all();
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
