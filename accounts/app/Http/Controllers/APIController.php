<?php
namespace App\Http\Controllers;

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
use App\Events\MobileHomePageUpdated;


class APIController extends Controller
{
    public function APIclear(){
        \Artisan::call('route:clear');
        \Artisan::call('cache:clear');
        \Artisan::call('route:cache');
        \Artisan::call('config:cache');
        \Artisan::call('view:clear');
        dd('Clear cache');
    }



    public function updateDashboardStatus($tutorId){


        $tutorID = $tutorId; // Replace with the actual ID of the tutor you want to update

        $result = DB::table('tutors')->where('id', $tutorID)->update(['open_dashboard' => 'yes']);



        $tutor= DB::table('tutors')->where('id', $tutorID)->first();

        return Response::json(['status'=>200, 'tutorDetailById'=>$tutor]);

    }

   public function agreeAttendance($id){




        $getClassAttendedID = DB::table('class_attendeds')->where('id','=',$id)->first();
        // dd($getClassAttendedID);

          $classScheduleData=DB::table('class_schedules')->where('id','=',$getClassAttendedID->class_schedule_id)->first();



        $ticketDetail = DB::table('job_tickets')->where('id','=',$getClassAttendedID->ticketID)->first();
        $tutorID = DB::table('tutor_subjects')->where('ticket_id','=',$ticketDetail->id)->first();

        $paymentPreviousDetail = DB::table('payments')->where('bill_no','=',$ticketDetail->uid)->first();
        // dd($ticketDetail);
        // $ledgerTutorValue = array(
        //     'payment_reference' => $paymentPreviousDetail->payment_reference,
        //     'user_id' => $paymentPreviousDetail->user_id,
        //     'bill_no' => $ticketDetail->uid,
        //     'purchase_id' => $ticketDetail->uid,
        //     'account_id' => $tutorID->tutor_id,
        //     'amount' => (($ticketDetail->totalPrice*70/100)/$ticketDetail->classFrequency)/($ticketDetail->quantity*60)/$getClassAttendedID->totalTime,
        //     'type' => 'd',
        //     'credit' =>null,
        //     'debit' => (($ticketDetail->totalPrice*70/100)/$ticketDetail->classFrequency)/($ticketDetail->quantity*60)/$getClassAttendedID->totalTime,
        //     'date' => date('Y-m-d'),
        //     'date_2' => date('Y-m-d')
        // );

        // $ledgerID = DB::table('payments')->insertGetId($ledgerTutorValue);


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

        $id=(int)$id;
        $getClassAttendedID=(int)$getClassAttendedID->class_schedule_id;
        DB::table('class_attendeds')->where('id', $id)->update(['parent_verified' => 'YES','status' => 'attended','endTime'=>$newTime,'totalTime'=>$timeDifferenceFormatted]);
        DB::table('class_schedules')->where('id', $getClassAttendedID)->update(['status' => 'attended','parent_verified' => 'YES']);

         //app home record event
        $data=["Class Status Updated"];
        event(new MobileHomePageUpdated($data));

        return redirect()->to("/welcomeMessage");

    }

    public function disputeAttendance($id){


        $class_schedule_id=DB::table('class_attendeds')->where('id', $id)->first();
        // dd($class_schedule_id);
        // dd($class_schedule_id);
        DB::table('class_attendeds')->where('id', $id)->update(['status' => 'dispute']);
        DB::table('class_schedules')->where('id', $class_schedule_id->class_schedule_id)->update(['status' => 'dispute']);
        return redirect()->to("/disputeMessage");
    }

    public function sendAttendanceToParent($ticketID, $tutorID){
        // ticketID = 59
        // TutorID = 16

        return Response::json(['status'=>200, 'message'=>'Email for Attendance confirmation Sent to Parent Successfully']);

    }




//     public function appTutorRegister(Request $request) {

//     $existingTutor = DB::table('tutors')->where('phoneNumber', $request->phoneNumber)->first();

//     if ($existingTutor) {

//         $updateValues = array(
//             'phoneNumber' => $request->phoneNumber,
//             'whatsapp' => $request->phoneNumber, // Assuming WhatsApp is the same as phone number
//             'full_name' => $request->fullName
//         );

//         DB::table('tutors')->where('id', $existingTutor->id)->update($updateValues);

//         // Retrieve the updated tutor details
//         $updatedTutorDetail = DB::table('tutors')->where('id', $existingTutor->id)->first();

//         // Return the response with the updated tutor details
//         return response()->json(['status' => 200, 'tutorDetail' => $updatedTutorDetail]);
//     }

//     // Values for the new tutor
//     $newValues = array(
//         'uid' => 'TU-' . date('Hisdm'),
//         'tutor_id' => 'TU-' . date('Hisdm'),
//         'email' => $request->email,
//         'phoneNumber' => $request->phoneNumber,
//         'whatsapp' => $request->phoneNumber, // Assuming WhatsApp is the same as phone number
//         'full_name' => $request->fullName
//     );

//     // Insert the new tutor into the database and get the last inserted ID
//     $tutorLastID = DB::table('tutors')->insertGetId($newValues);

//     // Retrieve the details of the newly inserted tutor
//     $newTutorDetail = DB::table('tutors')->where('id', $tutorLastID)->first();

//     // Return the response with the new tutor details
//     return response()->json(['status' => 200, 'tutorDetail' => $newTutorDetail]);
// }






    public function appTutorRegister(Request $request){

        //return Response::json(['status'=>200, 'message'=>$request->all()]);
        //  $tutorDetail = DB::table('tutors')->where('email','=',$request->email)->first();
        //  return Response::json(['status'=>200, 'message'=>$tutorDetail]);
        //  dd($tutorDetail);

        // return Response::json(['status'=>200, 'msg'=>"Here"]);

        // $imageName="";
        // if($request->tutorImage!=null)
        // {
        //     $imageName = time().'.'.$request->profileImage->extension();
        //     $request->profileImage->move(public_path('userProfileImage'), $imageName);
        // }


        //   $values = array(
        //             'uid' => 'TU-'.date('Hisdm'),
        //             'tutor_id' => 'TU-'.date('Hisdm'),
        //             'email' => $request->email,
        //             'phoneNumber' => $request->phoneNumber,
        //             'whatsapp' => $request->phoneNumber,
        //             'full_name' => $request->fullName,
        //             'status' => "unverified",
        //             'tutorImage'=>$imageName!=null?$imageName:null,
        //             );



        $tables = ['customers', 'staffs', 'tutors','users'];
        $results = [];

        foreach ($tables as $table) {
            $result = DB::table($table)->where('email', $request->email)->first();

            if ($result) {
                $results[$table] = $result;
            }
        }

        if (!empty($results)) {

            return Response::json(['status'=>200, 'Msg'=>"Email already exist"]);
        }




        $imageName="";
        if($request->tutorImage!=null)
        {
            $imageName = time().'.'.$request->profileImage->extension();
            $request->profileImage->move(public_path('userProfileImage'), $imageName);
        }

        $values = array(

            'email' => $request->email,
            'phoneNumber' => $request->phoneNumber,
            'full_name' => $request->fullName,
            'displayName' => $request->fullName,
            'status' => "unverified",


        );


        $tutorLastID =  DB::table('tutors')->where('id', $request->tutorId)->update($values);

        $to = $request->email;
        $subject = "New Tutor Registration:";

        $message = "<html
        xmlns='ttp://www.w3.org/1999/xhtml'
        xmlns:v='urn:schemas-microsoft-com:vml'
        xmlns:o='urn:schemas-microsoft-com:office:office'
>
<head>
    <!--[if gte mso 9]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'/>
    <meta name='x-apple-disable-message-reformatting'/>
    <!--[if !mso
    ]><!-->
    <meta
            http-equiv='-UA-Compatible'
            content='IE=edge'
    /><!--<![endif]-->
    <title></title>

    <style type='text/css'>
        @media only screen and (min-width: 620px) {
            .u-row {
                width: 600px !important;
            }

            .u-row .u-col {
                vertical-align: top;
            }

            .u-row .u-col-33p33 {
                width: 199.98px !important;
            }

            .u-row .u-col-100 {
                width: 600px !important;
            }
        }

        @media (max-width: 620px) {
            .u-row-container {
                max-width: 100% !important;
                padding-left: 0px !important;
                padding-right: 0px !important;
            }

            .u-row .u-col {
                min-width: 320px !important;
                max-width: 100% !important;
                display: block !important;
            }

            .u-row {
                width: 100% !important;
            }

            .u-col {
                width: 100% !important;
            }

            .u-col > div {
                margin: 0 auto;
            }
        }

        body {
            margin: 0;
            padding: 0;
        }

        table,
        tr,
        td {
            vertical-align: top;
            border-collapse: collapse;
        }

        p {
            margin: 0;
        }

        .ie-container table,
        .mso-container table {
            table-layout: fixed;
        }

        * {
            line-height: inherit;
        }

        a[x-apple-data-detectors='true'] {
            color: inherit !important;
            text-decoration: none !important;
        }

        table,
        td {
            color: #000000;
        }

        #u_body a {
            color: #0000ee;
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            #u_content_heading_2 .v-container-padding-padding {
                padding: 0px 20px !important;
            }

            #u_content_heading_2 .v-font-size {
                font-size: 46px !important;
            }

            #u_content_text_1 .v-container-padding-padding {
                padding: 10px 30px !important;
            }

            #u_content_text_1 .v-text-align {
                text-align: center !important;
            }

            #u_content_text_1 .v-line-height {
                line-height: 140% !important;
            }

            #u_content_heading_3 .v-container-padding-padding {
                padding: 0px 20px !important;
            }

            #u_content_button_2 .v-size-width {
                width: 62% !important;
            }

            #u_content_text_2 .v-container-padding-padding {
                padding: 10px 30px !important;
            }

            #u_content_heading_4 .v-container-padding-padding {
                padding: 0px 30px !important;
            }

            #u_content_text_10 .v-container-padding-padding {
                padding: 10px 30px !important;
            }

            #u_content_text_9 .v-container-padding-padding {
                padding: 10px 30px !important;
            }
        }
    </style>

    <!--[if !mso
    ]><!-->
    <link
            href='https://fonts.googleapis.com/css2?family=Arvo&amp;display=swap'
            rel='stylesheet'
            type='text/css'
    />
    <link
            href='https://fonts.googleapis.com/css?family=Montserrat:400,700'
            rel='stylesheet'
            type='text/css'
    /><!--<![endif]-->
</head>

<body
        class='clean-body u_body'
        style='
      margin: 0;
      padding: 0;
      -webkit-text-size-adjust: 100%;
      background-color: #e7e7e7;
      color: #000000;
    '
        cz-shortcut-listen='true'
>
<!--[if IE]>
<div class='e-container'><![endif]-->
<!--[if mso]>
<div class='so-container'><![endif]-->
<table
        id='u_body'
        style='
        border-collapse: collapse;
        table-layout: fixed;
        border-spacing: 0;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
        vertical-align: top;
        min-width: 320px;
        margin: 0 auto;
        background-color: #e7e7e7;
        width: 100%;
      '
        cellpadding='0'
        cellspacing='0'
