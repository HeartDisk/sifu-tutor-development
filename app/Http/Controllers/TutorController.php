<?php

namespace App\Http\Controllers;
use App\Events\Parent\ParentNotification;
use App\Events\Parent\SingleParentDashboard;
use App\Events\Tutor\TutorApproved;
use App\Events\Tutor\TutorNotification;
use App\Events\Tutor\TutorOffers;
use App\Events\Tutor\TutorVerified;
use App\Events\Tutor\TutorDashboard;
use App\Events\Parent\JobTicket;
use App\Mail\ParentApprovalEmail;
use App\Mail\OfferRejectedMail;
use App\Mail\TutorApprovalEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

use App\Libraries\PushNotificationLibrary;
use App\Models\Category;
use App\Models\Tutor;
use App\Models\ClassSchedules;
use App\Models\Tutorpayment;
use App\Models\State;
use App\Models\City;
use App\Mail\TutorPaymentSlip;
use App\Libraries\WhatsappApi;
use Barryvdh\DomPDF\Facade\Pdf;
use Pusher\Pusher;
use Redirect;
use Auth;
use App\Jobs\SendPushNotificationJob;
use Dirape\Token\Token;


class TutorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function checkTutorDuplicateEmail(Request $request)
    {

        $tables = ['customers', 'staffs', 'tutors', 'users'];
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

    public function checkDuplicateNric(Request $request)
    {

        $nric_check = DB::table("tutors")->where("nric", $request->nric)->first();

        if ($nric_check == null) {
            return response()->json(["recordFound" => false], 200);

        } else {
            return response()->json(["recordFound" => true], 200);
        }
    }

    public function checkTutorDuplicatePhone(Request $request)
    {
        $tables = ['customers', 'staffs', 'tutors', 'users'];

        $found = false;


        if (isset($request->tutorWhatsapp)) {
            foreach ($tables as $table) {

                $existingRecord = DB::table($table)
                    ->where('whatsapp', $request->tutorWhatsapp)
                    ->first();
                if ($existingRecord) {
                    $found = true;
                    break;
                }
            }
        } else {
            // If tutorWhatsApp is not set, use tutorMobile for search
            foreach ($tables as $table) {
                if ($table == "tutors") {
                    $existingRecord = DB::table($table)
                        ->where('phoneNumber', $request->tutorMobile)
                        ->first();
                } else {
                    $existingRecord = DB::table($table)
                        ->where('phone', $request->tutorMobile)
                        ->first();
                }

                if ($existingRecord) {
                    $found = true;
                    break;
                }
            }
        }

        // Return the response based on whether a record was found
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

    //urooj 4/23
    // public function index(Request $request)
    // {
    //     $id = Auth::user()->id;
    //     $checked = DB::table('loggedInUsers')->where('user_id', '=', $id)->where('last_login', '<>', date("Y-m-d H"))->where('pageUrl', '=', url()->full())->first();
    //     if (!$checked) {
    //         DB::table('loggedInUsers')->insert([
    //             'user_id' => $id,
    //             'last_login' => date('Y-m-d H:i:s'),
    //             'pageUrl' => url()->full(),
    //             'detail' => 'Tutor List'
    //         ]);
    //     }
    //     $tutor = Tutor::query();
    //     if ($request->filled(['fromDate', 'toDate', 'status', 'search'])) {
    //         $tutors = $tutor->whereBetween('fee_payment_date', [$request->fromDate, $request->toDate])
    //             ->where('status', $request->status)
    //             ->where('full_name', 'LIKE', '%' . $request->search . '%')
    //             ->get();
    //     } elseif ($request->filled(['fromDate', 'toDate', 'status'])) {
    //         $tutors = $tutor->whereBetween('fee_payment_date', [$request->fromDate, $request->toDate])
    //             ->where('status', $request->status)
    //             ->get();
    //     } elseif ($request->filled(['status', 'search'])) {
    //         $tutors = $tutor->where('status', $request->status)
    //             ->where('full_name', 'LIKE', '%' . $request->search . '%')
    //             ->get();
    //     } elseif ($request->filled(['fromDate', 'toDate'])) {
    //         $tutors = $tutor->whereBetween('fee_payment_date', [$request->fromDate, $request->toDate])
    //             ->get();
    //     } elseif ($request->filled('fromDate')) {
    //         $tutors = $tutor->whereDate('fee_payment_date', '>=', $request->fromDate)
    //             ->get();
    //     } elseif ($request->filled('toDate')) {
    //         $tutors = $tutor->whereDate('fee_payment_date', '<=', $request->toDate)
    //             ->get();
    //     } elseif ($request->filled('status')) {
    //         $tutors = $tutor->where('status', $request->status)
    //             ->get();
    //     } elseif ($request->filled('search')) {
    //         $tutors = $tutor->where('full_name', 'LIKE', '%' . $request->search . '%')
    //             ->get();
    //     } else {
    //         $tutors = $tutor->orderByDesc('id')
    //             ->get();
    //     }
    //     return view('tutor/index', Compact('tutors'));
    // }

    public function index(Request $request)
    {
        DB::table('user_activities')->insert([
            'user' => \Illuminate\Support\Facades\Auth::user()->name,
            'module' => "Tutor",
            'action' => "viewed Tutors list",
        ]);

        $id = Auth::user()->id;
        $checked = DB::table('loggedInUsers')->where('user_id', '=', $id)->where('last_login', '<>', date("Y-m-d H"))->where('pageUrl', '=', url()->full())->first();
        if (!$checked) {
            DB::table('loggedInUsers')->insert([
                'user_id' => $id,
                'last_login' => date('Y-m-d H:i:s'),
                'pageUrl' => url()->full(),
                'detail' => 'Tutor List'
            ]);
        }

        $tutor = Tutor::query();

        if ($request->filled('fromDate') && $request->filled('toDate')) {
            $tutor->whereBetween('fee_payment_date', [$request->fromDate, $request->toDate]);
        } elseif ($request->filled('fromDate')) {
            $tutor->whereDate('fee_payment_date', '>=', $request->fromDate);
        } elseif ($request->filled('toDate')) {
            $tutor->whereDate('fee_payment_date', '<=', $request->toDate);
        }

        if ($request->filled('status')) {
            $tutor->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $tutor->where('full_name', 'LIKE', '%' . $request->search . '%')
                ->orWhere('uid', 'LIKE', '%' . $request->search . '%');
        }

        $tutors = $tutor->orderByDesc('id')->get();

        return view('tutor.index', compact('tutors'));
    }


    public function tutorDetail($id)
    {

        $id = Auth::user()->id;
        $checked = DB::table('loggedInUsers')->where('user_id', '=', $id)->where('last_login', '<>', date("Y-m-d H"))->where('pageUrl', '=', url()->full())->first();
        if (!$checked) {
            DB::table('loggedInUsers')->insert([
                'user_id' => $id,
                'last_login' => date('Y-m-d H:i:s'),
                'pageUrl' => url()->full(),
                'detail' => 'Tutor Detail'
            ]);
        }

        $tutor = DB::table('tutors')->where('id', '=', $id)->first();
        $tutor_subjects = DB::table('tutor_subjects')->where('tutor_id', '=', $id)->get();
        return view('tutor/tutorDetail', Compact('tutor', 'tutor_subjects'));
    }


    public function sendTutorPaymentSlip($id)
    {
        $tutorPaymentID = $id;
        $tutor_payment = DB::table('tutorpayments')->where('id', '=', $id)->first();
        $tutor = DB::table('tutors')->where('id', '=', $tutor_payment->tutorID)->first();
        $paidClasses = DB::table('class_attendeds')->orderBy('id', 'ASC')->where('tutorPaymentID', '=', $id)->get();
        $additionals = DB::table('tutorPaymentAdditionals')->where('tutorPaymentsId', '=', $tutorPaymentID)->get();
        $deductions = DB::table('tutorPaymentDeductions')->where('tutorPaymentsId', '=', $tutorPaymentID)->get();

        $attendedDurationInSeconds = DB::table('class_attendeds')
            ->select(DB::raw('SUM(TIME_TO_SEC(totalTime)) AS totalSeconds'))
            ->where('tutorID', '=', $id)
            ->where('parent_verified', '=', 'YES')
            ->where('status', '=', 'attended')
            ->where('is_paid', '=', 'paid')
            ->where('is_tutor_paid', '=', 'paid')
            ->first()
            ->totalSeconds;

        Mail::to('binasift@gmail.com')->send(new TutorPaymentSlip($paidClasses, $tutor, $tutor_payment, $additionals, $deductions));
        Mail::to($tutor->email)->send(new TutorPaymentSlip($paidClasses, $tutor, $tutor_payment, $additionals, $deductions));

        return redirect()->back()->with("success", "Email sent successfully");
    }

    public function downloadTutorPaymentSlip($id)
    {

        $tutorPaymentID = $id;
        $tutor_payment = DB::table('tutorpayments')->where('id', '=', $id)->first();
        $tutor = DB::table('tutors')->where('id', '=', $tutor_payment->tutorID)->first();
        $tutor_subjects = DB::table('tutor_subjects')->where('tutor_id', '=', $tutor_payment->tutorID)->get();

        $job_tickets = DB::table('job_tickets')->where('tutor_id', '=', $tutor_payment->tutorID)->first();
        $student_subjects = DB::table('student_subjects')->where('tutor_id', '=', $tutor_payment->tutorID)->get();
        $subjects = DB::table('tutor_subjects')->where('tutor_id', '=', $tutor_payment->tutorID)->get();
        $allSubjects = DB::table('products')->get();
        $paidClasses = DB::table('class_attendeds')->orderBy('id', 'ASC')->where('tutorPaymentID', '=', $id)->get();
        $totalCommission = DB::table('class_attendeds')->orderBy('id', 'ASC')->where('tutorPaymentID', '=', $id)->sum("commission");


        $additionals = DB::table('tutorPaymentAdditionals')->where('tutorPaymentsId', '=', $tutorPaymentID)->get();
        $deductions = DB::table('tutorPaymentDeductions')->where('tutorPaymentsId', '=', $tutorPaymentID)->get();


        $attendedDurationInSeconds = DB::table('class_attendeds')
            ->select(DB::raw('SUM(TIME_TO_SEC(totalTime)) AS totalSeconds'))
            ->where('tutorID', '=', $id)
            ->where('parent_verified', '=', 'YES')
            ->where('status', '=', 'attended')
            ->where('is_paid', '=', 'paid')
            ->where('is_tutor_paid', '=', 'paid')
            ->first()
            ->totalSeconds;

        // Convert the total duration from seconds to the desired format (HH:MM:SS)
        $attendedDuration = gmdate('H:i:s', $attendedDurationInSeconds);


        $file = public_path() . "/tutorPaymentSlipPDF" . "/" . "tutor-Payment-Slip-" . $tutor_payment->id . ".pdf";

        if (file_exists($file)) {
            $headers = array('Content-Type: application/pdf',);
            return Response::download($file, "staff-Payment-Slip-" . $tutor_payment->id, $headers);
        } else {
            $pdf = PDF::loadView('tutor.tutorPaymentSlipPDF', [
                'paidClasses' => $paidClasses,
                'tutor' => $tutor,
                'tutor_payment' => $tutor_payment,
                'additionals' => $additionals,
                'deductions' => $deductions,

            ]);
            $pdf->save(public_path('staffPaymentSlipPDF') . "/" . "staff-Payment-Slip-" . $tutor_payment->id . ".pdf");
            $file = public_path() . "/staffPaymentSlipPDF" . "/" . "staff-Payment-Slip-" . $tutor_payment->id . ".pdf";
            $headers = array('Content-Type: application/pdf',);
            return Response::download($file, "tutor-Payment-Slip-" . $tutor_payment->id, $headers);
        }


        return view('tutor/downloadPaymentSlip', Compact('tutor', 'tutor_payment', 'additionals', 'attendedDuration', 'totalCommission', 'deductions', 'tutorPaymentID', 'paidClasses', 'tutor_subjects', 'tutor_payment', 'job_tickets', 'student_subjects', 'subjects', 'allSubjects'));
    }

    public function tutorPaymentSlip($id)
    {
        $tutorPaymentID = $id;
        $tutor_payment = DB::table('tutorpayments')->where('id', '=', $id)->first();
        $tutor = DB::table('tutors')->where('id', '=', $tutor_payment->tutorID)->first();
        $tutor_subjects = DB::table('tutor_subjects')->where('tutor_id', '=', $tutor_payment->tutorID)->get();

        $job_tickets = DB::table('job_tickets')->where('tutor_id', '=', $tutor_payment->tutorID)->first();
        $student_subjects = DB::table('student_subjects')->where('tutor_id', '=', $tutor_payment->tutorID)->get();
        $subjects = DB::table('tutor_subjects')->where('tutor_id', '=', $tutor_payment->tutorID)->get();
        $allSubjects = DB::table('products')->get();
        $paidClasses = DB::table('class_attendeds')->orderBy('id', 'ASC')->where('tutorPaymentID', '=', $id)->get();
        $totalCommission = DB::table('class_attendeds')->orderBy('id', 'ASC')->where('tutorPaymentID', '=', $id)->sum("commission");


        $additionals = DB::table('tutorPaymentAdditionals')->where('tutorPaymentsId', '=', $tutorPaymentID)->get();
        $deductions = DB::table('tutorPaymentDeductions')->where('tutorPaymentsId', '=', $tutorPaymentID)->get();


        $attendedDurationInSeconds = DB::table('class_attendeds')
            ->select(DB::raw('SUM(TIME_TO_SEC(totalTime)) AS totalSeconds'))
            ->where('tutorID', '=', $id)
            ->where('parent_verified', '=', 'YES')
            ->where('status', '=', 'attended')
            ->where('is_paid', '=', 'paid')
            ->where('is_tutor_paid', '=', 'paid')
            ->first()
            ->totalSeconds;

        // Convert the total duration from seconds to the desired format (HH:MM:SS)
        $attendedDuration = gmdate('H:i:s', $attendedDurationInSeconds);

        $additionalsSum = DB::table('tutorPaymentAdditionals')->where('tutorPaymentsId', '=', $tutorPaymentID)->sum("amount");
        $deductionsSum = DB::table('tutorPaymentDeductions')->where('tutorPaymentsId', '=', $tutorPaymentID)->sum("amount");


        return view('tutor/tutorPaymentSlip', Compact('tutor', 'tutor_payment', 'additionals', 'attendedDuration', 'totalCommission',
            'deductionsSum', 'additionalsSum',

            'deductions', 'tutorPaymentID', 'paidClasses', 'tutor_subjects', 'tutor_payment', 'job_tickets', 'student_subjects', 'subjects', 'allSubjects'));
    }

    // public function TutorScheduleCalendar(Request $request)
    // {
    //     dd($request->all());

    //     $tutors = DB::table('tutors')->get();

    //     $subjects = DB::table('products')->get();

    //     $job_tickets = DB::table('class_schedules')

    //      ->leftjoin("products","class_schedules.subjectID","=","products.id")
    //      ->leftjoin("categories","products.category","=","categories.id")
    //      ->select("class_schedules.*","products.name as subject_name",
    //             "categories.category_name as category_name",
    //             "categories.mode as mode")->orderBy('class_schedules.id','DESC')->get();

    //     // if($request->search != null){
    //     //     $tutorPayments = $tutorPayments->where('tutorID','=',$tutorID->id);
    //     // }
    //     // if($request->SelectedMonth != null){
    //     //     $tutorPayments = $tutorPayments->where('comissionMonth','=',$request->SelectedMonth);
    //     // }

    //     // if($request->SelectedYear != null){
    //     //     $tutorPayments = $tutorPayments->where('comissionYear','=',$request->SelectedMonth);
    //     // }
    //     // $tutorPayments = $tutorPayments->orderBy('id','DESC')->get();

    //     // $job_tickets = $job_tickets->orderBy('id','DESC')->get();

    //     $jsonticket = $job_tickets;

    //     return view('tutor/TutorScheduleCalendar', Compact('tutors','jsonticket','subjects'));
    // }


    public function TutorScheduleCalendar(Request $request)
    {


        // dd($request->all());

        $tutorSearch = $request->tutors;
        $subjectsSearch = $request->subjects;


        $job_tickets = DB::table('class_schedules')
            ->leftJoin("products", "class_schedules.subjectID", "=", "products.id")
            ->leftJoin("categories", "products.category", "=", "categories.id")
            ->select(
                "class_schedules.*",
                "products.name as subject_name",
                "categories.category_name as category_name",
                "categories.mode as mode"
            )
            ->orderBy('class_schedules.id', 'DESC')->get();


        if ($tutorSearch != null && $subjectsSearch != null) {


            $job_tickets = DB::table('class_schedules')
                ->leftJoin("products", "class_schedules.subjectID", "=", "products.id")
                ->leftJoin("categories", "products.category", "=", "categories.id")
                ->select(
                    "class_schedules.*",
                    "products.name as subject_name",
                    "categories.category_name as category_name",
                    "categories.mode as mode"
                )
                ->where('class_schedules.tutorID', $tutorSearch)
                ->where('class_schedules.subjectID', $subjectsSearch)
                ->orderBy('class_schedules.id', 'DESC')->get();
        } else if ($tutorSearch != null) {


            $job_tickets = DB::table('class_schedules')
                ->leftJoin("products", "class_schedules.subjectID", "=", "products.id")
                ->leftJoin("categories", "products.category", "=", "categories.id")
                ->select(
                    "class_schedules.*",
                    "products.name as subject_name",
                    "categories.category_name as category_name",
                    "categories.mode as mode"
                )
                ->where('class_schedules.tutorID', $tutorSearch)
                ->orderBy('class_schedules.id', 'DESC')->get();
        } else if ($subjectsSearch != null) {

            $job_tickets = DB::table('class_schedules')
                ->leftJoin("products", "class_schedules.subjectID", "=", "products.id")
                ->leftJoin("categories", "products.category", "=", "categories.id")
                ->select(
                    "class_schedules.*",
                    "products.name as subject_name",
                    "categories.category_name as category_name",
                    "categories.mode as mode"
                )
                ->where('class_schedules.subjectID', $subjectsSearch)
                ->orderBy('class_schedules.id', 'DESC')->get();
        }


        $jsonticket = $job_tickets;


        $tutors = DB::table('tutors')->where("email", "!=", null)->get();
        $subjects = DB::table('products')->join("categories", "products.category", "=", "categories.id")
            ->select(
                "products.*",
                "categories.category_name as category_name",
                "categories.mode as mode"
            )->get();


        return view('tutor/TutorScheduleCalendar', Compact('tutors', 'jsonticket', 'subjects', 'tutorSearch', 'subjectsSearch'));
    }

    public function TutorReports()
    {
        //
        $tutors = DB::table('tutors')->get();
        $tutorSubmittedReports = DB::table('tutorSubmittedReports')->get();
        return view('tutor/TutorReports', Compact('tutors', 'tutorSubmittedReports'));
    }

    public function addTutorReport()
    {
        //
        $tutors = DB::table('tutors')->get();
        $students = DB::table('students')->get();
        return view('tutor/addTutorReport', Compact('tutors', 'students'));
    }

    public function editTutorReport($id)
    {
        //
        $tutors = DB::table('tutors')->get();
        $students = DB::table('students')->get();
        return view('tutor/editTutorReport', Compact('tutors', 'students'));
    }

    public function submitTutorReport(Request $request)
    {

        $imageName = time() . '.' . $request->reportFile->extension();
        $request->reportFile->move(public_path('reportFile'), $imageName);

        /* Store $imageName name in DATABASE from HERE */


        $tutorSubmittedReportValues = array(
            'tutorID' => $request->tutorID,
            'studentID' => $request->studentID,
            'date' => $request->date,
            'reportType' => $request->reportType,
            'reportFileName' => $imageName,
            'remarks' => $request->remarks
        );

        $tutorSubmittedReportID = DB::table('tutorSubmittedReports')->insertGetId($tutorSubmittedReportValues);


        return redirect('TutorReports')
            ->with('success', 'You have successfully Submited Report.')
            ->with('File', $imageName);


        dd($request);
        die();
        //
        // $tutors = DB::table('tutors')->get();
        // $students = DB::table('students')->get();
        // return view('tutor/addTutorReport', Compact('tutors','students'));
    }


    // public function TutorReportsV2(Request $request)
    // {
    //     //
    //     $tutorReport = DB::table('tutorFirstSubmittedReportFromApps');

    //     if ($request->search != null) {
    //         $tutorID = DB::table('tutors')->where('uid', '=', $request->search)->first();
    //         $tutorReport = $tutorReport->where('tutorFirstSubmittedReportFromApps.tutorID', '=', $tutorID->id);
    //     }
    //     // if ($request->Month != null) {
    //     //     $tutorReport = $tutorReport->where('comissionMonth', '=', $request->SelectedMonth);
    //     // }

    //     // if ($request->Year != null) {
    //     //     $tutorReport = $tutorReport->where('comissionYear', '=', $request->SelectedMonth);
    //     // }


    //     $tutorReport = $tutorReport->join('students', 'tutorFirstSubmittedReportFromApps.studentID', '=', 'students.id')
    //         ->join('tutors', 'tutorFirstSubmittedReportFromApps.tutorID', '=', 'tutors.id')
    //         ->join('products', 'tutorFirstSubmittedReportFromApps.subjectID', '=', 'products.id')
    //         ->select(
    //             'tutorFirstSubmittedReportFromApps.id as id',
    //             'tutorFirstSubmittedReportFromApps.reportType as reportType',
    //             'tutorFirstSubmittedReportFromApps.currentDate as Month',
    //             'tutorFirstSubmittedReportFromApps.knowledge as knowledge',
    //             'tutorFirstSubmittedReportFromApps.understanding as understanding',
    //             'tutorFirstSubmittedReportFromApps.analysis as analysis',
    //             'tutorFirstSubmittedReportFromApps.additionalAssisment as additionalAssisment',
    //             'tutorFirstSubmittedReportFromApps.plan as plan',
    //             'tutors.uid as tutorID',
    //             'tutors.full_name as tutorName',
    //             'tutors.street_address1 as tutorAddress1',
    //             'tutors.street_address2 as tutorAddress2',
    //             'tutors.city as tutorCity',
    //             'products.name as subjectName',
    //             'students.uid as studentID',
    //             'students.full_name as studentName',
    //             'students.address1 as studentAddress1',
    //             'students.address2 as studentAddress2',
    //             'students.city as studentCity',
    //             'tutorFirstSubmittedReportFromApps.created_at as created_at'
    //         )->
    //         orderBy("tutorFirstSubmittedReportFromApps.id", "desc")
    //         ->get();

    //     return view('tutor/tutorReportsV2', Compact('tutorReport'));
    // }

    public function TutorReportsV2(Request $request)
    {
        $tutorReport = DB::table('tutorFirstSubmittedReportFromApps');

        // Search by tutor UID
        if ($request->filled('search')) {
            $tutorID = DB::table('tutors')->where('uid', '=', $request->search)->first();
            if ($tutorID) {
                $tutorReport = $tutorReport->where('tutorFirstSubmittedReportFromApps.tutorID', '=', $tutorID->id);
            } else {
                // If no tutor found, return empty result
                return view('tutor/tutorReportsV2', ['tutorReport' => []]);
            }
        }

        // Filter by month
        if ($request->filled('Month')) {
            $tutorReport = $tutorReport->whereMonth('tutorFirstSubmittedReportFromApps.currentDate', '=', date('m', strtotime($request->Month)));
        }

        // Filter by year
        if ($request->filled('Year')) {
            $tutorReport = $tutorReport->whereYear('tutorFirstSubmittedReportFromApps.currentDate', '=', $request->Year);
        }

        // Filter by report type
        if ($request->filled('ReportType')) {
            $tutorReport = $tutorReport->where('tutorFirstSubmittedReportFromApps.reportType', '=', $request->ReportType);
        }

        // Joining related tables
        $tutorReport = $tutorReport->join('students', 'tutorFirstSubmittedReportFromApps.studentID', '=', 'students.id')
            ->join('tutors', 'tutorFirstSubmittedReportFromApps.tutorID', '=', 'tutors.id')
            ->join('products', 'tutorFirstSubmittedReportFromApps.subjectID', '=', 'products.id')
            ->select(
                'tutorFirstSubmittedReportFromApps.id as id',
                'tutorFirstSubmittedReportFromApps.reportType as reportType',
                'tutorFirstSubmittedReportFromApps.currentDate as Month',
                'tutorFirstSubmittedReportFromApps.knowledge as knowledge',
                'tutorFirstSubmittedReportFromApps.understanding as understanding',
                'tutorFirstSubmittedReportFromApps.analysis as analysis',
                'tutorFirstSubmittedReportFromApps.additionalAssisment as additionalAssisment',
                'tutorFirstSubmittedReportFromApps.plan as plan',
                'tutors.uid as tutorID',
                'tutors.full_name as tutorName',
                'tutors.street_address1 as tutorAddress1',
                'tutors.street_address2 as tutorAddress2',
                'tutors.city as tutorCity',
                'products.name as subjectName',
                'students.uid as studentID',
                'students.full_name as studentName',
                'students.address1 as studentAddress1',
                'students.address2 as studentAddress2',
                'students.city as studentCity',
                'tutorFirstSubmittedReportFromApps.created_at as created_at'
            )
            ->orderBy("tutorFirstSubmittedReportFromApps.id", "desc")
            ->get();

        return view('tutor/tutorReportsV2', compact('tutorReport'));
    }

    public function progressReports(Request $request)
    {
        //
        $tutorReport = DB::table('progressReport');

        if ($request->search != null) {
            $tutorID = DB::table('tutors')->where('uid', '=', $request->search)->first();
            $tutorReport = $tutorReport->where('progressReport.tutorID', '=', $tutorID->id);
        }


        $tutorReport = $tutorReport->join('students', 'progressReport.studentID', '=', 'students.id')
            ->join('tutors', 'progressReport.tutorID', '=', 'tutors.id')
            ->join('products', 'progressReport.subjectID', '=', 'products.id')
            ->select(
                'progressReport.*',

                'tutors.uid as tutorID',
                'tutors.full_name as tutorName',
                'tutors.street_address1 as tutorAddress1',
                'tutors.street_address2 as tutorAddress2',
                'tutors.city as tutorCity',
                'products.name as subjectName',
                'students.uid as studentID',
                'students.full_name as studentName',
                'students.address1 as studentAddress1',
                'students.address2 as studentAddress2',
                'students.city as studentCity',
                'progressReport.created_at as created_at'
            )->orderBy("progressReport.id", "desc")
            ->get();

        return view('tutor/progressReports', Compact('tutorReport'));
    }

    public function TutorReportsV2View($id)
    {

        $tutorReport = DB::table('tutorFirstSubmittedReportFromApps')
            ->join('students', 'tutorFirstSubmittedReportFromApps.studentID', '=', 'students.id')
            ->join('tutors', 'tutorFirstSubmittedReportFromApps.tutorID', '=', 'tutors.id')
            ->join('products', 'tutorFirstSubmittedReportFromApps.subjectID', '=', 'products.id')
            ->where('tutorFirstSubmittedReportFromApps.id', '=', $id)
            ->select(
                'tutorFirstSubmittedReportFromApps.*',
                'tutors.uid as tutorID',
                'tutors.full_name as tutorName',
                'tutors.street_address1 as tutorAddress1',
                'tutors.street_address2 as tutorAddress2',
                'tutors.city as tutorCity',
                'products.name as subjectName',
                'students.uid as studentID',
                'students.full_name as studentName',
                'students.address1 as studentAddress1',
                'students.address2 as studentAddress2',
                'students.city as studentCity',
                'tutorFirstSubmittedReportFromApps.created_at as created_at'
            )
            ->first();


        return view('tutor/tutorReportsV2View', Compact('tutorReport'));
    }

    public function progressReportsView($id)
    {

        $tutorReport = DB::table('progressReport')
            ->join('students', 'progressReport.studentID', '=', 'students.id')
            ->join('tutors', 'progressReport.tutorID', '=', 'tutors.id')
            ->join('products', 'progressReport.subjectID', '=', 'products.id')
            ->where('progressReport.id', '=', $id)
            ->select(
                'progressReport.*',
                'tutors.uid as tutorID',
                'tutors.full_name as tutorName',
                'tutors.street_address1 as tutorAddress1',
                'tutors.street_address2 as tutorAddress2',
                'tutors.city as tutorCity',
                'products.name as subjectName',
                'students.uid as studentID',
                'students.full_name as studentName',
                'students.address1 as studentAddress1',
                'students.address2 as studentAddress2',
                'students.city as studentCity',
                'progressReport.created_at as created_at'
            )
            ->first();


        return view('tutor/progressreport', Compact('tutorReport'));
    }


    public function TutorAssignments()
    {
        //
        $tutors = DB::table('tutors')->get();
        return view('tutor/TutorAssignments', Compact('tutors'));
    }


    public function TutorPayments(Request $request)
    {
        $tutorPayments = Tutorpayment::query();

        if ($request->filled('search')) {
            $tutorPayments->whereHas('tutor', function ($query) use ($request) {
                $query->where('full_name', 'LIKE', '%' . $request->search . '%');
            });
        } elseif ($request->filled('SelectedYear')) {
            $tutorPayments->where('comissionYear', $request->SelectedYear);
        } elseif ($request->filled('SelectedMonth')) {
            $tutorPayments->where('comissionMonth', $request->SelectedMonth);
        }


        $tutorPayments = $tutorPayments->with('tutor')->orderBy("id", "desc")->get();


        return view('tutor.tutorPayments', compact('tutorPayments'));
    }


    public function TutorPaymentJournal(Request $request)
    {
        //

        // $tutorpayments = DB::table('tutorpayments');

        // if ($request->search != null) {
        //     $tutorID = DB::table('tutors')->where('full_name', '=', $request->search)->first();
        //     $tutorpayments = $tutorpayments->where('tutorID', '=', $tutorID->id);
        // }
        // if ($request->fromDate != null) {
        //     $tutorpayments = $tutorpayments->where('tutorpayments.created_at', '>=', $request->fromDate);
        // }
        // if ($request->month != null) {
        //     $tutorpayments = $tutorpayments->where('tutorpayments.month', '>=', $request->month);
        // }

        // if ($request->toDate != null) {
        //     $tutorpayments = $tutorpayments->where('tutorpayments.created_at', '<=', $request->toDate);
        // }

        // $tutorpayments = $tutorpayments->join('tutors', 'tutors.id', '=', 'tutorpayments.tutorID')
        //     ->select('tutors.*', 'tutorpayments.*')
        //     ->get();

        // $tutorpayments = Tutorpayment::query();
        // dd($request->all());

        if ($request->month != null) {
            $currentMonthNumber = $request->month;
        } else {
            $currentMonthNumber = date('n');
        }

        $query = ClassSchedules::join('tutors', 'class_schedules.tutorID', '=', 'tutors.id')
            ->leftJoin('class_attendeds', 'class_attendeds.class_schedule_id', '=', 'class_schedules.id')
            ->select(
                'class_schedules.tutorID',
                'tutors.full_name as tutor_name',
                'tutors.uid as tutor_uid',
                \DB::raw('MONTHNAME(class_schedules.created_at) as month'),
                \DB::raw('YEAR(class_schedules.created_at) as year'),
                \DB::raw('SUM(class_schedules.totalTime) as total_duration'),
                \DB::raw("SUM(CASE WHEN class_attendeds.status='attended' THEN class_attendeds.commission ELSE 0 END) as total_amount"),
                \DB::raw("SUM(CASE WHEN class_attendeds.status='attended' THEN class_attendeds.totalTime ELSE 0 END) as total_invoice_duration"),
                \DB::raw("DATE_FORMAT(class_attendeds.created_at, '%d/%b/%Y') as completion_date"),
                \DB::raw("COUNT(CASE WHEN class_attendeds.status = 'attended' THEN 1 END) as attended_count"),
                \DB::raw("COUNT(CASE WHEN class_attendeds.status = 'pending' THEN 1 END) as pending_count")
            )
            ->where([
                ['class_attendeds.is_paid', '=', 'unpaid'],
                ['is_tutor_paid', '=', 'unpaid']
            ]);

        if ($currentMonthNumber != 13) {
            $query->whereRaw("MONTH(class_schedules.created_at) = ?", [$currentMonthNumber]);
        }

        // Apply validation status filter
        if ($request->validation_status && $request->validation_status != 'all') {
            switch ($request->validation_status) {
                case 'fully_verified':
                    $query->having('pending_count', '=', 0);
                    break;
                case 'partial_verified':
                    $query->having('pending_count', '>', 0);
                    break;
            }
        }

        // Apply processing status filter
        if ($request->processing_status && $request->processing_status != 'all') {
            switch ($request->processing_status) {
                case 'ready':
                    $query->having('pending_count', '=', 0);
                    break;
                case 'pending':
                    $query->having('pending_count', '>', 0);
                    break;
                case 'processing':
                    $query->having('pending_count', '>', 0)
                        ->having('attended_count', '>', 0);
                    break;
            }
        }

        // Apply tutor name search filter
        if ($request->search) {
            $query->where('tutors.full_name', 'like', '%' . $request->search . '%');
        }

        $tutorpayments = $query->groupBy(
            'class_schedules.tutorID',
            'tutors.full_name',
            'tutors.uid',
            \DB::raw('MONTH(class_schedules.created_at)'),
            \DB::raw('YEAR(class_schedules.created_at)')
        )->get();


        return view('tutor/TutorPaymentJournal', Compact('tutorpayments', 'currentMonthNumber'));
    }

    public function viewTutorPaymentJournalBreakdown($id)
    {

        $tutorID = $id;
        $tutor = DB::table('tutors')->where('id', '=', $id)->first();
        $tutors = DB::table('tutors')->get();
        $job_tickets = DB::table('job_tickets')->where('tutor_id', '=', $id)->first();

        $student_subjects = DB::table('student_subjects')->where('tutor_id', '=', $id)->get();

        $class_attended = DB::table('class_attendeds')->
        where('tutorID', '=', $id)->
        where('status', '=', 'attended')->
        where('is_paid', '=', 'unpaid')->
        where('is_tutor_paid', '=', 'unpaid')->get();

        $totalCommission = DB::table('class_attendeds')->
        where('tutorID', '=', $id)->where('status', '=', 'attended')->
        where('is_paid', '=', 'unpaid')->
        where('is_tutor_paid', '=', 'unpaid')->
        sum('commission');


        $attendedDurationInSeconds = DB::table('class_attendeds')
            ->select(DB::raw('SUM(TIME_TO_SEC(totalTime)) AS totalSeconds'))
            ->where('tutorID', '=', $id)
            ->where('parent_verified', '=', 'YES')
            ->where('status', '=', 'attended')
            ->where('is_paid', '=', 'unpaid')
            ->where('is_tutor_paid', '=', 'unpaid')
            ->first()
            ->totalSeconds;


        if ($attendedDurationInSeconds == null) {
            $attendedDuration = 0;
        } else {

            $attendedDuration = (gmdate('H:i:s', $attendedDurationInSeconds));
        }
        // Convert the total duration from seconds to the desired format (HH:MM:SS)


        $subjects = DB::table('tutor_subjects')->where('tutor_id', '=', $id)->get();

        $allSubjects = DB::table('products')->get();
        $totalTime = DB::table('class_schedules')->where('tutorID', '=', $id)->where('is_paid', '=', 0)->orderBy('id', 'DESC')->sum('totalTime');
        $currentMonthName = date('F');
        $currentYear = date('Y');

        return view('tutor/viewTutorPaymentJournal', Compact('subjects', 'currentYear', 'currentMonthName', 'attendedDuration', 'totalCommission', 'tutorID', 'tutors', 'class_attended', 'tutor', 'allSubjects', 'job_tickets', 'student_subjects', 'totalTime'));
    }

    public function TutorFinder(Request $req)
    {
        $tutor = Tutor::query();

        // Search by full_name or email
        if ($req->filled('searchQuery')) {
            $searchQuery = $req->searchQuery;
            $tutor->where(function ($query) use ($searchQuery) {
                $query->where('full_name', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('email', 'LIKE', "%{$searchQuery}%");
            });
        }

        // Filter by subject and category through direct query on class_schedules
        if ($req->filled('subject') || $req->filled('category')) {
            $tutor->whereIn('id', function ($query) use ($req) {
                $query->select('tutorID')
                    ->from('class_schedules')
                    ->when($req->filled('subject'), function ($query) use ($req) {
                        $query->where('subjectID', $req->subject);
                    })
                    ->when($req->filled('category'), function ($query) use ($req) {
                        $query->whereIn('subjectID', function ($query) use ($req) {
                            $query->select('id')
                                ->from('products')
                                ->where('category', $req->category);
                        });
                    });
            });
        }

        if ($req->filled('state')) {
            $tutor->where('state', $req->state);
        }

        if ($req->filled('city')) {
            $tutor->where('city', $req->city);
        }

        if ($req->filled('status')) {
            $tutor->where('status', $req->status);
        }

        $tutors = $tutor->orderByDesc('id')->distinct()->get();

        $states = State::get();
        $cities = City::get();
        $subjects = DB::table('products')
            ->join("categories", "products.category", "=", "categories.id")
            ->select("products.*", "categories.category_name as category_name", "categories.mode as mode")
            ->get();

        $categories = Category::distinct("category_name")->get();

        $levels = Category::select('category_name')
            ->groupBy('category_name')
            ->get();

        return view('tutor/TutorFinder', compact('tutors', 'subjects', 'states', 'cities', 'levels', 'categories'));
    }

    public function addTutor()
    {
        //
        $allSubjects = DB::table('products')->join("categories", "products.category", "=", "categories.id")
            ->select("products.*", "categories.price as category_price", "categories.category_name as category_name", "categories.mode as mode")->get();


        $states = DB::table("states")->get();
        $categories = DB::table("categories")->get();

        return view('tutor/addTutor', Compact('allSubjects', 'states', 'categories'));
    }

    public function viewTutor($id)
    {
        $tutor = DB::table('tutors')->leftjoin("states", "tutors.state", "=", "states.id")
            ->leftjoin("cities", "tutors.city", "=", "cities.id")
            ->select("tutors.*", "states.name as state", "cities.name as city")
            ->where('tutors.id', '=', $id)->first();


        $subjects = DB::table('tutor_subjects')->where('tutor_id', '=', $id)->get();
        $totalSubjects = DB::table('tutor_subjects')->where('tutor_id', '=', $id)->orderBy('id', 'desc')->count();
        $allSubjects = DB::table('products')->get();
        $commitment_fee = DB::table('tutor_commitment_fees')->where('tutor_id', '=', $id)->first();

        $bio_details = DB::table('bio_details')->where('tutor_id', '=', $id)->first();
        $service_preferences = DB::table('service_preferences')->where('tutor_id', '=', $id)->first();

        $emergency_contacts = DB::table('emergency_contacts')->where('tutor_id', '=', $id)->first();
        $educations = DB::table('educations')->where('tutor_id', '=', $id)->first();
        $documents = DB::table('documents')->where('tutor_id', '=', $id)->first();


        // dd($emergency_contacts);

        return view('tutor/viewTutor', Compact('subjects', 'tutor', 'allSubjects', 'totalSubjects', 'bio_details', 'service_preferences', 'emergency_contacts',
            'educations', 'documents'));
    }

    public function editTutor($id)
    {
        //
        $tutor = DB::table('tutors')->where('id', '=', $id)->first();
        $subjects = DB::table('tutor_subjects')->where('tutor_id', '=', $id)->get();
        $totalSubjects = DB::table('tutor_subjects')->where('tutor_id', '=', $id)->orderBy('id', 'desc')->count();
        $allSubjects = DB::table('products')->get();

        $servicePreferences = DB::table('service_preferences')->where('tutor_id', $id)->first();
        $bioDetails = DB::table('bio_details')->where('tutor_id', $id)->first();
        $emergencyContact = DB::table('emergency_contacts')->where('tutor_id', $id)->first();
        $education = DB::table('educations')->where('tutor_id', $id)->first();
        $documents = DB::table('documents')->where('tutor_id', $id)->first();
        $declaration = DB::table('declarations')->where('tutor_id', $id)->first();


        $categories = DB::table("categories")->groupBy('category_name')->get();



         $selectedCategories = $servicePreferences?explode(', ', $servicePreferences->category):"";
        //  dd($selectedCategories);


        // dd($emergencyContact);


        //  dd(explode(', ', $servicePreferences->category));

        // dd(explode(', ', $servicePreferences->preferable_location));
        $cities = DB::table('cities')->get();
        $states = DB::table('states')->get();

        $cityDetail = DB::table('cities')->where('id','=',$tutor->city)->first();
        $stateDetail = DB::table('states')->where('id','=',$tutor->state)->first();

        return view('tutor/editTutor', Compact('subjects', 'tutor', 'allSubjects', 'totalSubjects', 'servicePreferences', 'bioDetails', 'emergencyContact', 'education',
            'categories',
            'documents',
            'declaration','cities','states','cityDetail','stateDetail','selectedCategories'

        ));
    }

    public function tutoOfferActionApprove($id)
    {
        $tutorOffer = DB::table('tutoroffers')->where('id', '=', $id)->first();
        $studentID = DB::table('job_tickets')->where('id', '=', $tutorOffer->ticketID)->first();
        $ticket = DB::table("job_tickets")->find($tutorOffer->ticketID);
        DB::table('tutoroffers')
            ->where('id', $id)
            ->update(['status' => 'approved']);

        DB::table('tutoroffers')
            ->where('ticketID', $tutorOffer->ticketID)
            ->where('status', '!=', 'approved')
            ->update(['status' => 'rejected']);

        $tutorDetail = DB::table('tutoroffers')->where('id', $id)->first();

        $rejected_tutors = DB::table('tutoroffers')
            ->where('ticketID', $tutorOffer->ticketID)
            ->where('status', '!=', 'approved')->get(["tutorID"]);

        $rejectedTutorIDs = $rejected_tutors->pluck('tutorID');


        $tutorDevices = DB::table('tutor_device_tokens')
            ->whereIn('tutor_id', $rejectedTutorIDs)
            ->distinct()
            ->get(['device_token', 'tutor_id']);


        $tutorDeviceApproved = DB::table('tutor_device_tokens')
            ->where('tutor_id', $tutorOffer->tutorID)
            ->first();

        $tutor = DB::table('tutors')->where('id', '=', $tutorOffer->tutorID)->first();
        $studentDetail = DB::table('students')->where('id', '=', $ticket->student_id)->first();
        $subjectName = DB::table('products')->where('id', '=', $ticket->subjects)->value('name');
        $approvalDate = now()->format('d F Y, h:i A');
        
        $push_notification_api = new PushNotificationLibrary();
        $title = 'Job Ticket Application Status (Successful)';
        $message = 'You’re hired! Check out the details for ' . $ticket->uid;
        if (isset($tutorDeviceApproved->device_token)) {
            $deviceToken = $tutorDeviceApproved->device_token;

             $notificationdata = [
                    'id' => $ticket->uid,
                    'Sender' => 'jobTicket'
                ];
            
                // Dispatch push notification job
                SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
            
                // Store notification in the database
                DB::table('notifications')->insert([
                    'page' => 'jobTicket',
                    'token' => $tutor->token,
                    'title' => $title,
                    'message' => $message,
                    'type' => 'tutor',
                    'status' => 'new',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

            // dd($deviceToken);
        }
        
        // Send email to the approved tutor
        try {
            $emailData = [
                'tutorName' => $tutor->full_name,
                'studentName' => $studentDetail->full_name,
                'subjectName' => $subjectName,
                'approvalDate' => $approvalDate,
            ];
    
            Mail::to($tutor->email)->send(new TutorApprovalEmail($emailData));
        } catch (\Exception $e) {
            \Log::error('Failed to send approval email to tutor: ' . $e->getMessage());
        }

        foreach ($tutorDevices as $rowDeviceToken) {
            $tutor = DB::table('tutors')->where('id', '=', $rowDeviceToken->tutor_id)->first();
            $push_notification_api = new PushNotificationLibrary();
            $title = 'Job Ticket Application Status (Unsuccessful)';
            $message = 'Bummer! You didn’t get the job for ' . $ticket->uid . '. Try for the next one!';
            if (isset($rowDeviceToken->device_token)) {
                $deviceToken = $rowDeviceToken->device_token;

                $notificationdata = [
                    'id' => $ticket->uid,
                    'Sender' => 'jobTicket'
                ];
            
                // Dispatch push notification job
                SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
            
                // Store notification in the database
                DB::table('notifications')->insert([
                    'page' => 'jobTicket',
                    'token' => $tutor->token,
                    'title' => $title,
                    'message' => $message,
                    'type' => 'tutor',
                    'status' => 'new',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

            }
            
            // Prepare email data
            $emailData = [
                'tutorName' => $tutor->full_name,
            ];
        
            // Send email to the tutor
            try {
                Mail::to($tutor->email)->send(new OfferRejectedMail($emailData));
            } catch (\Exception $e) {
                return response()->json([
                    "ResponseCode" => "103",
                    "error" => "Failed to send rejection email: " . $e->getMessage(),
                ]);
            }

        }
        
        $studentDetail = DB::table('students')->where('id', '=', $ticket->student_id)->first();
        $customerDetail = DB::table('customers')->where('id', '=', $studentDetail->customer_id)->first();

        // Send push notification to parent devices
        $parent_device_tokens = DB::table('parent_device_tokens')->where('parent_id', '=', $customerDetail->id)->distinct()->get(['device_token', 'parent_id']);
        foreach ($parent_device_tokens as $token) {
            $push_notification_api = new PushNotificationLibrary();
            $deviceToken = $token->device_token;
            $title = 'Pay Commitment Fee';
            $message = 'Pay RM50 commitment fee for  '. $tutor->full_name .' to confirm the class.';
        
            $notificationdata = [
                'id' => $ticket->uid,
                'Sender' => 'jobTicket'
            ];
        
            // Dispatch push notification job
            SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
        
            // Store notification in the database
            DB::table('notifications')->insert([
                'page' => 'jobTicket',
                'token' => $customerDetail->token,
                'title' => $title,
                'message' => $message,
                'type' => 'parent',
                'status' => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


        DB::table('job_tickets')
            ->where('id', $tutorDetail->ticketID)
            ->update(['status' => 'approved', 'application_status' => 'completed', 'tutor_id' => $tutorDetail->tutorID,'ticket_approval_date'=>date("Y-m-d")]);

        DB::table('student_subjects')
            ->where('ticket_id', $tutorOffer->ticketID)
            ->update(['status' => 'Accepted', 'days' => 1, 'tutor_id' => $tutorDetail->tutorID]);

        $tutorOfferValues = array(
            'class_schedule_id' => 0,
            'tutorID' => $tutorOffer->tutorID,
            'studentID' => $studentID->student_id,
            'subjectID' => $tutorOffer->subject_id,
            'ticketID' => $tutorOffer->ticketID,
            'status' => 'Pending'
        );
        DB::table('class_schedules')->insertGetId($tutorOfferValues);

        $extraStudentFeeCharges = DB::table('extra_student_charges')->orderBy('id', 'DESC')->first();
        $ticketDetal = DB::table('job_tickets')->where('id', '=', $tutorDetail->ticketID)->first();
        $subjectPrice = DB::table('products')->where('id', '=', $ticketDetal->subjects)->first();

        $studentInvoiceInitiateTwo = array(
            'tutorID' => $tutorOffer->tutorID,
            'studentID' => $studentID->student_id,
            'subjectID' => $tutorOffer->subject_id,
            'ticketID' => $tutorOffer->ticketID,
            'isPaid' => 'unpaid',
        );

        DB::table('studentinvoices')->insertGetId($studentInvoiceInitiateTwo);

        $tutorInvoiceInitiate = array(
            'tutorID' => $tutorOffer->tutorID,
            'studentID' => $studentID->student_id,
            'subjectID' => $tutorOffer->subject_id,
            'ticketID' => $tutorOffer->ticketID
        );

        DB::table('tutorinvoices')->insertGetId($tutorInvoiceInitiate);


        $tutor_data = array(
            'tutor_id' => $tutorOffer->tutorID,
            'ticket_id' => $tutorOffer->ticketID,
            'subject' => $studentID->student_id,
            'quantity' => $studentID->quantity,
            'classFrequency' => $studentID->classFrequency,
            'day' => $studentID->day,
            'time' => $studentID->time,
            'subscription' => $studentID->subscription,
            'specialRequest' => $studentID->specialRequest,
        );
        DB::table('tutor_subjects')->insertGetId($tutor_data);

        // dd($tutorDetail);

        $tutor = DB::table("tutors")->where("id", $id)->first();
        if (isset($tutor) && $tutor->whatsapp != null) {
            $whatsapp_api = new WhatsappApi();
            $phone_number = $tutor->whatsapp;
            $message = 'Your Application for Ticket: *' . $tutorDetail->ticketUID . '* has been approved';
            $whatsapp_api->send_message($phone_number, $message);
        }
        
        // Retrieve parent details
        $ticketDetail = DB::table('job_tickets')->where('id', '=', $tutorOffer->ticketID)->first();
        $studentDetail = DB::table('students')->where('id', '=', $ticket->student_id)->first();
        $customerDetail = DB::table('customers')->where('id', '=', $studentDetail->customer_id)->first();

        //Sending notification in mobile app
        $tutorName = DB::table('tutors')->where('id', '=', $tutorOffer->tutorID)->first();
        $studentName = DB::table('students')->where('id', '=', $studentID->student_id)->first();
        // $subjectName = DB::table('products')->where('id', '=', $tutorOffer->subject_id)->first();
        
        // Retrieve email details
        $schedule = "{$ticketDetail->day} at {$ticketDetail->time}";
        $specialRequirement = $ticketDetail->specialRequest;
        $subjectName = DB::table('products')->where('id', $ticket->subjects)->value('name');
        
        // Send email to parent
        Mail::to($customerDetail->email)->send(new ParentApprovalEmail(
            $customerDetail->full_name,
            $studentDetail->full_name,
            $subjectName,
            $schedule,
            $specialRequirement
        ));

        try {

            $data = [
                "ResponseCode" => "100",
                "message" => "Job Ticket Approved"
            ];

            //tutor
            event(new TutorNotification($data,$tutorName->token));
            event(new TutorOffers($data,$tutorName->token));

            //parent
            event(new JobTicket($data, $customerDetail->token));
            event(new ParentNotification($data,$customerDetail->token));
            event(new SingleParentDashboard($data,$customerDetail->token));


        } catch(Exception $e) {
            return response()->json(["ResponseCode"=> "103",
                "error"=> "Unable to get Job Ticket Approved Info"]);
        }

        return redirect()->back();
    }

    public function tutoOfferActionReject($id)
    {

        $tutorOffer = DB::table('tutoroffers')->where('id', '=', $id)->first();
        $ticket = DB::table("job_tickets")->find($tutorOffer->ticketID);
        $tutorDetail = DB::table('tutoroffers')->where('id', $id)->first();
        $tutor = DB::table("tutors")->where("id",$tutorOffer->tutorID)->first();

        DB::table('tutoroffers')
            ->where('id', $id)
            ->update(['status' => 'rejected']);

        $tutorDeviceApproved = DB::table('tutor_device_tokens')
            ->where('tutor_id', $tutorDetail->tutorID)
            ->first();

       //Sending notification in mobile app
       if(isset($tutorDeviceApproved->device_token))
       {
        $tutor = DB::table('tutors')->where('id', '=', $tutorDeviceApproved->tutor_id)->first();
        $push_notification_api = new PushNotificationLibrary();
        $title = 'Job Ticket Application Status (Unsuccessful)';
        $message = 'Bummer! You didn’t get the job for ' . $ticket->uid . '. Try for the next one!';
        $deviceToken =  $tutorDeviceApproved->device_token;;
        $notificationdata = [
                'id' => $ticket->uid,
                'Sender' => 'jobTicket'
            ];
        
            // Dispatch push notification job
            SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
        
            // Store notification in the database
            DB::table('notifications')->insert([
                'page' => 'jobTicket',
                'token' => $tutor->token,
                'title' => $title,
                'message' => $message,
                'type' => 'parent',
                'status' => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $tutorName = DB::table('tutors')->where('id', '=', $tutorDetail->tutorID)->first();
        $studentName = DB::table('students')->where('id', '=', $ticket->student_id)->first();
        $subjectName = DB::table('products')->where('id', '=', $tutorDetail->subject_id)->first();
        $parent = DB::table("customers")->where("id",$studentName->customer_id)->first();
        
        $tutordevicetoken = DB::table('tutor_device_tokens')->where('tutor_id', $tutorName->id)->first();
        $parentdevicetoken = DB::table('parent_device_tokens')->where('parent_id', $parent->id)->first();
        
        
        $title = 'Job Ticket Offter Rejected';
        $message = "Job Ticket Rejected" . ', TutorName:' . $tutorName->full_name . ', Student Name:' . $studentName->full_name . ', Subject:' . $subjectName->name.', Ticket ID:' . $tutorDetail->ticketUID;
        $notificationdata = [
                'id' => $ticket->uid,
                'Sender' => 'jobTicket'
            ];
        
        // Dispatch push notification job
        SendPushNotificationJob::dispatch($tutordevicetoken, $title, $message, $notificationdata);
    
        // Store notification in the database
        DB::table('notifications')->insert([
            'page' => 'jobTicket',
            'token' => $tutorName->token,
            'title' => $title,
            'message' => $message,
            'type' => 'tutor',
            'status' => 'new',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Dispatch push notification job
        SendPushNotificationJob::dispatch($parentdevicetoken, $title, $message, $notificationdata);
    
        // Store notification in the database
        DB::table('notifications')->insert([
            'page' => 'jobTicket',
            'token' => $parent->token,
            'title' => $title,
            'message' => $message,
            'type' => 'Parent',
            'status' => 'new',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Prepare email data
        $emailData = [
            'tutorName' => $tutor->full_name,
        ];
    
        // Send email to the tutor
        try {
            Mail::to($tutor->email)->send(new OfferRejectedMail($emailData));
        } catch (\Exception $e) {
            return response()->json([
                "ResponseCode" => "103",
                "error" => "Failed to send rejection email: " . $e->getMessage(),
            ]);
        }

        try {

            $data = [
                "ResponseCode" => "100",
                "message" => "Job Ticket Rejected"
            ];

            //tutor
            event(new TutorNotification($data,$tutor->token));
            event(new TutorOffers($data,$tutor->token));

            //parent
            event(new JobTicket($data, $parent->token));
            event(new ParentNotification($data,$parent->token));
            event(new SingleParentDashboard($data,$parent->token));
            
        } catch(Exception $e) {
            return response()->json(["ResponseCode"=> "103",
                "error"=> "Unable to get Job Ticket Rejected Info"]);
        }

        //End Sending notification in mobile app
        // dd("Done");

        return redirect()->back();
    }

    public function tutorOffer($subjectID, $tutorID, $ticketID)
    {
        $tutorOfferValues = array(
            'tutorID' => $tutorID,
            'subject_id' => $subjectID,
            'ticketID' => $ticketID,
            'status' => 'Applied'
        );

        $ssa = DB::table('tutoroffers')->insertGetId($tutorOfferValues);
        return Redirect::back()->with('success', 'Ticket updated successfully');
    }


    public function allTickets($id)
    {
        $tutorID = $id;
        $tutor = DB::table('tutors')->where('id', '=', $id)->first();

        $tickets = DB::table('student_subjects')
            ->join('job_tickets', 'student_subjects.ticket_id', '=', 'job_tickets.id')
            ->join('products', 'student_subjects.subject', '=', 'products.id')
            ->select('student_subjects.*', 'job_tickets.*', 'products.name as subject_name', 'products.id as subject_id', 'student_subjects.tutor_id as tutorID', 'student_subjects.ticket_id as ticketID', 'student_subjects.id as ssid', 'student_subjects.student_id as studentID', 'job_tickets.status as ticket_status')
            ->get();


        $myApplicedTicekts = DB::table('student_subjects')
            ->join('job_tickets', 'student_subjects.ticket_id', '=', 'job_tickets.id')->where('student_subjects.tutor_id', '=', $tutorID)
            ->join('products', 'student_subjects.subject', '=', 'products.id')
            ->select('student_subjects.*', 'job_tickets.*', 'products.name as subject_name', 'products.id as subject_id', 'student_subjects.tutor_id as tutorID', 'student_subjects.id as ssid', 'student_subjects.status as ticket_status')
            ->get();


        $subjects = DB::table('tutor_subjects')->where('tutor_id', '=', $tutor->id)
            ->join('products', 'tutor_subjects.subject', '=', 'products.id')
            ->select('tutor_subjects.*', 'products.name as subject_name')
            ->get();


        return view('tutor/allTickets', Compact('tutor', 'subjects', 'tickets', 'tutorID', 'myApplicedTicekts'));
    }


    public function scheduledClasses($id)
    {
        $tutorID = $id;
        $tutor = DB::table('tutors')->where('id', '=', $id)->first();

        $tickets = DB::table('student_subjects')
            ->join('job_tickets', 'student_subjects.ticket_id', '=', 'job_tickets.id')
            ->join('products', 'student_subjects.subject', '=', 'products.id')
            ->select('student_subjects.*', 'job_tickets.*', 'products.name as subject_name', 'products.id as subject_id', 'student_subjects.tutor_id as tutorID', 'student_subjects.ticket_id as ticketID', 'student_subjects.id as ssid', 'student_subjects.student_id as studentID', 'student_subjects.status as ticket_status')
            ->get();


        $myApplicedTicekts = DB::table('student_subjects')
            ->join('job_tickets', 'student_subjects.ticket_id', '=', 'job_tickets.id')->where('student_subjects.tutor_id', '=', $tutorID)
            ->join('products', 'student_subjects.subject', '=', 'products.id')
            ->join('students', 'job_tickets.student_id', '=', 'students.id')
            ->select('students.*', 'student_subjects.*', 'job_tickets.*', 'products.name as subject_name', 'job_tickets.id as job_ticketsID', 'students.full_name as studentName', 'students.id as studentID', 'students.email as studentEmail', 'students.age as studentAge', 'students.gender as studentGender', 'students.address1 as studentAddress', 'products.id as subject_id', 'student_subjects.tutor_id as tutorID', 'student_subjects.status as ticket_status')
            ->get();


        $subjects = DB::table('tutor_subjects')->where('tutor_id', '=', $tutor->id)
            ->join('products', 'tutor_subjects.subject', '=', 'products.id')
            ->select('tutor_subjects.*', 'products.name as subject_name')
            ->get();


        $tutors = DB::table('tutors')->get();
        $allSubjects = DB::table('products')->get();

        return view('tutor/scheduledClasses', Compact('tutor', 'tutors', 'allSubjects', 'subjects', 'tickets', 'tutorID', 'myApplicedTicekts'));
    }

    public function assignedClasses($id)
    {
        $tutorID = $id;
        $tutor = DB::table('tutors')->where('id', '=', $id)->first();

        $tickets = DB::table('student_subjects')
            ->join('job_tickets', 'student_subjects.ticket_id', '=', 'job_tickets.id')
            ->join('products', 'student_subjects.subject', '=', 'products.id')
            ->select('student_subjects.*', 'job_tickets.*', 'products.name as subject_name', 'products.id as subject_id', 'student_subjects.tutor_id as tutorID', 'student_subjects.ticket_id as ticketID', 'student_subjects.id as ssid', 'student_subjects.student_id as studentID', 'student_subjects.status as ticket_status')
            ->get();


        $myApplicedTicekts = DB::table('student_subjects')
            ->join('job_tickets', 'student_subjects.ticket_id', '=', 'job_tickets.id')->where('student_subjects.tutor_id', '=', $tutorID)
            ->join('products', 'student_subjects.subject', '=', 'products.id')
            ->join('students', 'job_tickets.student_id', '=', 'students.id')
            ->select('students.*', 'student_subjects.*', 'job_tickets.*', 'products.name as subject_name', 'students.full_name as studentName', 'students.email as studentEmail', 'students.age as studentAge', 'students.gender as studentGender', 'students.address1 as studentAddress', 'products.id as subject_id', 'student_subjects.tutor_id as tutorID', 'student_subjects.status as ticket_status')
            ->get();


        $subjects = DB::table('tutor_subjects')->where('tutor_id', '=', $tutor->id)
            ->join('products', 'tutor_subjects.subject', '=', 'products.id')
            ->select('tutor_subjects.*', 'products.name as subject_name')
            ->get();


        return view('tutor/assignedClasses', Compact('tutor', 'subjects', 'tickets', 'tutorID', 'myApplicedTicekts'));
    }


    public function tutorLogin($id)
    {
        $tutorID = $id;
        $tutor = DB::table('tutors')->where('id', '=', $id)->first();
        
        // dd($tutor);

        $tickets = DB::table('student_subjects')
            ->join('job_tickets', 'student_subjects.ticket_id', '=', 'job_tickets.id')
            ->join('products', 'student_subjects.subject', '=', 'products.id')
            ->join('tutoroffers', 'student_subjects.tutor_id', '=', 'tutoroffers.tutorID')
            ->select('student_subjects.*',
                'job_tickets.*',
                'products.name as subject_name',
                'products.id as subject_id',
                'tutoroffers.tutorID', 'tutoroffers.status')
            ->where("tutoroffers.status", 'approved')
            ->where("tutoroffers.tutorID", $id)
            ->get();
        
        $appliedtickets = DB::table('student_subjects')
            ->join('job_tickets', 'student_subjects.ticket_id', '=', 'job_tickets.id')
            ->join('products', 'student_subjects.subject', '=', 'products.id')
            ->join('tutoroffers', 'job_tickets.id', '=', 'tutoroffers.ticketID')
            ->select('student_subjects.*',
                'job_tickets.*',
                'products.name as subject_name',
                'products.id as subject_id',
                'tutoroffers.tutorID', 'tutoroffers.status')
            ->where("tutoroffers.tutorID", $id)
            ->get();
        
        // dd($appliedtickets);


        $myApplicedTicektsOne = DB::table('student_subjects')
            ->join('job_tickets', 'student_subjects.ticket_id', '=', 'job_tickets.id')->where('student_subjects.tutor_id', '=', $tutorID)
            ->join('products', 'student_subjects.subject', '=', 'products.id')
            ->join('students', 'job_tickets.student_id', '=', 'students.id')
            ->select('students.*', 'student_subjects.*', 'job_tickets.*', 'products.name as subject_name', 'students.full_name as studentName', 'students.email as studentEmail', 'students.age as studentAge', 'students.gender as studentGender', 'students.address1 as studentAddress', 'products.id as subject_id', 'student_subjects.tutor_id as tutorID', 'student_subjects.status as ticket_status')
            ->get();

        $myApplicedTicekts = DB::table('student_subjects')
            ->join('job_tickets', 'student_subjects.ticket_id', '=', 'job_tickets.id')->where('student_subjects.tutor_id', '=', $tutorID)
            ->join('products', 'student_subjects.subject', '=', 'products.id')
            ->join('students', 'job_tickets.student_id', '=', 'students.id')
            ->select('students.*', 'student_subjects.*', 'job_tickets.*', 'products.name as subject_name', 'job_tickets.id as job_ticketsID', 'students.full_name as studentName', 'students.id as studentID', 'students.email as studentEmail', 'students.age as studentAge', 'students.gender as studentGender', 'students.address1 as studentAddress', 'products.id as subject_id', 'student_subjects.tutor_id as tutorID', 'student_subjects.status as ticket_status')
            ->get();

        // dd($myApplicedTicektsOne);

        $subjects = DB::table('tutor_subjects')->where('tutor_id', '=', $tutor->id)
            ->join('products', 'tutor_subjects.subject', '=', 'products.id')
            ->select('tutor_subjects.*', 'products.name as subject_name')
            ->get();


        $tutors = DB::table('tutors')->get();
        $allSubjects = DB::table('products')->get();

        return view('tutor/tutorDashboard', Compact('tutor', 'tutors', 'allSubjects', 'subjects', 'tickets', 'appliedtickets', 'tutorID', 'myApplicedTicektsOne', 'myApplicedTicekts'));
    }


    public function submitAttendance(Request $request)
    {


        $sTime = $request->start_time;
        $eTime = $request->end_time;
        $t1 = strtotime($sTime);
        $t2 = strtotime($eTime);
        $differenceInSeconds = $t2 - $t1;
        $differenceInHours = $differenceInSeconds / 3600;

        if ($differenceInHours < 0) {
            $differenceInHours += 24;
        }

        $values = array(
            'tutorID' => $request->tutor_id,
            'class_schedule_id' => $request->csid,
            'studentID' => $request->studentID,
            'subjectID' => $request->subject_id,
            'ticketID' => $request->ticketID,
            'date' => $request->date,
            'startTime' => $request->start_time,
            'endTime' => $request->end_time,
            'status' => 'scheduled',
            'totalTime' => $differenceInHours,
            'hasIncentive' => 1,
        );

        $customerLastID = DB::table('assigned_classes')->insertGetId($values);

        $getCurrentDay = DB::table('student_subjects')->latest('id')->where('id', $request->ssid)->where('ticket_id', $request->ticket_id)->where('subject', $request->subject)->where('tutor_id', $request->tutor_id)->first();

        $var1 = DB::table('student_subjects')->where('id', $request->ssid)->where('ticket_id', $request->ticket_id)->where('subject', $request->subject)->where('tutor_id', $request->tutor_id)->update(['status' => 'Accepted', 'days' => $getCurrentDay->days + 1]);

        $ssa_values = array(
            'studentSubjectID' => $request->ssid,
            'days' => $getCurrentDay->days,
            'attendance' => $request->attendance,
            'checkIN' => $request->checkIN,
            'checkOUT' => $request->checkOUT
        );

        $ssa = DB::table('student_subject_attendances')->insertGetId($ssa_values);

        return Redirect::back()->with('success', 'Attendance and  updated successfully');
    }


    public function updateAttendance(Request $request)
    {

        $getCurrentRecord = DB::table('student_subject_attendances')->latest('id')->where('id', $request->id)->first();

        if ($getCurrentRecord->checkIN != NULL) {
        } else {
            DB::table('student_subject_attendances')
                ->where('id', $request->id)
                ->update(['checkIN' => $request->checkIN]);
        }

        if ($getCurrentRecord->checkOUT != NULL) {
        } else {
            DB::table('student_subject_attendances')
                ->where('id', $request->id)
                ->update(['checkOUT' => $request->checkOUT]);
        }

        return Redirect::back()->with('success', 'CheckIN and CheckOUT successfully');
    }


    public function submitTutor(Request $request)
    {


        $values = array(
            'uid' => $request->tutorID,
            'tutor_id' => $request->tutorID,
            'full_name' => $request->full_name,
            'fee_payment_date' => $request->feePaymentDate,
            'gender' => $request->gender,
            'start_date' => $request->registration_date,
            'age' => $request->age,
            'email' => $request->email,
            'password' => Hash::make($request->loginPassword),
            'phoneNumber' => $request->phoneNumber,
            'whatsapp' => $request->whatsapp,
            'dob' => $request->dob,
            'nric' => $request->nric,
            'street_address1' => $request->street_address1,
            'street_address2' => $request->street_address2,
            'city' => $request->city,
            'state' => $request->state,
            'status' => "unverified",
            'marital_status' => $request->maritalStatus,
            'training_date' => $request->attendedTrainingDate,
            'postal_code' => $request->postalcode,
            'latitude' => $request->customerLatitude,
            'longitude' => $request->customerLongitude,
            'receiving_account' => $request->receivingAccount,
            'remark' => $request->remark,
            'bank_name' => $request->bankName,
            'shirt_size' => $request->shirtSize,
            'fee_payment_date' => $request->feePaymentDate,
            'bank_account_number' => $request->bankAccountNumber,
            'token' => (new Token())->Unique('customers', 'token', 60)
        );


        $tutorLastID = DB::table('tutors')->insertGetId($values);
        $data = $request->all();


//  dd($request->all());
        // $subject = $data['subject'];
        // for ($i = 0; $i < count($subject); $i++) {
        //     if($data['subject'][$i] != NULL){

        //         $tutor_data = array(
        //             'tutor_id' => $tutorLastID,
        //             'subject' => $data['subject'][$i],

        //             );
        //         DB::table('tutor_subjects')->insertGetId($tutor_data);
        //     }
        // }
        // dd($request->all());
        if (isset($data['paymentAttachment'])) {
            foreach ($data['paymentAttachment'] as $key => $data) {

                $paymentAttachments = $request->paymentAttachment[$key];
                if (!empty($paymentAttachments)) {
                    $imageName = time() . '.' . $request->file('paymentAttachment')[$key]->extension();
                    $request->file('paymentAttachment')[$key]->move(public_path('tutorPaymentAttachment'), $imageName);

                    $tutorPayment = array(
                        'tutor_id' => $tutorLastID,
                        'payment_attachment' => $imageName,
                        'payment_amount' => $request->feeAmount[$key],
                        'payment_date' => $request->feePaymentDate[$key],
                        'receiving_account' => $request->receivingAccount[$key],
                        'status' => $request->payment_status[$key],
                    );

                    DB::table('tutor_commitment_fees')->insertGetId($tutorPayment);
                    DB::table('tutors')->where("id", $tutorLastID)->update(["fee_payment_date" => $request->feePaymentDate[$key]]);
                }
            }


            $affected = DB::table('tutors')
                ->where('id', $tutorLastID)
                ->update(['status' => 'verified']);
        }


        // Store Service Preferences
        if (isset($data['category']) && isset($data['mode_of_tutoring']) && isset($data['preferable_location']) && isset($data['teaching_experience'])) {

            $categories = implode(', ', $data['category']);
            $modesOfTutoring = implode(', ', $data['mode_of_tutoring']);
            $preferableLocations = implode(', ', $data['preferable_location']);

            // for ($i = 0; $i < count($data['categories']); $i++) {
            $serviceData = [
                'category' => $categories,
                'mode_of_tutoring' => $modesOfTutoring,
                'preferable_location' => $preferableLocations,
                'teaching_experience' => $data['teaching_experience'],
            ];

            $existingRecord = DB::table('service_preferences')
                ->where('tutor_id', $tutorLastID)
                ->where('category', $data['category'])
                ->first();

            if ($existingRecord) {
                DB::table('service_preferences')
                    ->where('tutor_id', $tutorLastID)
                    ->where('category', $data['category'][$i])
                    ->update($serviceData);
            } else {
                $serviceData['tutor_id'] = $tutorLastID;
                DB::table('service_preferences')->insert($serviceData);
            }
            // }
        }


        //   dd($data);

        // Store Bio Details
        if (isset($data['full_name']) && isset($data['phoneNumber']) && isset($data['email']) && isset($data['CNIC']) && isset($data['street_address1']) && isset($data['postalcode'])) {
            $bioData = [
                'full_name' => $request->full_name,
                'phone_number' => $request->phoneNumber,
                'email' => $request->email,
                'ic_number' => $request->CNIC,
                'residential_address' => $request->street_address1 . ' ' . $request->street_address2,
                'postal_code' => $request->postalcode,
            ];

            $existingRecord = DB::table('bio_details')->where('tutor_id', $tutorLastID)->first();
            if ($existingRecord) {
                DB::table('bio_details')->where('tutor_id', $tutorLastID)->update($bioData);
            } else {
                $bioData['tutor_id'] = $tutorLastID;
                DB::table('bio_details')->insert($bioData);
            }
        }

        // Store Emergency Contact
        if (isset($data['emergency_contact_name']) && isset($data['relationship']) && isset($data['emergency_contact_number'])) {
            $emergencyContactData = [
                'emergency_contact_name' => $data['emergency_contact_name'],
                'relationship' => $data['relationship'],
                'emergency_contact_number' => $data['emergency_contact_number'],
            ];

            $existingRecord = DB::table('emergency_contacts')->where('tutor_id', $tutorLastID)->first();
            if ($existingRecord) {
                DB::table('emergency_contacts')->where('tutor_id', $tutorLastID)->update($emergencyContactData);
            } else {
                $emergencyContactData['tutor_id'] = $tutorLastID;
                DB::table('emergency_contacts')->insert($emergencyContactData);
            }
        }

        // Store Education
        if (isset($data['highest_education']) && isset($data['field_of_study']) && isset($data['academic_year']) && isset($data['institution_name'])) {
            $educationData = [
                'highest_education' => $data['highest_education'],
                'field_of_study' => $data['field_of_study'],
                'academic_year' => $data['academic_year'],
                'institution_name' => $data['institution_name'],
            ];

            $existingRecord = DB::table('educations')->where('tutor_id', $tutorLastID)->first();
            if ($existingRecord) {
                DB::table('educations')->where('tutor_id', $request->id)->update($educationData);
            } else {
                $educationData['tutor_id'] = $tutorLastID;
                DB::table('educations')->insert($educationData);
            }
        }

        // Store Documents
        // Store Documents
        $documentData = [];
        if ($request->hasFile('resume')) {
            $resume = $request->file('resume');
            $resumePath = $resume->store('documents/resumes', 'public');
            $documentData['resume_url'] = $resumePath;
        }

        if ($request->hasFile('education_transcript')) {
            $educationTranscript = $request->file('education_transcript');
            $educationTranscriptPath = $educationTranscript->store('documents/education_transcripts', 'public');
            $documentData['education_transcript_url'] = $educationTranscriptPath;
        }

        if ($request->hasFile('formal_photo')) {
            $formalPhoto = $request->file('formal_photo');
            $formalPhotoPath = $formalPhoto->store('documents/formal_photos', 'public');
            $documentData['formal_photo_url'] = $formalPhotoPath;
        }

        if ($request->hasFile('identity_card_front')) {
            $identityCardFront = $request->file('identity_card_front');
            $identityCardFrontPath = $identityCardFront->store('documents/identity_cards', 'public');
            $documentData['identity_card_front_url'] = $identityCardFrontPath;
        }

        if (!empty($documentData)) {
            $existingRecord = DB::table('documents')->where('tutor_id', $tutorLastID)->first();
            if ($existingRecord) {
                DB::table('documents')->where('tutor_id', $tutorLastID)->update($documentData);
            } else {
                $documentData['tutor_id'] = $tutorLastID;
                DB::table('documents')->insert($documentData);
            }
        }


        // dd($request->all());
        return redirect("/TutorList");
        // return redirect()->back()->with('success','Tutor has been added successfully!');


    }

    public function submitTutorPayment(Request $request)
    {
        if ($request->payingAmount < 1) {
            return redirect()->back()->with('danger', 'Please Enter Paying Amount!');
        }

        $data = $request->all();
        $classAttendedID = $data['classAttendedID'];
        $AdditionalAmount = isset($data['AdditionalAmount']) ? $data['AdditionalAmount'] : 0;
        $deductionAmount = isset($data['deductionAmount']) ? $data['deductionAmount'] : 0;

        $values = array(
            'tutorID' => $request->id,
            'paymentDate' => $request->paymentDate,
            'comissionMonth' => $request->salaryMonth,
            'comissionYear' => $request->salaryYear,
            'deduction' => 0,
            'addition' => 0,
            'payAmount' => $request->payingAmount,
            'payingAccount' => $request->payingAccount,
            'remark' => $request->remark,
            'payingAccount' => $request->payingAccount,
        );
        $paymentLastID = DB::table('tutorpayments')->insertGetId($values);

        if (count($classAttendedID) > 0) {
            $update_value = array('status' => 'verified');

            $affected = DB::table('tutors')
                ->where('id', $request->id)
                ->update($update_value);
            for ($i = 0; $i < count($classAttendedID); $i++) {
                if ($data['classAttendedID'][$i] != NULL) {
                    $values = array(
                        'is_paid' => 'paid',
                        'is_tutor_paid' => 'paid', 'tutorPaymentID' => $paymentLastID, 'payment_date' => $request->paymentDate
                    );
                    $affected = DB::table('class_attendeds')
                        ->where('id', $data['classAttendedID'][$i])
                        ->update($values);
                }
            }
        }
        
        $tutor = DB::table('tutors')->where('id', '=', $request->id)->first();

        // Send push notifications to tutor devices
        $tutorDevices = DB::table('tutor_device_tokens')->where('tutor_id', '=', $request->id)->distinct()->get(['device_token', 'tutor_id']);
        foreach ($tutorDevices as $rowDeviceToken) {
            $push_notification_api = new PushNotificationLibrary();
            $deviceToken = $rowDeviceToken->device_token;
            $title = 'Monthly Payment Confirmation';
            $message = $request->salaryMonth.' payment processed! Check your slip in the app.';
        
            $notificationdata = [
                'Sender' => 'PaymentHistory'
            ];
        
            // Dispatch push notification job
            SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
        
            // Store notification in the database
            DB::table('notifications')->insert([
                'page' => 'PaymentHistory',
                'token' => $tutor->token,
                'title' => $title,
                'message' => $message,
                'type' => 'tutor',
                'status' => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


        if ($deductionAmount > 0) {
            for ($i = 0; $i < count($deductionAmount); $i++) {
                $deductionAmountValues = array(
                    'tutorPaymentsId' => $paymentLastID,
                    'description' => $data['deductionDescription'][$i],
                    'amount' => $data['deductionAmount'][$i],
                );
                $invoiceDeductionsLastIDDeduction = DB::table('tutorPaymentDeductions')->insertGetId($deductionAmountValues);
            }
        }


        if ($AdditionalAmount > 0) {
            for ($i = 0; $i < count($AdditionalAmount); $i++) {
                $additionalAmountValues = array(
                    'tutorPaymentsId' => $paymentLastID,
                    'description' => $data['AdditionalDescription'][$i],
                    'amount' => $data['AdditionalAmount'][$i],
                );
                $invoiceDeductionsLastIDAdditional = DB::table('tutorPaymentAdditionals')->insertGetId($additionalAmountValues);
            }
        }


        //dd("Done");

        return redirect()->back();
    }


    public function submitEditTutor(Request $request)
    {
        // dd($request->all());
        $values = [
            'uid' => $request->tutorID,
            'tutor_id' => $request->tutorID,
            'full_name' => $request->full_name,
            'fee_payment_date' => $request->feePaymentDate,
            'gender' => $request->gender,
            'start_date' => $request->registration_date,
            'age' => $request->age,
            'email' => $request->email,
            'password' => Hash::make($request->loginPassword),
            'phoneNumber' => $request->phoneNumber,
            'whatsapp' => $request->whatsapp,
            'dob' => $request->dob,
            'nric' => $request->CNIC,
            'street_address1' => $request->street_address1,
            'street_address2' => $request->street_address2,
            'city' => $request->city,
            'state' => $request->state,
            'status' => $request->status,
            'marital_status' => $request->maritalStatus,
            'training_date' => $request->attendedTrainingDate,
            'postal_code' => $request->postalcode,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'receiving_account' => $request->receivingAccount,
            'remark' => $request->remark,
            'bank_name' => $request->bankName,
            'shirt_size' => $request->shirtSize,
            'fee_payment_date' => $request->feePaymentDate,
            'bank_account_number' => $request->bankAccountNumber
        ];


        $affected = DB::table('tutors')
            ->where('id', $request->id)
            ->update($values);
        
        if($request->status === 'verified'){

            $tutor = DB::table("tutors")->where("id",$request->id)->first();
            
            // Send push notifications to tutor devices
            $tutorDevice = DB::table('tutor_device_tokens')->where('tutor_id', '=', $tutor->id)->distinct()->first(['device_token', 'tutor_id']);
            if($tutorDevice) {
                $deviceToken = $tutorDevice->device_token;
                $title = 'Commitment Fee Paid';
                $message = 'As a tutor, you have successfully paid your commitment fees. You are now approved and can begin teaching.';
            
                $notificationdata = [
                    'Sender' => 'Home'
                ];
            
                // Dispatch push notification job
                SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
            
                // Store notification in the database
                DB::table('notifications')->insert([
                    'page' => 'jobTicket',
                    'token' => $tutor->token,
                    'title' => $title,
                    'message' => $message,
                    'type' => 'tutor',
                    'status' => 'new',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $data = $request->all();

        if (isset($data['paymentAttachment'])) {
            $paymentAttachments = $data['paymentAttachment'];
            if (!empty($paymentAttachments)) {
                $imageName = time() . '.' . $request->file('paymentAttachment')->extension();
                $request->file('paymentAttachment')->move(public_path('tutorPaymentAttachment'), $imageName);

                $tutorPayment = [
                    'tutor_id' => $request->id,
                    'payment_attachment' => $imageName,
                    'payment_amount' => $data['feeAmount'],
                    'payment_date' => $data['feePaymentDate'],
                    'receiving_account' => $data['receivingAccount'],
                    'status' => $data['payment_status'],
                ];

                DB::table('tutor_commitment_fees')->insertGetId($tutorPayment);
                DB::table('tutors')->where("id", $request->id)->update(["fee_payment_date" => $data['feePaymentDate']]);
            }

            $affected = DB::table('tutors')
                ->where('id', $request->id)
                ->update(['status' => 'verified']);
                
            $tutor = DB::table("tutors")->where("id",$request->id)->first();
            
            // Send push notifications to tutor devices
            $tutorDevice = DB::table('tutor_device_tokens')->where('tutor_id', '=', $tutor->id)->distinct()->first(['device_token', 'tutor_id']);
            if($tutorDevice) {
                $deviceToken = $tutorDevice->device_token;
                $title = 'Commitment Fee Paid';
                $message = 'As a tutor, you have successfully paid your commitment fees. You are now approved and can begin teaching.';
            
                $notificationdata = [
                    'Sender' => 'Home'
                ];
            
                // Dispatch push notification job
                SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
            
                // Store notification in the database
                DB::table('notifications')->insert([
                    'page' => 'jobTicket',
                    'token' => $tutor->token,
                    'title' => $title,
                    'message' => $message,
                    'type' => 'tutor',
                    'status' => 'new',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            
            try {

                $data = [
                    "ResponseCode" => "100",
                    "message" => "Tutor  Verfied"
                ];

                event(new TutorVerified($data, $tutor->token));


            } catch (Exception $e) {
                return response()->json(["ResponseCode" => "103",
                    "error" => "Unable to verify tutor"]);
            }
        }
        else {

            $data = [
                "ResponseCode" => "100",
                "message" => "Tutor  Update"
            ];
    
            event(new TutorDashboard($data, $tutor->token));
        }
        
        // $data = [
        //     "ResponseCode" => "100",
        //     "message" => "Tutor  Update"
        // ];

        // event(new TutorVerified($data, $tutor->token));


        if (isset($data['subject'])) {
            $tutorSubjects = DB::table('tutor_subjects')->where("tutor_id", $request->id)->get();
            if ($tutorSubjects != null) {
                foreach ($tutorSubjects as $subject) {
                    DB::table('tutor_subjects')->where("id", $subject->id)->delete();
                }
            }

            $subject = $data['subject'];
            for ($i = 0; $i < count($subject); $i++) {
                if ($data['subject'][$i] != NULL) {
                    $tutor_data = [
                        'tutor_id' => $request->id,
                        'subject' => $data['subject'][$i],
                    ];
                    DB::table('tutor_subjects')->insertGetId($tutor_data);
                }
            }
        }

        // Store Service Preferences
        if (isset($data['category']) && isset($data['mode_of_tutoring']) && isset($data['preferable_location']) && isset($data['teaching_experience'])) {
    // Convert arrays to comma-separated strings
    $categories = implode(', ', $data['category']);
    $modesOfTutoring = implode(', ', $data['mode_of_tutoring']);
    $preferableLocations = implode(', ', $data['preferable_location']);

    $serviceData = [
        'category' => $categories,
        'mode_of_tutoring' => $modesOfTutoring,
        'preferable_location' => $preferableLocations,
        'teaching_experience' => $data['teaching_experience'],
    ];

    // Check if the record exists
    $existingRecord = DB::table('service_preferences')
        ->where('tutor_id', $request->id)
        ->first();

    if ($existingRecord) {
        // Update existing record
        DB::table('service_preferences')
            ->where('tutor_id', $request->id)
            ->update($serviceData);
    } else {
        // Insert new record
        $serviceData['tutor_id'] = $request->id;
        DB::table('service_preferences')->insert($serviceData);
    }
}



        // Store Bio Details
        if (isset($data['full_name']) && isset($data['phoneNumber']) && isset($data['email']) && isset($data['CNIC']) && isset($data['street_address1']) && isset($data['postalcode'])) {
            $bioData = [
                'full_name' => $request->full_name,
                'phone_number' => $request->phoneNumber,
                'email' => $request->email,
                'ic_number' => $request->CNIC,
                'residential_address' => $request->street_address1 . ' ' . $request->street_address2,
                'postal_code' => $request->postalcode,
            ];

            $existingRecord = DB::table('bio_details')->where('tutor_id', $request->id)->first();
            if ($existingRecord) {
                DB::table('bio_details')->where('tutor_id', $request->id)->update($bioData);
            } else {
                $bioData['tutor_id'] = $request->id;
                DB::table('bio_details')->insert($bioData);
            }
        }

        // Store Emergency Contact
        if (isset($data['emergency_contact_name']) && isset($data['relationship']) && isset($data['emergency_contact_number'])) {
            $emergencyContactData = [
                'emergency_contact_name' => $data['emergency_contact_name'],
                'relationship' => $data['relationship'],
                'emergency_contact_number' => $data['emergency_contact_number'],
            ];

            $existingRecord = DB::table('emergency_contacts')->where('tutor_id', $request->id)->first();
            if ($existingRecord) {
                DB::table('emergency_contacts')->where('tutor_id', $request->id)->update($emergencyContactData);
            } else {
                $emergencyContactData['tutor_id'] = $request->id;
                DB::table('emergency_contacts')->insert($emergencyContactData);
            }
        }

        // Store Education
        if (isset($data['highest_education']) && isset($data['field_of_study']) && isset($data['academic_year']) && isset($data['institution_name'])) {
            $educationData = [
                'highest_education' => $data['highest_education'],
                'field_of_study' => $data['field_of_study'],
                'academic_year' => $data['academic_year'],
                'institution_name' => $data['institution_name'],
            ];

            $existingRecord = DB::table('educations')->where('tutor_id', $request->id)->first();
            if ($existingRecord) {
                DB::table('educations')->where('tutor_id', $request->id)->update($educationData);
            } else {
                $educationData['tutor_id'] = $request->id;
                DB::table('educations')->insert($educationData);
            }
        }

        // Store Documents
        // Store Documents
        $documentData = [];
        if ($request->hasFile('resume')) {
            $resume = $request->file('resume');
            $resumePath = $resume->store('documents/resumes', 'public');
            $documentData['resume_url'] = $resumePath;
        }

        if ($request->hasFile('education_transcript')) {
            $educationTranscript = $request->file('education_transcript');
            $educationTranscriptPath = $educationTranscript->store('documents/education_transcripts', 'public');
            $documentData['education_transcript_url'] = $educationTranscriptPath;
        }

        if ($request->hasFile('formal_photo')) {
            $formalPhoto = $request->file('formal_photo');
            $formalPhotoPath = $formalPhoto->store('documents/formal_photos', 'public');
            $documentData['formal_photo_url'] = $formalPhotoPath;
        }

        if ($request->hasFile('identity_card_front')) {
            $identityCardFront = $request->file('identity_card_front');
            $identityCardFrontPath = $identityCardFront->store('documents/identity_cards', 'public');
            $documentData['identity_card_front_url'] = $identityCardFrontPath;
        }

        if (!empty($documentData)) {
            $existingRecord = DB::table('documents')->where('tutor_id', $request->id)->first();
            if ($existingRecord) {
                DB::table('documents')->where('tutor_id', $request->id)->update($documentData);
            } else {
                $documentData['tutor_id'] = $request->id;
                DB::table('documents')->insert($documentData);
            }
        }

        // Store Declaration
        // $declarationData = ['declaration' => $request->declaration];
        // $existingRecord = DB::table('declarations')->where('tutor_id', $request->id)->first();
        // if ($existingRecord) {
        //     DB::table('declarations')->where('tutor_id', $request->id)->update($declarationData);
        // } else {
        //     $declarationData['tutor_id'] = $request->id;
        //     DB::table('declarations')->insert($declarationData);
        // }
        return redirect()->back()->with('success', 'Tutor has been editted successfully!');


        return view('student/addStudent');
    }


    public function getAttendance($id, $subject, $tutor_id, $ticket_id)
    {
        //
        $id;
        $subject;
        $tutor_id;
        $ticket_id;
        return $id;
        //$tutor = DB::table('tutors')->where('id','=',$id)->get();
        //return view('tutor/addTutor',Compact($tutor));
    }

    public function getCheckINN($id, $subject, $tutor_id, $ticket_id)
    {
        //
        return $id;
        //$tutor = DB::table('tutors')->where('id','=',$id)->get();
        //return view('tutor/addTutor',Compact($tutor));
    }

    public function getCheckOUT($id, $subject, $tutor_id, $ticket_id)
    {
        //
        return $id;
        //$tutor = DB::table('tutors')->where('id','=',$id)->get();
        //return view('tutor/addTutor',Compact($tutor));
    }

    public function makeTutorPayment($id)
    {
        $tutorID = $id;
        $tutor = DB::table('tutors')->where('id', '=', $id)->first();
        $tutors = DB::table('tutors')->get();
        $job_tickets = DB::table('job_tickets')->where('tutor_id', '=', $id)->first();
        $student_subjects = DB::table('student_subjects')->where('tutor_id', '=', $id)->get();
        $class_attended = DB::table('class_attendeds')->
        where('tutorID', '=', $id)->where('status', '=', 'attended')->
        where('is_paid', '=', 'unpaid')->
        where('is_tutor_paid', '=', 'unpaid')->
        // where('parent_verified', '=', 'YES')->
        get();

        $totalCommission = DB::table('class_attendeds')
            ->where('tutorID', '=', $id)
            ->where('status', '=', 'attended')
            ->where('is_paid', '=', 'unpaid')
            // ->where('parent_verified', '=', 'YES') // Additional condition
            ->where('is_tutor_paid', '=', 'unpaid')
            ->sum('commission');

        // dd($totalCommission);

        // $attendedDuration = DB::table('class_attendeds')->where('tutorID','=',$id)->where('parent_verified','=','YES')->where('status','=','attended')->where('is_paid','=','unpaid')->where('is_tutor_paid','=','unpaid')->sum('totalTime');
        $attendedDurationInSeconds = DB::table('class_attendeds')
            ->select(DB::raw('SUM(TIME_TO_SEC(totalTime)) AS totalSeconds'))
            ->where('tutorID', '=', $id)
            // ->where('parent_verified', '=', 'YES')
            ->where('status', '=', 'attended')
            ->where('is_paid', '=', 'unpaid')
            ->where('is_tutor_paid', '=', 'unpaid')
            ->first()
            ->totalSeconds;


        if ($attendedDurationInSeconds == null) {
            $attendedDuration = 0;
        } else {

            $attendedDuration = (gmdate('H:i:s', $attendedDurationInSeconds));
        }
        // Convert the total duration from seconds to the desired format (HH:MM:SS)


        $subjects = DB::table('tutor_subjects')->where('tutor_id', '=', $id)->get();

        $allSubjects = DB::table('products')->get();
        $totalTime = DB::table('class_schedules')->where('tutorID', '=', $id)->where('is_paid', '=', 0)->orderBy('id', 'DESC')->sum('totalTime');


        return view('tutor/makeTutorPayment', Compact('subjects', 'attendedDuration', 'totalCommission', 'tutorID', 'tutors', 'class_attended', 'tutor', 'allSubjects', 'job_tickets', 'student_subjects', 'totalTime'));
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

    public function profile($id)
    {
        $user = DB::table('users')->where('id', '=', $id)->first();
        return view('tutor/TutorProfile', Compact('user'));
    }

    public function changePassword($id)
    {
        $user = DB::table('users')->where('id', '=', $id)->first();
        return view('tutor/changePassword', Compact('user'));
    }

    public function submitChangePassword(Request $request)
    {
        dd($request);
        die();

        $user = DB::table('users')->where('id', '=', $id)->first();
        return view('tutor/changePassword', Compact('user'));
    }

    public function submitEditProfile(Request $request)
    {

        $imageName = time() . '.' . $request->profileImage->extension();
        $request->profileImage->move(public_path('userProfileImage'), $imageName);

        $affected = DB::table('users')
            ->where('id', $request->userID)
            ->update(['userImage' => $imageName, 'phone' => $request->phone]);
        return redirect()->back();
    }

    public function deleteTutor($id)
    {
        //$tutor_values = array('is_deleted' => 1);

        //$var1 = DB::table('tutors')->where('id', $id)->update($tutor_values);
        DB::table('tutors')->where('id', $id)->delete();

        return redirect()->back();
    }

    public function assignmentSearch(Request $request)
    {
        if ($request->search && $request->status) {
            $tutors = Tutor::where('full_name', 'LIKE', '%' . $request->search . '%')->where('status', '=', $request->status)->get();
        } elseif ($request->status) {
            $tutors = Tutor::where('status', '=', $request->status)->get();
        } elseif ($request->search) {
            $tutors = Tutor::where('full_name', 'LIKE', '%' . $request->search . '%')->get();
        } else {
            $tutors = Tutor::orderBy('id', 'DESC')->get();
        }
        return view('tutor/TutorAssignments', Compact('tutors'));
    }

    public function tutorSearch(Request $req)
    {
        $tutor = Tutor::query();
        if ($req->filled(['subject', 'state', 'status', 'city'])) {
            $tutors = Tutor::where('state', $req->state)->where('city', $req->city)->where('status', $req->status)->where('subject', $req->subject)->get();
        } elseif ($req->filled(['state', 'status', 'city'])) {
            $tutors = $tutor->where('state', $req->state)->where('city', $req->city)->where('status', $req->status)->get();
        } elseif ($req->filled(['state', 'city'])) {
            $tutors = $tutor->where('state', $req->state)->where('city', $req->city)->get();
        } elseif ($req->filled('city')) {
            $tutors = $tutor->where('city', $req->city)->get();
        } elseif ($req->filled('state')) {
            $tutors = $tutor->where('state', $req->state)->get();
        } elseif ($req->filled('status')) {
            $tutors = $tutor->where('status', $req->status)->get();
        } else {
            $tutors = Tutor::get();
        }
        return view('tutor/TutorFinder', Compact('tutors'));
    }
}
