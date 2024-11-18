<?php

namespace App\Http\Controllers;

use App\Events\Parent\SingleParentDashboard;
use App\Events\Tutor\TutorDashboard;
use App\Events\TutorOffers;
use App\Models\Blog;
use App\Models\Chat;
use App\Models\ClassSchedules;
use App\Models\Invoice;
use App\Models\Student;
use Illuminate\Http\Request;
use DB;
use DateTime;
use DateInterval;
use Auth;
use Illuminate\Support\Str;
use App\Libraries\WhatsappApi;
use App\Libraries\SmsNiagaApi;
use App\Libraries\PushNotificationLibrary;
use Mockery\Exception;
use Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Pusher\Pusher;
use App\Events\MobileHomePageUpdated;
use App\Events\TicketCreated;
use App\Jobs\SendWhatsAppMessageJob;
use App\Jobs\SendSmsMessageJob;
use App\Jobs\SendPushNotificationJob;
use Carbon\Carbon;
use Dirape\Token\Token;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class ParentAPIController extends Controller
{

    public function blogs(Request $req)
    {
        $blogs = Blog::orderBy("id", "desc")->get();

        foreach ($blogs as $blog) {
            // Format the created_at field
            $blog->date_time = Carbon::parse($blog->created_at)->format('d M Y | h:i A');

            // Handle image URL
            if ($blog->headerimage == null) {
                $blog->image = "";
            } else {
                $blog->image = url("/public/MobileBlogImages" . "/" . $blog->headerimage);
            }
        }



        return response()->json(["msg" => "success", "blogs" => $blogs], 200);
    }



   public function jobTicketDetails($ticket_id)
    {
        // Initialize an empty result array
        $resultData = [];

        // Check if a ticket exists with the given ticket_id
        $ticketExists = DB::table('job_tickets')
            ->where('uid', $ticket_id)
            ->exists();

        if (!$ticketExists) {
            // Return a response indicating that no ticket was found with the given structure
            return Response::json([
                'ResponseCode' => '101',
                'error' => 'No tickets found. Invalid Ticket ID:' . $ticket_id
            ]);
        }

        // Proceed with fetching ticket details since the ticket exists
        $tickets = DB::table('job_tickets')
            ->leftjoin('products', 'products.id', '=', 'job_tickets.subjects')
            ->leftjoin('categories', 'categories.id', '=', 'products.category')
            ->leftjoin('students', 'students.id', '=', 'job_tickets.student_id')
            ->leftjoin('customers', 'customers.id', '=', 'students.customer_id')
            ->leftjoin('cities', 'customers.city', '=', 'cities.id')
            ->leftjoin('states', 'customers.state', '=', 'states.id')
            ->leftJoin('tutoroffers', 'job_tickets.id', '=', 'tutoroffers.ticketID')
            ->where('job_tickets.uid', $ticket_id)
            ->select(
                'job_tickets.*',
                'tutoroffers.id as tutor_offer_id',
                'products.name as subject_name',
                'job_tickets.uid as jtuid',
                'job_tickets.day as classDay',
                'job_tickets.time as classTime',
                'job_tickets.id as ticketID',
                'job_tickets.quantity as quantity',
                'job_tickets.tutorPereference as tutorPereference',
                'job_tickets.classFrequency as classFrequency',
                'job_tickets.specialRequest as specialRequest',
                'job_tickets.mode as mode',
                'job_tickets.tutor_id as tutor_id',
                'job_tickets.status as status',
                'job_tickets.classAddress as classAddress',
                // 'job_tickets.totalTutorApplied as totalTutorApplied',
                'job_tickets.estimate_commission as estimate_commission',
                'job_tickets.subscription as subscription',
                'job_tickets.totalPrice as totalPrice',
                'job_tickets.job_ticket_requirement as job_ticket_requirement',
                'job_tickets.totalPrice as price',
                'students.age as student_age',
                'students.specialNeed as special_need',
                'students.gender as student_gender',
                DB::raw('COUNT(DISTINCT tutoroffers.tutorID) as totalTutorApplied'),
                DB::raw('DATE_FORMAT(STR_TO_DATE(job_tickets.time, "%H:%i"), "%h:%i %p") as start_time'),
                DB::raw('DATE_FORMAT(
                    DATE_ADD(
                        STR_TO_DATE(job_tickets.time, "%H:%i"),
                        INTERVAL ROUND(job_tickets.quantity * 60) MINUTE
                    ),
                    "%h:%i %p"
                ) as end_time'),
                DB::raw("
                    CASE
                        WHEN DATE(job_tickets.created_at) = CURDATE() THEN 'Today'
                        WHEN DATE(job_tickets.created_at) = CURDATE() - INTERVAL 1 DAY THEN 'Yesterday'
                        WHEN DATE(job_tickets.created_at) >= CURDATE() - INTERVAL 7 DAY THEN CONCAT(DATEDIFF(CURDATE(), DATE(job_tickets.created_at)), ' days ago')
                        WHEN DATE(job_tickets.created_at) >= CURDATE() - INTERVAL 30 DAY THEN CONCAT(FLOOR(DATEDIFF(CURDATE(), DATE(job_tickets.created_at)) / 7), ' weeks ago')
                        WHEN DATE(job_tickets.created_at) >= CURDATE() - INTERVAL 365 DAY THEN CONCAT(FLOOR(DATEDIFF(CURDATE(), DATE(job_tickets.created_at)) / 30), ' months ago')
                        ELSE CONCAT(FLOOR(DATEDIFF(CURDATE(), DATE(job_tickets.created_at)) / 365), ' years ago')
                    END as ticket_created_at
                "),
                'categories.category_name as level',
                'job_tickets.per_class_commission_before_eight_hours as per_class_commission_before_eight_hours',
                'job_tickets.per_class_commission_after_eight_hours as per_class_commission_after_eight_hours',
                'products.id as subject_id',
                'products.id as subjectID',
                'students.full_name as studentName',
                'students.gender as studentGender',
                'students.age as studentAge',
                'students.address1 as studentAddress',
                'categories.category_name as categoryName',
                'categories.id as categoryID',
                'cities.name as city',
                'cities.id as cityID',
                'states.id as stateID',
                'states.name as state',
                'customers.created_at as customer_joining_date',
                'customers.full_name as customer_full_name',
                'customers.email as customer_email',
                'customers.phone as customer_phone',
                'customers.id as customer_id'
            )
            ->orderBy('job_tickets.id', 'DESC')
            ->get();

        foreach ($tickets as $ticket) {
            $inputString = $ticket->classDay;
            $outputString = stripslashes(trim($inputString, "\""));

            $totalHours = $ticket->classFrequency * $ticket->quantity;
            $hoursBeforeEight = min($totalHours, 8);
            $hoursAfterEight = max($totalHours - 8, 0);

            $commitmentFeeCheck = DB::table("customer_commitment_fees")->where("customer_id", $ticket->customer_id)->first();
            $commitmentFeePaid = $commitmentFeeCheck ? true : false;

            $ticketData = [
                'subject_name' => $ticket->subject_name,
                'jtuid' => $ticket->jtuid,
                'classDay' => $outputString,
                'classTime' => $ticket->classTime,
                'ticketID' => $ticket->ticketID,
                'tutor_id' => $ticket->tutor_id,
                'status' => $ticket->status,
                'totalTutorApplied' => $ticket->totalTutorApplied,
                'tutorPereference' => $ticket->tutorPereference,
                'classFrequency' => $ticket->classFrequency,
                'quantity' => $ticket->quantity,
                'mode' => $ticket->mode,
                'per_class_commission_before_eight_hours' => $ticket->per_class_commission_before_eight_hours * $hoursBeforeEight,
                'per_class_commission_after_eight_hours' => $ticket->per_class_commission_after_eight_hours * $hoursAfterEight,
                'subscription' => $ticket->subscription,
                'classAddress' => $ticket->classAddress,
                'classState' => $ticket->classState,
                'classCity' => $ticket->classCity,
                'classPostalCode' => $ticket->classPostalCode,
                'specialRequest' => $ticket->specialRequest,
                'subject_id' => $ticket->subject_id,
                'subjectID' => $ticket->subject_id,
                'price' => $ticket->estimate_commission_display_tutor,
                'student_name' => $ticket->studentName,
                'student_gender' => $ticket->studentGender,
                'student_age' => $ticket->student_age,
                'special_need' => $ticket->special_need,
                'student_address' => $ticket->classAddress,
                'city' => $ticket->city,
                'state' => $ticket->state,
                'cityID' => $ticket->cityID,
                'stateID' => $ticket->stateID,
                'categoryName' => $ticket->categoryName,
                'categoryID' => $ticket->categoryID,
                'price' => $ticket->totalPrice,
                'level' => $ticket->level,
                'customer_joining_date' => $ticket->customer_joining_date,
                'job_ticket_requirement' => $ticket->job_ticket_requirement,
                'ticket_created_date' => $ticket->ticket_created_at,
                'start_time' => $ticket->start_time,
                'end_time' => $ticket->end_time,
                'jobTicketExtraStudents' => [],
                'customers_full_name' => $ticket->customer_full_name,
                'customers_email' => $ticket->customer_email,
                'customers_phone' => $ticket->customer_phone,
                'commitmentFee' => $commitmentFeeCheck ? $commitmentFeeCheck->payment_amount : "",
                'commitmentFeePaid' => $commitmentFeePaid
            ];

            $days = explode(',', str_replace('"', '', $ticket->classDay));
            if (in_array('Sat', $days) || in_array('Sun', $days)) {
                $classDayType = 'weekend';
            } else {
                $classDayType = 'weekday';
            }

            $ticketData['classDayType'] = $classDayType;

            $students = DB::table('job_ticket_students')->where('job_ticket_id', '=', $ticket->ticketID)->get();

            foreach ($students as $student) {
                $studentData = [
                    'student_name' => $student->student_name,
                    'student_age' => $student->student_age,
                    'student_gender' => $student->student_gender,
                    'year_of_birth' => $student->year_of_birth,
                    'special_need' => $student->special_need,
                    'student_id' => $student->student_id,
                ];
                $ticketData['jobTicketExtraStudents'][] = $studentData;
            }

            $resultData[] = $ticketData;
        }

        return Response::json([
            'ResponseCode' => '100',
            'message' => 'Data retrieved successfully.',
            'data' => $resultData
        ]);
    }


     public function getCategories()
    {
        $categories = DB::table('categories')->get();
        return Response::json(['categories' => $categories]);
    }

    public function blogsDetails($id)
    {
        $blog = Blog::find($id);
        if ($blog) {
            $blog->image = url("/public/MobileBlogImages" . "/" . $blog->headerimage);

            // Format the created_at field
            $blog->date_time = Carbon::parse($blog->created_at)->format('d M Y | h:i A');

            return response()->json(["msg" => "success", "blog" => $blog], 200);
        } else {
            return response()->json(["msg" => "No blog found", "blog" => []], 200);
        }
    }

    public function sendMessage(Request $request)
    {
        $chat = new Chat();
        $chat->from = $request->from;
        $chat->to = $request->to;
        $chat->message = $request->message;
        $chat->save();
        return response()->json(["message" => "Message Sent Successfully"], 200);

    }

    public function getChats($from, $to)
    {
        $chats = Chat::where(["from" => $from, "to" => $to])->orderBy("id", "desc")->get();
        if ($chats == null) {
            return response()->json(["message" => "No chats found", "chats" => []], 200);

        } else {
            return response()->json(["message" => "Success", "chats" => $chats], 200);

        }
    }

    public function deleteChat($to)
    {
        $chat = Chat::where("to", $to)->get();
        foreach ($chat as $ct) {
            Chat::find($ct->id)->delete();
        }
        return response()->json(["message" => "Chat deleted Successfully"], 200);

    }

     public function parentLogin(Request $request)
    {
        $phone = $request->phone;
        $tutorDetail = DB::table('customers')
            ->where('phone', '=', $phone)
            ->first();

        // If the customer is already registered
        if ($tutorDetail) {
            $SixDigitRandomNumber = rand(100000, 999999);

            $values = [
                'parentID' => $tutorDetail->id,
                'code' => $SixDigitRandomNumber,
                'token' => bin2hex(random_bytes(16)),
            ];

            $tutorVerificationCodeCheck = DB::table('verificationCode')
                ->where('parentID', $tutorDetail->id)
                ->first();

            if ($tutorVerificationCodeCheck) {
                $updateResult = DB::table('verificationCode')
                    ->where('parentID', $tutorDetail->id)
                    ->update($values);
            } else {
                $insertResult = DB::table('verificationCode')->insert($values);
            }

            if (isset($updateResult) && $updateResult || isset($insertResult) && $insertResult) {
                $message = "Here's your SifuTutor verification code: $SixDigitRandomNumber. It's valid for the next 10 minutes. Thank you!";
                $this->sendVerificationMessage($phone, $message);

                return Response::json([
                    'ResponseCode' => "100",
                    'message' => 'Login Successfully',
                    'data' => [
                        'parent_id' => $tutorDetail->id,
                        'contact' => $tutorDetail->phone,
                        'token' => $values['token']
                    ]
                ]);
            } else {
                return Response::json([
                    'ResponseCode' => "103",
                    'message' => 'Failed to update or insert verification code.',
                ]);
            }
        }

        // If the customer is not registered, register as a new customer
        $uuidForTutor = rand(100, 99999);
        $values = [
            'uid' => 'TU-' . $uuidForTutor,
            'phone' => $phone,
            'status' => 'unverified',
            'whatsapp' => $phone,
            'token' => (new Token())->Unique('customers', 'token', 60)
        ];
        $tutorLastID = DB::table('customers')->insertGetId($values);

        if ($tutorLastID) {
            $tutorDetail = DB::table('customers')
                ->where('id', '=', $tutorLastID)
                ->first();

            $SixDigitRandomNumber = rand(100000, 999999);
            DB::table('verificationCode')->where('parentID', $tutorDetail->id)->delete();
            $valuesVC = [
                'parentID' => $tutorLastID,
                'code' => $SixDigitRandomNumber,
                'token' => bin2hex(random_bytes(16)),
            ];
            $insertVCResult = DB::table('verificationCode')->insert($valuesVC);

            if ($insertVCResult) {
                $message = "Here's your SifuTutor verification code: $SixDigitRandomNumber. It's valid for the next 10 minutes. Thank you!";
                $this->sendVerificationMessage($phone, $message);

                return response()->json([
                    'ResponseCode' => "100",
                    'message' => 'New Parent Registered Successfully.',
                    'data' => [
                        'parent_id' => $tutorDetail->id,
                        'contact' => $tutorDetail->phone,
                        'token' => $values['token']
                    ]
                ]);
            } else {
                return response()->json([
                    'ResponseCode' => "103",
                    'message' => 'Failed to insert verification code for the new parent in database.',
                ]);
            }
        } else {
            return response()->json([
                'ResponseCode' => "103",
                'message' => 'Failed to register new parent in database.',
            ]);
        }
    }


    private function sendVerificationMessage($phone, $message)
    {
        $whatsapp_api = new WhatsappApi();
        $sms_api = new SmsNiagaApi();
        $whatsapp_api->send_message($phone, $message);
        $sms_api->sendSms($phone, $message);
    }

//     public function appParentRegister(Request $request)
//     {


//         $tables = ['customers', 'staffs', 'tutors', 'users'];
//         $results = [];

//         foreach ($tables as $table) {
//             $result = DB::table($table)->where('email', $request->email)->first();

//             if ($result) {
//                 $results[$table] = $result;
//             }
//         }

//         if (!empty($results)) {

//             return Response::json(['status' => 200, 'Msg' => "Email already exist"]);
//         }


//         $imageName = "";
//         if ($request->tutorImage != null) {
//             $imageName = time() . '.' . $request->profileImage->extension();
//             $request->profileImage->move(public_path('userProfileImage'), $imageName);
//         }

//         $values = array(

//             'email' => $request->email,
//             'phone' => $request->phoneNumber,
//             'full_name' => $request->fullName,
//             'displayName' => $request->fullName,
//             'status' => "unverified",


//         );


//         $tutorLastID = DB::table('customers')->where('id', $request->tutorId)->update($values);

//         $to = $request->email;
//         $subject = "New parent Registration:";

//         $message = "<html
//         xmlns='ttp://www.w3.org/1999/xhtml'
//         xmlns:v='urn:schemas-microsoft-com:vml'
//         xmlns:o='urn:schemas-microsoft-com:office:office'
// >
// <head>
//     <!--[if gte mso 9]>
//     <xml>
//         <o:OfficeDocumentSettings>
//             <o:AllowPNG/>
//             <o:PixelsPerInch>96</o:PixelsPerInch>
//         </o:OfficeDocumentSettings>
//     </xml>
//     <![endif]-->
//     <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>
//     <meta name='viewport' content='width=device-width, initial-scale=1.0'/>
//     <meta name='x-apple-disable-message-reformatting'/>
//     <!--[if !mso
//     ]><!-->
//     <meta
//             http-equiv='-UA-Compatible'
//             content='IE=edge'
//     /><!--<![endif]-->
//     <title></title>

//     <style type='text/css'>
//         @media only screen and (min-width: 620px) {
//             .u-row {
//                 width: 600px !important;
//             }

//             .u-row .u-col {
//                 vertical-align: top;
//             }

//             .u-row .u-col-33p33 {
//                 width: 199.98px !important;
//             }

//             .u-row .u-col-100 {
//                 width: 600px !important;
//             }
//         }

//         @media (max-width: 620px) {
//             .u-row-container {
//                 max-width: 100% !important;
//                 padding-left: 0px !important;
//                 padding-right: 0px !important;
//             }

//             .u-row .u-col {
//                 min-width: 320px !important;
//                 max-width: 100% !important;
//                 display: block !important;
//             }

//             .u-row {
//                 width: 100% !important;
//             }

//             .u-col {
//                 width: 100% !important;
//             }

//             .u-col > div {
//                 margin: 0 auto;
//             }
//         }

//         body {
//             margin: 0;
//             padding: 0;
//         }

//         table,
//         tr,
//         td {
//             vertical-align: top;
//             border-collapse: collapse;
//         }

//         p {
//             margin: 0;
//         }

//         .ie-container table,
//         .mso-container table {
//             table-layout: fixed;
//         }

//         * {
//             line-height: inherit;
//         }

//         a[x-apple-data-detectors='true'] {
//             color: inherit !important;
//             text-decoration: none !important;
//         }

//         table,
//         td {
//             color: #000000;
//         }

//         #u_body a {
//             color: #0000ee;
//             text-decoration: underline;
//         }

//         @media (max-width: 480px) {
//             #u_content_heading_2 .v-container-padding-padding {
//                 padding: 0px 20px !important;
//             }

//             #u_content_heading_2 .v-font-size {
//                 font-size: 46px !important;
//             }

//             #u_content_text_1 .v-container-padding-padding {
//                 padding: 10px 30px !important;
//             }

//             #u_content_text_1 .v-text-align {
//                 text-align: center !important;
//             }

//             #u_content_text_1 .v-line-height {
//                 line-height: 140% !important;
//             }

//             #u_content_heading_3 .v-container-padding-padding {
//                 padding: 0px 20px !important;
//             }

//             #u_content_button_2 .v-size-width {
//                 width: 62% !important;
//             }

//             #u_content_text_2 .v-container-padding-padding {
//                 padding: 10px 30px !important;
//             }

//             #u_content_heading_4 .v-container-padding-padding {
//                 padding: 0px 30px !important;
//             }

//             #u_content_text_10 .v-container-padding-padding {
//                 padding: 10px 30px !important;
//             }

//             #u_content_text_9 .v-container-padding-padding {
//                 padding: 10px 30px !important;
//             }
//         }
//     </style>

//     <!--[if !mso
//     ]><!-->
//     <link
//             href='https://fonts.googleapis.com/css2?family=Arvo&amp;display=swap'
//             rel='stylesheet'
//             type='text/css'
//     />
//     <link
//             href='https://fonts.googleapis.com/css?family=Montserrat:400,700'
//             rel='stylesheet'
//             type='text/css'
//     /><!--<![endif]-->
// </head>

// <body
//         class='clean-body u_body'
//         style='
//       margin: 0;
//       padding: 0;
//       -webkit-text-size-adjust: 100%;
//       background-color: #e7e7e7;
//       color: #000000;
//     '
//         cz-shortcut-listen='true'
// >
// <!--[if IE]>
// <div class='e-container'><![endif]-->
// <!--[if mso]>
// <div class='so-container'><![endif]-->
// <table
//         id='u_body'
//         style='
//         border-collapse: collapse;
//         table-layout: fixed;
//         border-spacing: 0;
//         mso-table-lspace: 0pt;
//         mso-table-rspace: 0pt;
//         vertical-align: top;
//         min-width: 320px;
//         margin: 0 auto;
//         background-color: #e7e7e7;
//         width: 100%;
//       '
//         cellpadding='0'
//         cellspacing='0'
// >
//     <tbody>
//     <tr style='vertical-align: top'>
//         <td
//                 style='
//               word-break: break-word;
//               border-collapse: collapse !important;
//               vertical-align: top;
//             '
//         >
//             <!--[if (mso)|(IE)]>
//             <table width='00%' cellpadding='0' cellspacing='0' border='0'>
//                 <tr>
//                     <td align='center' style='background-color: #e7e7e7;'><![endif]-->

//             <div
//                     class='u-row-container'
//                     style='padding: 0px; background-color: #f5dff1'
//             >
//                 <div
//                         class='u-row'
//                         style='
//                   margin: 0 auto;
//                   min-width: 320px;
//                   max-width: 600px;
//                   overflow-wrap: break-word;
//                   word-wrap: break-word;
//                   word-break: break-word;
//                   background-color: transparent;
//                 '
//                 >
//                     <div
//                             style='
//                     border-collapse: collapse;
//                     display: table;
//                     width: 100%;
//                     height: 100%;
//                     background-color: transparent;
//                   '
//                     >
//                         <!--[if (mso)|(IE)]>
//                         <table width='00%' cellpadding='0' cellspacing='0' border='0'>
//                             <tr>
//                                 <td style='padding: 0px;background-color: #f5dff1;' align='center'>
//                                     <table cellpadding='0' cellspacing='0' border='0' style='width:600px;'>
//                                         <tr style='background-color: transparent;'><![endif]-->

//                         <!--[if (mso)|(IE)]>
//                         <td align='enter' width='600'
//                             style='width: 600px;padding: 60px 0px 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;'
//                             valign='top'><![endif]-->
//                         <div
//                                 class='u-col u-col-100'
//                                 style='
//                       max-width: 320px;
//                       min-width: 600px;
//                       display: table-cell;
//                       vertical-align: top;
//                     '
//                         >
//                             <div style='height: 100%; width: 100% !important'>
//                                 <!--[if (!mso)&(!IE)]><!-->
//                                 <div
//                                         style='
//                           box-sizing: border-box;
//                           height: 100%;
//                           padding: 60px 0px 0px;
//                           border-top: 0px solid transparent;
//                           border-left: 0px solid transparent;
//                           border-right: 0px solid transparent;
//                           border-bottom: 0px solid transparent;
//                         '
//                                 ><!--<![endif]-->
//                                     <table
//                                             id='u_content_heading_2'
//                                             style='font-family: 'Montserrat', sans-serif'
//                                             role='presentation'
//                                             cellpadding='0'
//                                             cellspacing='0'
//                                             width='100%'
//                                             border='0'
//                                     >
//                                         <tbody>
//                                         <tr>
//                                             <td
//                                                     class='v-container-padding-padding'
//                                                     style='
//                                   overflow-wrap: break-word;
//                                   word-break: break-word;
//                                   padding: 0px;
//                                   font-family: 'Montserrat', sans-serif;
//                                 '
//                                                     align='left'
//                                             >
//                                                 <h1
//                                                         class='v-text-align v-line-height v-font-size'
//                                                         style='
//                                     margin: 0px;
//                                     color: #6f59a0;
//                                     line-height: 100%;
//                                     text-align: center;
//                                     word-wrap: break-word;
//                                     font-family: Arvo;
//                                     font-size: 70px;
//                                     font-weight: 400;
//                                   '
//                                                 >
//                                                     <div>
//                                                         <div>
//                                                             <div
//                                                             ><strong
//                                                             >Hi, Welcome to Sifututor!</strong
//                                                             ></div
//                                                             >
//                                                         </div>
//                                                     </div>
//                                                 </h1
//                                                 >
//                                             </td>
//                                         </tr>
//                                         </tbody>
//                                     </table>

//                                     <table
//                                             id='u_content_text_1'
//                                             style='font-family: 'Montserrat', sans-serif'
//                                             role='presentation'
//                                             cellpadding='0'
//                                             cellspacing='0'
//                                             width='100%'
//                                             border='0'
//                                     >
//                                         <tbody>
//                                         <tr>
//                                             <td
//                                                     class='v-container-padding-padding'
//                                                     style='
//                                   overflow-wrap: break-word;
//                                   word-break: break-word;
//                                   padding: 20px 30px 10px;
//                                   font-family: 'Montserrat', sans-serif;
//                                 '
//                                                     align='left'
//                                             >
//                                                 <div
//                                                         class='v-text-align v-line-height v-font-size'
//                                                         style='
//                                     font-size: 14px;
//                                     color: #d8317d;
//                                     line-height: 160%;
//                                     text-align: center;
//                                     word-wrap: break-word;
//                                   '
//                                                 >
//                                                     <p
//                                                             style='
//                                       font-size: 14px;
//                                       line-height: 160%;
//                                       text-align: center;
//                                     '
//                                                     ><span
//                                                             style='
//                                         font-size: 24px;
//                                         line-height: 38.4px;
//                                       '
//                                                     ><em
//                                                     ><span
//                                                             style='
//                                             line-height: 38.4px;
//                                             font-size: 24px;
//                                           '
//                                                     >'This Could be the Start of Something
//                                           Awesome'</span
//                                                     ></em
//                                                     ></span
//                                                     ></p
//                                                     >
//                                                 </div>
//                                             </td>
//                                         </tr>
//                                         </tbody>
//                                     </table>

//                                     <table
//                                             style='font-family: 'Montserrat', sans-serif'
//                                             role='presentation'
//                                             cellpadding='0'
//                                             cellspacing='0'
//                                             width='100%'
//                                             border='0'
//                                     >
//                                         <tbody>
//                                         <tr>
//                                             <td
//                                                     class='v-container-padding-padding'
//                                                     style='
//                                   overflow-wrap: break-word;
//                                   word-break: break-word;
//                                   padding: 120px 0px 0px;
//                                   font-family: 'Montserrat', sans-serif;
//                                 '
//                                                     align='left'
//                                             >
//                                                 <table
//                                                         width='100%'
//                                                         cellpadding='0'
//                                                         cellspacing='0'
//                                                         border='0'
//                                                 >
//                                                     <tbody
//                                                     >
//                                                     <tr>
//                                                         <td
//                                                                 class='v-text-align'
//                                                                 style='
//                                           padding-right: 0px;
//                                           padding-left: 0px;
//                                         '
//                                                                 align='center'
//                                                         >
//                                                             <img
//                                                                     align='center'
//                                                                     border='0'
//                                                                     src='https://cdn.templates.unlayer.com/assets/1661428767276-img.png'
//                                                                     alt='image'
//                                                                     title='image'
//                                                                     style='
//                                             outline: none;
//                                             text-decoration: none;
//                                             -ms-interpolation-mode: bicubic;
//                                             clear: both;
//                                             display: inline-block !important;
//                                             border: none;
//                                             height: auto;
//                                             float: none;
//                                             width: 100%;
//                                             max-width: 480px;
//                                           '
//                                                                     width='480'
//                                                             />
//                                                         </td>
//                                                     </tr>
//                                                     </tbody
//                                                     >
//                                                 </table>
//                                             </td>
//                                         </tr>
//                                         </tbody>
//                                     </table>

//                                     <!--[if (!mso)&(!IE)]><!--></div
//                                 ><!--<![endif]-->
//                             </div>
//                         </div>
//                         <!--[if (mso)|(IE)]></td><![endif]-->
//                         <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
//                     </div>
//                 </div>
//             </div>

//             <div
//                     class='u-row-container'
//                     style='padding: 0px; background-color: #ffffff'
//             >
//                 <div
//                         class='u-row'
//                         style='
//                   margin: 0 auto;
//                   min-width: 320px;
//                   max-width: 600px;
//                   overflow-wrap: break-word;
//                   word-wrap: break-word;
//                   word-break: break-word;
//                   background-color: transparent;
//                 '
//                 >
//                     <div
//                             style='
//                     border-collapse: collapse;
//                     display: table;
//                     width: 100%;
//                     height: 100%;
//                     background-color: transparent;
//                   '
//                     >
//                         <!--[if (mso)|(IE)]>
//                         <table width='00%' cellpadding='0' cellspacing='0' border='0'>
//                             <tr>
//                                 <td style='padding: 0px;background-color: #ffffff;' align='center'>
//                                     <table cellpadding='0' cellspacing='0' border='0' style='width:600px;'>
//                                         <tr style='background-color: transparent;'><![endif]-->

//                         <!--[if (mso)|(IE)]>
//                         <td align='enter' width='600'
//                             style='width: 600px;padding: 60px 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;'
//                             valign='top'><![endif]-->
//                         <div
//                                 class='u-col u-col-100'
//                                 style='
//                       max-width: 320px;
//                       min-width: 600px;
//                       display: table-cell;
//                       vertical-align: top;
//                     '
//                         >
//                             <div
//                                     style='
//                         height: 100%;
//                         width: 100% !important;
//                         border-radius: 0px;
//                         -webkit-border-radius: 0px;
//                         -moz-border-radius: 0px;
//                       '
//                             >
//                                 <!--[if (!mso)&(!IE)]><!-->
//                                 <div
//                                         style='
//                           box-sizing: border-box;
//                           height: 100%;
//                           padding: 60px 0px;
//                           border-top: 0px solid transparent;
//                           border-left: 0px solid transparent;
//                           border-right: 0px solid transparent;
//                           border-bottom: 0px solid transparent;
//                           border-radius: 0px;
//                           -webkit-border-radius: 0px;
//                           -moz-border-radius: 0px;
//                         '
//                                 ><!--<![endif]-->
//                                     <table
//                                             id='u_content_heading_3'
//                                             style='font-family: 'Montserrat', sans-serif'
//                                             role='presentation'
//                                             cellpadding='0'
//                                             cellspacing='0'
//                                             width='100%'
//                                             border='0'
//                                     >
//                                         <tbody>

//                                         </tbody>
//                                     </table>

//                                     <table
//                                             id='u_content_text_2'
//                                             style='font-family: 'Montserrat', sans-serif'
//                                             role='presentation'
//                                             cellpadding='0'
//                                             cellspacing='0'
//                                             width='100%'
//                                             border='0'
//                                     >
//                                         <tbody>
//                                         <tr>
//                                             <td
//                                                     class='v-container-padding-padding'
//                                                     style='
//                                   overflow-wrap: break-word;
//                                   word-break: break-word;
//                                   padding: 10px;
//                                   font-family: 'Montserrat', sans-serif;
//                                 '
//                                                     align='left'
//                                             >
//                                                 <div
//                                                         class='v-text-align v-line-height v-font-size'
//                                                         style='
//                                     font-size: 14px;
//                                     line-height: 140%;
//                                     text-align: center;
//                                     word-wrap: break-word;
//                                   '
//                                                 >
//                                                     <p style='font-size: 14px; line-height: 140%'
//                                                     >We are thrilled to welcome you to the SifuTutor family! As an
//                                                         esteemed educator, your dedication to fostering learning aligns
//                                                         perfectly with our mission to provide top-notch education to
//                                                         students worldwide.

//                                                         At SifuTutor, we believe in creating an environment where
//                                                         knowledge knows no bounds. Your expertise and passion for
//                                                         teaching will undoubtedly contribute significantly to our
//                                                         community, empowering students to reach new heights in their
//                                                         academic journey.&nbsp;</p
//                                                     >
//                                                 </div>
//                                             </td>
//                                         </tr>
//                                         </tbody>
//                                     </table>

//                                     <!--[if (!mso)&(!IE)]><!--></div
//                                 ><!--<![endif]-->
//                             </div>
//                         </div>
//                         <!--[if (mso)|(IE)]></td><![endif]-->
//                         <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
//                     </div>
//                 </div>
//             </div>

//             <div
//                     class='u-row-container'
//                     style='padding: 0px; background-color: #e6e2e2'
//             >
//                 <div
//                         class='u-row'
//                         style='
//                   margin: 0 auto;
//                   min-width: 320px;
//                   max-width: 600px;
//                   overflow-wrap: break-word;
//                   word-wrap: break-word;
//                   word-break: break-word;
//                   background-color: transparent;
//                 '
//                 >
//                     <div
//                             style='
//                     border-collapse: collapse;
//                     display: table;
//                     width: 100%;
//                     height: 100%;
//                     background-color: transparent;
//                   '
//                     >
//                         <!--[if (mso)|(IE)]>
//                         <table width='00%' cellpadding='0' cellspacing='0' border='0'>
//                             <tr>
//                                 <td style='padding: 0px;background-color: #ffffff;' align='center'>
//                                     <table cellpadding='0' cellspacing='0' border='0' style='width:600px;'>
//                                         <tr style='background-color: transparent;'><![endif]-->

//                         <!--[if (mso)|(IE)]>
//                         <td align='enter' width='600'
//                             style='width: 600px;padding: 60px 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;'
//                             valign='top'><![endif]-->
//                         <div
//                                 class='u-col u-col-100'
//                                 style='
//                       max-width: 320px;
//                       min-width: 600px;
//                       display: table-cell;
//                       vertical-align: top;
//                     '
//                         >
//                             <div
//                                     style='
//                         height: 100%;
//                         width: 100% !important;
//                         border-radius: 0px;
//                         -webkit-border-radius: 0px;
//                         -moz-border-radius: 0px;
//                       '
//                             >
//                                 <!--[if (!mso)&(!IE)]><!-->
//                                 <div
//                                         style='
//                           box-sizing: border-box;
//                           height: 100%;
//                           padding: 60px 0px;
//                           border-top: 0px solid transparent;
//                           border-left: 0px solid transparent;
//                           border-right: 0px solid transparent;
//                           border-bottom: 0px solid transparent;
//                           border-radius: 0px;
//                           -webkit-border-radius: 0px;
//                           -moz-border-radius: 0px;
//                         '
//                                 ><!--<![endif]-->
//                                     <table
//                                             id='u_content_text_10'
//                                             style='font-family: 'Montserrat', sans-serif'
//                                             role='presentation'
//                                             cellpadding='0'
//                                             cellspacing='0'
//                                             width='100%'
//                                             border='0'
//                                     >
//                                         <tbody>
//                                         <tr>
//                                             <td
//                                                     class='v-container-padding-padding'
//                                                     style='
//                                   overflow-wrap: break-word;
//                                   word-break: break-word;
//                                   padding: 10px;
//                                   font-family: 'Montserrat', sans-serif;
//                                 '
//                                                     align='left'
//                                             >
//                                                 <div
//                                                         class='v-text-align v-line-height v-font-size'
//                                                         style='
//                                     font-size: 14px;
//                                     line-height: 140%;
//                                     text-align: center;
//                                     word-wrap: break-word;
//                                   '
//                                                 >
//                                                     <p style='font-size: 14px; line-height: 140%'
//                                                     >&copy; Copyright 2024 Sifututor.</p
//                                                     >
//                                                     <p style='font-size: 14px; line-height: 140%'
//                                                     >All rights reserved.&nbsp;</p
//                                                     >
//                                                 </div>
//                                             </td>
//                                         </tr>
//                                         </tbody>
//                                     </table>

//                                     <table
//                                             style='font-family: 'Montserrat', sans-serif'
//                                             role='presentation'
//                                             cellpadding='0'
//                                             cellspacing='0'
//                                             width='100%'
//                                             border=''
//                                     >
//                                         <tbody>
//                                         <tr>
//                                             <td
//                                                     class='v-container-padding-padding'
//                                                     style='
//                                   overflow-wrap: break-word;
//                                   word-break: break-word;
//                                   padding: 10px;
//                                   font-family: 'Montserrat', sans-serif;
//                                 '
//                                                     align='left'
//                                             >
//                                                 <div align='center'>
//                                                     <div style='display: table; max-width: 175px'>
//                                                         <!--[if (mso)|(IE)]>
//                                                         <table width='75' cellpadding='0' cellspacing='0' border='0'>
//                                                             <tr>
//                                                                 <td style='border-collapse:collapse;' align='center'>
//                                                                     <table width='100%' cellpadding='0' cellspacing='0'
//                                                                           border='0'
//                                                                           style='border-collapse:collapse; mso-table-lspace: 0pt;mso-table-rspace: 0pt; width:175px;'>
//                                                                         <tr><![endif]-->

//                                                         <!--[if (mso)|(IE)]>
//                                                         <td width='2' style='width:32px; padding-right: 12px;'
//                                                             valign='top'><![endif]-->
//                                                         <table
//                                                                 align='left'
//                                                                 border='0'
//                                                                 cellspacing='0'
//                                                                 cellpadding='0'
//                                                                 width='32'
//                                                                 height='32'
//                                                                 style='
//                                         width: 32px !important;
//                                         height: 32px !important;
//                                         display: inline-block;
//                                         border-collapse: collapse;
//                                         table-layout: fixed;
//                                         border-spacing: 0;
//                                         mso-table-lspace: 0pt;
//                                         mso-table-rspace: 0pt;
//                                         vertical-align: top;
//                                         margin-right: 12px;
//                                       '
//                                                         >
//                                                             <tbody
//                                                             >
//                                                             <tr style='vertical-align: top'
//                                                             >
//                                                                 <td
//                                                                         align='left'
//                                                                         valign='middle'
//                                                                         style='
//                                               word-break: break-word;
//                                               border-collapse: collapse !important;
//                                               vertical-align: top;
//                                             '
//                                                                 >
//                                                                     <a
//                                                                             href='javascript:void(0)'
//                                                                             title='Facebook'
//                                                                             target='_blank'
//                                                                     >
//                                                                         <img
//                                                                                 src='https://cdn.tools.unlayer.com/social/icons/circle/facebook.png'
//                                                                                 alt='Facebook'
//                                                                                 title='Facebook'
//                                                                                 width='32'
//                                                                                 style='
//                                                   outline: none;
//                                                   text-decoration: none;
//                                                   -ms-interpolation-mode: bicubic;
//                                                   clear: both;
//                                                   display: block !important;
//                                                   border: none;
//                                                   height: auto;
//                                                   float: none;
//                                                   max-width: 32px !important;
//                                                 '
//                                                                         />
//                                                                     </a></td
//                                                                 >
//                                                             </tr>
//                                                             </tbody
//                                                             >
//                                                         </table>
//                                                         <!--[if (mso)|(IE)]></td><![endif]-->

//                                                         <!--[if (mso)|(IE)]>
//                                                         <td width='2' style='width:32px; padding-right: 12px;'
//                                                             valign='top'><![endif]-->
//                                                         <table
//                                                                 align='left'
//                                                                 border='0'
//                                                                 cellspacing='0'
//                                                                 cellpadding='0'
//                                                                 width='32'
//                                                                 height='32'
//                                                                 style='
//                                         width: 32px !important;
//                                         height: 32px !important;
//                                         display: inline-block;
//                                         border-collapse: collapse;
//                                         table-layout: fixed;
//                                         border-spacing: 0;
//                                         mso-table-lspace: 0pt;
//                                         mso-table-rspace: 0pt;
//                                         vertical-align: top;
//                                         margin-right: 12px;
//                                       '
//                                                         >
//                                                             <tbody
//                                                             >
//                                                             <tr style='vertical-align: top'
//                                                             >
//                                                                 <td
//                                                                         align='left'
//                                                                         valign='middle'
//                                                                         style='
//                                               word-break: break-word;
//                                               border-collapse: collapse !important;
//                                               vertical-align: top;
//                                             '
//                                                                 >
//                                                                     <a
//                                                                             href='javascript:void(0)'
//                                                                             title='Twitter'
//                                                                             target='_blank'
//                                                                     >
//                                                                         <img
//                                                                                 src='https://cdn.tools.unlayer.com/social/icons/circle/twitter.png'
//                                                                                 alt='Twitter'
//                                                                                 title='Twitter'
//                                                                                 width='32'
//                                                                                 style='
//                                                   outline: none;
//                                                   text-decoration: none;
//                                                   -ms-interpolation-mode: bicubic;
//                                                   clear: both;
//                                                   display: block !important;
//                                                   border: none;
//                                                   height: auto;
//                                                   float: none;
//                                                   max-width: 32px !important;
//                                                 '
//                                                                         />
//                                                                     </a></td
//                                                                 >
//                                                             </tr>
//                                                             </tbody
//                                                             >
//                                                         </table>
//                                                         <!--[if (mso)|(IE)]></td><![endif]-->

//                                                         <!--[if (mso)|(IE)]>
//                                                         <td width='2' style='width:32px; padding-right: 12px;'
//                                                             valign='top'><![endif]-->
//                                                         <table
//                                                                 align='left'
//                                                                 border='0'
//                                                                 cellspacing='0'
//                                                                 cellpadding='0'
//                                                                 width='32'
//                                                                 height='32'
//                                                                 style='
//                                         width: 32px !important;
//                                         height: 32px !important;
//                                         display: inline-block;
//                                         border-collapse: collapse;
//                                         table-layout: fixed;
//                                         border-spacing: 0;
//                                         mso-table-lspace: 0pt;
//                                         mso-table-rspace: 0pt;
//                                         vertical-align: top;
//                                         margin-right: 12px;
//                                       '
//                                                         >
//                                                             <tbody
//                                                             >
//                                                             <tr style='vertical-align: top'
//                                                             >
//                                                                 <td
//                                                                         align='left'
//                                                                         valign='middle'
//                                                                         style='
//                                               word-break: break-word;
//                                               border-collapse: collapse !important;
//                                               vertical-align: top;
//                                             '
//                                                                 >
//                                                                     <a
//                                                                             href='javascript:void(0)'
//                                                                             title='LinkedIn'
//                                                                             target='_blank'
//                                                                     >
//                                                                         <img
//                                                                                 src='https://cdn.tools.unlayer.com/social/icons/circle/linkedin.png'
//                                                                                 alt='LinkedIn'
//                                                                                 title='LinkedIn'
//                                                                                 width='32'
//                                                                                 style='
//                                                   outline: none;
//                                                   text-decoration: none;
//                                                   -ms-interpolation-mode: bicubic;
//                                                   clear: both;
//                                                   display: block !important;
//                                                   border: none;
//                                                   height: auto;
//                                                   float: none;
//                                                   max-width: 32px !important;
//                                                 '
//                                                                         />
//                                                                     </a></td
//                                                                 >
//                                                             </tr>
//                                                             </tbody
//                                                             >
//                                                         </table>
//                                                         <!--[if (mso)|(IE)]></td><![endif]-->

//                                                         <!--[if (mso)|(IE)]>
//                                                         <td width='2' style='width:32px; padding-right: 0px;'
//                                                             valign='top'><![endif]-->
//                                                         <table
//                                                                 align='left'
//                                                                 border='0'
//                                                                 cellspacing='0'
//                                                                 cellpadding='0'
//                                                                 width='32'
//                                                                 height='32'
//                                                                 style='
//                                         width: 32px !important;
//                                         height: 32px !important;
//                                         display: inline-block;
//                                         border-collapse: collapse;
//                                         table-layout: fixed;
//                                         border-spacing: 0;
//                                         mso-table-lspace: 0pt;
//                                         mso-table-rspace: 0pt;
//                                         vertical-align: top;
//                                         margin-right: 0px;
//                                       '
//                                                         >
//                                                             <tbody
//                                                             >
//                                                             <tr style='vertical-align: top'
//                                                             >
//                                                                 <td
//                                                                         align='left'
//                                                                         valign='middle'
//                                                                         style='
//                                               word-break: break-word;
//                                               border-collapse: collapse !important;
//                                               vertical-align: top;
//                                             '
//                                                                 >
//                                                                     <a
//                                                                             href='https://instagram.com/'
//                                                                             title='Instagram'
//                                                                             target='_blank'
//                                                                     >
//                                                                         <img
//                                                                                 src='https://cdn.tools.unlayer.com/social/icons/circle/instagram.png'
//                                                                                 alt='Instagram'
//                                                                                 title='Instagram'
//                                                                                 width='32'
//                                                                                 style='
//                                                   outline: none;
//                                                   text-decoration: none;
//                                                   -ms-interpolation-mode: bicubic;
//                                                   clear: both;
//                                                   display: block !important;
//                                                   border: none;
//                                                   height: auto;
//                                                   float: none;
//                                                   max-width: 32px !important;
//                                                 '
//                                                                         />
//                                                                     </a></td
//                                                                 >
//                                                             </tr>
//                                                             </tbody
//                                                             >
//                                                         </table>
//                                                         <!--[if (mso)|(IE)]></td><![endif]-->

//                                                         <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
//                                                     </div>
//                                                 </div>
//                                             </td>
//                                         </tr>
//                                         </tbody>
//                                     </table>

//                                     <!--[if (!mso)&(!IE)]><!--></div
//                                 ><!--<![endif]-->
//                             </div>
//                         </div>
//                         <!--[if (mso)|(IE)]></td><![endif]-->
//                         <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
//                     </div>
//                 </div>
//             </div>

//             <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
//         </td>
//     </tr>
//     </tbody>
// </table>
// <!--[if mso]></div><![endif]-->
// <!--[if IE]></div><![endif]-->
// </body>
// </html
// >

//                         ";

//         // Always set content-type when sending HTML email
//         $headers = "MIME-Version: 1.0" . "\r\n";
//         $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

//         // More headers
//         $headers .= 'From: <info@sifututor.brainiaccreation.com>' . "\r\n";

//         mail($to, $subject, $message, $headers);


//         $tutorDetail = DB::table('customers')->where('id', '=', $request->tutorId)->first();

//         return Response::json(['status' => 200, 'tutorDetail' => $tutorDetail]);
//     }

    public function verificationCode(Request $req)
    {
        $code = $req->code;
        $token = $req->token;

        // Check for hardcoded verification code
        if ($code == 12345) {
            $customer = DB::table('customers')->where('token', $token)->first();

            if ($customer) {
                DB::table('customers')->where('id', $customer->id)
                    ->update(['last_login' => now()]);

                return response()->json([
                    'ResponseCode' => '100',
                    'message' => 'Login successful.',
                    'data' => [
                        'parentID' => $customer->id,
                        'contact' => $customer->phone,
                        'Parentstatus' => $customer->status,
                        'token' => $customer->token,
                    ]
                ]);
            }

            return response()->json([
                'ResponseCode' => '104',
                'error' => 'Parent Not found!'

            ]);
        }

        // Check for verification code in database
        $verificationCode = DB::table('tutorVerificationCode')
            ->where('code', $code)
            ->where('token', $token)
            ->latest('id')
            ->first();

        if ($verificationCode) {
            DB::table('customers')->where('token', $verificationCode->token)
                ->update(['last_login' => now()]);

            $customer = DB::table('customers')->where('token', $verificationCode->token)
                ->first();

            return response()->json([
                'ResponseCode' => '200',
                'message' => 'Login successful.',
                'data' => [
                    'parentID' => $customer->id,
                    'contact' => $customer->phone,
                    'token' => $verificationCode->token,
                    'Parentstatus' => $customer->status,
                ]
            ]);
        }

        return response()->json([
            'ResponseCode' => '104',
            'error' => 'Sorry, code didn’t match! Please try again.'

        ]);
    }


     public function updateParentProfile(Request $request)
    {

        $parent = DB::table('customers')->where('token', $request->token)->first();

        if (!$parent)
        {
            return response()->json(["ResponseCode" => "104", "error" => "Customer not found"], 200);
        }

        $updateData = [
            'full_name' => $request->full_name ?? $parent->full_name,
            'dob' => $request->dob ?? $parent->dob,
            'email' => $request->email ?? $parent->email,
            'address' => $request->address ?? $parent->address,
            'city' => $request->city ?? $parent->city,
            'state' => $request->state ?? $parent->state,
            'postal_code' => $request->postal_code ?? $parent->postal_code,
            'latitude' => $request->lat ?? $parent->latitude,
            'longitude' => $request->long ?? $parent->longitude,
            'gender' => $request->gender ?? $parent->gender,
            'age' => $request->age ?? $parent->age,
            'phone' => $request->phone ?? $parent->phone,
            'whatsapp' => $request->whatsapp ?? $parent->whatsapp
        ];

        // Perform the update
        $affected = DB::table('customers')
            ->where('token', $request->token)
            ->update($updateData);

        // if ($affected) {


            return response()->json([
                'ResponseCode' => "100",
                'message' => 'Profile updated successfully!',
                'data' => $parent

            ]);
        // } else {
        //     return response()->json([
        //         'ResponseCode' => "103",
        //         'error' => 'Database error,Failed to update customer profile. Please try again later.'
        //     ], 200);
        // }
    }


   public function getStudents()
    {
        $result = [];

        // Fetch all students
        $students = Student::all();

        if ($students->isEmpty()) {

            return response()->json([
                'ResponseCode' => "104",
                'error' => 'No students found.',
            ], 200);
        }

        foreach ($students as $key => $student) {
            $result[$key] = [
                "id" => $student->id,
                "gender" => $student->gender,
                "specialNeed" => $student->specialNeed,
                "name" => $student->full_name,
                "subject" => $student->full_name,
                "dob" => $student->dob,
            ];
        }

        return response()->json([
                        'ResponseCode' => "100",
                        'message' => 'Students data fetched successfully.',
                        'data' => $result,
                    ], 200);
    }

       public function parentStudents($token)
    {
        // Fetch the customer based on the provided token
        $customer = DB::table('customers')->where('token', $token)->first();

        if ($customer === null) {
            // Return response when no customer is found
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ], 200);
        }

        // Fetch students associated with the customer
        $students = Student::where('customer_id', $customer->id)->get();

        if ($students->isEmpty()) {
            // Return response when no students are found
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No students found for this customer.',
            ], 200);
        }

        // Prepare the result
        $result = [];
        foreach ($students as $key => $student) {
            $result[$key] = [
                'id' => $student->id,
                'gender' => $student->gender,
                'specialNeed' => $student->specialNeed,
                'name' => $student->full_name,
                'subject' => $student->full_name,
                'dob' => $student->dob,
            ];
        }

        // Return the response with the student data
        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Students data fetched successfully.',
            'data' => $result,
        ], 200);
    }

   public function studentsDetails($student_id)
    {
        // Fetch the student details based on the provided student ID
        $studentDetail = DB::table('students')->where('id', $student_id)->first();

        if ($studentDetail === null) {
            // Return response when no student is found
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No student found with the provided ID.',
            ]);
        }

        // Return the response with the student details
        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Student details fetched successfully.',
            'data' => $studentDetail,
        ]);
    }

   public function getParentDetailByID($token)
    {
        // Fetch customer information
        $customer = DB::table('customers')->where('token', $token)->first();

        if ($customer === null) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No Parent found!',
            ], 200);
        }

        // Fetch detailed customer information with state and city names
        $customerDetail = DB::table('customers')
            ->leftJoin('states', 'customers.state', '=', 'states.id')
            ->leftJoin('cities', 'customers.city', '=', 'cities.id')
            ->select('customers.*', 'states.name as state_name', 'cities.name as city_name')
            ->where('customers.token', '=', $token)
            ->first();

        // Set default image if none exists
        $customerDetail->image = $customerDetail->image
            ? url("/public/parentProfileImages/{$customerDetail->image}")
            : url("/public/person_place_holder.png");

        // Query to get unique subjects and total time for this customer
        $subjectDetails = DB::table('class_attendeds as ca')
            ->join('students as s', 'ca.studentID', '=', 's.id')
            ->join('products as p', 'ca.subjectID', '=', 'p.id')
            ->where('s.customer_id', $customer->id)
            ->where('ca.status', 'attended')
            ->select('p.name as subject_name', DB::raw('ROUND(SUM(ca.totalTime), 2) as total_time'))
            ->groupBy('p.name')
            ->get();

        // Calculate the sum of total_time
        $sumTotalTimeDecimal = $subjectDetails->sum('total_time');
        $hours = floor($sumTotalTimeDecimal);
        $minutes = round(($sumTotalTimeDecimal - $hours) * 60);
        $sumTotalTimeFormatted = "{$hours}h {$minutes}m";

        // Add sumTotalTime and subjectDetails to the customerDetail object
        $customerDetail->sumTotalTime = $sumTotalTimeFormatted;
        $customerDetail->subjectDetails = $subjectDetails->map(function ($detail) {
            $detail->total_time = round($detail->total_time, 2);
            return $detail;
        });

        // Check Commitment fee
        $customerCommitmentFee = DB::table('customer_commitment_fees')->where('customer_id', $customer->id)->first();
        $customerCommitmentFeeCheck = $customerCommitmentFee ? true : false;

        $customerDetail->commitmentFeeAmount = 50;
        $customerDetail->commitmentFee = $customerCommitmentFee;

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Parent details fetched successfully.',
            'data' => $customerDetail,
        ], 200);
    }

   public function storeStudent(Request $request)
    {
        // Find the parent customer by token
        $parent = DB::table('customers')->where('token', $request->token)->first();

        if (!$parent) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No customer found!',
            ]);
        }

        // Prepare student data
        $studentValues = [
            'uid' => 'ST-' . now()->format('dis'),
            'student_id' => 'ST-' . now()->format('dis'),
            'full_name' => $request->studentFullName,
            'dob' => $request->studentDateOfBirth,
            'customer_id' => $parent->id,
            'gender' => $request->studentGender,
            'specialNeed' => $request->specialNeed,
            'age' => $request->age,
            'register_date' => now()->format('Y-m-d')
        ];

        // Insert the student and get the last inserted ID
        $studentLastID = DB::table('students')->insertGetId($studentValues);

        // Retrieve the newly inserted student
        $student = DB::table('students')->where('id', $studentLastID)->first();

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Student added successfully.',
            'data' => $student
        ]);
    }

    public function getSubjects()
    {
        $products = DB::table('products')->
        join("categories", "products.category", "=", "categories.id")
            ->select("products.*", "categories.mode as mode", "categories.category_name as category_name")
            ->get();
        return Response::json(['subjects' => $products,'code'=>200]);
    }


  public function getSubjectsByLevel($id)
    {
        // Check if the category exists
        $category = DB::table("categories")->where("id", $id)->first();

        if (!$category) {
            return response()->json([
                'ResponseCode' => "104",
                'error' => 'Category not found'
            ]);
        }

        // Retrieve the products associated with the category
        $products = DB::table('products')
            ->join("categories", "products.category", "=", "categories.id")
            ->select(
                "products.*",
                "categories.mode as mode",
                "categories.category_name as category_name"
            )
            ->where("categories.id", $id)
            ->get();

          return response()->json([
            'ResponseCode' => "100",
            'data' => $products
        ]);
    }

    public function submitTicket(Request $request)
    {

        $data = $request->all();

        $studentLastID = $request->students[0]["id"];

        $data = $request->all();

        $latestTicketID = DB::table('job_tickets')->latest('created_at')->first();
        if ($latestTicketID) {
            $ticketIDs = $latestTicketID->id + 1;
        } else {
            $ticketIDs = 1;
        }

        $subject = $data['subject'];

        $dayArray = array();

        foreach ($data['day'] as $selectedDay) {
            $dayArray[] = $selectedDay;
        }

        $extraStudentFeeCharges = DB::table('extra_student_charges')->orderBy('id', 'DESC')->first();

        $subjectFee = DB::table('products')->join("categories", "products.category", "=", "categories.id")
            ->select("products.*", "categories.price as category_price", "categories.mode as mode")
            ->where('products.id', '=', $data['subject'])->first();
        $uuidForTicket = rand(100, 99999);



        $ticektValues = array(
            'ticket_id' => $ticketIDs,
            'uid' => 'JT-' . $uuidForTicket,
            'student_id' => $studentLastID,
            'admin_charge' => $request->inCharge,
            'service' => $request->service,
            'subjects' => $data['subject'],
            'subject_fee' => $subjectFee->category_price,
            'quantity' => $data['quantity'],
            'classFrequency' => $data['classFrequency'],
            'remaining_classes' => $data['classFrequency'],
            'tutorPereference' => $data['tutorPereference'],
            'day' => json_encode(implode(",", $dayArray)),
            'time' => $data['time'],
            'subscription' => $data['subscription'],
            'specialRequest' => $data['specialRequest'],
            'classAddress' => $request->classAddress,
            'classLatitude' => $request->classLatitude,
            'classLongitude' => $request->classLongitude,
            'classCity' => $request->classCity,
            'classState' => $request->classState,
            'classPostalCode' => $request->classPostalCode,
            'register_date' => date("Y-m-d"),
            'mode' => $request->classType,
            'estimate_commission' => $request->estimate_commission,
            'job_ticket_requirement' => $request->job_ticket_requirement,
            'status' => 'pending'
        );

        $jobTicketLastID = DB::table('job_tickets')->insertGetId($ticektValues);

        $student_data = array(
            'student_id' => $studentLastID,
            'ticket_id' => $jobTicketLastID,
            'ticket_id2' => $ticketIDs,
            'subject' => $data['subject'],
            'quantity' => $data['quantity'],
            'classFrequency' => $data['classFrequency'],
            'remaining_classes' => $data['classFrequency'],
            'day' => json_encode(implode(",", $dayArray)),
            'time' => $data['time'],
            'subscription' => $data['subscription'],
            'specialRequest' => $data['specialRequest'],
        );
        DB::table('student_subjects')->insertGetId($student_data);


        if (isset($data["students"])) {
    $i = 0; // Index counter
    foreach ($data['students'] as $student) {
        $i++;
        if ($i == 1) {
            continue; // Skip the first iteration
        }

        if ($student['studentFullName'] != NULL) {

            if ($subjectFee->mode == "physical") {
                $extraStudentCharges = DB::table("extra_student_charges")->first();
                $extraStudentChargesDate = $extraStudentCharges->created_at;
                $extraStudentCharges = $extraStudentFeeCharges->physical_additional_charges;

            } else {
                $extraStudentCharges = DB::table("extra_student_charges")->first();
                $extraStudentChargesDate = $extraStudentCharges->created_at;
                $extraStudentCharges = $extraStudentFeeCharges->online_additional_charges;
            }

            $uuidForStudent = rand(100, 99999);

            $extra_student_charges = DB::table('extra_student_charges')->orderBy('id', 'DESC')->first();
            $multipleStudent = array(
                'student_id' => $student['id'],
                'student_name' => $student['studentFullName'],
                'student_gender' => $student['studentGender'],
                'student_age' => $student['age'],
                'year_of_birth' => $student['studentDateOfBirth'],
                'special_need' => $student['specialNeed'],
                'job_ticket_id' => $jobTicketLastID,
                'subject_id' => $student['subject'],
                'extra_fee' => $extraStudentCharges,
                'extra_fee_date' => $extraStudentChargesDate,
            );

            DB::table('job_ticket_students')->insertGetId($multipleStudent);
        }
    }
}

        $studentDetail = DB::table('students')->where('id', '=', $studentLastID)->first();
        $customerDetail = DB::table('customers')->where('id', '=', $studentDetail->customer_id)->first();
        $subjectDetail = DB::table('products')->join("categories", "products.category", "=", "categories.id")
            ->select("products.*", "categories.price as category_price", "categories.mode as class_mode", "categories.category_name as category_name")->
            where('products.id', '=', $data['subject'])->first();

        $tableName = 'job_ticket_students';
        $count = DB::table($tableName)
            ->select(DB::raw('count(*) as count'))
            ->where('job_ticket_id', '=', $jobTicketLastID)
            ->first()
            ->count;


        if ($subjectDetail->class_mode == "physical") {
            $extraStudentCharges = DB::table("extra_student_charges")->first();
            $extraStudentCharges = $extraStudentFeeCharges->physical_additional_charges;

        }
        else {
            $extraStudentCharges = DB::table("extra_student_charges")->first();
            $extraStudentCharges = $extraStudentFeeCharges->online_additional_charges;

        }

        $extraStudentFeeCharges = DB::table('extra_student_charges')->orderBy('id', 'DESC')->first();

        $jobTicketID = DB::table('job_tickets')->where('id', '=', $jobTicketLastID)->first();


        $price = $subjectDetail->category_price;

        $classFrequency = floatval($data['classFrequency']);

        $quantity = floatval($data['quantity']);

        if ($subjectDetail->class_mode == "physical") {
            $extraCharges = $count * $extraStudentFeeCharges->physical_additional_charges;
            $extraChargesTutorComission = $count * $extraStudentFeeCharges->tutor_physical;

        }
        else {
            $extraCharges = $count * $extraStudentFeeCharges->online_additional_charges;
            $extraChargesTutorComission = $count * $extraStudentFeeCharges->tutor_online;

        }

        $tableName = 'job_ticket_students';

        $count = DB::table($tableName)
            ->select(DB::raw('count(*) as count'))
            ->where('job_ticket_id', '=', $ticketIDs)
            ->first()
            ->count;

        $class_term = $jobTicketID->subscription;
        $modeOfClass = $subjectDetail->class_mode;
        $category_id_subject = $subjectDetail->category;
        $category_level_subject = $subjectDetail->category_name;
        $estimate_commission = 0;
        $estimate_after_eight_hours = 0;



        //Long Term Classes prices according to hours
        $long_term_online_first_eight_hours = [
            'Pre-school' => 9,
            'UPSR' => 9,
            'PT3' => 9,
            'SPM' => 10.5,
            'IGCSE' => 10.5,
            'STPM' => 12,
            'A-level/Pre-U' => 12,
            'Diploma' => 13.5,
            'Degree' => 15,
            'ACCA' => 18,
            'Master' => 18,
        ];

        $long_term_online_after_eight_hours = [
            'Pre-school' => 21,
            'UPSR' => 21,
            'PT3' => 21,
            'SPM' => 24.5,
            'IGCSE' => 24.5,
            'STPM' => 28,
            'A-level/Pre-U' => 28,
            'Diploma' => 31.5,
            'Degree' => 35,
            'ACCA' => 42,
            'Master' => 42,
        ];

        $long_term_physical_first_eight_hours = [
            'Pre-school' => 15,
            'UPSR' => 15,
            'PT3' => 15,
            'SPM' => 18,
            'IGCSE' => 21,
            'STPM' => 24,
            'A-level/Pre-U' => 24,
            'Diploma' => 27,
            'Degree' => 30,
            'ACCA' => 36,
            'Master' => 36,
        ];

        $long_term_physical_after_eight_hours = [
            'Pre-school' => 35,
            'UPSR' => 35,
            'PT3' => 35,
            'SPM' => 40,
            'IGCSE' => 49,
            'STPM' => 56,
            'A-level/Pre-U' => 56,
            'Diploma' => 63,
            'Degree' => 70,
            'ACCA' => 84,
            'Master' => 84,
        ];

        //Short Term Classes prices according to hours
        $short_term_online = [
            'Pre-school' => 18,
            'UPSR' => 18,
            'PT3' => 18,
            'SPM' => 21,
            'IGCSE' => 21,
            'STPM' => 24,
            'A-level/Pre-U' => 24,
            'Diploma' => 27,
            'Degree' => 30,
            'ACCA' => 36,
            'Master' => 36,
        ];

        $short_term_physical = [
            'Pre-school' => 30,
            'UPSR' => 30,
            'PT3' => 30,
            'SPM' => 36,
            'IGCSE' => 42,
            'STPM' => 48,
            'A-level/Pre-U' => 48,
            'Diploma' => 54,
            'Degree' => 60,
            'ACCA' => 72,
            'Master' => 72,
        ];

        $per_class_commission_before_eight_hours = 0;
        $per_class_commission_after_eight_hours = 0;

        if ($class_term == "Long-Term") {
            if ($modeOfClass == "online") {
                switch ($category_level_subject) {

                    case "Pre-school":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_online_first_eight_hours["Pre-school"];
                        $per_hour_charges_addition = $long_term_online_after_eight_hours["Pre-school"];

                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }


                        break;

                    case "UPSR":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);


                        $per_hour_charges = $long_term_online_first_eight_hours["UPSR"];
                        $per_hour_charges_addition = $long_term_online_after_eight_hours["UPSR"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }


                        break;

                    case "PT3":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_online_first_eight_hours["PT3"];
                        $per_hour_charges_addition = $long_term_online_after_eight_hours["PT3"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }


                        break;

                    case "SPM":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);

                        $per_hour_charges = $long_term_online_first_eight_hours["SPM"];
                        $per_hour_charges_addition = $long_term_online_after_eight_hours["SPM"];

                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }

                        break;

                    case "IGCSE":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_online_first_eight_hours["IGCSE"];
                        $per_hour_charges_addition = $long_term_online_after_eight_hours["IGCSE"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }

                        break;

                    case "STPM":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_online_first_eight_hours["STPM"];
                        $per_hour_charges_addition = $long_term_online_after_eight_hours["STPM"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }

                        break;

                    case "A-level/Pre-U":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_online_first_eight_hours["A-level/Pre-U"];
                        $per_hour_charges_addition = $long_term_online_after_eight_hours["A-level/Pre-U"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }

                        break;

                    case "Diploma":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_online_first_eight_hours["Diploma"];
                        $per_hour_charges_addition = $long_term_online_after_eight_hours["Diploma"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }

                        break;

                    case "Degree":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_online_first_eight_hours["Degree"];
                        $per_hour_charges_addition = $long_term_online_after_eight_hours["Degree"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }

                        break;

                    case "ACCA":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_online_first_eight_hours["ACCA"];
                        $per_hour_charges_addition = $long_term_online_after_eight_hours["ACCA"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }

                        break;

                    case "Master":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_online_first_eight_hours["Master"];
                        $per_hour_charges_addition = $long_term_online_after_eight_hours["Master"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));


                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }


                        break;

                }
            } elseif ($modeOfClass == "physical") {

                switch ($category_level_subject) {

                    case "Pre-school":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_physical_first_eight_hours["Pre-school"];
                        $per_hour_charges_addition = $long_term_physical_after_eight_hours["Pre-school"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }


                        break;

                    case "UPSR":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_physical_first_eight_hours["UPSR"];
                        $per_hour_charges_addition = $long_term_physical_after_eight_hours["UPSR"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }


                        break;

                    case "PT3":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_physical_first_eight_hours["PT3"];
                        $per_hour_charges_addition = $long_term_physical_after_eight_hours["PT3"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }


                        break;

                    case "SPM":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_physical_first_eight_hours["SPM"];
                        $per_hour_charges_addition = $long_term_physical_after_eight_hours["SPM"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }


                        break;

                    case "IGCSE":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_physical_first_eight_hours["IGCSE"];
                        $per_hour_charges_addition = $long_term_physical_after_eight_hours["IGCSE"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }


                        break;

                    case "STPM":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_physical_first_eight_hours["STPM"];
                        $per_hour_charges_addition = $long_term_physical_after_eight_hours["STPM"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }


                        break;

                    case "A-level/Pre-U":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_physical_first_eight_hours["A-level/Pre-U"];
                        $per_hour_charges_addition = $long_term_physical_after_eight_hours["A-level/Pre-U"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }

                        break;

                    case "Diploma":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_physical_first_eight_hours["Diploma"];
                        $per_hour_charges_addition = $long_term_physical_after_eight_hours["Diploma"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }

                        break;

                    case "Degree":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_physical_first_eight_hours["Degree"];
                        $per_hour_charges_addition = $long_term_physical_after_eight_hours["Degree"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }


                        break;

                    case "ACCA":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_physical_first_eight_hours["ACCA"];
                        $per_hour_charges_addition = $long_term_physical_after_eight_hours["ACCA"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }


                        break;

                    case "Master":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_physical_first_eight_hours["Master"];
                        $per_hour_charges_addition = $long_term_physical_after_eight_hours["Master"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }


                        break;

                }
            }
        }
        else {
            if ($modeOfClass == "online") {


                switch ($category_level_subject) {

                    case "Pre-school":

                        $per_hour_charges = $short_term_online["Pre-school"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;


                    case "UPSR":
                        $per_hour_charges = $short_term_online["UPSR"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "PT3":
                        $per_hour_charges = $short_term_online["PT3"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;


                    case "SPM":

                        $per_hour_charges = $short_term_online["SPM"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;


                    case "IGCSE":
                        $per_hour_charges = $short_term_online["IGCSE"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "STPM":
                        $per_hour_charges = $short_term_online["STPM"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "A-level/Pre-U":
                        $per_hour_charges = $short_term_online["A-level/Pre-U"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "Diploma":
                        $per_hour_charges = $short_term_online["Diploma"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "Degree":
                        $per_hour_charges = $short_term_online["Degree"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "ACCA":
                        $per_hour_charges = $short_term_online["ACCA"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "Master":
                        $per_hour_charges = $short_term_online["Master"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                }
            } elseif ($modeOfClass == "physical") {
                switch ($category_level_subject) {

                    case "Pre-school":
                        $per_hour_charges = $short_term_physical["Pre-school"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "UPSR":
                        $per_hour_charges = $short_term_physical["UPSR"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "PT3":
                        $per_hour_charges = $short_term_physical["PT3"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "SPM":
                        $per_hour_charges = $short_term_physical["SPM"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "IGCSE":
                        $per_hour_charges = $short_term_physical["IGCSE"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "STPM":
                        $per_hour_charges = $short_term_physical["STPM"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "A-level/Pre-U":
                        $per_hour_charges = $short_term_physical["A-level/Pre-U"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "Diploma":
                        $per_hour_charges = $short_term_physical["Diploma"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "Degree":
                        $per_hour_charges = $short_term_physical["Degree"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "ACCA":
                        $per_hour_charges = $short_term_physical["ACCA"];
                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "Master":
                        $per_hour_charges = $short_term_physical["Master"];
                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                }
            }
        }


        if ((isset($data["students"]))) {

            // dd("1");

            $jobTicketCalc = $subjectFee->category_price + $extraCharges;
            $jobTicketCalc = $jobTicketCalc * $data['classFrequency'] * $data['quantity'];
        } else {
            // dd("2");
            $jobTicketCalc = $subjectFee->category_price;
            $jobTicketCalc = $jobTicketCalc * $data['classFrequency'] * $data['quantity'];
        }


        $additionalStudentChargesTutor = 0;
        $additionalStudentChargesJobTicket = 0;
        $additionalStudentCharges = 0;

        if ($subjectDetail->class_mode == "physical") {
            if ($count == 0) {
                $additionalStudentChargesTutor = 1 * $extraStudentFeeCharges->tutor_physical;
                $additionalStudentChargesJobTicket = 1 * $extraStudentFeeCharges->physical_additional_charges;
            } else {

                $additionalStudentChargesTutor = $count * $extraStudentFeeCharges->tutor_physical;
                $additionalStudentChargesJobTicket = $count * $extraStudentFeeCharges->physical_additional_charges;
            }

        } else {
            if ($count == 0) {

                $additionalStudentChargesTutor = 1 * $extraStudentFeeCharges->tutor_online;
                $additionalStudentChargesJobTicket = 1 * $extraStudentFeeCharges->online_additional_charges;
            } else {
                $additionalStudentChargesTutor = $count * $extraStudentFeeCharges->tutor_physical;
                $additionalStudentChargesJobTicket = $count * $extraStudentFeeCharges->online_additional_charges;
            }
        }


        if ($count == 0) {
            $count = 1;
            $additionalStudentCharges = $additionalStudentChargesTutor * 1 * (floatval($data['classFrequency']) * floatval($data['quantity']));
            $additionalStudentChargesJobTicket = $additionalStudentChargesJobTicket * 1 * (floatval($data['classFrequency']) * floatval($data['quantity']));
        } else {
            $additionalStudentCharges = $additionalStudentChargesTutor * 1 * (floatval($data['classFrequency']) * floatval($data['quantity']));
            $additionalStudentChargesJobTicket = $additionalStudentChargesJobTicket * 1 * (floatval($data['classFrequency']) * floatval($data['quantity']));

        }


        DB::table('job_tickets')
            ->where('id', $jobTicketLastID)
            ->update([
                'extra_student_total' => $additionalStudentChargesJobTicket,
                'extra_student_tutor_commission' => $additionalStudentCharges,
                'extra_estimate_commission_display_tutor' => $additionalStudentCharges,
                'estimate_commission' => $estimate_commission,
                'estimate_commission_display_tutor' => $estimate_after_eight_hours,
                'totalPrice' => $jobTicketCalc,
                'per_class_commission_before_eight_hours' => $per_class_commission_before_eight_hours,
                'per_class_commission_after_eight_hours' => $per_class_commission_after_eight_hours
            ]);

        if ((isset($data["studentFullName"]))) {

            $calcPrice = $subjectDetail->category_price + $extraCharges;
            $calcPrice = $calcPrice * $data['classFrequency'];
            $calcPrice = $calcPrice * $data['quantity'];
        } else {

            $calcPrice = $subjectDetail->category_price;
            $calcPrice = $calcPrice * $data['classFrequency'];
            $calcPrice = $calcPrice * $data['quantity'];
        }


        // dd($estimate_commission);
        $invoiceValue = array(
            'studentID' => $studentLastID,
            'ticketID' => $jobTicketLastID,
            'subjectID' => $data['subject'],
            'account_id' => $customerDetail->id,
            'invoiceDate' => date('Y-m-d'),
            'reference' => $jobTicketLastID,
            'payerName' => $customerDetail->full_name,
            'payerEmail' => $customerDetail->email,
            'payerPhone' => $customerDetail->phone,
            'quantity' => $data['quantity'],
            'classFrequency' => $data['classFrequency'],
            'day' => json_encode(implode(",", $dayArray)),
            'time' => $data['time'],
            'type' => 'd',
            'debit' => ($subjectDetail->price * $data['classFrequency'] * $data['quantity']) + ($extraCharges),
            'credit' => 0,
            'invoice_status' => "First",
            'invoiceTotal' => $calcPrice,
            'sentEmail' => "true",
            'brand' => $subjectDetail->brand);

        $invoiceID = DB::table('invoices')->insertGetId($invoiceValue);

        // Split the days string into an array
        $daysArray = explode(',', json_decode($invoiceValue['day']));

        // Get the initial date for the invoice
        $initialDate = new DateTime($invoiceValue['invoiceDate']);
        $perClassPriceInvoiceItem = $jobTicketCalc / $request->classFrequency;
        // Insert records for each day and each occurrence based on ClassFrequency

        //  return response()->json($data['classFrequency']."+++++".$perClassPriceInvoiceItem."---".$invoiceID."---".$studentLastID."---".$jobTicketLastID,200);

        for ($j = 0; $j < $data['classFrequency']; $j++) {



            // Iterate over each day
            $currentDay = $daysArray[$j % count($daysArray)];
            // Get the day for the current iteration using modulus


            //  return response()->json($currentDay);


            // Calculate the date based on the current day
            $date = clone $initialDate;
            while ($date->format('D') !== $currentDay) {
                $date->add(new DateInterval('P1D'));
            }


                // Update the initial date to the next occurrence of the current day
            $initialDate = clone $date;
            $initialDate->add(new DateInterval('P1D'));





            // Modify data as needed for each iteration
            $invoiceItemsData['quantity'] = $data['quantity'];
            $invoiceItemsData['time'] = $data['time'];
            $invoiceItemsData['day'] = $currentDay;
            $invoiceItemsData['isPaid'] = 'unPaid';
            $invoiceItemsData['studentID'] = $studentLastID;
            $invoiceItemsData['ticketID'] = $jobTicketLastID;
            $invoiceItemsData['subjectID'] = $data['subject'];
            $invoiceItemsData['invoiceID'] = $invoiceID;
            $invoiceItemsData['invoiceDate'] = $date->format('Y-m-d');
            $invoiceItemsData['price'] = $perClassPriceInvoiceItem;

            // Add other fields as needed

            // Insert into invoice_items table
            DB::table('invoice_items')->insert($invoiceItemsData);

        }

        // Sample data to pass to the view
        $invoice_detail = DB::table('invoices')->where('id', '=', $invoiceID)->orderBy('id', 'desc')->first();
        $invoice_items = DB::table('invoice_items')->where('invoiceID', '=', $invoiceID)->orderBy('id', 'desc')->get();
        $students = DB::table('students')->where('id', '=', $invoice_detail->studentID)->orderBy('id', 'DESC')->first();
        $customer = DB::table('customers')->where('id', '=', $students->customer_id)->first();

        $subjects = DB::table('products')->join("categories", "products.category", "=", "categories.id")
            ->select("products.*", "categories.price as category_price")
            ->where('products.id', '=', $invoice_detail->subjectID)->first();

        $tutorListings = DB::table('tutors')->where('status', '=', 'verified')->get();
        $jobTicketDeails = DB::table('job_tickets')->where('id', '=', $jobTicketLastID)->first();

         if (isset($customer) && $customer->whatsapp != null) {
                $month_year = Carbon::now()->format('F Y'); // Using Carbon for date formatting
                $invoice_link = url("/invoicePublicLink") . $invoice_detail->id;
                $phone_number = $customer->whatsapp;
                $message = "Dear Parent/Student, Your SifuTutor invoice for $month_year is ready! You can easily view and pay your bill online at $invoice_link.
                The total amount due is " . $invoice_detail->invoiceTotal . ".
                If you prefer, you can also make a payment to our Maybank account: Sifu Edu & Learning Sdn Bhd Account No: 5621 1551 6678.
                Please send your payment confirmation to us via WhatsApp at www.wasap.my/60146037500. If you have any enquiry,
                feel free to call or WhatsApp us at 014-603 7500. Thank you! - SifuTutor Management Team [This is an automated message, please do not reply directly.]";

                SendWhatsAppMessageJob::dispatch($phone_number, $message);
            }

        foreach ($tutorListings as $rowTutorListings) {
                $phone_number = $rowTutorListings->whatsapp;
                $message = 'Dear Tutor: *' . $rowTutorListings->full_name . '*, A Class Ticket has been generated. Class Ticket # *' . $jobTicketDeails->uid . '*';

                SendWhatsAppMessageJob::dispatch($phone_number, $message);
                SendSmsMessageJob::dispatch($phone_number, $message);

                DB::table('text_messages')->insert([
                    'recipient' => $phone_number,
                    'message' => $message,
                    'status' => 'sent',
                ]);
            }

        $tutorDevices = DB::table('tutor_device_tokens')->distinct()->get(['device_token', 'tutor_id']);
            foreach ($tutorDevices as $rowDeviceToken) {
                $push_notification_api = new PushNotificationLibrary();
                $deviceToken = $rowDeviceToken->device_token;
                $title = 'Job-Ticket Create Successfully';
                $message = 'Message JOB Ticket ';
                $notificationdata = array(
                    'sender' => 'jobTicket',
                    'id' => $jobTicketDeails->uid,
                );
                // $push_notification_api->sendPushNotification($deviceToken, $title, $message, $notificationdata);
                SendPushNotificationJob::dispatch($deviceToken, $title, $message,$notificationdata);
            }

        //new ticket creation event
        $data = ["New Ticket Created"];
        event(new TicketCreated($data));


        return response()->json(["ResponseCode"=>"100" , "message" => "Ticket has been added successfully"]);
        // return redirect('TicketList')->with('success', 'ticket has been added successfully!');

    }

    public function getJobTicketEstimation(Request $request)
    {

        $data = $request->all();

        $studentLastID = $request->students[0]["id"];

        $data = $request->all();

        $subject = $data['subject'];

        $dayArray = array();

        foreach ($data['day'] as $selectedDay) {
            $dayArray[] = $selectedDay;
        }

        $extraStudentFeeCharges = DB::table('extra_student_charges')->orderBy('id', 'DESC')->first();

        $subjectFee = DB::table('products')->join("categories", "products.category", "=", "categories.id")
            ->select("products.*", "categories.price as category_price", "categories.mode as mode")
            ->where('products.id', '=', $data['subject'])->first();


        if (isset($data["students"])) {
        $i = 0; // Index counter
        foreach ($data['students'] as $student) {
            $i++;
            if ($i == 1) {
            continue; // Skip the first iteration
                }
            }
        }

        $studentDetail = DB::table('students')->where('id', '=', $studentLastID)->first();
        $customerDetail = DB::table('customers')->where('id', '=', $studentDetail->customer_id)->first();
        $subjectDetail = DB::table('products')->join("categories", "products.category", "=", "categories.id")
            ->select("products.*", "categories.price as category_price", "categories.mode as class_mode", "categories.category_name as category_name")->
            where('products.id', '=', $data['subject'])->first();

        $count = count($request->students);
        $count = $count-1;

        if ($subjectDetail->class_mode == "physical") {
            $extraStudentCharges = DB::table("extra_student_charges")->first();
            $extraStudentCharges = $extraStudentFeeCharges->physical_additional_charges;

        } else {
            $extraStudentCharges = DB::table("extra_student_charges")->first();
            $extraStudentCharges = $extraStudentFeeCharges->online_additional_charges;

        }

        $extraStudentFeeCharges = DB::table('extra_student_charges')->orderBy('id', 'DESC')->first();

        $price = $subjectDetail->category_price;

        $classFrequency = floatval($data['classFrequency']);

        $quantity = floatval($data['quantity']);

        if ($subjectDetail->class_mode == "physical") {
            $extraCharges = $count * $extraStudentFeeCharges->physical_additional_charges;
            $extraChargesTutorComission = $count * $extraStudentFeeCharges->tutor_physical;

        } else {
            $extraCharges = $count * $extraStudentFeeCharges->online_additional_charges;
            $extraChargesTutorComission = $count * $extraStudentFeeCharges->tutor_online;

        }



        $class_term = $request->subscription;
        $modeOfClass = $request->class_mode;
        $category_id_subject = $request->category;
        $category_level_subject = $request->category_name;
        $estimate_commission = 0;
        $estimate_after_eight_hours = 0;



        //Long Term Classes prices according to hours
        $long_term_online_first_eight_hours = [
            'Pre-school' => 9,
            'UPSR' => 9,
            'PT3' => 9,
            'SPM' => 10.5,
            'IGCSE' => 10.5,
            'STPM' => 12,
            'A-level/Pre-U' => 12,
            'Diploma' => 13.5,
            'Degree' => 15,
            'ACCA' => 18,
            'Master' => 18,
        ];

        $long_term_online_after_eight_hours = [
            'Pre-school' => 21,
            'UPSR' => 21,
            'PT3' => 21,
            'SPM' => 24.5,
            'IGCSE' => 24.5,
            'STPM' => 28,
            'A-level/Pre-U' => 28,
            'Diploma' => 31.5,
            'Degree' => 35,
            'ACCA' => 42,
            'Master' => 42,
        ];

        $long_term_physical_first_eight_hours = [
            'Pre-school' => 15,
            'UPSR' => 15,
            'PT3' => 15,
            'SPM' => 18,
            'IGCSE' => 21,
            'STPM' => 24,
            'A-level/Pre-U' => 24,
            'Diploma' => 27,
            'Degree' => 30,
            'ACCA' => 36,
            'Master' => 36,
        ];

        $long_term_physical_after_eight_hours = [
            'Pre-school' => 35,
            'UPSR' => 35,
            'PT3' => 35,
            'SPM' => 40,
            'IGCSE' => 49,
            'STPM' => 56,
            'A-level/Pre-U' => 56,
            'Diploma' => 63,
            'Degree' => 70,
            'ACCA' => 84,
            'Master' => 84,
        ];

        //Short Term Classes prices according to hours
        $short_term_online = [
            'Pre-school' => 18,
            'UPSR' => 18,
            'PT3' => 18,
            'SPM' => 21,
            'IGCSE' => 21,
            'STPM' => 24,
            'A-level/Pre-U' => 24,
            'Diploma' => 27,
            'Degree' => 30,
            'ACCA' => 36,
            'Master' => 36,
        ];

        $short_term_physical = [
            'Pre-school' => 30,
            'UPSR' => 30,
            'PT3' => 30,
            'SPM' => 36,
            'IGCSE' => 42,
            'STPM' => 48,
            'A-level/Pre-U' => 48,
            'Diploma' => 54,
            'Degree' => 60,
            'ACCA' => 72,
            'Master' => 72,
        ];

        $per_class_commission_before_eight_hours = 0;
        $per_class_commission_after_eight_hours = 0;

        if ($class_term == "Long-Term") {
            if ($modeOfClass == "online") {
                switch ($category_level_subject) {

                    case "Pre-school":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_online_first_eight_hours["Pre-school"];
                        $per_hour_charges_addition = $long_term_online_after_eight_hours["Pre-school"];

                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }


                        break;

                    case "UPSR":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);


                        $per_hour_charges = $long_term_online_first_eight_hours["UPSR"];
                        $per_hour_charges_addition = $long_term_online_after_eight_hours["UPSR"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }


                        break;

                    case "PT3":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_online_first_eight_hours["PT3"];
                        $per_hour_charges_addition = $long_term_online_after_eight_hours["PT3"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }


                        break;

                    case "SPM":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);

                        $per_hour_charges = $long_term_online_first_eight_hours["SPM"];
                        $per_hour_charges_addition = $long_term_online_after_eight_hours["SPM"];

                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }

                        break;

                    case "IGCSE":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_online_first_eight_hours["IGCSE"];
                        $per_hour_charges_addition = $long_term_online_after_eight_hours["IGCSE"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }

                        break;

                    case "STPM":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_online_first_eight_hours["STPM"];
                        $per_hour_charges_addition = $long_term_online_after_eight_hours["STPM"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }

                        break;

                    case "A-level/Pre-U":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_online_first_eight_hours["A-level/Pre-U"];
                        $per_hour_charges_addition = $long_term_online_after_eight_hours["A-level/Pre-U"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }

                        break;

                    case "Diploma":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_online_first_eight_hours["Diploma"];
                        $per_hour_charges_addition = $long_term_online_after_eight_hours["Diploma"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }

                        break;

                    case "Degree":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_online_first_eight_hours["Degree"];
                        $per_hour_charges_addition = $long_term_online_after_eight_hours["Degree"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }

                        break;

                    case "ACCA":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_online_first_eight_hours["ACCA"];
                        $per_hour_charges_addition = $long_term_online_after_eight_hours["ACCA"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }

                        break;

                    case "Master":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_online_first_eight_hours["Master"];
                        $per_hour_charges_addition = $long_term_online_after_eight_hours["Master"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));


                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }


                        break;

                }
            } elseif ($modeOfClass == "physical") {
                switch ($category_level_subject) {

                    case "Pre-school":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_physical_first_eight_hours["Pre-school"];
                        $per_hour_charges_addition = $long_term_physical_after_eight_hours["Pre-school"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }


                        break;

                    case "UPSR":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_physical_first_eight_hours["UPSR"];
                        $per_hour_charges_addition = $long_term_physical_after_eight_hours["UPSR"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }


                        break;

                    case "PT3":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_physical_first_eight_hours["PT3"];
                        $per_hour_charges_addition = $long_term_physical_after_eight_hours["PT3"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }


                        break;

                    case "SPM":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_physical_first_eight_hours["SPM"];
                        $per_hour_charges_addition = $long_term_physical_after_eight_hours["SPM"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }


                        break;

                    case "IGCSE":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_physical_first_eight_hours["IGCSE"];
                        $per_hour_charges_addition = $long_term_physical_after_eight_hours["IGCSE"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }


                        break;

                    case "STPM":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_physical_first_eight_hours["STPM"];
                        $per_hour_charges_addition = $long_term_physical_after_eight_hours["STPM"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }


                        break;

                    case "A-level/Pre-U":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_physical_first_eight_hours["A-level/Pre-U"];
                        $per_hour_charges_addition = $long_term_physical_after_eight_hours["A-level/Pre-U"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }

                        break;

                    case "Diploma":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_physical_first_eight_hours["Diploma"];
                        $per_hour_charges_addition = $long_term_physical_after_eight_hours["Diploma"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }

                        break;

                    case "Degree":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_physical_first_eight_hours["Degree"];
                        $per_hour_charges_addition = $long_term_physical_after_eight_hours["Degree"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }


                        break;

                    case "ACCA":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_physical_first_eight_hours["ACCA"];
                        $per_hour_charges_addition = $long_term_physical_after_eight_hours["ACCA"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }


                        break;

                    case "Master":
                        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
                        $per_hour_charges = $long_term_physical_first_eight_hours["Master"];
                        $per_hour_charges_addition = $long_term_physical_after_eight_hours["Master"];
                        $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        if ($numberOfSessions <= 8) {
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
                            $per_class_commission_after_eight_hours = 0;

                        } else {

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
                            $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

                        }


                        break;

                }
            }
        }
        else {
            if ($modeOfClass == "online") {
                switch ($category_level_subject) {

                    case "Pre-school":

                        $per_hour_charges = $short_term_online["Pre-school"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;


                    case "UPSR":
                        $per_hour_charges = $short_term_online["UPSR"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "PT3":
                        $per_hour_charges = $short_term_online["PT3"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;


                    case "SPM":

                        $per_hour_charges = $short_term_online["SPM"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;


                    case "IGCSE":
                        $per_hour_charges = $short_term_online["IGCSE"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "STPM":
                        $per_hour_charges = $short_term_online["STPM"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "A-level/Pre-U":
                        $per_hour_charges = $short_term_online["A-level/Pre-U"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "Diploma":
                        $per_hour_charges = $short_term_online["Diploma"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "Degree":
                        $per_hour_charges = $short_term_online["Degree"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "ACCA":
                        $per_hour_charges = $short_term_online["ACCA"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "Master":
                        $per_hour_charges = $short_term_online["Master"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                }
            } elseif ($modeOfClass == "physical") {
                switch ($category_level_subject) {

                    case "Pre-school":
                        $per_hour_charges = $short_term_physical["Pre-school"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "UPSR":
                        $per_hour_charges = $short_term_physical["UPSR"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "PT3":
                        $per_hour_charges = $short_term_physical["PT3"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "SPM":
                        $per_hour_charges = $short_term_physical["SPM"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "IGCSE":
                        $per_hour_charges = $short_term_physical["IGCSE"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "STPM":
                        $per_hour_charges = $short_term_physical["STPM"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "A-level/Pre-U":
                        $per_hour_charges = $short_term_physical["A-level/Pre-U"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "Diploma":
                        $per_hour_charges = $short_term_physical["Diploma"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "Degree":
                        $per_hour_charges = $short_term_physical["Degree"];

                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "ACCA":
                        $per_hour_charges = $short_term_physical["ACCA"];
                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                    case "Master":
                        $per_hour_charges = $short_term_physical["Master"];
                        $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
                        $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

                        $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
                        $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

                        break;

                }
            }
        }


        if ((isset($data["students"]))) {

            $jobTicketCalc = $subjectFee->category_price + $extraCharges;
            $jobTicketCalc = $jobTicketCalc * $data['classFrequency'] * $data['quantity'];
        }
        else {

            $jobTicketCalc = $subjectFee->category_price;
            $jobTicketCalc = $jobTicketCalc * $data['classFrequency'] * $data['quantity'];
        }


        $additionalStudentChargesTutor = 0;
        $additionalStudentChargesJobTicket = 0;
        $additionalStudentCharges = 0;

        if ($subjectDetail->class_mode == "physical") {
            if ($count == 0) {
                $additionalStudentChargesTutor = 1 * $extraStudentFeeCharges->tutor_physical;
                $additionalStudentChargesJobTicket = 1 * $extraStudentFeeCharges->physical_additional_charges;
            } else {

                $additionalStudentChargesTutor = $count * $extraStudentFeeCharges->tutor_physical;
                $additionalStudentChargesJobTicket = $count * $extraStudentFeeCharges->physical_additional_charges;
            }

        } else {
            if ($count == 0) {

                $additionalStudentChargesTutor = 1 * $extraStudentFeeCharges->tutor_online;
                $additionalStudentChargesJobTicket = 1 * $extraStudentFeeCharges->online_additional_charges;
            } else {
                $additionalStudentChargesTutor = $count * $extraStudentFeeCharges->tutor_physical;
                $additionalStudentChargesJobTicket = $count * $extraStudentFeeCharges->online_additional_charges;
            }
        }


        if ($count == 0) {
            $count = 1;
            $additionalStudentCharges = $additionalStudentChargesTutor * 1 * (floatval($data['classFrequency']) * floatval($data['quantity']));
            $additionalStudentChargesJobTicket = $additionalStudentChargesJobTicket * 1 * (floatval($data['classFrequency']) * floatval($data['quantity']));
        } else {
            $additionalStudentCharges = $additionalStudentChargesTutor * 1 * (floatval($data['classFrequency']) * floatval($data['quantity']));
            $additionalStudentChargesJobTicket = $additionalStudentChargesJobTicket * 1 * (floatval($data['classFrequency']) * floatval($data['quantity']));

        }


        // DB::table('job_tickets')
        //     ->where('id', $jobTicketLastID)
        //     ->update([
        //         'extra_student_total' => $additionalStudentChargesJobTicket,
        //         'extra_student_tutor_commission' => $additionalStudentCharges,
        //         'extra_estimate_commission_display_tutor' => $additionalStudentCharges,
        //         'estimate_commission' => $estimate_commission,
        //         'estimate_commission_display_tutor' => $estimate_after_eight_hours,
        //         'totalPrice' => $jobTicketCalc,
        //         'per_class_commission_before_eight_hours' => $per_class_commission_before_eight_hours,
        //         'per_class_commission_after_eight_hours' => $per_class_commission_after_eight_hours
        //     ]);

        return response()->json(["response"=>"RM".$jobTicketCalc,"code"=>200]);

    }

    public function tutorAttendance($token)
    {
        $parent = DB::table('customers')->where('token', $token)->first();

        if (!$parent) {
            return response()->json(["code"=>404,"msg"=>"No customer found!"], 200);
        }

        $data = DB::table("class_attendeds")
            ->leftJoin("tutors", "class_attendeds.tutorID", "=", "tutors.id")
            ->leftJoin("products", "class_attendeds.subjectID", "=", "products.id")
            ->leftJoin("categories", "class_attendeds.subjectID", "=", "products.id")
            ->leftJoin("students", "class_attendeds.studentID", "=", "students.id")
            ->leftJoin("job_tickets", "class_attendeds.ticketID", "=", "job_tickets.id")
            ->leftJoin("customers", "students.customer_id", "=", "customers.id") // Join with customers table
            ->leftjoin("states", "customers.state", "=", "states.id") // Join with states table
            ->select(
                "class_attendeds.*",
                "tutors.full_name as tutor_name",
                "tutors.tutorImage as tutor_image",
                "products.name as subject_name",
                "students.full_name as student_name",
                "job_tickets.uid as ticket_uid",
                "states.name as state_name",
                "categories.category_name as level"
            )
            ->where("students.customer_id", $parent->id)
            ->groupBy(
                "class_attendeds.id"
            )
            ->get();

        // Format date, time, and add tutor_attendance_status and location
        $formattedData = $data->map(function ($item) {

            if ($item->tutor_image == null) {
                $item->tutor_image = "https://pdtxar.com/wp-content/uploads/2019/04/person-placeholder.jpg";
            } else {
                $item->tutor_image = url("/public/tutorImage") . "/" .$item->tutor_image;
            }

            if ($item->startTimeProofImage != null) {
                $item->startTimeProofImage = url("/public/signInProof"."/".$item->startTimeProofImage);
            }

             if ($item->endTimeProofImage != null) {
                $item->endTimeProofImage = url("/public/signOutProof"."/".$item->endTimeProofImage);
            }

            $item->formatted_date = Carbon::parse($item->date)->format('M d, Y');
            $item->formatted_time = Carbon::parse($item->startTime)->format('h:i A');

            // Determine tutor_attendance_status based on status value
            if ($item->status === 'pending') {
                $item->tutor_attendance_status = 'pending';
            } else if (in_array($item->status, ['dispute', 'attended'])) {
                $item->tutor_attendance_status = 'past';
            }

            // Set the location as state_name
            $item->location = $item->state_name;

            return $item;
        });



        return Response::json(['attendance_data' => $data]);

    }

   public function tutorRequests($parent_token)
    {
        $parent_id=DB::table("customers")->where("token",$parent_token)->first();

        if($parent_id==null)
        {
            return response()->json(["msg"=>"Customer not found","code"=>404],200);
        }


        $tutorRequests = DB::table('job_tickets')
            ->select([
                'tutoroffers.id as tutor_offer_id',
                'students.full_name as student_name',
                'job_tickets.created_at as ticket_created_at',
                'job_tickets.subscription as ticket_subscription',
                'job_tickets.mode as mode',
                'job_tickets.application_status as status',
                'job_tickets.uid as uid',
                'job_tickets.created_at as created_at',
                'job_tickets.classFrequency as sessions_per_month',
                'job_tickets.quantity as class_duration',
                'job_tickets.ticket_approval_date as completion_date',
                'products.name as subject',
                'students.full_name as student_name',
                'tutors.full_name as tutor_name',
                'tutors.tutorImage as tutor_image', // Add tutor image to the selection
                'students.age as student_age',
                'students.specialNeed as special_need',
                'students.gender as student_gender',
                'states.name as state_name',
                'cities.name as city_name',

                // Convert 24-hour format to AM/PM format
                DB::raw('DATE_FORMAT(STR_TO_DATE(job_tickets.time, "%H:%i"), "%h:%i %p") as start_time'),

                // Add duration to start_time to get end_time
                DB::raw('DATE_FORMAT(
                        DATE_ADD(
                            STR_TO_DATE(job_tickets.time, "%H:%i"),
                            INTERVAL ROUND(job_tickets.quantity * 60) MINUTE
                        ),
                        "%h:%i %p"
                    ) as end_time'),

                DB::raw("
                        CASE
                            WHEN DATE(job_tickets.created_at) = CURDATE() THEN 'Today'
                            WHEN DATE(job_tickets.created_at) = CURDATE() - INTERVAL 1 DAY THEN 'Yesterday'
                            WHEN DATE(job_tickets.created_at) >= CURDATE() - INTERVAL 7 DAY THEN CONCAT(DATEDIFF(CURDATE(), DATE(job_tickets.created_at)), ' days ago')
                            WHEN DATE(job_tickets.created_at) >= CURDATE() - INTERVAL 30 DAY THEN CONCAT(FLOOR(DATEDIFF(CURDATE(), DATE(job_tickets.created_at)) / 7), ' weeks ago')
                            WHEN DATE(job_tickets.created_at) >= CURDATE() - INTERVAL 365 DAY THEN CONCAT(FLOOR(DATEDIFF(CURDATE(), DATE(job_tickets.created_at)) / 30), ' months ago')
                            ELSE CONCAT(FLOOR(DATEDIFF(CURDATE(), DATE(job_tickets.created_at)) / 365), ' years ago')
                        END as ticket_created_at
                    "),

                // Correct counting of the total tutors applied
                DB::raw('COUNT(DISTINCT tutoroffers.tutorID) as total_tutors_applied')
            ])
            ->join('students', 'job_tickets.student_id', '=', 'students.id')
            ->join('customers', 'students.customer_id', '=', 'customers.id')
            ->leftJoin('tutoroffers', 'job_tickets.id', '=', 'tutoroffers.ticketID')
            ->leftJoin('tutors', 'tutoroffers.tutorID', '=', 'tutors.id')
            ->leftJoin('states', 'customers.state', '=', 'states.id')   // Join with states table
            ->leftJoin('cities', 'customers.city', '=', 'cities.id')    // Join with cities table
            ->leftJoin('products', 'job_tickets.subjects', '=', 'products.id')
            ->where('customers.token', $parent_token)

            ->groupBy(
                'job_tickets.id',
                'students.full_name',
                'job_tickets.created_at',
                'job_tickets.subscription',
                'job_tickets.mode',
                'job_tickets.application_status',
                'job_tickets.uid',
                'job_tickets.classFrequency',
                'products.name',
                'states.name',
                'cities.name',
                'job_tickets.time',
                'job_tickets.quantity',
                'tutor_offer_id'
            )
            ->orderBy("job_tickets.id","desc")
            ->get();

            // dd(count($tutorRequests));

            // Iterate through the results to set the tutor image URL
            $tutorRequests->each(function ($request) {
                if ($request->tutor_image == null) {
                    $request->tutor_image = url("/public/person_place_holder.png");
                } else {
                    $request->tutor_image = url("/public/tutorImage") . "/" . $request->tutor_image;
                }
            });

        return Response::json(['result' => $tutorRequests,'code'=>200,"msg"=>"Data found"]);

    }

    public function tutorRequestDetails($id)
    {

        $tutorRequests = DB::table('job_tickets')
            ->select([
                'students.full_name as student_name',
                'job_tickets.created_at as ticket_created_at',
                'job_tickets.subscription as ticket_subscription',
                'job_tickets.mode as mode',
                'job_tickets.application_status as status',
                'job_tickets.uid as uid',
                'tutoroffers.ticketID as ticket_id',
                'job_tickets.created_at as created_at',
                'job_tickets.classFrequency as sessions_per_month',
                'job_tickets.quantity as class_duration',
                'job_tickets.ticket_approval_date as completion_date',
                'products.name as subject',
                'students.full_name as student_name',
                'tutors.full_name as tutor_name',
                'tutors.tutorImage as tutor_image', // Add tutor image to the selection
                'students.age as student_age',
                'students.specialNeed as special_need',
                'students.gender as student_gender',
                'states.name as state_name',
                'cities.name as city_name',
                'categories.category_name as level',

                // Convert 24-hour format to AM/PM format
                DB::raw('DATE_FORMAT(STR_TO_DATE(job_tickets.time, "%H:%i"), "%h:%i %p") as start_time'),

                // Add duration to start_time to get end_time
                DB::raw('DATE_FORMAT(
                        DATE_ADD(
                            STR_TO_DATE(job_tickets.time, "%H:%i"),
                            INTERVAL ROUND(job_tickets.quantity * 60) MINUTE
                        ),
                        "%h:%i %p"
                    ) as end_time'),

                DB::raw("
                        CASE
                            WHEN DATE(job_tickets.created_at) = CURDATE() THEN 'Today'
                            WHEN DATE(job_tickets.created_at) = CURDATE() - INTERVAL 1 DAY THEN 'Yesterday'
                            WHEN DATE(job_tickets.created_at) >= CURDATE() - INTERVAL 7 DAY THEN CONCAT(DATEDIFF(CURDATE(), DATE(job_tickets.created_at)), ' days ago')
                            WHEN DATE(job_tickets.created_at) >= CURDATE() - INTERVAL 30 DAY THEN CONCAT(FLOOR(DATEDIFF(CURDATE(), DATE(job_tickets.created_at)) / 7), ' weeks ago')
                            WHEN DATE(job_tickets.created_at) >= CURDATE() - INTERVAL 365 DAY THEN CONCAT(FLOOR(DATEDIFF(CURDATE(), DATE(job_tickets.created_at)) / 30), ' months ago')
                            ELSE CONCAT(FLOOR(DATEDIFF(CURDATE(), DATE(job_tickets.created_at)) / 365), ' years ago')
                        END as ticket_created_at
                    "),

                // Correct counting of the total tutors applied
                DB::raw('COUNT(DISTINCT tutoroffers.tutorID) as total_tutors_applied')
            ])
            ->join('students', 'job_tickets.student_id', '=', 'students.id')
            ->join('customers', 'students.customer_id', '=', 'customers.id')
            ->leftJoin('tutoroffers', 'job_tickets.id', '=', 'tutoroffers.ticketID')
            ->leftJoin('tutors', 'tutoroffers.tutorID', '=', 'tutors.id')
            ->leftJoin('states', 'customers.state', '=', 'states.id')   // Join with states table
            ->leftJoin('cities', 'customers.city', '=', 'cities.id')    // Join with cities table
            ->leftJoin('products', 'job_tickets.subjects', '=', 'products.id')
            ->leftJoin('categories', 'products.category', '=', 'categories.id')
            ->where('tutoroffers.id', $id)
            ->first();


            if ($tutorRequests->tutor_image == null) {
                $tutorRequests->tutor_image = url("/public/person_place_holder.png");
            } else {
                $tutorRequests->tutor_image = url("/public/tutorImage") . "/" . $tutorRequests->tutor_image;
            }


       $additional_students = DB::table("job_ticket_students")
            ->join("students", "job_ticket_students.student_id", "=", "students.id")
            ->where("job_ticket_id", $tutorRequests->ticket_id)
            ->get();

        if ($additional_students->isNotEmpty()) {

            foreach ($additional_students as $student) {
                $studentData = [
                    'student_name' => $student->student_name,
                    'student_age' => $student->student_age,
                    'student_gender' => $student->student_gender,
                    'year_of_birth' => $student->year_of_birth,
                    'special_need' => $student->special_need,
                    'subject_id' => $student->subject_id,
                ];

                $tutorRequests->additionalStudents[] = $studentData;
            }

        }

        return Response::json(['code'=>200,"msg"=>"Data found",'result' => $tutorRequests]);

    }

    public function getStates()
    {
        $states = DB::table('states')->get();
        return Response::json(['states' => $states]);
    }

    public function getCities($state_id)
    {
        $cities = DB::table('cities')->where("state_id", $state_id)->get();
        return Response::json(['cities' => $cities]);
    }

    function ordinal($number) {
        $suffix = ['th', 'st', 'nd', 'rd'];
        $lastDigit = $number % 10;
        $lastTwoDigits = $number % 100;

        if ($lastTwoDigits >= 11 && $lastTwoDigits <= 13) {
            return $number . 'th';
        }

        return $number . ($suffix[$lastDigit] ?? 'th');
    }



    public function getClassSchedules($token)
    {
        $parent = DB::table('customers')->where('token', $token)->first();

        if (!$parent) {
            return response()->json(["code"=>404,"msg"=>"No customer found!"], 200);
        }

        $classSchedules = DB::table('class_schedules')
            ->select([
                'class_schedules.date as class_date',
                DB::raw("DATE_FORMAT(class_schedules.startTime, '%h:%i %p') as start_time"),
                DB::raw("DATE_FORMAT(class_schedules.endTime, '%h:%i %p') as end_time"),
                'job_tickets.mode as mode',
                'job_tickets.id as job_ticket_id',
                'job_tickets.uid as uid',
                'job_tickets.classFrequency as class_frequency',
                'students.full_name as student_name',
                DB::raw('IFNULL(tutors.full_name, "No tutor") as tutor_name'),
                'products.name as subject_name',
                DB::raw("
                            CASE
                                WHEN DATE(job_tickets.created_at) = CURDATE() THEN 'Today'
                                WHEN DATE(job_tickets.created_at) = CURDATE() - INTERVAL 1 DAY THEN 'Yesterday'
                                WHEN DATE(job_tickets.created_at) >= CURDATE() - INTERVAL 7 DAY THEN CONCAT(DATEDIFF(CURDATE(), DATE(job_tickets.created_at)), ' days ago')
                                WHEN DATE(job_tickets.created_at) >= CURDATE() - INTERVAL 30 DAY THEN CONCAT(FLOOR(DATEDIFF(CURDATE(), DATE(job_tickets.created_at)) / 7), ' weeks ago')
                                WHEN DATE(job_tickets.created_at) >= CURDATE() - INTERVAL 365 DAY THEN CONCAT(FLOOR(DATEDIFF(CURDATE(), DATE(job_tickets.created_at)) / 30), ' months ago')
                                ELSE CONCAT(FLOOR(DATEDIFF(CURDATE(), DATE(job_tickets.created_at)) / 365), ' years ago')
                            END as posted
                        "),
                //  DB::raw('1 + COUNT(job_ticket_students.student_id) as students_count'),  // Count the total number of students
               DB::raw("
                        CASE
                            WHEN class_schedules.status = 'attended' THEN 'Past'
                            ELSE 'Upcoming'
                        END as status
                    "),
                'states.name as state_name',  // Add the state name to the selection
                DB::raw('ROW_NUMBER() OVER (PARTITION BY class_schedules.ticketID ORDER BY class_schedules.date) as schedule_position')  // Calculate the position of each class schedule
            ])
            ->leftjoin('job_tickets', 'class_schedules.ticketID', '=', 'job_tickets.id')
            ->leftjoin('students', 'class_schedules.studentID', '=', 'students.id')
            ->leftjoin('customers', 'students.customer_id', '=', 'customers.id')
            ->leftjoin('states', 'customers.state', '=', 'states.id')  // Join states table
            ->leftjoin('tutoroffers', 'job_tickets.id', '=', 'tutoroffers.ticketID')
            ->leftjoin('tutors', 'tutoroffers.tutorID', '=', 'tutors.id')
            ->leftjoin('products', 'class_schedules.subjectID', '=', 'products.id')
            ->leftjoin('job_ticket_students', 'job_tickets.id', '=', 'job_ticket_students.job_ticket_id')
            ->where('customers.token', $token)
            ->where("class_schedules.date", "!=", null)
            ->groupBy(
                'class_schedules.id'
                // 'job_tickets.id',
                // 'class_schedules.date',
                // 'class_schedules.startTime',
                // 'class_schedules.endTime',
                // 'job_tickets.mode',
                // 'job_tickets.classFrequency',
                // 'students.full_name',
                // 'tutors.full_name',
                // 'products.name',
                // 'job_tickets.created_at',
                // 'states.name'
            )
            ->get();

        foreach ($classSchedules as $schedule) {
            $schedule->formatted_position = $this->ordinal($schedule->schedule_position);
            $schedule->students_count = DB::table("job_ticket_students")->where("job_ticket_id", $schedule->job_ticket_id)->count() + 1;

        }


        return Response::json(['classSchedules' => $classSchedules]);
    }

     public function getDueInvoices($token)
    {
        $parent_id=DB::table("customers")->where("token",$token)->first();

        if($parent_id==null)
        {
            return response()->json(["msg"=>"Customer not found","code"=>404],200);
        }
        $studentIds = Student::where('customer_id', $parent_id->id)->pluck('id');

        $invoices = Invoice::join("products","invoices.subjectID","=","products.id")
            ->join("job_tickets","invoices.ticketID","=","job_tickets.id")
            ->join("students","invoices.studentID","=","students.id")
            ->join("categories","products.category","=","categories.id")
            ->select("invoices.*","products.name as subject","job_tickets.classFrequency as no_of_classes","students.full_name as student","categories.category_name as level",
                "job_tickets.uid as uid"
            )

            ->whereIn('studentID', $studentIds)->orderBy("id","desc")->get();

        return response()->json(["result" => $invoices], 200);

    }


    public function getUpcomingClasses($token)
    {
        $parent_id=DB::table("customers")->where("token",$token)->first();

        if($parent_id==null)
        {
            return response()->json(["msg"=>"Customer not found","code"=>404],200);
        }

        $classSchedules = DB::table('class_schedules')
            ->join('products', 'class_schedules.subjectID', '=', 'products.id')
            ->join('students', 'class_schedules.studentID', '=', 'students.id')
            ->join('customers', 'students.customer_id', '=', 'customers.id')
            ->where('customers.token', '=', $token)
            ->where('class_schedules.status', '=', 'scheduled')
            ->where('class_schedules.class_schedule_id', '!=', 0)
            ->where('class_schedules.date', '>=', date("Y-m-d"))
            ->select(
                'class_schedules.id as id',
                'class_schedules.ticketID as ticketID',
                'products.name as subject_name',
                'products.id as subject_id',
                'students.full_name as studentName',
                'students.address1 as studentAddress1',
                'students.address2 as studentAddress2',
                'students.city as studentCity',
                'class_schedules.date as date',
                'class_schedules.startTime',
                'class_schedules.endTime'
            )
            ->get();

        return response()->json(["result" => $classSchedules], 200);
    }

    public function getTodayClasses($token)
    {

        $parent = DB::table('customers')->where('token', $token)->first();

        if (!$parent) {
            return response()->json(["code"=>404,"msg"=>"No customer found!"], 200);
        }

        $classSchedules = DB::table('class_schedules')
            ->join('products', 'class_schedules.subjectID', '=', 'products.id')
            ->join('students', 'class_schedules.studentID', '=', 'students.id')
            ->join('customers', 'students.customer_id', '=', 'customers.id')
            ->leftjoin('states', 'customers.state', '=', 'states.id')
            ->leftjoin('cities', 'customers.city', '=', 'cities.id')
            ->where('customers.token', '=', $token)
            ->where('class_schedules.status', '=', 'scheduled')
            ->whereDate('class_schedules.date', '=', date("Y-m-d"))  // Filter for the current date
            ->select(
                'class_schedules.id as id',
                'class_schedules.ticketID as ticketID',
                'products.name as subject_name',
                'products.id as subject_id',
                'students.full_name as studentName',
                'customers.address1 as studentAddress1',
                'customers.address2 as studentAddress2',
                'states.name as studentState',
                'cities.name as studentCity',
                'class_schedules.date as date',
                DB::raw("DATE_FORMAT(class_schedules.startTime, '%h:%i %p') as startTime"),
                DB::raw("DATE_FORMAT(class_schedules.endTime, '%h:%i %p') as endTime")
            )
            ->get();

        return response()->json(["result" => $classSchedules,'code'=>200], 200);
    }


    public function approveAttendance($id)
    {

        $class_schedule_id = DB::table('class_attendeds')->where('id', $id)->first();

        $classSchedule = DB::table("class_schedules")->where("id",$class_schedule_id->class_schedule_id)->first();


        if ($class_schedule_id) {
            DB::table('class_attendeds')->where('id', $id)->update(['status' => 'attended','totalTime'=>$classSchedule->totalTime]);
            DB::table('class_schedules')->where('id', $class_schedule_id->class_schedule_id)->update(['status' => 'attended']);
            return response()->json(["msg" => "Attendance Approved","code"=>200]);

        } else {
            return response()->json(["msg" => "Class Schedule Not Found","code"=>404]);
        }

    }

    public function rejectAttendance($id)
    {

        $class_schedule_id = DB::table('class_attendeds')->where('id', $id)->first();

        if ($class_schedule_id) {
            DB::table('class_attendeds')->where('id', $id)->update(['status' => 'dispute']);
            DB::table('class_schedules')->where('id', $class_schedule_id->class_schedule_id)->update(['status' => 'dispute']);
            return response()->json(["msg" => "Attendance Rejected"], 200);
        } else {
            return response()->json(["msg" => "Class Schedule Not Found"], 200);
        }
    }

    public function news()
    {
        $baseUrl = url("/public/MobileNewsImages/") . "/";
        $news = DB::table('news')
            ->select('*', DB::raw("CONCAT('$baseUrl', headerimage) AS headerimage"))
            ->get();
        return Response::json(['news' => $news]);
    }


    public function payCommitmentFee(Request $request)
    {


        $parent = DB::table('customers')->where('token', $request->token)->first();

        if (!$parent) {
            return response()->json(["code"=>404,"msg"=>"No customer found!"], 200);
        }

        $feePaymentValue = array(
            'customer_id' => $parent->id,
            'payment_amount' => $request->feeAmount,
            'payment_date' => $request->feePaymentDate,
            'receiving_account' => $request->receivingAccount,
        );

        DB::table('customers')->where('id',  $request->parent_id)->update(["status" => "active"]);

        DB::table('customer_commitment_fees')
            ->where('customer_id', $request->id)
            ->insert($feePaymentValue);

        return response()->json(["msg"=>"Data Updated Successfully"],200);
    }

    public function faqs()
    {
        $faqs = DB::table('faqs')
            ->get();
        return Response::json(['faqs' => $faqs]);
    }

    public function submitEvaluationReport(Request $request)
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

    public function evaluationReportListing($token)
    {
        $parent = DB::table('customers')->where('token', $token)->first();

        if (!$parent) {
            return response()->json(["code" => 404, "msg" => "No customer found!"], 200);
        }

        $baseUrl = rtrim(url("/template/"), '/') . '/';
        $parentID = $parent->id;

        $tutorReportListing = DB::table('tutorFirstSubmittedReportFromApps')
            ->leftjoin('students', 'tutorFirstSubmittedReportFromApps.studentID', '=', 'students.id')
            ->leftjoin('tutors', 'tutorFirstSubmittedReportFromApps.tutorID', '=', 'tutors.id')
            ->leftjoin('products', 'tutorFirstSubmittedReportFromApps.subjectID', '=', 'products.id')
            ->leftjoin('class_schedules', 'tutorFirstSubmittedReportFromApps.scheduleID', '=', 'class_schedules.id')
            ->leftjoin('job_tickets', 'class_schedules.ticketID', '=', 'job_tickets.id')
            ->select(
                DB::raw("CONCAT('$baseUrl', tutorFirstSubmittedReportFromApps.logoImage) AS logo"),
                'students.full_name as studentName',
                'students.uid as student_id',
                'tutors.id as tutorID',
                'tutors.full_name as tutorName',
                'tutors.displayName as tutorDisplayName',
                'products.id as subjectID',
                'products.name as subjectName',
                'job_tickets.uid as uid',
                DB::raw("DATE_FORMAT(tutorFirstSubmittedReportFromApps.created_at, '%d-%b-%Y') as submittedDate"),
                'tutorFirstSubmittedReportFromApps.*',
                DB::raw("CONCAT('$baseUrl', tutorFirstSubmittedReportFromApps.logoImage) AS logo"),
                DB::raw('MONTHNAME(STR_TO_DATE(tutorFirstSubmittedReportFromApps.currentDate, "%m/%d/%Y")) as month'),
                DB::raw("DATE_FORMAT(tutorFirstSubmittedReportFromApps.created_at, '%d-%b-%Y') as submittedDate")
            )
            ->where('students.customer_id', $parentID)
            ->orderBy('tutorFirstSubmittedReportFromApps.id', 'desc')
            ->get();

        $allFormattedData = [];

        foreach ($tutorReportListing as $key => $report) {
            $allFormattedData[] = [
                'id' => $key + 1,
                'knowledge' => [
                    'test' => 'Knowledge',
                    'icon' => 'require(\'../../Images/Performance-icon.png\')',
                    'q1' => 'How well does the student recall basic concepts?',
                    'a1' => $report->knowledge,
                    'q2' => 'How well does the student share their ideas on the topics under discussion?',
                    'a2' => $report->knowledge2,

                    'open' => false
                ],
                'understanding' => [
                    'test' => 'Understanding',
                    'icon' => 'require(\'../../Images/Performance-icon.png\')',
                    'q1' => 'How well does the student explain the basic concepts?',
                    'a1' => $report->understanding,
                    'q2' => 'How well does the student apply learned concepts to solve problems or answer questions?',
                    'a2' => $report->understanding2,

                    'open' => false
                ],
                'critical_thinking' => [
                    'test' => 'Critical Thinking',
                    'icon' => 'require(\'../../Images/Performance-icon.png\')',
                    'q1' => 'How well does the student solve different types of questions with minimal guidance?',
                    'a1' => $report->criticalThinking,
                    'q2' => 'How well is the is the student able to answer questions using a variety of methods and concepts? ',
                    'a2' => $report->criticalThinking2,
                    'open' => false
                ],
                 'observation' => [
                    'test' => 'Observation',
                    'icon' => 'require(\'../../Images/Performance-icon.png\')',
                    'q1' => 'What is the student\'s learning style? Do you believe it is effective for them?',
                    'a1' => $report->observation,
                    'open' => false
                ],
                'additional_ssessment' => [
                    'test' => 'Additional Assessment',
                    'icon' => 'require(\'../../Images/result-icon.png\')',
                    'q1' => 'What is the current score for the student in the first assessment?[SCORE]/10',
                    'a1' => $report->additionalAssisment,
                    'q2' => 'This is the tutoring plan designed to provide the most effective support for the student',
                    'a2' => $report->additionalAssisment2,
                    'open' => false
                ],
                'student_name' => $report->studentName,
                'tutor' => $report->tutorName,
                'created_at' => $report->created_at,
                'subject_name' => $report->subjectName,
                'logo' => $report->logo,
                'submitted_date' => $report->submittedDate,
                'month' => $report->month,
                "report_type"=>"Evaluation Report",
                "uid"=>$report->uid
            ];
        }

    return response()->json(['code' => 200,'evaluationReportListing' => $allFormattedData]);

    }

    public function evaluationReportView($id)
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

    public function submitProgressReport(Request $request)
    {

        $values = array(
            'tutorID' => $request->tutorID,
            'studentID' => $request->studentID,
            'subjectID' => $request->subjectID,
            'reportType' => 'Progress Report',
            'month' => $request->month,

            'observation' => $request->observation,
            'observation2' => $request->observation2,
            'observation3' => $request->observation3,
            'observation4' => $request->observation4,
            'observation5' => $request->observation5,
            'observation6' => $request->observation6,

            'performance' => $request->performance,
            'performance2' => $request->performance2,
            'performance3' => $request->performance3,
            'performance4' => $request->performance4,
            'performance5' => $request->performance5,
            'performance6' => $request->performance6,

            'attitude' => $request->attitude,
            'attitude2' => $request->attitude2,
            'attitude3' => $request->attitude3,
            'attitude4' => $request->attitude4,
            'attitude5' => $request->attitude5,
            'attitude6' => $request->attitude6,


            'result' => $request->result,
            'result2' => $request->result2,
            'result3' => $request->result3,
            'result4' => $request->result4,


        );

        $submitClassScheduleTime = DB::table('progressReport')->insertGetId($values);


        return Response::json(['successMessage' => 'Progress Report Submitted Successfully']);
    }

   public function progressReportListing($token)
    {

        $parent = DB::table('customers')->where('token', $token)->first();

        if (!$parent) {
            return response()->json(["code"=>404,"msg"=>"No customer found!"], 200);
        }

        $baseUrl = rtrim(url("/template/"), '/') . '/';
        $parentID = $parent->id; // Example parent ID value
//   dd($parentID);
        $progressReportListing = DB::table('progressReport')
            ->leftjoin('students', 'progressReport.studentID', '=', 'students.id')
            ->leftjoin('tutors', 'progressReport.tutorID', '=', 'tutors.id')
            ->leftjoin('products', 'progressReport.subjectID', '=', 'products.id')
            ->leftjoin('class_schedules', 'progressReport.scheduleID', '=', 'class_schedules.id')
            ->leftjoin('job_tickets', 'class_schedules.ticketID', '=', 'job_tickets.id')

            ->select(
                'students.full_name as studentName',
                'students.student_id as student_id',
                'tutors.id as tutorID',
                'tutors.full_name as tutorName',
                'tutors.displayName as tutorDisplayName',
                'products.id as subjectID',
                'products.name as subjectName',
                'job_tickets.uid as uid',

                DB::raw("CONCAT('$baseUrl', progressReport.logoImage) AS logo"),
                DB::raw("MONTHNAME(STR_TO_DATE(progressReport.currentDate, '%m/%d/%Y')) as month"),
                DB::raw("DATE_FORMAT(progressReport.created_at, '%d-%b-%Y') as submittedDate"),
                'progressReport.*'
            )
            ->where('students.customer_id', $parentID) // Filter by parent ID
            ->orderBy('progressReport.id', 'desc')
            ->get();

        $allFormattedData = [];

        foreach ($progressReportListing as $key => $report) {
            // Combine all data into one object
            $allFormattedData[] = [
                'id' => $key + 1,
                'observation' => [
                    'test' => 'Observation',
                    'icon' => 'require(\'../../Images/Performance-icon.png\')',
                    'q1' => 'Did you (tutor) hold or carried out any form of examination/test/quiz for the student within this 3 months?',
                    'a1' => $report->observation,
                    'q2' => 'What is the student\'s learning style?',
                    'a2' => $report->observation2,
                    'q3' => 'What significant improvement do you see in the student\'s performance compared to before?',
                    'a3' => $report->observation3,
                    'q4' => 'Please suggest the parts that the student should improve and focus on?',
                    'a4' => $report->observation4,
                    'q5' => 'Please suggest the parts that the student should improve and focus on?',
                    'a5' => $report->observation5,
                    'q6' => 'Please suggest the parts that the student should improve and focus on?',
                    'a6' => $report->observation6,
                    'open' => false
                ],
                'performance' => [
                    'test' => 'Performance',
                    'icon' => 'require(\'../../Images/Performance-icon.png\')',
                    'q1' => 'How well does the student understand this subject?',
                    'a1' => $report->performance,
                    'q2' => 'How well the student’s performance during these 3 months?',
                    'a2' => $report->performance2,
                    'q3' => 'How well student’s participates in learning session?',
                    'a3' => $report->performance3,
                    'q4' => 'How well the student answers/explains/elaborates questions given by tutor?',
                    'a4' => $report->performance4,
                    'q5' => 'How would you rate the student\'s level of improvement over the past month?',
                    'a5' => $report->performance5,
                    'q6' => 'Comment (Additional)',
                    'a6' => $report->performance6,
                    'open' => false
                ],
                'attitude' => [
                    'test' => 'Attitude',
                    'icon' => 'require(\'../../Images/Performance-icon.png\')',
                    'q1' => 'How well is the student’s attendance for the last 3 months?',
                    'a1' => $report->attitude,
                    'q2' => 'How well do you interact/communicate with the student during/after class?',
                    'a2' => $report->attitude2,
                    'q3' => 'How well does the student manage their tasks given?',
                    'a3' => $report->attitude3,
                    'q4' => 'How strong is the student\'s willingness to learn?',
                    'a4' => $report->attitude4,
                    'q5' => 'What are the student\'s interests towards the subject?',
                    'a5' => $report->attitude5,
                    'q6' => 'Comment (Additional)',
                    'a6' => $report->attitude6,
                    'open' => false
                ],
                'result' => [
                    'test' => 'Result',
                    'icon' => 'require(\'../../Images/result-icon.png\')',
                    'q1' => 'How well does the student perform in quizzes/tests?',
                    'a1' => $report->result,
                    'q2' => 'How well does the student prepare for tests and assignments?',
                    'a2' => $report->result2,
                    'q3' => 'How are the student’s test scores at school?',
                    'a3' => $report->result3,
                    'q4' => 'Comment (Additional)',
                    'a4' => $report->result4,
                    'open' => false
                ],
                'student_name' => $report->studentName,
                'tutor' => $report->tutorName,
                'created_at' => $report->created_at,
                'subject_name' => $report->subjectName,
                'logo' => $report->logo,
                'submitted_date' => $report->submittedDate,
                'month' => $report->month,
                "report_type"=>"Progress Report",
                "uid"=>$report->uid
            ];
        }

        return response()->json(['code'=>200,'progressReportListing' => $allFormattedData]);

    }

    public function submitClassSchedulesAdmin(Request $request)
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

        // $maxId = DB::table('class_schedules')->max('id');
        $tutorOfferValues = array(
            'class_schedule_id' => $request->classScheduleID,
            'date' => $request->date,
            'tutorID' => $request->tutorID,
            'studentID' => $request->studentID,
            'subjectID' => $request->subjectID,
            'ticketID' => $request->ticketID,
            'startTime' => $request->start_time,
            'endTime' => $request->end_time,
            'status' => 'scheduled',
            'totalTime' => number_format($differenceInHours, 2)
        );
        DB::table('class_schedules')->insertGetId($tutorOfferValues);

        $remaining_classes = DB::table('job_tickets')
            ->where('id', $request->ticketID)
            ->first();

        $last_month_additional_classes = 0;
        if ($remaining_classes->remaining_classes <= 0) {

            $last_month_additional_classes = $remaining_classes->additional_classes + 1;
            $remaining_classes = 0;

        } else {
            $remaining_classes = $remaining_classes->remaining_classes - 1;
        }


        DB::table('job_tickets')
            ->where('id', $request->ticketID)
            ->update([
                'remaining_classes' => $remaining_classes,
                'additional_classes' => $last_month_additional_classes,
            ]);


        DB::table('student_subjects')
            ->where('ticket_id', $request->ticketID)
            ->update(['remaining_classes' => $remaining_classes]);

        $tutor = DB::table("tutors")->where("id",$request->tutorID)->first();
        $student = DB::table("students")->where("id",$request->studentID)->first();
        $parent = DB::table("customers")->where("id",$student->customer_id)->first();
        //app home record event

        try {

            $data = ["Admin Added Class Schedule from Admin Panel Event"];
            event(new TutorDashboard($data,$tutor->token));
            event(new SingleParentDashboard($data,$parent->token));

        } catch(Exception $e) {
            return response()->json(["ResponseCode"=> "103",
                "error"=> "Unable to Add Class Schedule by Admin"]);
        }



        return response()->json("Class Schedule Added Successfully", 200);
    }

    public function savePaymentInfo(Request $request)
    {
        $data = [
            'card_no' => $request->input('card_no'),
            'cvv' => $request->input('cvv'),
            'expiry_date' => $request->input('expiry_date'),
            'user_id' => $request->input('user_id'),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('payment_cards')->insert($data);

        return response()->json(['message' => 'Payment card stored successfully'], 201);
    }

    public function paymentCards($id)
    {
        $data = DB::table('payment_cards')->where("user_id", $id)->get();
        return response()->json(['payment_info' => $data], 200);

    }


}
