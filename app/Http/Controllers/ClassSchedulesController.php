<?php

namespace App\Http\Controllers;

use App\Events\Parent\ParentDashbaord;
use App\Events\Parent\ParentNotification;
use App\Events\Tutor\TutorDashboard;
use App\Events\Tutor\TutorNotification;
use App\Mail\ClassDisputeStatusUpdate;
use App\Mail\DisputeStatusUpdate;
use Illuminate\Http\Request;
use DB;
use App\Libraries\WhatsappApi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Pusher\Pusher;
use App\Mail\AttendanceReportMailAdmin;
use App\Mail\InvoiceMail;
use App\Mail\FirstClassConfirmedMail;

use App\Events\Tutor\ClassSchedule as TutorClassSchedule;
use App\Events\Parent\ClassSchedule as ParentClassSchedule;
use App\Events\Parent\ClassAttendance;
use App\Events\Parent\SingleParentDashboard;
use App\Jobs\SendPushNotificationJob;


class ClassSchedulesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function approveDispute($id)
    {

        $job_ticket = DB::table("class_attendeds")->
        where(["class_schedule_id" => $id, "status" => "dispute"])->
        first();


        $student_id = $job_ticket->studentID;
        $customer_id = DB::table("customers")->where("id", $student_id)->first();
        $customer_email = $customer_id->email;
        $job_ticket_data = DB::table("job_tickets")->where("id", $job_ticket->ticketID)->first();


        //Email work start
        $toEmail = "binasift@gmail.com";


        // Send email to both addresses
        Mail::to($toEmail)->send(new ClassDisputeStatusUpdate($job_ticket_data));
        Mail::to($customer_email)->send(new ClassDisputeStatusUpdate($job_ticket_data));


//        mail($to_email, $subject, $emailBody, $header);
//        mail($customer_email, $subject, $emailBody, $header);
        //Email work End

//        dd("Done");


        $class_attended = DB::table("class_attendeds")->
        where(["class_schedule_id" => $id, "status" => "dispute"])->
        first();
        //updating class attendeds
        DB::table("class_attendeds")->
        where("id", $class_attended->id)->
        update(["status" => "dispute", "totalTime" => "00:00:00", "commission" => 0]);

        //updating class schedule
        DB::table("class_schedules")->where("id", $id)->
        update(["status" => "dispute", "totalTime" => "0"]);
        return redirect()->back();
    }

    public function rejectDispute($id)
    {
        $class_attended = DB::table("class_attendeds")->
        where(["class_schedule_id" => $id, "status" => "dispute"])->
        first();

        $job_ticket = DB::table("class_attendeds")->
        where(["class_schedule_id" => $id, "status" => "dispute"])->
        first();


        $student_id = $job_ticket->studentID;
        $customer_id = DB::table("customers")->where("id", $student_id)->first();
        $customer_email = $customer_id->email;
        $job_ticket_data = DB::table("job_tickets")->where("id", $job_ticket->ticketID)->first();


        //Email work start
        $to_email = "binasift@gmail.com";
        Mail::to($to_email)->send(new DisputeStatusUpdate($job_ticket_data));
        Mail::to($customer_email)->send(new DisputeStatusUpdate($job_ticket_data));

        //Email work End


        //updating class attendeds
        DB::table("class_attendeds")->
        where("id", $class_attended->id)->
        update(["status" => "attended"]);

        //updating class schedule
        DB::table("class_schedules")->where("id", $id)->
        update(["status" => "attended"]);
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
        DB::table('user_activities')->insert([
            'user' => \Illuminate\Support\Facades\Auth::user()->name,
            'module' => "Class Schedule",
            'action' => "viewed Class Scheduled list",
        ]);

        // Fetch distinct mode values
        $modes = DB::table('categories')->select('mode')->distinct()->get();

        // Fetch distinct category names
        $categoryNames = DB::table('categories')->select('category_name')->distinct()->get();

        $query = DB::table('class_schedules')
            ->join('job_tickets', 'class_schedules.ticketID', '=', 'job_tickets.id')
            ->join('products', 'class_schedules.subjectID', '=', 'products.id')
            ->join('categories', 'products.category', '=', 'categories.id')
            ->select('class_schedules.*', 'job_tickets.*', 'products.name as subject_name', 'categories.category_name as level', 'categories.mode')
            ->whereIn('class_schedules.id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('class_schedules')
                    ->groupBy('ticketID');
            })
            ->where('class_schedules.class_schedule_id', '!=', 0)
            ->orderBy('class_schedules.id', 'DESC');

        // Apply Status Filter
        if ($request->has('status') && $request->status != '') {
            $query->where('class_schedules.status', '=', $request->status);
        }

        // Apply Student Name Filter
        if ($request->has('search') && $request->search != '') {
            $name = $request->search;
            $student = DB::table('students')->where('full_name', 'like', '%' . $name . '%')
                ->orWhere('student_id', 'like', '%' . $name . '%')
                ->first();
            if ($student) {
                $query->where('class_schedules.studentID', '=', $student->id);
            }
        }

        // Apply Level Filter
        if ($request->has('level') && $request->level != '') {
            $query->where('categories.category_name', 'like', '%' . $request->level . '%');
        }

        // Apply Mode Filter
        if ($request->has('mode') && $request->mode != '') {
            $query->where('categories.mode', 'like', '%' . $request->mode . '%');
        }

        // Apply Month and Year Filters
        if ($request->has('month') && $request->month != '') {
            $query->whereMonth('class_schedules.created_at', $request->month);
        }

        if ($request->has('year') && $request->year != '') {
            $query->whereYear('class_schedules.created_at', $request->year);
        }

        // Retrieve filtered data
        $class_schedules = $query->get();

        // Fetch distinct statuses for the dropdown
        $statuses = DB::table('class_schedules')->select('status')->distinct()->get();

        return view('classSchedule.index', compact('class_schedules', 'statuses', 'modes', 'categoryNames'));
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


    public function viewClassSchedules(Request $request, $id)
    {
        // Capture search parameters
        $status = $request->input('status');
        $month = $request->input('month');

        // Base query
        $query = DB::table('class_schedules')
            ->where('ticketID', '=', $id)
            ->orderBy('id', 'DESC');

        // Apply filters
        if ($status) {
            $query->where('status', '=', $status);
        }

        if ($month) {
            // Convert month name to month number
            $monthNumber = date('m', strtotime($month));
            $query->whereMonth('created_at', '=', $monthNumber);
        }

        $class_schedulesByID = $query->get();

        // Check if there's at least one class schedule to get details
        if ($class_schedulesByID->isNotEmpty()) {
            $firstSchedule = $class_schedulesByID[0];

            $subjectDetail = DB::table('products')->where('id', '=', $firstSchedule->subjectID)->first();
            $studentDetail = DB::table('students')->where('id', '=', $firstSchedule->studentID)->first();
            $tutorDetail = DB::table('tutors')->where('id', '=', $firstSchedule->tutorID)->first();
            $tickets = DB::table('job_tickets')->where('id', '=', $firstSchedule->ticketID)->first();

            $total_attended_hours = DB::table('class_schedules')
                ->where('ticketID', '=', $id)
                ->where('status', '=', "attended")
                ->sum('totalTime');
            $total_subscribed_hours = $tickets->quantity * $tickets->classFrequency;

            $classScheduleId = $firstSchedule->class_schedule_id;

            // Format startTime and endTime in AM/PM format
            foreach ($class_schedulesByID as $schedule) {
                $schedule->startTime = date('g:i A', strtotime($schedule->startTime));
                $schedule->endTime = date('g:i A', strtotime($schedule->endTime));
            }
        } else {
            // Handle the case where there are no class schedules
            $subjectDetail = $studentDetail = $tutorDetail = $tickets = null;
            $total_attended_hours = $total_subscribed_hours = 0;
            $classScheduleId = null;
        }

        $tutors = DB::table('tutors')->get();
        $subjects = DB::table('products')->get();

        return view('classSchedule/viewClassSchedule', compact(
            'class_schedulesByID', 'subjectDetail', 'classScheduleId',
            'studentDetail', 'tutorDetail', 'tickets', 'tutors',
            'subjects', 'total_subscribed_hours', 'total_attended_hours',
            'status', 'month', 'id'
        ));
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


        DB::table('class_schedules')->where("id", $request->classScheduleID)->update($tutorOfferValues);
        
        // Send push notifications to tutor devices
        $tutorDevices = DB::table('tutor_device_tokens')->where('tutor_id', '=', $request->tutorID)->distinct()->get(['device_token', 'tutor_id']);
        foreach ($tutorDevices as $rowDeviceToken) {
            $deviceToken = $rowDeviceToken->device_token;
            $title = 'Class Schedules Updated';
            $message = 'Class Schedules Updated Successfully';
        
            $notificationdata = [
                'Sender' => 'Schedule'
            ];
        
            // Dispatch push notification job
            SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
        
            // Store notification in the database
            DB::table('notifications')->insert([
                'page' => 'Schedule',
                'token' => $deviceToken,
                'title' => $title,
                'message' => $message,
                'type' => 'tutor',
                'status' => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        $student = DB::table('students')->where('id', '=', $request->studentID)->first();
        
        // Send push notification to parent devices
        $parent_device_tokens = DB::table('parent_device_tokens')->where('parent_id', '=', $student->customer_id)->distinct()->get(['device_token', 'parent_id']);
        foreach ($parent_device_tokens as $token) {
            $deviceToken = $token->device_token;
            $title = 'Class Schedules Updated';
            $message = 'Class Schedules Updated Successfully';
        
            $notificationdata = [
                'Sender' => 'Schedule'
            ];
        
            // Dispatch push notification job
            SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
        
            // Store notification in the database
            DB::table('notifications')->insert([
                'page' => 'Schedule',
                'token' => $deviceToken,
                'title' => $title,
                'message' => $message,
                'type' => 'parent',
                'status' => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


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
            DB::table('class_schedules')->where('id', '=', $request->classScheduleID)->update(["status" => "On going"]);
        }
        
        //notification work
        $job_ticket = DB::table('job_tickets')
            ->where('id', $request->ticketID)
            ->first();

        $tutors = DB::table('tutors')->where('id', '=', $request->tutorID)->first();

        // Send push notifications to tutor devices
        $tutorDevices = DB::table('tutor_device_tokens')->where('tutor_id', '=', $request->tutorID)->distinct()->get(['device_token', 'tutor_id']);
        foreach ($tutorDevices as $rowDeviceToken) {
            $deviceToken = $rowDeviceToken->device_token;
            $title = 'Clock-In Successfully';
            $message = 'Your Class in Clock-In Successfully';
        
            $notificationdata = [
                'Sender' => 'Schedule'
            ];
        
            // Dispatch push notification job
            SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
        
            // Store notification in the database
            DB::table('notifications')->insert([
                'page' => 'Schedule',
                'token' => $tutors->token,
                'title' => $title,
                'message' => $message,
                'type' => 'tutor',
                'status' => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $data = [
                "ResponseCode" => "100",
                "message" => "Your class has been started successfully."
            ];
            
            event(new TutorClassSchedule($data, $tutors->token));
            event(new TutorDashboard($data, $tutors->token));
            event(new TutorNotification($data, $tutors->token));        }
        
        $student = DB::table('students')->where('id', '=', $request->studentID)->first();
        $parent = DB::table('customers')->where('id', '=', $student->customer_id)->first();
        
        // Send push notification to parent devices
        $parent_device_tokens = DB::table('parent_device_tokens')->where('parent_id', '=', $student->customer_id)->distinct()->get(['device_token', 'parent_id']);
        foreach ($parent_device_tokens as $token) {
            $deviceToken = $token->device_token;
            $title = 'Clock-In Successfully';
            $message = 'Your Class in Clock-In Successfully';
        
            $notificationdata = [
                'Sender' => 'Schedule'
            ];
        
            // Dispatch push notification job
            SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
        
            // Store notification in the database
            DB::table('notifications')->insert([
                'page' => 'Schedule',
                'token' => $parent->token,
                'title' => $title,
                'message' => $message,
                'type' => 'parent',
                'status' => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $data = [
                "ResponseCode" => "100",
                "message" => "Your class has been started successfully."
            ];
            
            event(new ParentClassSchedule($data, $parent->token));
            event(new ParentDashbaord($data, $parent->token));
            event(new ParentNotification($data, $parent->token));
        }
        return redirect()->back()->with('success', 'Class Successfully Clock-in!');
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


        $getClassSchedule = DB::table("class_schedules")->where("id", $request->classScheduleID)->first();

        $class_schedule = DB::table("class_schedules")->where("id", $request->classScheduleID)->first();

        $job_ticket = DB::table("job_tickets")->where("id", $getClassSchedule->ticketID)->first();
        
        $tutor = DB::table("tutors")->where("id", $getClassSchedule->tutorID)->first();

        //commission work
        $totalAttendedHours = DB::table('class_schedules')
            ->where('class_schedule_id', '=', $request->csID)
            ->where("status", "attended")
            ->sum('totalTime');

        $newTotalAttendedHours = $totalAttendedHours + $class_schedule->totalTime;

        $per_class_commission = 0;
        $hoursAfterEight = 0;
        $hoursBeforeEight = 0;
        $commissionAfterEight = 0;
        $commissionBeforeEight = 0;

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
            ->update(['endTime' => $request->end_time, 'endTimeProofImage' => $imageNameSignoutProof, 'totalTime' => $getClassSchedule->totalTime, 'status' => "pending", 'commission' => $per_class_commission]);

        DB::table('class_schedules')
            ->where('id', $request->classScheduleID)
            ->update(['status' => "pending"]);
            
        
        //notification work
        
        // Send push notifications to tutor devices
        $tutorDevices = DB::table('tutor_device_tokens')->where('tutor_id', '=', $getClassSchedule->tutorID)->distinct()->get(['device_token', 'tutor_id']);
        foreach ($tutorDevices as $rowDeviceToken) {
            $deviceToken = $rowDeviceToken->device_token;
            $title = 'Clock-Out Successfully';
            $message = 'Your Class in Clock-Out Successfully';
        
            $notificationdata = [
                'Sender' => 'Schedule'
            ];
        
            // Dispatch push notification job
            SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
        
            // Store notification in the database
            DB::table('notifications')->insert([
                'page' => 'Schedule',
                'token' => $deviceToken,
                'title' => $title,
                'message' => $message,
                'type' => 'tutor',
                'status' => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        $student = DB::table('students')->where('id', '=', $getClassSchedule->studentID)->first();
        
        // Send push notification to parent devices
        $parent_device_tokens = DB::table('parent_device_tokens')->where('parent_id', '=', $student->customer_id)->distinct()->get(['device_token', 'parent_id']);
        foreach ($parent_device_tokens as $token) {
            $deviceToken = $token->device_token;
            $title = 'Clock-Out Successfully';
            $message = 'Your Class in Clock-Out Successfully';
        
            $notificationdata = [
                'Sender' => 'Schedule'
            ];
        
            // Dispatch push notification job
            SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
        
            // Store notification in the database
            DB::table('notifications')->insert([
                'page' => 'Schedule',
                'token' => $deviceToken,
                'title' => $title,
                'message' => $message,
                'type' => 'parent',
                'status' => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        // return redirect()->back()->with('success', 'Class Successfully Clock-Out!');


        //   dd($request->all());

        //Email work start
        $studentName = DB::table('students')->where('id', '=', $getClassSchedule->studentID)->first();
        $subjectName = DB::table('products')->where('id', '=', $getClassSchedule->subjectID)->first();
        $student_data = DB::table('students')->where('id', '=', $getClassSchedule->studentID)->get()->first();

        $customer_data = DB::table('customers')->where('id', '=', $student_data->customer_id)->get()->first();

        $attendedRecord = DB::table('class_attendeds')->where('id', '=', $request->id)->first();
        $agreePath = url("/") . "/agreeAttendance/" . $attendedRecord->id;
        $disagreePath = url("/") . "/disputeAttendance/" . $attendedRecord->id;
        $total_time_attended = $attendedRecord->totalTime;
        // dd($getClassSchedule);

        //   dd($attendedRecord);


        if ($customer_data->email != null) {
            $to = $customer_data->email;
            $emailTwo = "binasift@gmail.com";

        } else {
            $to = $customer_data->email;
            $emailTwo = "binasift@gmail.com";

        }

        Mail::to($to)->send(new AttendanceReportMailAdmin($attendedRecord, $studentName, $subjectName, $agreePath, $disagreePath, $total_time_attended));
        Mail::to($emailTwo)->send(new AttendanceReportMailAdmin($attendedRecord, $studentName, $subjectName, $agreePath, $disagreePath, $total_time_attended));


        //Invoice Email setup
        $invoice_detail = DB::table('invoices')->where('ticketID', '=', $job_ticket->id)->first();

        $invoice_items = DB::table('invoice_items')->
        join("products", "invoice_items.subjectID", "=", "products.id")->
        join("students", "invoice_items.studentID", "=", "students.id")->
        select("invoice_items.*", "students.full_name as full_name", "products.name as name", "products.price as price", "invoice_items.invoiceDate as invoiceDate")->
        where('invoiceID', '=', $invoice_detail->id)->get();
        
        $parentdevicetoken = DB::table('parent_device_tokens')->where('parent_id', $customer_data->id)->first();

        $pdfPath = public_path("/invoicePDF/Invoice-" . $invoice_detail->id . ".pdf");
        $pdfContent = file_get_contents($pdfPath);
        $base64Content = base64_encode($pdfContent);
        
        if (!$job_ticket->first_invoice_sent) {

            DB::table('invoices')->where('ticketID', $job_ticket->id)->update([
                'paymentDate' => now(),
                'dueDate' => now()->addWeek()
            ]);
            
            Mail::to($to)->send(new InvoiceMail($invoice_detail, $base64Content));
            DB::table('job_tickets')->where('id', $job_ticket->id)->update(['first_invoice_sent' => true]);
            
            $parenttitle = 'Pay Monthly Invoice';
            $month = date('F');
            // $month = date('F', strtotime($invoice_detail->invoiceDate));
            $parentmessage = "Your ".$month." invoice is ready. Please pay now.";
            $notificationdata = [
                'Sender' => 'PaymentHistory'
            ];
            
            if($parentdevicetoken){
                // Dispatch push notification job
                SendPushNotificationJob::dispatch($parentdevicetoken->device_token, $parenttitle, $parentmessage, $notificationdata);
            
                // Store notification in the database
                DB::table('notifications')->insert([
                    'page' => 'PaymentHistory',
                    'token' => $customer_data->token,
                    'title' => $parenttitle,
                    'message' => $parentmessage,
                    'type' => 'Parent',
                    'status' => 'new',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
            
            
        try {

            $data = [
                "ResponseCode" => "100",
                "message" => "New Attendance"
            ];
            //tutor
            event(new TutorDashboard($data, $tutor->token));
            //parent
            event(new ClassAttendance($data, $customer_data->token));
            event(new SingleParentDashboard($data, $customer_data->token));


        } catch (Exception $e) {
            return response()->json(["ResponseCode" => "103",
                "error" => "Unable to get New Attendance"]);
        }

        return redirect()->back()->with('success', 'Class Successfully Clock-Out!');
    }


    public function submitStatus(Request $request)

    {
        
        $class_schedule = DB::table("class_schedules")->where("id", $request->classScheduleID)->first();
        $tutor = DB::table("tutors")->where("id", $class_schedule->tutorID)->first();
        $job_ticket = DB::table("job_tickets")->where("id", $class_schedule->ticketID)->first();

        $totalAttendedHours = DB::table('class_schedules')
            ->where('class_schedule_id', '=', $request->csID)
            ->where("status", "attended")
            ->sum('totalTime'); // Assuming 'totalTime' is the field that holds the duration for each attended class schedule
        $newTotalAttendedHours = $totalAttendedHours + $class_schedule->totalTime;


        $per_class_commission = 0;
        $hoursAfterEight = 0;
        $hoursBeforeEight = 0;
        $commissionAfterEight = 0;
        $commissionBeforeEight = 0;


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


        $attendedRecord = DB::table('class_attendeds')->where('id', '=', $request->id)->first();
        if ($attendedRecord == null) {


            $getClassSchedule = DB::table('class_schedules')->where('id', '=', $class_schedule->id)->first();
            $jobTicket = DB::table("job_tickets")->where("id", $getClassSchedule->ticketID)->first();


            $endTime = $class_schedule->endTime;
            $sTime = $getClassSchedule->startTime;
            $eTime = $endTime;
            $t1 = strtotime($sTime);
            $t2 = strtotime($eTime);
            $differenceInSeconds = $t2 - $t1;
            $differenceInHours = $differenceInSeconds / 3600;
            $totalTime = $differenceInHours;
            if ($differenceInHours < 0) {
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
            
            $sTime = $getClassSchedule->startTime;
            $eTime = $getClassSchedule->endTime;
            $t1 = strtotime($sTime);
            $t2 = strtotime($eTime);
            $differenceInSeconds = $t2 - $t1;
            
            // If the end time is earlier than the start time, adjust for the next day
            if ($differenceInSeconds < 0) {
                $differenceInSeconds += 24 * 3600; // Add 24 hours in seconds
            }
            
            // Format the difference in HH:MM:SS
            $formattedDifference = gmdate('H:i:s', $differenceInSeconds);


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
                'totalTime' => $formattedDifference,
                'commission' => $per_class_commission,
                'parent_verified' => "YES"

            );
            $attendedRecord = DB::table('class_attendeds')->insertGetId($values);

            $attendedRecord = DB::table('class_attendeds')->where("id", $attendedRecord)->first();


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

        } else {
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


        if ($request->classStatus == "cancelled" || $request->classStatus == "postponed") {


            $job_ticket = DB::table("job_tickets")->where("id", $class_schedule->ticketID)->first();
            $remainingClasses = $job_ticket->remaining_classes;
            $remainingClasses = $remainingClasses + 1;
            DB::table("job_tickets")->where("id", $job_ticket->id)->update(["remaining_classes" => $remainingClasses]);

            DB::table('student_subjects')
                ->where('ticket_id', $job_ticket->id)
                ->update(['remaining_classes' => $remainingClasses]);

        }

        $student = DB::table("students")->where("id", $class_schedule->studentID)->first();
        $customer = DB::table("customers")->where("id", $student->customer_id)->first();
        
        
        // Send push notifications to tutor devices
        $tutorDevices = DB::table('tutor_device_tokens')->where('tutor_id', '=', $attendedRecord->tutorID)->distinct()->get(['device_token', 'tutor_id']);
        foreach ($tutorDevices as $rowDeviceToken) {
            $deviceToken = $rowDeviceToken->device_token;
            $title = 'Class Status Changed';
            $message = 'Class Schedule Status Changed Successfully';
        
            $notificationdata = [
                'Sender' => 'Schedule'
            ];
        
            // Dispatch push notification job
            SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
        
            // Store notification in the database
            DB::table('notifications')->insert([
                'page' => 'Schedule',
                'token' => $deviceToken,
                'title' => $title,
                'message' => $message,
                'type' => 'tutor',
                'status' => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Send push notification to parent devices
        $parent_device_tokens = DB::table('parent_device_tokens')->where('parent_id', '=', $customer->id)->distinct()->get(['device_token', 'parent_id']);
        foreach ($parent_device_tokens as $token) {
            $deviceToken = $token->device_token;
            $title = 'Class Status Changed';
            $message = 'Class Schedule Status Changed Successfully';
        
            $notificationdata = [
                'Sender' => 'Schedule'
            ];
        
            // Dispatch push notification job
            SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
        
            // Store notification in the database
            DB::table('notifications')->insert([
                'page' => 'Schedule',
                'token' => $deviceToken,
                'title' => $title,
                'message' => $message,
                'type' => 'parent',
                'status' => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


        try {

            $data = [
                "ResponseCode" => "100",
                "message" => "Class Status Updated"
            ];

            //tutor
            event(new TutorDashboard($data, $tutor->token));
            event(new TutorClassSchedule($data, $tutor->token));

            //parent
            event(new ParentDashbaord($data));
            event(new ClassAttendance($data, $tutor->token));
            event(new ParentClassSchedule($data, $customer->token));

        } catch (Exception $e) {
            return response()->json(["ResponseCode" => "103",
                "error" => "Unable to update the attendance status"]);
        }


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

        // dd($request->all());

        $tutor = DB::table("tutors")->where("id", $request->tutorID)->first();
        $student = DB::table("students")->where("id", $request->studentID)->first();
        $parent = DB::table("customers")->where("id", $student->customer_id)->first();
        $subject = DB::table("products")->where("id", $request->subjectID)->first();

        try {

            $data = [
                "ResponseCode" => "100",
                "message" => "New Class Schedule Created"
            ];

            //tutor
            event(new TutorDashboard($data, $tutor->token));
            event(new TutorClassSchedule($data, $tutor->token));
            event(new TutorNotification($data, $tutor->token));

            //parent
            event(new ParentClassSchedule($data, $parent->token));
            event(new ParentNotification($data, $parent->token));
            event(new SingleParentDashboard($data, $parent->token));


        } catch (Exception $e) {
            return response()->json(["ResponseCode" => "103",
                "error" => "Unable to get New Class Schedule"]);
        }


        //notification work
        $job_ticket = DB::table('job_tickets')
            ->where('id', $request->ticketID)
            ->first();

        // Send push notifications to tutor devices
        $tutorDevices = DB::table('tutor_device_tokens')->where('tutor_id', '=', $tutor->id)->distinct()->get(['device_token', 'tutor_id']);
        foreach ($tutorDevices as $rowDeviceToken) {
            $deviceToken = $rowDeviceToken->device_token;
            $title = 'New Class Schedule Created';
            $message = 'New Class Schedule created';
        
            $notificationdata = [
                'Sender' => 'Schedule'
            ];
        
            // Dispatch push notification job
            SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
        
            // Store notification in the database
            DB::table('notifications')->insert([
                'page' => 'Schedule',
                'token' => $tutor->token,
                'title' => $title,
                'message' => $message,
                'type' => 'tutor',
                'status' => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Send push notification to parent devices
        $parent_device_tokens = DB::table('parent_device_tokens')->where('parent_id', '=', $parent->id)->distinct()->get(['device_token', 'parent_id']);
        foreach ($parent_device_tokens as $token) {
            $deviceToken = $token->device_token;
            $title = 'First Class Confirmation';
            $message = 'First class with '.$tutor->full_name.' confirmed! Check details.';
        
            $notificationdata = [
                'Sender' => 'Schedule'
            ];
        
            // Dispatch push notification job
            SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
        
            // Store notification in the database
            DB::table('notifications')->insert([
                'page' => 'Schedule',
                'token' => $parent->token,
                'title' => $title,
                'message' => $message,
                'type' => 'parent',
                'status' => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


        return redirect()->back()->with('success', 'New Class Schedule created!');
    }

    public function submitClassSchedules(Request $request)
    {
        $student_id = DB::table('job_tickets')->where("id", $request->ticketID)->first();
        $customer_id = DB::table('students')->where("id", $student_id->student_id)->first();
        $tutor = DB::table("tutors")->where("id", $request->tutorID)->first();
        
        // dd($request->all());

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
            DB::table('customers')->where('id', $customer_id->customer_id)->update(["status" => "active"]);
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
        
        // Send "First Class Confirmed" email
        try {
            $emailData = [
                'tutorName' => $tutor->full_name,
            ];
    
            // Send email to tutor
            Mail::to($tutor->email)->send(new FirstClassConfirmedMail($emailData));
        } catch (\Exception $e) {
            \Log::error('Failed to send first class confirmation email: ' . $e->getMessage());
        }

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


        $student = DB::table('students')->where('id', '=', $request->studentID)->first();
        $parent = DB::table('customers')->where('id', '=', $student->customer_id)->first();
        $tutor = DB::table("tutors")->where("id", $request->tutorID)->first();
        $subject = DB::table("products")->where("id", $request->subjectID)->first();

        if (isset($parent) && $parent->whatsapp != null) {
            $month_year = date('F Y'); // This will generate the current month and year
            $invoice_link = url("/invoicePublicLink") . $invoice_detail->id; // Assuming you have a property for the invoice link in your invoice detail object
            $whatsapp_api = new WhatsappApi();
            $phone_number = $customer->whatsapp;
            $message = "Dear Parent/Student, Your SifuTutor invoice for $month_year is ready! You can easily view and pay your bill online at $invoice_link.
                The total amount due is " . $invoice_detail->invoiceTotal . ".
                If you prefer, you can also make a payment to our Maybank account: Sifu Edu & Learning Sdn Bhd Account No: 5621 1551 6678.
                Please send your payment confirmation to us via WhatsApp at www.wasap.my/60146037500. If you have any enquiry,
                feel free to call or WhatsApp us at 014-603 7500. Thank you! - SifuTutor Management Team [This is an automated message, please do not reply directly.]";
            $whatsapp_api->send_message($phone_number, $message);
        }

        $title = 'First Class Schedule Created';
        $message = "First class with ".$student->full_name." scheduled! View  details.";


        $parenttitle = 'First Class Confirmation';
        $parentmessage = "First class with ".$tutor->full_name." confirmed! Check details.";
        $notificationdata = [
                'Sender' => 'Schedule'
            ];
        
                
        $parentdevicetoken = DB::table('parent_device_tokens')->where('parent_id', $parent->id)->first();

        if ($parentdevicetoken) {
            // Dispatch push notification job if the device token exists
            SendPushNotificationJob::dispatch($parentdevicetoken->device_token, $parenttitle, $parentmessage, $notificationdata);
        
            // Store notification in the database
            DB::table('notifications')->insert([
                'page' => 'Schedule',
                'token' => $parent->token,
                'title' => $parenttitle,
                'message' => $parenttitle,
                'type' => 'parent',
                'status' => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
            
        if($tutor){
                
                $tutordevicetoken = DB::table('tutor_device_tokens')->where('tutor_id', $tutor->id)->first();
                
                $data = [
                    "ResponseCode" => "100",
                    "message" => "Class Schedule status set to attended by Admin."
                ];
                
                if ($tutordevicetoken) {
                    // Dispatch push notification job
                    SendPushNotificationJob::dispatch($tutordevicetoken->device_token, $title, $message, $notificationdata);
                
                    // Store notification in the database
                    DB::table('notifications')->insert([
                        'page' => 'Schedule',
                        'token' => $tutor->token,
                        'title' => $title,
                        'message' => $message,
                        'type' => 'Tutor',
                        'status' => 'new',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                event(new TutorDashboard($data, $tutor->token));
                event(new TutorClassSchedule($data, $tutor->token));
                event(new TutorNotification($data, $tutor->token));
        } try {

            //parent tokent
            $data = [
                "ResponseCode" => "100",
                "message" => "Class Schedule status set to attended by Admin."
            ];

            event(new ParentClassSchedule($data, $parent->token));
            event(new ParentDashbaord($data, $parent->token));
            event(new ParentNotification($data, $parent->token));

        } catch (Exception $e) {
            return response()->json(["ResponseCode" => "103",
                "error" => "Unable to created Job Ticket"]);
        }


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

        $class_schedule = DB::table("class_schedules")->where("id", $id)->first();
        $job_ticket = DB::table("job_tickets")->where("id", $class_schedule->ticketID)->first();
        // dd($job_ticket);
        $remainingClasses = $job_ticket->remaining_classes;
        $remainingClasses = $remainingClasses + 1;
        DB::table("job_tickets")->where("id", $job_ticket->id)->update(["remaining_classes" => $remainingClasses]);
        DB::table("class_schedules")->where("id", $id)->delete();

        // dd($remainingClasses);


        DB::table('student_subjects')
            ->where('ticket_id', $job_ticket->id)
            ->update(['remaining_classes' => $remainingClasses]);
            
        // Send push notifications to tutor devices
        $tutorDevices = DB::table('tutor_device_tokens')->where('tutor_id', '=', $class_schedule->tutorID)->distinct()->get(['device_token', 'tutor_id']);
        foreach ($tutorDevices as $rowDeviceToken) {
            $deviceToken = $rowDeviceToken->device_token;
            $title = 'Class Schedule Deleted';
            $message = 'Class Schedule Deleted Successfully';
        
            $notificationdata = [
                'Sender' => 'Schedule'
            ];
        
            // Dispatch push notification job
            SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
        
            // Store notification in the database
            DB::table('notifications')->insert([
                'page' => 'Schedule',
                'token' => $deviceToken,
                'title' => $title,
                'message' => $message,
                'type' => 'tutor',
                'status' => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        $student = DB::table('students')->where('id', '=', $class_schedule->studentID)->first();

        // Send push notification to parent devices
        $parent_device_tokens = DB::table('parent_device_tokens')->where('parent_id', '=', $student->customer_id)->distinct()->get(['device_token', 'parent_id']);
        foreach ($parent_device_tokens as $token) {
            $deviceToken = $token->device_token;
            $title = 'Class Schedule Deleted';
            $message = 'Class Schedule Deleted Successfully';
        
            $notificationdata = [
                'Sender' => 'Schedule'
            ];
        
            // Dispatch push notification job
            SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
        
            // Store notification in the database
            DB::table('notifications')->insert([
                'page' => 'Schedule',
                'token' => $deviceToken,
                'title' => $title,
                'message' => $message,
                'type' => 'parent',
                'status' => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


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
