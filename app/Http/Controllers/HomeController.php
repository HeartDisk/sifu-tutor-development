<?php

namespace App\Http\Controllers;

use App\Models\ExpenseVoucher;
use Illuminate\Http\Request;
use DB;
use Carbon;
use Auth;
use App\Mail\TutorRegistrationMail;
use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\ApiLog;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Libraries\PushNotificationLibrary;
use App\Libraries\WhatsappApi;
use Illuminate\Support\Facades\File;
use App\Events\TestEvent;
use App\Events\Parent\ParentDashbaord;
use Pusher\Pusher;
use App\Helpers\GoogleHelper;
use App\Jobs\SendPushNotificationJob;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function getAccessToken()
    {
        // Call your helper to get the Google access token
        echo GoogleHelper::getGoogleAccessToken();
    }
     
    public function logsIndex()
    {
      
       $apiLogs = ApiLog::orderBy("id", "desc")->get(); // Adjust the number 10 to the number of items per page you want
       return view('api_logs', compact('apiLogs'));

    }

    public function eventTest()
    {
        //parent tokent
        $data = [
            "ResponseCode" => "100",
            "message" => "Test Event Run Successfully"
        ];
        event(new ParentDashbaord($data));

        // event(new ParentDashbaord('123AsimSaleem!'));
        dd("Test Event Run Successfully");
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function upcomingClasses()
    {
        $now = \Carbon\Carbon::now();

        // Calculate the time 5 minutes from now
        $nextFiveMinutes = $now->copy()->addMinutes(5);

        // Format current time for comparison
        $currentTime = $now->format('H:i:s');

        // dd($currentTime."===========".$nextFiveMinutes);

        // Get the classes starting in the next 5 minutes where status is not 'attended'
        $classes = DB::table('class_schedules')
            ->where('startTime', '>=', $currentTime) // Compare with current time
            ->where('startTime', '<=', $nextFiveMinutes->format('H:i:s')) // Compare with 5 minutes from now
            ->where('status', '!=', 'attended')
            ->get();

        // dd($classes);

        foreach ($classes as $class) {

            // dd($class);

            $classStartTime = \Carbon\Carbon::parse($class->startTime);
            $notificationTime = $classStartTime->subMinutes(5);

            // dd($notificationTime);

            // if ($notificationTime->between($now, $nextFiveMinutes)) {


                 $tutorDeviceTokens = DB::table('tutor_device_tokens')
                ->where('tutor_id', $class->tutorID)
                ->pluck('device_token');

                // dd($tutorDeviceTokens);

                foreach ($tutorDeviceTokens as $token) {
                    $tutorDevices = DB::table('tutor_device_tokens')->distinct()->get(['device_token', 'tutor_id']);
                    //dd($tutorDevices);
                    foreach ($tutorDevices as $rowDeviceToken) {
                        $push_notification_api = new PushNotificationLibrary();
                        $title = 'Upcoming Class';
                        $message = 'Your class will start in 5 minutes. Do not forget to clock-in';
                        $deviceToken = $rowDeviceToken->device_token;
                        $push_notification_api->sendPushNotification($deviceToken, $title, $message);
                    }
                }
            // }
        }

    }
    
        
    public function Emailtest(Request $request)
    {
        // Send email notification
        $to = 'aasim.creative@gmail.com';
        Mail::to($to)->send(new TutorRegistrationMail());
    }
    
    public function Notificationtest(Request $request)
    {
        // Send push notifications to parent devices
        $parentDevices = DB::table('tutor_device_tokens')->distinct()->get(['device_token']);
        foreach ($parentDevices as $rowDeviceToken) {
            $push_notification_api = new PushNotificationLibrary();
            $deviceToken = $rowDeviceToken->device_token;
            $title = 'OB-Ticket Create Successfully';
            $message = 'New Job Ticket Created';
        
            $notificationdata = [
                'Sender' => 'FAQs'
            ];
        
            // Dispatch the notification job with proper data structure
            SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
            
            // $response = $push_notification_api->sendPushNotification($deviceToken, $title, $message, $notificationdata);
            // echo $response;
        }
    }
    
    
    public function Whatsapptest()
    {
        $phone = '923118354191';
        $message = 'testing';
        $whatsapp_api = new WhatsappApi();
        // $sms_api = new SmsNiagaApi();

        $whatsapp_api->send_message($phone, $message);
        // $sms_api->sendSms($phone, $message);

        // DB::table('text_messages')->insert([
        //     'recipient' => $phone,
        //     'message' => $message,
        //     'status' => 'sent',
        // ]);
    }


     public function getStudents(Request $request){

     }

//     public function emailTest()
//     {
//          $to = "binasift@gmail.com";
//          $two ="aasim.creative@gmail.com";
//          $subject = "Class Attendance";

//          $message = "<!DOCTYPE html>
// <html lang='en'>
//   <head>
//     <meta charset='UTF-8' />
//     <meta http-equiv='X-UA-Compatible' content='IE=edge' />
//     <meta name='viewport' content='width=device-width, initial-scale=1.0' />
//     <title>Attendance Confirmation</title>
//   </head>
//   <body
//     style='
//       font-family: Arial, sans-serif;
//       margin: 0;
//       padding: 20px;
//       background-color: #f4f4f4;
//     '
//   >
//     <div
//       style='
//         margin: auto;
//         background-color: #ffffff;
//         padding: 20px;
//         border-radius: 10px;
//         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
//       '
//     >
//       <h2 style='color: #333333'>Attendance Confirmation</h2>

//       <p>Dear Parents/Guardians,</p>

//       <p>
//         <strong>Subject Name:</strong> Subject</p>
//         <p></p><strong>Student Name:</strong> Full name
//       </p>

//       <p>
//         Below are the details of the class attended:<br />

//         <table style='border: 1px solid #ccc; border-collapse: collapse; width: 100%; table-layout: fixed;'>
//           <thead>
//             <tr>
//               <th style='background-color: #233cb3; color: #fff; padding: .625em; text-align: center; font-size: .85em; letter-spacing: .1em; text-transform: uppercase;' scope='col'>Date</th>
//               <th style='background-color: #233cb3; color: #fff; padding: .625em; text-align: center; font-size: .85em; letter-spacing: .1em; text-transform: uppercase;' scope='col'>Start Time</th>
//               <th style='background-color: #233cb3; color: #fff; padding: .625em; text-align: center; font-size: .85em; letter-spacing: .1em; text-transform: uppercase;' scope='col'>End Time</th>
//               <th style='background-color: #233cb3; color: #fff; padding: .625em; text-align: center; font-size: .85em; letter-spacing: .1em; text-transform: uppercase;' scope='col'>Total Time</th>
//             </tr>
//           </thead>
//           <tbody>
//             <tr>
//               <td style='background-color: #f8f8f8; border: 1px solid #ddd; padding: .60em; text-align: center;' data-label='Date'>12/09/2024</td>
//               <td style='background-color: #f8f8f8; border: 1px solid #ddd; padding: .60em; text-align: center;' data-label='Start Time'>12:03:03</td>
//               <td style='background-color: #f8f8f8; border: 1px solid #ddd; padding: .60em; text-align: center;' data-label='End Time'>12:03:03</td>
//               <td style='background-color: #f8f8f8; border: 1px solid #ddd; padding: .60em; text-align: center;' data-label='Total Time'>12:03:03</td>
//             </tr>
//           </tbody>
//         </table>
//       </p>

//       <p>
//         <strong>Dear parents/guardians, we appreciate your verification.</strong>
//       </p>

//       <p>
//         Click Agree if you agree, otherwise click Disagree<br>
//         <a
//           href='https://sifututor.odits.co/new/agreeAttendance/1'
//           style='
//               display: inline-block;
//               margin-top: 10px;
//               padding: 10px 0px;
//               background-color: #4caf50;
//               color: #ffffff;
//               text-decoration: none;
//               border-radius: 0px;
//               width: 100%;
//               text-align: center;
//               font-weight: bold;
//           '
//           >Agree</a
//         >
//         <a
//           href='https://sifututor.odits.co/new/disputeAttendance/1'
//           style='
//             display: inline-block;
//             margin-top: 10px;
//             padding: 10px 0px;
//             background-color: #e74c3c;
//             color: #ffffff;
//             text-decoration: none;
//             border-radius: 0px;
//             width: 100%;
//             text-align: center;
//             font-weight: bold;
//           '
//           >Disagree</a
//         >
//       </p>

//       <p>Thank you</p>
//     </div>
//   </body>
// </html>
// ";

// // Always set content-type when sending HTML email
//          $headers = "MIME-Version: 1.0" . "\r\n";
//          $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// // More headers
//          $headers .= 'From: <tutor@sifu.qurangeek.com>' . "\r\n";

//          mail($to,$subject,$message,$headers);
//          mail($two,$subject,$message,$headers);

//         // Example using PHP's mail function
// //        $email = "aasim.creative@gmail.com";
// //        $subject = 'Testing Code';
// //        $message = 'Your Testing Email from Sifututor';
// //        $headers = 'From: sifututorbrainia@sifututor.brainiaccreation.com' . "\r\n" .
// //                   'Reply-To: sifututorbrainia@sifututor.brainiaccreation.com' . "\r\n" .
// //                   'X-Mailer: PHP/' . phpversion();
// //
// //        mail($email, $subject, $message, $headers);

//          return "Email sent successfully!";

//     }

    // public function emailTest()
    // {
    //     // Example using PHP's mail function
    //     $email = "aasim.creative@gmail.com";
    //     $subject = 'Testing Code';
    //     $message = 'Your Testing Email from Sifututor';
    //     $headers = 'From: tutor@sifu.qurangeek.com' . "\r\n" .
    //               'Reply-To: tutor@sifu.qurangeek.com' . "\r\n" .
    //               'X-Mailer: PHP/' . phpversion();

    //     mail($email, $subject, $message, $headers);
    //     return "Email sent successfully!";
    // }


    public function automated()
    {
        for ($x = 0; $x <= 10; $x--) {
            echo "The number is: $x <br>";
        }
    }

     public function automation()
    {
        $thirtyDaysAgo = \Carbon\Carbon::now()->subDays(30)->toDateString();

        $invoices = DB::table("invoices")
        ->join("job_tickets","invoices.ticketID","=","job_tickets.id")
            ->whereDate('invoiceDate', '<=', $thirtyDaysAgo)
            ->where("job_tickets.ticket_tutor_status","Active")
            ->select("invoices.*")
            ->get();

            // dd($invoices);

        // dd($invoices);
        foreach ($invoices as $invoice) {

            $class_schedule = DB::table("class_schedules")->where("ticketID", $invoice->ticketID)->first();
            $job_ticket = DB::table("job_tickets")->where("id", $invoice->ticketID)->first();


            $totalAdditionalClassesAmount=0;
            if ($class_schedule != null) {
                $numberOfClassesClassSchedule = $class_schedule->remaining_classes + $job_ticket->classFrequency;
                $numberOfClassesJobTicket =  DB::table("job_tickets")->where("id", $invoice->ticketID)->first();
                $numberOfClassesJobTicket = $numberOfClassesJobTicket->remaining_classes + $numberOfClassesJobTicket->classFrequency;
                DB::table("class_schedules")->where("ticketID", $invoice->ticketID)->update(["remaining_classes" => $numberOfClassesClassSchedule]);
                DB::table("job_tickets")->where("id", $invoice->ticketID)->update(["remaining_classes" => $numberOfClassesJobTicket]);
                $subjects =DB::table("student_subjects")->where("ticket_id", $invoice->ticketID)->update(["remaining_classes" => $numberOfClassesClassSchedule]);
                $check =DB::table("student_subjects")->where("ticket_id", $invoice->ticketID)->first();
                // dd($check);
            }


            $invoiceData = (array)$invoice;


            if($job_ticket->additional_classes!=0)
            {
                $totalAdditionalClassesAmount=($invoiceData["invoiceTotal"]/$invoiceData["classFrequency"])*$job_ticket->additional_classes;
            }


            unset($invoiceData['id']);
            $invoiceData["invoiceDate"] = date("Y-m-d");

            $invoiceData["invoiceTotal"] = $invoiceData["invoiceTotal"]+$totalAdditionalClassesAmount;
            $invoiceData["invoice_status"] = "recurring";
            $invoiceData["status"] = "pending";
            if($job_ticket->remaining_classes==0)
            {
                   $invoiceData["sentEmail"] = "true";
            }else{
                 $invoiceData["sentEmail"] = "false";
            }

            $newInvoiceId = DB::table('invoices')->insertGetId($invoiceData);


            $invoiceItems = DB::table('invoice_items')->where('invoiceID', $invoice->id)->get();

            foreach ($invoiceItems as $invoiceItem) {

                $itemData = (array)$invoiceItem;
                unset($itemData['id']);

                $itemData['invoiceID'] = $newInvoiceId;

                $itemData['invoiceDate'] = date("Y-m-d");

                DB::table('invoice_items')->insert($itemData);
            }

            $invoice_detail = DB::table("invoices")->where("id", $newInvoiceId)->first();
            $invoice_items = DB::table("invoice_items")->where("invoiceID", $newInvoiceId)->get();
            $jobTicketDeails = DB::table("job_tickets")->where("id", $invoice->ticketID)->first();
            $students = DB::table('students')->where('id', '=', $invoice->studentID)->orderBy('id', 'DESC')->first();
            $customer = DB::table('customers')->where('id', '=', $students->customer_id)->first();
            $subjects = DB::table('products')->join("categories", "products.category", "=", "categories.id")
                ->select("products.*", "categories.price as category_price")
                ->where('products.id', '=', $invoice_detail->subjectID)->first();


            $data = [
                'title' => 'Invoice',
                'content' => 'System Generated Invoice',
            ];


            $pdf = PDF::loadView('pdf.recurringInvoice', [
                'data' => $data,
                'invoice_items' => $invoice_items,
                'invoice_detail' => $invoice_detail,
                'students' => $students,
                'subjects' => $subjects,
                'customer' => $customer,
                'jobTicketDeails' => $jobTicketDeails,
            ]);


//            return view('pdf.recurringInvoice', [
//                'data' => $data,
//                'invoice_items' => $invoice_items,
//                'invoice_detail' => $invoice_detail,
//                'students' => $students,
//                'subjects' => $subjects,
//                'customer' => $customer,
//                'jobTicketDeails' => $jobTicketDeails,
//            ]);
            DB::table("job_tickets")->where("id", $invoice->ticketID)->update(['additional_classes' => 0]);
            $pdf->save(public_path('invoicePDF/') . "/" . "Invoice-" . $invoice_detail->id . ".pdf");


            //Invoice Email setup
            $subjectTwo = 'Invoice';
            $headersTwo = "MIME-Version: 1.0" . "\r\n";
            $headersTwo .= "Content-type: multipart/mixed; boundary=\"boundary\"\r\n";
            // More headers
            $headersTwo .= 'From: <tutor@sifu.qurangeek.com>' . "\r\n";


            $pdfPath = url("/public/invoicePDF/Invoice-") . $invoice_detail->id . ".pdf";
            // $fileExsist=false;


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
            if($job_ticket->remaining_classes==0)
            {
                mail($to, $subjectTwo, $emailBody, $headersTwo);
                mail($to_email, $subjectTwo, $emailBody, $headersTwo);
            }

        }

        dd("Invoice Automation Job run Sucessfully");
    }



    public function getStudentByID($id){
         $student = DB::table('students')->where('id','=',$id)->first();

         return response()->json(['studentDetail'=>$student]);
    }
    
    public function getStudentsByParentID($parent_id)
    {
        try {
            // Directly query the database for students with the given parent_id
            $students = DB::table('students')
                          ->where('customer_id', $parent_id)
                          ->select('id', 'full_name', 'uid') // Only select necessary columns
                          ->get();
    
            // Return the students in JSON format
            return response()->json($students, 200);
        } catch (\Exception $e) {
            // Handle exceptions and return error response if something goes wrong
            return response()->json(['error' => 'Failed to retrieve students'], 500);
        }
    }


     public function getSearchStudents(Request $request){
         $search = $request->search;
         return response()->json(['search'=>$search]);

     }

     public function updateToken(Request $request){
            try{
                $request->user()->update(['fcm_token'=>$request->token]);
                return response()->json([
                    'success'=>true
                ]);
            }catch(\Exception $e){
                report($e);
                return response()->json([
                    'success'=>false
                ],500);
            }
        }


     public function index()
    {

        $id = Auth::user()->id;
        $ifUserLoggedIN = DB::table('users')->where('id', '=', $id)->where('last_login', '<>', date("Y-m-d H"))->first();
        if (!$ifUserLoggedIN) {
            $affected = DB::table('users')
                ->where('id', $id)
                ->update(['last_login' => date('Y-m-d H:i:s')]);
        }

        //$id = Auth::user()->id;
        $checked = DB::table('loggedInUsers')->where('user_id', '=', $id)->where('last_login', '<>', date("Y-m-d H"))->where('pageUrl', '=', url()->full())->first();
        if (!$checked) {
            DB::table('loggedInUsers')->insert([
                'user_id' => $id,
                'last_login' => date('Y-m-d H:i:s'),
                'pageUrl' => url()->full(),
                'detail' => 'Dashboard at home page'
            ]);
        }


        //$revenue = DB::table('class_attendeds')->where('status','=','attended')->sum('totalPrice');

        $revenue = DB::table('class_attendeds')->where('status', '=', 'attended')->whereMonth('created_at', '=', Carbon\Carbon::now()->month)->sum('totalPrice');

        $expenses = DB::table('expenditures')->whereMonth('created_at', '=', Carbon\Carbon::now()->month)->sum('total');

        $userName = DB::table('users')->where('id', '=', Auth::id())->first();

        $invoices = Invoice::all();
        $totalInvoices = $invoices->count();
        // Check if there are invoices to avoid division by zero
        if ($totalInvoices > 0) {
            $average_per_invoice = $invoices->sum('invoiceTotal') / $totalInvoices;
        } else {
            $average_per_invoice = 0; // or any default value you prefer when there are no invoices
        }

        $totalTime = DB::table('class_attendeds')->whereMonth('created_at', '=', Carbon\Carbon::now()->month)->where('is_paid', '=', 'unpaid')->sum('totalPrice');

        $income = DB::table('invoices')->whereMonth('created_at', '=', Carbon\Carbon::now()->month)->sum('invoiceTotal');

        $incomeCollected = DB::table('invoices')->whereMonth('created_at', '=', Carbon\Carbon::now()->month)->where('status', '=', 'paid')->sum('invoiceTotal');

        $totalTutorPaidTime = DB::table('class_attendeds')->whereMonth('created_at', '=', Carbon\Carbon::now()->month)->where('is_tutor_paid', '=', 'paid')->sum('totalTime');

//        $expenditures = DB::table('expenditures')->whereMonth('created_at', '=', Carbon\Carbon::now()->month)->sum('total');

        $expenditures = $expense_vouchers = ExpenseVoucher::join("accounts", "expense_vouchers.expense_id", "=", "accounts.id")->
                                            select("expense_vouchers.*", "accounts.name as expense_name")->
                                            whereMonth('expense_vouchers.created_at', '=', Carbon\Carbon::now()->month)->
                                            orderBy("id", "desc")->sum("amount");
        //unpaid invoices work
        // Fetch all unpaid invoices
        $unpaidInvoices = Invoice::where('status', 'unpaid')->get();


        // Initialize an array to store unpaid amounts per month
        $unpaidAmounts = array_fill_keys(
            ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            0
        );

        // Sum the amounts for each month
        foreach ($unpaidInvoices as $invoice) {
            $invoiceMonth = Carbon\Carbon::parse($invoice->date)->format('F');
            $unpaidAmounts[$invoiceMonth] += $invoice->invoiceTotal;
        }


        // Prepare the data array
        $dataArray = array_values($unpaidAmounts);
        //unpaid invoices work end


        return view('home', Compact('userName', 'revenue', 'expenses',
            'totalTime', 'incomeCollected', 'totalTutorPaidTime', 'expenditures', 'dataArray',
            'income',
            'average_per_invoice'));
    }


     public function sendTutorInvoice()
    {

        $tutors=DB::table("tutors")->get();

        // foreach ($tutors as $tutor)
        // {
            $tutor = DB::table("tutors")->where("id", 60)->first();
            // dd($tutor);
            $currentDate = Carbon\Carbon::now()->subMonth();

            $currentYear = $currentDate->year; // Get the current year
            $monthName = $currentDate->format('F'); // Get the full month name

            $firstDayLastMonth = $currentDate->startOfMonth()->startOfDay(); // Ensures the start of the day
            $lastDayLastMonth = $firstDayLastMonth->copy()->endOfMonth()->endOfDay(); // Ensures the end of the day

            $firstDayLastMonthFormatted = $firstDayLastMonth->toDateString();
            $lastDayLastMonthFormatted = $lastDayLastMonth->toDateTimeString(); // Use toDateTimeString() to include time

            // dd($firstDayLastMonth."-".$lastDayLastMonth);

            $tutorsClassData = DB::table("class_attendeds")
                ->join("products", "class_attendeds.subjectID", "=", "products.id")
                ->join("categories", "products.category", "=", "categories.id")
                ->join("job_tickets", "class_attendeds.ticketID", "=", "job_tickets.id")
                ->select("class_attendeds.*", "products.name as product_name","job_tickets.quantity as quantity","categories.mode as mode","categories.category_name as category_name")
                ->whereBetween('class_attendeds.created_at', [$firstDayLastMonthFormatted, $lastDayLastMonthFormatted])
                ->where(["class_attendeds.tutorID"=>60,"is_paid"=>"unpaid"])
                ->get();






               //BONUS WORK
                   $tutorAttendedClassData = DB::table("class_attendeds")
                                        ->select(DB::raw('SUM(class_attendeds.totalTime) as total_time_sum'))
                                        ->whereBetween('class_attendeds.created_at', [$firstDayLastMonthFormatted, $lastDayLastMonthFormatted])
                                        ->where(["class_attendeds.tutorID"=>60,"is_paid"=>"unpaid"])
                                        ->first();
            //         dd($tutorAttendedClassData);

            //   dd($firstDayLastMonthFormatted."--".$lastDayLastMonthFormatted);
                    $totalTimeSumInLastMonth = $tutorAttendedClassData->total_time_sum;

                    $oneTimeBonus = 0;
                    $monthlyBonus = 0;

                    switch ($totalTimeSumInLastMonth) {
                        case ($totalTimeSumInLastMonth >= 20 && $totalTimeSumInLastMonth <= 50):
                            $monthlyBonus = 100;
                        case ($totalTimeSumInLastMonth >= 51 && $totalTimeSumInLastMonth <= 80):
                            $monthlyBonus = 200;
                        case ($totalTimeSumInLastMonth >= 81 && $totalTimeSumInLastMonth <= 100):
                            $monthlyBonus = 300;
                        case ($totalTimeSumInLastMonth >= 100):
                            $monthlyBonus = 500;
                    }


                    // dd($monthlyBonus);

                    $joiningDate = $tutor->start_date;
                    $withInTwoMonths = Carbon\Carbon::createFromDate($joiningDate)->subMonth(2)->toDateString();
                    $withInFourMonths = Carbon\Carbon::createFromDate($joiningDate)->subMonth(4)->toDateString();



                    $tutorAttendedClassesTwoMonth = DB::table("class_attendeds")
                                        ->select(DB::raw('SUM(class_attendeds.totalTime) as total_time_sum'))
                                        //  ->whereBetween('class_attendeds.created_at', [$joiningDate, $withInTwoMonths])
                                        ->where(["class_attendeds.tutorID"=>4,"is_paid"=>"unpaid"])
                                        ->first();

                    // dd($tutorAttendedClassesTwoMonth);
                     $tutorAttendedClassesFourMonth = DB::table("class_attendeds")
                                        ->select(DB::raw('SUM(class_attendeds.totalTime) as total_time_sum'))
                                    //   ->whereBetween('class_attendeds.created_at', [$joiningDate, $withInFourMonths])
                                        ->where(["class_attendeds.tutorID"=>4,"is_paid"=>"unpaid"])
                                        ->first();


                    // $tutorAttendedClassesTwoMonth = DB::table("class_attendeds")
                    //     ->select("class_attendeds.*", DB::raw('SUM(class_attendeds.totalTime) as total_time_sum'))
                    //     ->whereBetween('class_attendeds.created_at', [$joiningDate, $withInTwoMonths])
                    //     ->get();

                    // $tutorAttendedClassesFourMonth = DB::table("class_attendeds")
                    //     ->select("class_attendeds.*", DB::raw('SUM(class_attendeds.totalTime) as total_time_sum'))
                    //     ->whereBetween('class_attendeds.created_at', [$joiningDate, $withInFourMonths])
                    //     ->get();



                    if ($tutorAttendedClassesTwoMonth->total_time_sum >= 14) {
                        $oneTimeBonus = 100;
                    } elseif ($tutorAttendedClassesTwoMonth->total_time_sum >= 45) {
                        $oneTimeBonus = 200;
                    }



               //END BONUS WORK




            if($tutorsClassData!=null)
            {
                // var_dump($tutorsClassData) ;
                $data = [
                    'title' => "Tutor Commission Report for ".$monthName."(".$currentYear.")",
                    'content' => 'System Generated Invoice',
                ];

                $totalAmount= $tutorsClassData->pluck('commission')->sum();



                if(isset($oneTimeBonus))
                {
                  $totalAmount+=  $oneTimeBonus;
                }

                if(isset($monthlyBonus))
                {
                  $totalAmount+=  $monthlyBonus;
                }



                // dd($tutorsClassData);
                // Generate PDF from a view
                $pdf = PDF::loadView('tutor.invoice', [
                    'data' => $data,
                    'tutor' => $tutor,
                    'totalAmount'=>$totalAmount,
                    'oneTimeBonus' => $oneTimeBonus,
                    'monthlyBonus' => $monthlyBonus,
                    'tutorsClassData' => $tutorsClassData
                ]);

                // dd(time());
                $pdfName=$tutor->id."-".date('s', time());

                $pdf->save(public_path('tutorCommission/')."/"."Invoice-".$pdfName.".pdf");

                // dd("Done");
                //Invoice Email setup
                $subjectTwo = 'Invoice';
                $headersTwo = "MIME-Version: 1.0" . "\r\n";
                $headersTwo .= "Content-type: multipart/mixed; boundary=\"boundary\"\r\n";
                // More headers
                $headersTwo .= 'From: <tutor@sifu.qurangeek.com>' . "\r\n";


                $pdfPath = url("/public/tutorCommission/Invoice-") .$pdfName . ".pdf";


                $pdfContent = file_get_contents($pdfPath);
                $base64Content = base64_encode($pdfContent);


                // if (true) {
                $emailBody = "Tutor Commission Report for ".$monthName."(".$currentYear.")" ;


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


                $to=$tutor->email;
                $to_email = "binasift@gmail.com";
                mail($to, $subjectTwo, $emailBody, $headersTwo);
                mail($to_email, $subjectTwo, $emailBody, $headersTwo);
            }

        // }

        dd("Tutor Commission Automation Job Run Sucesfully");
        //dd($tutorsClassData);

        //dd($tutorsClassData);

        // $tutorAttendedClassData = DB::table("class_attendeds")
        //     ->select("class_attendeds.*",DB::raw('SUM(class_attendeds.totalTime) as total_time_sum'))
        //     ->whereBetween('class_attendeds.created_at', [$firstDayLastMonthFormatted, $lastDayLastMonthFormatted])
        //     ->get();

        // $totalTimeSumInLastMonth = $tutorAttendedClassData->total_time_sum;

        // $oneTimeBonus = 0;
        // $monthlyBonus = 0;

        // switch ($totalTimeSumInLastMonth) {
        //     case ($totalTimeSumInLastMonth >= 20 && $totalTimeSumInLastMonth <= 50):
        //         $monthlyBonus = 100;
        //     case ($totalTimeSumInLastMonth >= 51 && $totalTimeSumInLastMonth <= 80):
        //         $monthlyBonus = 200;
        //     case ($totalTimeSumInLastMonth >= 81 && $totalTimeSumInLastMonth <= 100):
        //         $monthlyBonus = 300;
        //     case ($totalTimeSumInLastMonth >= 100):
        //         $monthlyBonus = 500;
        // }

        // $joiningDate = $tutor->start_date;
        // $withInTwoMonths = Carbon\Carbon::createFromDate($joiningDate)->subMonth(2)->toDateString();
        // $withInFourMonths = Carbon\Carbon::createFromDate($joiningDate)->subMonth(4)->toDateString();

        // $tutorAttendedClassesTwoMonth = DB::table("class_attendeds")
        //     ->select("class_attendeds.*", DB::raw('SUM(class_attendeds.totalTime) as total_time_sum'))
        //     ->whereBetween('class_attendeds.created_at', [$joiningDate, $withInTwoMonths])
        //     ->get();

        // $tutorAttendedClassesFourMonth = DB::table("class_attendeds")
        //     ->select("class_attendeds.*", DB::raw('SUM(class_attendeds.totalTime) as total_time_sum'))
        //     ->whereBetween('class_attendeds.created_at', [$joiningDate, $withInFourMonths])
        //     ->get();


        // if ($tutorAttendedClassesTwoMonth->total_time_sum >= 14) {
        //     $oneTimeBonus = 100;
        // } elseif ($tutorAttendedClassesTwoMonth->total_time_sum >= 45) {
        //     $oneTimeBonus = 200;
        // }

        //End Tutor Bonus Work


        // return view("tutor.invoice", ['tutor' => $tutor, 'subjects' => $subjects, 'tutorData' => $tutorsClassData,
        //     'oneTimeBonus' => $oneTimeBonus, 'monthlyBonus' => $monthlyBonus]);


        return view("tutor.invoice", ['tutor' => $tutor, 'subjects' => $subjects, 'tutorData' => $tutorsClassData]);
    }

    public function mobileNotificationForm(){

        return view('mobileNotificationForm');

    }


     public function sendMobileNotification(Request $request){


        $tutorDevices = DB::table('tutor_device_tokens')->distinct()->get(['device_token','tutor_id']);

        foreach($tutorDevices as $rowDeviceToken){
            if($rowDeviceToken->device_token){
                    $serverKey = 'AAAAxgC0G8E:APA91bGGid53CVR8sd27Y-yvKQbjOOyvcIt6jHNdsH2Bt8JEJMKnNU5SUXZ5SzTH4oGMnKmTJ7Nw-YWcbmMdqsTO1DhO0Fe2g-EeurVOIZF-N-r2e4cgNg-Pp8ckhcbyBUIkbUzU9TBS';
                    // $deviceToken = 'fHGXb9NpS1WrICADz7RmHQ:APA91bGsdpFXd5LPJ2WzDOOIi5f9gIgdi9-Ak8J9vs6t97w4ek9ITdg5ScDdMsSjgGRbDVKl7Uv8bp3ZRMUoVK4tlizH2Q6ZlIAy586u1g8PtGeyTrkUYR99OxhmBWe0c6ztv-exzmvg';
                    $deviceToken = $rowDeviceToken->device_token;
                    $title = $request->title;
                    $message = $request->message;
                    $data = array(
                        'custom_key' => 'custom_value', // Additional data to be sent with the notification
                        // 'image_url' => 'https://example.com/image.jpg', // Image URL
                        // 'link' => 'https://example.com/page', // Link URL
                    );
                    // Prepare the notification payload
                    $payload = array(
                        'to' => $deviceToken,
                        'notification' => array(
                            'title' => $title,
                            'body' => $message,
                            'sound' => 'default',
                            'click_action' => 'FCM_PLUGIN_ACTIVITY',
                        ),
                        'data' => $data
                    );

                    // Convert the payload to JSON
                    $jsonPayload = json_encode($payload);

                     // Prepare the cURL request
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Authorization: key=' . $serverKey,
                    ));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);

                    // Send the cURL request
                    $result = curl_exec($ch);

                    if ($result === false) {
                        $error = curl_error($ch);
                        curl_close($ch);
                        $error_message = 'Failed to send notification: ' . $error;
                        return $error_message;
                    }

                    curl_close($ch);

                    $response = json_decode($result);

                    if (isset($response->success) && $response->success == 1) {
                        return true;
                    } else {
                        $error_messageTwo = 'Failed to send notification: ' . $result;
                        return $error_messageTwo;
                    }



                    if ($result) {
                        echo 'Notification sent successfully';
                    } else {
                        echo 'Failed to send notification: ';
                    }

            }
        }



     }
}
