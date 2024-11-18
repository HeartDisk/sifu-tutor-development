<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\State;
use App\Imports\StateImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StateController extends Controller
{
    //

    public function importState(Request $request){

        Excel::import(new StateImport, request()->file('file'));
    }

    public function getStates(){
        $states = State::latest()->get();

        return response()->json(['states' => $states]);

    }

    public function getCityByState(Request $request)
    {
        $cities = City::where('state_id', $request->state_id)->get();

        return response()->json(['cities' => $cities]);

    }
}
