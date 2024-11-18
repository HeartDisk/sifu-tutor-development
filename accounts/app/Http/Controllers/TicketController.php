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
use App\Events\TicketCreated;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function editTicketTutor($id)
    {
        $data = DB::table("job_tickets")->where("id", $id)->first();
        return view("job_tickets.update_tutor", ["data" => $data]);
    }

    public function updateTicketTutor(Request $request)
    {
      
        $class_schdules=DB::table("class_schedules")->where(["ticketID"=> $request->id,"status"=>"scheduled"])->get();
        foreach($class_schdules as $class_schdule)
        {
            DB::table("class_schedules")->where(["id"=> $class_schdule->id])->update(["status"=>"cancelled"]);
        }
        //   dd($class_schdules);
        DB::table("job_tickets")->where("id", $request->id)->
        update(["ticket_tutor_status" => $request->ticket_tutor_status, "reason" => $request->reason]);
        return redirect("/TicketList");
    }

    public function TicketList(Request $request)
    {
        // dd($request->all());

        $id = Auth::user()->id;
        $checked = DB::table('loggedInUsers')->where('user_id', '=', $id)->where('last_login', '<>', date("Y-m-d H"))->where('pageUrl', '=', url()->full())->first();
        if (!$checked) {
            DB::table('loggedInUsers')->insert([
                'user_id' => $id,
                'last_login' => date('Y-m-d H:i:s'),
                'pageUrl' => url()->full(),
                'detail' => 'View dashboard at home page'
            ]);
        }

        $query = DB::table('job_tickets');

        if ($request->filled('search')) {

            $query->where('uid', '=', $request->search);
        }
        if ($request->filled('status')) {
            
           
            
            if($request->status=="discontinued" || $request->status=="Active")
            {
                 $query->where('ticket_tutor_status', '=', $request->status);
            }else
            {
                
            $query->where('application_status', '=', $request->status);    
            }
            
        }

        if ($request->filled('classType')) {
            $query->where('mode', '=', $request->classType);
        }

        if ($request->filled('stateID')) {
            $query->where('classState', '=', $request->stateID);
        }


        if ($request->filled('subjectID')) {
            $query->where('subjects', '=', $request->subjectID);
        }


        if ($request->filled('staff')) {

            $query->where('admin_charge', '=', $request->staff);
        }

        if ($request->hasAny(['search', 'status', 'classType', 'stateID', 'subjectID', 'staff'])) {

            $tickets = $query->distinct()->orderBy("id", "desc")->get();


        } else {
            // Fetch all tutors if no search parameters are provided
            $tickets = $query->orderBy("id", "desc")->get();
        }


        $staffs = DB::table('staffs')->
        where("staffs.status", "Active")->
        get();
        
        // dd($tickets);
        
        //$tickets = DB::table('job_tickets')->orderBy('id','DESC')->get();
        return view('job_tickets/ticketList', Compact('tickets', 'staffs'));
    }

    public function addExtraStudents(Request $request)
    {

        $extraStudentFeeCharges = DB::table('extra_student_charges')->orderBy('id', 'DESC')->first();

        $studentValues = array(
            'uid' => 'ST-' . date('dis'),
            'student_id' => 'ST-' . date('dis'),
            'full_name' => $request->student_name,
            'register_date' => date('Y-m-d'),
            'customer_id' => $request->parent_id,
            'gender' => $request->gender,
            'age' => $request->age,
            'specialNeed' => $request->special_need,
        );
        $studentLastID = DB::table('students')->insertGetId($studentValues);
        $multipleStudent = array(
            'student_id' => $studentLastID,
            'student_name' => $request->student_name,
            'student_gender' => $request->gender,
            'student_age' => $request->age,
            'year_of_birth' => $request->birthYear,
            'special_need' => $request->special_need,
            'job_ticket_id' => $request->ticketID,
            'subject_id' => $request->subject_id,
            'extra_fee' => $extraStudentFeeCharges->charges,
            'extra_fee_date' => $extraStudentFeeCharges->created_at,
        );
        DB::table('job_ticket_students')->insertGetId($multipleStudent);
        // $ledgerValue = array(
        //     'payment_reference' => Auth::user()->id,
        //     'user_id' => Auth::user()->id,
        //     'bill_no' => $request->ticketUID,
        //     'sale_id' => $request->ticketUID,
        //     'account_id' => $request->parent_id,
        //     'amount' => $extraStudentFeeCharges->charges,
        //     'type' => 'c',
        //     'debit' => 0,
        //     'credit' => $extraStudentFeeCharges->charges,
        //     'date' => date('Y-m-d'),
        //     'date_2' => date('Y-m-d')
        // );
        // $ledgerID = DB::table('payments')->insertGetId($ledgerValue);

        return redirect()->back();

    }

    public function TutorApplicationSummary()
    {

        $data = DB::table("tutoroffers")->
        join("products", "tutoroffers.subject_id", "=", "products.id")->
        select("tutoroffers.*")
            ->join("tutors", "tutoroffers.tutorID", "=", "tutors.id")
            ->select("tutoroffers.*", "tutors.uid as uid", "tutors.full_name as tutor", "products.name as subject",
                "tutors.email as email"

            )
            ->get();
        foreach ($data as $dt) {
            $tutor = DB::table("tutors")->join("states", "tutors.state", "=", "states.id")->
            join("cities", "tutors.city", "=", "cities.id")->
            select("tutors.*", "states.name as state_name", "cities.name as city_name")->first();
            $dt->state = $tutor->state_name;
            $dt->city = $tutor->city_name;
        }

        // dd($data);

        return view('job_tickets/TutorApplicationSummary', ['data' => $data]);


    }

    public function addTicketAjaxPOSTcustomerState(Request $request)
    {


        $cities = DB::table('cities')->where('state_id', '=', $request->customerState)->get();

        $options = [];

        foreach ($cities as $rowsCity) {
            $options[] .= "<option value='$rowsCity->id'>" . $rowsCity->name . "</option>";
        }

        return Response::json(['cities' => $options]);
    }


    public function getParentStudents(Request $request)
    {

        // dd($request->all());
        $students = DB::table('students')->where('customer_id', '=', $request->customer_id)->get();
        $options = [];
        if ($students == null) {


            foreach ($students as $student) {
                $options[] .= "<option value='$student->id'>" . $student->full_name . "</option>";
            }
        } else {
            $options[] .= "<option value='newStudent'>" . "New Student" . "</option>";
        }


        return Response::json(['students' => $options]);
    }


    public function addTicketAjaxPOSTclassState(Request $request)
    {


        $cities = DB::table('cities')->where('state_id', '=', $request->classState)->get();

        $options = [];

        foreach ($cities as $rowsCity) {
            $options[] .= "<option value='$rowsCity->id'>" . $rowsCity->name . "</option>";
        }

        return Response::json(['cities' => $options]);
    }

    public function addTicketGetAjaxCall($id)
    {

        if ($id == "newStudent") {
            $student = [];
            $customer = [];
            $subjects = [];
            return Response::json(['student_id' => $id, 'student' => $student, 'customer' => $customer, 'subjects' => $subjects]);
        } else {
            $student = DB::table('students')->where('id', '=', $id)->first();
            $customer = DB::table('customers')->where('id', '=', $student->customer_id)->first();
            $stateName = DB::table('states')->where('id', '=', $customer->state)->first();
            $cityName = DB::table('cities')->where('id', '=', $customer->city)->first();
            //$subjects = DB::table('student_subjects')->where('student_id','=',$student->id)->get();
            $subjects = DB::table('student_subjects')
                ->where('student_subjects.student_id', '=', $student->id)
                ->join('products', 'student_subjects.subject', '=', 'products.id')
                ->join('students', 'student_subjects.student_id', '=', 'students.id')
                ->join('customers', 'customers.id', '=', 'students.customer_id')
                ->join('states', 'customers.state', '=', 'states.id')
                ->join('cities', 'customers.city', '=', 'cities.id')
                ->select('student_subjects.*', 'products.name as subject', 'cities.name as cityName', 'states.name as stateName', 'students.full_name as studentName', 'student_subjects.status as newstatus')->get();

            return Response::json(['student_id' => $id, 'student' => $student, 'customer' => $customer, 'stateName' => $stateName->name, 'cityName' => $cityName->name, 'subjects' => $subjects]);
        }


    }

    public function addTicketAjaxCallParrent($parrentID)
    {

        if ($parrentID == "newCustomer") {

            $customer = "";
            return Response::json(['customer' => $customer, 'customer_id' => $parrentID]);
        } else {

            $customer = DB::table('customers')->where('id', '=', $parrentID)->first();


            $stateName = DB::table('states')->where('id', '=', $customer->state)->first();
            $cityName = DB::table('cities')->where('id', '=', $customer->city)->first();


            //return Response::json(['customer' => $stateName->name]);


            $studentLastClass = DB::table('students')
                ->join('class_attendeds', 'class_attendeds.studentID', '=', 'students.id')
                ->where('students.customer_id', '=', $parrentID)
                ->orderBy('class_attendeds.id', 'DESC')
                ->first();
            if ($studentLastClass) {
                $studentLastClassDate = $studentLastClass->date;
            } else {
                $studentLastClassDate = null;
            }
            //$subjects = DB::table('student_subjects')->where('student_id','=',$student->id)->get();
            return Response::json(['customer' => $customer, 'customer_id' => $parrentID, 'stateName' => $stateName->name, 'cityName' => $cityName->name, 'studentLastClass' => $studentLastClassDate]);
        }

    }


    public function addTicket()
    {

        $students = DB::table('students')->where("is_deleted", "!=", "1")->get();
        $customers = DB::table('customers')->where("is_deleted", "!=", "1")->get();
        $subjects = DB::table('products')->where("is_deleted", "!=", "1")->get();

        $subjects = DB::table('products')->join("categories", "products.category", "=", "categories.id")
            ->select("products.*", "categories.price as category_price", "categories.category_name as category_name", "categories.mode as mode")
            ->get();


        $subjectsPhysical = DB::table('products')->join("categories", "products.category", "=", "categories.id")
            ->select("products.*", "categories.price as category_price", "categories.category_name as category_name", "categories.mode as mode")->
            where("mode", "physical")->get();

        $subjectsOnline = DB::table('products')->join("categories", "products.category", "=", "categories.id")->
        where("mode", "online")
            ->select("products.*", "categories.price as category_price", "categories.category_name as category_name", "categories.mode as mode")->get();


        $today = date("Y-m-d");


        return view('job_tickets/addTicket', compact('students', 'subjects', 'customers', 'today', 'subjectsPhysical', 'subjectsOnline'));

    }


    public function acceptTicket($ssid, $subject_id, $ticket_id, $tutorID)
    {

        $var1 = DB::table('student_subjects')->where('id', $ssid)->where('ticket_id', $ticket_id)->where('subject', $subject_id)->where('tutor_id', $tutorID)->update(['status' => 'Accepted', 'days' => 1]);

        return redirect()->back();
    }

    public function duplicateTicket($id)
    {

       

        $tableName = 'job_ticket_students';
        $count = DB::table($tableName)
            ->select(DB::raw('count(*) as count'))
            ->where('job_ticket_id', '=', $id)
            ->first()
            ->count;

        $extraStudentFeeCharges = DB::table('extra_student_charges')->orderBy('id', 'DESC')->first();

        $duplicateTicket = DB::table('job_tickets')->where('id', '=', $id)->first();
        $duplicateStudent_subjects = DB::table('student_subjects')->where('ticket_id', '=', $id)->first();
        $duplicateJob_ticket_students = DB::table('job_ticket_students')->where('job_ticket_id', '=', $id)->get();
//        $subjectPrice = DB::table('products')->where('id', '=', $duplicateTicket->subjects)->first();

        $subjectPrice = DB::table('products')->join("categories", "products.category", "=", "categories.id")
            ->select("products.*", "categories.price as category_price", "categories.mode as mode")->
            where('products.id', '=', $duplicateTicket->subjects)->first();
        // $subjectPrice = (int)$subjectFee->category_price;


        $currentID = DB::table('job_tickets')->orderBy('id', 'DESC')->first();

//        dd($currentID);

        $ticektValues = array(
            'ticket_id' => $currentID->id + 1,
            'uid' => 'JT-' . date('mds') + 1,
            'student_id' => $duplicateTicket->student_id,
            'admin_charge' => $duplicateTicket->admin_charge,
            'payment_attachment' => $duplicateTicket->payment_attachment,
            'subjects' => $duplicateTicket->subjects,
            'service' => $duplicateTicket->service,
            'quantity' => $duplicateTicket->quantity,
            'classFrequency' => $duplicateTicket->classFrequency,
            'remaining_classes' => $duplicateTicket->classFrequency,
            'tutorPereference' => $duplicateTicket->tutorPereference,
            'day' => $duplicateTicket->day,
            'time' => $duplicateTicket->time,
            'subscription' => $duplicateTicket->subscription,
            'specialRequest' => $duplicateTicket->specialRequest,
            'classAddress' => $duplicateTicket->classAddress,
            'classLatitude' => $duplicateTicket->classLatitude,
            'classLongitude' => $duplicateTicket->classLongitude,
            'classCity' => $duplicateTicket->classCity,
            'classState' => $duplicateTicket->classState,
            'classPostalCode' => $duplicateTicket->classPostalCode,
            'register_date' => $duplicateTicket->register_date,
            'mode' => $duplicateTicket->mode,
            'fee_payment_date' => $duplicateTicket->fee_payment_date,
            'receiving_account' => $duplicateTicket->receiving_account,
            'status' => 'pending',
            'totalPrice' => $currentID->totalPrice,
            'estimate_commission' => $currentID->estimate_commission,
            'estimate_commission_display_tutor' => $currentID->estimate_commission_display_tutor,
            'extra_student_total' => $currentID->extra_student_total,
            'extra_student_tutor_commission' => $currentID->extra_student_tutor_commission,
            'extra_estimate_commission_display_tutor' => $currentID->extra_estimate_commission_display_tutor,
        );

        $jobTicketLastID = DB::table('job_tickets')->insertGetId($ticektValues);

        $student_data = array(
            'student_id' => $duplicateStudent_subjects->student_id,
            'ticket_id' => $jobTicketLastID,
            'ticket_id2' => $jobTicketLastID,
            'subject' => $duplicateStudent_subjects->subject,
            'quantity' => $duplicateStudent_subjects->quantity,
            'classFrequency' => $duplicateStudent_subjects->classFrequency,
            'remaining_classes' => $duplicateStudent_subjects->classFrequency,
            'day' => $duplicateStudent_subjects->day,
            'time' => $duplicateStudent_subjects->time,
            'subscription' => $duplicateStudent_subjects->subscription,
            'specialRequest' => $duplicateStudent_subjects->specialRequest,
        );

        // dd($subjectPrice);
        DB::table('student_subjects')->insertGetId($student_data);
        // $extra_student_charges = DB::table('extra_student_charges')->orderBy('id', 'DESC')->first();
        if ($subjectPrice->mode == "physical") {
            $extraStudentCharges = DB::table("extra_student_charges")->first();
            $extraStudentChargesDate = $extraStudentCharges->created_at;
            $extraStudentCharges = $extraStudentFeeCharges->physical_additional_charges;

        } else {
            $extraStudentCharges = DB::table("extra_student_charges")->first();
            $extraStudentChargesDate = $extraStudentCharges->created_at;
            $extraStudentCharges = $extraStudentFeeCharges->online_additional_charges;

        }

        foreach ($duplicateJob_ticket_students as $row) {


            $multipleStudent = array(
                'student_name' => $row->student_name,
                'student_gender' => $row->student_gender,
                'student_age' => $row->student_age,
                'year_of_birth' => $row->year_of_birth,
                'special_need' => $row->special_need,
                'job_ticket_id' => $jobTicketLastID,
                'subject_id' => $row->subject_id,
                'extra_fee' => $extraStudentCharges,
                'extra_fee_date' => $extraStudentChargesDate,
            );
            DB::table('job_ticket_students')->insertGetId($multipleStudent);

        }

        $studentDetail = DB::table('students')->where('id', '=', $duplicateTicket->student_id)->first();
        $customerDetail = DB::table('customers')->where('id', '=', $studentDetail->customer_id)->first();

        // $ledgerValue = array(
        //     'payment_reference' => Auth::user()->id,
        //     'user_id' => Auth::user()->id,
        //     'bill_no' => $currentID->id + 1,
        //     'sale_id' => $currentID->id + 1,
        //     'account_id' => $customerDetail->id,
        //     'amount' => $currentID->totalPrice,
        //     'type' => 'd',
        //     'debit' => $currentID->totalPrice,
        //     'credit' => 0,
        //     'date' => date('Y-m-d'),
        //     'date_2' => date('Y-m-d')
        // );

        // $ledgerID = DB::table('payments')->insertGetId($ledgerValue);

        $invoiceValue = array(
            'tutorID' => $duplicateTicket->tutor_id,
            'studentID' => $duplicateTicket->student_id,
            'ticketID' => $jobTicketLastID,
            'subjectID' => $duplicateStudent_subjects->subject,
            'invoiceID' => $jobTicketLastID,
            'account_id' => $customerDetail->id,
            'invoiceDate' => date('Y-m-d'),
            'reference' => $jobTicketLastID,
            'payerName' => $customerDetail->full_name,
            'payerEmail' => $customerDetail->email,
            'payerPhone' => $customerDetail->phone,
            'type' => 'd',
            'debit' => $currentID->totalPrice,
            'credit' => 0,
            'invoiceTotal' => $currentID->totalPrice,
            'brand' => $duplicateTicket->service);

        $ledgerID = DB::table('invoices')->insertGetId($invoiceValue);

        // dd($id);
        $invoiceItems = DB::table('invoice_items')->where('ticketID', $id)->get();

        foreach ($invoiceItems as $invoiceItem) {

            $itemData = (array)$invoiceItem;
            unset($itemData['id']);

            $itemData['invoiceID'] = $ledgerID;
            $itemData['ticketID'] = $jobTicketLastID;


            DB::table('invoice_items')->insert($itemData);
        }

         
        
        return redirect('TicketList')->with('success', 'ticket has been added successfully!');

    }


    public function viewTicket($id)
    {

        $user_id = Auth::user()->id;
        $checked = DB::table('loggedInUsers')->where('user_id', '=', $user_id)->where('last_login', '<>', date("Y-m-d H"))->where('pageUrl', '=', url()->full())->first();
        if (!$checked) {
            DB::table('loggedInUsers')->insert([
                'user_id' => $user_id,
                'last_login' => date('Y-m-d H:i:s'),
                'pageUrl' => url()->full(),
                'detail' => 'View dashboard at home page'
            ]);
        }

        $students = DB::table('students')->get();
        $customers = DB::table('customers')->get();
        $tutors = DB::table('tutors')->get();
        $subjects = DB::table('products')->get();

        $tickets = DB::table('job_tickets')->where('job_tickets.id', '=', $id)
            ->join('products', 'job_tickets.subjects', '=', 'products.id')
            ->join('students', 'students.id', '=', 'job_tickets.student_id')
            ->join('customers', 'customers.id', '=', 'students.customer_id')
            ->select('job_tickets.*', 'products.name as subject_name',
                'customers.full_name as customerName',
                'customers.email as customerEmail',
                'customers.phone as customerPhone',
                'customers.whatsapp as customerWhatsapp',
                'customers.age as customerAge',
                'students.dob as customerDOB',
                'customers.gender as customerGender',
                'customers.nric as customerNRIC',
                'customers.address1 as customerAddress1',
                'customers.address2 as customerAddress2',
                'customers.postal_code as customerPostalCode',
                'customers.city as customerCity',
                'customers.state as customerState',
                'customers.latitude as customerLatitude',
                'customers.longitude as customerLongitude',
                'customers.id as customerId',
                'job_tickets.uid as jtuid',
                'job_tickets.student_id as student_id',
                'products.id as subject_id')
            ->first();

        $studentDetail = DB::table('students')->where('id', '=', $tickets->student_id)->first();

        $tutorOffers = DB::table('tutoroffers')->where('ticketID', '=', $id)->first();

        $subjects = DB::table('products')->get();


        // dd($tickets);

        $customerCommitmentFeeCheck = DB::table('customer_commitment_fees')->where('customer_id', '=', $tickets->customerId)->first();

        $customerPaymentFlag = false;

        if ($customerCommitmentFeeCheck == null) {
            $customerPaymentFlag = false;
        } else {
            $customerPaymentFlag = true;
        }

        $ticketOffersCheck = DB::table('class_schedules')->where('ticketID', '=', $id)->where("status", "Pending")->first();

        // dd($ticketOffersCheck);
        // dd($tickets);

        // dd($subjects);

        return view('job_tickets/viewTicket', compact('students', 'tutors', 'subjects', 'customers', 'tickets', 'studentDetail', 'tutorOffers', 'customerPaymentFlag', 'customerCommitmentFeeCheck', 'ticketOffersCheck'));


    }

    public function editTicket($id)
    {


        $students = DB::table('students')->get();
        $customers = DB::table('customers')->get();
        // $subjects = DB::table('products')->get();


        $subjects = DB::table('products')->join("categories", "products.category", "=", "categories.id")
            ->select("products.*", "categories.price as category_price", "categories.category_name as category_name", "categories.mode as mode")->get();

        $singleTicket = DB::table('job_tickets')->where('id', '=', $id)->first();
        $extraStudents = DB::table('job_ticket_students')->where('job_ticket_id', '=', $singleTicket->id)->get();
        $singleStudent = DB::table('students')->where('id', '=', $singleTicket->student_id)->first();
        $singleCustomer = DB::table('customers')->join("cities", "customers.city", "=", "cities.id")->join("states", "customers.state", "=", "states.id")
            ->select("customers.*", "cities.name as city_name", "states.name as state_name")
            ->where('customers.id', '=', $singleStudent->customer_id)->first();
        $singleSubject = DB::table('products')->where('id', '=', $singleTicket->subjects)->first();

        $ticketDays = explode(",", $singleTicket->day);

        return view('job_tickets/editTicket', compact('students', 'ticketDays', 'subjects', 'customers', 'singleTicket', 'singleStudent', 'extraStudents', 'singleCustomer', 'singleSubject'));

    }

    public function submitEditJobTicket(Request $request)
    {


        $data = $request->all();

        $extraStudentFeeCharges = DB::table('extra_student_charges')->orderBy('id', 'DESC')->first();
        $subjectFee = DB::table('products')->join("categories", "products.category", "=", "categories.id")
            ->select("products.*", "categories.price as category_price", "categories.mode as mode")
            ->where('products.id', '=', $data['subject'][0])->first();


        if (isset($data['extraNewStudent'])) {
            $totalCount = count($data['extraNewStudent']);

            $extraFee = 0;
            if ($subjectFee->mode == "physcial") {
                $extraFee = $extraStudentFeeCharges->physical_additional_charges;
                $extraFeeDate = $extraStudentFeeCharges->created_at;
            } else {
                $extraFee = $extraStudentFeeCharges->online_additional_charges;
                $extraFeeDate = $extraStudentFeeCharges->created_at;

            }
            for ($j = 0; $j < count($data['extraNewStudent']); $j++) {
                if ($data['extraNewStudent'][$j] != NULL) {

                    if ($data['extraNewStudent'][$j] == 'extraNewStudent') {

                        $studentValuesTwo = array(
                            'uid' => 'ST-' . date('dis'),
                            'student_id' => 'ST-' . date('dis'),
                            'full_name' => $data['studentFullName'][$j],
                            'register_date' => $request->registration_date,
                            'customer_id' => $request->parent_id,
                            'fee_payment_date' => $request->FeePaymentDate,
                            'gender' => $data['studentGender'][$j],
                            'age' => $data['age'][$j],
                            'dob' => $data['studentDateOfBirth'][$j],
                            'specialNeed' => $data['specialNeed'][$j]
                        );
                        $studentLastID = DB::table('students')->insertGetId($studentValuesTwo);

                        $multipleStudent = array(
                            'student_id' => $studentLastID,
                            'student_name' => $data['studentFullName'][$j],
                            'student_gender' => $data['studentGender'][$j],
                            'student_age' => $data['age'][$j],
                            'year_of_birth' => $data['studentDateOfBirth'][$j],
                            'special_need' => $data['specialNeed'][$j],
                            'job_ticket_id' => $request->jobTicketID,
                            'extra_fee' => $extraFee,
                            'subject_id' => $data['subject'][0],
                            'extra_fee_date' => $extraFeeDate,
                        );
                        DB::table('job_ticket_students')->insertGetId($multipleStudent);
                    }
                }
            }
        } elseif (isset($data['extraOldStudent'])) {
            $oldStudents = DB::table("job_ticket_students")->where("job_ticket_id", $request->jobTicketID)->get();

            foreach ($oldStudents as $key => $oldStudent) {
                if (isset($data['studentFullName'][$key])) {
                    DB::table('job_ticket_students')->where("id", $oldStudent->id)->update([
                        'student_name' => $data['studentFullName'][$key],
                        'student_gender' => $data['studentGender'][$key],
                        'student_age' => $data['age'][$key],
                        'year_of_birth' => $data['studentDateOfBirth'][$key],
                        'special_need' => $data['specialNeed'][$key],
                        'status' => $data['status'][$key]
                    ]);
                }

            }

        }


        $dayArray = array();

        foreach ($data['day'] as $selectedDay) {
            $dayArray[] = $selectedDay;
        }

        $subjectFee = DB::table('products')->join("categories", "products.category", "=", "categories.id")
            ->select("products.*", "categories.price as category_price")
            ->where('products.id', '=', $data['subject'][0])->first();

        if (isset($data['extraNewStudent'])) {


            $job_ticket = DB::table("job_tickets")->where("id", $request->jobTicketID)->first();
            $estimate_commission = $job_ticket->estimate_commission + ($job_ticket->extra_student_tutor_commission * $totalCount);
            $estimate_commission_display_tutor = $job_ticket->estimate_commission_display_tutor + ($job_ticket->extra_estimate_commission_display_tutor * $totalCount);
            $totalPrice = $job_ticket->totalPrice + ($job_ticket->extra_student_total * $totalCount);
            $per_class_charges = $job_ticket->totalPrice / $job_ticket->classFrequency;
            $total_charges_remaining_classes = $per_class_charges * $job_ticket->remaining_classes;

            $invoice = DB::table("invoices")->where("ticketID", $job_ticket->id)->first();
            $invoiceData = (array)$invoice;


            $invoiceData["invoiceTotal"] = $total_charges_remaining_classes * $totalCount;
            $invoiceData['invoiceDate'] = date("Y-m-d");
            $invoiceData['reference'] = 0;
            $invoiceData['brand'] = 0;

            unset($invoiceData['id']);
            $newInvoiceId = DB::table('invoices')->insertGetId($invoiceData);
            // dd($newInvoiceId);
            $invoiceItems = DB::table('invoice_items')->where('invoiceID', $invoice->id)->get();

            foreach ($invoiceItems as $invoiceItem) {

                $itemData = (array)$invoiceItem;
                unset($itemData['id']);

                $itemData['invoiceID'] = $newInvoiceId;


                DB::table('invoice_items')->insert($itemData);
            }
            //end additional student invoice


            $invoice_detail = DB::table("invoices")->where("id", $newInvoiceId)->first();
            $invoice_items = DB::table("invoice_items")->where("invoiceID", $newInvoiceId)->get();

            $jobTicketDeails = DB::table("job_tickets")->where("id", $invoice->ticketID)->first();
            $students = DB::table('students')->where('id', '=', $invoice->studentID)->orderBy('id', 'DESC')->first();
            $customer = DB::table('customers')->where('id', '=', $students->customer_id)->first();
            $subjects = DB::table('products')->join("categories", "products.category", "=", "categories.id")
                ->select("products.*", "categories.price as category_price")
                ->where('products.id', '=', $invoice_detail->subjectID)->first();


            $Pdfdata = [
                'title' => 'Invoice',
                'content' => 'System Generated Invoice',
            ];


            // return view('pdf.additionaStudent', [
            //     'data' => $data,
            //     'remaining_classes'=>$job_ticket->remaining_classes,
            //     'invoice_items' => $invoice_items,
            //     'invoice_detail' => $invoice_detail,
            //     'students' => $students,
            //     'subjects' => $subjects,
            //     'customer' => $customer,
            //     'jobTicketDeails' => $jobTicketDeails,
            // ]);


            $pdf = PDF::loadView('pdf.additionaStudent', [
                'data' => $Pdfdata,
                'remaining_classes' => $job_ticket->remaining_classes,
                'invoice_items' => $invoice_items,
                'invoice_detail' => $invoice_detail,
                'students' => $students,
                'subjects' => $subjects,
                'customer' => $customer,
                'jobTicketDeails' => $jobTicketDeails,
            ]);
            $pdf->save(public_path('invoicePDF/') . "/" . "Invoice-" . $invoice_detail->id . ".pdf");

            $subjectTwo = 'Invoice';
            $headersTwo = "MIME-Version: 1.0" . "\r\n";
            $headersTwo .= "Content-type: multipart/mixed; boundary=\"boundary\"\r\n";
            // More headers
            $headersTwo .= 'From: <tutor@sifututor.com>' . "\r\n";

            $pdfPath = url("/public/invoicePDF/Invoice-") . $invoice_detail->id . ".pdf";

            $pdfContent = file_get_contents($pdfPath);
            $base64Content = base64_encode($pdfContent);

            // if (true) {
            $emailBody = "";
            $emailBody .= '
                        </tbody>
                        </table>
                        <table class="table table-responsive no-border">
                        <tbody>
                        <tr>
                            <td>
                                1) This invoice is computer-generated and no signature is required
                                <br>2) Payment is due within 3 working days of issuance of this invoice
                                <br>3) You can pay online via online banking by clicking the button PAY NOW or alternatively can transfer to account no below :
                                <br>
                                <br>MAYBANK - 562115516678 SIFU EDU & LEARNING SDN BHD
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
            $to = $customer->email;
            mail($to, $subjectTwo, $emailBody, $headersTwo);
            mail($to_email, $subjectTwo, $emailBody, $headersTwo);

            $affected = DB::table('job_tickets')
                ->where('id', $request->jobTicketID)
                ->update(['classAddress' => $request->classAddress,
                    'classLatitude' => $request->classLatitude,
                    'classLongitude' => $request->classLongitude,
                    'classCity' => $request->classCity,
                    'classState' => $request->classState,
                    'tutor_id' => $request->changeTutorID,
                    'classPostalCode' => $request->classPostalCode,
                    'admin_charge' => $request->inCharge,
//                    'subjects' => $data['subject'][0],
                    'subject_fee' => $subjectFee->category_price,
//                    'quantity' => $data['quantity'][0],
//                    'classFrequency' => $data['classFrequency'][0],
//                    'remaining_classes' => $data['classFrequency'][0],
//                    'tutorPereference' => $data['tutorPereference'][0],
                    'day' => json_encode(implode(",", $dayArray)),
                    'time' => $data['time'][0],
//                    'subscription' => $data['subscription'][0],
//                    'specialRequest' => $data['specialRequest'][0],
                    // 'register_date' => $request->registration_date,
                    // 'mode' => $request->classType,
                    'estimate_commission' => $estimate_commission,
                    'totalPrice' => $totalPrice,
                    'estimate_commission_display_tutor' => $estimate_commission_display_tutor,


                ]);
        }


        //get class schedules of old tutor and update tutorID
        $job_ticket_details = DB::table("job_tickets")->where("id", $request->jobTicketID)->first();
        if ($request->jobTicketID != $job_ticket_details->tutor_id) {
            $get_class_schedule = DB::table("class_schedules")->where("ticketID", $request->jobTicketID)->get();
            foreach ($get_class_schedule as $class_schedule) {
                DB::table("class_schedules")->where("id", $class_schedule->id)->
                where("status", "scheduled")->
                update(["tutorID" => $request->changeTutorID]);
            }
        }


