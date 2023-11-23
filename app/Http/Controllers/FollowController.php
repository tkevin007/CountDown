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
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $followlist = Auth::user()->follows->pluck('follow_id');

        $followHistory=[];
        $follows=[];

        foreach ($followlist as $followedUser){
            $oneUser =User::find($followedUser);
            array_push($follows,$oneUser);
            foreach ($oneUser->ratings as $rating){
                array_push($followHistory,$rating);
            }
        }

        usort($followHistory, function($a, $b) {return strcmp($b->updated_at,$a->updated_at);});

        return view('layouts.follows',[
            'followHistory' => $followHistory,
            'followedUsers' => $follows,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $followers=[];
        $users=User::all();
        foreach($users as $user){
            array_push($followers, DB::table('follows')->where('follow_id','=',$user->id)->where('deleted_at','=',NULL)->count());
        }

        return view('layouts.newFollow',[
            'users' => User::with('shows','ratings','follows')->get(),
            'followers' =>$followers,
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

        if(Auth::check() && !in_array($request->user_id,User::find(Auth::id())->follows->pluck('follow_id')->toArray())){

            if($request->user_id==Auth::id()){
                abort(400);
            }

            $newFollow = new Follow;
            $newFollow->user_id=Auth::id();
            $newFollow->follow_id=$request->user_id;
            $newFollow->save();

            Session::flash('followed',User::find($newFollow->follow_id)->username);
        }
        return redirect()->route('follows.show',$request->user_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Follow  $follow
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $usershows = User::find($id);
        $shows=[];
        $allFollows = Follow::All();
        $follows=[];
        $followers=[];

        $userRatings= DB::table('ratings')->where('user_id','=',$id)->where('deleted_at','=',NULL)->orderBy('updated_at','desc')->take(3)->get();

        $allowFollow= DB::table('follows')->where('follow_id','=',$id)->where('user_id','=',Auth::id())->where('deleted_at','=',NULL)->count()==0 && $id!=Auth::id();
        $allowUnFollow= $id!=Auth::id();

        foreach ($allFollows as $follow) {
            if($follow->user_id==$id){
                if (User::where('id', '=' ,$follow->follow_id)->exists()) {
                    array_push($follows,User::find($follow->follow_id));
                }
            }
            else if($follow->follow_id==$id){
                if (User::where('id', '=' ,$follow->user_id)->exists()) {
                    array_push($followers,User::find($follow->user_id));
                }
            }
        }

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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Follow  $follow
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Auth::hasUser()){
            $delete= DB::table('follows')->where('follow_id','=',$id)->where('user_id','=',Auth::id())->where('deleted_at','=',NULL);
            Session::flash('unfollowed',User::find($id)->username);
            $delete->delete();
        }


        return redirect()->route('follows.show',$id);
    }
}
