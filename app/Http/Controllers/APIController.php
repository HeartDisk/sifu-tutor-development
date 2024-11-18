<?php

namespace App\Http\Controllers;

use App\Events\Tutor\TutorDashboard;
use App\Events\Parent\ClassAttendance;
use App\Events\Parent\SingleParentDashboard;
use Illuminate\Http\Request;
use DB;
use DateTime;
use DateInterval;
use Auth;
use Illuminate\Support\Str;
use App\Libraries\WhatsappApi;
use App\Libraries\SmsNiagaApi;
use App\Libraries\PushNotificationLibrary;
use Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Pusher\Pusher;
use App\Models\Staff;
use App\Models\User;
use App\Mail\AttendanceReportMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;
use App\Mail\TutorRegistrationMail;
use App\Mail\TutorVerificationCode;
class APIController extends Controller
{

    public function getParentToken($id)
    {
        $parent = DB::table('customers')->where('id', $id)->first();

        if (!$parent) {
            return response()->json(["code"=>404,"msg"=>"No customer found!"], 200);
        }else
        {
             return response()->json(["token"=>$parent->token], 200);
        }
    }

    public function citiesByState($id)
    {

        $cities = DB::table('cities')->where('state_id', '=', $id)->get();
        if($cities==null)
        {
            $cities=[];
        }
        return Response::json(['cities' => $cities],200);
    }

    public function APIclear()
    {
        \Artisan::call('route:clear');
        \Artisan::call('cache:clear');
        \Artisan::call('route:cache');
        \Artisan::call('config:cache');
        \Artisan::call('view:clear');
        dd('Clear cache');
    }




    public function agreeAttendance($id)
    {


        $getClassAttendedID = DB::table('class_attendeds')->where('id', '=', $id)->first();
        // dd($getClassAttendedID);

        $classScheduleData = DB::table('class_schedules')->where('id', '=', $getClassAttendedID->class_schedule_id)->first();


        $ticketDetail = DB::table('job_tickets')->where('id', '=', $getClassAttendedID->ticketID)->first();
        $tutorID = DB::table('tutor_subjects')->where('ticket_id', '=', $ticketDetail->id)->first();

        $paymentPreviousDetail = DB::table('payments')->where('bill_no', '=', $ticketDetail->uid)->first();
       
        // Original time
        $originalTime = $classScheduleData->startTime;

        // Number of hours to add
        $decimalHours = $classScheduleData->totalTime;
        $hours = floor($decimalHours); // Extract the integer part
        $minutes = ($decimalHours - $hours) * 60; // Convert decimal part to minutes

        // Convert original time to DateTime object
        $dateTime = DateTime::createFromFormat('H:i:s', $originalTime);

        // Add hours and minutes
        $dateTime->modify("+" . $hours . " hours");
        $dateTime->modify("+" . $minutes . " minutes");

        // Get the new time
        $newTime = $dateTime->format('H:i:s');

        // dd($newTime);

        // Convert original time to DateTime object
        $dateTimeOriginal = DateTime::createFromFormat('H:i:s', $originalTime);

        // Convert new time to DateTime object
        $dateTimeNew = DateTime::createFromFormat('H:i:s', $newTime);

        // Calculate difference
        $timeDifference = $dateTimeNew->diff($dateTimeOriginal);

        // Format the difference
        $timeDifferenceFormatted = $timeDifference->format('%H:%I:%S');

        //  dd($timeDifferenceFormatted);

        $id = (int)$id;
        $getClassAttendedID = (int)$getClassAttendedID->class_schedule_id;
        DB::table('class_attendeds')->where('id', $id)->update(['parent_verified' => 'YES', 'status' => 'attended', 'endTime' => $newTime, 'totalTime' => $timeDifferenceFormatted]);
        DB::table('class_schedules')->where('id', $getClassAttendedID)->update(['status' => 'attended', 'parent_verified' => 'YES']);

        // dd($id);

        $class_schedule_id = DB::table('class_attendeds')->where('id', '=', $id)->first();
        $tutor = DB::table("tutors")->where("id",$class_schedule_id->tutorID)->first();
        $student = DB::table("students")->where("id",$class_schedule_id->studentID)->first();
        $customer = DB::table("customers")->where("id",$student->customer_id)->first();
        try {

            $data = [
                "ResponseCode" => "100",
                "message" => "Class Attendance Approved"
            ];
            //tutor
            event(new TutorDashboard($data, $tutor->token));
            //parent
            event(new ClassAttendance($data, $customer->token));
            event(new SingleParentDashboard($data, $customer->token));


        } catch (Exception $e) {
            return response()->json(["ResponseCode" => "103",
                "error" => "Unable to get New Attendance"]);
        }

        return redirect()->to("/welcomeMessage");

    }

    public function disputeAttendance($id)
    {


        $class_schedule_id = DB::table('class_attendeds')->where('id', $id)->first();
        // dd($class_schedule_id);
        // dd($class_schedule_id);
        DB::table('class_attendeds')->where('id', $id)->update(['status' => 'dispute']);
        DB::table('class_schedules')->where('id', $class_schedule_id->class_schedule_id)->update(['status' => 'dispute']);
        return redirect()->to("/disputeMessage");
    }

