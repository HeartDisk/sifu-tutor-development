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

    public function tutorNotSubmitReport(Request $request)
    {
        $query = DB::table('class_attendeds')
            ->join("tutors", "class_attendeds.tutorID", "=", "tutors.id")
            ->join("students", "class_attendeds.studentID", "=", "students.id")
            ->join("products", "class_attendeds.subjectID", "products.id")
            ->where("is_report_submitted", "no");

        // Apply date filters if they are present in the request
        if ($request->has('fromDate') && $request->fromDate) {
            $query->whereDate('class_attendeds.date', '>=', $request->fromDate);
        }

        if ($request->has('toDate') && $request->toDate) {
            $query->whereDate('class_attendeds.date', '<=', $request->toDate);
        }

        $class_attendeds = $query->select(
            'class_attendeds.*',
            'ticketID',
            'students.full_name as studentName',
            'tutors.full_name as tutorName',
            'tutors.phoneNumber as tutorPhone',
            'products.name as productName'
        )->get();

        return view('followup/TutorNotSubmitReport', compact('class_attendeds'));
    }


    public function TutorNeverLogIn(Request $request)
    {
        // Get the search query from the request
        $searchQuery = $request->input('searchQuery');

        // Query tutors who never logged in and have an email, with an optional search filter
        $tutorLoggedIN = DB::table('tutors')
            ->where('last_login', '=', NULL)
            ->where('email', '!=', NULL)
            ->when($searchQuery, function ($query, $searchQuery) {
                return $query->where(function ($query) use ($searchQuery) {
                    $query->where('tutor_id', 'like', '%' . $searchQuery . '%')
                        ->orWhere('full_name', 'like', '%' . $searchQuery . '%');
                });
            })
            ->get();

        return view('followup/TutorNeverLogIn', compact('tutorLoggedIN'));
    }

    public function TutorNeverScheduleClass(Request $request)
    {
        // Get the search query input
        $searchQuery = $request->input('searchQuery');

        // Build the query to get tutors who have never scheduled a class
        $tutorsQuery = DB::table('tutors')->where('tutors.email', '!=', null);

        // If there is a search query, filter the tutors by tutor_id or full_name
        if (!empty($searchQuery)) {
            $tutorsQuery->where(function($query) use ($searchQuery) {
                $query->where('tutor_id', 'like', '%' . $searchQuery . '%')
                    ->orWhere('full_name', 'like', '%' . $searchQuery . '%');
            });
        }

        // Get the tutors
        $tutors = $tutorsQuery->get();

        // Initialize the array to store tutors who never scheduled a class
        $class_schedules = [];

        // Loop through the tutors to check their schedule
        foreach ($tutors as $tutor) {
            $check_schedule = DB::table('class_schedules')->where('tutorID', $tutor->id)->first();
            if ($check_schedule == null) {
                $class_schedules[] = $tutor;
            }
        }

        // Return the view with the filtered class_schedules
        return view('followup/TutorNeverScheduleClass', compact('class_schedules'));
    }
}
