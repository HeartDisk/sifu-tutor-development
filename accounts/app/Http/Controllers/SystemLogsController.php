<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

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
        //
        return view('systemLogs.userActivities');
    }
    public function textMessages()
    {
        //
        return view('systemLogs.textMessages');
    }

    
}
