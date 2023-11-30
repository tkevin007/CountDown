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
     * Display a listing of the favourited shows by the authenticated user.
     *
     * @return \Illuminate\Http\Response
     *  TMDB_ap_shows: the details of each favourite shows in an array
     *  show_ids: the id's of the displayed show's record
     */
    public function index()
    {
        $TMDB_api_shows = collect();
        //finding favourite shows
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
     * Show the form for adding a new show to favourites.
     *
     * @param Requet request
     *  $request->searchtext: the searched string
     *  $request->pageNum: if there are multiple pages of results you can set which page to show
     *
     * @return \Illuminate\Http\Response
     *  results: the shows found for the given text (not all if there are multiple pages)
     *  oldSearch: the searched term to keep the text in the searchbar
     *  existingShows: an array of already added show's TMDB id's to avoid adding the same show multiple times
     */
    public function create(Request $request)
    {
        $result=[];
        if($request->all()!=[]){
            //searching the TMDB database for the given subString
            $result = TMDB_api::getSearch($request->searchtext,$request->pageNum);
        }

        //Getting only the TMDB_id's of the already added shows
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
     *  $request->show_id: the id of the show in the TMDB database
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Checking for correct permissions
        if(!Auth::check()){
            return redirect()->route('shows.index');
        }

        $response = TMDB_api::getShowDetailsById($request->show_id);

        //Setting up new
        $show = new Show;
        $show->user_id = Auth::id();
        $show->show_name = $response->name;
        $show->TMDB_show_id=$response->id;
        $show->current_season=$response->last_episode_to_air->season_number;
        $show->current_episode=$response->last_episode_to_air->episode_number;
        $show->status=$response->status;

        //Creating the new show in the database
        $show->save();

        //redirecting to the editing page of the newly created show
        return redirect()->route('shows.edit',$show->id);

    }

    /**
     * Display the specified show from TMDB.
     *
     * @param  int $id: the TMDB Id of the show
     * @return \Illuminate\Http\Response
     *  show: the TMDB data of the show
     *  users: the users who favourited the show
     *  doFollow: whether the user is following currently or not
     *  followRecordId: the authenticated user's record of this show
     *  showImages: array of images form the show
     */
    public function show($id)
    {
        //get the details of the show from TMDB
        $show = TMDB_api::getShowDetailsById($id);
        //Finding user id's of users who favourited the show
        $userFavs= DB::table('shows')->where('deleted_at','=',NULL)->where('TMDB_show_id','=',$id)->get();
        $usersFollowing = [];
        $doFollow=false;
        $followRecordId=Null;

        foreach ($userFavs as $userRecord) {
            //The user the show record belongs to
            $user=DB::table('users')->where('id','=',$userRecord->user_id)->where('deleted_at','=',NULL)->get();

            if(!($user->isEmpty())){ //if not soft deleted or wrong id
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
     * Show the form for editing the specified show.
     *
     * @param int $id - the id of the show's record
     *
     * @return \Illuminate\Http\Response
     *  TMDB_api_show: the data got from TMDB
     *  current_episode: the current progress of the show (episode number)
     *  current_season: the current progress of the show (season number)
     *  id: the id of show's record
     */
    public function edit($id)
    {
        //Checking for correct permissions
        if(Auth::id()!=Show::find($id)->user_id){
            return redirect()-> route('shows.index');
        }

        //Get the details of the show from TMDB
        $TMDB_api_show=TMDB_api::getShowDetailsById(Show::find($id)->TMDB_show_id);
        return view('layouts.editShow',[
            'TMDB_api_show' => $TMDB_api_show,
            'current_episode' =>Show::find($id)->current_episode,
            'current_season'=>Show::find($id)->current_season,
            'id'=>$id,
        ]
        );

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *  $request->season: the season needed to be set
     *  $request->episode: the episode number needed to be set
     * @param  $id: the id of the show's record
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        //Checking for correct authorization
        if(Auth::id()!=Show::find($id)->user_id){
            return redirect()-> route('shows.index');
        }

        //finding show needed to be modified
        $s= Show::find($id);
        $seasons=TMDB_api::getSeasonDetails($s->TMDB_show_id,$request->season);

        //Stepping the last seen episode to the next one, if the season ends then we step the season and reset the episode counter
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
        //Flash message about the success of the operation
        Session::flash('modified',$s->show_name);
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
