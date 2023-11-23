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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('layouts.report');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Auth::hasUser())
        abort(401);

        $report = new Report;
        $report->user_id=Auth::id();
        $report->message = $request->message;
        $report->type = $request->type;
        $report->read=0;
        $report->status="Unfixed";
        $report->save();

        Session::flash('sent',"Your Message has been sent!");
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        if(strcmp(Auth::user()->role,"Admin")!=0)
        abort(401);


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


        $report->lastModifier=Auth::id();

        $report->save();


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
