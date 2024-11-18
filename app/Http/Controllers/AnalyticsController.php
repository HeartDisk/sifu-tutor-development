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
                ->where('class_schedules.tutorID', '=', $tutorID)
                ->where('job_tickets.ticket_tutor_status', "Active")
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

        return view("analytics.tutorDropOutReport", ["results" => $results]);

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

            $currentMonth = $req->month;

        } elseif ($req->filled('month')) {

            $tutors = Tutor::whereMonth('created_at', $req->month)->get();
            $verified = Tutor::whereMonth('created_at', $req->month)->where('status', "verified")->count();
            $unverified = Tutor::whereMonth('created_at', $req->month)->where('status', "unverified")->count();
            $currentMonth = $req->month;


        } elseif ($req->filled('year')) {
            $tutors = Tutor::whereYear('created_at', $req->year)->get();
            $verified = Tutor::whereYear('created_at', $req->year)->where('status', "verified")->count();
            $unverified = Tutor::whereYear('created_at', $req->year)->where('status', "unverified")->count();
            $currentYear = $req->year;

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
        if (count($tutors) > 0) {
            $verified_percentage = ($verified / count($tutors)) * 100;
            $unverified_percentage = ($unverified / count($tutors)) * 100;

        } else {
            $verified_percentage = 0;
            $unverified_percentage = 0;
        }
        // $verified_percentage=($verified/count($tutors!=null?$tutors:0))*100;
        // $unverified_percentage=($unverified/count($tutors!=0?$tutors:0))*100;


        return view("analytics.tutorSuccessReport", ["tutors" => $tutors,
            'verified' => $verified, 'unverified' => $unverified,
            'verified_percentage' => $verified_percentage, 'unverified_percentage' => $unverified_percentage,
            'currentMonth' => $currentMonth, 'currentYear' => $currentYear]);
    }

    public function overview()
    {
        $invoices = DB::table("invoices")->count();
        $invoices_amount = DB::table("invoices")->sum("invoiceTotal");
        $avg_per_invoice =  $invoices_amount/$invoices ;
        
       
        
        $unpaid_invoice = DB::table("invoices")->where("status", "unpaid")->count();
        $unpaid_invoice_amount = DB::table("invoices")->where("status", "unpaid")->sum("invoiceTotal");
        // dd($unpaid_invoice_amount);
       
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
            'countData' => $countData,  'unpaid_invoice_amount' => $unpaid_invoice_amount,
             'tutors' => $tutors, 'tutors_active' => $tutors_active, 'logged_in_tutors' => $logged_in_tutors, 'tutor_scheduled_classes' => $tutor_scheduled_classes]);
    }

    public function classesByWeekday(Request $request)
    {
        // Get the from_date and to_date from the request
        $from_date = $request->input('from_date') ? $request->input('from_date') . '-01' : '2022-12-01'; // Default from date
        $to_date = $request->input('to_date') ? $request->input('to_date') . '-31' : '2023-06-30'; // Default to date

        // Query to get job tickets within the specified date range
        $jobTickets = DB::table('job_tickets')
            ->select(
                DB::raw("
                        COUNT(*) as count,
                        CASE
                            WHEN HOUR(created_at) BETWEEN 8 AND 11 THEN '8am – 12pm'
                            WHEN HOUR(created_at) BETWEEN 12 AND 15 THEN '12pm – 4pm'
                            WHEN HOUR(created_at) BETWEEN 16 AND 19 THEN '4pm – 8pm'
                            WHEN HOUR(created_at) BETWEEN 20 AND 23 THEN '8pm – 12am'
                            ELSE 'Invalid'
                        END as time_slot,
                        CASE
                            WHEN DAYOFWEEK(created_at) BETWEEN 2 AND 6 THEN 'Weekday'
                            ELSE 'Weekend'
                        END as day_type
                    ")
            )
            ->whereBetween('created_at', [$from_date, $to_date])
            ->groupBy('time_slot', 'day_type')
            ->get();

        // Initialize an array to store the results
        $data = [
            "Weekday" => [
                "8am – 12pm" => 0,
                "12pm – 4pm" => 0,
                "4pm – 8pm" => 0,
                "8pm – 12am" => 0,
            ],
            "Weekend" => [
                "8am – 12pm" => 0,
                "12pm – 4pm" => 0,
                "4pm – 8pm" => 0,
                "8pm – 12am" => 0,
            ],
            "Total" => [
                "8am – 12pm" => 0,
                "12pm – 4pm" => 0,
                "4pm – 8pm" => 0,
                "8pm – 12am" => 0,
            ]
        ];

        // Populate the results from the query
        foreach ($jobTickets as $ticket) {
            $data[$ticket->day_type][$ticket->time_slot] = $ticket->count;
        }

        // Calculate the totals
        foreach ($data['Total'] as $time_slot => $value) {
            $data['Total'][$time_slot] = $data['Weekday'][$time_slot] + $data['Weekend'][$time_slot];
        }

        return view('analytics/classesByWeekday', [
            'data' => $data,
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date')
        ]);
    }

    public function tutorVsSubject(Request $request)
    {
        // Get the selected month and year or use the current month and year as default
        $currentMonth = $request->input('month', date('m'));
        $currentYear = $request->input('year', date('Y'));

        $currentMonthFull = date('F', mktime(0, 0, 0, $currentMonth, 10)); // Full month name

        $subjects = DB::table('products')->get();

        $tutors = DB::table("products")
            ->join("class_schedules", "class_schedules.subjectID", "=", "products.id")
            ->join("categories", "products.category", "=", "categories.id")
            ->select(
                "products.*",
                DB::raw("SUM(CASE WHEN MONTH(class_schedules.date) = $currentMonth AND YEAR(class_schedules.date) = $currentYear THEN 1 ELSE 0 END) as current_month_count"),
                DB::raw("COUNT(class_schedules.tutorID) as total_count"),
                "categories.category_name",
                "categories.mode"
            )
            ->whereMonth('class_schedules.date', $currentMonth)
            ->whereYear('class_schedules.date', $currentYear)
            ->groupBy("products.id")
            ->get();

        return view('analytics.tutorVsSubject', compact('subjects', 'tutors', 'currentMonth', 'currentMonthFull', 'currentYear'));
    }


    public function studentVsSubject(Request $request)
    {
        // Get the selected month and year or use the current month and year as default
        $currentMonth = $request->input('month', date('m'));
        $currentYear = $request->input('year', date('Y'));

        $currentMonthFull = date('F', mktime(0, 0, 0, $currentMonth, 10)); // Full month name

        $tutors = DB::table("products")
            ->join("class_schedules", "class_schedules.subjectID", "=", "products.id")
            ->join("categories", "products.category", "=", "categories.id")
            ->select(
                "products.*",
                DB::raw("SUM(CASE WHEN MONTH(class_schedules.date) = $currentMonth AND YEAR(class_schedules.date) = $currentYear THEN 1 ELSE 0 END) as current_month_count"),
                DB::raw("COUNT(class_schedules.studentID) as total_count"),
                "categories.category_name",
                "categories.mode"
            )
            ->groupBy("products.id")
            ->get();

        return view('analytics.studentVsSubject', compact('tutors', 'currentMonth', 'currentYear', 'currentMonthFull'));
    }

    public function customerVsSubject(Request $request)
    {
        // Get the selected month and year or use the current month and year as default
        $currentMonth = $request->input('month', date('m'));
        $currentYear = $request->input('year', date('Y'));

        $currentMonthFull = date('F', mktime(0, 0, 0, $currentMonth, 10)); // Full month name

        $tutors = DB::table("products")
            ->join("class_schedules", "class_schedules.subjectID", "=", "products.id")
            ->join("categories", "products.category", "=", "categories.id")
            ->join("students", "class_schedules.studentID", "=", "students.id")
            ->join("customers", "students.customer_id", "=", "customers.id")
            ->select(
                "products.*",
                DB::raw("SUM(CASE WHEN MONTH(class_schedules.date) = $currentMonth AND YEAR(class_schedules.date) = $currentYear THEN 1 ELSE 0 END) as current_month_count"),
                DB::raw("COUNT(DISTINCT customers.id) as total_customer_count"),
                "categories.category_name",
                "categories.mode"
            )
            ->groupBy("products.id")
            ->get();

        return view('analytics.customerVsSubject', compact('tutors', 'currentMonth', 'currentYear', 'currentMonthFull'));
    }
    public function classesByDayType()
    {
        //
        return view('analytics/classesByDayType');
    }

       public function ticketStatus(Request $request)
    {
        $currentYear = Carbon::now()->year;

        // Step 1: Create an array of all months
        $months = collect([
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ]);

        // Step 2: Determine the range of months and years to query
        $fromMonth = $request->input('fromMonth', 1); // Default to January if not provided
        $fromYear = $request->input('fromYear', $currentYear); // Default to current year if not provided
        $toMonth = $request->input('toMonth', 12); // Default to December if not provided
        $toYear = $request->input('toYear', $currentYear); // Default to current year if not provided

        // Step 3: Generate the query to get the count of job tickets per month for the specified range
        $jobTickets = DB::table('job_tickets')
            ->select(DB::raw('MONTHNAME(created_at) as month, COUNT(*) as count'))
            ->whereBetween(DB::raw('MONTH(created_at)'), [$fromMonth, $toMonth])
            ->whereBetween(DB::raw('YEAR(created_at)'), [$fromYear, $toYear])
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get()
            ->keyBy('month');

        // Step 4: Merge the results with the full list of months
        $data = $months->map(function ($month) use ($jobTickets) {
            return $jobTickets->get($month)->count ?? 0;
        });

        // Convert the Laravel Collection to a plain PHP array
        $data = $data->toArray();

        // Pass months data to the view
        return view('analytics.ticketStatus', compact('data', 'months', 'fromMonth', 'fromYear', 'toMonth', 'toYear'));
    }


    public function studentInvoices(Request $request)
    {
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        // Get month and year from request or use current month and year
        $selectedMonth = $request->input('month', date('n'));
        $selectedYear = $request->input('year', date('Y'));

        // Query to retrieve all data initially
        $allDataQuery = DB::table('job_tickets')
            ->join('invoices', 'job_tickets.id', '=', 'invoices.ticketID')
            ->leftJoin('products', 'job_tickets.subjects', '=', 'products.id')
            ->select(
                'products.name as product_name',
                'products.id as product_id',
                DB::raw('YEAR(job_tickets.created_at) as year'),
                DB::raw('MONTHNAME(job_tickets.created_at) as month'),
                DB::raw('COUNT(job_tickets.id) as total_job_tickets'),
                DB::raw('SUM(job_tickets.totalPrice) as total_amount'),
                DB::raw('SUM(CASE WHEN job_tickets.mode = "physical" THEN 1 ELSE 0 END) as physical_job_tickets'),
                DB::raw('SUM(CASE WHEN job_tickets.mode = "online" THEN 1 ELSE 0 END) as online_job_tickets')
            )
            ->orderBy(DB::raw('MONTH(job_tickets.created_at)'));

        // Apply filters if month and year are selected
        if ($selectedMonth && $selectedYear) {
            $allDataQuery->where(DB::raw('MONTH(job_tickets.created_at)'), $selectedMonth)
                ->where(DB::raw('YEAR(job_tickets.created_at)'), $selectedYear);
        }

        // Execute the query to retrieve all data
        $allData = $allDataQuery->get();

        // Filtered data based on selected month and year
        $filteredData = [];
        foreach ($months as $month) {
            $monthNumber = array_search($month, $months) + 1;

            // Query for filtered data
            $filteredQuery = DB::table('job_tickets')
                ->join('invoices', 'job_tickets.id', '=', 'invoices.ticketID')
                ->leftJoin('products', 'job_tickets.subjects', '=', 'products.id')
                ->select(
                    'products.name as product_name',
                    'products.id as product_id',
                    DB::raw('YEAR(job_tickets.created_at) as year'),
                    DB::raw('MONTHNAME(job_tickets.created_at) as month'),
                    DB::raw('COUNT(job_tickets.id) as total_job_tickets'),
                    DB::raw('SUM(job_tickets.totalPrice) as total_amount'),
                    DB::raw('SUM(CASE WHEN job_tickets.mode = "physical" THEN 1 ELSE 0 END) as physical_job_tickets'),
                    DB::raw('SUM(CASE WHEN job_tickets.mode = "online" THEN 1 ELSE 0 END) as online_job_tickets')
                )
                ->where(DB::raw('MONTH(job_tickets.created_at)'), $monthNumber)
                ->where(DB::raw('YEAR(job_tickets.created_at)'), $selectedYear)
                ->groupBy('month', 'year')
                ->orderBy(DB::raw('MONTH(job_tickets.created_at)'))
                ->get();

            // Add filtered data to result
            $filteredData[] = [
                'month' => $month,
                'data' => $filteredQuery
            ];
        }

        // Pass data to view
        return view('analytics.studentInvoices', compact('allData', 'filteredData', 'months', 'selectedMonth', 'selectedYear'));
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
            ->groupBy('staff_name', 'month_name', 'staff_id')
            ->orderBy('month_name')
            ->where("job_tickets.admin_charge", "!=", NULL)
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
