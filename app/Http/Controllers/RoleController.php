<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Follow;
use App\Models\Rating;
use App\Models\Show;
use App\Models\Report;
use App\TMDB_api;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //If the logged in user is not an admin abort
        if(strcmp(Auth::user()->role,"Admin")!=0)
        abort(401);

        $users = User::all();
        $ratings = Rating::all();
        $follows = Follow::all();
        $shows = Show::all();
        $bannedUsers = User::onlyTrashed()->get();
        //$reports = DB::table('reports')->where('deleted_at', '=', NULL)->where('type','=','Report')->orderBy('status', 'DESC')->orderBy('created_at')->get();

        $reports = Report::where('type','=','Report')->orderBy('status', 'DESC')->orderBy('created_at','ASC')->get();
        $messages = Report::where('type','=','Message')->orderBy('read', 'ASC')->orderBy('created_at','ASC')->get();

        $userCount = count($users);
        $ratingsCount = count($ratings);
        $followsCount = count($follows);
        $showsCount = count($shows);

        $popshowids = DB::table('shows')->select('TMDB_show_id', DB::raw('count(*) as total'))->where('deleted_at', '=', NULL)->groupByRaw('show_name')->orderBy('total','DESC')->limit(5)->get();
        $popshows=[];
        $popshowsRatingsCount=[];
        $popshowsRatingsAVG=[];

        $ratingSumTMDB=0;
        $ratingSum=0;
        $limit = count($popshowids)==0? 1: count($popshowids);

        foreach ($popshowids->pluck('TMDB_show_id') as $id) {
            $TMDBShow=TMDB_api::getShowDetailsById($id);
            array_push($popshows,$TMDBShow );

            $sum=0;
            $count=0;
            foreach ($ratings as $rating) {
                if($rating->TMDB_show_id==$id){
                    $sum+=$rating->user_rating;
                    $count++;
                }
            }
            array_push($popshowsRatingsCount,$count);

            $ratingSumTMDB+=$TMDBShow->vote_average;

            //No dividing with 0
            if($count!=0){
            array_push($popshowsRatingsAVG,round($sum/$count,2));
            $ratingSum+=$sum/$count;
            }
            if($count==0){
            array_push($popshowsRatingsAVG,0);
            $ratingSum+=$TMDBShow->vote_average;
            }
        }

        $TMDBAVG = $ratingSumTMDB/$limit;
        $CDAVG = $ratingSum/$limit;

        $diff = $CDAVG-$TMDBAVG;



        return view( 'layouts.admin', [
            'users' => $users,
            'userCount' => $userCount,
            'ratingsCount' => $ratingsCount,
            'followsCount' => $followsCount,
            'showsCount' => $showsCount,
            'popshows' =>$popshows,
            'popshowsRatingCount' => $popshowsRatingsCount,
            'popshowsRatingsAVG' => $popshowsRatingsAVG,
            'TMDBAVG' => round($TMDBAVG,2),
            'CDAVG' => round($CDAVG,2),
            'diff' =>$diff,
            'bannedUsers' => $bannedUsers,
            'reports' => $reports,
            'messages' => $messages,
        ]);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
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
        if(strcmp(Auth::user()->role,"Admin")!=0)
        abort(401);

        User::withTrashed()->find($request->id)->restore();

        Session::flash('adminOperation',User::find($request->id)->username." has been Unbanned!");

        return redirect()->route('admin.index');
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
        if(strcmp(Auth::user()->role,"Admin")!=0)
        abort(401);

        $user = User::find($id);

        if(strcmp($user->role,"Admin")==0){
            $user->role="User";
            Session::flash('adminOperation',$user->username."'s role has been changed to User!");
        }
        else{
            $user->role="Admin";
            Session::flash('adminOperation',$user->username."'s role has been changed to Admin!");
        }

        $user->save();

        return redirect()->route('admin.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(strcmp(Auth::user()->role,"Admin")!=0)
        abort(401);


        $user=User::find($id);
        $user->role="User";
        $user->save();
        $user->delete();

        Session::flash('adminOperation',$user->username."has been banned!");

        return redirect()->route('admin.index');
    }
}
