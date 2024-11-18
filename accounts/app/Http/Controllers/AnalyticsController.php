<?php

namespace App\Http\Controllers;

use App\Models\Tutor;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function getTutorDropOutRate()
    {
        
        // $tutorStudentRecords =  DB::table('students')
        //     ->join('class_schedules', 'students.id', '=', 'class_schedules.studentID')
        //     ->join('products', 'class_schedules.subjectID', '=', 'products.id')
        //     ->join('job_tickets', 'class_schedules.ticketID', '=', 'job_tickets.id')
        //     ->where('class_schedules.tutorID','=',21)
        //     ->where('job_tickets.ticket_tutor_status',"Active")
        //     >where('studentStatus',"Active")
        //     ->distinct('students.student_id')
        //     ->select(
        //         'products.name as subjectName',
        //         'students.id as studentID',
        //         'students.register_date as studentRegisterDate',
        //         'students.full_name as studentName',
        //         'class_schedules.ticketID as jobTicketId',
        //         'job_tickets.ticket_tutor_status as ticket_tutor_status',
        //         'students.age as studentAge',
        //         'students.gender as studentGender',
        //         'students.student_id as uid',
        //         'students.reasonCategory as reasonCategory',
        //         'students.status as studentStatus'
        //     )->get();
            
        //     dd($tutorStudentRecords);
            
        //     dd($tutorStudentRecords->count());
        
        //  $studentsQuery = DB::table('students')
        //                     ->join('class_schedules', 'students.id', '=', 'class_schedules.studentID')
        //                     ->join('job_tickets', 'class_schedules.ticketID', '=', 'job_tickets.id')
        //                     ->where('class_schedules.tutorID', '=', 21)
        //                     ->distinct('students.id')
        //                     ->select('students.id')->get();
                            
                            
        // dd($studentsQuery->count());
        
    //   $tutorStudentRecords = DB::table('class_schedules')
    //                         ->join('job_tickets', 'class_schedules.ticketID', '=', 'job_tickets.id')
    //                         ->where('class_schedules.tutorID', '=', 21)
    //                         ->where('job_tickets.ticket_tutor_status', 'discontinued')
    //                         ->distinct('class_schedules.studentID')
    //                         ->select(
    //                             'class_schedules.ticketID as jobTicketId',
    //                             'job_tickets.ticket_tutor_status as ticket_tutor_status'
    //                         )
    //                         ->get();
            
    //         dd($tutorStudentRecords->count());
        
        
        
        
        
        
        
        
        
        
        
        $tutors = Tutor::all();
        $results = [];
        
        foreach ($tutors as $tutor) {
            $tutorID = $tutor->id;
        
            // Students Query
            $studentsQuery = DB::table('students')
                            ->join('class_schedules', 'students.id', '=', 'class_schedules.studentID')
                            ->join('products', 'class_schedules.subjectID', '=', 'products.id')
                            ->join('job_tickets', 'class_schedules.ticketID', '=', 'job_tickets.id')
                            ->where('class_schedules.tutorID','=',$tutorID)
                             ->where('job_tickets.ticket_tutor_status',"Active")
                            ->distinct('students.student_id')
                            ->select(
                                'products.name as subjectName',
                                'students.id as studentID',
                                'students.register_date as studentRegisterDate',
                                'students.full_name as studentName',
                                'class_schedules.ticketID as jobTicketId',
                                'job_tickets.ticket_tutor_status as ticket_tutor_status',
                                'students.age as studentAge',
                                'students.gender as studentGender',
                                'students.student_id as uid',
                                'students.reasonCategory as reasonCategory',
                                'students.status as studentStatus'
                            )->get();
        
            // Counting active students
            $activeStudentsCount = $studentsQuery->where('studentStatus', 'active')->count();
        
            // Counting inactive students
            $inactiveStudentsCount = $studentsQuery->where('studentStatus', 'inactive')->count();
        
            // Tickets Query
            $ticketsQuery = DB::table('class_schedules')
                            ->join('job_tickets', 'class_schedules.ticketID', '=', 'job_tickets.id')
                            ->where('class_schedules.tutorID', '=', $tutorID)
                            ->distinct('class_schedules.studentID')
                            ->select(
                                'class_schedules.ticketID as jobTicketId',
                                'job_tickets.ticket_tutor_status as ticket_tutor_status'
                            )
                            ->get();
        
            // Counting active tickets
            $activeTicketsCount = $ticketsQuery->where('ticket_tutor_status', 'Active')->count();
        
            // Counting discontinued tickets
            $discontinuedTicketsCount = $ticketsQuery->where('ticket_tutor_status', 'discontinued')->count();
        
            // dd($discontinuedTicketsCount);
        
            // Total students and tickets
            $totalStudents = $activeStudentsCount + $inactiveStudentsCount;
            $totalTickets = $activeTicketsCount + $discontinuedTicketsCount;
        
            // Calculating percentages
            $studentsPercentage = ($totalStudents > 0) ? ($activeStudentsCount / $totalStudents) * 100 : 0;
            $ticketsPercentage = ($totalTickets > 0) ? ($activeTicketsCount / $totalTickets) * 100 : 0;
        
            // Store results in array
            $results[] = (object)[
                'tutor' => $tutor->full_name,
                'active_students' => $activeStudentsCount,
                'inactive_students' => $inactiveStudentsCount,
                'active_tickets' => $activeTicketsCount,
                'discontinued_tickets' => $discontinuedTicketsCount,
                'students_success_percentage' => round($studentsPercentage, 2), // Rounded to two decimal places
                'tickets_success_percentage' => round($ticketsPercentage, 2), // Rounded to two decimal places
            ];
        }
            
            
            // dd($results);
            
            // dd($results);
            
        return view("analytics.tutorDropOutReport",["results"=>$results]);

    }

    public function tutorsuccessreport(Request $req)
    {
        $tutor = Tutor::query();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        if ($req->filled(['month', 'year', 'custom_date'])) {
            $tutors = Tutor::whereYear('created_at', $currentYear)->
            whereMonth('created_at', $req->month)->
            whereDate('created_at', $req->custom_date)->get();

            $verified = Tutor::whereYear('created_at', $currentYear)->
            whereMonth('created_at', $req->month)->
            whereDate('created_at', $req->custom_date)->where('status', "verified")->count();

            $unverified = Tutor::whereYear('created_at', $currentYear)->
            whereMonth('created_at', $req->month)->
            whereDate('created_at', $req->custom_date)->where('status', "unverified")->count();
            
            $currentMonth=$req->month;

        } elseif ($req->filled('month')) {

            $tutors = Tutor::whereMonth('created_at', $req->month)->get();
            $verified = Tutor::whereMonth('created_at', $req->month)->where('status', "verified")->count();
            $unverified = Tutor::whereMonth('created_at', $req->month)->where('status', "unverified")->count();
            $currentMonth=$req->month;


        } elseif ($req->filled('year')) {
            $tutors = Tutor::whereYear('created_at', $req->year)->get();
            $verified = Tutor::whereYear('created_at', $req->year)->where('status', "verified")->count();
            $unverified = Tutor::whereYear('created_at', $req->year)->where('status', "unverified")->count();
            $currentYear=$req->year;

        } elseif ($req->filled('custom_date')) {
            $tutors = Tutor::whereDate('created_at', $req->custom_date)->get();
            $verified = Tutor::whereDate('created_at', $req->custom_date)->where('status', "verified")->count();
            $unverified = Tutor::whereDate('created_at', $req->custom_date)->where('status', "unverified")->count();

        } else {
           
            $tutors = $tutor->orderByDesc('id')->get();
            $verified = Tutor::where('status', "verified")->count();
            $unverified = Tutor::where('status', "unverified")->count();

        }
            
            
            // dd(count($tutors));
            
            
            // dd($tutors);
            if(count($tutors)>0)
            {
                $verified_percentage=($verified/count($tutors))*100;
                $unverified_percentage=($unverified/count($tutors))*100;
                 
            }else{
                 $verified_percentage=0;
                $unverified_percentage=0; 
            }
            // $verified_percentage=($verified/count($tutors!=null?$tutors:0))*100;
            // $unverified_percentage=($unverified/count($tutors!=0?$tutors:0))*100;
           
            
        return view("analytics.tutorSuccessReport", ["tutors" => $tutors,
            'verified' => $verified, 'unverified' => $unverified,
            'verified_percentage'=>$verified_percentage,'unverified_percentage'=>$unverified_percentage,
            'currentMonth' => $currentMonth, 'currentYear' => $currentYear]);
    }

    public function overview()
    {
        $invoices = DB::table("invoices")->count();
        $invoices_amount = DB::table("invoices")->sum("invoiceTotal");
        $avg_per_invoice = $invoices / $invoices_amount;
        $unpaid_invoice = DB::table("invoices")->where("status", "unpaid")->count();
        $sum_unpaid_invoices = DB::table("invoices")->where("status", "unpaid")->sum("invoiceTotal");

        $tutors = DB::table("tutors")->count();
        $tutors_active = DB::table("tutors")->where("status", "verified")->count();
        $logged_in_tutors = DB::table("tutorlogs")->count();


        $tutor_scheduled_classes = DB::table("class_schedules")
            ->select(DB::raw('count(distinct tutorID) as total_tutors'))
            ->first();
            
            
        $currentYear = now()->year;
        $countData = [];
        
        // Loop through months from February (2) to December (12)
        for ($month = 1; $month <= 12; $month++) {
            $students_count = DB::table("students")
                            ->whereMonth("created_at", $month)
                            ->whereYear("created_at", $currentYear)
                            ->count();
        
            $tutors_count = DB::table("tutors")
                            ->whereMonth("created_at", $month) 
                            ->whereYear("created_at", $currentYear) 
                            ->count();
        
            $countData[] = [
                'month' => $month,
                'students_count' => $students_count,
                'tutors_count' => $tutors_count
            ];
        }

            
      

        return view('analytics/overview', ['invoices' => $invoices, 'invoices_amount' => $invoices_amount, 'avg_per_invoice' => $avg_per_invoice, 'unpaid_invoice' => $unpaid_invoice,
        'countData'=>$countData
            , 'tutors' => $tutors, 'tutors_active' => $tutors_active, 'logged_in_tutors' => $logged_in_tutors, 'tutor_scheduled_classes' => $tutor_scheduled_classes]);
    }

    public function classesByWeekday()
    {
        //
        return view('analytics/classesByWeekday');
    }


    public function tutorVsSubject()
    {
        //
        $subjects = DB::table('products')->get();
        $currentMonth = date('F');
        $currentYear = date('Y');

        $currentMonthFull = date('F');

        $currentMonth = date('m');

        $tutors = DB::table("products")
            ->join("class_schedules", "class_schedules.subjectID", "=", "products.id")
            ->join("categories", "products.category", "=", "categories.id")
            ->select(
                "products.*",
                DB::raw("SUM(CASE WHEN MONTH(class_schedules.date) = $currentMonth THEN 1 ELSE 0 END) as current_month_count"),
                DB::raw("COUNT(class_schedules.tutorID) as total_count"),
                "categories.category_name",
                "categories.mode"
            )
            ->groupBy("products.id")
            ->get();


        // dd($tutors);

        return view('analytics/tutorVsSubject', Compact('subjects', 'tutors', 'currentMonth', 'currentMonthFull', 'currentYear'));
    }

    public function studentVsSubject()
    {

        $currentMonth = date('F');
        $currentYear = date('Y');

        $currentMonthFull = date('F');

        $currentMonth = date('m');

        $tutors = DB::table("products")
            ->join("class_schedules", "class_schedules.subjectID", "=", "products.id")
            ->join("categories", "products.category", "=", "categories.id")
            ->select(
                "products.*",
                DB::raw("SUM(CASE WHEN MONTH(class_schedules.date) = $currentMonth THEN 1 ELSE 0 END) as current_month_count"),
                DB::raw("COUNT(class_schedules.studentID) as total_count"),
                "categories.category_name",
                "categories.mode"
            )
            ->groupBy("products.id")
            ->get();


        return view('analytics/studentVsSubject', Compact('tutors', 'currentMonth', 'currentYear', 'currentMonthFull'));
    }

    public function customerVsSubject()
    {
        $currentMonth = date('F');
        $currentYear = date('Y');

        $currentMonthFull = date('F');

        $currentMonth = date('m');

        $tutors = DB::table("products")
            ->join("class_schedules", "class_schedules.subjectID", "=", "products.id")
            ->join("categories", "products.category", "=", "categories.id")
            ->join("students", "class_schedules.studentID", "=", "students.id")
            ->join("customers", "students.customer_id", "=", "customers.id")
            ->select(
                "products.*",
                DB::raw("SUM(CASE WHEN MONTH(class_schedules.date) = $currentMonth THEN 1 ELSE 0 END) as current_month_count"),
                DB::raw("COUNT(DISTINCT customers.id) as total_customer_count"),
                "categories.category_name",
                "categories.mode"
            )
            ->groupBy("products.id")
            ->get();


        return view('analytics/customerVsSubject', Compact('tutors', 'currentMonth', 'currentYear', 'currentMonthFull'));

    }

    public function classesByDayType()
    {
        //
        return view('analytics/classesByDayType');
    }

    public function ticketStatus()
    {
        $currentYear = Carbon::now()->year;

        // Step 1: Create an array of all months
        $months = collect([
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ]);
        
        // Step 2: Generate the query to get the count of job tickets per month for the current year
        $jobTickets = DB::table('job_tickets')
            ->select(DB::raw('MONTHNAME(created_at) as month, COUNT(*) as count'))
            ->whereYear('created_at', $currentYear)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get()
            ->keyBy('month');
        
        // Step 3: Merge the results with the full list of months
        $data = $months->map(function ($month) use ($jobTickets) {
            return $jobTickets->get($month)->count ?? 0;
        });
        
        // Convert the Laravel Collection to a plain PHP array
        $data = $data->toArray();
       
        return view('analytics/ticketStatus',["data"=>$data]);
    }

   
       public function studentInvoices()
    {

        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        $rows = [];
        foreach ($months as $month) {
            $monthNumber = array_search($month, $months) + 1;

            $total_invoices = DB::table('job_tickets')
                ->join('invoices', 'job_tickets.id', '=', 'invoices.ticketID')
                ->leftJoin('products', 'job_tickets.subjects', '=', 'products.id')
                ->select(
                    'products.name as product_name',
                    'products.id as product_id',
                    DB::raw('YEAR(job_tickets.created_at) as year'),
                    DB::raw('MONTHNAME(job_tickets.created_at) as month'),
                    DB::raw('COUNT(job_tickets.id) as total_job_tickets'),
                    DB::raw('SUM(job_tickets.totalPrice) as total_amount')
                )
                ->where(DB::raw('MONTH(job_tickets.created_at)'), $monthNumber) // Filter for the current month
                ->orderBy(DB::raw('MONTH(job_tickets.created_at)'))
                ->first();

            $physical_invoices = DB::table('job_tickets')
                ->join('invoices', 'job_tickets.id', '=', 'invoices.ticketID')
                ->leftJoin('products', 'job_tickets.subjects', '=', 'products.id')
                ->select(
                    'products.name as product_name',
                    'products.id as product_id',
                    DB::raw('YEAR(job_tickets.created_at) as year'),
                    DB::raw('MONTHNAME(job_tickets.created_at) as month'),
                    DB::raw('COUNT(job_tickets.id) as total_job_tickets'),
                    DB::raw('SUM(job_tickets.totalPrice) as total_amount')
                )
                ->where('job_tickets.mode', 'physical') // Correct condition for physical mode
                ->where(DB::raw('MONTH(job_tickets.created_at)'), $monthNumber) // Filter for the current month
                ->orderBy(DB::raw('MONTH(job_tickets.created_at)'))
                ->first();

            $online_invoices = DB::table('job_tickets')
                ->join('invoices', 'job_tickets.id', '=', 'invoices.ticketID')
                ->leftJoin('products', 'job_tickets.subjects', '=', 'products.id')
                ->select(
                    'products.name as product_name',
                    'products.id as product_id',
                    DB::raw('YEAR(job_tickets.created_at) as year'),
                    DB::raw('MONTHNAME(job_tickets.created_at) as month'),
                    DB::raw('COUNT(job_tickets.id) as total_job_tickets'),
                    DB::raw('SUM(job_tickets.totalPrice) as total_amount')
                )
                ->where('job_tickets.mode', 'online') // Correct condition for online mode
                ->where(DB::raw('MONTH(job_tickets.created_at)'), $monthNumber) // Filter for the current month
                ->orderBy(DB::raw('MONTH(job_tickets.created_at)'))
                ->first();

            $rows[] = [
                'month' => $month,
                'total_job_tickets' => $total_invoices ? $total_invoices->total_job_tickets : 0,
                'physical_job_tickets' => $physical_invoices ? $physical_invoices->total_job_tickets : 0,
                'online_job_tickets' => $online_invoices ? $online_invoices->total_job_tickets : 0
            ];
        }
        
        
        $total = [
            'total_job_tickets' => 0,
            'physical_job_tickets' => 0,
            'online_job_tickets' => 0
        ];
        
        foreach ($rows as $row) {
            $total['total_job_tickets'] += $row['total_job_tickets'];
            $total['physical_job_tickets'] += $row['physical_job_tickets'];
            $total['online_job_tickets'] += $row['online_job_tickets'];
        }

        
        return view('analytics/studentInvoices', Compact('rows','total'));
    }

    public function picSalesPerformance()
    {
        $monthlyData = DB::table('job_tickets')
            ->leftJoin('staffs', 'job_tickets.admin_charge', '=', 'staffs.id')
            ->select(
                'staffs.full_name as staff_name',
                'staffs.id as staff_id',
                DB::raw('MONTHNAME(job_tickets.created_at) as month_name'),
                DB::raw('COUNT(job_tickets.id) as total_job_tickets'),
                DB::raw('SUM(job_tickets.totalPrice) as total_amount')
            )
            ->groupBy('staff_name', 'month_name','staff_id')
            ->orderBy('month_name')
            ->where("job_tickets.admin_charge","!=",NULL)
            ->get();




        foreach ($monthlyData as $data) {

            if ($data->total_amount >= 11000) {
                $data->bonus = round($data->total_amount * 0.02);
            } else {
                $data->bonus = round($data->total_amount * 0.01);
            }
        }
//        dd($monthlyData);

        return view('analytics/picSalesPerformance', ["data" => $monthlyData]);
    }

    public function platformUsage()
    {
        //
        return view('analytics/platformUsage');
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
