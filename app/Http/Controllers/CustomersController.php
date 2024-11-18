<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function checkCustomerDuplicateEmailJobTicket(Request $request)
    {

        $tables = ['customers', 'staffs', 'tutors','users'];
        $results = [];

        foreach ($tables as $table) {
            $result = DB::table($table)
                ->where('email', "=" ,$request->customerEmail)
                ->first();

            if ($result) {
                $results[$table] = $result;
            }
        }

        if (!empty($results)) {
            return response()->json(["recordFound" => true], 200);
        } else {
            return response()->json(["recordFound" => false], 200);
        }
    }


    public function checkCustomerDuplicateEmail(Request $request)
    {

        $tables = ['customers', 'staffs', 'tutors','users'];
        $results = [];

        foreach ($tables as $table) {
            $result = DB::table($table)
                ->where('email', $request->customerEmail)
                ->first();

            if ($result) {
                $results[$table] = $result;
            }
        }



        if (!empty($results)) {
            return response()->json(["recordFound" => true], 200);
        } else {
            return response()->json(["recordFound" => false], 200);
        }
    }

    public function checkCustomerDuplicatePhone(Request $request)
    {
        $tables = ['customers', 'staffs', 'tutors','users'];

        $found = false; // Flag to indicate if a record is found

        if(isset($request->customerWhatsapp)) {
            foreach ($tables as $table) {
                // Search for a record based on WhatsApp number
                $existingRecord = DB::table($table)
                    ->where('whatsapp', $request->customerWhatsapp)
                    ->first();
                if ($existingRecord) {
                    $found = true;
                    break; // Stop the loop if a record is found
                }
            }
        } else {
            // If tutorWhatsApp is not set, use tutorMobile for search
            foreach ($tables as $table) {
                if($table=="tutors")
                {
                    $existingRecord = DB::table($table)
                        ->where('phoneNumber', $request->customerMobile)
                        ->first();
                }else{
                    $existingRecord = DB::table($table)
                        ->where('phone', $request->customerMobile)
                        ->first();
                }

                if ($existingRecord) {
                    $found = true;
                    break;
                }
            }
        }

        if ($found) {
            return response()->json(["recordFound" => true], 200);
        } else {
            return response()->json(["recordFound" => false], 200);
        }

    }


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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