    public function sendAttendanceToParent($ticketID, $tutorID)
    {
        // ticketID = 59
        // TutorID = 16

        return Response::json(['status' => 200, 'message' => 'Email for Attendance confirmation Sent to Parent Successfully']);

    }

    // public function loginAPISMSTest()
    // {
    //     $phone = '+60356260018';
    //     $SixDigitRandomNumber = 12345;
    //     $whatsapp_api = new WhatsappApi();
    //     $sms_api = new SmsNiagaApi();
    //     $phone_number = $phone;
    //     $message = 'Verificate Code: ' . $SixDigitRandomNumber . ' <br> From *SIFUTUTOR*';
    //     $whatsapp_api->send_message($phone_number, $message);
    //     $sms_api->sendSms($phone_number, $message);

    //     DB::table('text_messages')->insert([
    //         'recipient' => $phone_number,
    //         'message' => $message,
    //         'status' => 'sent',
    //     ]);

    // }

    public function ajaxCall(Request $request)
    {

        return Response::json($request);

        if ($data['classScheduleId'] == 1) {
            $search = DB::table('class_schedules')->where('class_schedule_id', '=', 0)->get();
            return Response::json(['search' => $search]);
        }

        if ($data['studentListId'] == 1) {
            //$search = DB::table('students')->where('class_schedule_id','=',0)->get();
            return Response::json(['student' => $data['student']]);
        }


    }

    public function getTutors()
    {
        $tutors = DB::table('tutors')->get();
        return Response::json(['tutors' => $tutors]);
    }



    public function sendnotification(Request $request)
    {
        // Check if the required fields are present in the request
        if (!$request->has(['title', 'message', 'deviceToken'])) {
            return Response::json(['error' => 'Missing required fields'], 400);
        }

        // Extract data from the request
        $title = $request->title;
        $message = $request->message;
        $deviceToken = $request->deviceToken;

        // Create an instance of PushNotificationLibrary
        $pushNotificationApi = new PushNotificationLibrary();

        // Send the push notification
        $result = $pushNotificationApi->sendPushNotification($deviceToken, $title, $message);

        // Decode the JSON string into an associative array
        $resultArray = json_decode($result, true);

        // Check if the notification was sent successfully
        if (isset($resultArray['success']) && $resultArray['success'] == 1) {
            return Response::json(['notification' => 'Notification sent successfully']);
        } else {
            return Response::json(['error' => 'Failed to send notification'], 500);
        }

    }

    public function classScheduleNotifications($tutorID)
    {
        $notifications = DB::table('notifications')
            ->join('students', 'notifications.studentID', '=', 'students.id')
            ->join('tutors', 'notifications.tutorID', '=', 'tutors.id')
            ->Leftjoin('products', 'notifications.subjectID', '=', 'products.id')
            ->where('notifications.tutorID', '=', $tutorID)
            ->whereNull('notifications.subjectID')
            ->select('notifications.id as notificationID',
                'notifications.notificationType as notificationType',
                'notifications.status as status',
                'notifications.message as notificationMessage',
                'notifications.ProgressReportMonth as notificationProgressReportMonth',

                'tutors.uid as tutorID',
                'tutors.full_name as tutorName',
                'tutors.displayName as tutorDisplayName',
                'tutors.street_address1 as tutorAddress1',
                'tutors.street_address2 as tutorAddress2',
                'tutors.city as tutorCity',
                'products.name as subjectName',
                'students.full_name as studentName',
                'students.address1 as studentAddress1',
                'students.address2 as studentAddress2',
                'students.city as studentCity')
            ->get();
        return Response::json(['notifications' => $notifications]);
    }

    public function detailedNotification($id)
    {
        $detailedNotification = DB::table('notifications')
            ->join('students', 'notifications.studentID', '=', 'students.id')
            ->join('tutors', 'notifications.tutorID', '=', 'tutors.id')
            ->join('products', 'notifications.subjectID', '=', 'products.id')
            ->where('notifications.id', '=', $id)
            ->select('notifications.id as notificationID',
                'notifications.notificationType as notificationType',
                'notifications.message as notificationMessage',
                'notifications.ProgressReportMonth as notificationProgressReportMonth',
                'tutors.uid as tutorID',
                'tutors.full_name as tutorName',
                'tutors.displayName as tutorDisplayName',
                'tutors.street_address1 as tutorAddress1',
                'tutors.street_address2 as tutorAddress2',
                'tutors.city as tutorCity',
                'products.name as subjectName',
                'students.full_name as studentName',
                'students.address1 as studentAddress1',
                'students.address2 as studentAddress2',
                'students.city as studentCity')
            ->get();
        return Response::json(['detailedNotification' => $detailedNotification]);
    }

