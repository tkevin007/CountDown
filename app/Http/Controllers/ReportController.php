<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Psy\Readline\Hoa\Console;
use Illuminate\Support\Facades\Session;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new message.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('layouts.report');
    }

    /**
     * Store a newly created message in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     *  $request->message: the message we want to store
     *  $request->type: the type of message we need to store (Message or Report)
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Checking authorization
        if(!Auth::hasUser())
        abort(401);

        //If there are no message abort
        if($request->message==null)
        abort(400);

        //Creating new report and setting the values
        $report = new Report;
        $report->user_id=Auth::id();
        $report->message = $request->message;
        $report->type = $request->type;
        $report->read=0;
        $report->status="Unfixed";
        //Saving the new report to the database
        $report->save();

        //Creating a flash message about the success of the operation
        Session::flash('sent',"Your Message has been sent!");

        //Redirect to the mainpage
        return redirect()-> route("countDown.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Toggle the Read or the Status of the specified report depending on the type of the message
     *
     * @param  \Illuminate\Http\Request  $request
     * Required for the resourceController
     * @param  \App\Models\Report  $report
     *  $report: the report we want to toggle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        //Check authentication
        if(strcmp(Auth::user()->role,"Admin")!=0)
        abort(401);

        //Toggle the fields according to the input state, and creating a flash message about the success of the operation
        if( strcmp($report->type,"Report")==0 && strcmp($report->status,"Unfixed")==0){
            $report->status="Fixed";
            Session::flash('adminOperation',($report->user->username??"<Deleted User>")."'s Bug report has been marked as fixed!");
        }else if (strcmp($report->type,"Report")==0 && strcmp($report->status,"Fixed")==0){
            $report->status="Unfixed";
            Session::flash('adminOperation',($report->user->username??"<Deleted User>")."'s Bug report has been marked as Unfixed!");
        }
        else if(strcmp($report->type,"Message")==0 && $report->read==false){
            $report->read=true;
            Session::flash('adminOperation',($report->user->username??"<Deleted User>")."'s Message has been marked as Read!");
        }
        else if(strcmp($report->type,"Message")==0 && $report->read==true){
            $report->read=false;
            Session::flash('adminOperation',($report->user->username??"<Deleted User>")."'s Message has been marked as Unread!");
        }

        //The authenticated user will be set as the last user to modify the message
        $report->lastModifier=Auth::id();
        //Saving the changes
        $report->save();

        //Redirects back to the admin page
        return redirect()->route('admin.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        //
    }
}