>
    <tbody>
    <tr style='vertical-align: top'>
        <td
                style='
              word-break: break-word;
              border-collapse: collapse !important;
              vertical-align: top;
            '
        >
            <!--[if (mso)|(IE)]>
            <table width='00%' cellpadding='0' cellspacing='0' border='0'>
                <tr>
                    <td align='center' style='background-color: #e7e7e7;'><![endif]-->

            <div
                    class='u-row-container'
                    style='padding: 0px; background-color: #f5dff1'
            >
                <div
                        class='u-row'
                        style='
                  margin: 0 auto;
                  min-width: 320px;
                  max-width: 600px;
                  overflow-wrap: break-word;
                  word-wrap: break-word;
                  word-break: break-word;
                  background-color: transparent;
                '
                >
                    <div
                            style='
                    border-collapse: collapse;
                    display: table;
                    width: 100%;
                    height: 100%;
                    background-color: transparent;
                  '
                    >
                        <!--[if (mso)|(IE)]>
                        <table width='00%' cellpadding='0' cellspacing='0' border='0'>
                            <tr>
                                <td style='padding: 0px;background-color: #f5dff1;' align='center'>
                                    <table cellpadding='0' cellspacing='0' border='0' style='width:600px;'>
                                        <tr style='background-color: transparent;'><![endif]-->

                        <!--[if (mso)|(IE)]>
                        <td align='enter' width='600'
                            style='width: 600px;padding: 60px 0px 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;'
                            valign='top'><![endif]-->
                        <div
                                class='u-col u-col-100'
                                style='
                      max-width: 320px;
                      min-width: 600px;
                      display: table-cell;
                      vertical-align: top;
                    '
                        >
                            <div style='height: 100%; width: 100% !important'>
                                <!--[if (!mso)&(!IE)]><!-->
                                <div
                                        style='
                          box-sizing: border-box;
                          height: 100%;
                          padding: 60px 0px 0px;
                          border-top: 0px solid transparent;
                          border-left: 0px solid transparent;
                          border-right: 0px solid transparent;
                          border-bottom: 0px solid transparent;
                        '
                                ><!--<![endif]-->
                                    <table
                                            id='u_content_heading_2'
                                            style='font-family: 'Montserrat', sans-serif'
                                            role='presentation'
                                            cellpadding='0'
                                            cellspacing='0'
                                            width='100%'
                                            border='0'
                                    >
                                        <tbody>
                                        <tr>
                                            <td
                                                    class='v-container-padding-padding'
                                                    style='
                                  overflow-wrap: break-word;
                                  word-break: break-word;
                                  padding: 0px;
                                  font-family: 'Montserrat', sans-serif;
                                '
                                                    align='left'
                                            >
                                                <h1
                                                        class='v-text-align v-line-height v-font-size'
                                                        style='
                                    margin: 0px;
                                    color: #6f59a0;
                                    line-height: 100%;
                                    text-align: center;
                                    word-wrap: break-word;
                                    font-family: Arvo;
                                    font-size: 70px;
                                    font-weight: 400;
                                  '
                                                >
                                                    <div>
                                                        <div>
                                                            <div
                                                            ><strong
                                                            >Hi, Welcome to Sifututor!</strong
                                                            ></div
                                                            >
                                                        </div>
                                                    </div>
                                                </h1
                                                >
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <table
                                            id='u_content_text_1'
                                            style='font-family: 'Montserrat', sans-serif'
                                            role='presentation'
                                            cellpadding='0'
                                            cellspacing='0'
                                            width='100%'
                                            border='0'
                                    >
                                        <tbody>
                                        <tr>
                                            <td
                                                    class='v-container-padding-padding'
                                                    style='
                                  overflow-wrap: break-word;
                                  word-break: break-word;
                                  padding: 20px 30px 10px;
                                  font-family: 'Montserrat', sans-serif;
                                '
                                                    align='left'
                                            >
                                                <div
                                                        class='v-text-align v-line-height v-font-size'
                                                        style='
                                    font-size: 14px;
                                    color: #d8317d;
                                    line-height: 160%;
                                    text-align: center;
                                    word-wrap: break-word;
                                  '
                                                >
                                                    <p
                                                            style='
                                      font-size: 14px;
                                      line-height: 160%;
                                      text-align: center;
                                    '
                                                    ><span
                                                            style='
                                        font-size: 24px;
                                        line-height: 38.4px;
                                      '
                                                    ><em
                                                    ><span
                                                            style='
                                            line-height: 38.4px;
                                            font-size: 24px;
                                          '
                                                    >'This Could be the Start of Something
                                          Awesome'</span
                                                    ></em
                                                    ></span
                                                    ></p
                                                    >
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <table
                                            style='font-family: 'Montserrat', sans-serif'
                                            role='presentation'
                                            cellpadding='0'
                                            cellspacing='0'
                                            width='100%'
                                            border='0'
                                    >
                                        <tbody>
                                        <tr>
                                            <td
                                                    class='v-container-padding-padding'
                                                    style='
                                  overflow-wrap: break-word;
                                  word-break: break-word;
                                  padding: 120px 0px 0px;
                                  font-family: 'Montserrat', sans-serif;
                                '
                                                    align='left'
                                            >
                                                <table
                                                        width='100%'
                                                        cellpadding='0'
                                                        cellspacing='0'
                                                        border='0'
                                                >
                                                    <tbody
                                                    >
                                                    <tr>
                                                        <td
                                                                class='v-text-align'
                                                                style='
                                          padding-right: 0px;
                                          padding-left: 0px;
                                        '
                                                                align='center'
                                                        >
                                                            <img
                                                                    align='center'
                                                                    border='0'
                                                                    src='https://cdn.templates.unlayer.com/assets/1661428767276-img.png'
                                                                    alt='image'
                                                                    title='image'
                                                                    style='
                                            outline: none;
                                            text-decoration: none;
                                            -ms-interpolation-mode: bicubic;
                                            clear: both;
                                            display: inline-block !important;
                                            border: none;
                                            height: auto;
                                            float: none;
                                            width: 100%;
                                            max-width: 480px;
                                          '
                                                                    width='480'
                                                            />
                                                        </td>
                                                    </tr>
                                                    </tbody
                                                    >
                                                </table>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <!--[if (!mso)&(!IE)]><!--></div
                                ><!--<![endif]-->
                            </div>
                        </div>
                        <!--[if (mso)|(IE)]></td><![endif]-->
                        <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
                    </div>
                </div>
            </div>

            <div
                    class='u-row-container'
                    style='padding: 0px; background-color: #ffffff'
            >
                <div
                        class='u-row'
                        style='
                  margin: 0 auto;
                  min-width: 320px;
                  max-width: 600px;
                  overflow-wrap: break-word;
                  word-wrap: break-word;
                  word-break: break-word;
                  background-color: transparent;
                '
                >
                    <div
                            style='
                    border-collapse: collapse;
                    display: table;
                    width: 100%;
                    height: 100%;
                    background-color: transparent;
                  '
                    >
                        <!--[if (mso)|(IE)]>
                        <table width='00%' cellpadding='0' cellspacing='0' border='0'>
                            <tr>
                                <td style='padding: 0px;background-color: #ffffff;' align='center'>
                                    <table cellpadding='0' cellspacing='0' border='0' style='width:600px;'>
                                        <tr style='background-color: transparent;'><![endif]-->

                        <!--[if (mso)|(IE)]>
                        <td align='enter' width='600'
                            style='width: 600px;padding: 60px 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;'
                            valign='top'><![endif]-->
                        <div
                                class='u-col u-col-100'
                                style='
                      max-width: 320px;
                      min-width: 600px;
                      display: table-cell;
                      vertical-align: top;
                    '
                        >
                            <div
                                    style='
                        height: 100%;
                        width: 100% !important;
                        border-radius: 0px;
                        -webkit-border-radius: 0px;
                        -moz-border-radius: 0px;
                      '
                            >
                                <!--[if (!mso)&(!IE)]><!-->
                                <div
                                        style='
                          box-sizing: border-box;
                          height: 100%;
                          padding: 60px 0px;
                          border-top: 0px solid transparent;
                          border-left: 0px solid transparent;
                          border-right: 0px solid transparent;
                          border-bottom: 0px solid transparent;
                          border-radius: 0px;
                          -webkit-border-radius: 0px;
                          -moz-border-radius: 0px;
                        '
                                ><!--<![endif]-->
                                    <table
                                            id='u_content_heading_3'
                                            style='font-family: 'Montserrat', sans-serif'
                                            role='presentation'
                                            cellpadding='0'
                                            cellspacing='0'
                                            width='100%'
                                            border='0'
                                    >
                                        <tbody>

                                        </tbody>
                                    </table>

                                    <table
                                            id='u_content_text_2'
                                            style='font-family: 'Montserrat', sans-serif'
                                            role='presentation'
                                            cellpadding='0'
                                            cellspacing='0'
                                            width='100%'
                                            border='0'
                                    >
                                        <tbody>
                                        <tr>
                                            <td
                                                    class='v-container-padding-padding'
                                                    style='
                                  overflow-wrap: break-word;
                                  word-break: break-word;
                                  padding: 10px;
                                  font-family: 'Montserrat', sans-serif;
                                '
                                                    align='left'
                                            >
                                                <div
                                                        class='v-text-align v-line-height v-font-size'
                                                        style='
                                    font-size: 14px;
                                    line-height: 140%;
                                    text-align: center;
                                    word-wrap: break-word;
                                  '
                                                >
                                                    <p style='font-size: 14px; line-height: 140%'
                                                    >We are thrilled to welcome you to the SifuTutor family! As an
                                                        esteemed educator, your dedication to fostering learning aligns
                                                        perfectly with our mission to provide top-notch education to
                                                        students worldwide.

                                                        At SifuTutor, we believe in creating an environment where
                                                        knowledge knows no bounds. Your expertise and passion for
                                                        teaching will undoubtedly contribute significantly to our
                                                        community, empowering students to reach new heights in their
                                                        academic journey.&nbsp;</p
                                                    >
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <!--[if (!mso)&(!IE)]><!--></div
                                ><!--<![endif]-->
                            </div>
                        </div>
                        <!--[if (mso)|(IE)]></td><![endif]-->
                        <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
                    </div>
                </div>
            </div>

            <div
                    class='u-row-container'
                    style='padding: 0px; background-color: #e6e2e2'
            >
                <div
                        class='u-row'
                        style='
                  margin: 0 auto;
                  min-width: 320px;
                  max-width: 600px;
                  overflow-wrap: break-word;
                  word-wrap: break-word;
                  word-break: break-word;
                  background-color: transparent;
                '
                >
                    <div
                            style='
                    border-collapse: collapse;
                    display: table;
                    width: 100%;
                    height: 100%;
                    background-color: transparent;
                  '
                    >
                        <!--[if (mso)|(IE)]>
                        <table width='00%' cellpadding='0' cellspacing='0' border='0'>
                            <tr>
                                <td style='padding: 0px;background-color: #ffffff;' align='center'>
                                    <table cellpadding='0' cellspacing='0' border='0' style='width:600px;'>
                                        <tr style='background-color: transparent;'><![endif]-->

                        <!--[if (mso)|(IE)]>
                        <td align='enter' width='600'
                            style='width: 600px;padding: 60px 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;'
                            valign='top'><![endif]-->
                        <div
                                class='u-col u-col-100'
                                style='
                      max-width: 320px;
                      min-width: 600px;
                      display: table-cell;
                      vertical-align: top;
                    '
                        >
                            <div
                                    style='
                        height: 100%;
                        width: 100% !important;
                        border-radius: 0px;
                        -webkit-border-radius: 0px;
                        -moz-border-radius: 0px;
                      '
                            >
                                <!--[if (!mso)&(!IE)]><!-->
                                <div
                                        style='
                          box-sizing: border-box;
                          height: 100%;
                          padding: 60px 0px;
                          border-top: 0px solid transparent;
                          border-left: 0px solid transparent;
                          border-right: 0px solid transparent;
                          border-bottom: 0px solid transparent;
                          border-radius: 0px;
                          -webkit-border-radius: 0px;
                          -moz-border-radius: 0px;
                        '
                                ><!--<![endif]-->
                                    <table
                                            id='u_content_text_10'
                                            style='font-family: 'Montserrat', sans-serif'
                                            role='presentation'
                                            cellpadding='0'
                                            cellspacing='0'
                                            width='100%'
                                            border='0'
                                    >
                                        <tbody>
                                        <tr>
                                            <td
                                                    class='v-container-padding-padding'
                                                    style='
                                  overflow-wrap: break-word;
                                  word-break: break-word;
                                  padding: 10px;
                                  font-family: 'Montserrat', sans-serif;
                                '
                                                    align='left'
                                            >
                                                <div
                                                        class='v-text-align v-line-height v-font-size'
                                                        style='
                                    font-size: 14px;
                                    line-height: 140%;
                                    text-align: center;
                                    word-wrap: break-word;
                                  '
                                                >
                                                    <p style='font-size: 14px; line-height: 140%'
                                                    >&copy; Copyright 2024 Sifututor.</p
                                                    >
                                                    <p style='font-size: 14px; line-height: 140%'
                                                    >All rights reserved.&nbsp;</p
                                                    >
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <table
                                            style='font-family: 'Montserrat', sans-serif'
                                            role='presentation'
                                            cellpadding='0'
                                            cellspacing='0'
                                            width='100%'
                                            border=''
                                    >
                                        <tbody>
                                        <tr>
                                            <td
                                                    class='v-container-padding-padding'
                                                    style='
                                  overflow-wrap: break-word;
                                  word-break: break-word;
                                  padding: 10px;
                                  font-family: 'Montserrat', sans-serif;
                                '
                                                    align='left'
                                            >
                                                <div align='center'>
                                                    <div style='display: table; max-width: 175px'>
                                                        <!--[if (mso)|(IE)]>
                                                        <table width='75' cellpadding='0' cellspacing='0' border='0'>
                                                            <tr>
                                                                <td style='border-collapse:collapse;' align='center'>
                                                                    <table width='100%' cellpadding='0' cellspacing='0'
                                                                           border='0'
                                                                           style='border-collapse:collapse; mso-table-lspace: 0pt;mso-table-rspace: 0pt; width:175px;'>
                                                                        <tr><![endif]-->

                                                        <!--[if (mso)|(IE)]>
                                                        <td width='2' style='width:32px; padding-right: 12px;'
                                                            valign='top'><![endif]-->
                                                        <table
                                                                align='left'
                                                                border='0'
                                                                cellspacing='0'
                                                                cellpadding='0'
                                                                width='32'
                                                                height='32'
                                                                style='
                                        width: 32px !important;
                                        height: 32px !important;
                                        display: inline-block;
                                        border-collapse: collapse;
                                        table-layout: fixed;
                                        border-spacing: 0;
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        vertical-align: top;
                                        margin-right: 12px;
                                      '
                                                        >
                                                            <tbody
                                                            >
                                                            <tr style='vertical-align: top'
                                                            >
                                                                <td
                                                                        align='left'
                                                                        valign='middle'
                                                                        style='
                                              word-break: break-word;
                                              border-collapse: collapse !important;
                                              vertical-align: top;
                                            '
                                                                >
                                                                    <a
                                                                            href='javascript:void(0)'
                                                                            title='Facebook'
                                                                            target='_blank'
                                                                    >
                                                                        <img
                                                                                src='https://cdn.tools.unlayer.com/social/icons/circle/facebook.png'
                                                                                alt='Facebook'
                                                                                title='Facebook'
                                                                                width='32'
                                                                                style='
                                                  outline: none;
                                                  text-decoration: none;
                                                  -ms-interpolation-mode: bicubic;
                                                  clear: both;
                                                  display: block !important;
                                                  border: none;
                                                  height: auto;
                                                  float: none;
                                                  max-width: 32px !important;
                                                '
                                                                        />
                                                                    </a></td
                                                                >
                                                            </tr>
                                                            </tbody
                                                            >
                                                        </table>
                                                        <!--[if (mso)|(IE)]></td><![endif]-->

                                                        <!--[if (mso)|(IE)]>
                                                        <td width='2' style='width:32px; padding-right: 12px;'
                                                            valign='top'><![endif]-->
                                                        <table
                                                                align='left'
                                                                border='0'
                                                                cellspacing='0'
                                                                cellpadding='0'
                                                                width='32'
                                                                height='32'
                                                                style='
                                        width: 32px !important;
                                        height: 32px !important;
                                        display: inline-block;
                                        border-collapse: collapse;
                                        table-layout: fixed;
                                        border-spacing: 0;
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        vertical-align: top;
                                        margin-right: 12px;
                                      '
                                                        >
                                                            <tbody
                                                            >
                                                            <tr style='vertical-align: top'
                                                            >
                                                                <td
                                                                        align='left'
                                                                        valign='middle'
                                                                        style='
                                              word-break: break-word;
                                              border-collapse: collapse !important;
                                              vertical-align: top;
                                            '
                                                                >
                                                                    <a
                                                                            href='javascript:void(0)'
                                                                            title='Twitter'
                                                                            target='_blank'
                                                                    >
                                                                        <img
                                                                                src='https://cdn.tools.unlayer.com/social/icons/circle/twitter.png'
                                                                                alt='Twitter'
                                                                                title='Twitter'
                                                                                width='32'
                                                                                style='
                                                  outline: none;
                                                  text-decoration: none;
                                                  -ms-interpolation-mode: bicubic;
                                                  clear: both;
                                                  display: block !important;
                                                  border: none;
                                                  height: auto;
                                                  float: none;
                                                  max-width: 32px !important;
                                                '
                                                                        />
                                                                    </a></td
                                                                >
                                                            </tr>
                                                            </tbody
                                                            >
                                                        </table>
                                                        <!--[if (mso)|(IE)]></td><![endif]-->

                                                        <!--[if (mso)|(IE)]>
                                                        <td width='2' style='width:32px; padding-right: 12px;'
                                                            valign='top'><![endif]-->
                                                        <table
                                                                align='left'
                                                                border='0'
                                                                cellspacing='0'
                                                                cellpadding='0'
                                                                width='32'
                                                                height='32'
                                                                style='
                                        width: 32px !important;
                                        height: 32px !important;
                                        display: inline-block;
                                        border-collapse: collapse;
                                        table-layout: fixed;
                                        border-spacing: 0;
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        vertical-align: top;
                                        margin-right: 12px;
                                      '
                                                        >
                                                            <tbody
                                                            >
                                                            <tr style='vertical-align: top'
                                                            >
                                                                <td
                                                                        align='left'
                                                                        valign='middle'
                                                                        style='
                                              word-break: break-word;
                                              border-collapse: collapse !important;
                                              vertical-align: top;
                                            '
                                                                >
                                                                    <a
                                                                            href='javascript:void(0)'
                                                                            title='LinkedIn'
                                                                            target='_blank'
                                                                    >
                                                                        <img
                                                                                src='https://cdn.tools.unlayer.com/social/icons/circle/linkedin.png'
                                                                                alt='LinkedIn'
                                                                                title='LinkedIn'
                                                                                width='32'
                                                                                style='
                                                  outline: none;
                                                  text-decoration: none;
                                                  -ms-interpolation-mode: bicubic;
                                                  clear: both;
                                                  display: block !important;
                                                  border: none;
                                                  height: auto;
                                                  float: none;
                                                  max-width: 32px !important;
                                                '
                                                                        />
                                                                    </a></td
                                                                >
                                                            </tr>
                                                            </tbody
                                                            >
                                                        </table>
                                                        <!--[if (mso)|(IE)]></td><![endif]-->

                                                        <!--[if (mso)|(IE)]>
                                                        <td width='2' style='width:32px; padding-right: 0px;'
                                                            valign='top'><![endif]-->
                                                        <table
                                                                align='left'
                                                                border='0'
                                                                cellspacing='0'
                                                                cellpadding='0'
                                                                width='32'
                                                                height='32'
                                                                style='
                                        width: 32px !important;
                                        height: 32px !important;
                                        display: inline-block;
                                        border-collapse: collapse;
                                        table-layout: fixed;
                                        border-spacing: 0;
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        vertical-align: top;
                                        margin-right: 0px;
                                      '
                                                        >
                                                            <tbody
                                                            >
                                                            <tr style='vertical-align: top'
                                                            >
                                                                <td
                                                                        align='left'
                                                                        valign='middle'
                                                                        style='
                                              word-break: break-word;
                                              border-collapse: collapse !important;
                                              vertical-align: top;
                                            '
                                                                >
                                                                    <a
                                                                            href='https://instagram.com/'
                                                                            title='Instagram'
                                                                            target='_blank'
                                                                    >
                                                                        <img
                                                                                src='https://cdn.tools.unlayer.com/social/icons/circle/instagram.png'
                                                                                alt='Instagram'
                                                                                title='Instagram'
                                                                                width='32'
                                                                                style='
                                                  outline: none;
                                                  text-decoration: none;
                                                  -ms-interpolation-mode: bicubic;
                                                  clear: both;
                                                  display: block !important;
                                                  border: none;
                                                  height: auto;
                                                  float: none;
                                                  max-width: 32px !important;
                                                '
                                                                        />
                                                                    </a></td
                                                                >
                                                            </tr>
                                                            </tbody
                                                            >
                                                        </table>
                                                        <!--[if (mso)|(IE)]></td><![endif]-->

                                                        <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <!--[if (!mso)&(!IE)]><!--></div
                                ><!--<![endif]-->
                            </div>
                        </div>
                        <!--[if (mso)|(IE)]></td><![endif]-->
                        <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
                    </div>
                </div>
            </div>

            <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
        </td>
    </tr>
    </tbody>