    public function progressReportListing()
    {
        $baseUrl = rtrim(url("/template/"), '/') . '/';

        $progressReportListing = DB::table('progressReport')
            ->join('students', 'progressReport.studentID', '=', 'students.id')
            ->join('tutors', 'progressReport.tutorID', '=', 'tutors.id')
            ->join('products', 'progressReport.subjectID', '=', 'products.id')
            ->select(

                DB::raw("CONCAT('$baseUrl', progressReport.logoImage) AS logo"),
                'students.full_name as studentName',
                'students.student_id as student_id',
                'tutors.id as tutorID',
                'tutors.full_name as tutorName',
                'tutors.displayName as tutorDisplayName',
                'products.id as subjectID',
                'products.name as subjectName',
                DB::raw("DATE_FORMAT(students.created_at, '%d-%b-%Y') as submittedDate"),
                'progressReport.*'
            )
            ->orderBy("progressReport.id", "desc")
            ->get();

        return response()->json(['progressReportListing' => $progressReportListing]);

    }




    public function tutorFirstReport(Request $request)
    {

        $values = array(
            'tutorID' => $request->tutorID,
            'studentID' => $request->studentID,
            'scheduleID' => $request->scheduleID,
            'subjectID' => $request->subjectID,
            'currentDate' => $request->currentDate,
            'reportType' => 'Student Evaluation Report',

            'knowledge' => $request->knowledge,
            'knowledge2' => $request->knowledge2,
            'understanding' => $request->understanding,
            'understanding2' => $request->understanding2,
            'criticalThinking' => $request->criticalThinking,
            'criticalThinking2' => $request->criticalThinking2,
            'observation' => $request->observation,
            'additionalAssisment' => $request->additionalAssisment,
            'plan' => $request->plan
        );

        $submitClassScheduleTime = DB::table('tutorFirstSubmittedReportFromApps')->insertGetId($values);

        $row = DB::table('tutorFirstSubmittedReportFromApps')->where('id', '=', $submitClassScheduleTime)->select('reportType as reportType', 'currentDate as currentDate', 'knowledge as knowledge', 'understanding as understanding', 'analysis as analysis', 'additionalAssisment as additionalAssisment', 'plan as plan')->first();


        return Response::json(['successMessage' => 'Report Submitted Successfully', 'data' => $row]);
    }




    public function tutorFirstReportView($id)
    {
        $tutorReportListing = DB::table('tutorFirstSubmittedReportFromApps')
            ->join('students', 'tutorFirstSubmittedReportFromApps.studentID', '=', 'students.id')
            ->join('tutors', 'tutorFirstSubmittedReportFromApps.tutorID', '=', 'tutors.id')
            ->join('products', 'tutorFirstSubmittedReportFromApps.subjectID', '=', 'products.id')
            ->where('tutorFirstSubmittedReportFromApps.id', '=', $id)
            ->select('tutorFirstSubmittedReportFromApps.id as id',
                'tutorFirstSubmittedReportFromApps.reportType as reportType',
                'tutorFirstSubmittedReportFromApps.currentDate as currentDate',
                'tutorFirstSubmittedReportFromApps.currentDate as currentDate',
                'tutorFirstSubmittedReportFromApps.knowledge as knowledge',
                'tutorFirstSubmittedReportFromApps.understanding as understanding',
                'tutorFirstSubmittedReportFromApps.analysis as analysis',
                'tutorFirstSubmittedReportFromApps.additionalAssisment as additionalAssisment',
                'tutorFirstSubmittedReportFromApps.plan as plan',
                'tutors.uid as tutorID',
                'tutors.full_name as tutorName',
                'tutors.displayName as tutorDisplayName',
                'tutors.street_address1 as tutorAddress1',
                'tutors.street_address2 as tutorAddress2',
                'tutors.city as tutorCity',
                'products.name as subjectName',
                'students.uid as studentID',
                'students.full_name as studentName',
                'students.address1 as studentAddress1',
                'students.address2 as studentAddress2',
                'students.city as studentCity',
                'tutorFirstSubmittedReportFromApps.created_at as created_at')
            ->get();
        return Response::json(['tutorReportListing' => $tutorReportListing]);
    }


    public function searchJobTickets($categoryID, $subjectID, $mode)
    {


        $searchJobTickets = DB::table('job_tickets')
            ->join('student_subjects', 'student_subjects.ticket_id', '=', 'job_tickets.id')
            ->join('products', 'student_subjects.subject', '=', 'products.id')
            ->join('students', 'student_subjects.student_id', '=', 'students.id')
            ->where('job_tickets.mode', 'LIKE', $mode)
            ->Where('student_subjects.subject', 'LIKE', $subjectID)
            ->Where('products.category', 'LIKE', $categoryID)
            ->get();

        return Response::json(['JobTicketsResult' => $searchJobTickets]);
    }








    public function classScheduleAttendedStatusWithImage(Request $request)
    {


    }


    public function token(Request $request)
    {

        $values = array(
            'userId' => $request->userId,
            'token' => $request->token
        );
        $token = DB::table('fcmToken')->insertGetId($values);

        $data = DB::table('fcmToken')->where('id', '=', $token)->first();

        return Response::json(['statusCode' => 200, 'message' => 'Success', 'data' => $data]);

    }


}
