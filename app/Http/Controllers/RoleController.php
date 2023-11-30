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
     * Display the admin page with all admin related information and statistics.
     *
     * @return \Illuminate\Http\Response
     * users: all of our users
     * userCount: count of our users
     * ratingsCount: count of our ratings
     * followsCount: the follows table's record count,
     * showsCount: the shows table'S record count
     * popshows: the TOP5 shows and their details
     * popshowsRatingCount: the count of ratings of our TOP5 shows in an array,
     * popshowsRatingsAVG: the avg ratings of our TOP5 shows in an array,
     * TMDBAVG: the avg rating of our TOP5 shows in the TMDB database,
     * CDAVG: countDown average, the average rating of the TOP5 shows in our database,
     * diff: the difference of TMDBAVG and CDAVG
     * bannedUsers: the list of banned users
     * reports: the list of messages with a type of "Report"
     * messages: the list of messages with a type of "Message"
     */
    public function index()
    {

        //If the logged in user is not an admin abort
        if(strcmp(Auth::user()->role,"Admin")!=0)
        abort(401);

        //get all nessesary data
        $users = User::all();
        $ratings = Rating::all();
        $follows = Follow::all();
        $shows = Show::all();
        $bannedUsers = User::onlyTrashed()->get();

        //get messages into different arrays based on type
        $reports = Report::where('type','=','Report')->orderBy('status', 'DESC')->orderBy('created_at','ASC')->get();
        $messages = Report::where('type','=','Message')->orderBy('read', 'ASC')->orderBy('created_at','ASC')->get();

        //getting the count of our data
        $userCount = count($users);
        $ratingsCount = count($ratings);
        $followsCount = count($follows);
        $showsCount = count($shows);

        //getting the TOP 5 seried from our database
        $popshowids = DB::table('shows')->select('TMDB_show_id', DB::raw('count(*) as total'))->where('deleted_at', '=', NULL)->groupByRaw('show_name')->orderBy('total','DESC')->limit(5)->get();
        $popshows=[];
        $popshowsRatingsCount=[];
        $popshowsRatingsAVG=[];

        $ratingSumTMDB=0;
        $ratingSum=0;
        $limit = count($popshowids)==0? 1: count($popshowids); //avoid division by 0

        //get details of the TOP5 shows from TMDB
        foreach ($popshowids->pluck('TMDB_show_id') as $id) {
            $TMDBShow=TMDB_api::getShowDetailsById($id);
            array_push($popshows,$TMDBShow );

            $sum=0;
            $count=0;
            //calculate the avg rating of our users about this show
            foreach ($ratings as $rating) {
                if($rating->TMDB_show_id==$id){
                    $sum+=$rating->user_rating;
                    $count++;
                }
            }
            array_push($popshowsRatingsCount,$count);

            //calculate the avg rating of all TOP5 shows
            $ratingSumTMDB+=$TMDBShow->vote_average;

            //calculate the avg rating from our local data
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


        //returning the admin page's layout with all of our calculated stats
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
     * Restore banned user's profile
     *
     * @param  \Illuminate\Http\Request  $request
     *  $request->id: the id of the user we want to unban
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Check the premissions of the authenticated user
        if(strcmp(Auth::user()->role,"Admin")!=0)
        abort(401);

        //restore banned user by the id given
        User::withTrashed()->find($request->id)->restore();

        //Flash message about the success of the operation
        Session::flash('adminOperation',User::find($request->id)->username." has been Unbanned!");

        //Redirect back to the admin page
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
     * Change the role of the user given
     *
     * @param  \Illuminate\Http\Request  $request - Required for the resourceController
     * @param int id: the user's id we want to change the role of
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Check the premissions of the authenticated user
        if(strcmp(Auth::user()->role,"Admin")!=0)
        abort(401);

        //Searching for the user
        $user = User::find($id);

        //Toggling the role of the user
        if(strcmp($user->role,"Admin")==0){
            $user->role="User";
            Session::flash('adminOperation',$user->username."'s role has been changed to User!");
        }
        else{
            $user->role="Admin";
            Session::flash('adminOperation',$user->username."'s role has been changed to Admin!");
        }

        //Saving the modifications
        $user->save();
        //Flash message about the success of the operation
        Session::flash('adminOperation',$user->username."'s role has been changed!");
        //Redirect back to the admin page
        return redirect()->route('admin.index');
    }

    /**
     * Ban the specified user.
     *
     * @param  int  $id: the id of the user we want to Ban
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         //Check the premissions of the authenticated user
        if(strcmp(Auth::user()->role,"Admin")!=0)
        abort(401);

        //If the user gets banned their role will automatically be set to default (User)
        $user=User::find($id);
        $user->role="User";
        //Saving the rolechange
        $user->save();
        //Soft deleting user
        $user->delete();

        //Flash message about the success of the operation
        Session::flash('adminOperation',$user->username."has been banned!");
        //redirect back to the admin page
        return redirect()->route('admin.index');
    }
}
