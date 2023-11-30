<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TMDB_api;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CountDownController extends Controller
{
    /**
     * Display a listing of series that have a release date given, and it is in the future
     * route /countDown
     *
     * @return \Illuminate\Http\Response
     *      shows: The data of the shows from the TMDB database in an array
     *      shwowIds: The id's of the shows in the same order as
     */
    public function index()
    {
        //get the shows where the status is not 'ended' and belongs to the authenticated user and softDelete is false
        $shows = DB::table('shows')->where('status','<>','Ended')->where('user_id','=',Auth::id())->where('deleted_at','=',NULL)->get();
        $countDownable=[];
        $showIds=[];

        foreach ($shows as $show) {
            //get details of the shows
            $response=TMDB_api::getShowDetailsById($show->TMDB_show_id);

            // check if there are announced release dates
            if($response->next_episode_to_air!=null){
                //check if the releade date is in the future or already passed
                if($response->next_episode_to_air->air_date>date("Y-m-d")){
                    //add to return arrays
                    array_push($showIds,$show->id);
                    array_push($countDownable,$response);
                }
            }
        }
        //Sorting the shows based on the release dates, so that the first to release is in front
        usort($countDownable, function($a, $b) {return strcmp($a->next_episode_to_air->air_date, $b->next_episode_to_air->air_date);});

        return view('layouts.countdown',[
            'shows' => $countDownable
        ]);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
}
