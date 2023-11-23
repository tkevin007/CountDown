<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use app\Models\User;
use App\Models\Show;
use App\TMDB_api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shows=Auth::user()->shows;
        $ratings=DB::table('ratings')->where('user_id','=',Auth::id())->where('deleted_at','=',NULL)->orderBy('updated_at', 'desc')->get();

        return view('layouts.ratings',[
            "shows" => $shows,
            "ratings" =>$ratings,
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
        if(Auth::hasUser()){

            $showRecord= Show::find($request->showRecord);

            if($request->oldRating!=null){
                $rating = Rating::find($request->oldRating);
                $rating->user_rating=$request->rating;
                $rating->save();

                Session()->flash('change', $showRecord->show_name.' Season '. $rating->season_number. ' Episode '.$rating->episode_number."'s rating has been modified!");
            }
            else{
                $showEpisodeResponse=TMDB_api::getEpisodeDeatils(Show::find($request->showRecord)->TMDB_show_id,$request->s,$request->e);
                $rating = new Rating;
                $rating->user_rating=$request->rating;
                $rating->TMDB_show_id=$showRecord->TMDB_show_id;
                $rating->show_id=$showRecord->id;
                $rating->user_id=Auth::id();
                $rating->show_name=$showRecord->show_name;
                $rating->season_number=$request->s;
                $rating->episode_number=$request->e;
                $rating->episode_desc=$showEpisodeResponse->overview;
                $rating->episode_still_path=$showEpisodeResponse->still_path;
                $rating->save();
                Session()->flash('change', $showRecord->show_name.' Season '. $rating->season_number. ' Episode '.$rating->episode_number." has been Rated!");
            }


            if($request->step==1){
                $seasons=TMDB_api::getSeasonDetails($showRecord->TMDB_show_id,$request->s);

                if(isset(array_column($seasons->episodes, null, 'episode_number')[(string)($request->e)+1])){
                 $showRecord->current_episode+=1;
                }
                else{
                 $showRecord->current_season+=1;
                 $showRecord->current_episode=1;

                 //todo what if next season's numbering doesent't start with 1?
                }
                $showRecord->save();
            }
        }

        return redirect()->route('ratings.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $shows=User::find($id)->shows;
        $ratings=DB::table('ratings')->where('user_id','=',$id)->where('deleted_at','=',NULL)->orderBy('updated_at', 'desc')->get();

        return view('layouts.ratings',[
            "shows" => $shows,
            "ratings" =>$ratings,
            "user" =>User::find($id)->username,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function edit(Rating $rating)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rating $rating)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rating $rating)
    {
        //
    }
}