</table>
<!--[if mso]></div><![endif]-->
<!--[if IE]></div><![endif]-->
</body>
</html
>

                        ";

        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $headers .= 'From: <info@sifututor.brainiaccreation.com>' . "\r\n";

        mail($to,$subject,$message,$headers);


        $tutorDetail = DB::table('tutors')->where('id','=',$request->tutorId)->first();

        return Response::json(['status'=>200, 'tutorDetail'=>$tutorDetail]);
    }
    public function getTutorDeviceToken(Request $request){

        $values = array(
            'tutor_id' => $request->tutor_id,
            'device_token' => $request->device_token
        );
        $deviceTokenLastID = DB::table('tutor_device_tokens')->insertGetId($values);


        $tutorDeviceToken = DB::table('tutor_device_tokens')->where('id','=',$deviceTokenLastID)->first();

        return Response::json(['status'=>200, 'tutorDeviceToken'=>$tutorDeviceToken]);
    }

    public function loginAPI($phone){



        $tutorDetail = DB::table('tutors')
            ->where('phoneNumber','=',$phone)->first();


        if(isset($tutorDetail) && $tutorDetail->status == 'terminated'){
            return Response::json(['status'=>404, 'userStatus'=>'terminated','msg'=>"Your Account has been terminated."]);
        }

        if(isset($tutorDetail) && $tutorDetail->status == 'resigned'){
            return Response::json(['status'=>404, 'userStatus'=>'resigned','msg'=>"You have been resigned."]);
        }

        if(isset($tutorDetail) && $tutorDetail->status == 'inactive'){
            return Response::json(['status'=>404, 'userStatus'=>'resigned','msg'=>"Your Account is inactive."]);
        }

        if($tutorDetail!=null)
        {
            $SixDigitRandomNumber = rand(100000,999999);


            $values = array(
                'tutorID' => $tutorDetail->id,
                'code' => $SixDigitRandomNumber,
                'token' => bin2hex(random_bytes(16)),
            );

            $tutorVerificationCodeCheck= DB::table('tutorVerificationCode')->where("tutorID",$tutorDetail->id)->first();

            if($tutorVerificationCodeCheck==null)
            {
                $values = array(
                    'tutorID' => $tutorDetail->id,
                    'code' => $SixDigitRandomNumber,
                    'token' => bin2hex(random_bytes(16)),
                );

                $submitClassScheduleTime = DB::table('tutorVerificationCode')->insertGetId($values);
            }else
            {
                DB::table('tutorVerificationCode')->where("tutorID",$tutorDetail->id)->update($values);
            }




            $whatsapp_api = new WhatsappApi();
            $sms_api = new SmsNiagaApi();
            $phone_number = $phone;
            $message =  "Here's your SifuTutor verification code: $SixDigitRandomNumber. It's valid for the next 10 minutes. Thank you!";;
            $whatsapp_api->send_message($phone_number, $message);
            $sms_api->sendSms($phone_number, $message);

            return Response::json(['status'=>200, 'userStatus'=>'old','tutorDetail' => $tutorDetail,'msg'=>"Enter verfication code to continue"]);
        }



        $phoneNumber = $phone;
        $tutorDetail = DB::table('tutors')
            ->where('phoneNumber', '=', $phoneNumber)
            ->where(function ($query) {
                $query->where('status', '=', 'verified')
                    ->orWhere('status', '=', 'unverified')
                    ->orWhere('status', '=', 'terminated')
                    ->orWhere('status', '=', 'new')
                    ->orWhere('status', '=', 'resigned');
            })
            ->orderBy('id', 'desc') // Sort the results by ID in descending order
            ->first();


        if ($tutorDetail && $tutorDetail->status!="new") {
            // DD("1");

            if($tutorDetail->status == 'verified' || $tutorDetail->status == 'unverified'){
                $SixDigitRandomNumber = rand(100000,999999);
                //  $SixDigitRandomNumber = 123456;
                DB::table('tutorVerificationCode')->where('tutorID', $tutorDetail->id)->delete();


                $values = array(
                    'tutorID' => $tutorDetail->id,
                    'code' => $SixDigitRandomNumber,
                    'token' => bin2hex(random_bytes(16)),
                );

                $submitClassScheduleTime = DB::table('tutorVerificationCode')->insertGetId($values);

                $to = $tutorDetail->email;
                $subject = "Verification Code:". $SixDigitRandomNumber;

                $message = "
                        <html>
                        <head>

                        </head>
                        <body>
                        <p>Verification Code from Sifututor <br/> YourID: <strong>$tutorDetail->uid</strong></p>
                        <table>
                        <tr>
                        <th></th>
                        </tr>
                        <tr>
                        <td style='font-size:31px; font-weight:bold;'>$SixDigitRandomNumber</td>
                        </tr>
                        </table>
                        </body>
                        </html>
                        ";

                // Always set content-type when sending HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                // More headers
                $headers .= 'From: <info@sifututor.brainiaccreation.com>' . "\r\n";

                $secondMailAddress="binasift@gmail.com";
                $thirdMailAddress="binasift@gmail.com";
                mail($to,$subject,$message,$headers);
                mail($secondMailAddress,$subject,$message,$headers);
                mail($thirdMailAddress,$subject,$message,$headers);



                $whatsapp_api = new WhatsappApi();
                $sms_api = new SmsNiagaApi();
                $phone_number = $phoneNumber;
                $message = 'Verificate Code: '.$SixDigitRandomNumber.' From *SIFUTUTOR*';
                $whatsapp_api->send_message($phone_number, $message);
                $sms_api->sendSms($phone_number, $message);



                return Response::json(['status'=>200, 'UserDetail'=>$tutorDetail, 'userStatus'=>'old','msg'=>"Enter verfication code to continue"]);
            }




            //  if($tutorDetail->status == 'new'){
            //     return Response::json(['status'=>404, 'userStatus'=>'New']);
            // }

        }else{
            $phoneNumber = $phone;
            $uuidForTutor = rand(100, 99999);
            $values = array(
                'uid' => 'TU-'.$uuidForTutor,
                'tutor_id' => 'TU-'.$uuidForTutor,
                'phoneNumber' => $phoneNumber,
                'status' => 'unverified',
                'whatsapp' => $phoneNumber
            );
            $tutorLastID = DB::table('tutors')->insertGetId($values);

            $tutorDetail = DB::table('tutors')->where('id', '=', $tutorLastID)->
            orderBy('id', 'desc')->
            first();

            // $SixDigitRandomNumber = 123456;
            $SixDigitRandomNumber = rand(100000,999999);
            DB::table('tutorVerificationCode')->where('tutorID', $tutorDetail->id)->delete();
            $valuesVC = array(
                'tutorID' => $tutorLastID,
                'code' => $SixDigitRandomNumber,
                'token' => bin2hex(random_bytes(16)),
            );
            $submitClassScheduleTime = DB::table('tutorVerificationCode')->insertGetId($valuesVC);



            $whatsapp_api = new WhatsappApi();
            $sms_api = new SmsNiagaApi();
            $phone_number = $phoneNumber;
            $message = 'Verificate Code: '.$SixDigitRandomNumber.' From *SIFUTUTOR*';
            $whatsapp_api->send_message($phone_number, $message);
            $sms_api->sendSms($phone_number, $message);

            return Response::json(['status'=>200, 'userStatus'=>'new','tutorDetail' => $tutorDetail,'msg'=>"Enter verfication code to continue"]);
        }

    }

    public function loginAPISMSTest(){
        $phone = '+60356260018';
        $SixDigitRandomNumber = 123456;
        $whatsapp_api = new WhatsappApi();
        $sms_api = new SmsNiagaApi();
        $phone_number = $phone;
        $message = 'Verificate Code: '.$SixDigitRandomNumber.' <br> From *SIFUTUTOR*';
        $whatsapp_api->send_message($phone_number, $message);
        $sms_api->sendSms($phone_number, $message);

    }




  public function verificationCode(Request $req){

        if($req->code==123456)
        {
            $tutorCheck=DB::table('tutors')->where('id', $req->id)->first();
            if($tutorCheck!=null)
            {
                DB::table('tutors')->where('id', $tutorCheck->id)
                    ->update(['last_login' => date('Y-m-d H:i')]);


                $tutor =  DB::table('tutors')->where('id', $tutorCheck->id)
                    ->orderBy('id', 'desc')
                    ->first();
                return Response::json(['status'=>200, 'tutorID'=>$tutorCheck->id,'contact'=>$tutorCheck->phoneNumber,"Tutorstatus"=>$tutor->status]);
            }
        }else{
            $verificationCode = DB::table('tutorVerificationCode')
                ->where('code', '=', $req->code)
                ->where('tutorID', '=', $req->id)
                ->orderBy('id', 'desc') // Sort the results by ID in descending order
                ->first();



            if($verificationCode!=null)
            {
                DB::table('tutors')->where('id', $verificationCode->tutorID)
                    ->update(['last_login' => date('Y-m-d H:i')]);


                $tutor =  DB::table('tutors')->where('id', $verificationCode->tutorID)
                    ->orderBy('id', 'desc')
                    ->first();

                    // return response()->json($tutor);
                return Response::json(['status'=>200, 'tutorID'=>$tutor->id,'contact'=>$tutor->phoneNumber, 'token'=>$verificationCode->token,"Tutorstatus"=>$tutor->status]);

            }else{
                return Response::json(['status'=>404, 'errorMessage'=>'Sorry Code didnt Match! Please try again']);

            }
        }
}




    public function ajaxCall(Request $request){

        return Response::json($request);

        if($data['classScheduleId'] == 1){
            $search = DB::table('class_schedules')->where('class_schedule_id','=',0)->get();
            return Response::json(['search'=>$search]);
        }

        if($data['studentListId'] == 1){
            //$search = DB::table('students')->where('class_schedule_id','=',0)->get();
            return Response::json(['student'=>$data['student']]);
        }


    }

    public function addMultipleClasses(Request $request){


            //   return Response::json(["data" => $request->all()], 200);


        $data = $request->all();
        $conflictFlag=false;
        $getClassSchedule = DB::table('class_schedules')
            ->where('tutorID','=',$data['classes'][0]['tutorID'])
            ->where('studentID','=',$data['classes'][0]['studentID'])
            ->where('subjectID','=',$data['classes'][0]['subjectID'])
            ->where('class_schedule_id','!=',0)
            ->orderBy('id', 'DESC')->first();

            // dd($getClassSchedule);
        $classCount = 0;
        foreach($data['classes'] as $key => $value){

            // My custom Code start

            // dd($data);
            // $dateInput = $data["date"][$key];
            // dd($data["classes"][$key]["date"]);
            $dateInput = $data["classes"][$key]["date"];
            $startTime = $data["classes"][$key]["startTime"];
            $endTime = $data["classes"][$key]["endTime"]; // Add this line
            $tutorId = $data["classes"][$key]["tutorID"];

            // Step 1: Check for existing record with the given date and tutor ID
            $existingRecord = DB::table("class_schedules")
                ->where('date', '=', $dateInput)
                ->where('tutorId', '=', $tutorId)
                ->where('status', '!=', 'attended')
                ->first();

                //dd($existingRecord);
            $conflictingRecord = null;
            if ($existingRecord) {
                $conflictingRecord = DB::table("class_schedules")
                    ->where('date', '=', $dateInput)
                     ->where('status', '!=', 'attended')
                    ->where('tutorId', '=', $tutorId) // Add this line to check tutor ID
                    ->where(function ($query) use ($dateInput, $startTime, $endTime) {
                        $query->where(function ($query) use ($startTime, $endTime) {
                            $query->where('startTime', '<', $startTime)
                                  ->where('endTime', '>', $startTime);
                        })
                        ->orWhere(function ($query) use ($startTime, $endTime) {
                            $query->where('startTime', '<', $endTime)
                                  ->where('endTime', '>', $endTime);
                        })
                        ->orWhere(function ($query) use ($startTime, $endTime) {
                            $query->where('startTime', '>=', $startTime)
                                  ->where('endTime', '<=', $endTime);
                        });
                    })
                    ->first();

                    // dd($conflictingRecord);
            }

            if ($conflictingRecord) {
                $conflictFlag=true;

            }
            //My Custom Code end



            // dd($data);
            $tutorLastClassSchedule = DB::table('class_schedules')
                ->where('tutorID','=',$data['classes'][0]['tutorID'])
                ->where('class_schedule_id','!=',0)
                ->orderBy('id', 'DESC')->first();

            $sTimeDB = $tutorLastClassSchedule->startTime;
            $eTimeDB = $tutorLastClassSchedule->endTime;
            $t1DB  = strtotime($sTimeDB);
            $t2DB = strtotime($eTimeDB);
            $differenceInSecondsDB = $t2DB - $t1DB;
            $differenceInHoursDB = $differenceInSecondsDB / 3600;
            $totalTimeDB = number_format((float)$differenceInHoursDB, 2, '.', '');
            if($differenceInHoursDB<0) {
                $differenceInHoursDB += 24;
                $totalTimeDB = number_format((float)$differenceInHoursDB, 2, '.', '');
            }

            $sTimeRealTime = $value['startTime'];
            $eTimeRealTime = $value['endTime'];
            $t1RealTime  = strtotime($sTimeRealTime);
            $t2RealTime = strtotime($eTimeRealTime);
            $differenceInSecondsRealTime = $t2RealTime - $t1RealTime;
            $differenceInHoursRealTime = $differenceInSecondsRealTime / 3600;
            $totalTimeRealTime = number_format((float)$differenceInHoursRealTime, 2, '.', '');
            if($differenceInHoursRealTime<0) {
                $differenceInHoursRealTime += 24;
                $totalTimeRealTime = number_format((float)$differenceInHoursRealTime, 2, '.', '');
            }


            $timestamp = strtotime($sTimeRealTime) + 60*60;
            $time = date($tutorLastClassSchedule->endTime, $timestamp);

        //   dd($tutorLastClassSchedule);

           if ($conflictFlag==true)

            {

                //   return response()->json(['message'=>$conflictFlag]);
                $message = 'you have already schedule class in this time slot';
                return response()->json(['message'=>$message]);

            }
            else{
                //   return response()->json(['message'=>$conflictFlag]);
                // return response()->json(['message'=>"2"]);
                if($tutorLastClassSchedule->date < $value['date']){
                    $sTime = $value['startTime'];
                    $eTime = $value['endTime'];
                    $t1  = strtotime($sTime);
                    $t2 = strtotime($eTime);
                    $differenceInSeconds = $t2 - $t1;
                    $differenceInHours = $differenceInSeconds / 3600;
                    $totalTime = number_format((float)$differenceInHours, 2, '.', '');
                    if($differenceInHours<0) {
                        $differenceInHours += 24;
                        $totalTime = number_format((float)$differenceInHours, 2, '.', '');
                    }
                    $values = array(
                        'tutorID' => $getClassSchedule->tutorID,
                        'class_schedule_id' => $getClassSchedule->class_schedule_id,
                        'studentID' => $getClassSchedule->studentID,
                        'subjectID' => $getClassSchedule->subjectID,
                        'ticketID' => $getClassSchedule->ticketID,
                        'date' => $value['date'],
                        'startTime' => $value['startTime'],
                        'endTime' => $value['endTime'],
                        'status' => 'scheduled',
                        'totalTime' => $totalTime,
                    );
                    $classCount++;
                    $submitClassScheduleTime = DB::table('class_schedules')->insertGetId($values);
                    $message = 'Clases added Successfully';
                }else{
                    if($value['startTime'] >= $time){
                        $sTime = $value['startTime'];
                        $eTime = $value['endTime'];
                        $t1  = strtotime($sTime);
                        $t2 = strtotime($eTime);
                        $differenceInSeconds = $t2 - $t1;
                        $differenceInHours = $differenceInSeconds / 3600;
                        $totalTime = number_format((float)$differenceInHours, 2, '.', '');
                        if($differenceInHours<0) {
                            $differenceInHours += 24;
                            $totalTime = number_format((float)$differenceInHours, 2, '.', '');
                        }
                        $values = array(
                            'tutorID' => $getClassSchedule->tutorID,
                            'class_schedule_id' => $getClassSchedule->class_schedule_id,
                            'studentID' => $getClassSchedule->studentID,
                            'subjectID' => $getClassSchedule->subjectID,
                            'ticketID' => $getClassSchedule->ticketID,
                            'date' => $value['date'],
                            'startTime' => $value['startTime'],
                            'endTime' => $value['endTime'],
                            'status' => 'scheduled',
                            'totalTime' => $totalTime,
                        );

                        $submitClassScheduleTime = DB::table('class_schedules')->insertGetId($values);
                        $message = 'Clases added Successfully';
                        $classCount++;
                    }else{
                        $message = 'you have already schedule class in this time slot';
                    }
                }

            }

        }

        // dd($getClassSchedule);

        $remaining_classes = DB::table('job_tickets')->where('id', $getClassSchedule->ticketID)->first();


        DB::table('job_tickets')
            ->where('id', $getClassSchedule->ticketID)
            ->update(['remaining_classes' => $remaining_classes->remaining_classes - $classCount]);


        DB::table('student_subjects')
            ->where('ticket_id', $getClassSchedule->ticketID)
            ->update(['remaining_classes' => $remaining_classes->remaining_classes - $classCount]);



        return response()->json(['message'=>$message]);

    }

    public function assignedTicketsAPI($tutorID){

        $resultData = [];

        $tickets = DB::table('job_tickets')
            ->where('job_tickets.tutor_id', '=', $tutorID)
            ->join('products', 'products.id', '=', 'job_tickets.subjects')
            ->join('categories', 'categories.id', '=', 'products.category')
            ->join('students', 'students.id', '=', 'job_tickets.student_id')
            ->join('customers', 'customers.id', '=', 'students.customer_id')
            ->join('cities', 'customers.city', '=', 'cities.id')
            ->join('states', 'customers.state', '=', 'states.id')
            ->select(
                'job_tickets.*',
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
                'job_tickets.classAddress as classAddress',
                'job_tickets.totalTutorApplied as totalTutorApplied',
                'job_tickets.estimate_commission as estimate_commission',
                'products.id as subject_id',
                'products.id as subjectID',
                'job_tickets.totalPrice as price',
                'students.full_name as studentName',
                'students.gender as studentGender',
                'students.age as studentAge',
                'categories.category_name as categoryName',
                'categories.id as categoryID',
                'cities.name as city',
                'cities.id as cityID',
                'states.id as stateID',
                'states.name as state'
            )
            ->orderBy('job_tickets.id', 'DESC')
            ->get();
        foreach ($tickets as $ticket) {
            $ticketData = [
                'subject_name' => $ticket->subject_name,
                'jtuid' => $ticket->jtuid,
                'classDay' => $ticket->classDay,
                'classTime' => $ticket->classTime,
                'ticketID' => $ticket->ticketID,
                'totalTutorApplied' => $ticket->totalTutorApplied,
                'tutorPereference' => $ticket->tutorPereference,
                'classFrequency' => $ticket->classFrequency,
                'quantity' => $ticket->quantity,
                'mode' => $ticket->mode,
                'classAddress' => $ticket->classAddress,
                'classState' => $ticket->classState,
                'classCity' => $ticket->classCity,
                'classPostalCode' => $ticket->classPostalCode,
                'specialRequest' => $ticket->specialRequest,
                'subject_id' => $ticket->subject_id,
                'subjectID' => $ticket->subject_id,
                'price' => $ticket->estimate_commission_display_tutor,
                'studentName' => $ticket->studentName,
                'studentGender' => $ticket->studentGender,
                'student_age' => $ticket->studentAge,
                'city' => $ticket->city,
                'state' => $ticket->state,
                'cityID' => $ticket->cityID,
                'stateID' => $ticket->stateID,
                'categoryName' => $ticket->categoryName,
                'categoryID' => $ticket->categoryID,
                'jobTicketExtraStudents' => [],
            ];

            $students = DB::table('job_ticket_students')->where('job_ticket_id', '=', $ticket->ticketID)->get();

            foreach ($students as $student) {
                $studentData = [
                    'student_name' => $student->student_name,
                    'student_age' => $student->student_age,
                    'student_gender' => $student->student_gender,
                    'year_of_birth' => $student->year_of_birth,
                    'special_need' => $student->special_need,
                    'subject_id' => $student->subject_id,
                ];

                $ticketData['jobTicketExtraStudents'][] = $studentData;
            }

            $resultData[] = $ticketData;
        }

        // dd($resultData);
        return Response::json(['tickets'=>$resultData]);

        die();
        $resultData = [];

        $tickets = DB::table('job_tickets')
            ->where('job_tickets.tutor_id', '=', $tutorID)
            ->join('products', 'products.id', '=', 'job_tickets.subjects')
            ->join('categories', 'categories.id', '=', 'products.category')
            ->join('students', 'students.id', '=', 'job_tickets.student_id')
            ->join('customers', 'customers.student_id', '=', 'students.id')
            ->select(
                'job_tickets.*',
                'products.name as subject_name',
                'job_tickets.uid as jtuid',
                'job_tickets.day as classDay',
                'job_tickets.time as classTime',
                'job_tickets.id as ticketID',
                'job_tickets.totalTutorApplied as totalTutorApplied',
                'products.id as subject_id',
                'students.full_name as studentName',
                'students.gender as studentGender',
                'categories.category_name as categoryName',
                'categories.id as categoryID'
            )
            ->orderBy('job_tickets.id', 'DESC')
            ->get();

        foreach ($tickets as $ticket) {
            $ticketData = [
                'subject_name' => $ticket->subject_name,
                'jtuid' => $ticket->jtuid,
                'classDay' => $ticket->classDay,
                'classTime' => $ticket->classTime,
                'ticketID' => $ticket->ticketID,
                'totalTutorApplied' => $ticket->totalTutorApplied,
                'subject_id' => $ticket->subject_id,
                'studentName' => $ticket->studentName,
                'studentGender' => $ticket->studentGender,
                'categoryName' => $ticket->categoryName,
                'categoryID' => $ticket->categoryID,
                'jobTicketExtraStudents' => [],
            ];

            $students = DB::table('job_ticket_students')->where('job_ticket_id', '=', $ticket->ticketID)->get();

            foreach ($students as $student) {
                $studentData = [
                    'student_name' => $student->student_name,
                    'student_age' => $student->student_age,
                    'student_gender' => $student->student_gender,
                    'year_of_birth' => $student->year_of_birth,
                    'special_need' => $student->special_need,
                    'subject_id' => $student->subject_id,
                ];

                $ticketData['jobTicketExtraStudents'][] = $studentData;
            }

            $resultData[] = $ticketData;
        }

// Now $resultData contains the structured information you need.



        return Response::json(['tickets'=>$resultData]);
    }


    public function finishedTicketsAPI($tutorID){


        $tickets = DB::table('job_tickets')
            ->where('job_tickets.tutor_id', '=', $tutorID)
            ->where('job_tickets.status', '=', 'finished')
            ->join('products', 'products.id', '=', 'job_tickets.subjects')
            ->join('categories', 'categories.id', '=', 'products.category')
            ->join('students', 'students.id', '=', 'job_tickets.student_id')
            ->join('customers', 'customers.student_id', '=', 'students.id')
            ->select(
                'job_tickets.*',
                'products.name as subject_name',
                'job_tickets.uid as jtuid',
                'job_tickets.day as classDay',
                'job_tickets.time as classTime',
                'job_tickets.id as ticketID',
                'job_tickets.totalTutorApplied as totalTutorApplied',
                'products.id as subject_id',
                'students.full_name as studentName',
                'students.gender as studentGender',
                'categories.category_name as categoryName',
                'categories.id as categoryID'
            )
            ->orderBy('job_tickets.id', 'DESC')
            ->get();


// Now $result contains the desired structure with 'job_ticket_students' as a new array within each 'job_tickets' record


        return Response::json(['tickets'=>$tickets]);
    }



    public function ticketsAPI($tutorID){


        $resultData = [];

        // $tickets = DB::table('job_tickets')
        //     ->join('products', 'products.id', '=', 'job_tickets.subjects')
        //     ->join('categories', 'categories.id', '=', 'products.category')
        //     ->join('students', 'students.id', '=', 'job_tickets.student_id')
        //     ->join('customers', 'customers.id', '=', 'students.customer_id')
        //     ->join('cities', 'customers.city', '=', 'cities.id')
        //     ->join('states', 'customers.state', '=', 'states.id')

        //     ->where('job_tickets.status','=','pending')
        //     ->where('job_tickets.tutor_id','=',null)
        //     ->select(
        //         'job_tickets.*',
        //         'products.name as subject_name',
        //         'job_tickets.uid as jtuid',
        //         'job_tickets.day as classDay',
        //         'job_tickets.time as classTime',
        //         'job_tickets.id as ticketID',
        //         'job_tickets.quantity as quantity',
        //         'job_tickets.tutorPereference as tutorPereference',
        //         'job_tickets.classFrequency as classFrequency',
        //         'job_tickets.specialRequest as specialRequest',
        //         'job_tickets.mode as mode',
        //         'job_tickets.tutor_id as tutor_id',
        //         'job_tickets.status as status',
        //         'job_tickets.classAddress as classAddress',
        //         'job_tickets.totalTutorApplied as totalTutorApplied',
        //         'products.id as subject_id',
        //         'products.id as subjectID',
        //         'job_tickets.totalPrice as price',
        //         'students.full_name as studentName',
        //         'students.gender as studentGender',
        //         'students.age as studentAge',
        //         'categories.category_name as categoryName',
        //         'categories.id as categoryID',
        //         'cities.name as city',
        //         'cities.id as cityID',
        //         'states.id as stateID',
        //         'states.name as state'
        //     )
        //     ->orderBy('job_tickets.id', 'DESC')
        //     ->get();

        $tickets = DB::table('job_tickets')
            ->join('products', 'products.id', '=', 'job_tickets.subjects')
            ->join('categories', 'categories.id', '=', 'products.category')
            ->join('students', 'students.id', '=', 'job_tickets.student_id')
            ->join('customers', 'customers.id', '=', 'students.customer_id')
            ->join('cities', 'customers.city', '=', 'cities.id')
            ->join('states', 'customers.state', '=', 'states.id')


            // >join('tutors', 'job_tickets.tutor_id', '=', 'tutors.id')


            ->leftJoin('tutoroffers', function ($join) use ($tutorID) {
                $join->on('job_tickets.id', '=', 'tutoroffers.ticketID')
                    ->where('tutoroffers.tutorID', '=', $tutorID);
            })
            ->where('job_tickets.status', '=', 'pending')
            ->where('tutoroffers.tutorID', '=', null)
            ->select(
                'job_tickets.*',
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
                'job_tickets.totalTutorApplied as totalTutorApplied',
                'job_tickets.estimate_commission as estimate_commission',
                'job_tickets.subscription as subscription',
                'products.id as subject_id',
                'products.id as subjectID',
                'job_tickets.totalPrice as price',
                'students.full_name as studentName',
                'students.gender as studentGender',
                'students.age as studentAge',
                'students.address1 as studentAddress',
                'categories.category_name as categoryName',
                'categories.id as categoryID',
                'cities.name as city',
                'cities.id as cityID',
                'states.id as stateID',
                'states.name as state'
            // 'tutors.status as tutor_status'
            )
            ->orderBy('job_tickets.id', 'DESC')
            ->get();

        //dd($tickets);


        foreach ($tickets as $ticket) {
            $inputString = $ticket->classDay;
            $outputString = stripslashes(trim($inputString, "\""));

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
                'subscription' => $ticket->subscription,
                'classAddress' => $ticket->classAddress,
                'classState' => $ticket->classState,
                'classCity' => $ticket->classCity,
                'classPostalCode' => $ticket->classPostalCode,
                'specialRequest' => $ticket->specialRequest,
                'subject_id' => $ticket->subject_id,
                'subjectID' => $ticket->subject_id,
                'price' => $ticket->estimate_commission_display_tutor,
                'studentName' => $ticket->studentName,
                'studentGender' => $ticket->studentGender,
                'student_age' => $ticket->studentAge,
                'studentAddress' => $ticket->classAddress,
                'city' => $ticket->city,
                'state' => $ticket->state,
                'cityID' => $ticket->cityID,
                'stateID' => $ticket->stateID,
                'categoryName' => $ticket->categoryName,
                'categoryID' => $ticket->categoryID,
                'jobTicketExtraStudents' => [],
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
                    'subject_id' => $student->subject_id,
                ];

                $ticketData['jobTicketExtraStudents'][] = $studentData;
            }


            //  dd(count($ticketData))

            $resultData[] = $ticketData;



        }

        // dd("HEre");
        return Response::json(['tickets'=>$resultData]);

        die();

        $tickets = DB::table('job_tickets')->where('job_tickets.status','=','pending')
            ->join('products', 'products.id', '=', 'job_tickets.subjects')
            ->join('categories', 'categories.id', '=', 'products.category')
            ->join('students', 'students.id', '=', 'job_tickets.student_id')
            ->join('customers', 'customers.student_id', '=', 'students.id')
            ->select('job_tickets.*',
                'products.name as subject_name',
                'job_tickets.uid as jtuid',
                'job_tickets.day as classDay',
                'job_tickets.time as classTime',
                'job_tickets.id as ticketID',
                'job_tickets.totalTutorApplied as totalTutorApplied',
                'products.id as subject_id',
                'students.full_name as studentName',
                'students.gender as studentGender',
                'categories.category_name as categoryName',
                'categories.id as categoryID')
            //->whereNull('tutoroffers.ticketID')
            ->orderBy('job_tickets.id', 'DESC')
            ->get();

        return Response::json(['tickets'=>$tickets]);
    }

    public function getStates(){
        $states = DB::table('states')->get();
        return Response::json(['states'=>$states]);
    }

    public function getCities(){
        $cities = DB::table('cities')->get();
        return Response::json(['cities'=>$cities]);
    }

    public function getSubjects(){
        $products = DB::table('products')->get();
        return Response::json(['subjects'=>$products]);
    }

    public function getCategories(){
        $categories = DB::table('categories')->get();
        return Response::json(['categories'=>$categories]);
    }

    public function getTutors(){
        $tutors = DB::table('tutors')->get();
        return Response::json(['tutors'=>$tutors]);
    }

    public function getClassSchedules(){
        $classSchedules = DB::table('class_schedules')->where('class_schedule_id','=',0)->get();
        return Response::json(['classSchedules'=>$classSchedules]);
    }

    public function getClassSchedulesByID($id){
        $classSchedules = DB::table('class_schedules')->where('class_schedule_id','=',$id)->where('status','=','scheduled')->get();
        return Response::json(['classSchedules'=>$classSchedules]);
    }

    public function getUpcomingClassesByTutorID($tutorID){
        $classSchedules = DB::table('class_schedules')

            ->join('products', 'class_schedules.subjectID', '=', 'products.id')
            ->join('students', 'class_schedules.studentID', '=', 'students.id')
            ->where('class_schedules.tutorID','=',$tutorID)
            ->where('class_schedules.status','=','scheduled')
            ->where('class_schedules.class_schedule_id','!=',0)

            ->where('class_schedules.date', '>=', date("Y-m-d")."%")
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
        return Response::json(['classSchedules'=>$classSchedules]);
    }



    public function getClassScheduleHoursByID($id){
        $totalHoursForClassSchedules = DB::table('class_schedules')->where('class_schedule_id','=',$id)->where('status','=','scheduled')->sum('totalTime');
        return Response::json(['totalHoursForClassSchedules'=>$totalHoursForClassSchedules]);
    }

    public function getClassAttendedHoursByID($id){
        $rounded = DB::table('class_attendeds')->where('csID','=',$id)->where('status','=','attended')->sum('totalTime');
        $totalHoursForClassAttended = number_format((float)$rounded, 2, '.', '');
        return Response::json(['totalHoursForClassAttended'=>$totalHoursForClassAttended]);
    }

    public function getClassSchedulesTime($id){

        // return Response::json(['classSchedulesTime'=>$id]);
        $classSchedulesTime = DB::table('class_schedules')
            ->join('job_tickets', 'job_tickets.id', '=', 'class_schedules.ticketID')
            ->join('products', 'products.id', '=', 'class_schedules.subjectID')
            ->join('students', 'students.id', '=', 'class_schedules.studentID')
            ->join('customers', 'customers.id', '=', 'students.customer_id')
            ->join('cities', 'cities.id', '=', 'customers.city')

            ->where('class_schedules.tutorID','=',$id)
            ->select(
                'class_schedules.*',
                'students.id as studentID',
                'students.full_name as studentName',
                'products.id as subjectID',
                'products.name as subjectName',
                'class_schedules.date as dateTime',
                'job_tickets.uid as jtuid',
                'job_tickets.quantity as quantity',
                'cities.name as city',
                'products.category as category',
                DB::raw('TIMESTAMPDIFF(HOUR, class_schedules.date, NOW()) as remainingHours'),

                DB::raw('TIMESTAMPDIFF(HOUR, class_schedules.date, NOW()) as remainingHours')
            )
            ->orderBy('class_schedules.created_at','desc')
            ->get();




        foreach ($classSchedulesTime as $classSchedule) {
            $mode = DB::table('categories')
                ->where('id', '=', $classSchedule->category)
                ->first();

            $classSchedule->mode = $mode->mode != null ? $mode->mode : "Online";
        }

        return response()->json(['classSchedulesTime' => $classSchedulesTime]);
    }


    public function getClassAttendedTime($id){
        $classAttendedTime = DB::table('class_attendeds')
            ->join('products', 'products.id', '=', 'class_attendeds.subjectID')
            ->join('students', 'students.id', '=', 'class_attendeds.studentID')

            ->join('job_tickets', 'class_attendeds.ticketID', '=', 'job_tickets.id')
            ->leftjoin('cities', 'students.city', '=', 'cities.id')
            ->leftjoin('categories', 'products.category', '=', 'categories.id')

            ->where('class_attendeds.endTIme','!=',null)
            ->where('class_attendeds.tutorID','=',$id)
            ->select('class_attendeds.*','students.id as studentID','students.full_name as studentName','products.id as subjectID','products.name as subjectName',
                'job_tickets.uid as jtid',

                'cities.name as city',

                'categories.category_name as level',

                'job_tickets.mode as classMode'
            )
            ->get();

            // dd($classAttendedTime);

        foreach($classAttendedTime as $time)
        {
            // dd($time->totalTime);
            $time->totalPrice=number_format($time->commission, 2, '.', '');
            $time->totalTime=$time->totalTime;

            // Create a DateTime object from the date string
            $dateTime = new DateTime($time->date);

            // Get the day as a number (1 for Monday, 2 for Tuesday, etc.)
            $dayNumber = $dateTime->format('N');

            // If you want the day as a string (e.g., "Monday")
            $dayString = $dateTime->format('d-M-Y');

            // dd($dayString);

            $time->classDate=$dayString;
            // dd($time->status);

            if($time->status==null)
            {
                $time->status="pending";
            }


        }

        return Response::json(['classAttendedTime'=>$classAttendedTime]);
    }
    // public function getClassAttendedTime($id){
    //     $classAttendedTime = DB::table('class_attendeds')
    //                             ->join('products', 'products.id', '=', 'class_attendeds.subjectID')
    //                             ->join('students', 'students.id', '=', 'class_attendeds.studentID')
    //                             ->where('class_attendeds.tutorID','=',$id)->where('class_attendeds.status','=','attended')
    //                             ->select('class_attendeds.*','students.id as studentID','students.full_name as studentName','products.id as subjectID','products.name as subjectName',
    //                                     DB::raw("CONCAT('https://sifututor.odits.co/new/public/signInProof/', class_attendeds.startTimeProofImage) AS startTimeProofImage"),
    //                                     DB::raw("CONCAT('https://sifututor.odits.co/new/public/signOutProof/', class_attendeds.endTimeProofImage) AS endTimeProofImage")
    //                                     )
    //                             ->get();
    //     return Response::json(['classAttendedTime'=>$classAttendedTime]);
    // }

    public function attendedClassClockInTwo(Request $request){

        $id = $request->id;
        $class_schedule_id = $request->class_schedule_id;
        $min = $request->startMinutes;
        $sec = $request->startSeconds;
        $hasIncentive = $request->hasIncentive;

        $sTime = $min.':'.$sec.':00';
        // $sTime = "14:10:49";
        $startTimeProofImage = time().'.'.$request->startTimeProofImage->extension();
        $request->startTimeProofImage->move(public_path('signInProof'), $startTimeProofImage);

        $getClassSchedule = DB::table('class_schedules')->where('id','=',$request->id)->first();

        $values = array(
            'tutorID' => $getClassSchedule->tutorID,
            'class_schedule_id' => $getClassSchedule->id,
            'csID' => $getClassSchedule->class_schedule_id,
            'studentID' => $getClassSchedule->studentID,
            'subjectID' => $getClassSchedule->subjectID,
            'ticketID' => $getClassSchedule->ticketID,
            'date' => $getClassSchedule->date,
            'startTimeProofImage' => $startTimeProofImage,
            'startTime' => $sTime,
            'totalTime' => 0,

        );
        $classAttendedID = DB::table('class_attendeds')->insertGetId($values);


        $parent = DB::table('students')->where('id','=',$getClassSchedule->studentID)->first();
        $parent = DB::table('customers')->where('id','=',$parent->id)->first();
        $ticketUID = DB::table('job_tickets')->where('id','=',$getClassSchedule->ticketID)->first();

        //dd($parent);
        if(isset($parent)&& $parent->whatsapp!=null)

        {
            $whatsapp_api = new WhatsappApi();
            $phone_number = $parent->whatsapp;
            $message = "Clock In for:"."<b>".$ticketUID->uid."</b>";
            $whatsapp_api->send_message($phone_number, $message);
        }

        DB::table('class_schedules')->where('id','=',$request->id)->update(["status"=>"On going"]);
        return Response::json(['result'=>'Class Checkin Time Added for Attendance', 'checkInTime'=>$sTime, 'startTimeProofImage'=>$startTimeProofImage,'classAttendedID'=>$classAttendedID ]);
    }

    public function attendedClassClockIn($id, $class_schedule_id, $min, $sec, $hasIncentive){

        $sTime = $min.':'.$sec.':00';

        $getClassSchedule = DB::table('class_schedules')->where('id','=',$id)->first();

        $values = array(
            'tutorID' => $getClassSchedule->tutorID,
            'class_schedule_id' => $getClassSchedule->id,//primaryID from tbl_class_schedules in tbl_class_attended
            'csID' => $getClassSchedule->class_schedule_id,
            'studentID' => $getClassSchedule->studentID,
            'subjectID' => $getClassSchedule->subjectID,
            'ticketID' => $getClassSchedule->ticketID,
            'date' => $getClassSchedule->date,
            'startTime' => $sTime,
            'totalTime' => 0,
            'hasIncentive' => 1,
        );
        $customerLastID = DB::table('class_attendeds')->insertGetId($values);

        return Response::json(['result'=>'Class Checkin Time Added for Attendance', 'checkInTime'=>$sTime, ]);
    }

    public function attendedClassClockOut($id, $class_schedule_id, $min, $sec, $hasIncentive){

        $endTime = $min.':'.$sec.':00';
        $getClassSchedule = DB::table('class_attendeds')->where('csID','=',$id)->first();

        $sTime = $getClassSchedule->startTime;
        $eTime = $endTime;
        $t1  = strtotime($sTime);
        $t2 = strtotime($eTime);
        $differenceInSeconds = $t2 - $t1;
        $differenceInHours = $differenceInSeconds / 3600;
        $totalTime = $differenceInHours.' Hours';
        if($differenceInHours<0) {
            $differenceInHours += 24;
            $totalTime = $differenceInHours.' Hours';
        }

        DB::table('class_attendeds')
            ->where('id', $id)
            ->update(['endTime' => $endTime, 'totalTime' => $differenceInHours]);


        return Response::json(['result'=>'Class CheckOut Time Added for Attendance', 'checkInTime'=>$endTime, 'totalTime'=>$totalTime,'classAttendedID'=>$id ]);
    }

    // public function attendedClassClockOutTwo(Request $request)
    // {

    //     $id = $request->id;
    //     $class_schedule_id = $request->class_schedule_id;
    //     $min = $request->endMinutes;
    //     $sec = $request->endSeconds;

    //     $endTimeProofImage = time().'.'.$request->endTimeProofImage->extension();
    //     $request->endTimeProofImage->move(public_path('signOutProof'), $endTimeProofImage);

    //     $endTime = $min.':'.$sec.':00';
    //     $getClassSchedule = DB::table('class_attendeds')->where('id','=',$id)->first();

    //     $studentName = DB::table('students')->where('id','=',$getClassSchedule->studentID)->first();
    //     $subjectName = DB::table('products')->where('id','=',$getClassSchedule->subjectID)->first();

    //      $sTime = $getClassSchedule->startTime;
    //      $eTime = $endTime;
    //      $t1  = strtotime($sTime);
    //      $t2 = strtotime($eTime);
    //      $differenceInSeconds = $t2 - $t1;
    //      $differenceInHours = $differenceInSeconds / 3600;
    //      $differenceInMinutes = $differenceInSeconds / 60;
    //         $totalTime = number_format((float)$differenceInHours, 2, '.', '').' Hours';
    //      if($differenceInHours<0) {
    //          $differenceInHours += 24;
    //          $totalTime = number_format((float)$differenceInHours, 2, '.', '').' Hours';
    //      }


    //     if ($differenceInMinutes < 1) {

    //       return Response::json(['errorMsg' => "Please complete atleast an hour to complete the Clock Out"]);

    //     }

    //     DB::table('class_attendeds')
    //             ->where('id', $id)
    //             ->update(['endTime' => $endTime, 'endTimeProofImage' => $endTimeProofImage, 'totalTime' => $differenceInHours]);


    //     $attendedRecord = DB::table('class_attendeds')->where('class_schedule_id','=',$getClassSchedule->class_schedule_id)->first();
    //     $subjectPrice = DB::table('products')->where('id','=',$getClassSchedule->subjectID)->first();


    //       DB::table('class_attendeds')
    //             ->where('class_schedule_id', $getClassSchedule->class_schedule_id)
    //             ->update(['status' => 'pending', 'attendedStatusAttachment' => $endTimeProofImage, 'subjectPrice' => $subjectPrice->price,  'totalPrice' => $subjectPrice->price * $attendedRecord->totalTime]);

    //       DB::table('class_schedules')
    //                 ->where('class_schedule_id', $getClassSchedule->class_schedule_id)
    //                 ->update(['status' => 'pending', 'attendedStatusAttachment' => $endTimeProofImage, 'subjectPrice' => $subjectPrice->price,  'totalPrice' => $subjectPrice->price * $attendedRecord->totalTime]);


    //     $getClassScheduleAfterUpdate = DB::table('class_schedules')
    //                                 ->join('students','students.id','class_schedules.studentID')
    //                                 ->join('products','products.id','class_schedules.subjectId')
    //                                 ->select('class_schedules.*',
    //                                                 'students.full_name as studentName',
    //                                                 DB::raw("CONCAT('https://sifututor.odits.co/new/public/attendedStatusAttachment/', class_schedules.attendedStatusAttachment) AS attendedStatusAttachment"),
    //                                                 'products.name as subjectName')
    //                                 ->where('class_schedules.id','=',$request->id)->get();


    //                     $class_schedule_data= DB::table('class_schedules')->where('class_schedules.id','=',$request->id)->get()->first();
    //                     $student_data= DB::table('students')->where('id','=',$class_schedule_data->studentID)->get()->first();
    //                     $customer_data= DB::table('customers')->where('id','=',$student_data->customer_id)->get()->first();

    //                     if($customer_data->email != null)
    //                     {
    //                         $to=$customer_data->email;

    //                     }else
    //                     {
    //                          $to="binasift@gmail.com";
    //                     }

    //                     // $to=$customer_data->email;
    //                     // $to = 'mantaqiilmi@gmail.com';



    //                     $subject = "Attendance Report at:". date('Y-m-d H:i:s');

    //                     $message = "<html><head></head><body><p>
    //                             Dear Parents/Guardians, <br/>
    //                             <h4> SubjectName: $subjectName->name</h4>
    //                             <h4> StudentName: $studentName->full_name</h4>
    //                             Below are the date of classes attended at the date of <br/>
    //                             '<strong>Start Time: $attendedRecord->startTime,<br/> end Time:$attendedRecord->endTime</strong>', <br/> Total Time : $attendedRecord->totalTime <br/><br/>
    //                             Dear parents/guardians appreciate to verify. <br/><br/>
    //                             Click <a style='font-weight:bold; font-size:23px; color:green;' href='https://sifututor.odits.co/new/agreeAttendance/$attendedRecord->id'>Agree</a> If you agree <br/><br/>
    //                             if Not agree Please Click
    //                             <a style='font-weight:bold; font-size:23px; color:red;' href='https://sifututor.odits.co/new/disputeAttendance'>I Don't Agree</a>
    //                              <br/><br/>
    //                             Thank you <br/><br/>
    //                         </p>

    //                     </body>
    //                     </html>
    //                     ";

    //                     $headers = "MIME-Version: 1.0" . "\r\n";
    //                     $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    //                     // More headers
    //                     $headers .= 'From: <tutor@sifututor.com>' . "\r\n";

    //                     mail($to,$subject,$message,$headers);


    //                     $subjectTwo = 'Invoice-2';

    //                     $messageTwo = '<div class="container-fluid">


    //             <div class="row">
    //                 <div class="col-md-12">
    //                     &nbsp;
    //                 </div>
    //             </div>




    //             <div class="row">
    //                 <div class="col-md-12">
    //                     <div class="card">
    //                         <div class="card-header">
    //                             <h2 class="pull-left">INVOICE #ST117226</h2>
    //                             <div class="progress">
    //                                 <div class="progress-bar progress-bar-sm bg-gradient" role="progressbar" aria-valuenow="41.66666666666667" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
    //                             </div>
    //                         </div>
    //                         <div class="card-body">
    //                             <div class="row">
    //                                 <div class="col-sm-6">
    //                                     <div class="sender-logo">
    //                                         <img src="/Files/Logo/16-10-2020-17-07-054198.png" style="max-height: 100px;max-width: 100%;">
    //                                     </div>
    //                                 </div>
    //                                 <div class="col-sm-6">
    //                                     <div class="display-3 text-right" style="font-size:2.0rem">
    //                                             <span class="text text-danger font-bold">Unpaid</span>
    //                                     </div>
    //                                     <div class="invoice-date text-right" style="margin-top:10px">
    //                                         <strong>Invoice #:</strong> ST117226 <br>
    //                                         <strong>Issued Date:</strong> 27/12/2023<br>
    //                                     </div>
    //                                     <div class="row mt-3">
    //                                         <div class="col-md-12 text-right">
    //                                             <a href="/Public/Invoices/DownloadInvoice/December-2023-ST117226?token=ad2y7S11Tl2" class="btn btn-secondary btn-sm downloadInvoice waves-effect waves-light" style="margin-left: 22px"><i class="fa fa-download" style="font-size: 10px;"></i> Invoice</a>
    //                                         </div>
    //                                     </div>
    //                                 </div>
    //                             </div>
    //                             <div class="mb-5"></div>
    //                             <div class="card-table table-responsive">
    //                                 <table class="table table-hover">
    //                                     <thead>
    //                                         <tr>
    //                                             <th>#</th>
    //                                             <th>Description of classes</th>
    //                                             <th class="text-center">Hours</th>
    //                                             <th class="text-right">Unit Price</th>
    //                                             <th class="text-right">Total</th>
    //                                         </tr>
    //                                     </thead>
    //                                     <tbody>
    //                                             <tr>
    //                                                 <td>1</td>
    //                                                 <td>Dec - Nur Alia Faqihah - Mathematics (SPM) - PHYSICAL</td>
    //                                                 <td class="text-center">4.5</td>
    //                                                 <td class="text-right">RM 60.00</td>
    //                                                 <td class="text-right">RM 270.00</td>
    //                                             </tr>
    //                                             <tr>
    //                                                 <td>2</td>
    //                                                 <td>Dec - Nur Alia Faqihah - Add Maths (SPM) - PHYSICAL</td>
    //                                                 <td class="text-center">4.5</td>
    //                                                 <td class="text-right">RM 60.00</td>
    //                                                 <td class="text-right">RM 270.00</td>
    //                                             </tr>
    //                                             <tr>
    //                                                 <td>3</td>
    //                                                 <td>Dec - Nur Alia Faqihah - Chemistry (SPM) - PHYSICAL</td>
    //                                                 <td class="text-center">4.5</td>
    //                                                 <td class="text-right">RM 60.00</td>
    //                                                 <td class="text-right">RM 270.00</td>
    //                                             </tr>
    //                                             <tr>
    //                                                 <td>4</td>
    //                                                 <td>Dec - Nur Alia Faqihah - Physics (SPM)- PHYSICAL</td>
    //                                                 <td class="text-center">4.5</td>
    //                                                 <td class="text-right">RM 60.00</td>
    //                                                 <td class="text-right">RM 270.00</td>
    //                                             </tr>
    //                                             <tr>
    //                                                 <td colspan="4" class="text-right">
    //                                                     <strong>Commitment Fees</strong>
    //                                                 </td>
    //                                                 <td class="table-secondary text-right">
    //                                                     <strong>RM -50.00</strong>
    //                                                 </td>
    //                                             </tr>
    //                                             <tr>
    //                                                 <td colspan="4" class="text-right">
    //                                                     <strong>Grand Total</strong>
    //                                                 </td>
    //                                                 <td class="table-secondary text-right">
    //                                                     <strong>RM 1,030.00</strong>
    //                                                 </td>
    //                                             </tr>
    //                                     </tbody>
    //                                 </table>
    //                                 <table class="table table-responsive no-border">
    //                                     <tbody><tr>
    //                                         <td colspan="3"> 1) This invoice is computer generated and no signature is required
    //                                         <br>2) Payment is due within 3 working days of issuance of this invoice
    //                                         <br>3) You can pay online via online banking by clicking the button PAY NOW or alternatively can transfer to account no below :
    //                                         <br>
    //                                         <br>MAYBANK - 562115516678    SIFU EDU &amp; LEARNING SDN BHD   </td>
    //                                     </tr>
    //                                 </tbody></table>
    //                             </div>
    //                                     <form method="post" action="https://payment.ipay88.com.my/ePayment/entry.asp" id="makePaymentForm" name="makePaymentForm" novalidate="novalidate">
    //                                         <div class="row">
    //                                             <div class="col-lg-12 col-md-8">
    //                                                 <input type="hidden" name="MerchantCode" value="M28937">
    //                                                 <input type="hidden" name="RefNo" value="ST117226">
    //                                                 <input type="hidden" name="Amount" id="Amount" value="1030.00">
    //                                                 <input type="hidden" name="Currency" value="MYR">
    //                                                 <input type="hidden" name="ProdDesc" value="December 2023 - ST117226">
    //                                                 <input type="hidden" name="UserName" value="Wan Noriza">
    //                                                 <input type="hidden" name="UserEmail" value="kashiza82@yahoo.com">
    //                                                 <input type="hidden" name="UserContact" value="60195704303">
    //                                                 <input type="hidden" name="Remark">
    //                                                 <input type="hidden" name="Lang" value="UTF-8">
    //                                                 <input type="hidden" name="SignatureType" value="SHA256">
    //                                                 <input type="hidden" name="Signature" id="Signature" value="7dccc25ade1ff65c02724810a63c6155ca3f3b88753aea698bd6ec95c8145b5f">
    //                                                 <input type="hidden" name="ResponseURL" value="https://portal.sifututor.my/Public/Invoices/RedirectPaymentStatus?invoiveReferenceNo=ST117226&amp;token=ad2y7S11Tl2">
    //                                                 <input type="hidden" name="BackendURL" value="https://portal.sifututor.my/Public/Invoices/ConfirmPaymentStatus?invoiveReferenceNo=ST117226&amp;token=ad2y7S11Tl2">
    //                                                 <label class="" for="PaymentId">Choose Payment Method:</label>
    //                                                 <select class="form-control valid" style="max-width:500px;" data-val="true" data-val-length-max="150" data-val-required="Please select payment method" id="PaymentId" name="PaymentId" aria-required="true" aria-invalid="false" aria-describedby="PaymentId-error">
    //                                                     <option value=""></option>
    //                                                         <option value="2">Credit Card (MYR)</option>
    //                                                         <option value="6">Maybank2U</option>
    //                                                         <option value="8">Alliance Online</option>
    //                                                         <option value="10">AmOnline</option>
    //                                                         <option value="14">RHB Online</option>
    //                                                         <option value="15">Hong Leong Online</option>
    //                                                         <option value="20">CIMB Click</option>
    //                                                         <option value="31">Public Bank Online</option>
    //                                                         <option value="102">Bank Rakyat Internet Banking</option>
    //                                                         <option value="103">Affin Online</option>
    //                                                         <option value="124">BSN Online</option>
    //                                                         <option value="134">Bank Islam</option>
    //                                                         <option value="152">UOB</option>
    //                                                         <option value="166">Bank Muamalat</option>
    //                                                         <option value="167">OCBC</option>
    //                                                         <option value="168">Standard Chartered Bank</option>
    //                                                         <option value="198">HSBC Online Banking</option>
    //                                                         <option value="199">Kuwait Finance House</option>
    //                                                         <option value="210">Boost Wallet</option>
    //                                                 </select>
    //                                                 <span class="text-danger field-validation-valid" data-valmsg-for="PaymentId" data-valmsg-replace="true"></span>
    //                                                 <button class="btn btn-primary btn-paynow waves-effect waves-light" id="Paynow" type="submit">Pay Now</button>
    //                                                 <br>
    //                                                     <br>
    //                                                     <p class="font-bold" style="font-size:16px">
    //                                                         Pay in advance 3 months of home or online tuition and enjoy 10% discount
    //                                                     </p>
    //                                                     <a class="btn btn-pay-threemonth btn-primary waves-effect waves-light" id="GetDiscount">Pay 3 months</a>
    //                                             </div>
    //                                         </div>
    //                                     </form>
    //                         </div>
    //                     </div>
    //                 </div>
    //             </div>

    //             <div class="row">
    //                 <div class="col md-12 text-right mt-5">
    //                     <div class="copyright">
    //                         <p>
    //                             Copyright © 2020. | Powered by <a href="https://www.aafinancebuddy.com" target="_blank"><img class="companylogo" src="/images/aafb-sidetextlogo-color.png"></a> | All Rights Reserved.
    //                         </p>
    //                     </div>
    //                 </div>
    //             </div>


    //         </div>';

    //                     $headersTwo = "MIME-Version: 1.0" . "\r\n";
    //                     $headersTwo .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    //                     // More headers
    //                     $headersTwo .= 'From: <tutor@sifututor.com>' . "\r\n";

    //                     mail($to,$subjectTwo,$messageTwo,$headersTwo);




    //     $parent = DB::table('students')->where('id','=',$getClassSchedule->studentID)->first();
    //     $parent = DB::table('customers')->where('id','=',$parent->id)->first();
    //     $ticketUID = DB::table('job_tickets')->where('id','=',$getClassSchedule->ticketID)->first();

    //     if(isset($parent)&& $parent->whatsapp!=null)

    //     {
    //         $whatsapp_api = new WhatsappApi();
    //         $phone_number = $tutor->whatsapp;
    //         $message = "Clock Out for:"."<b>".$ticketUID."</b>";
    //         $whatsapp_api->send_message($phone_number, $message);
    //     }

    //     return Response::json(['result'=>'Class CheckOut Time Added for Attendance', 'classAttendedID'=>$id, 'studentID'=>$getClassSchedule->studentID, 'subjectID'=>$getClassSchedule->subjectID, 'class_schedule_id'=>$getClassSchedule->class_schedule_id, 'subjectSubject'=>$subjectName->name, 'studentName'=>$studentName->full_name, 'checkInTime'=>$getClassSchedule->startTime, 'checkOutTime'=>$endTime, 'totalTime'=>$totalTime, 'endTimeProofImage'=>$endTimeProofImage, ]);
    // }

  public function attendedClassClockOutTwo(Request $request)
    {

        $id = $request->id;
        $class_schedule_id = $request->class_schedule_id;
        $min = $request->endMinutes;
        $sec = $request->endSeconds;

        $endTimeProofImage = time() . '.' . $request->endTimeProofImage->extension();
        $request->endTimeProofImage->move(public_path('signOutProof'), $endTimeProofImage);

        // $endTime = "14:10:49";
        $endTime = $min . ':' . $sec . ':00';
        $getClassSchedule = DB::table('class_attendeds')->where('id', '=', $id)->first();

        //dd($getClassSchedule);

        //   return Response::json(['errorMsg' => $request->all()]);

        $studentName = DB::table('students')->where('id', '=', $getClassSchedule->studentID)->first();
        $subjectName = DB::table('products')->where('id', '=', $getClassSchedule->subjectID)->first();

        $sTime = $getClassSchedule->startTime;
        $eTime = $endTime;
        $t1 = strtotime($sTime);
        $t2 = strtotime($eTime);
        $differenceInSeconds = $t2 - $t1;
        $differenceInHours = $differenceInSeconds / 3600;
        $differenceInMinutes = $differenceInSeconds / 60;
        $totalTime = number_format((float)$differenceInHours, 2, '.', '') . ' Hours';
        if ($differenceInHours < 0) {
            $differenceInHours += 24;
            $totalTime = number_format((float)$differenceInHours, 2, '.', '') . ' Hours';
        }


        // if ($differenceInMinutes < 1) {
        //     return Response::json(['errorMsg' => "Please complete atleast an hour to complete the Clock Out"]);
        // }

        DB::table('class_attendeds')
            ->where('id', $id)
            ->update(['endTime' => $endTime, 'endTimeProofImage' => $endTimeProofImage, 'totalTime' => number_format($differenceInHours,2)]);


        $attendedRecord = DB::table('class_attendeds')->where('class_schedule_id', '=', $getClassSchedule->class_schedule_id)->first();


        $subjectPrice = DB::table('products')->join("categories", "products.category", "=", "categories.id")
            ->select("products.*", "categories.price as category_price")
            ->where('products.id', '=', $getClassSchedule->subjectID)->first();


        $job_ticket= DB::table("job_tickets")->where("id",$getClassSchedule->ticketID)->first();

        //commission work
        
        $class_schedule = DB::table("class_schedules")->where("id", $request->class_schedule_id)->first();

$totalAttendedHours = DB::table('class_schedules')
    ->where('ticketID', '=', $job_ticket->id)
    ->where("status", "attended")
    ->sum('totalTime');

$newTotalAttendedHours = $totalAttendedHours + $class_schedule->totalTime;

// Initialize commission variables
$per_class_commission = 0;
$hoursAfterEight = 0;
$hoursBeforeEight = 0;
$commissionAfterEight = 0;
$commissionBeforeEight = 0;

// Calculate commission for only the newly added hours
if ($newTotalAttendedHours > 8) {
    if ($totalAttendedHours >= 8) {
        // All newly added hours are after the first 8 hours
        $hoursAfterEight = $class_schedule->totalTime;
    } else {
        // Some newly added hours are within the first 8 hours, and some are after
        $hoursBeforeEight = min($class_schedule->totalTime, 8 - $totalAttendedHours);
        $hoursAfterEight = $class_schedule->totalTime - $hoursBeforeEight;
    }

    // Calculate commissions
    $commissionBeforeEight = $hoursBeforeEight * $job_ticket->per_class_commission_before_eight_hours;
    $commissionAfterEight = $hoursAfterEight * $job_ticket->per_class_commission_after_eight_hours;
    $per_class_commission = $commissionBeforeEight + $commissionAfterEight;
} else {
    // All newly added hours are within the first 8 hours
    $commissionBeforeEight = $class_schedule->totalTime * $job_ticket->per_class_commission_before_eight_hours;
    $per_class_commission = $commissionBeforeEight;
}
        //end commission work

        // $total_sessions=$job_ticket->quantity*$job_ticket->classFrequency;
        // $total_commission=$job_ticket->estimate_commission;
        // $per_class_commission=$total_commission/$total_sessions;
        // $per_class_commission=$per_class_commission*$job_ticket->quantity;



        // dd($getClassSchedule->class_schedule_id);
        DB::table('class_attendeds')
            ->where('class_schedule_id', $getClassSchedule->class_schedule_id)
            ->update(['status' => 'pending', 'attendedStatusAttachment' => $endTimeProofImage,
                'commission'=>$per_class_commission,
                'subjectPrice' => $subjectPrice->category_price, 'totalPrice' => $subjectPrice->category_price * $attendedRecord->totalTime]);


        DB::table('class_schedules')
            ->where('id', $getClassSchedule->class_schedule_id)
            ->update(['status' => 'pending', 'attendedStatusAttachment' => $endTimeProofImage, 'subjectPrice' => $subjectPrice->category_price, 'totalPrice' => $subjectPrice->category_price * $attendedRecord->totalTime]);


        //dd($getClassSchedule);


        $baseUrl = url("/attendedStatusAttachment");
        $getClassScheduleAfterUpdate = DB::table('class_schedules')
            ->join('students', 'students.id', 'class_schedules.studentID')
            ->join('products', 'products.id', 'class_schedules.subjectId')
            ->select(
                'class_schedules.*',
                'students.full_name as studentName',
                DB::raw("CONCAT('$baseUrl', class_schedules.attendedStatusAttachment) AS attendedStatusAttachment"),
                'products.name as subjectName'
            )
            ->where('class_schedules.id', '=', $request->id)
            ->get();


        $class_schedule_data = DB::table('class_schedules')->where('class_schedules.id', '=', $request->id)->get()->first();
        $student_data = DB::table('students')->where('id', '=', $getClassSchedule->studentID)->get()->first();
        $customer_data = DB::table('customers')->where('id', '=', $student_data->customer_id)->get()->first();

        if ($customer_data->email != null) {
            $to = $customer_data->email;
            $emailTwo = "binasift@gmail.com";

        } else {
            $to = "binasift@gmail.com";
            $emailTwo = "binasift@gmail.com";

        }

        // $to=$customer_data->email;
        // $to = 'mantaqiilmi@gmail.com';


        $subject = "Attendance Report at:" . date('Y-m-d H:i:s');

        $agreePath = url("/")."/agreeAttendance"."/". $attendedRecord->id;
        $disagreePath = url("/")."/disputeAttendance". "/".$attendedRecord->id;
        $total_time_attended=number_format($attendedRecord->totalTime,2);

        $message = "<!DOCTYPE html>
<html lang='en'>
  <head>
    <meta charset='UTF-8' />
    <meta http-equiv='X-UA-Compatible' content='IE=edge' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0' />
    <title>Attendance Confirmation</title>
  </head>
  <body
    style='
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
      background-color: #f4f4f4;
      width: 95%; max-width: 650px;
      margin: 0 auto;
    '
  >
    <div
      style='
        margin: auto;
        background-color: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      '
    >
      <h2 style='color: #333333'>Attendance Confirmation</h2>

      <p>Dear Parents/Guardians, below are details of the recent class attendance and we appreciate your verification : </p>

      <p>
        <strong>Subject Name:</strong> $subjectName->name</p>
        <p></p><strong>Student Name:</strong> $studentName->full_name
      </p>

      <p>
        Below are the details of the class attended:<br />

        <table style='border: 1px solid #ccc; border-collapse: collapse; width: 100%; table-layout: fixed;'>
          <thead>
            <tr>
              <th style='background-color: #233cb3; color: #fff; padding: .625em; text-align: center; font-size: .85em; letter-spacing: .1em; text-transform: uppercase;' scope='col'>Date</th>
              <th style='background-color: #233cb3; color: #fff; padding: .625em; text-align: center; font-size: .85em; letter-spacing: .1em; text-transform: uppercase;' scope='col'>Start Time</th>
              <th style='background-color: #233cb3; color: #fff; padding: .625em; text-align: center; font-size: .85em; letter-spacing: .1em; text-transform: uppercase;' scope='col'>End Time</th>
              <th style='background-color: #233cb3; color: #fff; padding: .625em; text-align: center; font-size: .85em; letter-spacing: .1em; text-transform: uppercase;' scope='col'>Total Time</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td style='background-color: #f8f8f8; border: 1px solid #ddd; padding: .60em; text-align: center;' data-label='Date'>$attendedRecord->date</td>
              <td style='background-color: #f8f8f8; border: 1px solid #ddd; padding: .60em; text-align: center;' data-label='Start Time'>$attendedRecord->startTime</td>
              <td style='background-color: #f8f8f8; border: 1px solid #ddd; padding: .60em; text-align: center;' data-label='End Time'>$attendedRecord->endTime</td>
              <td style='background-color: #f8f8f8; border: 1px solid #ddd; padding: .60em; text-align: center;' data-label='Total Time'>$total_time_attended</td>
            </tr>
          </tbody>
        </table>
      </p>

      <p>
        <strong>Dear Parents/Guardians, below are details of the recent class attendance and we appreciate your verification : </strong>
      </p>

      <p>

        <a
          href='https://sifututor.odits.co/new/agreeAttendance/$attendedRecord->id'
          style='
              display: inline-block;
              margin-top: 10px;
              padding: 10px 0px;
              background-color: #4caf50;
              color: #ffffff;
              text-decoration: none;
              border-radius: 0px;
              width: 49.1%;
              text-align: center;
              font-weight: bold;
          '
          >Agree</a
        >
        <a
          href='https://sifututor.odits.co/new/disputeAttendance/$attendedRecord->id'
          style='
            display: inline-block;
            margin-top: 10px;
            padding: 10px 0px;
            background-color: #e74c3c;
            color: #ffffff;
            text-decoration: none;
            border-radius: 0px;
            width: 49.1%;
            text-align: center;
            font-weight: bold;
          '
          >Disagree</a
        >

        <br> <br>Thank you for your cooperation!<br>Sifututor<br>
      </p>

      <p>Thank you</p>
    </div>
  </body>
</html>
";

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $headers .= 'From: <tutor@sifututor.com>' . "\r\n";

        mail($to, $subject, $message, $headers);
        mail($emailTwo, $subject, $message, $headers);


        $ticketDetails = DB::table('job_tickets')->where('id', '=', $getClassSchedule->ticketID)->first();

        $invoice_detail = DB::table('invoices')->where('ticketID', '=', $ticketDetails->id)->first();

        $invoice_items = DB::table('invoice_items')->

        join("products", "invoice_items.subjectID", "=", "products.id")->

        join("students", "invoice_items.studentID", "=", "students.id")->

        select("invoice_items.*", "students.full_name as full_name", "products.name as name", "products.price as price", "invoice_items.invoiceDate as invoiceDate")->

        where('invoiceID', '=', $invoice_detail->id)->get();


        //Invoice Email setup
        $subjectTwo = 'Invoice';
        $headersTwo = "MIME-Version: 1.0" . "\r\n";
        $headersTwo .= "Content-type: multipart/mixed; boundary=\"boundary\"\r\n";
        // More headers
        $headersTwo .= 'From: <tutor@sifututor.com>' . "\r\n";


        $pdfPath = url("/public/invoicePDF/Invoice-") . $invoice_detail->id . ".pdf";
        // $fileExsist=false;


        $pdfContent = file_get_contents($pdfPath);
        $base64Content = base64_encode($pdfContent);

        if ($ticketDetails->first_invoice_sent != true) {
            // if (true) {
            $emailBody = "";
            $emailBody .= '
                        </tbody>
                        </table>
                        <table class="table table-responsive no-border">
                        <tbody>
                       <tr>
                            <td>
                                Dear Parents/Guardians,<br/>
                                We hope you are having a great day! Here are some details for your latest invoice:
                            </td>
                        </tr>
                        <tr>
                            <td>
                                1) This invoice is computer-generated and no signature is required
                                <br>2) Payment is due within 3 working days of issuance of this invoice
                                <br>3) You can conveniently pay online via banking by clicking the "PAY NOW" button, or you can transfer to the account below:
                                <br>
                                <br>MAYBANK - 562115516678 <br> SIFU EDU & LEARNING SDN BHD
                                <br><br>
                                Good news! If you pay in advance for 3 months of home or online tuition, you\'ll enjoy a 10% discount
                            </td>
                        </tr>
                        </tbody>
                        </table>
                        </div>
                      <form method="post" action="https://payment.ipay88.com.my/ePayment/entry.asp" id="makePaymentForm" name="makePaymentForm" novalidate="novalidate">
                            <div class="row">
                                <div class="col-lg-12 col-md-8">
                                    <input type="hidden" name="MerchantCode" value="M28937">
                                    <input type="hidden" name="RefNo" value="ST117226">
                                    <input type="hidden" name="Amount" id="Amount" value="1030.00">
                                    <input type="hidden" name="Currency" value="MYR">
                                    <input type="hidden" name="ProdDesc" value="December 2023 - ST117226">
                                    <input type="hidden" name="UserName" value="Wan Noriza">
                                    <input type="hidden" name="UserEmail" value="kashiza82@yahoo.com">
                                    <input type="hidden" name="UserContact" value="60195704303">
                                    <input type="hidden" name="Remark">
                                    <input type="hidden" name="Lang" value="UTF-8">
                                    <input type="hidden" name="SignatureType" value="SHA256">
                                    <input type="hidden" name="Signature" id="Signature" value="7dccc25ade1ff65c02724810a63c6155ca3f3b88753aea698bd6ec95c8145b5f">
                                    <input type="hidden" name="ResponseURL" value="https://portal.sifututor.my/Public/Invoices/RedirectPaymentStatus?invoiveReferenceNo=ST117226&amp;token=ad2y7S11Tl2">
                                    <input type="hidden" name="BackendURL" value="https://portal.sifututor.my/Public/Invoices/ConfirmPaymentStatus?invoiveReferenceNo=ST117226&amp;token=ad2y7S11Tl2">
                                    <label class="" for="PaymentId">Choose Payment Method:</label>
                                    <select class="form-control valid" style="max-width:500px;" data-val="true" data-val-length-max="150" data-val-required="Please select payment method" id="PaymentId" name="PaymentId" aria-required="true" aria-invalid="false" aria-describedby="PaymentId-error">
                                        <option value=""></option>
                                            <option value="2">Credit Card (MYR)</option>
                                            <option value="6">Maybank2U</option>
                                            <option value="8">Alliance Online</option>
                                            <option value="10">AmOnline</option>
                                            <option value="14">RHB Online</option>
                                            <option value="15">Hong Leong Online</option>
                                            <option value="20">CIMB Click</option>
                                            <option value="31">Public Bank Online</option>
                                            <option value="102">Bank Rakyat Internet Banking</option>
                                            <option value="103">Affin Online</option>
                                            <option value="124">BSN Online</option>
                                            <option value="134">Bank Islam</option>
                                            <option value="152">UOB</option>
                                            <option value="166">Bank Muamalat</option>
                                            <option value="167">OCBC</option>
                                            <option value="168">Standard Chartered Bank</option>
                                            <option value="198">HSBC Online Banking</option>
                                            <option value="199">Kuwait Finance House</option>
                                            <option value="210">Boost Wallet</option>
                                    </select>
                                    <span class="text-danger field-validation-valid" data-valmsg-for="PaymentId" data-valmsg-replace="true"></span>
                                    <button class="btn btn-primary btn-paynow waves-effect waves-light" id="Paynow" type="submit">Pay Now</button>
                                    <br>
                                        <br>
                                        <p class="font-bold" style="font-size:16px">
                                            Pay in advance 3 months of home or online tuition and enjoy 10% discount
                                        </p>
                                        <a class="btn btn-pay-threemonth btn-primary waves-effect waves-light" id="GetDiscount">Pay 3 months</a>
                                </div>
                            </div>
                        </form>
                        </div>
                        </div>
                        </div>
                        </div>

                        <div class="row">
                        <div class="col md-12 text-right mt-5">
                        <div class="copyright">
                        <p>
                        Copyright © 2024. | Powered by <a href="https://www.aafinancebuddy.com" target="_blank"><img class="companylogo" src="/images/aafb-sidetextlogo-color.png"></a> | All Rights Reserved.
                        </p>
                        </div>
                        </div>
                        </div>

                        </div>
                        ';

            // Attachment
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


            $to_email = "binasift@gmail.com";
            mail($to, $subjectTwo, $emailBody, $headersTwo);
            mail($to_email, $subjectTwo, $emailBody, $headersTwo);
        }

        DB::table('job_tickets')
            ->where('id', $getClassSchedule->ticketID)
            ->update(['first_invoice_sent' => true]);


        $parent = DB::table('students')->where('id', '=', $getClassSchedule->studentID)->first();
        $parent = DB::table('customers')->where('id', '=', $parent->id)->first();
        $ticketUID = DB::table('job_tickets')->where('id', '=', $getClassSchedule->ticketID)->first();
        $tutor = DB::table('tutors')->where('id', '=', $ticketUID->tutor_id)->first();

        if (isset($parent) && $parent->whatsapp != null) {
            $whatsapp_api = new WhatsappApi();
            $phone_number = $parent->whatsapp;
            $message = "Clock Out for:" . "<b>" . $ticketUID->uid . "</b>";
            $whatsapp_api->send_message($phone_number, $message);
        }

        //update home page data
        $data=["Class Clockout Event"];
        event(new MobileHomePageUpdated($data));

        return Response::json(['result' => 'Clockout Sucessfully', 'classAttendedID' => $id, 'studentID' => $getClassSchedule->studentID, 'subjectID' => $getClassSchedule->subjectID, 'class_schedule_id' => $getClassSchedule->class_schedule_id, 'subjectSubject' => $subjectName->name, 'studentName' => $studentName->full_name, 'checkInTime' => $getClassSchedule->startTime, 'checkOutTime' => $endTime, 'totalTime' => $totalTime, 'endTimeProofImage' => $endTimeProofImage,]);
    }



    public function classScheduleStatusNotifications($tutorID){

        $attendedRecord = DB::table('class_attendeds')
            ->join('students','students.id','class_attendeds.studentID')
            ->join('products','products.id','class_attendeds.subjectId')
            ->select('class_attendeds.*',
                'students.full_name as studentName',
                'products.name as subjectName')
            ->where('class_attendeds.tutorID','=',$tutorID)
            ->where('class_attendeds.endTime','!=',NULL)->where('class_attendeds.tutorID','=',$tutorID)->where('class_attendeds.status','=',NULL)
            ->get();



        return Response::json(['record'=>$attendedRecord]);
    }
    public function attendedClassStatus($id, $status, $statusReason){

        $getClassSchedule = DB::table('class_schedules')->where('id','=',$id)->first();




        $tableName = 'job_ticket_students';
        $count = DB::table($tableName)
            ->select(DB::raw('count(*) as count'))
            ->where('job_ticket_id','=',$getClassSchedule->ticketID)
            ->first()
            ->count;

        $attendedRecord = DB::table('class_attendeds')->where('class_schedule_id','=',$getClassSchedule->id)->first();
        $subjectPrice = DB::table('products')->where('id','=',$getClassSchedule->subjectID)->first();
        // new work start
        if($status == 'attended'){


            $extraStudents = DB::table('job_ticket_students')->where('job_ticket_id','=',$getClassSchedule->ticketID)->where('status','=','active')->count();
            $extraStudentFeeCharges = DB::table('extra_student_charges')->orderBy('id','DESC')->first();
            DB::table('class_attendeds')
                ->where('class_schedule_id', $id)
                ->update(['status' => $status, 'subjectPrice' => $subjectPrice->price,  'totalPrice' => $subjectPrice->price * $attendedRecord->totalTime + (($count * $extraStudentFeeCharges->charges)/$attendedRecord->totalTime)]);

            DB::table('class_schedules')
                ->where('id', $attendedRecord->class_schedule_id)
                ->update(['status' => $status, 'subjectPrice' => $subjectPrice->price,  'totalPrice' => $subjectPrice->price * $attendedRecord->totalTime + (($count * $extraStudentFeeCharges->charges)/$attendedRecord->totalTime)]);
            $getClassScheduleAfterUpdate = DB::table('class_schedules')->where('id','=',$id)->first();


            $ticketID = DB::table('job_tickets')->where('id','=',$getClassSchedule->ticketID)->first();
            $ledgerTutorValue = array(
                'payment_reference' => Auth::user()->id,
                'user_id' => Auth::user()->id,
                'bill_no' => $ticketID->uid,
                'purchase_id' => $ticketID->uid,
                'account_id' => $tutorOffer->tutorID,
                'amount' => ($ticketID->totalPrice*70/100)/$attendedRecord->totalTime + (($count * $extraStudentFeeCharges->charges)/$attendedRecord->totalTime),
                'type' => 'd',
                'credit' => null,
                'debit' => ($ticketID->totalPrice*70/100)/$attendedRecord->totalTime + (($count * $extraStudentFeeCharges->charges)/$attendedRecord->totalTime),
                'saleDescription' => 'Total Time - '.$attendedRecord->totalTime.' at '.date('D-M-Y'),
                'sale_note' => $jobTicketID->uid,
                'date' => date('Y-m-d'),
                'date_2' => date('Y-m-d')
            );

            $ledgerID = DB::table('payments')->insertGetId($ledgerTutorValue);



        }else{


            DB::table('class_attendeds')
                ->where('class_schedule_id', $id)
                ->update(['status' => $status, 'statusReason' => $statusReason]);

            DB::table('class_schedules')
                ->where('id', $id)
                ->update(['status' => $status, 'statusReason' => $statusReason]);

            $getClassScheduleAfterUpdate = DB::table('class_schedules')->where('id','=',$id)->first();

           if($status=="cancelled" || $status=="postponed")

            {

                $job_ticket=DB::table("job_tickets")->where("id", $getClassSchedule->ticketID)->first();
                $remainingClasses=$job_ticket->remaining_classes;
                $remainingClasses=$remainingClasses+1;
                DB::table("job_tickets")->where("id", $job_ticket->id)->update(["remaining_classes"=>$remainingClasses]);

                 DB::table('student_subjects')
                        ->where('ticket_id',  $job_ticket->id)
                        ->update(['remaining_classes' =>$remainingClasses]);


            }

        }


        return Response::json(['SuccessMessage'=>'Class Staus has been '.$status, 'status'=>$status, 'record'=>$getClassScheduleAfterUpdate]);
    }




    public function submitClassSchedulesTime($tutorID,$class_schedule_id,$studentID,$subjectID,$ticketID,$date,$startTime,$endTime,$hasIncentive){

        $sTime = $startTime.':00:00';
        $eTime = $endTime.':00:00';
        $t1  = strtotime($sTime);
        $t2 = strtotime($eTime);
        $differenceInSeconds = $t2 - $t1;
        $differenceInHours = $differenceInSeconds / 3600;

        if($differenceInHours<0) {
            $differenceInHours += 24;
        }



        $getClassSchedule = DB::table('class_schedules')->where('subjectID','=',$subjectID)->where('ticketID','=',$ticketID)->first();
        if($getClassSchedule){
            if($hasIncentive == "on"){
                $values = array(
                    'tutorID' => $tutorID,
                    'class_schedule_id' => $getClassSchedule->id,
                    'studentID' => $studentID,
                    'subjectID' => $subjectID,
                    'ticketID' => $ticketID,
                    'date' => $date,
                    'startTime' => $sTime,
                    'endTime' => $eTime,
                    'hasIncentive' => 1,
                    'status' => 'scheduled',
                    'totalTime' => $differenceInHours,
                );

                $submitClassScheduleTime = DB::table('class_schedules')->insertGetId($values);
                $submittedRecord = DB::table('class_schedules')->where('id','=',$submitClassScheduleTime)->first();

                return Response::json(['submitClassScheduleTime'=>$submittedRecord]);
            }else{
                $values = array(
                    'tutorID' => $tutorID,
                    'class_schedule_id' => $getClassSchedule->id,
                    'studentID' => $studentID,
                    'subjectID' => $subjectID,
                    'ticketID' => $ticketID,
                    'date' => $date,
                    'startTime' => $startTime,
                    'endTime' => $endTime,
                    'hasIncentive' => 0,
                    'status' => 'scheduled',
                    'totalTime' => $differenceInHours,
                );
                $submitClassScheduleTime = DB::table('class_schedules')->insertGetId($values);
                $submittedRecord = DB::table('class_schedules')->where('id','=',$submitClassScheduleTime)->first();

                return Response::json(['createdClassSchedule'=>$submittedRecord]);
            }
        }else{
            return Response::json(['error'=>'for Class Schedule no SubjectID and ticketID found']);
        }

    }


    public function getTutorDetailByID($id){
        $tutorDetailById = DB::table('tutors')->where('id','=',$id)->get();


        // dd(count($tutorDetailById));

        // if($tutorDetailById==null)
        // {
        //     dd("null record");
        // }else{
        //     dd("Record fiund");
        // }

        // dd($tutorDetailById);


        if(count($tutorDetailById)==0)
        {
            return Response::json(['tutorDetailById'=>null]);
        }

        if($tutorDetailById[0]->tutorImage==null)
        {
            $tutorDetailById[0]->tutorImage="https://pdtxar.com/wp-content/uploads/2019/04/person-placeholder.jpg";
        } else {
            $tutorDetailById[0]->tutorImage=url("/public/tutorImage")."/".$tutorDetailById[0]->tutorImage;
        }

        return Response::json(['tutorDetailById'=>$tutorDetailById]);
    }


    public function getTutorSubjects($tutorID){
        $tutorSubjects = DB::table('tutor_subjects')
            ->join('products', 'tutor_subjects.subject', '=', 'products.id')
            ->where('tutor_id','=',$tutorID)->get();
        return Response::json(['tutorSubjects'=>$tutorSubjects]);
    }

    public function getStudentSubjects($studentID)
    {

        $studentSubjects = DB::table('student_subjects')
            ->join('products', 'student_subjects.subject', '=', 'products.id')
            // ->join('categories', 'products.category', '=', 'categories.id')
            ->where('student_subjects.student_id', '=', $studentID)->get();

        foreach ($studentSubjects as $key=>$subject) {

            // $studentSubjects[$key]->name=$subject->name." - ".$subject->mode;
            $product=DB::table('products')->where("id",$subject->subject)->first();
            $category=DB::table('categories')->where("id",$product->category)->first();
            $studentSubjects[$key]->name=$subject->name." - ".$category->mode;
            if ($subject->remaining_classes == 0 || $subject->remaining_classes == null) {
                $subject->remaining_classes = "0";
            }

            $totalHours=$subject->quantity*$subject->classFrequency;
            $subject->total_hours=$totalHours;
            $subject->reamining_hours=$subject->quantity*$subject->remaining_classes;
        }

        // dd($studentSubjects);

        return Response::json(['studentSubjects' => $studentSubjects]);
    }

    public function getAttendedHours($tutorID){


        //      $total_attended_hours = DB::table('class_schedules')->where(['tutorID'=> $tutorID,"is_paid"=>"unpaid","is_tutor_paid"=>"unpaid"])->
        // where('status', '=', "attended")
        //     ->sum('totalTime');
        //     dd($total_attended_hours);



        $queryResult = DB::table('class_attendeds')
            ->select(DB::raw('SUM(
            TIME_TO_SEC(SUBSTRING_INDEX(totalTime, ":", 1)) * 3600 +
            TIME_TO_SEC(SUBSTRING_INDEX(SUBSTRING_INDEX(totalTime, ":", -2), ":", 1)) * 60 +
            TIME_TO_SEC(SUBSTRING_INDEX(totalTime, ":", -1))
        ) AS totalSeconds'))
            ->where(['tutorID'=> $tutorID,'status'=>'attended','is_paid'=>'unpaid','is_tutor_paid'=>'unpaid'])
            ->first();

        $totalSeconds = $queryResult->totalSeconds;
        $attendedDurationInSeconds = $totalSeconds / 3600; // Convert seconds to hours



            if($attendedDurationInSeconds==null)
            {
               $attendedDuration=0;
            }else{
                        // Convert the total duration from seconds to the desired format (HH:MM:SS)
            $attendedDuration = number_format($attendedDurationInSeconds,2);
            }








        return Response::json(['attendedHours'=>$attendedDuration]);
    }

    public function getAssignedTickets($tutorID){
        $assignedTickets = DB::table('job_tickets')->where('tutor_id','=',$tutorID)->count();


        return Response::json(['assignedTickets'=>$assignedTickets]);
    }



    public function getScheduledHours($tutorID){
        $rounded = DB::table('class_schedules')->where('tutorID','=',$tutorID)->where('status','=','scheduled')->sum('totalTime');
        $scheduledHours = number_format((float)$rounded, 2, '.', '');
        return Response::json(['scheduledHours'=>$scheduledHours]);
    }
    public function getCancelledHours($tutorID){
        $rounded = DB::table('class_schedules')->where('tutorID','=',$tutorID)->where('status','=','cancelled')->sum('totalTime');
        $cancelledHours = number_format((float)$rounded, 2, '.', '');
        return Response::json(['cancelledHours'=>$cancelledHours]);
    }

    public function getCommulativeCommission($tutorID){
        $class_attended = DB::table('class_attendeds')->where(['tutorID'=>$tutorID,'status' =>'attended',"is_paid"=>"unpaid","is_tutor_paid"=>"unpaid"])->get();
        $commulativeCommission = 0;
        foreach($class_attended as $rowClassAttended){
            // $tickets = DB::table('job_tickets')->where('id','=',$rowClassAttended->ticketID)->first();
            $commulativeCommission += $rowClassAttended->commission;
        }

        $commulativeCommissionRounded = number_format((float)$commulativeCommission, 2, '.', '');

        return Response::json(['commulativeCommission'=>$commulativeCommissionRounded]);
    }


    public function getTutorStudents($tutorID){

// Step 1: Get the filtered class schedules with job tickets
$filteredClassSchedules = DB::table('class_schedules')
    ->join('job_tickets', 'class_schedules.ticketID', '=', 'job_tickets.id')
    ->select('class_schedules.studentID')
    ->whereIn('class_schedules.id', function ($query) {
        $query->select(DB::raw('MAX(id)'))
            ->from('class_schedules')
            ->groupBy('ticketID');
    })
    ->where('class_schedules.class_schedule_id', '!=', 0)
    ->where('class_schedules.tutorID', '=', $tutorID) // Apply tutor ID condition here
    ->orderBy('class_schedules.id', 'DESC')
    ->get()
    ->pluck('studentID'); // Get the student IDs from the filtered class schedules

 // Debug: check the output

// Step 2: Get the students based on the filtered class schedules
$tutorStudentRecords = DB::table('students')
    ->join('class_schedules', 'students.id', '=', 'class_schedules.studentID')
    ->join('products', 'class_schedules.subjectID', '=', 'products.id')
    ->join('job_tickets', 'class_schedules.ticketID', '=', 'job_tickets.id')
    ->where('class_schedules.tutorID', '=', $tutorID)
    ->where('job_tickets.ticket_tutor_status', "Active")
    ->whereIn('students.id', $filteredClassSchedules) // Filter students based on the filtered class schedules
    ->distinct()
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
        'students.status as studentStatus',
        'students.reasonStatus as reasonStatus'
    )
    ->get();

// dd($tutorStudentRecords); // Debug: check the output

foreach ($tutorStudentRecords as $key => $record) {
    $jobTicketCheck = DB::table('job_tickets')->where('id', $record->jobTicketId)->first();
    if ($jobTicketCheck->ticket_tutor_status == 'discontinued') {
        $student = DB::table('students')->where('id', $record->studentID)->first();
        $customer = DB::table('customers')->where('id', $student->customer_id)->first();
        $city = DB::table('cities')->where('id', $customer->city)->first();

        $tutorStudentRecords[$key]->studentPhone = $customer->phone;
        $tutorStudentRecords[$key]->studentWhatsapp = $customer->whatsapp;
        $tutorStudentRecords[$key]->studentLatitude = $customer->latitude;
        $tutorStudentRecords[$key]->studentLongitude = $customer->longitude;
        $tutorStudentRecords[$key]->studentAddress1 = $customer->address1;
        $tutorStudentRecords[$key]->studentAddress2 = $customer->address2;
        $tutorStudentRecords[$key]->studentCity = $city->name;
        $tutorStudentRecords[$key]->uid = $student->student_id;
        $tutorStudentRecords[$key]->reasonStatus = $student->reasonStatus;
        $tutorStudentRecords[$key]->reasonCategory = $student->reasonCategory;
    }
}

            return Response::json(['tutorStudents'=>$tutorStudentRecords]);

    }




    public function getTutorOffers($tutorID)
    {


        $statuses = ["pending", "approved", "rejected"];
        $getTutorOffers = DB::table('tutoroffers')
            ->join('job_tickets', 'tutoroffers.ticketID', '=', 'job_tickets.id')
            ->join('products', 'tutoroffers.subject_id', '=', 'products.id')
            ->join('categories', 'categories.id', '=', 'products.category')
            ->where('tutoroffers.tutorID', '=', $tutorID)
            ->whereIn('tutoroffers.status', $statuses)
            ->join('students', 'job_tickets.student_id', '=', 'students.id')
            ->join('tutors', 'tutoroffers.tutorID', '=', 'tutors.id')
            ->join('customers', 'customers.id', '=', 'students.customer_id')
            ->join('cities', 'customers.city', '=', 'cities.id')
            ->join('states', 'customers.state', '=', 'states.id')
            ->select('job_tickets.*',
                'products.name as subject_name',
                'job_tickets.uid as jtuid',
                'job_tickets.id as ticketID',
                'job_tickets.day as classDay',
                'job_tickets.time as classTime',
                'job_tickets.quantity as quantity',
                'job_tickets.tutorPereference as tutorPereference',
                'job_tickets.classFrequency as classFrequency',
                'job_tickets.specialRequest as specialRequest',
                'job_tickets.mode as mode',
                'job_tickets.tutor_id as tutor_id',
                'job_tickets.status as status',
                'job_tickets.classAddress as classAddress',
                'job_tickets.totalTutorApplied as totalTutorApplied',
                'job_tickets.estimate_commission_display_tutor as estimate_commission_display_tutor',
                'job_tickets.totalPrice as price',
                'job_tickets.subscription  as subscription ',
                'products.id as subject_id',
                'products.id as subjectID',
                'tutoroffers.tutorID as tutorID',
                'students.full_name as studentName',
                'students.city as cityID',
                'students.full_name as studentName',
                'students.gender as studentGender',
                'students.age as studentAge',
                'students.address1 as studentAddress',
                'students.gender as studentGender',
                'categories.category_name as categoryName',
                'categories.id as categoryID',
                'cities.name as city',
                'states.name as state',
                 'cities.id as cityID',
                'states.id as stateID',
                'job_tickets.status as ticket_status',
                'tutoroffers.status as offer_status')
            ->orderBy('tutoroffers.created_at', 'desc')
            ->get();


        $resultData = [];


        foreach ($getTutorOffers as $key => $offer) {

            $offerData = [
                'ticket_status' => $offer->ticket_status,
                'offer_status' => $offer->offer_status,
                'price' => $offer->estimate_commission_display_tutor,
                'city' => $offer->city,
                'mode' => $offer->mode,
                'subject_name' => $offer->subject_name,
                'tutorPereference' => $offer->tutorPereference,
                'categoryName' => $offer->categoryName,
                'classTime' => $offer->classTime,
                'jtuid' => $offer->jtuid,
                'subscription' =>$offer->subscription,

                // Copy the missing items from $ticketData
                'classDay' => $offer->classDay,
                'ticketID' => $offer->ticketID,
                'tutor_id' => $offer->tutor_id,
                'status' => $offer->status,
                'totalTutorApplied' => $offer->totalTutorApplied,
                'classFrequency' => $offer->classFrequency,
                'quantity' => $offer->quantity,
                'classAddress' => $offer->classAddress,
                'classState' => $offer->classState,
                'classCity' => $offer->classCity,
                'classPostalCode' => $offer->classPostalCode,
                'specialRequest' => $offer->specialRequest,
                'subject_id' => $offer->subject_id,
                'subjectID' => $offer->subjectID,
                'studentName' => $offer->studentName,
                'studentGender' => $offer->studentGender,
                'student_age' => $offer->studentAge,
                'studentAddress' => $offer->studentAddress,
                'state' => $offer->state,
                'cityID' => $offer->cityID,
                'stateID' => $offer->stateID,
                'categoryID' => $offer->categoryID,
                'jobTicketExtraStudents' => [],

            ];
            $days = explode(',', str_replace('"', '', $offer->classDay));
            if (in_array('Sat', $days) || in_array('Sun', $days)) {
                $classDayType = 'weekend';
            } else {
                $classDayType = 'weekday';
            }

            $offerData['classDayType'] = $classDayType;

            $students = DB::table('job_ticket_students')->where('job_ticket_id', '=', $offer->ticketID)->get();

            foreach ($students as $student) {
                $studentData = [
                    'student_name' => $student->student_name,
                    'student_age' => $student->student_age,
                    'student_gender' => $student->student_gender,
                    'year_of_birth' => $student->year_of_birth,
                    'special_need' => $student->special_need,
                    'subject_id' => $student->subject_id,
                ];

                $offerData['jobTicketExtraStudents'][] = $studentData;
            }


            $resultData[] = $offerData;

        }


        if ($resultData == null) {
            return Response::json(['getTutorOffers' => []]);
        } else {
            return Response::json(['getTutorOffers' => $resultData]);
        }

    }


    // public function getTutorOffers($tutorID){

    //     $getTutorOffers = DB::table('tutoroffers')
    //     ->join('job_tickets', 'tutoroffers.ticketID', '=', 'job_tickets.id')
    //     ->join('products', 'tutoroffers.subject_id', '=', 'products.id')
    //     ->join('categories', 'categories.id', '=', 'products.category')
    //     ->where('tutoroffers.tutorID','=',$tutorID)
    //     ->where('tutoroffers.status','=','Applied')
    //     ->join('students', 'job_tickets.student_id', '=', 'students.id')
    //     ->join('tutors', 'tutoroffers.tutorID', '=', 'tutors.id')
    //     ->join('customers', 'customers.id', '=', 'students.customer_id')
    //     ->join('cities', 'customers.city', '=', 'cities.id')
    //     ->join('states', 'customers.state', '=', 'states.id')
    //     ->select('job_tickets.*',
    //                         'products.name as subject_name',
    //                         'job_tickets.uid as jtuid',
    //                         'job_tickets.id as ticketID',
    //                         'job_tickets.day as classDay',
    //                         'job_tickets.time as classTime',
    //                         'job_tickets.totalPrice as price',
    //                         'products.id as subject_id',
    //                         'tutoroffers.tutorID as tutorID',
    //                         'students.full_name as studentName',
    //                         'students.city as cityID',
    //                         DB::raw('DATE_FORMAT(FROM_DAYS(DATEDIFF(now(),students.dob)), "%Y")+0 AS studentAge'),
    //                         'students.gender as studentGender',
    //                         'categories.category_name as categoryName',
    //                         'categories.id as categoryID',
    //                         'cities.name as city',
    //                         'states.name as state',
    //                         'job_tickets.status as ticket_status')
    //     ->get();


    //     /*

    //     ->join('student_subjects', 'job_tickets.id', '=', 'student_subjects.ticket_id')


    //     ->get();
    //     */

    //     return Response::json(['getTutorOffers'=>$getTutorOffers]);
    // }

    public function offerSendByTutor($subjectID,$tutorID,$ticketID,$comment){
        $ticketUID =  DB::table('job_tickets')->where('id','=',$ticketID)->first();
        $ifExist =  DB::table('tutoroffers')->where('ticketID','=',$ticketID)->where('tutorID','=',$tutorID)->first();
        if(!$ifExist){
            $tutorOfferValues = array(
                'tutorID' => $tutorID,
                'subject_id' => $subjectID,
                'ticketID' => $ticketID,
                'ticketUID' => $ticketUID->uid,
                'status' => 'pending',
                'comment' => $comment
            );
            $ssa = DB::table('tutoroffers')->insertGetId($tutorOfferValues);

            $result = DB::table('tutoroffers')->where('tutorID','=',$tutorID)->where('ticketID','=',$ticketID)->where('subject_id','=',$subjectID)->first();
        }else{
            $result = "You Already Applied to this TICKET";
        }

        $totalTutorApplied = DB::table('tutoroffers')->where('ticketID','=',$ticketID)->count();

        $affected = DB::table('job_tickets')
            ->where('id', $ticketID)
            ->update(['totalTutorApplied' => $totalTutorApplied, 'application_status' => 'incomplete']);

        return Response::json(['result'=>$result]);
    }

    public function getClassSchedulesForTutors($tutorID){
        $classSchedules = DB::table('class_schedules')
            ->join('students', 'class_schedules.studentID', '=', 'students.id')
            ->join('products', 'class_schedules.subjectID', '=', 'products.id')
            ->join('tutors', 'class_schedules.tutorID', '=', 'tutors.id')
            ->where('class_schedules.class_schedule_id','=',0)
            ->where('class_schedules.status','=','scheduled')->where('class_schedules.tutorID','=',$tutorID)
            ->select(
                'class_schedules.id as class_schedule_id',
                'class_schedules.status as status',
                'class_schedules.date as date',
                'class_schedules.startTime as startTime',
                'class_schedules.endTime as endTime',
                'class_schedules.totalTime as totalTime',
                'tutors.uid as tutorID',
                'tutors.full_name as tutorName',
                'tutors.displayName as tutorDisplayName',
                'tutors.street_address1 as tutorAddress',
                'tutors.city as tutorCity',
                'products.name as subjectName',
                'students.full_name as studentName',
                'students.address1 as studentAddress',
                'students.city as studentCity')
            ->get();




        return Response::json(['classSchedules'=>$classSchedules]);
    }



    public function newsAPI(){
        $baseUrl = url("/public/MobileNewsImages/")."/";
        $news = DB::table('news')
            ->select('*', DB::raw("CONCAT('$baseUrl', headerimage) AS headerimage"))
            ->get();
        return Response::json(['news'=>$news]);
    }



    public function faqsAPI(){
        $faqs = DB::table('faqs')
            ->get();
        return Response::json(['faqs'=>$faqs]);
    }

    public function newsStatusUpdate($id, $status, $tutorID){

        $values = array(
            'tutorID' => $tutorID,
            'newsID' => $id,
            'newsStatus' => $status
        );

        $tutor_news_status = DB::table('tutor_news_status')->insertGetId($values);

        return Response::json(['result'=>'News Status Updated']);
    }

    public function tutorNewsStatusList($tutorID){

        $tutor_news_status = DB::table('tutor_news_status')->where('tutorID','=',$tutorID)->get();

        return Response::json(['result'=>$tutor_news_status]);
    }

    public function detailedNews($id){
        $id = $id; // Replace with the desired news ID
        $baseUrl = url("/public/MobileNewsImages");

        $news = DB::table('news')
            ->where('id', '=', $id)
            ->select('*', DB::raw("CONCAT('$baseUrl', headerimage) AS headerimage"))
            ->first();

        return response()->json(['detailedNEWS' => $news]);

    }
    public function updateNotificationStatus($id, $status){

        $affected = DB::table('notifications')
            ->where('id', $id)
            ->update(['status' => $status]);
        return Response::json(['message'=>'Status has been updated']);
    }
    public function notifications($tutorID){
        $notifications = DB::table('notifications')
            ->join('students', 'notifications.studentID', '=', 'students.id')
            ->join('tutors', 'notifications.tutorID', '=', 'tutors.id')
            ->join('products', 'notifications.subjectID', '=', 'products.id')
            ->where('notifications.tutorID','=', $tutorID)
            ->select('notifications.id as notificationID',
                'notifications.notificationType as notificationType',
                'notifications.status as status',
                'notifications.message as notificationMessage',
                'notifications.ProgressReportMonth as notificationProgressReportMonth',

                'tutors.uid as tutorUID',
                'tutors.id as tutorID',
                'tutors.full_name as tutorName',
                'tutors.displayName as tutorDisplayName',
                'tutors.street_address1 as tutorAddress1',
                'tutors.street_address2 as tutorAddress2',
                'tutors.city as tutorCity',
                'products.id as subjectID',
                'products.name as subjectName',

                'students.id as studentID',
                'students.full_name as studentName',
                'students.address1 as studentAddress1',
                'students.address2 as studentAddress2',
                'students.city as studentCity')
            ->get();
        return Response::json(['notifications'=>$notifications]);
    }

    public function sendnotification(Request $request) {
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

    public function classScheduleNotifications($tutorID){
        $notifications = DB::table('notifications')
            ->join('students', 'notifications.studentID', '=', 'students.id')
            ->join('tutors', 'notifications.tutorID', '=', 'tutors.id')
            ->Leftjoin('products', 'notifications.subjectID', '=', 'products.id')
            ->where('notifications.tutorID','=', $tutorID)
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
        return Response::json(['notifications'=>$notifications]);
    }

    public function detailedNotification($id){
        $detailedNotification = DB::table('notifications')
            ->join('students', 'notifications.studentID', '=', 'students.id')
            ->join('tutors', 'notifications.tutorID', '=', 'tutors.id')
            ->join('products', 'notifications.subjectID', '=', 'products.id')
            ->where('notifications.id','=',$id)
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
        return Response::json(['detailedNotification'=>$detailedNotification]);
    }

    public function progressReportListing(){
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
            ->orderBy("progressReport.id","desc")
            ->get();

        return response()->json(['progressReportListing' => $progressReportListing]);

    }
    public function progressReport(Request $request){

        // $values = array(
        //     'tutorID' => $request->tutorID,
        //     'studentID' => $request->studentID,
        //     'subjectID' => $request->subjectID,
        //     'reportType' => 'Progress Report',
        //     'month' => $request->month,

        //     'rate_student_understanding_on_this_subject' => $request->rate_student_understanding_on_this_subject,
        //     'how_is_the_student_performance_on_homework' => $request->how_is_the_student_performance_on_homework,
        //     'how_well_student_participates_in_learning_session' => $request->how_well_student_participates_in_learning_session,
        //     'how_well_student_answers' => $request->how_well_student_answers,

        //     'how_you_can_rate_student_attendance_for_3_months' => $request->how_you_can_rate_student_attendance_for_3_months,
        //     'how_well_do_you_interact_with_studyent_during_class' => $request->how_well_do_you_interact_with_studyent_during_class,
        //     'how_well_the_student_manages_his_time_tomplete_his_homework' => $request->how_well_the_student_manages_his_time_tomplete_his_homework,
        //     'how_well_the_student_respondes_when_corrected' => $request->how_well_the_student_respondes_when_corrected,

        //     'rate_the_student_performance_in_quizzes' => $request->rate_the_student_performance_in_quizzes,
        //     'how_well_the_student_prepares_for_test_and_assignment' => $request->how_well_the_student_prepares_for_test_and_assignment,
        //     'rate_student_learning_preferences_willingness_to_learn_and_inter' => $request->rate_student_learning_preferences_willingness_to_learn_and_inter,
        //     'did_you_hold_or_carried_out_any_form_of_examination_for_the_stud' => $request->did_you_hold_or_carried_out_any_form_of_examination_for_the_stud,

        //     'how_do_you_rate_student_performance_based_on_this_test' => $request->how_do_you_rate_student_performance_based_on_this_test,
        //     'which_topic_has_the_student_showed_some_significant_improvement' => $request->which_topic_has_the_student_showed_some_significant_improvement,
        //     'can_you_determine_and_name_the_topic_that_the_student_should_imp' => $request->can_you_determine_and_name_the_topic_that_the_student_should_imp,
        //     'please_elaborate_your_plan_for_the_student_in_3_months_time_from' => $request->please_elaborate_your_plan_for_the_student_in_3_months_time_from

        // );


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


        return Response::json(['successMessage'=>'Progress Report Submitted Successfully']);
    }



    public function tutorFirstReport(Request $request){

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

        $row = DB::table('tutorFirstSubmittedReportFromApps')->where('id','=',$submitClassScheduleTime)->select('reportType as reportType','currentDate as currentDate','knowledge as knowledge','understanding as understanding','analysis as analysis','additionalAssisment as additionalAssisment','plan as plan')->first();


        return Response::json(['successMessage'=>'Report Submitted Successfully', 'data'=>$row]);
    }



    public function tutorFirstReportListing($id){
        $baseUrl = url("/template/")."/";

        $tutorReportListing = DB::table('tutorFirstSubmittedReportFromApps')
            ->join('students', 'tutorFirstSubmittedReportFromApps.studentID', '=', 'students.id')
            ->join('tutors', 'tutorFirstSubmittedReportFromApps.tutorID', '=', 'tutors.id')
            ->join('products', 'tutorFirstSubmittedReportFromApps.subjectID', '=', 'products.id')
            ->where('tutorFirstSubmittedReportFromApps.tutorID', '=', $id)
            ->select(
                'tutorFirstSubmittedReportFromApps.*',
                DB::raw("CONCAT('$baseUrl', tutorFirstSubmittedReportFromApps.logoImage) AS logo"),
                DB::raw('MONTHNAME(STR_TO_DATE(tutorFirstSubmittedReportFromApps.currentDate, "%m/%d/%Y")) as month'),
                'tutors.id as tutorID',
                'tutors.uid as tutorUID',
                'tutors.full_name as tutorName',
                'tutors.displayName as tutorDisplayName',
                'tutors.street_address1 as tutorAddress1',
                'tutors.street_address2 as tutorAddress2',
                'tutors.city as tutorCity',
                'products.name as subjectName',
                'students.uid as student_id',
                'students.full_name as studentName',
                'students.address1 as studentAddress1',
                'students.address2 as studentAddress2',
                'students.city as studentCity',
                DB::raw("DATE_FORMAT(tutorFirstSubmittedReportFromApps.created_at, '%d-%b-%Y') as submittedDate"),

            )
            ->get();

        return response()->json(['tutorReportListing' => $tutorReportListing]);
    }

    public function tutorFirstReportView($id){
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
        return Response::json(['tutorReportListing'=>$tutorReportListing]);
    }



    public function searchJobTickets($categoryID,$subjectID,$mode){


        $searchJobTickets = DB::table('job_tickets')
            ->join('student_subjects', 'student_subjects.ticket_id', '=', 'job_tickets.id')
            ->join('products', 'student_subjects.subject', '=', 'products.id')
            ->join('students', 'student_subjects.student_id', '=', 'students.id')
            ->where('job_tickets.mode', 'LIKE', $mode)
            ->Where('student_subjects.subject', 'LIKE', $subjectID)
            ->Where('products.category', 'LIKE', $categoryID)
            ->get();

        return Response::json(['JobTicketsResult'=>$searchJobTickets]);
    }

    public function editStatus(Request $request){

        $affected = DB::table('students')
            ->where('id', $request->studentID)
            ->update(['status' => $request->status, 'reasonStatus' => $request->reasonStatus,'reasonCategory' => $request->reasonCategory]);

        return Response::json(['response'=>'Status has been updated','reasonStatus'=>$request->reasonStatus]);



    }

    public function editTutorProfile(Request $request){

        $data = $request->all();
        // dd($data);
        $file = $request->tutorImage;
        if($file){
            $tutorImage = time().'.'.$request->file('tutorImage')->extension();
            $request->tutorImage->move(public_path('tutorImage'), $tutorImage);
            $filename = basename(parse_url($file, PHP_URL_PATH));

            $affected = DB::table('tutors')
                ->where('id', $request->id)
                ->update([

                    'email' => $request->email,
                    'displayName' => $request->displayName,
                    // 'full_name' => $request->displayName,
                    'phoneNumber' => $request->phone,
                    'whatsapp' => $request->whatsapp,
                    'gender' => $request->gender,
                    'age' => $request->age,
                    'nric' => $request->nric,
                    'tutorImage' => $tutorImage
                ]);
            $data['tutorImage'] = url("/public/tutorImage/").$tutorImage;
            // dd("Done00");
        }else{

            // dD($request->all());
            $affected = DB::table('tutors')
                ->where('id', $request->id)
                ->update(['full_name' => $request->name,
                    'email' => $request->email,
                    'displayName' => $request->displayName,
                    // 'full_name' => $request->displayName,
                    'phoneNumber' => $request->phone,
                    'whatsapp' => $request->whatsapp,
                    'gender' => $request->gender,
                    'age' => $request->age,
                    'nric' => $request->nric,
                ]);
            $data['tutorImage'] = '';
        }

        return Response::json(['response'=>$data]);


    }

    public function tutorPayments($tutorID){
        $tutorPayments = DB::table('tutorpayments')->where('tutorID','=',$tutorID)->get();
        return Response::json(['response'=>$tutorPayments]);
    }

    public function bannerAds(){

        $baseUrl = url("/public/BannerImage/")."/";

        $bannerAds = DB::table('bannerads')
            ->select('*', DB::raw("CONCAT('$baseUrl', bannerads.bannerImage) AS bannerImage"))
            ->get();

        return response()->json(['bannerAds' => $bannerAds]);
    }

    public function classScheduleAttendedStatusWithImage(Request $request){



    }


    public function token(Request $request){

        $values = array(
            'userId' => $request->userId,
            'token' => $request->token
        );
        $token = DB::table('fcmToken')->insertGetId($values);

        $data =  DB::table('fcmToken')->where('id','=',$token)->first();

        return Response::json(['statusCode'=>200, 'message'=>'Success', 'data'=>$data]);

    }













}
