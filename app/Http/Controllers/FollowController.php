<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use app\Models\User;
use App\TMDB_api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FollowController extends Controller
{
    /**
     * Display a listing of the resource.
     * Lists all of the follows of  the user and their activity
     * route /
     * @return \Illuminate\Http\Response
     *     followHistory : all previous ratings of the users followed
     *     followedUsers : the profile data's of the followed accounts
     */
    public function index()
    {
        //get the id of the authenticated user
        $followlist = Auth::user()->follows->pluck('follow_id');

        $followHistory=[];
        $follows=[];


        foreach ($followlist as $followedUser){
            //finding the followed user's profile
            $oneUser =User::find($followedUser);
            array_push($follows,$oneUser);
            foreach ($oneUser->ratings as $rating){
                //adding the ratings of the followed profile to an array
                array_push($followHistory,$rating);
            }
        }

        //sorting the array so that the most recent ratings are first and the oldest in the back
        usort($followHistory, function($a, $b) {return strcmp($b->updated_at,$a->updated_at);});

        return view('layouts.follows',[
            'followHistory' => $followHistory,
            'followedUsers' => $follows,
        ]);
    }

    /**
     * Show the form for creating a new follow.
     *
     * @return \Illuminate\Http\Response
     *      users : Every User
     *      followers : a list of followed profiles
     */
    public function create()
    {
        $followers=[];
        $users=User::all();
        foreach($users as $user){
            //finding the already followed profiles
            array_push($followers, DB::table('follows')->where('follow_id','=',$user->id)->where('deleted_at','=',NULL)->count());
        }

        return view('layouts.newFollow',[
            'users' => User::with('shows','ratings','follows')->get(),
            'followers' =>$followers,
        ]);
    }

    /**
     * Store a newly created follow relation in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     *  request->user_id : a users id that the logged in profile wants to follow
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if(Auth::check() && !in_array($request->user_id,User::find(Auth::id())->follows->pluck('follow_id')->toArray())){

            if($request->user_id==Auth::id()){
                abort(400);
            }
            //setting the up the data for the new follow
            $newFollow = new Follow;
            $newFollow->user_id=Auth::id();
            $newFollow->follow_id=$request->user_id;
            //adding the $newFollow to the database
            $newFollow->save();

            //sending a flash message to the next page that shows saying the success of the operation
            Session::flash('followed',User::find($newFollow->follow_id)->username);
        }

        //redirecting the user to the profile of their new follow
        return redirect()->route('follows.show',$request->user_id);
    }

    /**
     * Display the profile of the user given.
     *
     * @param  \App\Models\Follow  $follow
     *  id : the user we want the profile of
     *
     * @return \Illuminate\Http\Response
     *  user: all the data associated with the user
     *  followers: users that follow this profile
     *  follows: users that this profile follows
     *  shows: data of the favourite shows of the user
     *  ratings: the last 3 ratings of the user
     *  allowFollow: weather the the auth user can follow this profile currently
     *  allowUnFollow: weather the auth user can unfollow this profile currently
     */
    public function show($id)
    {
        $usershows = User::find($id);
        $shows=[];
        $allFollows = Follow::All();
        $follows=[];
        $followers=[];

        //get the last 3 rating of the user
        $userRatings= DB::table('ratings')->where('user_id','=',$id)->where('deleted_at','=',NULL)->orderBy('updated_at','desc')->take(3)->get();

        //setting if the following allowed or not
        $allowFollow= DB::table('follows')->where('follow_id','=',$id)->where('user_id','=',Auth::id())->where('deleted_at','=',NULL)->count()==0 && $id!=Auth::id();
        $allowUnFollow= $id!=Auth::id();

        foreach ($allFollows as $follow) {

            // finding the users we follow
            if($follow->user_id==$id){
                if (User::where('id', '=' ,$follow->follow_id)->exists()) {
                    array_push($follows,User::find($follow->follow_id));
                }
            }

            //finding the user following us
            else if($follow->follow_id==$id){
                if (User::where('id', '=' ,$follow->user_id)->exists()) {
                    array_push($followers,User::find($follow->user_id));
                }
            }
        }

        //getting the data for the favourited shows
        foreach ($usershows->shows as $show) {
            array_push($shows, TMDB_api::getShowDetailsById($show->TMDB_show_id));
        }

        return view('layouts.profile',[
            'user' => User::where('id',$id)->with('shows','ratings','follows')->get()[0],
            'followers' => $followers,
            'follows' => $follows,
            'shows' =>$shows,
            'ratings' => $userRatings,
            'allowFollow' => $allowFollow,
            'allowUnFollow' =>$allowUnFollow,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Follow  $follow
     * @return \Illuminate\Http\Response
     */
    public function edit(Follow $follow)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Follow  $follow
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Follow $follow)
    {
        //
    }

    /**
     * Remove the specified follow relation from the database.
     *
     * @param  \App\Models\Follow  $follow
     *  id: the id of the user we want to unfollow
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Auth::hasUser()){
            //getting the record of the follow relation
            $delete= DB::table('follows')->where('follow_id','=',$id)->where('user_id','=',Auth::id())->where('deleted_at','=',NULL);

            //sending a success message to flash on the next page
            Session::flash('unfollowed',User::find($id)->username);
            //deleting the resource
            $delete->delete();
        }
        //displaying the profile we unfollowed
        return redirect()->route('follows.show',$id);
    }
}
