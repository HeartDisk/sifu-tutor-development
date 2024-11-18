<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use Redirect;
use Illuminate\Support\Facades\Hash;
use App\Libraries\WhatsappApi;
use App\Models\Category;
use App\Models\Tutor;
use App\Models\ClassSchedules;
use App\Models\Tutorpayment;
use App\Models\State;
use App\Models\City;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;
use Pusher\Pusher;
use App\Events\TutorOffers;
use App\Events\TutorApproved;
use App\Libraries\PushNotificationLibrary;

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
            $tutor->where('full_name', 'LIKE', '%' . $request->search . '%');
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


        $to_email = "binasift@gmail.com";

        $subject = "Tutor Payment Slip";

        $header = "MIME-Version: 1.0" . "\r\n";
        $header .= "Content-type: multipart/mixed; boundary=\"boundary\"\r\n";
        // More headers
        $header .= 'From: <tutor@sifututor.com>' . "\r\n";

        $file = public_path() . "/tutorPaymentSlipPDF" . "/" . "tutor-Payment-Slip-" . $tutor_payment->id . ".pdf";

        if (!file_exists($file)) {
            $pdf = PDF::loadView('tutor.tutorPaymentSlipPDF', [
                'paidClasses' => $paidClasses,
                'tutor' => $tutor,
                'tutor_payment' => $tutor_payment,
                'additionals' => $additionals,
                'deductions' => $deductions,


            ]);
            $pdf->save(public_path('tutorPaymentSlipPDF') . "/" . "tutor-Payment-Slip-" . $tutor_payment->id . ".pdf");
        }
        // dd("Stop");

        $pdfContent = file_get_contents($file);
        $base64Content = base64_encode($pdfContent);

        // Attachment
        $emailBody = "";
        $emailBody .= '</tbody>
                        </table>
                        <table class="table table-responsive no-border">
                        <tbody>
                        <tr>
                            <td>
                              Please find attached Payment Slip
                            </td>
                        </tr>
                        </tbody>
                        </table>
                        </div>';

        $emailBody .= "\r\n--boundary\r\n";
        $emailBody .= "Content-Type: text/html; charset=UTF-8\r\n";
        $emailBody .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $emailBody .= $emailBody . "\r\n";
        $emailBody .= "--boundary\r\n";


        // PDF Attachment
        $emailBody .= "Content-Type: application/pdf; name=\"Invoice-484.pdf\"\r\n";
        $emailBody .= "Content-Transfer-Encoding: base64\r\n";
        $emailBody .= "Content-Disposition: attachment\r\n\r\n";
        $emailBody .= $base64Content . "\r\n";
        $emailBody .= "--boundary--";


        mail($to_email, $subject, $emailBody, $header);
        mail($tutor->email, $subject, $emailBody, $header);

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
    ->leftJoin('class_attendeds', function($join) {
        $join->on('class_attendeds.class_schedule_id', '=', 'class_schedules.id')
             ->where('class_attendeds.is_paid', '=', 'unpaid');
    })
    ->select(
        'class_schedules.tutorID',
        'tutors.full_name as tutor_name',
        'tutors.uid as tutor_uid',
        \DB::raw('MONTHNAME(class_schedules.created_at) as month'),
        \DB::raw('YEAR(class_schedules.created_at) as year'),
        \DB::raw('SUM(class_schedules.totalTime) as total_duration'),
        \DB::raw("SUM(COALESCE(CASE WHEN class_attendeds.status='attended' THEN class_attendeds.commission ELSE 0 END, 0)) as total_amount"),
        \DB::raw("SUM(COALESCE(CASE WHEN class_attendeds.status='attended' THEN class_attendeds.totalTime ELSE 0 END, 0)) as total_invoice_duration"),
        \DB::raw("DATE_FORMAT(class_attendeds.created_at, '%d/%b/%Y') as completion_date"),
        \DB::raw("COUNT(CASE WHEN class_attendeds.status = 'attended' THEN 1 ELSE NULL END) as attended_count"),
        \DB::raw("COUNT(CASE WHEN class_attendeds.status = 'pending' THEN 1 ELSE NULL END) as pending_count")
    )
    ->groupBy(
        'class_schedules.tutorID',
        'tutors.full_name',
        'tutors.uid',
        \DB::raw('MONTH(class_schedules.created_at)'),
        \DB::raw('YEAR(class_schedules.created_at)')
    );

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


    // urooj 4/28
    public function TutorFinder(Request $req)
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
            $tutors = $tutor->orderByDesc('id')->get();
        }

        $states = State::get();
        $cities = City::get();
        $subjects = DB::table('products')->join("categories", "products.category", "=", "products.id")
            ->select(
                "products.*",
                "categories.category_name as category_name",
                "categories.mode as mode"
            )
            ->get();
        $levels = Category::groupBy('category_name');

        return view('tutor/TutorFinder', Compact('tutors', 'subjects', 'states', 'cities', 'levels'));
    }

    public function addTutor()
    {
        //
        $allSubjects = DB::table('products')->join("categories", "products.category", "=", "categories.id")
            ->select("products.*", "categories.price as category_price", "categories.category_name as category_name", "categories.mode as mode")->get();
        return view('tutor/addTutor', Compact('allSubjects'));
    }

    public function viewTutor($id)
    {
        //
        $tutor = DB::table('tutors')->where('id', '=', $id)->first();
        $subjects = DB::table('tutor_subjects')->where('tutor_id', '=', $id)->get();
        $totalSubjects = DB::table('tutor_subjects')->where('tutor_id', '=', $id)->orderBy('id', 'desc')->count();
        $allSubjects = DB::table('products')->get();
        $commitment_fee = DB::table('tutor_commitment_fees')->where('tutor_id', '=', $id)->first();

        return view('tutor/viewTutor', Compact('subjects', 'tutor', 'allSubjects', 'totalSubjects'));
    }

    public function editTutor($id)
    {
        //
        $tutor = DB::table('tutors')->where('id', '=', $id)->first();
        $subjects = DB::table('tutor_subjects')->where('tutor_id', '=', $id)->get();
        $totalSubjects = DB::table('tutor_subjects')->where('tutor_id', '=', $id)->orderBy('id', 'desc')->count();
        $allSubjects = DB::table('products')->get();


        return view('tutor/editTutor', Compact('subjects', 'tutor', 'allSubjects', 'totalSubjects'));
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
            
            
            
          $push_notification_api = new PushNotificationLibrary();
                $title = 'Application approved for Job Ticket '. $ticket->uid;
                $message = 'Job Ticket Approved';
                $deviceToken = $tutorDeviceApproved->device_token;
                $push_notification_api->sendPushNotification($deviceToken, $title, $message);
                
        foreach ($tutorDevices as $rowDeviceToken) {
                $push_notification_api = new PushNotificationLibrary();
                $title = 'The ticket '. $ticket->uid.' you applied for has been unsuccessful. Thank you for your interest and time to apply, but until the next one!';
                $message = 'Job Ticket Rejected';
                $deviceToken = $rowDeviceToken->device_token;
                $push_notification_api->sendPushNotification($deviceToken, $title, $message);
            }
            

            
        DB::table('job_tickets')
            ->where('id', $tutorDetail->ticketID)
            ->update(['status' => 'approved', 'application_status' => 'completed', 'tutor_id' => $tutorDetail->tutorID]);

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
        $studentDetail = DB::table('students')->where('id', '=', $ticketDetal->student_id)->first();
        $customerDetail = DB::table('customers')->where('id', '=', $studentDetail->customer_id)->first();
        // $studentInvoiceInitiateTwo = array(
        //         'tutorID' => $tutorOffer->tutorID,
        //         'studentID' => $studentID->student_id,
        //         'subjectID' => $tutorOffer->subject_id,
        //         'ticketID' => $tutorOffer->ticketID,
        //         'invoiceDate' => date('Y-m-d'),
        //         'reference' => 'INV-'.$tutorOffer->ticketID,
        //         'invoiceID' => 'INV-'.$tutorOffer->ticketID,
        //         'brand' => $ticketDetal->service,
        //         'payerName' => $customerDetail->full_name,
        //         'payerEmail' => $customerDetail->email,
        //         'payerPhone' => $customerDetail->phone,
        //         'type' => "d",
        //         'debit' => $ticketDetal->totalPrice,
        //         'status' => "unpaid",
        //         'customerRemark' => "",
        //         'totalPrice' => $ticketDetal->totalPrice
        //         );

        //     DB::table('invoices')->insertGetId($studentInvoiceInitiateTwo);

        $ledgerTutorValue = array(
            'payment_reference' => Auth::user()->id,
            'user_id' => Auth::user()->id,
            'bill_no' => $ticketDetal->uid,
            'purchase_id' => $ticketDetal->uid,
            'account_id' => $tutorOffer->tutorID,
            'amount' => $ticketDetal->totalPrice * 70 / 100,
            'type' => 'c',
            'credit' => $ticketDetal->totalPrice * 70 / 100,
            'debit' => null,
            'date' => date('Y-m-d'),
            'date_2' => date('Y-m-d')
        );

        $ledgerID = DB::table('payments')->insertGetId($ledgerTutorValue);


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

        //new offer approved event
        $data=["Ticket Offer Approved"];
        event(new TutorOffers($data));

        // dd("ASd");

        return redirect()->back();
    }

    public function tutoOfferActionReject($id)
    {
        
        $tutorOffer = DB::table('tutoroffers')->where('id', '=', $id)->first();
        $ticket = DB::table("job_tickets")->find($tutorOffer->ticketID);
        $tutorDetail = DB::table('tutoroffers')->where('id', $id)->first();
        
        
        DB::table('tutoroffers')
            ->where('id', $id)
            ->update(['status' => 'rejected']);

        //new offer reject event
        $data=["Ticket Offer Rejected"];
        event(new TutorOffers($data));
        
        
       $tutorDeviceApproved = DB::table('tutor_device_tokens')
        ->where('tutor_id', $tutorDetail->tutorID)
        ->first();
        
        
        
      $push_notification_api = new PushNotificationLibrary();
            $title = 'The ticket '. $ticket->uid.' you applied for has been unsuccessful. Thank you for your interest and time to apply, but until the next one!';
            $message = 'Job Ticket Rejected';
            $deviceToken = $tutorDeviceApproved->device_token;
            $push_notification_api->sendPushNotification($deviceToken, $title, $message);
        

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

        $tickets = DB::table('student_subjects')
            ->join('job_tickets', 'student_subjects.ticket_id', '=', 'job_tickets.id')
            ->join('products', 'student_subjects.subject', '=', 'products.id')
            ->join('tutoroffers', 'student_subjects.tutor_id', '=', 'tutoroffers.tutorID')
            ->select('student_subjects.*',
                'job_tickets.*',
                'products.name as subject_name',
                'products.id as subject_id',
                'tutoroffers.tutorID', 'tutoroffers.status')
            ->where("tutoroffers.tutorID", $id)
            ->get();


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

        return view('tutor/tutorDashboard', Compact('tutor', 'tutors', 'allSubjects', 'subjects', 'tickets', 'tutorID', 'myApplicedTicektsOne', 'myApplicedTicekts'));
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
            'gender' => $request->gender,
            // 'comission_percentage' => $request->comission_percentage,
            'start_date' => $request->registration_date,
            'age' => $request->age,
            'email' => $request->email,
            'password' => Hash::make($request->loginPassword),
            'phoneNumber' => $request->phoneNumber,
            'dob' => $request->dob,
            'nric' => $request->nric,
            'street_address1' => $request->street_address1,
            'street_address2' => $request->street_address2,
            'city' => $request->city,
            'state' => $request->state,
            'marital_status' => $request->maritalStatus,
            'training_date' => $request->attendedTrainingDate,
            'postal_code' => $request->postalcode,
            'latitude' => $request->customerLatitude,
            'longitude' => $request->customerLongitude,
            'remark' => $request->remark,
            'bank_name' => $request->bankName,
            'status' => 'unverified',
            'shirt_size' => $request->shirtSize,
            'bank_account_number' => $request->bankAccountNumber
        );


        $tutorLastID = DB::table('tutors')->insertGetId($values);
        $data = $request->all();

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


        //dd("Done");
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
            'receiving_account' => $request->receivingAccount,
            'fee_payment_date' => $request->feePaymentDate,
            'bank_account_number' => $request->bankAccountNumber
        );


        $affected = DB::table('tutors')
            ->where('id', $request->id)
            ->update($values);


        //   dd($request->input('paymentAttachment')[0]);

        $data = $request->all();

        //dd($data);
        if (isset($data['paymentAttachment'])) {
            $paymentAttachments = $data['paymentAttachment'];
            if (!empty($paymentAttachments)) {
                $imageName = time() . '.' . $request->file('paymentAttachment')->extension();
                $request->file('paymentAttachment')->move(public_path('tutorPaymentAttachment'), $imageName);

                $tutorPayment = array(
                    'tutor_id' => $request->id,
                    'payment_attachment' => $imageName,
                    'payment_amount' => $data['feeAmount'],
                    'payment_date' => $data['feePaymentDate'],
                    'receiving_account' => $data['receivingAccount'],
                    'status'             => $data['payment_status'],
                );

                DB::table('tutor_commitment_fees')->insertGetId($tutorPayment);
                DB::table('tutors')->where("id", $request->id)->update(["fee_payment_date" => $data['feePaymentDate']]);
            }

            $affected = DB::table('tutors')
                ->where('id', $request->id)
                ->update(['status' => 'verified']);

            //tutor verified
            $data=["Tutor Verified"];
            event(new TutorApproved($data));
        }


        // DD("Done");


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

                    $tutor_data = array(
                        'tutor_id' => $request->id,
                        'subject' => $data['subject'][$i],

                    );
                    DB::table('tutor_subjects')->insertGetId($tutor_data);
                }
            }
        }


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
