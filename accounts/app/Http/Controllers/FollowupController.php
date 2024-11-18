<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class FollowupController extends Controller
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

    // public function StudentInvoiceReadyConfirmation(Request $request)
    // {
    //     //
    //     $invoices = DB::table('invoices');
    //     if($request->fromDate != null){
    //         $invoices = $invoices->where('created_at','>=',$request->fromDate);
    //     }
    //     if($request->toDate != null){
    //         $invoices = $invoices->where('created_at','<=',$request->toDate);
    //     }
        
    //     $invoices = $invoices->where('status','=','unpaid')->get();
        
        
    //     $invoices = DB::table('invoices')->where('status','=','unpaid')->get();
    //     foreach($invoices as $invoice)
    //     {
    //         $ticket = DB::table("job_tickets")
    //                     ->join("class_schedules","job_tickets.id","=","class_schedules.ticketID")
    //                     ->select("job_tickets.*","class_schedules.date as schedule_date","class_schedules.id as schedule_id")
    //                     ->where("job_tickets.ticket_id",$invoice->ticketID)
    //                     ->first(); 
                        
    //                     // dd($ticket);
            
    //         if($ticket) {
    //             $invoice->schedule_data = $ticket->schedule_date;
                
    //              $reportSubmissionDate=DB::table("tutorFirstSubmittedReportFromApps")
    //                     ->join("class_schedules","tutorFirstSubmittedReportFromApps.scheduleID","=","class_schedules.id")
    //                     ->select("tutorFirstSubmittedReportFromApps.*")
    //                     ->where("tutorFirstSubmittedReportFromApps.scheduleID",$ticket->schedule_id)
    //                     ->first(); 
                
                        
    //                     $invoice->report_date=isset($reportSubmissionDate->currentDate)?$reportSubmissionDate->currentDate:"";
    //         }
    //         else {
    //             $invoice->schedule_data = null;
    //             $invoice->report_date=null;
    //         }
    //     }
        
    //     return view('followup.StudentInvoiceReadyConfirmation',Compact('invoices'));
    // }
    
    public function StudentInvoiceReadyConfirmation(Request $request)
    {
        $query = DB::table('invoices')
            ->where('status', 'unpaid');
        
        if ($request->fromDate) {
            $query->whereDate('created_at', '>=', $request->fromDate);
        }
        if ($request->toDate) {
            $query->whereDate('created_at', '<=', $request->toDate);
        }
    
        $invoices = $query->get();
    
        // Debugging to check if properties are set correctly
        foreach ($invoices as $invoice) {
            $invoice->schedule_date = null;
            $invoice->report_date = null;
    
            $ticket = DB::table('job_tickets')
                ->join('class_schedules', 'job_tickets.id', '=', 'class_schedules.ticketID')
                ->select('job_tickets.*', 'class_schedules.date as schedule_date', 'class_schedules.id as schedule_id')
                ->where('job_tickets.ticket_id', $invoice->ticketID)
                ->first();
    
            if ($ticket) {
                $invoice->schedule_date = $ticket->schedule_date;
    
                $reportSubmissionDate = DB::table('tutorFirstSubmittedReportFromApps')
                    ->join('class_schedules', 'tutorFirstSubmittedReportFromApps.scheduleID', '=', 'class_schedules.id')
                    ->select('tutorFirstSubmittedReportFromApps.currentDate')
                    ->where('tutorFirstSubmittedReportFromApps.scheduleID', $ticket->schedule_id)
                    ->first();
    
                $invoice->report_date = $reportSubmissionDate->currentDate ?? "";
            }
        }
    
        // Debugging output
        // dd($invoices);
    
        return view('followup.StudentInvoiceReadyConfirmation', compact('invoices'));
    }

    public function viweStudentInvoiceReadyConfirmation ($id)
    {
        //
        $invoice_detail = DB::table('invoices')->where('id','=',$id)->orderBy('id','desc')->first();
        $invoice_items = DB::table('invoice_items')->where('invoiceID','=',$id)->orderBy('id','desc')->get();
        $students = DB::table('students')->where('id','=',$invoice_items[0]->studentID)->orderBy('id','DESC')->first();
        $subjects = DB::table('products')->where('id','=',$invoice_items[0]->subjectID)->orderBy('id','DESC')->first();
             return view('followup.viewStudentInvoiceReadyConfirmation',Compact('invoice_items','students','invoice_detail','subjects'));
    }
    
    public function editStudentInvoiceReadyConfirmation ($id)
    {
        $invoice_detail = DB::table('invoices')->where('status','=','unpaid')->where('id','=',$id)->first();
        $invoice_items = DB::table('invoice_items')->
        join('students', 'invoice_items.studentID', '=', 'students.id')->
        join('products', 'invoice_items.subjectID', '=', 'products.id')->
        select('invoice_items.*', 'students.full_name as full_name', 'products.name as name', 'products.price as price')
        ->where('invoice_items.invoiceID',$id)->get();
        return view('followup.editStudentInvoiceReadyConfirmation',Compact('invoice_detail', 'invoice_items'));
    }
    
    

    // public function TutorNotUpdateClassSchedule(Request $request)
    // {
    //     $class_schedules = DB::table('class_schedules');
    //     if($request->fromDate != null){
            
    //         $class_schedules = $class_schedules->where('created_at','>=',$request->fromDate);
    //     }
    //     if($request->toDate != null){
    //         $class_schedules = $class_schedules->where('created_at','<=',$request->toDate);
    //     }
        
    //     $class_schedules = $class_schedules->where('class_schedule_id','=',0)->get();
        
    //     return view('followup/TutorNotUpdateClassSchedule',Compact('class_schedules'));
        
        
    // }
    
    public function TutorNotUpdateClassSchedule(Request $request)
    {
        $query = DB::table('class_schedules');
    
        if ($request->fromDate) {
            $query->whereDate('created_at', '>=', $request->fromDate);
        }
        if ($request->toDate) {
            $query->whereDate('created_at', '<=', $request->toDate);
        }
    
        $class_schedules = $query->where('class_schedule_id', 0)->get();
    
        return view('followup.TutorNotUpdateClassSchedule', compact('class_schedules'));
    }

    public function TutorNotSubmitReport()
    {
        //
        
        $class_attendeds = DB::table('class_attendeds')->
        join("tutors","class_attendeds.tutorID","=","tutors.id")->
        join("students","class_attendeds.studentID","=","students.id")->
        join("products","class_attendeds.subjectID","products.id")->
        where("is_report_submitted","no")->
        select('class_attendeds.*','ticketID',"students.full_name as studentName",
            "tutors.full_name as tutorName","tutors.phoneNumber as tutorPhone",
            "products.name as productName")->get();
            
            // dd($class_attendeds);
          
                    return view('followup/TutorNotSubmitReport',Compact('class_attendeds'));

    }
    public function TutorNeverLogIn()
    {
        //
        $tutorLoggedIN = DB::table('tutors')->where('last_login','=',NULL)->where('email','!=',NULL)->get();
        
        return view('followup/TutorNeverLogIn', Compact('tutorLoggedIN'));
    }

    public function TutorNeverScheduleClass()
    {
        
         $tutors=DB::table("tutors")->where("tutors.email","!=",null)->get();
        $class_schedules=[];
        foreach ($tutors as $tutor)
        {
            $check_schedule=DB::table("class_schedules")->where("tutorID",$tutor->id)->first();
            if($check_schedule==null)
            {
                array_push($class_schedules,$tutor);
            }

        }
        
         return view('followup/TutorNeverScheduleClass',Compact('class_schedules'));
        dd($data);
        
         $tutors=DB::table("tutors")->where("tutors.email","!=",null)->get();
        $tutors=DB::table("tutors")->leftjoin("class_schedules","class_schedules.tutorID","=","tutors.id")->get();
        dd($tutors);
        
       $class_schedules = DB::table("tutors")
                        ->join(
                            DB::raw("(SELECT tutorID, COUNT(*) as num_schedules FROM class_schedules GROUP BY tutorID) as cs"),
                            "cs.tutorID",
                            "=",
                            "tutors.id"
                        )
                        ->whereNotNull("tutors.email")
                        ->select("tutors.*", "cs.num_schedules")
                        ->get();

         return view('followup/TutorNeverScheduleClass',Compact('class_schedules'));
        
        // // $class_schedules = DB::table('class_schedules')
        // //         ->join('products', 'class_schedules.subjectID', '=', 'products.id')
        // //         ->join('tutors', 'class_schedules.tutorID', '=', 'tutors.id')
        // //         ->select('class_schedules.*', 'products.*', 'tutors.*')
        // //         ->where('class_schedules.class_schedule_id', '=', 0)
        // //         ->orderBy('class_schedules.id', 'DESC')
        // //         ->get();
                
                 $class_schedules = DB::table('tutors')
                ->join('products', 'class_schedules.subjectID', '=', 'products.id')
                ->join('class_schedules', 'class_schedules.tutorID', '=', 'class_schedules.id')
                ->select('class_schedules.*', 'products.*', 'tutors.*')
                ->where('class_schedules.class_schedule_id', '=', 0)
                ->orderBy('class_schedules.id', 'DESC')
                ->get();
    
        // $class_schedules = DB::table('class_schedules')->where('class_schedule_id','=',0)->get();
        // $subject = DB::table('products')->where('id','=',$class_schedules[0]->subjectID)->orderBy('id','DESC')->first();
        // $tutor = DB::table('tutors')->where('id','=',$class_schedules[0]->subjectID)->orderBy('id','DESC')->first();
        // dd($class_schedules);
        
        return view('followup/TutorNeverScheduleClass',Compact('class_schedules'));
        
    }

    


    
}
