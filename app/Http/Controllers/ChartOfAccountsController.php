<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ChartOfAccountsController extends Controller
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
    public function index()
    {
        //
    }


     public function ChartOfAccounts(){
        $chartofaccounts = DB::table('chart_accounts')->get();
        return view('chartofaccounts/chartOfAccountsList', Compact('chartofaccounts'));
    }

    public function addChartOfAccounts(){
        $chartofaccounts = DB::table('chart_accounts')->get();
        return view('chartofaccounts/addChartOfAccounts',compact('chartofaccounts'));
    }

    public function viewChartOfAccounts($id){
        $chartofaccounts = DB::table('chart_accounts')->where('id','=',$id)->first();
        return view('chartofaccounts/viewChartOfAccounts',Compact('chartofaccounts'));
    }

    public function editChartOfAccounts($id){


        return view('chartofaccounts/editChartOfAccounts');
    }


     public function submitChartOfAccounts(Request $request)
    {

        if($request->is_cash_source == 'on'){
            $values = array(
                    'uid' => $request->accountID,
                    'name' => $request->account_name,
                    'slug' => $request->account_name,
                    'code' => $request->account_code,
                    'type' => $request->account_type,
                    'description' => $request->description,
                    'is_cash_source' => 1

                    );
        }else{
            $values = array(
                    'uid' => $request->accountID,
                    'name' => $request->account_name,
                    'slug' => $request->account_name,
                    'code' => $request->account_code,
                    'type' => $request->account_type,
                    'description' => $request->description,
                    'is_cash_source' => 0

                    );
        }




        $tutorLastID = DB::table('chart_accounts')->insertGetId($values);


        // $customer_values = array(
        //             'student_id' => $studentLastID,
        //             'uid' => 'CUS-'.date('Hisdm'),
        //             'full_name' => $request->customerFullName,
        //             'gender' => $request->customerGender,
        //             'age' => $request->customerAge,
        //             'email' => $request->customerEmail,
        //             'dob' => $request->customerDateOfBirth,
        //             'nric' => $request->customerCNIC,
        //             'address1' => $request->customerStreetAddress1,
        //             'address2' => $request->customerStreetAddress2,
        //             'city' => $request->cicustomerCityty,
        //             'postal_code' => $request->customerPostalcode,
        //             'latitude' => $request->customerLatitude,
        //             'longitude' => $request->customerLongitude,
        //             'customerable_type' => 0,
        //             'customerable_id' => 0,
        //             'remarks' => $request->remarks
        //             );
        // $studentLastID = DB::table('customers')->insertGetId($customer_values);

        return redirect()->back()->with('success','Chart of Account has been added successfully!');


        return view('chartofaccounts/addChartOfAccounts');
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
