<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use DB;
use Spatie\Permission\Models\Role;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;


class StaffController extends Controller
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
    public function index()
    {
        //
    }


 public function downloadStaffPaymentSlip($id)
    {


        $payment_slip = DB::table('staff_payments')->join("staffs", "staff_payments.staff_id", "=", "staffs.id")
            ->select("staff_payments.*", "staffs.full_name as name")
            ->where('staff_payments.id', '=', $id)->orderBy('id', 'desc')->first();
        $file = public_path() . "/staffPaymentSlipPDF" . "/" . "staff-Payment-Slip-" . $payment_slip->id . ".pdf";

        if (file_exists($file)) {
            $headers = array('Content-Type: application/pdf',);
            return Response::download($file, "staff-Payment-Slip-" . $payment_slip->id, $headers);
        } else {
            $pdf = PDF::loadView('staff.staffPaymentSlipPDF', [
                'payment_slip' => $payment_slip,

            ]);
            $pdf->save(public_path('staffPaymentSlipPDF') . "/" . "staff-Payment-Slip-" . $payment_slip->id . ".pdf");
            $file = public_path() . "/staffPaymentSlipPDF" . "/" . "staff-Payment-Slip-" . $payment_slip->id . ".pdf";
            $headers = array('Content-Type: application/pdf',);
            return Response::download($file, "staff-Payment-Slip-" . $payment_slip->id, $headers);
        }
    }

    public function sendStaffPaymentSlip($id)
    {
        $payment_slip = DB::table('staff_payments')->join("staffs", "staff_payments.staff_id", "=", "staffs.id")
            ->select("staff_payments.*", "staffs.full_name as name","staffs.email as staff_email")
            ->where('staff_payments.id', '=', $id)->orderBy('id', 'desc')->first();
        
        $to_email = "binasift@gmail.com";
        $subject="Staff Payment Slip Invoice";

        $header = "MIME-Version: 1.0" . "\r\n";
        $header .= "Content-type: multipart/mixed; boundary=\"boundary\"\r\n";
        // More headers
        $header .= 'From: <tutor@sifututor.com>' . "\r\n";

        $pdfPath = public_path() . "/staffPaymentSlipPDF" . "/" . "staff-Payment-Slip-" . $payment_slip->id . ".pdf";

        $pdfContent = file_get_contents($pdfPath);
        $base64Content = base64_encode($pdfContent);

        // Attachment
        $emailBody = "";
        $emailBody .= '</tbody>
                        </table>
                        <table class="table table-responsive no-border">
                        <tbody>
                        <tr>
                            <td>
                              Please find attached Invoice
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
        mail($payment_slip->staff_email, $subject, $emailBody, $header);
        
       return redirect()->back()->with("success","Email sent successfully");

    }

    

     public function checkStaffDuplicateEmail(Request $request)
    {

        $tables = ['customers', 'staffs', 'tutors','users'];
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

    public function checkStaffDuplicatePhone(Request $request)
    {
        $tables = ['customers', 'staffs', 'tutors','users'];

        $found = false; // Flag to indicate if a record is found

        if(isset($request->customerWhatsapp)) {
            foreach ($tables as $table) {
                // Search for a record based on WhatsApp number
                $existingRecord = DB::table($table)
                    ->where('whatsapp', $request->customerWhatsapp)
                    ->first();
                if ($existingRecord) {
                    $found = true;
                    break; // Stop the loop if a record is found
                }
            }
        } else {
            foreach ($tables as $table) {
                if($table=="tutors")
                {
                    $existingRecord = DB::table($table)
                        ->where('phoneNumber', $request->customerMobile)
                        ->first();
                }else{
                    $existingRecord = DB::table($table)
                        ->where('phone', $request->customerMobile)
                        ->first();
                }

                if ($existingRecord) {
                    $found = true;
                    break;
                }
            }
        }

        if ($found) {
            return response()->json(["recordFound" => true], 200);
        } else {
            return response()->json(["recordFound" => false], 200);
        }

    }


    public function deleteStaff($id)
    {
        //
        $staff_values = array('is_deleted' => 1);

        $var1 = DB::table('staffs')->where('id', $id)->update($staff_values);

        return redirect()->back();


    }

    public function getStaffCommissionById($id)
    {
        $staff = DB::table('staffs')->where("id", $id)->first();
        $data = [];
        $data["salary"] = $staff->basic_salary;

        $monthlyData = DB::table('job_tickets')
            ->leftJoin('staffs', 'job_tickets.admin_charge', '=', 'staffs.id')
            ->leftjoin('invoices', 'job_tickets.id', '=', 'invoices.ticketID')
            ->select(
                'staffs.full_name as tutor_name',
                DB::raw('MONTHNAME(job_tickets.created_at) as month_name'),
                DB::raw('COUNT(job_tickets.id) as total_job_tickets'),
                DB::raw('SUM(job_tickets.totalPrice) as total_amount')
            )
            ->groupBy('tutor_name', 'month_name')
            ->orderBy('tutor_name')
            ->orderBy('month_name')
            ->where('staffs.id', $id)
            ->whereMonth('job_tickets.created_at', '=', now()->subMonth()->month)  // Filtering records for the last month
            ->where('invoices.status', 'paid')  // Ensuring only paid invoices are considered
            ->first();

        if ($monthlyData != null) {
            if ($monthlyData->total_amount >= 11000) {
                $data["commission"] = round($monthlyData->total_amount * 0.02);
            } else {
                $data["commission"] = round($monthlyData->total_amount * 0.01);
            }




            $registeredStudents=DB::table("students")->where("staff_id",$id)->count();
            $registeredParents=DB::table("customers")->where("staff_id",$id)->count();

            $data["invoice_count"] = $registeredStudents+$registeredParents;

            // Initialize bonus to 0 before the loop
            $data["bonus"] = 0;

            $studentPICBonuses = DB::table("studentbonuses")->get();


            foreach ($studentPICBonuses as $studentPICBonus) {
                if ($data["invoice_count"] > $studentPICBonus->rangeFrom && $data["invoice_count"] < $studentPICBonus->rangeTo)
                {
                    $data["bonus"] = $studentPICBonus->bonusAmount;
                    break;
                }

            }
        } else
        {
            $data["bonus"] = 0;
            $data["commission"] = 0;
            $data["salary"] = $staff->basic_salary;
        }


        return response()->json(['message' => $data]);

    }

     public function staffPaymentsViewCommissions()
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
            ->groupBy('staff_name', 'month_name','staff_id')
            ->orderBy('month_name')
            ->where("job_tickets.admin_charge","!=",NULL)
            ->get();




        foreach ($monthlyData as $data) {

            if ($data->total_amount >= 11000) {
                $data->bonus = round($data->total_amount * 0.02);
            } else {
                $data->bonus = round($data->total_amount * 0.01);
            }
        }
        
        // dd($monthlyData);

        return view("staff.staffCommission", ["data" => $monthlyData]);

    }

    public function staffPaymentsViewCommissionsByMonth(Request $request)
    {
        $selectedMonth = $request->month; // Replace this with the desired month

        if ($selectedMonth != null) {
            $monthlyData = DB::table('job_tickets')
                ->leftJoin('staffs', 'job_tickets.admin_charge', '=', 'staffs.id')
                ->select(
                    'staffs.full_name as staff_name',
                    DB::raw('MONTHNAME(job_tickets.created_at) as month_name'),
                    DB::raw('COUNT(job_tickets.id) as total_job_tickets'),
                    DB::raw('SUM(job_tickets.totalPrice) as total_amount')
                )
                ->whereRaw("MONTHNAME(job_tickets.created_at) = ?", [$selectedMonth])
                ->groupBy('staff_name', 'month_name')
//                ->orderBy('tutor_name')
                ->orderBy('month_name')
                ->get();

            foreach ($monthlyData as $data) {

                if ($data->total_amount >= 11000) {
                    $data->bonus = round($data->total_amount * 0.02);
                } else {
                    $data->bonus = round($data->total_amount * 0.01);
                }
            }
        } else {
            $monthlyData = DB::table('job_tickets')
                ->leftJoin('staffs', 'job_tickets.admin_charge', '=', 'staffs.id')
                ->select(
                    'staffs.full_name as staff_name',
                    DB::raw('MONTHNAME(job_tickets.created_at) as month_name'),
                    DB::raw('COUNT(job_tickets.id) as total_job_tickets'),
                    DB::raw('SUM(job_tickets.totalPrice) as total_amount')
                )
                ->groupBy('staff_name', 'month_name')
//                ->orderBy('tutor_name')
                ->orderBy('month_name')
                ->get();
            foreach ($monthlyData as $data) {

                if ($data->total_amount >= 11000) {
                    $data->bonus = round($data->total_amount * 0.02);
                } else {
                    $data->bonus = round($data->total_amount * 0.01);
                }
            }
        }


        return view("staff.staffCommission", ["data" => $monthlyData, 'selectedMonth' => $selectedMonth]);

    }

    public function ViewCommissionsBreakDown($staff_id)
    {
        $data = DB::table("job_tickets")->
        join("invoices", "invoices.id", "=", "job_tickets.ticket_id")->
        join("students", "students.id", "=", "job_tickets.student_id")->
        join("customers", "customers.id", "=", "students.customer_id")->

        join("products", "job_tickets.subjects", "=", "products.id")->
        join("categories", "products.category", "=", "categories.id")->
        leftjoin("invoicePayments", "invoicePayments.invoiceID", "=", "invoices.id")->

        select("job_tickets.*", "customers.full_name as customer_name", "customers.full_name as customer_name",
        "invoices.id as invoice_id","categories.mode as mode","invoices.invoiceTotal as invoiceTotal","invoicePayments.paymentDate as paymentDate")->
        where("job_tickets.admin_charge", $staff_id)->get();
        $staff=DB::table("staffs")->where("id",$staff_id)->first();

        return view("staff.staffCommissionBreakDown",["data"=>$data,'staff'=>$staff]);
    }




    public function StaffList(){
        $staffs = DB::table('staffs')->join("users","staffs.user_id","users.id")
        ->join("roles","users.role","roles.id")
        ->select("staffs.*","roles.name as role")
        ->orderBy("staffs.id","desc")
        ->get();

        return view('staff/staffList', Compact('staffs'));
    }

    public function addStaff(){
          $roles = DB::table('roles')->get();
        return view('staff/addStaff', Compact('roles'));
    }

    public function viewStaff($id){
        $staff = DB::table('staffs')->where('id','=',$id)->first();
        if($staff->city!=null)
        {
            $city=DB::table("cities")->where("id",$staff->city)->pluck("name")->first(); 
        }else{
            $city="";
        }
        
    
        $user_id=DB::table("staffs")->where("id",$id)->first();
        $staff_role=DB::table("users")->where("id",$user_id->user_id)->first();
       
        $role_name=DB::table("roles")->where("id",$staff_role->role)->first();
        
        return view('staff/viewStaff',Compact('staff','city','role_name'));
    }

     public function editStaff($id){
        $staff = DB::table('staffs')->where('id','=',$id)->first();
        $states = DB::table('states')->get();

        $roles = DB::table('roles')->get();
        $cities = DB::table("cities")->get();

        return view('staff/editStaff',Compact('staff','states','roles','cities'));
    }
    
      public function staffPayment($id){
        $staff = DB::table('staffs')->where('id','=',$id)->first();
        $staffPayments=DB::table('staff_payments')->where('staff_id','=',$id)->get();
        return view('staff/staffPayment',Compact('staff','staffPayments'));
    }


  public function ViewPaymentSlip($id)
    {

        // dd($id);
        $payment_slip = DB::table('staff_payments')->join("staffs","staff_payments.staff_id","=","staffs.id")
            ->select("staff_payments.*","staffs.full_name as name")
            ->where('staff_payments.id', '=', $id)->orderBy('id', 'desc')->first();

        return view('staff.viewPayment', Compact('payment_slip'));
    }




    public function staffPayments(){

        $staffPayments = DB::table('staff_payments')->join("staffs","staff_payments.staff_id","=","staffs.id")
        ->select("staff_payments.*","staffs.full_name as name")
        ->get();


        // dd($staffPayments);
        return view('staff/staffPayment',Compact('staffPayments'));

    }


       public function StaffMakePayment()
    {
        $staffs=DB::table('staffs')->get();
        return view("staff.submitStaffPayment",["staffs"=>$staffs]);
    }

    public function submitPaymentStaff(Request $request){


        //dd($request->all());

        $basicSalary=$request->basicSalry;
        $overTime=$request->overTimeAmount*$request->overTimeHour;
        $deduction=$request->deduction;
        $bonusAmount=$request->bonusAmount;
        $claim=$request->claim;
        $food=$request->food;


        $calc=$basicSalary+$bonusAmount+$overTime;
        $afterDeductionGovtFund=$calc-($calc*0.11);
        $afterDeductionGovtFund=$afterDeductionGovtFund+$claim+$food-$deduction;

        $nett_total=$afterDeductionGovtFund;
        $total=$basicSalary+$bonusAmount+$overTime+$claim+$food-$deduction;


        // dd($request->all());
         $values = array(

             'staff_id' => $request->staffID,
             'payment_date' => $request->paymentDate,
             'salary_month' => $request->salaryMonth,
             'salary_year' => $request->salaryYear,
             'basic_salary_description' => $request->basicSalaryDescription,
             'basic_salary' => $request->basicSalry,
             'overtime_amount_perhour' => $request->overTimeAmount,
             'overtime_hour' => $request->overTimeHour,
             'claim' => $request->claim,
             'food' => $request->food,
             'no_unpaid_leave' => $request->numberOfUnpaidLeave,
             'deduction' => $request->deduction,
             'has_services' => $request->has_services,
             'paying_account' => $request->PayingAccount,
             'comission' => $request->comission,

             'remark' => $request->remarks,
             'bonus_amount' => $request->bonusAmount,
             'total'=>$total,
             'nett_amount'=>$afterDeductionGovtFund


                    );
        $tutorLastID = DB::table('staff_payments')->insertGetId($values);

        return redirect("/StaffPayments");

    }
     public function submitStaff(Request $request)
    {
        //
        //echo date('siHdm');
        //dd($request);
        //die();






        $values = array(
            'name' => $request->full_name,
            'phone' => $request->phoneNumber,
            'email' => $request->email,
            'remarks' => $request->remarks,
            'status' => $request->status,
            'role' => $request->role,
            'password' => bcrypt($request->password),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'));

        $user = User::create($values);

         $values = array(
            'uid' => $request->staffID,
            'full_name' => $request->full_name,
            'user_id' => $user->id,
            'gender' => $request->gender,
            'designation' => $request->desgnation,
            'start_date' => $request->registration_date,
            'age' => $request->age,
            'basic_salary' => $request->basic_salary,
            'email' => $request->email,
            'phone' => $request->phoneNumber,
            'attended_training_date' => $request->attended_training_date,
            'dob' => $request->dob,
            'nric' => $request->nric,
            'address' => $request->street_address1,
            'street_address2' => $request->street_address2,
            'city' => $request->city,
            'state' => $request->state,
            'bank_name' => $request->bankName,
            'bank_account_number' => $request->bankAccountNumber,
            'marital_status' => $request->maritalStatus,
            'no_of_children' => $request->number_of_children,
            'postal_code' => $request->postal_code,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'remark' => $request->remarks,
            'epf_no' => $request->epf_no,
            'tax_no' => $request->tax_no,
            'type' => $request->type,
            'status' => $request->status,
        );
        $tutorLastID = DB::table('staffs')->insertGetId($values);


        $role = Role::find($request->role);
        $user->assignRole($role);


        if ($request->email != null) {
            $to = $request->email;
            $subject = "New Staff Registration:";

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
                                                        esteemed staff member, your dedication to fostering learning aligns
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

            mail($to, $subject, $message, $headers);

        }

        return redirect("/StaffList")->with('success', 'Staff has been added successfully!');
         
        return redirect()->back()->with('success', 'Staff has been added successfully!');

        return view('staff/addStaff');
    }

      public function submitEditStaff(Request $request)
    {
        //
        //echo date('siHdm');
        //die();
        $staffValues = array(
                    'uid' => $request->staffID,
                    'full_name' => $request->full_name,
                    //'fee_payment_date' => $request->FeePaymentDate,
                    'gender' => $request->gender,
                    'designation' => $request->desgnation,
                    'start_date' => $request->registration_date,
                    'age' => $request->age,
                    'basic_salary' => $request->basic_salary,
                    'email' => $request->email,
                    'type' => $request->type,
                    'phone' => $request->phoneNumber,
                    'attended_training_date' => $request->attended_training_date,
                    'dob' => $request->dob,
                    'nric' => $request->nric,
                    'address' => $request->street_address1,
                    'street_address2' => $request->street_address2,
                    'city' => $request->city,
                     'state' => $request->state,
                    'bank_name' => $request->bank_name,
                    'bank_account_number' => $request->bank_account_number,
                    'marital_status' => $request->maritalStatus,
                    'no_of_children' => $request->number_of_children,
                    'postal_code' => $request->postal_code,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'remark' => $request->remark,
                    'epf_no' => $request->epf_no,
                    'tax_no' => $request->tax_no,
                    'status' => $request->status,
                    );
        $var1 = DB::table('staffs')->where('id', $request->id)->update($staffValues);
        // dd($request->password);
        if($request->password==NULL)
        {
            // dd("1");
             $var2 = DB::table('users')->where('id', $request->user_id)->update(["role"=>$request->role]);


        }else{
            // dd("2");
             $var2 = DB::table('users')->where('id', $request->user_id)->update(["role"=>$request->role,"password"=>bcrypt($request->password)]);

        }


        return redirect()->back()->with('success','Staff has been edited successfully!');

        return view('staff/addStaff');
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
