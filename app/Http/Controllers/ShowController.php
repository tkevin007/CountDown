<?php

namespace App\Http\Controllers;

use App\Models\Show;
use App\Models\User;
use App\TMDB_api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class ShowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $TMDB_api_shows = collect();
        foreach(Auth::User()->shows as $show){
        $TMDB_api_shows->push(TMDB_api::getShowDetailsById($show->TMDB_show_id));
        }

        return view('layouts.shows',[
            'TMDB_api_shows' => $TMDB_api_shows,
            'show_ids'=> Auth::User()->shows->pluck('id'),
        ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $result=[];
        if($request->all()!=[]){
            $result = TMDB_api::getSearch($request->searchtext,$request->pageNum);
        }

        $existingShows= ((User::find(Auth::User()->id))->shows)->pluck('TMDB_show_id')->toArray();

        return view('layouts.newShow',[
            'results' => $result,
            'oldSearch' => $request->searchtext,
            'existingShows' => $existingShows,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Auth::check()){
            $response = TMDB_api::getShowDetailsById($request->show_id);

            $show = new Show;
            $show->user_id = Auth::id();
            $show->show_name = $response->name;
            $show->TMDB_show_id=$response->id;
            $show->current_season=$response->last_episode_to_air->season_number;
            $show->current_episode=$response->last_episode_to_air->episode_number;
            $show->status=$response->status;

            $show->save();


            return redirect()->route('shows.edit',$show->id);
        }
        return redirect()->route('shows.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Show  $show
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $show = TMDB_api::getShowDetailsById($id);

        $userFavs= DB::table('shows')->where('deleted_at','=',NULL)->where('TMDB_show_id','=',$id)->get();
        $usersFollowing = [];
        $doFollow=false;
        $followRecordId=Null;

        foreach ($userFavs as $userRecord) {
            $user=DB::table('users')->where('id','=',$userRecord->user_id)->where('deleted_at','=',NULL)->get();

            if(!($user->isEmpty())){
                $user=$user[0];
                array_push($usersFollowing,$user);
                if(Auth::id()==$userRecord->user_id){
                $doFollow=true;
                $followRecordId=$userRecord->id;
                }
            }



        }

        $showImages = TMDB_api::getShowImages($id);

        return view('layouts.showDeatils',[
            "show" => $show,
            "users" => $usersFollowing,
            "doFollow" => $doFollow,
            "followRecordId" => $followRecordId,
            'showImages'=>$showImages,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Show  $show
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Auth::id()==Show::find($id)->user_id){
            $TMDB_api_show=TMDB_api::getShowDetailsById(Show::find($id)->TMDB_show_id);

        return view('layouts.editShow',[
            'TMDB_api_show' => $TMDB_api_show,
            'current_episode' =>Show::find($id)->current_episode,
            'current_season'=>Show::find($id)->current_season,
            'id'=>$id,
        ]
        );
        }
        return redirect()-> route('shows.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Show  $show
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {


        if(Auth::id()==Show::find($id)->user_id){
            $s= Show::find($id);
            Session::flash('modified',$s->show_name);

            $seasons=TMDB_api::getSeasonDetails($s->TMDB_show_id,$request->season);
            if(isset(array_column($seasons->episodes, null, 'episode_number')[(string)($request->episode)+1])){
                $s->update([
                    'current_season'=>$request->season,
                    'current_episode'=>($request->episode)+1,
                 ]);
               }
               else{
                $s->update([
                    'current_season'=>($request->season)+1,
                    'current_episode'=>1,
            ]);
               }



        }

        return redirect()-> route('shows.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Show  $show
     * @return \Illuminate\Http\Response
     */
    public function destroy($show_id)
    {
        if(Auth::id()==Show::find($show_id)->user_id){
            $s= Show::find($show_id);
            Session::flash('deleted',$s->show_name);
            $s->delete();
            $s->save();
        }

        return redirect()-> route('shows.index');
    }
}