//  dd($estimate_commission);

        return redirect()->back()->with('success', 'Job Ticket updated successfully');
    }

    public function submitTicket(Request $request)
    {
        $data = $request->all();

        if ($request->PaymentAttachment) {
            $imageName = time() . '.' . $request->PaymentAttachment->extension();
            $request->PaymentAttachment->move(public_path('PaymentAttachment'), $imageName);
        } else {
            $imageName = "";
        }

        // dd($request->all());

        if ($request->student_id == 'newStudent') {
            if ($request->parent_id == 'newParent') {
                $uuidForCustomer = rand(100, 99999);
                $customer_values = array(
                    'uid' => 'CUS-' . $uuidForCustomer,
                    'full_name' => $request->customerFullName,
                    'gender' => $request->customerGender,
                    'age' => $request->customerAge,
                    'email' => $request->customerEmail,
                    'dob' => $request->customerDateOfBirth,
                    'nric' => $request->customerCNIC,
                    'address1' => $request->address,
                    'city' => $request->customerCity,
                    'state' => $request->customerState,
                    'phone' => '+60' . $request->customerPhone,
                    'whatsapp' => '+60' . $request->customerWhatsapp,
                    'postal_code' => $request->customerPostalcode,
                    'latitude' => $request->customerLatitude,
                    'longitude' => $request->customerLongitude,
                    'customerable_type' => 0,
                    'customerable_id' => 0,
                    'landmark' => $request->landmark,
                    'remarks' => $request->remarks,
                    'staff_id' => $request->inCharge,
                );

                $customerLastID = DB::table('customers')->insertGetId($customer_values);
                $uuidForStudent = rand(100, 99999);
                $studentValues = array(
                    'uid' => 'ST-' . $uuidForStudent,
                    'student_id' => 'ST-' . $uuidForStudent,
                    'full_name' => $request->mainStudentFullName,
                    'register_date' => $request->registration_date,
                    'customer_id' => $customerLastID,
                    'gender' => $request->mainStudentGender,
                    'age' => $request->mainAge,
                    'email' => $request->studentEmail,
                    'phone' => '+60' . $request->studentPhone,
                    'whatsapp' => '+60' . $request->studentWhatsapp,
                    'dob' => $request->mainStudentYearOfBirth,
                    'cnic' => $request->studentNRIC,
                    'address1' => $request->address,
                    'specialNeed' => $request->mainStudentSpecialNeed,
                    'city' => $request->studentCity,
                    'state' => $request->studentState,
                    'postal_code' => $request->studentPostalcode,
                    'latitude' => $request->studentLatitude,
                    'longitude' => $request->studentLongitude,
                    'receiving_account' => $request->receivingAccount,
                    'remarks' => $request->remarks,
                    'staff_id' => $request->inCharge,
                );
                $studentLastID = DB::table('students')->insertGetId($studentValues);

                if (isset($data['studentFullName'])) {

                    for ($j = 0; $j < count($data['studentFullName']); $j++) {
                        if ($data['studentFullName'][$j] != NULL) {
                            if ($data['student_ids'][$j] == 'newStudent') {
                                $studentValuesTwo = array(
                                    'uid' => 'ST-' . $uuidForStudent,
                                    'student_id' => 'ST-' . $uuidForStudent,
                                    'full_name' => $data['studentFullName'][$j],
                                    'register_date' => $request->registration_date,
                                    'customer_id' => $customerLastID,
                                    'fee_payment_date' => $request->FeePaymentDate,
                                    'gender' => $data['studentGender'][$j],
                                    'age' => $data['age'][$j],
                                    'dob' => $data['studentDateOfBirth'][$j],
                                    'cnic' => $request->studentNRIC,
                                    'address1' => $request->address,
                                    'specialNeed' => $data['specialNeed'][$j],
                                    'staff_id' => $request->inCharge,
                                );
                                $studentLastIDTwo = DB::table('students')->insertGetId($studentValuesTwo);
                                DB::table('customers')->where("id", $customerLastID)->update(["student_id" => $studentLastIDTwo]);

                            }


                        }
                    }
                }
                DB::table('customers')->where("id", $customerLastID)->update(["student_id" => $studentLastID]);

            } else {

                $uuidForStudent = rand(100, 99999);
                $studentValues = array(
                    'uid' => 'ST-' . $uuidForStudent,
                    'student_id' => 'ST-' . $uuidForStudent,
                    'full_name' => $request->mainStudentFullName,
                    'register_date' => $request->registration_date,
                    'customer_id' => $request->parent_id,
                    'gender' => $request->mainStudentGender,
                    'age' => $request->mainAge,
                    'email' => $request->studentEmail,
                    'phone' => '+60' . $request->studentPhone,
                    'whatsapp' => '+60' . $request->studentWhatsapp,
                    'dob' => $request->mainStudentYearOfBirth,
                    'cnic' => $request->studentNRIC,
                    'address1' => $request->address,
                    'specialNeed' => $request->mainStudentSpecialNeed,
                    'city' => $request->studentCity,
                    'state' => $request->studentState,
                    'postal_code' => $request->studentPostalcode,
                    'latitude' => $request->studentLatitude,
                    'longitude' => $request->studentLongitude,
                    'receiving_account' => $request->receivingAccount,
                    'remarks' => $request->remarks,
                    'staff_id' => $request->inCharge,
                );
                $studentLastID = DB::table('students')->insertGetId($studentValues);

                if (isset($data['studentFullName'])) {
                    for ($j = 0; $j < count($data['studentFullName']); $j++) {
                        if ($data['studentFullName'][$j] != NULL) {
                            if ($data['student_ids'][$j] == 'newStudent') {
                                $uuidForAnotherStudent = rand(100, 99999);
                                $studentValuesTwo = array(
                                    'uid' => 'ST-' . $uuidForAnotherStudent,
                                    'student_id' => 'ST-' . $uuidForAnotherStudent,
                                    'full_name' => $data['studentFullName'][$j],
                                    'register_date' => $request->registration_date,
                                    'customer_id' => $request->parent_id,
                                    'gender' => $data['studentGender'][$j],
                                    'age' => $data['age'][$j],
                                    'dob' => $data['studentDateOfBirth'][$j],
                                    'specialNeed' => $data['specialNeed'][$j],
                                    'staff_id' => $request->inCharge,
                                );
                                $studentLastIDTwo = DB::table('students')->insertGetId($studentValuesTwo);
                            }
                        }
                    }

                }
            }

        } else {
            $studentLastID = $request->student_id;

            if (isset($data['studentFullName'])) {
                for ($j = 0; $j < count($data['studentFullName']); $j++) {
                    if ($data['studentFullName'][$j] != NULL) {
                        if ($data['student_ids'][$j] == 'newStudent') {
                            $uuidForStudent = rand(100, 99999);
                            $studentValuesTwo = array(
                                'uid' => 'ST-' . $uuidForStudent,
                                'student_id' => 'ST-' . $uuidForStudent,
                                'full_name' => $data['studentFullName'][$j],
                                'register_date' => $request->registration_date,
                                'customer_id' => $request->existingParent_id,
                                'fee_payment_date' => $request->FeePaymentDate,
                                'gender' => $data['studentGender'][$j],
                                'age' => $data['age'][$j],
                                'dob' => $data['studentDateOfBirth'][$j],
                                'cnic' => $request->studentNRIC,
                                'address1' => $request->address,
                                'specialNeed' => $data['specialNeed'][$j],
                                'staff_id' => $request->inCharge,
                            );
                            $studentLastIDTwo = DB::table('students')->insertGetId($studentValuesTwo);
                        }


                    }
                }
            }

        }
        $data = $request->all();

        // dd("Done");

        $latestTicketID = DB::table('job_tickets')->latest('created_at')->first();
        if ($latestTicketID) {
            $ticketIDs = $latestTicketID->id + 1;
        } else {
            $ticketIDs = 1;
        }
        // dd($request->all());
        $subject = $data['subject'];

        for ($i = 0; $i < count($subject); $i++) {

            $dayArray = array();

            foreach ($data['day'][$i + 1] as $selectedDay) {
                $dayArray[] = $selectedDay;
            }

            $extraStudentFeeCharges = DB::table('extra_student_charges')->orderBy('id', 'DESC')->first();

            $subjectFee = DB::table('products')->join("categories", "products.category", "=", "categories.id")
                ->select("products.*", "categories.price as category_price", "categories.mode as mode")
                ->where('products.id', '=', $data['subject'][$i])->first();
            $uuidForTicket = rand(100, 99999);
            $ticektValues = array(
                'ticket_id' => $ticketIDs,
                'uid' => 'JT-' . $uuidForTicket,
                'student_id' => $studentLastID,
                'admin_charge' => $request->inCharge,
                'service' => $request->service,
                'payment_attachment' => $imageName,
                'fee_payment_date' => $request->PaymentDate,
                'fee_payment_amount' => $request->feeAmount / count($data['subject']),
                'receiving_account' => $request->ReceivingAccountId,
                'subjects' => $data['subject'][$i],
                'subject_fee' => $subjectFee->category_price,
                'quantity' => $data['quantity'][$i],
                'classFrequency' => $data['classFrequency'][$i],
                'remaining_classes' => $data['classFrequency'][$i],
                'tutorPereference' => $data['tutorPereference'][$i],
                'day' => json_encode(implode(",", $dayArray)),
                'time' => $data['time'][$i],
                'subscription' => $data['subscription'][$i],
                'specialRequest' => $data['specialRequest'][$i],
                'classAddress' => $request->classAddress,
                'classLatitude' => $request->classLatitude,
                'classLongitude' => $request->classLongitude,
                'classCity' => $request->classCity,
                'classState' => $request->classState,
                'classPostalCode' => $request->classPostalCode,
                'register_date' => $request->registration_date,
                'mode' => $request->classType,
                'estimate_commission' => $request->estimate_commission,
                'status' => 'pending'
            );
            $jobTicketLastID = DB::table('job_tickets')->insertGetId($ticektValues);

            $student_data = array(
                'student_id' => $studentLastID,
                'ticket_id' => $jobTicketLastID,
                'ticket_id2' => $ticketIDs,
                'subject' => $data['subject'][$i],
                'quantity' => $data['quantity'][$i],
                'classFrequency' => $data['classFrequency'][$i],
                'remaining_classes' => $data['classFrequency'][$i],
                'day' => json_encode(implode(",", $dayArray)),
                'time' => $data['time'][$i],
                'subscription' => $data['subscription'][$i],
                'specialRequest' => $data['specialRequest'][$i],
            );
            DB::table('student_subjects')->insertGetId($student_data);

            if (isset($data['studentFullName'])) {
                for ($j = 0; $j < count($data['studentFullName']); $j++) {
                    if ($data['studentFullName'][$j] != NULL) {

                        if ($subjectFee->mode == "physical") {
                            $extraStudentCharges = DB::table("extra_student_charges")->first();
                            $extraStudentChargesDate = $extraStudentCharges->created_at;
                            $extraStudentCharges = $extraStudentFeeCharges->physical_additional_charges;

                        } else {
                            $extraStudentCharges = DB::table("extra_student_charges")->first();
                            $extraStudentChargesDate = $extraStudentCharges->created_at;
                            $extraStudentCharges = $extraStudentFeeCharges->online_additional_charges;
                        }

                        $extra_student_charges = DB::table('extra_student_charges')->orderBy('id', 'DESC')->first();
                        $multipleStudent = array(
                            'student_id' => $studentLastID,
                            'student_name' => $data['studentFullName'][$j],
                            'student_gender' => $data['studentGender'][$j],
                            'student_age' => $data['age'][$j],
                            'year_of_birth' => $data['studentDateOfBirth'][$j],
                            'special_need' => $data['specialNeed'][$j],
                            'job_ticket_id' => $jobTicketLastID,
                            'subject_id' => $data['subject'][$i],
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
                where('products.id', '=', $data['subject'][$i])->first();

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
            // $ledgerValue = array(
            //     'payment_reference' => Auth::user()->id,
            //     'user_id' => Auth::user()->id,
            //     'bill_no' => $jobTicketID->uid,
            //     'sale_id' => $jobTicketID->uid,
            //     'account_id' => $customerDetail->id,
            //     'amount' => ($subjectDetail->price * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]) + ($count * $extraStudentCharges)),
            //     'type' => 'd',
            //     'debit' => ($subjectDetail->price * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]) + ($count * $extraStudentCharges)),
            //     'credit' => 0,
            //     'date' => date('Y-m-d'),
            //     'date_2' => date('Y-m-d')
            // );

            $price = $subjectDetail->category_price;
            $classFrequency = floatval($data['classFrequency'][$i]);
            $quantity = floatval($data['quantity'][$i]);

            if ($subjectDetail->class_mode == "physical") {
                $extraCharges = $count * $extraStudentFeeCharges->physical_additional_charges;
                $extraChargesTutorComission = $count * $extraStudentFeeCharges->tutor_physical;

            } else {
                $extraCharges = $count * $extraStudentFeeCharges->online_additional_charges;
                $extraChargesTutorComission = $count * $extraStudentFeeCharges->tutor_online;

            }

//            dd($extraChargesTutorComission);
            $result = $price * $classFrequency * $quantity + $extraCharges;
            // $ledgerID = DB::table('payments')->insertGetId($ledgerValue);

//            dd($extraCharges);

            // if ($request->feeAmount != NULL) {
            //     $ledgerValueTwo = array(
            //         'payment_reference' => Auth::user()->id,
            //         'user_id' => Auth::user()->id,
            //         'bill_no' => $jobTicketID->uid,
            //         'sale_id' => $jobTicketID->uid,
            //         'account_id' => $customerDetail->id,
            //         'amount' => $request->feeAmount / count($data['subject']),
            //         'type' => 'c',
            //         'saleDescription' => 'Commitment Fee - ' . $request->receivingAccountId,
            //         'sale_note' => $jobTicketID->uid,
            //         'credit' => $request->feeAmount / count($data['subject']),
            //         'debit' => null,
            //         'date' => date('Y-m-d'),
            //         'date_2' => date('Y-m-d')
            //     );
            //     $ledgerID = DB::table('payments')->insertGetId($ledgerValueTwo);
            // }

            $tableName = 'job_ticket_students';
            $count = DB::table($tableName)
                ->select(DB::raw('count(*) as count'))
                ->where('job_ticket_id', '=', $ticketIDs)
                ->first()
                ->count;


            $class_term = $jobTicketID->subscription; //Checking the subsciption of the class (Long term/short term)
            $modeOfClass = $subjectDetail->class_mode;  //Checking the class mode(Physical or Online)
            $category_id_subject = $subjectDetail->category;
            $category_level_subject = $subjectDetail->category_name;  //Getting the category of the class(i.e PT3, IGCSE, Pre-school, Diploma)
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

            $per_class_commission_before_eight_hours=0;
            $per_class_commission_after_eight_hours=0;


            if ($class_term == "LongTerm") {
                if ($modeOfClass == "online") {
                    switch ($category_level_subject)
                    {

                        case "Pre-school":
                            $numberOfSessions = $data['classFrequency'][$i]*floatval($data['quantity'][$i]);
                            $per_hour_charges = $long_term_online_first_eight_hours["Pre-school"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["Pre-school"];

                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions )/$numberOfSessions;
                                $per_class_commission_after_eight_hours=0;

                            } else {

                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8 )/8;
                                $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                                $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions-8)) / ($numberOfSessions - 8);

                            }
                            


                            break;

                        case "UPSR":
                            $numberOfSessions = $data['classFrequency'][$i]*floatval($data['quantity'][$i]);
                            
                          
                            $per_hour_charges = $long_term_online_first_eight_hours["UPSR"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["UPSR"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions )/$numberOfSessions;
                                $per_class_commission_after_eight_hours=0;

                            } else {

                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8 )/8;
                                $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                                $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions-8)) / ($numberOfSessions - 8);

                            }
                            
                          
                            break;

                        case "PT3":
                            $numberOfSessions = $data['classFrequency'][$i]*floatval($data['quantity'][$i]);
                            $per_hour_charges = $long_term_online_first_eight_hours["PT3"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["PT3"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                             if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions )/$numberOfSessions;
                                $per_class_commission_after_eight_hours=0;

                            } else {

                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8 )/8;
                                $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                                $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions-8)) / ($numberOfSessions - 8);

                            }
                            

                            break;

                        case "SPM":
                           $numberOfSessions = $data['classFrequency'][$i]*floatval($data['quantity'][$i]);

                            $per_hour_charges = $long_term_online_first_eight_hours["SPM"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["SPM"];

                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions )/$numberOfSessions;
                                $per_class_commission_after_eight_hours=0;

                            } else {

                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8 )/8;
                                $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                                $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions-8)) / ($numberOfSessions - 8);

                            }
                            
                            break;

                        case "IGCSE":
                            $numberOfSessions = $data['classFrequency'][$i]*floatval($data['quantity'][$i]);
                            $per_hour_charges = $long_term_online_first_eight_hours["IGCSE"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["IGCSE"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                             if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions )/$numberOfSessions;
                                $per_class_commission_after_eight_hours=0;

                            } else {

                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8 )/8;
                                $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                                $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions-8)) / ($numberOfSessions - 8);

                            }
                            
                            break;

                        case "STPM":
                            $numberOfSessions = $data['classFrequency'][$i]*floatval($data['quantity'][$i]);
                            $per_hour_charges = $long_term_online_first_eight_hours["STPM"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["STPM"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions )/$numberOfSessions;
                                $per_class_commission_after_eight_hours=0;

                            } else {

                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8 )/8;
                                $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                                $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions-8)) / ($numberOfSessions - 8);

                            }
                            
                            break;

                        case "A-level/Pre-U":
                            $numberOfSessions = $data['classFrequency'][$i]*floatval($data['quantity'][$i]);
                            $per_hour_charges = $long_term_online_first_eight_hours["A-level/Pre-U"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["A-level/Pre-U"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                             if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions )/$numberOfSessions;
                                $per_class_commission_after_eight_hours=0;

                            } else {

                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8 )/8;
                                $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                                $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions-8)) / ($numberOfSessions - 8);

                            }
                            
                            break;

                        case "Diploma":
                           $numberOfSessions = $data['classFrequency'][$i]*floatval($data['quantity'][$i]);
                            $per_hour_charges = $long_term_online_first_eight_hours["Diploma"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["Diploma"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions )/$numberOfSessions;
                                $per_class_commission_after_eight_hours=0;

                            } else {

                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8 )/8;
                                $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                                $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions-8)) / ($numberOfSessions - 8);

                            }
                            
                            break;

                        case "Degree":
                            $numberOfSessions = $data['classFrequency'][$i]*floatval($data['quantity'][$i]);
                            $per_hour_charges = $long_term_online_first_eight_hours["Degree"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["Degree"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                             if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions )/$numberOfSessions;
                                $per_class_commission_after_eight_hours=0;

                            } else {

                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8 )/8;
                                $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                                $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions-8)) / ($numberOfSessions - 8);

                            }
                            
                            break;

                        case "ACCA":
                            $numberOfSessions = $data['classFrequency'][$i]*floatval($data['quantity'][$i]);
                            $per_hour_charges = $long_term_online_first_eight_hours["ACCA"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["ACCA"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions )/$numberOfSessions;
                                $per_class_commission_after_eight_hours=0;

                            } else {

                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8 )/8;
                                $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                                $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions-8)) / ($numberOfSessions - 8);

                            }
                            
                            break;

                        case "Master":
                           $numberOfSessions = $data['classFrequency'][$i]*floatval($data['quantity'][$i]);
                            $per_hour_charges = $long_term_online_first_eight_hours["Master"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["Master"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));


                                if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions )/$numberOfSessions;
                                $per_class_commission_after_eight_hours=0;

                            } else {

                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8 )/8;
                                $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                                $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions-8)) / ($numberOfSessions - 8);

                            }
                            
                            
                             
                            break;

                    }
                }
                elseif ($modeOfClass == "physical")
                {

                    switch ($category_level_subject) {

                        case "Pre-school":
                            $numberOfSessions = $data['classFrequency'][$i]*floatval($data['quantity'][$i]);
                            $per_hour_charges = $long_term_physical_first_eight_hours["Pre-school"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["Pre-school"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions )/$numberOfSessions;
                                $per_class_commission_after_eight_hours=0;

                            } else {

                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8 )/8;
                                $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                                $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions-8)) / ($numberOfSessions - 8);

                            }
                            


                            break;

                        case "UPSR":
                            $numberOfSessions = $data['classFrequency'][$i]*floatval($data['quantity'][$i]);
                            $per_hour_charges = $long_term_physical_first_eight_hours["UPSR"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["UPSR"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                             if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions )/$numberOfSessions;
                                $per_class_commission_after_eight_hours=0;

                            } else {

                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8 )/8;
                                $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                                $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions-8)) / ($numberOfSessions - 8);

                            }
                            

                            break;

                        case "PT3":
                            $numberOfSessions = $data['classFrequency'][$i]*floatval($data['quantity'][$i]);
                            $per_hour_charges = $long_term_physical_first_eight_hours["PT3"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["PT3"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                             if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions )/$numberOfSessions;
                                $per_class_commission_after_eight_hours=0;

                            } else {

                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8 )/8;
                                $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                                $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions-8)) / ($numberOfSessions - 8);

                            }
                            

                            break;

                        case "SPM":
                            $numberOfSessions = $data['classFrequency'][$i]*floatval($data['quantity'][$i]);
                            $per_hour_charges = $long_term_physical_first_eight_hours["SPM"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["SPM"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions )/$numberOfSessions;
                                $per_class_commission_after_eight_hours=0;

                            } else {

                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8 )/8;
                                $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                                $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions-8)) / ($numberOfSessions - 8);

                            }
                            

                            break;

                        case "IGCSE":
                            $numberOfSessions = $data['classFrequency'][$i]*floatval($data['quantity'][$i]);
                            $per_hour_charges = $long_term_physical_first_eight_hours["IGCSE"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["IGCSE"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                             if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions )/$numberOfSessions;
                                $per_class_commission_after_eight_hours=0;

                            } else {

                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8 )/8;
                                $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                                $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions-8)) / ($numberOfSessions - 8);

                            }
                            

                            break;

                        case "STPM":
                            $numberOfSessions = $data['classFrequency'][$i]*floatval($data['quantity'][$i]);
                            $per_hour_charges = $long_term_physical_first_eight_hours["STPM"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["STPM"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                             if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions )/$numberOfSessions;
                                $per_class_commission_after_eight_hours=0;

                            } else {

                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8 )/8;
                                $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                                $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions-8)) / ($numberOfSessions - 8);

                            }
                            

                            break;

                        case "A-level/Pre-U":
                           $numberOfSessions = $data['classFrequency'][$i]*floatval($data['quantity'][$i]);
                            $per_hour_charges = $long_term_physical_first_eight_hours["A-level/Pre-U"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["A-level/Pre-U"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                             if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions )/$numberOfSessions;
                                $per_class_commission_after_eight_hours=0;

                            } else {

                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8 )/8;
                                $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                                $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions-8)) / ($numberOfSessions - 8);

                            }
                            
                            break;

                        case "Diploma":
                            $numberOfSessions = $data['classFrequency'][$i]*floatval($data['quantity'][$i]);
                            $per_hour_charges = $long_term_physical_first_eight_hours["Diploma"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["Diploma"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                              if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions )/$numberOfSessions;
                                $per_class_commission_after_eight_hours=0;

                            } else {

                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8 )/8;
                                $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                                $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions-8)) / ($numberOfSessions - 8);

                            }
                            
                            break;

                        case "Degree":
                             $numberOfSessions = $data['classFrequency'][$i]*floatval($data['quantity'][$i]);
                            $per_hour_charges = $long_term_physical_first_eight_hours["Degree"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["Degree"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                              if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions )/$numberOfSessions;
                                $per_class_commission_after_eight_hours=0;

                            } else {

                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8 )/8;
                                $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                                $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions-8)) / ($numberOfSessions - 8);

                            }
                            

                            break;

                        case "ACCA":
                           $numberOfSessions = $data['classFrequency'][$i]*floatval($data['quantity'][$i]);
                            $per_hour_charges = $long_term_physical_first_eight_hours["ACCA"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["ACCA"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                              if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions )/$numberOfSessions;
                                $per_class_commission_after_eight_hours=0;

                            } else {

                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8 )/8;
                                $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                                $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions-8)) / ($numberOfSessions - 8);

                            }
                            

                            break;

                        case "Master":
                            $numberOfSessions = $data['classFrequency'][$i]*floatval($data['quantity'][$i]);
                            $per_hour_charges = $long_term_physical_first_eight_hours["Master"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["Master"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                             if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions )/$numberOfSessions;
                                $per_class_commission_after_eight_hours=0;

                            } else {

                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
                                $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8 )/8;
                                $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
                                $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions-8)) / ($numberOfSessions - 8);

                            }
                            

                            break;

                    }
                }
            } else {
                if ($modeOfClass == "online") {


                    switch ($category_level_subject) {

                        case "Pre-school":

                            $per_hour_charges = $short_term_online["Pre-school"];

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];

                            break;


                        case "UPSR":
                            $per_hour_charges = $short_term_online["UPSR"];

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];

                            break;

                        case "PT3":
                            $per_hour_charges = $short_term_online["PT3"];

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];

                            break;


                        case "SPM":

                            $per_hour_charges = $short_term_online["SPM"];

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];

                            break;


                        case "IGCSE":
                            $per_hour_charges = $short_term_online["IGCSE"];

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];

                            break;

                        case "STPM":
                            $per_hour_charges = $short_term_online["STPM"];

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];

                            break;

                        case "A-level/Pre-U":
                            $per_hour_charges = $short_term_online["A-level/Pre-U"];

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];

                            break;

                        case "Diploma":
                            $per_hour_charges = $short_term_online["Diploma"];

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];

                            break;

                        case "Degree":
                            $per_hour_charges = $short_term_online["Degree"];

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];

                            break;

                        case "ACCA":
                            $per_hour_charges = $short_term_online["ACCA"];

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];

                            break;

                        case "Master":
                            $per_hour_charges = $short_term_online["Master"];

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];

                            break;

                    }
                }
                elseif ($modeOfClass == "physical")
                {

                    switch ($category_level_subject) {

                        case "Pre-school":
                            $per_hour_charges = $short_term_physical["Pre-school"];

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];

                            break;

                        case "UPSR":
                            $per_hour_charges = $short_term_physical["UPSR"];

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];

                            break;

                        case "PT3":
                            $per_hour_charges = $short_term_physical["PT3"];

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];

                            break;

                        case "SPM":
                            $per_hour_charges = $short_term_physical["SPM"];

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];

                            break;

                        case "IGCSE":
                            $per_hour_charges = $short_term_physical["IGCSE"];

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];

                            break;

                        case "STPM":
                            $per_hour_charges = $short_term_physical["STPM"];

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];

                            break;

                        case "A-level/Pre-U":
                            $per_hour_charges = $short_term_physical["A-level/Pre-U"];

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];

                            break;

                        case "Diploma":
                            $per_hour_charges = $short_term_physical["Diploma"];

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];

                            break;

                        case "Degree":
                            $per_hour_charges = $short_term_physical["Degree"];

                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];

                            break;

                        case "ACCA":
                            $per_hour_charges = $short_term_physical["ACCA"];
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];

                            break;

                        case "Master":
                            $per_hour_charges = $short_term_physical["Master"];
                            $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];
                            $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]))/$data['classFrequency'][$i];

                            break;

                    }
                }
            }


            if ((isset($data["studentFullName"]))) {

                $jobTicketCalc = $subjectFee->category_price + $extraCharges;
                $jobTicketCalc = $jobTicketCalc * $data['classFrequency'][$i] * $data['quantity'][$i];
            } else {
                $jobTicketCalc = $subjectFee->category_price;
                $jobTicketCalc = $jobTicketCalc * $data['classFrequency'][$i] * $data['quantity'][$i];
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

            }
            else {
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
                $additionalStudentCharges = $additionalStudentChargesTutor * 1 * (floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                $additionalStudentChargesJobTicket = $additionalStudentChargesJobTicket * 1 * (floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
            }
            else {
                $additionalStudentCharges = $additionalStudentChargesTutor * 1 * (floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                $additionalStudentChargesJobTicket = $additionalStudentChargesJobTicket * 1 * (floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

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
                $calcPrice = $calcPrice * $data['classFrequency'][$i];
                $calcPrice = $calcPrice * $data['quantity'][$i];
            } else {

                $calcPrice = $subjectDetail->category_price;
                $calcPrice = $calcPrice * $data['classFrequency'][$i];
                $calcPrice = $calcPrice * $data['quantity'][$i];
            }

            // dd($estimate_commission);
            $invoiceValue = array(
                'studentID' => $studentLastID,
                'ticketID' => $jobTicketLastID,
                'subjectID' => $data['subject'][$i],
                'account_id' => $customerDetail->id,
                'invoiceDate' => date('Y-m-d'),
                'reference' => $jobTicketLastID,
                'payerName' => $customerDetail->full_name,
                'payerEmail' => $customerDetail->email,
                'payerPhone' => $customerDetail->phone,
                'quantity' => $data['quantity'][$i],
                'classFrequency' => $data['classFrequency'][$i],
                'day' => json_encode(implode(",", $dayArray)),
                'time' => $data['time'][$i],
                'type' => 'd',
                'debit' => ($subjectDetail->price * $data['classFrequency'][$i] * $data['quantity'][$i]) + ($extraCharges),
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
            $perClassPriceInvoiceItem=$jobTicketCalc/$request->classFrequency[0];
            // Insert records for each day and each occurrence based on ClassFrequency
            for ($j = 0; $j < $data['classFrequency'][$i]; $j++) {
                // Iterate over each day
                $currentDay = $daysArray[$j % count($daysArray)];
                // Get the day for the current iteration using modulus

                // Calculate the date based on the current day
                $date = clone $initialDate;
                while ($date->format('D') !== $currentDay) {
                    $date->add(new DateInterval('P1D'));
                }

                // Update the initial date to the next occurrence of the current day
                $initialDate = clone $date;
                $initialDate->add(new DateInterval('P1D'));


                // Modify data as needed for each iteration
                $invoiceItemsData['quantity'] = $data['quantity'][$i];
                $invoiceItemsData['time'] = $data['time'][$i];
                $invoiceItemsData['day'] = $currentDay;
                $invoiceItemsData['isPaid'] = 'unPaid';
                $invoiceItemsData['studentID'] = $studentLastID;
                $invoiceItemsData['ticketID'] = $jobTicketLastID;
                $invoiceItemsData['subjectID'] = $data['subject'][$i];
                $invoiceItemsData['invoiceID'] = $invoiceID;
                $invoiceItemsData['invoiceDate'] = $date->format('Y-m-d');
                $invoiceItemsData['price'] =$perClassPriceInvoiceItem;

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


            // $subjects = DB::table('products')->where('id','=',$invoice_detail->subjectID)->orderBy('id','DESC')->first();


            //dd($invoice_items);

            $tutorListings = DB::table('tutors')->where('status', '=', 'verified')->get();
            $jobTicketDeails = DB::table('job_tickets')->where('id', '=', $jobTicketLastID)->first();


            // $data = [
            //     'title' => 'Invoice',
            //     'content' => 'System Generated Invoice',
            // ];


            //dd($invoice_items);

            // Generate PDF from a view
            // $pdf = PDF::loadView('pdf.invoice', [
            //     'data' => $data,
            //     'invoice_items' => $invoice_items,
            //     'invoice_detail' => $invoice_detail,
            //     'students' => $students,
            //     'subjects' => $subjects,
            //     'customer' => $customer,
            //     'jobTicketDeails'=>$jobTicketDeails,
            // ]);

            // $pdf->save(public_path('invoicePDF/')."/"."Invoice-".$invoice_detail->id.".pdf");



            if(isset($customer)&& $customer->whatsapp!=null)

            {
                
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


                // $whatsapp_api = new WhatsappApi();
                // $phone_number = $customer->whatsapp;
                // $message = "Dear Parent. New Invoice of ".$invoice_detail->invoiceTotal." has been generated against ticket No: ".$jobTicketDeails->uid;
                // $whatsapp_api->send_message($phone_number, $message);
            }


            foreach ($tutorListings as $rowTutorListings) {
                $sms_api = new SmsNiagaApi();
                $whatsapp_api = new WhatsappApi();

                $phone_number = $rowTutorListings->whatsapp;
                $message = 'Dear Tutor: *' . $rowTutorListings->full_name . '*, A Class Ticket has been generated. Class Ticket # *' . $jobTicketDeails->uid . '*';
                $whatsapp_api->send_message($phone_number, $message);
                $sms_api->sendSms($phone_number, $message);
            }

            $tutorDevices = DB::table('tutor_device_tokens')->distinct()->get(['device_token', 'tutor_id']);
            //dd($tutorDevices);
            foreach ($tutorDevices as $rowDeviceToken) {
                $push_notification_api = new PushNotificationLibrary();
                $title = 'JOB-Ticket Create Successfully';
                $message = 'Message JOB Ticket ';
                $deviceToken = $rowDeviceToken->device_token;
                $push_notification_api->sendPushNotification($deviceToken, $title, $message);
            }

        }

        //dD("Done");
        
         //new ticket creation event
        $data=["New Ticket Created"];
        event(new TicketCreated($data));

        return redirect('TicketList')->with('success', 'ticket has been added successfully!');
        //return view('job_tickets/addticket');
    }


    public function sendnotification()
    {
        $tutorDevices = DB::table('tutor_device_tokens')->get(['device_token', 'tutor_id']);

        foreach ($tutorDevices as $rowDeviceToken) {
            $push_notification_api = new PushNotificationLibrary();
            $title = 'JOB-Ticket Create Successfully Testing By Asim Saleem';
            $message = 'Message JOB Ticket Testing By Asim Saleem';
            $deviceToken = $rowDeviceToken->device_token;
            $push_notification_api->sendPushNotification($deviceToken, $title, $message);
        }
    }


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

    public function ticketAPI()
    {
        $tickets = DB::table('job_tickets')->get();
        return Response::json(['tickets' => $tickets]);
    }

    public function deleteTicket($id)
    {

        $ticketDetail = DB::table('job_tickets')->where('id', '=', $id)->first();

        DB::table('job_tickets')->where('id', '=', $id)->delete();
        DB::table('student_subjects')->where('ticket_id', '=', $id)->delete();
        DB::table('payments')->where('bill_no', '=', $ticketDetail->uid)->delete();
        DB::table('invoices')->where('ticketID', '=', $id)->delete();
        DB::table('invoice_items')->where('ticketID', '=', $id)->delete();
        DB::table('tutoroffers')->where('ticketID', '=', $id)->delete();
        return redirect()->back();

    }

    public function changeTutorID(Request $request)
    {

        $ticketDetail = DB::table('job_tickets')->where('id', '=', $request->ticketID)->first();

        $previousTutor = DB::table('tutors')->where('id', '=', $ticketDetail->tutor_id)->first();

        // API Endpoint URL
        $api_url = 'https://api.watext.com/hook/message';
        // API Key
        $api_key = 'e6f2cb62a2b54cfbb6a1b25fbfee6131';
        // Prepare data for the POST request
        $data = array(
            'apikey' => $api_key,
            'phone' => "+923118354191",
            'message' => '<br/> Thank you, ' . $previousTutor->full_name . ', for your valuable contributions! Best wishes in your future endeavors. Unfortunately! We have assigned this Class ticket # *' . $ticketDetail->uid . '* to another Tutor.',
        );
        // Initialize cURL session
        $ch = curl_init($api_url);
        // Set cURL options
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        // Execute cURL session and get the response
        $response = curl_exec($ch);
        // Close cURL session
        curl_close($ch);
        // Process the response
        if ($response === false) {
            echo 'Error sending message.';
        } else {
            // Check if the response is in JSON format
            $result = json_decode($response, true);
            // Debugging: Output the response for inspection
            // var_dump($result);
            if (is_array($result)) {
                // Check if 'message' index exists in the response
                if (isset($result['message'])) {
                    echo 'Error: ' . $result['message'];
                } elseif (isset($result['status']) && $result['status'] == 'success') {
                    echo 'Message sent successfully.';
                } else {
                    echo 'Error: Unexpected response format.';
                }
            } else {
                // Handle non-JSON response
                echo 'Non-JSON Response: ' . $response;
                // Additional logic for handling non-JSON response can be added here
            }
        }

        $tutorDetail = DB::table('tutors')->where('id', '=', $request->selectedValue)->first();
        $affected = DB::table('job_tickets')
            ->where('id', $request->ticketID)
            ->update(['tutor_id' => $request->selectedValue]);

        // API Endpoint URL
        $api_url = 'https://api.watext.com/hook/message';
        // API Key
        $api_key = 'e6f2cb62a2b54cfbb6a1b25fbfee6131';
        // Prepare data for the POST request
        $data = array(
            'apikey' => $api_key,
            'phone' => "+923118354191",
            'message' => 'Dear Tutor, *' . $tutorDetail->full_name . '* You have been assigned this Student Class Ticket # *' . $ticketDetail->uid . '* .',
        );
        // Initialize cURL session
        $ch = curl_init($api_url);
        // Set cURL options
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        // Execute cURL session and get the response
        $response = curl_exec($ch);
        // Close cURL session
        curl_close($ch);
        // Process the response
        if ($response === false) {
            echo 'Error sending message.';
        } else {
            // Check if the response is in JSON format
            $result = json_decode($response, true);
            // Debugging: Output the response for inspection
            // var_dump($result);
            if (is_array($result)) {
                // Check if 'message' index exists in the response
                if (isset($result['message'])) {
                    echo 'Error: ' . $result['message'];
                } elseif (isset($result['status']) && $result['status'] == 'success') {
                    echo 'Message sent successfully.';
                } else {
                    echo 'Error: Unexpected response format.';
                }
            } else {
                // Handle non-JSON response
                echo 'Non-JSON Response: ' . $response;
                // Additional logic for handling non-JSON response can be added here
            }
        }


        return Response::json(['status' => 200]);
    }
}
