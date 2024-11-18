<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Libraries\WhatsappApi;
use Barryvdh\DomPDF\Facade\Pdf;
use Pusher\Pusher;
use App\Events\MobileHomePageUpdated;


class ClassSchedulesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


   public function approveDispute($id)
    {

        $job_ticket=DB::table("class_attendeds")->
       where(["class_schedule_id"=>$id,"status"=>"dispute"])->
       first();


        $student_id=$job_ticket->studentID;
        $customer_id=DB::table("customers")->where("id",$student_id)->first();
        $customer_email=$customer_id->email;
        $job_ticket_data=DB::table("job_tickets")->where("id",$job_ticket->ticketID)->first();



        //Email work start
        $to_email = "binasift@gmail.com";
        $subject="Class Dispute Status Update";

        $header = "MIME-Version: 1.0" . "\r\n";
        $header .= "Content-type: multipart/mixed; boundary=\"boundary\"\r\n";
        // More headers
        $header .= 'From: <tutor@sifututor.com>' . "\r\n";

        // Attachment
        $emailBody = "";
        $emailBody .= '</tbody>
                    </table>
                    <table class="table table-responsive no-border">
                    <tbody>
                    <tr>
                        <td>
                         Dear Parent your dispute for ticket ID:'.$job_ticket_data->uid. '  been approved.
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




        mail($to_email, $subject, $emailBody, $header);
        mail($customer_email, $subject, $emailBody, $header);
        //Email work End

        dd("Done");



       $class_attended=DB::table("class_attendeds")->
       where(["class_schedule_id"=>$id,"status"=>"dispute"])->
       first();
        //updating class attendeds
       DB::table("class_attendeds")->
       where("id",$class_attended->id)->
       update(["status"=>"dispute","totalTime"=>"00:00:00","commission"=>0]);

       //updating class schedule
       DB::table("class_schedules")->where("id",$id)->
       update(["status"=>"dispute","totalTime"=>"0"]);
       return redirect()->back();
    }

    public function rejectDispute($id)
    {
        $class_attended=DB::table("class_attendeds")->
        where(["class_schedule_id"=>$id,"status"=>"dispute"])->
        first();

         $job_ticket=DB::table("class_attendeds")->
       where(["class_schedule_id"=>$id,"status"=>"dispute"])->
       first();


        $student_id=$job_ticket->studentID;
        $customer_id=DB::table("customers")->where("id",$student_id)->first();
        $customer_email=$customer_id->email;
        $job_ticket_data=DB::table("job_tickets")->where("id",$job_ticket->ticketID)->first();



        //Email work start
        $to_email = "binasift@gmail.com";
        $subject="Class Dispute Status Update";

        $header = "MIME-Version: 1.0" . "\r\n";
        $header .= "Content-type: multipart/mixed; boundary=\"boundary\"\r\n";
        // More headers
        $header .= 'From: <tutor@sifututor.com>' . "\r\n";

        // Attachment
        $emailBody = "";
        $emailBody .= '</tbody>
                    </table>
                    <table class="table table-responsive no-border">
                    <tbody>
                    <tr>
                        <td>
                         Dear Parent your dispute for ticket ID:'.$job_ticket_data->uid. '  been rejected.
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




        mail($to_email, $subject, $emailBody, $header);
        mail($customer_email, $subject, $emailBody, $header);
        //Email work End

       dd("Done");

        //updating class attendeds
        DB::table("class_attendeds")->
        where("id",$class_attended->id)->
        update(["status"=>"attended"]);

        //updating class schedule
        DB::table("class_schedules")->where("id",$id)->
        update(["status"=>"attended"]);
        return redirect()->back();
    }



    public function checkSchedule(Request $req)
    {


        // dd($req->all());
        $dateInput = $req->dateInput;
        $startTime = $req->startTime;
        $endTime = $req->endTime; // Add this line
        $tutorId = $req->tutorId;




        // Step 1: Check for existing record with the given date and tutor ID
        $existingRecord = DB::table("class_schedules")
            ->where('date', '=', $dateInput)
            ->where('tutorId', '=', $tutorId)
            ->where('status', '!=', 'attended')
            ->first();

            //  dd($existingRecord);
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
            return response()->json(["recordFound" => true], 200);
        } else {
            return response()->json(["recordFound" => false], 200);
        }
    }

    public function index(Request $request)
    {

       $class_schedules = DB::table('class_schedules')
    ->join('job_tickets', 'class_schedules.ticketID', '=', 'job_tickets.id')
    ->select('class_schedules.*', 'job_tickets.*') // Select columns from both tables
    ->whereIn('class_schedules.id', function ($query) {
        $query->select(DB::raw('MAX(id)'))
            ->from('class_schedules')
            ->groupBy('ticketID');
    })
    ->where('class_schedules.class_schedule_id', '!=', 0)
    ->orderBy('class_schedules.id', 'DESC')
    ->get();

        // dd($class_schedules);

        if ($request->search != null) {

            $name = $request->search;
            $tutor_name = DB::table('students')->where('full_name', '=', $name)->first();
            $class_schedules = $class_schedules->where('studentID', '=', $tutor_name->id);
        }
        if ($request->status != null) {
            $class_schedules = $class_schedules->where('status', '=', $request->status);
        }
        
        $statuses = DB::table('class_schedules')->select('status')->distinct()->get();

        // dd($class_schedules);
        return view('classSchedule.index', Compact('class_schedules', 'statuses'));


        // $class_schedules = $class_schedules->where('class_schedule_id','=',0)->orderBy('id','DESC')->get();
        // $class_schedules = $class_schedules->where('class_schedule_id', '!=', 0)->orderBy('id', 'DESC')->get();

        // return view('classSchedule.index', Compact('class_schedules'));


    }


    // public function viewClassSchedules($id)
    // {

    //     $class_schedulesByID = DB::table('class_schedules')->where('id','=',$id)->orderBy('id','DESC')->get();

    //     $subjectDetail = DB::table('products')->where('id','=',$class_schedulesByID[0]->subjectID)->first();

    //     $studentDetail = DB::table('students')->where('id','=',$class_schedulesByID[0]->studentID)->first();
    //     $tutorDetail = DB::table('tutors')->where('id','=',$class_schedulesByID[0]->tutorID)->first();
    //     $tickets = DB::table('job_tickets')->where('id','=',$class_schedulesByID[0]->ticketID)->first();
    //     $tutors = DB::table('tutors')->get();
    //     $subjects = DB::table('products')->get();
    //     return view('classSchedule/viewClassSchedule',Compact('class_schedulesByID','subjectDetail','studentDetail','tutorDetail','tickets','tutors','subjects'));
    // }


    public function viewClassSchedules($id)
    {



        $class_schedulesByID = DB::table('class_schedules')->where('ticketID', '=', $id)->orderBy('id', 'DESC')->get();



        // dd($class_schedulesByID);

        $subjectDetail = DB::table('products')->where('id', '=', $class_schedulesByID[0]->subjectID)->first();



        $studentDetail = DB::table('students')->where('id', '=', $class_schedulesByID[0]->studentID)->first();
        $tutorDetail = DB::table('tutors')->where('id', '=', $class_schedulesByID[0]->tutorID)->first();
        $tickets = DB::table('job_tickets')->where('id', '=', $class_schedulesByID[0]->ticketID)->first();


        $tutors = DB::table('tutors')->get();
        $subjects = DB::table('products')->get();

        $total_attended_hours = DB::table('class_schedules')->where('ticketID', '=', $id)->
        where('status', '=', "attended")
            ->sum('totalTime');
        $total_subscribed_hours = $tickets->quantity * $tickets->classFrequency;

        $classScheduleId = $class_schedulesByID[0]->class_schedule_id;


        // dd($tickets);
        return view('classSchedule/viewClassSchedule', Compact('class_schedulesByID', 'subjectDetail', 'classScheduleId', 'studentDetail', 'tutorDetail', 'tickets', 'tutors', 'subjects', 'total_subscribed_hours', 'total_attended_hours'));
    }


    public function fetchClassSchedules()
    {

        $classes = DB::table('student_subjects')->where('tutor_id', '!=', NULL)->select('day', 'created_at');
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


    public function submitEditClassSchedules(Request $request)
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
            'totalTime' => $differenceInHours
        );


        DB::table('class_schedules')->where("id",$request->classScheduleID)->update($tutorOfferValues);


        return redirect()->back();
    }


    public function submitCheckIn(Request $request)
    {

        $getClassSchedule = DB::table('class_schedules')->where('subjectID', '=', $request->subjectID)->where('ticketID', '=', $request->ticketID)->first();
        if ($getClassSchedule) {
            // if ($request->has_incentive == "on") {
            //     $request->validate([
            //         'signInProof' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            //     ]);

            //     $imageNameSignInProof = time() . '.' . $request->signInProof->extension();

            //     $request->signInProof->move(public_path('signInProof'), $imageNameSignInProof);
            //     $values = array(
            //         'tutorID' => $request->tutorID,
            //         'class_schedule_id' => $request->classScheduleID,
            //         'csID' => $request->csID,
            //         'studentID' => $request->studentID,
            //         'subjectID' => $request->subjectID,
            //         'ticketID' => $request->ticketID,
            //         'date' => $request->date,
            //         'startTime' => $request->start_time,
            //         'endTime' => $request->end_time,
            //         'totalTime' => 0,
            //         'startTimeProofImage' => $imageNameSignInProof,

            //         'hasIncentive' => 1,
            //     );
            //     $customerLastID = DB::table('class_attendeds')->insertGetId($values);
            // } else {

                // $request->validate([
                //     'signInProof' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                // ]);

                $imageNameSignInProof = time() . '.' . $request->signInProof->extension();

                $request->signInProof->move(public_path('signInProof'), $imageNameSignInProof);


                $values = array(
                    'tutorID' => $request->tutorID,
                    'class_schedule_id' => $request->classScheduleID,
                    'csID' => $request->csID,
                    'studentID' => $request->studentID,
                    'subjectID' => $request->subjectID,
                    'ticketID' => $request->ticketID,
                    'date' => $request->date,
                    'startTime' => $request->start_time,
                    'endTime' => $request->end_time,
                    'totalTime' => 0,

                    'startTimeProofImage' => $imageNameSignInProof,
                    //'endTimeProofImage' => $imageNameSignoutProof,
                    'hasIncentive' => 0,
                );
                $customerLastID = DB::table('class_attendeds')->insertGetId($values);

            // }
             DB::table('class_schedules')->where('id', '=', $request->classScheduleID)->update(["status"=>"On going"]);
        }
        return redirect()->back();
    }


    public function submitCheckOut(Request $request)
    {




        // $request->validate([
        //     'signOutProof' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        // ]);
        $imageNameSignoutProof = time() . '.' . $request->signOutProof->extension();
        $request->signOutProof->move(public_path('signOutProof'), $imageNameSignoutProof);

        $sTime = $request->start_time;
        $eTime = $request->end_time;
        $t1 = strtotime($sTime);
        $t2 = strtotime($eTime);
        $differenceInSeconds = $t2 - $t1;
        $differenceInHours = $differenceInSeconds / 3600;

        if ($differenceInHours < 0) {
            $differenceInHours += 24;
        }


        $getClassSchedule=DB::table("class_schedules")->where("id",$request->classScheduleID)->first();
        
         $class_schedule=DB::table("class_schedules")->where("id",$request->classScheduleID)->first();

        $job_ticket= DB::table("job_tickets")->where("id",$getClassSchedule->ticketID)->first();

        //commission work
       $totalAttendedHours = DB::table('class_schedules')
    ->where('class_schedule_id', '=', $request->csID)
    ->where("status", "attended")
    ->sum('totalTime');

// Add the new class schedule's time to the total attended hours
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
   
       //commission work end





        // dd($getClassSchedule->totalTime);
        DB::table('class_attendeds')
            ->where('id', $request->id)
            ->update(['endTime' => $request->end_time, 'endTimeProofImage' => $imageNameSignoutProof, 'totalTime' => $getClassSchedule->totalTime,'status'=>"pending", 'commission'=>$per_class_commission]);

        DB::table('class_schedules')
            ->where('id', $request->classScheduleID)
            ->update(['status' => "pending"]);


        //   dd($request->all());

            //Email work start
        $studentName = DB::table('students')->where('id', '=', $getClassSchedule->studentID)->first();
        $subjectName = DB::table('products')->where('id', '=', $getClassSchedule->subjectID)->first();
        $student_data = DB::table('students')->where('id', '=', $getClassSchedule->studentID)->get()->first();

        $customer_data = DB::table('customers')->where('id', '=', $student_data->customer_id)->get()->first();

        $attendedRecord = DB::table('class_attendeds')->where('id', '=', $request->id)->first();





        // dd($getClassSchedule);

    //   dd($attendedRecord);


        if ($customer_data->email != null) {
            $to = $customer_data->email;
            $emailTwo = "binasift@gmail.com";

        } else {
            $to = $customer_data->email;
            $emailTwo = "binasift@gmail.com";

        }

         $subject = "Attendance Report at:" . date('Y-m-d H:i:s');

        $agreePath = url("/")."/agreeAttendance"."/". $attendedRecord->id;
        $disagreePath = url("/")."/disputeAttendance". "/".$attendedRecord->id;
        $total_time_attended=$attendedRecord->totalTime;
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

      <p>Dear Parents/Guardians,</p>

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
        <strong>Dear parents/guardians, we appreciate your verification.</strong>
      </p>

      <p>
        Click Agree if you agree, otherwise click Disagree<br>
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
        //Email work End
//  dd($emailTwo);
        return redirect()->back();
    }


 public function submitStatus(Request $request)

    {

        // dd($request->all());


        $class_schedule=DB::table("class_schedules")->where("id",$request->classScheduleID)->first();


            //commission calculation work
            $job_ticket= DB::table("job_tickets")->where("id",$class_schedule->ticketID)->first();
            // $total_sessions=$job_ticket->quantity*$job_ticket->classFrequency;
            // $total_commission=$job_ticket->estimate_commission;
            // $per_class_commission=$total_commission/$total_sessions;
            // $per_class_commission=$per_class_commission*$job_ticket->quantity;
           //commission calculation work End


           //commission work
           // Fetch total attended hours for the class schedule
$totalAttendedHours = DB::table('class_schedules')
    ->where('class_schedule_id', '=', $request->csID)
    ->where("status", "attended")
    ->sum('totalTime'); // Assuming 'totalTime' is the field that holds the duration for each attended class schedule

// Add the new class schedule's time to the total attended hours
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
           //commission work end
           
        //   dd($per_class_commission);

        $attendedRecord = DB::table('class_attendeds')->where('id', '=', $request->id)->first();
        if($attendedRecord==null)
        {


            $getClassSchedule = DB::table('class_schedules')->where('id','=',$class_schedule->id)->first();
            $jobTicket=DB::table("job_tickets")->where("id",$getClassSchedule->ticketID)->first();




            $endTime = $class_schedule->endTime;
            $sTime = $getClassSchedule->startTime;
            $eTime = $endTime;
            $t1  = strtotime($sTime);
            $t2 = strtotime($eTime);
            $differenceInSeconds = $t2 - $t1;
            $differenceInHours = $differenceInSeconds / 3600;
            $totalTime = $differenceInHours;
            if($differenceInHours<0) {
                $differenceInHours += 24;
                $totalTime = $differenceInHours;
            }

            $totalTimeInHours = $totalTime; // Total time in hours

            // Convert hours to seconds
            $totalTimeInSeconds = $totalTimeInHours * 3600;

            // Calculate hours, minutes, and seconds
            $hours = floor($totalTimeInSeconds / 3600);
            $minutes = floor(($totalTimeInSeconds % 3600) / 60);
            $seconds = $totalTimeInSeconds % 60;

            // Format the time with leading zeros
            $formattedTime = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);

            // dd($formattedTime);


            $values = array(
                'tutorID' => $getClassSchedule->tutorID,
                'class_schedule_id' => $getClassSchedule->id,
                'csID' => $getClassSchedule->class_schedule_id,
                'studentID' => $getClassSchedule->studentID,
                'subjectID' => $getClassSchedule->subjectID,
                'ticketID' => $getClassSchedule->ticketID,
                'date' => $getClassSchedule->date,
                'startTime' => $sTime,
                'endTime' => $endTime,
                'status' => $request->classStatus,
                'totalTime' => $formattedTime,
                'commission' => $per_class_commission,
                 'parent_verified' => "YES"

            );
           $attendedRecord = DB::table('class_attendeds')->insertGetId($values);

            $attendedRecord = DB::table('class_attendeds')->where("id",$attendedRecord)->first();


            $subjectPrice = DB::table('products')->where('id', '=', $request->subjectID)->first();

            DB::table('class_attendeds')
                ->where('id', $request->id)
                ->update(['status' => $request->classStatus, 'subjectPrice' => $subjectPrice->price]);

            DB::table('class_schedules')
                ->where('id', $request->classScheduleID)
                ->update(['status' => $request->classStatus, 'subjectPrice' => $subjectPrice->price]);

            $studentinvoices = DB::table('studentinvoices')->where('ticketID', '=', $attendedRecord->ticketID)->where('studentID', '=', $attendedRecord->studentID)->where('subjectID', '=', $request->subjectID)->first();

            $tutorinvoices = DB::table('tutorinvoices')->where('ticketID', '=', $attendedRecord->ticketID)->where('studentID', '=', $attendedRecord->studentID)->where('subjectID', '=', $request->subjectID)->first();

            $studentinvoiceItemValues = array(
                'classScheduledID' => $attendedRecord->csID,
                'csAttendedID' => $attendedRecord->id,
                'studentinvoiceID' => $studentinvoices->id,
                'date' => $attendedRecord->date,
                'startTime' => $attendedRecord->startTime,
                'endTime' => $attendedRecord->endTime,
                'totalTime' => $attendedRecord->totalTime,
                'hasIncentive' => $attendedRecord->hasIncentive,
            );
            $studentinvoice_items = DB::table('studentinvoice_items')->insertGetId($studentinvoiceItemValues);

            $tutorinvoiceItemValues = array(
                'csAttendedID' => $attendedRecord->id,
                'classScheduledID' => $attendedRecord->csID,
                'tutorinvoiceID' => $tutorinvoices->id,
                'date' => $attendedRecord->date,
                'startTime' => $attendedRecord->startTime,
                'endTime' => $attendedRecord->endTime,
                'totalTime' => $attendedRecord->totalTime,
                'hasIncentive' => $attendedRecord->hasIncentive,
            );
            $tutorinvoice_items = DB::table('tutorinvoice_items')->insertGetId($tutorinvoiceItemValues);

        }
        else{
            $subjectPrice = DB::table('products')->where('id', '=', $request->subjectID)->first();

            DB::table('class_attendeds')
                ->where('id', $request->id)
                ->update(['status' => $request->classStatus, 'subjectPrice' => $subjectPrice->price, 'commission' => $per_class_commission]);

            DB::table('class_schedules')
                ->where('id', $request->classScheduleID)
                ->update(['status' => $request->classStatus, 'subjectPrice' => $subjectPrice->price]);

            $studentinvoices = DB::table('studentinvoices')->where('ticketID', '=', $attendedRecord->ticketID)->where('studentID', '=', $attendedRecord->studentID)->where('subjectID', '=', $request->subjectID)->first();

            $tutorinvoices = DB::table('tutorinvoices')->where('ticketID', '=', $attendedRecord->ticketID)->where('studentID', '=', $attendedRecord->studentID)->where('subjectID', '=', $request->subjectID)->first();

            $studentinvoiceItemValues = array(
                'classScheduledID' => $attendedRecord->csID,
                'csAttendedID' => $attendedRecord->id,
                'studentinvoiceID' => $studentinvoices->id,
                'date' => $attendedRecord->date,
                'startTime' => $attendedRecord->startTime,
                'endTime' => $attendedRecord->endTime,
                'totalTime' => $attendedRecord->totalTime,
                'hasIncentive' => $attendedRecord->hasIncentive,
            );
            $studentinvoice_items = DB::table('studentinvoice_items')->insertGetId($studentinvoiceItemValues);

            $tutorinvoiceItemValues = array(
                'csAttendedID' => $attendedRecord->id,
                'classScheduledID' => $attendedRecord->csID,
                'tutorinvoiceID' => $tutorinvoices->id,
                'date' => $attendedRecord->date,
                'startTime' => $attendedRecord->startTime,
                'endTime' => $attendedRecord->endTime,
                'totalTime' => $attendedRecord->totalTime,
                'hasIncentive' => $attendedRecord->hasIncentive,
            );
            $tutorinvoice_items = DB::table('tutorinvoice_items')->insertGetId($tutorinvoiceItemValues);
        }


        if($request->classStatus=="cancelled" || $request->classStatus=="postponed")
        {



            $job_ticket=DB::table("job_tickets")->where("id", $class_schedule->ticketID)->first();
            $remainingClasses=$job_ticket->remaining_classes;
            $remainingClasses=$remainingClasses+1;
            DB::table("job_tickets")->where("id", $job_ticket->id)->update(["remaining_classes"=>$remainingClasses]);

            DB::table('student_subjects')
                ->where('ticket_id',  $job_ticket->id)
                ->update(['remaining_classes' =>$remainingClasses]);

        }

        //app home record event
        $data=["Class Status Updated"];
        event(new MobileHomePageUpdated($data));

        return redirect()->back();
    }



    public function submitClassSchedulesAdmin(Request $request)
    {


        // dd($request->all());
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
            'totalTime' => number_format($differenceInHours,2)
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

        //app home record event
        $data=["Admin Added Class Schedule from Admin Panel Event"];
        event(new MobileHomePageUpdated($data));

        return redirect()->back();
    }

    public function submitClassSchedules(Request $request)
    {


        $student_id = DB::table('job_tickets')->where("id", $request->ticketID)->first();

        $customer_id = DB::table('students')->where("id", $student_id->student_id)->first();


        if ($request->paymentAttachment) {
            $imageName = time() . '.' . $request->paymentAttachment->extension();
            $request->paymentAttachment->move(public_path('customerCommitmentFee'), $imageName);
            $feePaymentValue = array(
                'ticket_id' => $request->ticketID,
                'customer_id' => $customer_id->customer_id,
                'payment_attachment' => $imageName,
                'payment_amount' => $request->feeAmount,
                'payment_date' => $request->feePaymentDate,
                'receiving_account' => $request->receivingAccount,
                'payment_attachment' => $imageName,
            );


            $jobTicketLastID = DB::table('customer_commitment_fees')->insertGetId($feePaymentValue);
        }




        // Sample data to pass to the view for PDF
        $invoice_detail = DB::table("invoices")->where("ticketID", $request->ticketID)->first();
        $invoice_items = DB::table("invoice_items")->where("ticketID", $request->ticketID)->get();
        $jobTicketDeails = DB::table("job_tickets")->where("id", $request->ticketID)->first();
        $students = DB::table('students')->where('id', '=', $invoice_detail->studentID)->orderBy('id', 'DESC')->first();
        $customer = DB::table('customers')->where('id', '=', $students->customer_id)->first();
        $subjects = DB::table('products')->join("categories", "products.category", "=", "categories.id")
            ->select("products.*", "categories.price as category_price")
            ->where('products.id', '=', $invoice_detail->subjectID)->first();

        $data = [
            'title' => 'Invoice',
            'content' => 'System Generated Invoice',
        ];



        // dd($invoice_items);

        // Generate PDF from a view
        $pdf = PDF::loadView('pdf.invoice', [
            'data' => $data,
            'invoice_items' => $invoice_items,
            'invoice_detail' => $invoice_detail,
            'students' => $students,
            'subjects' => $subjects,
            'customer' => $customer,
            'jobTicketDeails' => $jobTicketDeails,
        ]);

        $pdf->save(public_path('invoicePDF/') . "/" . "Invoice-" . $invoice_detail->id . ".pdf");
        // END Sample data to pass to the view for PDF


        //dd("Done");


        //dd($request->all());
        DB::table('job_tickets')
            ->where('id', $request->ticketID)
            ->update([
                'status' => 'approved',
                'ticket_status' => 'closed',
                'tutor_status' => 'approved',
                'remaining_classes' => $request->remaining_classes - 1,
                'tutor_id' => $request->tutor_id]);


        DB::table('invoices')
            ->where('ticketID', $request->ticketID)
            ->update([
                'showStatus' => 1]);
        // dd("Done");

        DB::table('student_subjects')
            ->where('ticket_id', $request->ticketID)
            ->update(['remaining_classes' => $request->remaining_classes - 1]);

        $sTime = $request->start_time;
        $eTime = $request->end_time;
        $t1 = strtotime($sTime);
        $t2 = strtotime($eTime);
        $differenceInSeconds = $t2 - $t1;
        $differenceInHours = $differenceInSeconds / 3600;

        if ($differenceInHours < 0) {
            $differenceInHours += 24;
        }


        // dd($request->subjectID." ".$request->ticketID);


        $getClassSchedule = DB::table('class_schedules')->where('subjectID', '=', $request->subjectID)->where('ticketID', '=', $request->ticketID)->first();

        $values = array(
            'tutorID' => $request->tutorID,
            'class_schedule_id' => $getClassSchedule->id,
            'studentID' => $request->studentID,
            'subjectID' => $request->subjectID,
            'ticketID' => $request->ticketID,
            'date' => $request->date,
            'startTime' => $request->start_time,
            'endTime' => $request->end_time,
            'status' => 'scheduled',
            'totalTime' => $differenceInHours,
            'remaining_classes' => $request->remaining_classes - 1,
            'hasIncentive' => 0,
        );

        // Assuming you have a unique identifier column like 'id' for the class_schedules table
        $classScheduleId = $getClassSchedule->id;

        // Update the record based on $getClassSchedule->id
        DB::table('class_schedules')->where('id', $classScheduleId)->update($values);


        $jobTicketData = DB::table('job_tickets')->where('id', '=', $request->ticketID)->first();
        $ticketUID = $jobTicketData->uid;


        $parent = DB::table('students')->where('id', '=', $request->studentID)->first();
        $parent = DB::table('customers')->where('id', '=', $parent->id)->first();


        if (isset($parent) && $parent->whatsapp != null) {
          $month_year = date('F Y'); // This will generate the current month and year
                $invoice_link = url("/invoicePublicLink").$invoice_detail->id; // Assuming you have a property for the invoice link in your invoice detail object
                $whatsapp_api = new WhatsappApi();
                $phone_number = $customer->whatsapp;
                $message = "Dear Parent/Student, Your SifuTutor invoice for $month_year is ready! You can easily view and pay your bill online at $invoice_link.
                The total amount due is ".$invoice_detail->invoiceTotal.".
                If you prefer, you can also make a payment to our Maybank account: Sifu Edu & Learning Sdn Bhd Account No: 5621 1551 6678.
                Please send your payment confirmation to us via WhatsApp at www.wasap.my/60146037500. If you have any enquiry,
                feel free to call or WhatsApp us at 014-603 7500. Thank you! - SifuTutor Management Team [This is an automated message, please do not reply directly.]";
                $whatsapp_api->send_message($phone_number, $message);
        }

        //app home record event
        $data=["App Home page record updated"];
        event(new MobileHomePageUpdated($data));

        return redirect('ClassSchedules');


    }

    // public function submitClassSchedules(Request $request)
    // {

    //     $student_id=DB::table('job_tickets')->where("id",$request->ticketID)->first();

    //     $customer_id=DB::table('students')->where("id",$student_id->student_id)->first();
    //   // dd($customer_id);


    //     if($request->paymentAttachment){
    //     $imageName = time().'.'.$request->paymentAttachment->extension();
    //     $request->paymentAttachment->move(public_path('customerCommitmentFee'), $imageName);
    //      $feePaymentValue = array(
    //              'ticket_id' => $request->ticketID,
    //              'customer_id' => $customer_id->customer_id,
    //              'payment_attachment' => $imageName,
    //              'payment_amount' => $request->feeAmount,
    //              'payment_date' => $request->feePaymentDate,
    //              'receiving_account' => $request->receivingAccount,
    //              'payment_attachment'=>$imageName,
    //          );


    //         $jobTicketLastID = DB::table('customer_commitment_fees')->insertGetId($feePaymentValue);
    //     }


    //      //dd($request->all());
    //     DB::table('job_tickets')
    //             ->where('id', $request->ticketID)
    //             ->update([
    //                     'status' => 'approved',
    //                     'ticket_status' => 'closed',
    //                     'tutor_status' => 'approved',
    //                     'remaining_classes' => $request->remaining_classes-1,
    //                     'tutor_id' => $request->tutor_id]);


    //   DB::table('invoices')
    //             ->where('ticketID', $request->ticketID)
    //             ->update([
    //                     'showStatus' => 1]);
    //                     // dd("Done");

    //     DB::table('student_subjects')
    //             ->where('ticket_id', $request->ticketID)
    //             ->update(['remaining_classes' => $request->remaining_classes-1]);

    //             $sTime = $request->start_time;
    //             $eTime = $request->end_time;
    //             $t1  = strtotime($sTime);
    //             $t2 = strtotime($eTime);
    //             $differenceInSeconds = $t2 - $t1;
    //             $differenceInHours = $differenceInSeconds / 3600;

    //             if($differenceInHours<0) {
    //                 $differenceInHours += 24;
    //             }


    //             // dd($request->subjectID." ".$request->ticketID);


    //         $getClassSchedule = DB::table('class_schedules')->where('subjectID','=',$request->subjectID)->where('ticketID','=',$request->ticketID)->first();

    //                     $values = array(
    //                     'tutorID' => $request->tutorID,
    //                     'class_schedule_id' => $getClassSchedule->id,
    //                     'studentID' => $request->studentID,
    //                     'subjectID' => $request->subjectID,
    //                     'ticketID' => $request->ticketID,
    //                     'date' => $request->date,
    //                     'startTime' => $request->start_time,
    //                     'endTime' => $request->end_time,
    //                     'status' => 'scheduled',
    //                     'totalTime' => $differenceInHours,
    //                     'remaining_classes' => $request->remaining_classes - 1,
    //                     'hasIncentive' => 0,
    //                 );

    //                 // Assuming you have a unique identifier column like 'id' for the class_schedules table
    //                 $classScheduleId = $getClassSchedule->id;

    //                 // Update the record based on $getClassSchedule->id
    //                 DB::table('class_schedules')->where('id', $classScheduleId)->update($values);


    //     $jobTicketData = DB::table('job_tickets')->where('id','=',$request->ticketID)->first();
    //     $ticketUID = $jobTicketData->uid;


    //     $parent=DB::table('students')->where('id','=',$request->studentID)->first();
    //     $parent = DB::table('customers')->where('id','=',$parent->id)->first();


    //     if(isset($parent)&& $parent->whatsapp!=null)

    //     {
    //         $whatsapp_api = new WhatsappApi();
    //         $phone_number = $parent->whatsapp;
    //         $message = 'New Class Scheduled for:".*$ticketUID*. "on". '.$request->date.'. Start Time '.$request->start_time.". End Time: ". $request->end_time;
    //         $whatsapp_api->send_message($phone_number, $message);
    //     }


    //   return redirect('ClassSchedules');


    // }


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

    public function deleteClassSchedule($id)
    {

        $class_schedule=DB::table("class_schedules")->where("id", $id)->first();
        $job_ticket=DB::table("job_tickets")->where("id", $class_schedule->ticketID)->first();
        // dd($job_ticket);
        $remainingClasses=$job_ticket->remaining_classes;
        $remainingClasses=$remainingClasses+1;
        DB::table("job_tickets")->where("id", $job_ticket->id)->update(["remaining_classes"=>$remainingClasses]);
        DB::table("class_schedules")->where("id", $id)->delete();

        // dd($remainingClasses);


        DB::table('student_subjects')
            ->where('ticket_id',  $job_ticket->id)
            ->update(['remaining_classes' =>$remainingClasses]);


        return redirect("/ClassSchedules");
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
