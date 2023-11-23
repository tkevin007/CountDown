<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TMDB_api;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CountDownController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shows = DB::table('shows')->where('status','<>','Ended')->where('user_id','=',Auth::id())->where('deleted_at','=',NULL)->get();
        $countDownable=[];
        $showIds=[];

        foreach ($shows as $show) {
            $response=TMDB_api::getShowDetailsById($show->TMDB_show_id);

            if($response->next_episode_to_air!=null){
                if($response->next_episode_to_air->air_date>date("Y-m-d")){
                    array_push($showIds,$show->id);
                    array_push($countDownable,$response);
                }
            }
        }
        usort($countDownable, function($a, $b) {return strcmp($a->next_episode_to_air->air_date, $b->next_episode_to_air->air_date);});

        return view('layouts.countdown',[
            'shows' => $countDownable,
            'showIds' => $showIds,
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
