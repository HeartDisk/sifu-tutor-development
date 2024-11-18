<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;

class SystemLogsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function userActivities()
    {


        $data= DB::table("user_activities")->get();

        return view('systemLogs.userActivities',compact("data"));
    }
    public function textMessages()
    {
        $messages=DB::table("text_messages")->orderBy("id","desc")->get();
        return view('systemLogs.textMessages',compact('messages'));
    }


}
