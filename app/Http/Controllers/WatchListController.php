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
     * Display the watchlist based on favourite series and the progress of them by the user.
     *
     * @return \Illuminate\Http\Response
     *  notWatchedShows: the shows needed to be displayed in the list in an array
     *  notWatchedEpisodeDetails: the details of the next episode needed to be watched in an array
     */
    public function index()
    {

    //Check the authentication of user
    if(!Auth::hasUser()){
        return view('layouts.watchlist',[]);
    }

    //get favourite shows of user
    $shows = Auth::User()->shows;
    $notWatchedShows = [];
    $notWatchedEpisodeDeatils=[];

    //loop through the shows
    foreach ($shows as $show) {
        $request = TMDB_api::getShowDetailsById($show->TMDB_show_id);

        //checking if the user has seen the last episode already released or not
        if( $request->last_episode_to_air->season_number > $show->current_season ||($request->last_episode_to_air->episode_number>=$show->current_episode) ){
            $episode=TMDB_api::getEpisodeDeatils($request->id,$show->current_season,$show->current_episode);

            if(!isset($episode->success))
            {
                //meking sure the episode has already aired
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
     * Display the form to rate a specific episode.
     *
     * @param  int  $id - the id of the show we want to rate
     * @param  Request $request
     *  $request->step: option to turn off incrementation of the progreee
     *  $request->s: the season of the episode to be rated
     *  $request->e: the episode number to be rated
     *
     * @return \Illuminate\Http\Response
     *  show: the show details from TMDB
     *  episode: episode details from TMDB
     *  showRecord: the record of the user's show in the shows table
     *  oldRating: if the episode was already rated before we pass the old rating value
     *  step: passing the step input value so the store method will run accordingly
     */
    public function show($id,Request $request)
    {
        //get the data of the show from TMDB
        $show = TMDB_api::getShowDetailsById($id);
        //get the record of the show that belongs to the user
        $showRecord = DB::table('shows')->where('deleted_at','=',NULL)->where('user_id','=',Auth::id())->where('TMDB_show_id','=',$id)->get()[0];

        //if $request->step is not set than progress will be incremented by default
        $step= isset($request->step)?$request->step:true;
        //option to set specific season to be rated
        $season_number = $request->s==null?$showRecord->current_season:$request->s;
        //option to set specific episode to be rated
        $episode_number = $request->e==null? $showRecord->current_episode:$request->e;

        $episode = TMDB_api::getEpisodeDeatils($id,$season_number,$episode_number);


        //Getting the previous rating of the same episode if there is already one
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
