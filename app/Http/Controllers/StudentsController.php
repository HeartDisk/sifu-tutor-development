<?php

namespace App\Http\Controllers;

use App\Mail\SendEmailInvoice;
use App\Mail\SubmitStudentInvoice;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\JobTicket;
use App\Models\InvoicePayment;
use App\Models\Student;
use Illuminate\Http\Request;
use DB;
use Auth;
use Illuminate\Support\Facades\Mail;
use Response;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\WatextApiService;
use Carbon\Carbon;
use Dirape\Token\Token;
use App\Events\Parent\StudentList;
use App\Events\Parent\PaymentHistory;


class StudentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getParentDetails($id)
    {
        $parent_data = Customer::find($id);
        $students = Student::where("customer_id", $id)->get();
        return response()->json(['parent_data' => $parent_data, 'students' => $students, 200]);
    }
    
    public function assignAdminInChargeStudent(Request $req)
    {
         $affected = DB::table('students')
            ->where('id', $req->student_id)
            ->update(['staff_id' => $req->staff_id]);

           return redirect()->back();
    }
    
    public function assignAdminInChargeCustomer(Request $req)
    {
       $affected = DB::table('customers')
            ->where('id', $req->customer_id)
            ->update(['staff_id' => $req->staff_id]);

           return redirect()->back(); 
    }

    public function index(Request $request)
    {
        DB::table('user_activities')->insert([
            'user' => \Illuminate\Support\Facades\Auth::user()->name,
            'module' => "Students",
            'action' => "viewed Students list",
        ]);

        $student = DB::table('students')
            // ->join('customer_commitment_fees', 'students.customer_id', '=', 'customer_commitment_fees.customer_id')
            ->join('customers', 'students.customer_id', '=', 'customers.id')
            ->select('students.*', 'customers.full_name as customer_full_name', 'customers.status as customer_status')
            ->distinct();

        // Check if the status filter is applied
        if ($request->status) {
            $student->where('students.status', $request->status);
        }

        // Check if the search query is applied
        if ($request->search) {
            $search = $request->search;
            $student->where(function ($query) use ($search) {
                $query->where('students.student_id', 'LIKE', "%{$search}%")
                    ->orWhere('students.full_name', 'LIKE', "%{$search}%")
                    ->orWhere('students.uid', 'LIKE', "%{$search}%")
                    ->orWhere('students.gender', 'LIKE', "%{$search}%")
                    ->orWhere('students.age', 'LIKE', "%{$search}%")
                    ->orWhere('students.specialNeed', 'LIKE', "%{$search}%")
                    ->orWhere('students.phone', 'LIKE', "%{$search}%")
                    ->orWhere('students.whatsapp', 'LIKE', "%{$search}%")
                    ->orWhere('students.dob', 'LIKE', "%{$search}%");
            });
        }

        // Check if the from_date and to_date filters are applied
        if ($request->from_date && $request->to_date) {
            $fromDate = $request->from_date;
            $toDate = $request->to_date;
            $student->whereBetween('students.register_date', [$fromDate, $toDate]);
        } elseif ($request->from_date) {
            $fromDate = $request->from_date;
            $student->where('students.register_date', '>=', $fromDate);
        } elseif ($request->to_date) {
            $toDate = $request->to_date;
            $student->where('students.register_date', '<=', $toDate);
        }

        // Get the students
        $students = $student->orderBy('students.id', 'DESC')->get();

        return view('student.index', compact('students'));
    }

    public function studentSchedule(Request $request)
    {

        $studentSearch = $request->student;
        $subjectsSearch = $request->subject;

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


        if ($studentSearch != null && $subjectsSearch != null) {
            // dd($studentSearch."-".$subjectsSearch);
            // dd("1");
            $job_tickets = DB::table('class_schedules')
                ->leftJoin("products", "class_schedules.subjectID", "=", "products.id")
                ->leftJoin("categories", "products.category", "=", "categories.id")
                ->select(
                    "class_schedules.*",
                    "products.name as subject_name",
                    "categories.category_name as category_name",
                    "categories.mode as mode"
                )
                ->where('class_schedules.studentID', $studentSearch)
                ->where('class_schedules.subjectID', $subjectsSearch)
                ->orderBy('class_schedules.id', 'DESC')->get();
        } else if ($studentSearch != null) {

            //  dd("2");
            $job_tickets = DB::table('class_schedules')
                ->leftJoin("products", "class_schedules.subjectID", "=", "products.id")
                ->leftJoin("categories", "products.category", "=", "categories.id")
                ->select(
                    "class_schedules.*",
                    "products.name as subject_name",
                    "categories.category_name as category_name",
                    "categories.mode as mode"
                )
                ->where('class_schedules.studentID', $studentSearch)
                ->orderBy('class_schedules.id', 'DESC')->get();
        } else if ($subjectsSearch != null) {
            //  dd("3");
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


        // dd($job_tickets);
        $jsonticket = $job_tickets;


        $students = DB::table('students')->orderBy('id', 'DESC')->get();
        $subjects = DB::table('products')->join("categories", "products.category", "=", "categories.id")
            ->select(
                "products.*",
                "categories.category_name as category_name",
                "categories.mode as mode"
            )
            ->get();


        return view('student/studentSchedule', Compact('students', 'subjects', 'studentSearch', 'subjectsSearch', 'jsonticket'));
    }


//   public function studentAssignments(Request $req)
//   {
//     $students = DB::table('students')->orderBy('id', 'DESC')->get();
//     $data = DB::table('student_subjects')
//       ->join('students', 'student_subjects.student_id', '=', 'students.id')
//       ->join('products', 'student_subjects.subject', '=', 'products.id')
//       ->join('tutors', 'student_subjects.tutor_id', '=', 'tutors.id')
//       ->select(
//         'student_subjects.*',
//         'students.full_name as student_name',
//         'students.student_id as student_id',
//         'products.name as product_name',
//         'tutors.full_name as tutor_name'
//       )
//       ->whereNotNull('student_subjects.id')->get();
//     return view('student.studentAssignment', compact('data'));
//   }

    public function studentAssignments(Request $req)
    {
        $query = DB::table('student_subjects')
            ->join('students', 'student_subjects.student_id', '=', 'students.id')
            ->join('products', 'student_subjects.subject', '=', 'products.id')
            ->join('tutors', 'student_subjects.tutor_id', '=', 'tutors.id')
            ->select(
                'student_subjects.*',
                'students.full_name as student_name',
                'students.student_id as student_id',
                'products.name as product_name',
                'tutors.full_name as tutor_name'
            );

        if ($req->search) {
            $query->where(function ($q) use ($req) {
                $q->where('students.full_name', 'LIKE', "%{$req->search}%")
                    ->orWhere('products.name', 'LIKE', "%{$req->search}%")
                    ->orWhere('tutors.full_name', 'LIKE', "%{$req->search}%");
            });
        }

        $data = $query->orderBy('student_subjects.id', 'DESC')->get();

        return view('student.studentAssignment', compact('data'));
    }


    public function addStudent()
    {
        //
        $customers = DB::table('customers')->get();
        $subjects = DB::table('products')->get();
        $staffs = DB::table('staffs')->get();
        return view('student/addStudent', Compact('customers', 'subjects', 'staffs'));
    }


    public function submitStudent(Request $request)
    {
        $data = $request->all();

        // dd($data);

        //$imageName = time().'.'.$request->PaymentAttachment->extension();
        //$request->PaymentAttachment->move(public_path('PaymentAttachment'), $imageName);
        $imageName = "";

        if ($request->parent_id == 'newParent') {

            $customer_values = array(
                'uid' => 'CUS-' . date('dis'),
                'full_name' => $request->customerFullName,

                'gender' => $request->customerGender,
                'age' => $request->customerAge,
                'email' => $request->customerEmail,
                'dob' => $request->customerDateOfBirth,
                'nric' => $request->customerCNIC,
                'phone' => '+60' . $request->customerPhone,
                'whatsapp' => '+60' . $request->customerWhatsapp,
                'address1' => $request->customerStreetAddress1,
                'address2' => $request->customerStreetAddress2,
                'city' => $request->customerCity,
                'state' => $request->customerState,
                'postal_code' => $request->customerPostalcode,
                'latitude' => $request->customerLatitude,
                'longitude' => $request->customerLongitude,
                'customerable_type' => 0,
                'customerable_id' => 0,
                'remarks' => $request->remarks,
                'staff_id' => $request->staff_id
            );

            $customerLastID = DB::table('customers')->insertGetId($customer_values);

            $studentValues = array(
                'uid' => 'ST-' . date('dis'),
                'student_id' => 'ST-' . date('dis'),
                'full_name' => $request->studentFullName,
                'admin_charge' => $request->adminIncharge_id,
                'register_date' => $request->registration_date,
                'customer_id' => $customerLastID,
                'fee_payment_date' => $request->FeePaymentDate,
                'payment_attachment' => $imageName,
                'receiving_account' => $request->receivingAccount,
                'gender' => $request->studentGender,
                'age' => $request->age,
                'phone' => '+60' . $request->customerPhone,
                'whatsapp' => '+60' . $request->customerWhatsapp,
                'email' => $request->customerEmail,
                'dob' => $request->studentDateOfBirth,
                'cnic' => $request->cnic,
                'address1' => $request->studentStreetAddress1,
                'address2' => $request->studentStreetAddress2,
                'city' => $request->customerCity,
                'postal_code' => $request->customerPostalcode,
                'latitude' => $request->customerLatitude,
                'longitude' => $request->customerLongitude,
                'receiving_account' => $request->receivingAccount,
                'remarks' => $request->remarks,
                'specialNeed' => $request->specialNeed,
                'staff_id' => $request->staff_id
            );

            $studentLastID = DB::table('students')->insertGetId($studentValues);
        } else {

            $studentValues = array(
                'uid' => 'ST-' . date('dis'),
                'student_id' => 'ST-' . date('dis'),
                'full_name' => $request->studentFullName,
                'register_date' => $request->registration_date,
                'customer_id' => $request->parent_id,
                'fee_payment_date' => $request->FeePaymentDate,
                'gender' => $request->studentGender,
                'age' => $request->age,
                'email' => $request->email,
                'dob' => $request->studentDateOfBirth,
                'cnic' => $request->cnic,
                'address1' => $request->address,
                'address2' => $request->streetAddress2,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'receiving_account' => $request->receivingAccount,
                'remarks' => $request->remarks,
                'specialNeed' => $request->specialNeed,
                'staff_id' => $request->staff_id
            );

            $studentLastID = DB::table('students')->insertGetId($studentValues);
        }
       
       $student = DB::table("students")->where("id",$studentLastID)->first();
       $customer = DB::table("customers")->where("id",$student->customer_id)->first();
       
        try {
           
            $data = [
                "ResponseCode" => "100",
                "message" => "New Student Stored Successfully"
            ];
            
            //tutor
            event(new StudentList($data,$customer->token));

        } catch(Exception $e) {
           return response()->json(["ResponseCode"=> "103",
        		"error"=> "Unable to get New Attendance"]); 
        }
       
        return redirect('Students')->with('success', 'Student added successfully!');
    }

    public function submitEditStudent(Request $request)
    {


        $studentValues = array(
            'uid' => 'ST-' . date('dis'),
            'student_id' => 'ST-' . date('dis'),
            'full_name' => $request->full_name,
            'admin_charge' => $request->adminIncharge_id,
            'register_date' => $request->registration_date,
            'fee_payment_date' => $request->FeePaymentDate,
            'receiving_account' => $request->receivingAccount,
            'gender' => $request->gender,
            'age' => $request->age,
            'phone' => '+60' . $request->customerPhone,
            'whatsapp' => '+60' . $request->customerWhatsapp,
            'email' => $request->customerEmail,
            'dob' => $request->dob,
            'cnic' => $request->cnic,
            'address1' => $request->studentStreetAddress1,
            'address2' => $request->studentStreetAddress2,
            'city' => $request->customerCity,
            'postal_code' => $request->customerPostalcode,
            'latitude' => $request->customerLatitude,
            'longitude' => $request->customerLongitude,
            'remarks' => $request->remarks,
            'specialNeed' => $request->specialNeed,
            'staff_id' => $request->staff_id
        );

        $studentLastID = DB::table('students')->where("id", $request->id)->update($studentValues);

        $student_subjects = $request->student_subject_id;


      if ($request->has('subject') && is_array($request->subject)) {
        foreach ($request->subject as $key => $subject) {
            $record = $request->student_subject_id[$key];
            DB::table('student_subjects')->where('id', $record)->update([
                'subject' => $request->subject[$key],
                'day' => $request->day[$key],
                'time' => $request->time[$key],
                'subscription' => $request->subscription[$key],
            ]);
        }
    }

        return redirect()->back();
        return redirect('Students')->with('success', 'Student updated successfully!');
    }


    public function submitEditCustomer(Request $request)
    {


        $whatsapp = str_replace('+60', '', $request->customerWhatsapp);
        $phone = str_replace('+60', '', $request->customerPhone);

        $customer_values = array(
            'full_name' => $request->customerFullName,
            'status' => $request->Status,
            'gender' => $request->customerGender,
            'age' => $request->customerAge,
            'email' => $request->customerEmail,
            'whatsapp' => $whatsapp,
            'phone' => $phone,
            'dob' => $request->dob,
            'nric' => $request->customerCNIC,
            'address' => $request->customerStreetAddress1,
            'address1' => $request->customerStreetAddress1,
            'status' => $request->status,
            'address2' => $request->customerStreetAddress2,
            'city' => $request->customerCity,
            'state' => $request->customerState,
            'postal_code' => $request->customerPostalcode,
            'latitude' => $request->customerLatitude,
            'longitude' => $request->customerLongitude,
            'customerable_type' => 0,
            'customerable_id' => 0,
            'landmark' => $request->landmark,
            'remarks' => $request->remarks,
            'staff_id' => $request->staff_id
        );

        $var1 = DB::table('customers')->where('id', $request->id)->update($customer_values);


        $customerCommitmentFeeCheck = DB::table('customer_commitment_fees')->where('customer_id', '=', $request->id)->first();

        if ($customerCommitmentFeeCheck == null && isset($request->paymentAttachment)) {
            $imageName = time() . '.' . $request->paymentAttachment->extension();
            $request->paymentAttachment->move(public_path('customerCommitmentFee'), $imageName);
            $feePaymentValue = array(
                'ticket_id' => $request->ticketID,
                'customer_id' => $request->id,
                'payment_attachment' => $imageName,
                'payment_amount' => $request->feeAmount,
                'payment_date' => $request->feePaymentDate,
                'receiving_account' => $request->receivingAccount,
            );
            $jobTicketLastID = DB::table('customer_commitment_fees')->insertGetId($feePaymentValue);
            DB::table('customers')->where('id', $request->id)->update(["status" => "active"]);
        } elseif (isset($request->paymentAttachment) || isset($request->feeAmount)) {

            if ($request->paymentAttachment) {
                //  dd("\2");
                $imageName = time() . '.' . $request->paymentAttachment->extension();
                $request->paymentAttachment->move(public_path('customerCommitmentFee'), $imageName);
                $feePaymentValue = array(
                    'ticket_id' => $request->ticketID,
                    'customer_id' => $request->id,
                    'payment_attachment' => $imageName,
                    'payment_amount' => $request->feeAmount,
                    'payment_date' => $request->feePaymentDate,
                    'receiving_account' => $request->receivingAccount,
                );
            } else {
                $feePaymentValue = array(
                    'ticket_id' => $request->ticketID,
                    'customer_id' => $request->id,
                    'payment_amount' => $request->feeAmount,
                    'payment_date' => $request->feePaymentDate,
                    'receiving_account' => $request->receivingAccount,
                );
            }


            DB::table('customer_commitment_fees')
                ->where('customer_id', $request->id)
                ->update($feePaymentValue);

            DB::table('customers')->where('id', $request->id)->update(["status" => "active"]);
        }

        $customer = DB::table('customers')->where('id', $request->id)->get();

        return redirect()->back()->with('success', 'Customer / Parent has been Updated successfully!');
    }


    public function submitEditInvoice(Request $request)
    {
        // Log the request data for debugging
        \Log::info('submitEditInvoice Request Data: ', $request->all());

        // Prepare invoice values for update
        $invoiceValues = array(
            'payerName' => $request->payerName,
            'payerEmail' => $request->payerEmail,
            'payerPhone' => $request->payerPhoneNumber,
            'remarks' => $request->remarks,
            'managementStatus' => $request->managementStatus,
            'type' => "c",
            'credit' => $request->invoiceTotal,
        );

        // Update the invoice
        DB::table('invoices')->where('id', $request->saleInvoiceID)->update($invoiceValues);

        // Delete existing invoice items and deductions
        DB::table('invoice_items')->where('invoiceID', $request->saleInvoiceID)->delete();
        DB::table('invoice_deductions')->where('invoiceID', $request->saleInvoiceID)->delete();

        // Retrieve all data from the request
        $data = $request->all();
        $accountID = $data['unitPrice'];
        $sum = 0;

        // Insert new invoice items and calculate the sum
        for ($i = 0; $i < count($accountID); $i++) {
            $invoiceItemValues = array(
                'invoiceID' => $request->saleInvoiceID,
                'description' => $data['description'][$i],
                'studentID' => $data['students'][$i],
                'price' => $data['unitPrice'][$i],
                'quantity' => $data['quantity'][$i],
                'sum' => ($data['unitPrice'][$i] * $data['quantity'][$i]),
                'subjectID' => $data['subject'][$i],
            );
            \Log::info('Inserting Invoice Item: ', $invoiceItemValues); // Debug log for each item
            $sum += ($data['unitPrice'][$i] * $data['quantity'][$i]);
            DB::table('invoice_items')->insert($invoiceItemValues);
        }

        // Insert new invoice deductions
        if (isset($data['deductionDescription']) && is_array($data['deductionDescription'])) {
            for ($i = 0; $i < count($data['deductionDescription']); $i++) {
                $invoiceDeductionValues = array(
                    'invoiceID' => $request->saleInvoiceID,
                    'description' => $data['deductionDescription'][$i],
                    'amount' => $data['deductionAmount'][$i],
                );
                \Log::info('Inserting Invoice Deduction: ', $invoiceDeductionValues); // Debug log for each deduction
                DB::table('invoice_deductions')->insert($invoiceDeductionValues);
            }
        }

        // Update the invoice total and class frequency
        DB::table('invoices')->where('id', $request->saleInvoiceID)->update([
            'invoiceTotal' => $sum,
            'classFrequency' => count($accountID)
        ]);

        return redirect()->back()->with('success', 'Invoice has been updated successfully!');
    }

    public function submitStudentInvoice(Request $request)
    {
        
        

        $invoice_detail = DB::table('invoices')->where('id', '=', $request->id)->orderBy('id', 'desc')->first();
        $invoice_items = DB::table('invoice_items')->where('isPaid', '=', 'unpaid')->where('invoiceID', '=', $request->id)->orderBy('id', 'desc')->get();

        $invoicePaymentValues = array(
            'invoiceID' => $request->id,
            'invoiceReference' => $invoice_detail->reference,
            'paymentID' => 'PC-' . date('dis'),
            'amount' => $request->amount,
            'receivingAccount' => $request->receivingAccount,
            'paymentAttachment' => $request->paymentAttachment
        );
        $invoicePaymentLastID = DB::table('invoicePayments')->insertGetId($invoicePaymentValues);


        $updateInvoice = array(
            'paymentDate' => $request->paymentDate,
            'status' => 'paid'
        );

        $affected = DB::table('invoices')->where('id', $request->id)->update($updateInvoice);

        $item = array(
            'isPaid' => 'paid',
            'paymentDate' => $request->invoiceDate,
        );

        $affected = DB::table('invoice_items')->where('invoiceID', $request->id)->update($item);

        // Send the email
        $toEmail = $request->payerEmail;
        $to = "binasift@gmail.com";
        Mail::to($toEmail)->send(new SubmitStudentInvoice($invoice_detail, $request->paymentDate));
        Mail::to($to)->send(new SubmitStudentInvoice($invoice_detail, $request->paymentDate));


        $customerDetail = DB::table('customers')->where('id', '=', $invoice_detail->account_id)->orderBy('id', 'desc')->first();
        
        $student = DB::table("students")->where("id",$request->id)->first();
        $student = DB::table("students")->where("id",$student->customer_id)->first();
        $customer = DB::table("customers")->where("id",$student->customer_id)->first();
        
       
        $ledgerValue = array(
            'payment_reference' => Auth::user()->id,
            'user_id' => Auth::user()->id,
            'bill_no' => $request->id,
            'sale_id' => $request->id,
            'account_id' => $invoice_detail->account_id,
            'amount' => $request->amount,
            'type' => 'c',
            'debit' => null,
            'credit' => $request->amount,
            'date' => date('Y-m-d'),
            'date_2' => date('Y-m-d')
        );

        $payment = DB::table('payments')->insertGetId($ledgerValue);
        
        try {
           
            $data = [
                "ResponseCode" => "100",
                "message" => "Invoice Paid Successfully."
            ];
            
            //tutor
            event(new PaymentHistory($data,$customer->token));

        } catch(Exception $e) {
           return response()->json(["ResponseCode"=> "103",
        		"error"=> "Unable to get New Attendance"]); 
        }

        return redirect('students/StudentInvoices')->with('success', 'Invoice has been added successfully!');
    }

    public function submitStudentInvoiceTwo(Request $request)
    {


        $invoice_detail = DB::table('studentinvoices')->where('id', '=', $request->id)->orderBy('id', 'desc')->first();
        $invoice_items = DB::table('studentinvoice_items')->where('isPaid', '=', 'unpaid')->where('studentinvoiceID', '=', $request->id)->orderBy('id', 'desc')->get();
        $invoice_itemstotalTime = DB::table('studentinvoice_items')->where('isPaid', '=', 'unpaid')->where('studentinvoiceID', '=', $request->id)->orderBy('id', 'desc')->sum('totalTime');
        $totalHours = round($invoice_itemstotalTime);
        $students = DB::table('students')->where('id', '=', $invoice_detail->studentID)->orderBy('id', 'DESC')->first();
        $tutors = DB::table('tutors')->where('id', '=', $invoice_detail->tutorID)->orderBy('id', 'DESC')->first();
        $subjects = DB::table('products')->where('id', '=', $invoice_detail->subjectID)->orderBy('id', 'DESC')->first();
        $totaAttendedHours = 0;
        $totalAmount = 0;

        $totalAmount = $totalHours * $subjects->price;

        $updateStudentInvoice = array(
            'date' => $request->invoiceDate,
            'referenceNumber' => $request->referenceNumber,
            'managementStatus' => $request->managementStatus,
            'brand' => $request->brand,
            'payerName' => $request->payerName,
            'payerEmail' => $request->payerEmail,
            'payerPhone' => $request->payerPhoneNumber,
            'totalAmount' => $totalAmount,
            'isPaid' => "paid",
            'remarks' => $request->remarks
        );


        $affected = DB::table('studentinvoices')->where('id', $request->id)->update($updateStudentInvoice);

        $item = array(
            'isPaid' => 'paid',
            'paymentDate' => $request->invoiceDate,
        );

        $affected = DB::table('studentinvoice_items')->where('studentinvoiceID', $request->id)->update($item);

        $to = $request->payerEmail;
        //$to = 'mantaqiilmi@gmail.com';
        $subject = "Invoice Paid:" . $request->invoiceDate . " -" . $request->referenceNumber;

        $message = "
                        <html>
                          <head></head>
                          <body>
                            <div class='card'>
                              <div class='nk-invoice'>
                                <div class='nk-invoice-head flex-column flex-sm-row'>
                                  <div class='nk-invoice-head-item mb-3 mb-sm-0'>
                                    <div class='nk-invoice-brand mb-1'>
                                      <h1>SifuTutor</h1>
                                    </div>

                                  </div>
                                  <div class='nk-invoice-head-item text-sm-end'>
                                    <div class='h3'>Invoices No: $request->referenceNumber</div>
                                    <div class='h3'>Invoices Date:  $request->invoiceDate</div>
                                  </div>
                                </div>
                                <div class='nk-invoice-head flex-column flex-sm-row'>
                                  <table class='table table-responsive no-border'>
                                    <tbody>
                                      <tr>
                                        <td>
                                          <strong>Payer Name: </strong> $request->payerName
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                          <strong>Payer Email: </strong> $request->payerEmail
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                          <strong>Payer Phone Number: </strong> $request->payerPhoneNumber
                                        </td>
                                      </tr>
                                      <tr>
                                        <td>
                                          <strong>Management Remark: </strong> $request->remarks
                                        </td>
                                      </tr>
                                      <tr>
                                        <td>
                                          <strong>Total Hours : </strong> $totalHours
                                        </td>
                                      </tr>

                                      <tr>
                                        <td>
                                          <strong>Paid Amount: </strong> $totalAmount
                                        </td>
                                      </tr>

                                    </tbody>
                                  </table>
                                </div>

                              </div>
                            </div </body>
                        </html>
                        ";

        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $headers .= 'From: <no-reply@sifututor.com>' . "\r\n";

        mail($to, $subject, $message, $headers);


        return redirect('students/StudentInvoices')->with('success', 'Invoice has been added successfully!');
    }

    public function submitAddInvoice(Request $request)
    {
        $data = $request->all();
        $invoiceValues = array(
            'invoiceDate' => $request->invoiceDate,
            'reference' => $request->reference,
            'managementStatus' => $request->managementStatus,
            'brand' => $request->brand,
            'payerName' => $request->payerName,
            'payerEmail' => $request->payerEmail,
            'payerPhone' => $request->payerPhoneNumber,
            'type' => "c",
            'credit' => $request->invoiceTotal,
            'status' => "pending",
            'customerRemark' => $request->customerRemark,
            'subjectID' => isset($data['subject'][0]) ? $data['subject'][0] : "",
            'studentID' => isset($data['students'][0]) ? $data['students'][0] : ""

        );

        $invoiceLastID = DB::table('invoices')->insertGetId($invoiceValues);

        $accountID = $data['unitPrice'];

        for ($i = 0; $i < count($accountID); $i++) {
            $invoiceValues = array(
                'invoiceID' => $invoiceLastID,
                'description' => $data['description'][$i],
                'studentID' => $data['students'][$i],
                'price' => $data['unitPrice'][$i],
                'quantity' => $data['quantity'][$i],
                'sum' => ($data['unitPrice'][$i] * $data['quantity'][$i]),
                'subjectID' => $data['subject'][$i],
            );
            $invoiceItemLastID = DB::table('invoice_items')->insertGetId($invoiceValues);
        }

        // dd($data['deductionAmount']);

        if (isset($data['deductionAmount'])) {
            for ($i = 0; $i < count($data['deductionAmount']); $i++) {
                $deductionAmountValues = array(
                    'invoiceID' => $invoiceLastID,
                    'description' => $data['deductionDescription'][$i],
                    'amount' => $data['deductionAmount'][$i],
                );
                $invoiceDeductionsLastID = DB::table('invoice_deductions')->insertGetId($deductionAmountValues);
            }

        }

        $getRecrod = DB::table('invoice_items')->where('id', '=', $invoiceItemLastID)->first();
        $getSum = DB::table('invoice_items')->where('invoiceID', '=', $getRecrod->invoiceID)->sum('sum');
        $getTotalDeductionsAmount = DB::table('invoice_deductions')->where('invoiceID', '=', $getRecrod->invoiceID)->sum('amount');
        DB::table('invoices')->where('id', $getRecrod->invoiceID)->update(['invoiceTotal' => $getSum - $getTotalDeductionsAmount]);

        return redirect('students/StudentInvoices')->with('success', 'Invoice has been added successfully!');
    }

    public function editStudent($id)
    {
        //
        $student = DB::table('students')->where('id', '=', $id)->first();
        $customer = DB::table('customers')->where('id', '=', $student->customer_id)->first();
        $subjects = DB::table('student_subjects')->where('student_id', '=', $student->id)->get();
        $allStudents = DB::table('students')->where('id', '=', $id)->first();
        $totalSubjects = DB::table('student_subjects')->where('student_id', '=', $id)->orderBy('id', 'desc')->count();
        $allCustomers = DB::table('customers')->get();
        $allSubjects = DB::table('products')->get();

        $customerState = DB::table('states')->where('id', '=', $customer->state)->first();
        $customerCity = DB::table('cities')->where('id', '=', $customer->city)->first();

        $studentState = DB::table('states')->where('id', '=', $student->state)->first();
        $studentCity = DB::table('cities')->where('id', '=', $student->city)->first();

        $staffs = DB::table('staffs')->get();


        return view('student/editStudent', compact('student', 'customer', 'subjects', 'allStudents', 'allCustomers', 'totalSubjects', 'allSubjects', 'customerState', 'customerCity', 'studentCity', 'studentState', 'staffs'));
    }


    public function studentDashboardClassSchedules($id)
    {
        $student = DB::table('students')->where('id', '=', $id)->first();
        $customer = DB::table('customers')->where('id', '=', $student->customer_id)->first();

        $customerState = DB::table('states')->where('id', '=', $customer->state)->first();
        $customerCity = DB::table('cities')->where('id', '=', $customer->city)->first();

        $studentState = DB::table('states')->where('id', '=', $student->state)->first();
        $studentCity = DB::table('cities')->where('id', '=', $student->city)->first();

        $subjects = DB::table('student_subjects')->where('student_id', '=', $student->id)->get();
        $allStudents = DB::table('students')->where('id', '=', $id)->first();
        $totalSubjects = DB::table('student_subjects')->where('student_id', '=', $id)->orderBy('id', 'desc')->count();
        $allCustomers = DB::table('customers')->get();
        $allSubjects = DB::table('products')->get();

        $studentCustomer = DB::table("customers")->where("id", $student->customer_id)->first();

        return view('student/viewStudentDashboardClassSchedules', compact('student', 'studentCustomer', 'customer', 'subjects', 'allStudents', 'allCustomers', 'totalSubjects', 'allSubjects', 'customerState', 'customerCity', 'studentState', 'studentCity'));
    }

    public function studentDashboard($id)
    {
        $student = DB::table('students')->where('id', '=', $id)->first();
        $customer = DB::table('customers')->where('id', '=', $student->customer_id)->first();

        $customerState = DB::table('states')->where('id', '=', $customer->state)->first();
        $customerCity = DB::table('cities')->where('id', '=', $customer->city)->first();

        $studentState = DB::table('states')->where('id', '=', $student->state)->first();
        $studentCity = DB::table('cities')->where('id', '=', $student->city)->first();

        $subjects = DB::table('student_subjects')->where('student_id', '=', $student->id)->get();
        $allStudents = DB::table('students')->where('id', '=', $id)->first();
        $totalSubjects = DB::table('student_subjects')->where('student_id', '=', $id)->orderBy('id', 'desc')->count();
        $allCustomers = DB::table('customers')->get();
        $allSubjects = DB::table('products')->get();
        return view('student/viewStudentDashboard', compact('student', 'customer', 'subjects', 'allStudents', 'allCustomers', 'totalSubjects', 'allSubjects', 'customerState', 'customerCity', 'studentState', 'studentCity'));
    }


    public function studentDashboardTickets($id)
    {
        $student = DB::table('students')->where('id', '=', $id)->first();
        $customer = DB::table('customers')->where('id', '=', $student->customer_id)->first();

        $customerState = DB::table('states')->where('id', '=', $customer->state)->first();
        $customerCity = DB::table('cities')->where('id', '=', $customer->city)->first();

        $studentState = DB::table('states')->where('id', '=', $student->state)->first();
        $studentCity = DB::table('cities')->where('id', '=', $student->city)->first();

        $subjects = DB::table('student_subjects')->where('student_id', '=', $student->id)->get();
        $allStudents = DB::table('students')->where('id', '=', $id)->first();
        $totalSubjects = DB::table('student_subjects')->where('student_id', '=', $id)->orderBy('id', 'desc')->count();
        $allCustomers = DB::table('customers')->get();
        $allSubjects = DB::table('products')->get();
        return view('student/viewStudentDashboardTickets', compact('student', 'customer', 'subjects', 'allStudents', 'allCustomers', 'totalSubjects', 'allSubjects', 'customerState', 'customerCity', 'studentState', 'studentCity'));
    }

    public function studentDashboardInvoices($id)
    {
        $student = DB::table('students')->where('id', '=', $id)->first();
        $customer = DB::table('customers')->where('id', '=', $student->customer_id)->first();

        $customerState = DB::table('states')->where('id', '=', $customer->state)->first();
        $customerCity = DB::table('cities')->where('id', '=', $customer->city)->first();

        $studentState = DB::table('states')->where('id', '=', $student->state)->first();
        $studentCity = DB::table('cities')->where('id', '=', $student->city)->first();

        $subjects = DB::table('student_subjects')->where('student_id', '=', $student->id)->get();
        $allStudents = DB::table('students')->where('id', '=', $id)->first();
        $totalSubjects = DB::table('student_subjects')->where('student_id', '=', $id)->orderBy('id', 'desc')->count();
        $allCustomers = DB::table('customers')->get();
        $allSubjects = DB::table('products')->get();
        return view('student/viewStudentDashboardInvoices', compact('student', 'customer', 'subjects', 'allStudents', 'allCustomers', 'totalSubjects', 'allSubjects', 'customerState', 'customerCity', 'studentState', 'studentCity'));
    }


    public function customerDashboard($id)
    {

        $customer = DB::table('customers')->where('id', '=', $id)->first();
        $student = DB::table('students')->where('customer_id', '=', $customer->id)->get()->toArray();;

        // dd($student);

        $countStudents = DB::table('students')->where('customer_id', '=', $customer->id)->count();

        $customerState = DB::table('states')->where('id', '=', $customer->state)->first();
        $customerCity = DB::table('cities')->where('id', '=', $customer->city)->first();
        $studentState = [];
        $studentCity = [];
        $subjects = [];
        $allStudents = DB::table('students')->where('id', '=', $id)->first();
        $totalSubjects = DB::table('student_subjects')->where('student_id', '=', $id)->orderBy('id', 'desc')->count();
        $allCustomers = DB::table('customers')->get();
        $allSubjects = DB::table('products')->get();

        $customerTickets = DB::table('job_tickets')->where('student_id', '=', $customer->id)->get();


        $studentArray = [];
        foreach ($student as $std) {
            array_push($studentArray, $std->id);
        }

        // dd($studentArray);
        $customerTickets = DB::table('job_tickets')
            ->whereIn('student_id', $studentArray)
            ->get();


        $commitmentFee = DB::table('customer_commitment_fees')->where('customer_id', '=', $id)->first();

        $jobticketCheck = DB::table('job_tickets')
            ->join('students', 'job_tickets.student_id', '=', 'students.id')
            ->join('customers', 'customers.id', '=', 'students.customer_id')
            ->where('customers.id', $id)
            ->whereDate('job_tickets.created_at', '=', now()->subDays(90)->toDateString())
            ->get();

        return view('student/viewCustomerDashboard', compact('jobticketCheck', 'commitmentFee', 'student', 'customer', 'countStudents', 'subjects', 'allStudents', 'allCustomers', 'totalSubjects', 'allSubjects', 'customerState', 'customerCity', 'studentState', 'studentCity', 'customerTickets'));
    }


    public function customerInvoices($id)
    {
        $customer = DB::table('customers')->where('id', '=', $id)->first();
        $student = DB::table('students')->where('customer_id', '=', $customer->id)->get();
        $countStudents = DB::table('students')->where('customer_id', '=', $customer->id)->count();
        $customerState = DB::table('states')->where('id', '=', $customer->state)->first();
        $customerCity = DB::table('cities')->where('id', '=', $customer->city)->first();
        $studentState = [];
        //$studentState= DB::table('states')->where('id','=',$student->state)->first();
        //$studentCity = DB::table('cities')->where('id','=',$student->city)->first();
        $studentCity = [];
        $subjects = [];
        //$subjects = DB::table('student_subjects')->where('student_id','=',$student->id)->get();
        $allStudents = DB::table('students')->where('id', '=', $id)->first();
        $totalSubjects = DB::table('student_subjects')->where('student_id', '=', $id)->orderBy('id', 'desc')->count();
        $allCustomers = DB::table('customers')->get();
        $allSubjects = DB::table('products')->get();
        return view('student/viewCustomerInvoices', compact('student', 'customer', 'countStudents', 'subjects', 'allStudents', 'allCustomers', 'totalSubjects', 'allSubjects', 'customerState', 'customerCity', 'studentState', 'studentCity'));
    }


    public function customerCommentmentFees($id)
    {
        $customer = DB::table('customers')->where('id', '=', $id)->first();
        $student = DB::table('students')->where('customer_id', '=', $customer->id)->get();
        $countStudents = DB::table('students')->where('customer_id', '=', $customer->id)->count();
        $customerState = DB::table('states')->where('id', '=', $customer->state)->first();
        $customerCity = DB::table('cities')->where('id', '=', $customer->city)->first();
        $studentState = [];
        //$studentState= DB::table('states')->where('id','=',$student->state)->first();
        //$studentCity = DB::table('cities')->where('id','=',$student->city)->first();
        $studentCity = [];
        $subjects = [];
        //$subjects = DB::table('student_subjects')->where('student_id','=',$student->id)->get();
        $allStudents = DB::table('students')->where('id', '=', $id)->first();
        $totalSubjects = DB::table('student_subjects')->where('student_id', '=', $id)->orderBy('id', 'desc')->count();
        $allCustomers = DB::table('customers')->get();
        $allSubjects = DB::table('products')->get();
        return view('student/viewCustomerCommentmentFees', compact('student', 'customer', 'subjects', 'countStudents', 'allStudents', 'allCustomers', 'totalSubjects', 'allSubjects', 'customerState', 'customerCity', 'studentState', 'studentCity'));
    }


    public function customerTicket($id)
    {

        $customer = DB::table('customers')->where('id', '=', $id)->first();
        $student = DB::table('students')->where('customer_id', '=', $customer->id)->get();
        $countStudents = DB::table('students')->where('customer_id', '=', $customer->id)->count();
        $customerState = DB::table('states')->where('id', '=', $customer->state)->first();
        $customerCity = DB::table('cities')->where('id', '=', $customer->city)->first();
        $studentState = [];
        //$studentState= DB::table('states')->where('id','=',$student->state)->first();
        //$studentCity = DB::table('cities')->where('id','=',$student->city)->first();
        $studentCity = [];
        $subjects = [];
        //$subjects = DB::table('student_subjects')->where('student_id','=',$student->id)->get();
        $allStudents = DB::table('students')->where('id', '=', $id)->first();
        $totalSubjects = DB::table('student_subjects')->where('student_id', '=', $id)->orderBy('id', 'desc')->count();
        $allCustomers = DB::table('customers')->get();
        $allSubjects = DB::table('products')->get();
        return view('student/viewCustomerTickets', compact('student', 'customer', 'countStudents', 'subjects', 'allStudents', 'allCustomers', 'totalSubjects', 'allSubjects', 'customerState', 'customerCity', 'studentState', 'studentCity'));
    }


    public function viewStudent($id)
    {
        $student = DB::table('students')->where('id', '=', $id)->first();
        $customer = DB::table('customers')->where('id', '=', $student->customer_id)->first();
        $customerState = DB::table('states')->where('id', '=', $customer->state)->first();
        $customerCity = DB::table('cities')->where('id', '=', $customer->city)->first();

        $studentState = DB::table('states')->where('id', '=', $student->state)->first();
        $studentCity = DB::table('cities')->where('id', '=', $student->city)->first();


        $subjects = DB::table('student_subjects')->where('student_id', '=', $student->id)->get();
        $allStudents = DB::table('students')->where('id', '=', $id)->first();
        $totalSubjects = DB::table('student_subjects')->where('student_id', '=', $id)->orderBy('id', 'desc')->count();
        $allCustomers = DB::table('customers')->get();
        $allSubjects = DB::table('products')->get();
        
        
        $role = DB::table("roles")->
                where("name", "Student Customer Service")->
                first();
        $staffs = DB::table('staffs')->
                join("users", "staffs.user_id", "=", "users.id")->
                select("staffs.*", "users.role as role")->
                where("staffs.status", "Active")->where("users.role", $role->id)->get();
        
        return view('student/viewStudent', compact('student', 'customer', 'subjects', 'allStudents', 'allCustomers', 'totalSubjects', 'allSubjects', 'customerState', 
        'customerCity', 'studentState', 'studentCity','staffs'));
    }


    public function getStudent($id)
    {

        $customer = DB::table('customers')->where('id', '=', $id)->first();
        return Response::json(['customer' => $customer]);
    }


    //urooj 4/21

    public function Customers(Request $request)
    {
        DB::table('user_activities')->insert([
            'user' => \Illuminate\Support\Facades\Auth::user()->name,
            'module' => "Customers",
            'action' => "viewed Customers list",
        ]);

        $query = Customer::query();

        // Apply search filter if provided
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Apply status filter if provided and not 'All'
        if ($request->status && $request->status != 'All') {
            $customers = $query->get()->filter(function ($customer) use ($request) {
                $commitmentFee = DB::table('customer_commitment_fees')->where('customer_id', $customer->id)->first();
                $jobticketCheck = DB::table('job_tickets')
                    ->join('students', 'job_tickets.student_id', '=', 'students.id')
                    ->join('customers', 'customers.id', '=', 'students.customer_id')
                    ->where('customers.id', $customer->id)
                    ->whereDate('job_tickets.created_at', '>=', now()->subDays(90)->toDateString())
                    ->exists();

                switch ($request->status) {
                    case 'Unregistered':
                        return $commitmentFee == null;
                    case 'Inactive':
                        return $commitmentFee != null && !$jobticketCheck;
                    case 'Active':
                        return $commitmentFee != null && $jobticketCheck;
                    default:
                        return false;
                }
            });
        } else {
            // Get all customers if no status filter is applied
            $customers = $query->orderBy('id', 'DESC')->get();
        }
        
        
       

        return view('student/customer', compact('customers'));
    }


    public function addCustomer()
    {
        //
        $cities = DB::table('cities')->get();
        $states = DB::table('states')->get();
        $staffs = DB::table('staffs')->get();
        return view('student/addCustomer', Compact('cities', 'states', 'staffs'));
    }


    public function submitCustomer(Request $request)
    {
        $customer_values = array(

            'uid' => 'CUS-' . date('dis'),
            'full_name' => $request->customerFullName,
            'gender' => $request->customerGender,
            'age' => $request->customerAge,
            'email' => $request->customerEmail,
            'phone' => $request->customerPhone,
            'whatsapp' => $request->customerWhatsapp,
            
            'address' => $request->customerStreetAddress1,
            'address1' => $request->customerStreetAddress1,
            'address2' => $request->customerStreetAddress2,
            'city' => $request->customerCity,
            'state' => $request->customerState,
            
             'dob' => $request->dob,

            'postal_code' => $request->customerPostalcode,
            'latitude' => $request->customerLatitude,
            'longitude' => $request->customerLongitude,
            'customerable_type' => 0,
            'customerable_id' => 0,
            'remarks' => $request->remarks,
            'landmark' => $request->landmark,
            'staff_id' => $request->staff_id,
            'token' => (new Token())->Unique('customers', 'token', 60)
        );
        $studentLastID = DB::table('customers')->insertGetId($customer_values);

        return redirect('Customers')->with('success', 'Customer / Parent has been added successfully!');


        //return view('Customers');
    }


    public function viewCustomer($id)
    {
        //
        $customers = DB::table('customers')->where('id', '=', $id)->orderBy('id', 'desc')->first();
        $states = DB::table('states')->orderBy('id', 'desc')->get();
        $cities = DB::table('cities')->get();

        $customerState = DB::table('states')->where('id', '=', $customers->state)->first();
        $customerCity = DB::table('cities')->where('id', '=', $customers->city)->first();

        $commitmentFee = DB::table('customer_commitment_fees')->where('customer_id', '=', $customers->id)->first();

        $jobticketCheck = DB::table('job_tickets')
            ->join('students', 'job_tickets.student_id', '=', 'students.id')
            ->join('customers', 'customers.id', '=', 'students.customer_id')
            ->where('customers.id', $id)
            ->whereDate('job_tickets.created_at', '=', now()->subDays(90)->toDateString())
            ->get();

        $role = DB::table("roles")->
                where("name", "Student Customer Service")->
                first();
        $staffs = DB::table('staffs')->
                join("users", "staffs.user_id", "=", "users.id")->
                select("staffs.*", "users.role as role")->
                where("staffs.status", "Active")->where("users.role", $role->id)->get();
        

        return view('student/viewCustomer', Compact('customers', 'states', 'cities', 'customerCity', 'customerState', 'commitmentFee', 'jobticketCheck','staffs'));
    }

    public function editCustomer($id)
    {
        //
        $states = DB::table('states')->orderBy('id', 'desc')->get();
        $cities = DB::table('cities')->orderBy('id', 'desc')->get();
        $customers = DB::table('customers')->where('id', '=', $id)->orderBy('id', 'desc')->first();

        $customerState = DB::table('states')->where('id', '=', $customers->state)->first();
        $customerCity = DB::table('cities')->where('id', '=', $customers->city)->first();

        $staffs = DB::table('staffs')->get();

        $customerCommitmentFeeCheck = DB::table('customer_commitment_fees')->where('customer_id', '=', $id)->first();

        return view('student/editCustomer', Compact('customers', 'states', 'cities', 'customerCity', 'customerState', 'staffs', 'customerCommitmentFeeCheck'));
    }

    public function deleteCustomer($id)
    {
        
        $students = DB::table("students")->where("customer_id",$id)->get();
        foreach($students as $student)
        {
            DB::table("students")->where("customer_id",$student->id)->delete();
        }
        DB::table('customers')->where('id','=',$id)->delete();
        // DB::table('customers')->where('id', '=', $id)->update(['is_deleted' => 1]);
        return redirect()->back()->with('success', 'Customer Deleted Successfully');
    }

    public function deleteStudent($id)
    {
        $student_values = array('is_deleted' => 1);

        DB::table('students')->where('id', $id)->update($student_values);

        // DB::table('students')->where('id','=',$id)->delete();
        return redirect()->back()->with('success', 'Student Deleted Successfully');
    }


    public function studentPayments()
    {
        //
        return view('student/customer');
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

    public function ScheduleCalendar()
    {
        return view('student.ScheduleCalendar');
    }

    // public function studentInvoices(Request $request)
    // {
    //     $invoices = Invoice::query();
    //     $fromDate = Carbon::parse($request->fromDate)->format('Y-m-d');
    //     $toDate = Carbon::parse($request->toDate)->format('Y-m-d');

    //     if ($request->filled(['fromDate', 'toDate', 'status'])) {
    //         $invoices->whereBetween('invoiceDate', [$fromDate, $toDate])
    //             ->where('status', $request->status);
    //     } elseif ($request->filled('fromDate')) {
    //         $invoices->whereDate('invoiceDate', '>=', $fromDate)->get();
    //     } elseif ($request->filled('toDate')) {
    //         $invoices->whereDate('invoiceDate', '<=', $toDate);
    //     } elseif ($request->filled('status')) {
    //         $invoices->where('status', $request->status);
    //     } elseif ($request->filled('search')) {
    //         $invoices->where('reference', 'LIKE', '%' . $request->search . '%')->get();
    //     } elseif ($request->filled('studentID')) {
    //         $invoices->whereHas('student', function ($query) use ($request) {
    //             $query->where('uid', 'LIKE', '%' . $request->studentID . '%');
    //         })->with('student')->get();
    //     } elseif ($request->filled('fullName')) {
    //         $name = $request->fullName;
    //         $invoices->whereHas('student', function ($query) use ($name) {
    //             $query->where('full_name', 'LIKE', '%' . $name . '%');
    //         })->with('student')->get();
    //     }

    //     $invoices = $invoices->orderBy("id", "desc")->get();

    //     return view('student.studentInvoice', compact('invoices'));
    // }
    
    public function studentInvoices(Request $request)
    {
        $invoices = Invoice::query();
    
        // Date and status filters
        $fromDate = Carbon::parse($request->fromDate)->format('Y-m-d');
        $toDate = Carbon::parse($request->toDate)->format('Y-m-d');
    
        if ($request->filled(['fromDate', 'toDate', 'status'])) {
            $invoices->whereBetween('invoiceDate', [$fromDate, $toDate])
                ->where('status', $request->status);
        } elseif ($request->filled('fromDate')) {
            $invoices->whereDate('invoiceDate', '>=', $fromDate);
        } elseif ($request->filled('toDate')) {
            $invoices->whereDate('invoiceDate', '<=', $toDate);
        } elseif ($request->filled('status')) {
            $invoices->where('status', $request->status);
        } elseif ($request->filled('search')) {
            $invoices->where('reference', 'LIKE', '%' . $request->search . '%');
        } elseif ($request->filled('studentID')) {
            $invoices->whereHas('student', function ($query) use ($request) {
                $query->where('uid', 'LIKE', '%' . $request->studentID . '%');
            })->with('student');
        } elseif ($request->filled('fullName')) {
            $name = $request->fullName;
            $invoices->whereHas('student', function ($query) use ($name) {
                $query->where('full_name', 'LIKE', '%' . $name . '%');
            })->with('student');
        }
    
        // Add the CASE statement to the query for show_invoice and filter out non-true results
        $invoices = $invoices->leftJoin('class_schedules', 'invoices.ticketID', '=', 'class_schedules.ticketID')
            ->select(
                'invoices.*',
                DB::raw("
                    CASE 
                        WHEN SUM(CASE WHEN class_schedules.totalTime IS NOT NULL AND class_schedules.totalTime > 0 THEN 1 ELSE 0 END) > 0
                        AND SUM(CASE WHEN class_schedules.status = 'pending' OR class_schedules.status = 'attended' THEN 1 ELSE 0 END) > 0
                        THEN 'true'
                        ELSE 'false'
                    END as show_invoice")
            )
            ->groupBy('invoices.id') // Group by invoice id
            ->having('show_invoice', '=', 'true') // Filter where show_invoice is true
            ->orderBy('invoices.id', 'desc')
            ->get();
    
        return view('student.studentInvoice', compact('invoices'));
    }



    public function viewStudentInvoice($id)
    {
        $invoice_detail = DB::table('studentinvoices')->where('id', '=', $id)->orderBy('id', 'desc')->first();
        $invoice_items = DB::table('studentinvoice_items')->where('isPaid', '=', 'unpaid')->where('studentinvoiceID', '=', $id)->orderBy('id', 'desc')->get();
        $students = DB::table('students')->where('id', '=', $invoice_detail->studentID)->orderBy('id', 'DESC')->first();
        $tutors = DB::table('tutors')->where('id', '=', $invoice_detail->tutorID)->orderBy('id', 'DESC')->first();
        $subjects = DB::table('products')->where('id', '=', $invoice_detail->subjectID)->orderBy('id', 'DESC')->first();
        return view('student.viewStudentInvoice', Compact('invoice_items', 'students', 'invoice_detail', 'tutors', 'subjects'));
    }


    public function viewStudentInvoiceById($id)
    {
        // $invoice_detail = DB::table('invoices')->where('id','=',$id)->orderBy('id','desc')->first();
        // $invoice_items = DB::table('invoice_items')->where('invoiceID','=',$id)->orderBy('id','ASC')->get();

        // $students = DB::table('students')->where('id','=',$invoice_detail->studentID)->orderBy('id','DESC')->first();
        // $subjects = DB::table('products')->where('id','=',$invoice_detail->subjectID)->orderBy('id','DESC')->first();


        $invoice_detail = DB::table('invoices')->where('id', '=', $id)->orderBy('id', 'desc')->first();
        
        // dd($invoice_detail);

        $invoice_items = DB::table('invoice_items')->where('invoiceID', '=', $id)->orderBy('id', 'ASC')->get();
        $rowInvoiceItems = $invoice_items[0];


        $students = DB::table('students')->where('id', '=', $invoice_detail->studentID)->orderBy('id', 'DESC')->first();
        // $subjects = DB::table('products')->where('id','=',$invoice_detail->subjectID)->orderBy('id','DESC')->first();

        $subjects = DB::table('products')->join("categories", "products.category", "=", "categories.id")
            ->select("products.*", "categories.price as category_price")
            ->where('products.id', '=', $invoice_detail->subjectID)->first();
        
        // $customer = Student::find($students->studentID)->first();
        $customer = DB::table('customers')->where('id', '=', $students->customer_id)->first();

        $jobTicketDeails = JobTicket::find($invoice_detail->ticketID)->first();
        
        //dd($invoice_detail);
        return view('student.viewStudentInvoiceById', Compact('invoice_items', 'students', 'invoice_detail', 'subjects', 'rowInvoiceItems','customer','jobTicketDeails'));
    }


    public function sendEmailInvoice($id)
    {
        $invoice_detail = DB::table('invoices')->where('id', '=', $id)->orderBy('id', 'desc')->first();
        $invoice_items = DB::table('invoice_items')->where('invoiceID', '=', $id)->orderBy('id', 'desc')->get();
        $jobTicketDeails = DB::table('job_tickets')->where('id', '=', $invoice_detail->ticketID)->first();
        $students = DB::table('students')->where('id', '=', $invoice_detail->studentID)->orderBy('id', 'DESC')->first();
        $customer = DB::table('customers')->where('id', '=', $students->customer_id)->first();
        $subjects = DB::table('products')->join("categories", "products.category", "=", "categories.id")->select("products.*", "categories.price as category_price")->where('products.id', '=', $invoice_detail->subjectID)->orderBy('id', 'DESC')->first();
        $data = [
            'title' => 'Laravel DomPDF Example',
            'content' => 'This is an example PDF generated using Laravel DomPDF.',
        ];

        $file = public_path() . "/studentInvoiceSlipPDF" . "/" . "student-invoice-slip-" . $invoice_detail->id . ".pdf";

        if (file_exists($file)) {

            $to_email = "binasift@gmail.com";
            $subject = "Student Payment Slip Invoice";

            Mail::to('binasift@gmail.com')->send(new SendEmailInvoice($invoice_detail, $file));
            Mail::to($customer->email)->send(new SendEmailInvoice($invoice_detail, $file));

            return redirect()->back()->with("success", "Email sent successfully");
        } else {
            $pdf = PDF::loadView('pdf.invoice', [
                'data' => $data,
                'invoice_items' => $invoice_items,
                'invoice_detail' => $invoice_detail,
                'students' => $students,
                'subjects' => $subjects,
                'customer' => $customer,
                'jobTicketDeails' => $jobTicketDeails,
            ]);
            $pdf->save(public_path('studentInvoiceSlipPDF') . "/" . "staff-Payment-Slip-" . $invoice_detail->id . ".pdf");

            $pdfPath = public_path() . "/studentInvoiceSlipPDF" . "/" . "staff-Payment-Slip-" . $invoice_detail->id . ".pdf";

            Mail::to('binasift@gmail.com')->send(new SendEmailInvoice($invoice_detail, $pdfPath));
            Mail::to($customer->email)->send(new SendEmailInvoice($invoice_detail, $pdfPath));

            $pdfContent = file_get_contents($pdfPath);
            $base64Content = base64_encode($pdfContent);

            return redirect()->back()->with("success", "Email sent successfully");
        }
    }


    public function viewStudentPayment($id)
    {

        $invoicePayments = DB::table('invoicePayments')->where('id', '=', $id)->orderBy('id', 'desc')->first();
        $invoice_detail = DB::table('invoices')->where('id', '=', $invoicePayments->invoiceID)->orderBy('id', 'desc')->first();
        $invoice_items = DB::table('invoice_items')->where('invoiceID', '=', $invoice_detail->id)->orderBy('id', 'desc')->get();


        $students = DB::table('students')->where('id', '=', $invoice_items[0]->studentID)->orderBy('id', 'DESC')->first();
        //$tutors = DB::table('tutors')->where('id','=',$invoice_items[0]->tutorID)->orderBy('id','DESC')->first();
        // $subjects = DB::table('products')->where('id','=',$invoice_items[0]->subjectID)->orderBy('id','DESC')->first();

        $rowInvoiceItems = $invoice_items[0];


        $subjects = DB::table('products')->join("categories", "products.category", "=", "categories.id")
            ->select("products.*", "categories.price as category_price")
            ->where('products.id', '=', $invoice_detail->subjectID)->first();

        // dd($subjects);
        return view('student.viewStudentPayment', Compact('rowInvoiceItems', 'invoice_items', 'students', 'invoice_detail', 'subjects'));
    }

    public function editStudentInvoice($id)
    {

        $invoice_detail = DB::table('invoices')->where('id', '=', $id)->orderBy('id', 'desc')->first();
        $invoice_items = DB::table('invoice_items')->where('invoiceID', '=', $id)->orderBy('id', 'desc')->get();
        $students = DB::table('students')->where('id', '=', $invoice_items[0]->studentID)->orderBy('id', 'DESC')->get();
        //$tutors = DB::table('tutors')->where('id','=',$invoice_items[0]->tutorID)->orderBy('id','DESC')->first();
        // $subjects = DB::table('products')->where('id', '=', $invoice_items[0]->subjectID)->orderBy('id', 'DESC')->get();

        $subjects = DB::table('products')->join("categories", "categories.id", "=", "products.category")
            ->select("products.*", "categories.category_name as category")
            ->orderBy('id', 'desc')
            ->where('products.id', '=', $invoice_items[0]->subjectID)
            ->get();
        // dd($subjects);

        $unitPrice = $invoice_detail->invoiceTotal / count($invoice_items);
        $deductions = DB::table('invoice_deductions')->where('invoiceID', '=', $id)->orderBy('id', 'DESC')->get();

        // dd($deductions);

        return view('student.editStudentInvoice', Compact('invoice_items', 'unitPrice', 'students', 'invoice_detail', 'subjects', 'deductions'));
    }


    public function deleteStudentInvoice($id)
    {
        //   $studentInvoice_values = array('is_deleted' => 1);

        //   $var1 = DB::table('invoices')->where('id', $id)->update($studentInvoice_values);

        DB::table('invoices')->where('id', '=', $id)->delete();
        DB::table('invoice_items')->where('invoiceID', '=', $id)->delete();
        return redirect()->back()->with('success', 'Student Invoice Deleted Successfully');
    }


    public function StudentPaymentLists(Request $request)
    {


        // if ($request->studentPayment) {
        $name = $request->search;
        $status = $request->status;
        $invoices = DB::table('invoicePayments');
        $fromDate = Carbon::parse($request->fromDate)->format('Y-m-d');
        $toDate = Carbon::parse($request->toDate)->format('Y-m-d');

        //   if ($request->search) {
        //     $invoices = DB::table('invoicePayments')->where('paymentID', '=', $name)->orderBy('id', 'DESC')->get();
        //   } else {
        //     $invoices = DB::table('invoicePayments')->orderBy('id', 'desc')->get();
        //   }
        // } else {
        //   $invoices = DB::table('invoicePayments')->orderBy('id', 'desc')->get();
        // }
        if ($request->filled(['fromDate', 'toDate', 'search'])) {
            $invoices->whereBetween('paymentDate', [$fromDate, $toDate])
                ->where('paymentID', $request->search);
        } elseif ($request->filled('fromDate')) {
            $invoices->whereDate('paymentDate', '>=', $fromDate)->get();
        } elseif ($request->filled('toDate')) {
            $invoices->whereDate('paymentDate', '<=', $toDate);
        } elseif ($request->filled('search')) {
            $invoices->where('paymentID', $request->search);
        }
        $invoices = $invoices->orderBy('id', 'desc')->get();

        return view('student.studentPayments', Compact('invoices'));
    }

    public function addStudentInvoice()
    {
        $customers = DB::table('customers')->orderBy('id', 'desc')->get();
        $students = DB::table('students')->orderBy('id', 'desc')->get();
        $subjects = DB::table('products')->join("categories", "categories.id", "=", "products.category")
            ->select("products.*", "categories.category_name as category")
            ->orderBy('id', 'desc')->get();
        return view('student.addStudentInvoice', Compact('customers', 'students', 'subjects'));
    }

    public function getSubjectById($id)
    {

        $subjects = DB::table('products')->where('id', '=', $id)->first();
        return Response::json(['subjects' => $subjects]);
    }

    public function sendWhatsapp()
    {
        // API Endpoint URL
        $api_url = 'https://api.watext.com/hook/message';
        // API Key
        $api_key = 'e6f2cb62a2b54cfbb6a1b25fbfee6131';
        // Prepare data for the POST request
        $data = array(
            'apikey' => $api_key,
            'phone' => '+923121984475',
            'message' => 'Testing Message from Localhost Sifututor'
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
            }
            // else {
            //     // Handle non-JSON response
            //     echo 'Non-JSON Response: ' . $response;
            //     // Additional logic for handling non-JSON response can be added here
            // }
        }
    }

    public function pdfFile($id)
    {
        // Sample data to pass to the view

        $invoice_detail = DB::table('invoices')->where('id', '=', $id)->orderBy('id', 'desc')->first();
        $invoice_items = DB::table('invoice_items')->where('invoiceID', '=', $id)->orderBy('id', 'desc')->get();
        $jobTicketDeails = DB::table('job_tickets')->where('id', '=', $invoice_detail->ticketID)->first();
        $students = DB::table('students')->where('id', '=', $invoice_detail->studentID)->orderBy('id', 'DESC')->first();
        $customer = DB::table('customers')->where('id', '=', $students->customer_id)->first();
        $subjects = DB::table('products')->join("categories", "products.category", "=", "categories.id")->select("products.*", "categories.price as category_price")->where('products.id', '=', $invoice_detail->subjectID)->orderBy('id', 'DESC')->first();

        $data = [
            'title' => 'Laravel DomPDF Example',
            'content' => 'This is an example PDF generated using Laravel DomPDF.',
        ];

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

        // Download the PDF file
        return $pdf->download("INV-" . $invoice_detail->id . ".pdf");
    }
}
