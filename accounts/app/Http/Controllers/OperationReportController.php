<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class OperationReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    
    
    public function dailyTicketApplication()
    {
        //
        $subjects = DB::table('products')->join("categories","products.category","categories.id")
        ->select("products.*","categories.mode as mode","categories.category_name as category_name")
        ->get();
        
        $states=DB::table('states')->get();
        
        $cities=DB::table('cities')->get();
        
        return view('operationReport/dailyTicketApplication',Compact('subjects','states','cities'));
    }
    
    public function monthlyInvoiceChargeStatus()
    {
        //
        return view('operationReport/monthlyInvoiceChargeStatus');
    }
    
    public function monthlyProductVsComission()
    {
       
       $monthlyData = DB::table('job_tickets')
                    ->leftJoin('staffs', 'job_tickets.admin_charge', '=', 'staffs.id')
                    ->leftJoin('products', 'job_tickets.subjects', '=', 'products.id')
                    ->select(
                        'products.name as product_name', // Assuming the product name field is 'name'
                        'products.id as product_id',
                        'staffs.full_name as staff_name',
                         'staffs.id as staff_id ',
                        DB::raw('MONTHNAME(job_tickets.created_at) as month_name'),
                        DB::raw('COUNT(job_tickets.id) as total_job_tickets'),
                        DB::raw('SUM(job_tickets.totalPrice) as total_amount')
                    )
                    ->groupBy('product_name', 'month_name', 'product_id')
                    ->orderBy('month_name')
                    ->where('job_tickets.admin_charge', '!=', NULL)
                    ->get();
                    
                    
                    
// dd($monthlyData);
       

        foreach ($monthlyData as $data) {

            if ($data->total_amount >= 11000) {
                $data->bonus = round($data->total_amount * 0.02);
            } else {
                $data->bonus = round($data->total_amount * 0.01);
            }
        }
        
       

        return view('operationReport/monthlyProductVsComission', ["data" => $monthlyData]);
    }

    public function monthlyProductVsComissionByMonth()
    {

    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('classSchedule/index');
    }
    
    public function fetchClassSchedules(){
        
        $classes = DB::table('student_subjects')->where('tutor_id','!=',NULL)->select('day','created_at');
        return response()->json($classes);
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
