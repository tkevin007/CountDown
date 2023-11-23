<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TMDB_api;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class WatchListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::hasUser()){
            $shows = Auth::User()->shows;
            $notWatchedShows = [];
            $notWatchedEpisodeDeatils=[];

            foreach ($shows as $show) {
                $request = TMDB_api::getShowDetailsById($show->TMDB_show_id);
                if( $request->last_episode_to_air->season_number > $show->current_season ||($request->last_episode_to_air->episode_number>=$show->current_episode) ){
                    $episode=TMDB_api::getEpisodeDeatils($request->id,$show->current_season,$show->current_episode);

                    if(!isset($episode->success))
                    {
                        if($episode->air_date<new Date()){
                        array_push($notWatchedShows,$request);
                        array_push($notWatchedEpisodeDeatils,$episode);
                        }
                    }


                }
            }

            return view('layouts.watchlist',[
                'notWatchedShows' =>$notWatchedShows,
                'notWatchedEpisodeDetails' => $notWatchedEpisodeDeatils,
            ]);
        }

        return view('layouts.watchlist',[

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
    public function show($id,Request $request)
    {
        $show = TMDB_api::getShowDetailsById($id);
        $showRecord = DB::table('shows')->where('deleted_at','=',NULL)->where('user_id','=',Auth::id())->where('TMDB_show_id','=',$id)->get()[0];

        $step= isset($request->step)?$request->step:true;
        $season_number = $request->s==null?$showRecord->current_season:$request->s;
        $episode_number = $request->e==null? $showRecord->current_episode:$request->e;

        $episode = TMDB_api::getEpisodeDeatils($id,$season_number,$episode_number);

        $oldRating=DB::table('ratings')->where('deleted_at','=',NULL)->where('user_id','=',Auth::id())->where('show_id','=',$showRecord->id)
        ->where('season_number','=',$season_number)->where('episode_number','=',$episode_number)->get();

        $oldRating = isset($oldRating[0])?$oldRating[0] :null;

        return view('layouts.showWatchlistItem',[
            "show" => $show,
            "episode" => $episode,
            "showRecord" => $showRecord,
            'oldRating' => $oldRating,
            'step' => $step,
        ]);
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
