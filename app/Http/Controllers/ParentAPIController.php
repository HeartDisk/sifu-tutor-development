<?php

namespace App\Http\Controllers;

use App\Events\Parent\SingleParentDashboard;
use App\Events\Tutor\TutorDashboard;
use App\Events\Parent\ParentDashbaord;
use App\Events\Parent\JobTicket;
use App\Events\TutorOffers;
use App\Models\Blog;
use App\Models\Chat;
use App\Models\ClassSchedules;
use App\Models\Invoice;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Libraries\WhatsappApi;
use App\Libraries\SmsNiagaApi;
use App\Libraries\PushNotificationLibrary;
use Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Pusher\Pusher;
use App\Events\MobileHomePageUpdated;
use App\Events\Tutor\TicketCreated;
use App\Events\Parent\ParentProfile;
use App\Events\Parent\StudentList;
use App\Events\Parent\StudentReport;
use App\Jobs\SendWhatsAppMessageJob;
use App\Jobs\SendSmsMessageJob;
use App\Jobs\SendPushNotificationJob;
use App\Mail\ParentRegistrationMail;
use App\Mail\PaymentReceiptMail;
use Carbon\Carbon;
use Dirape\Token\Token;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberToCarrierMapper;
use libphonenumber\geocoding\PhoneNumberOfflineGeocoder;
use libphonenumber\CountryCodeToRegionCodeMap;
use App\Services\FiuuService;
use DB;
use DateTime;
use DateInterval;
use Auth;



class ParentAPIController extends Controller
{

    public function __construct(Request $request)
    {

        Log::channel('api')->info('API Request:', [
            'ip' => $request->ip(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'headers' => $request->header(),
            'body' => $request->all()
        ]);
    }
    
    public function getHomeData(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }
    
        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }
    
        $token = $request->token;
    
        // Fetch Parent Details
        $customer = DB::table('customers')->where('token', $token)->first();
    
        if ($customer === null) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }
    
        $customerDetail = DB::table('customers')
            ->leftJoin('states', 'customers.state', '=', 'states.id')
            ->leftJoin('cities', 'customers.city', '=', 'cities.id')
            ->select('customers.*', 'states.name as state_name', 'cities.name as city_name')
            ->where('customers.token', '=', $token)
            ->first();
    
        $customerDetail->image = $customerDetail->image
            ? url("{$customerDetail->image}")
            : url("/public/person_place_holder.png");
    
        try {
            $phoneUtil = PhoneNumberUtil::getInstance();
            $numberProto = $phoneUtil->parse($customerDetail->phone, null);
    
            $countryCodeNumeric = $numberProto->getCountryCode();
            $nationalNumber = $numberProto->getNationalNumber();
            $countryCodeAlpha = $phoneUtil->getRegionCodeForNumber($numberProto);
    
            $customerDetail->country_code = "+{$countryCodeNumeric}";
            $customerDetail->country_code_alpha = $countryCodeAlpha;
            $customerDetail->phone_number = $nationalNumber;
    
        } catch (NumberParseException $e) {
            $customerDetail->country_code = '';
            $customerDetail->country_code_alpha = '';
            $customerDetail->phone_number = $customerDetail->phone;
        }
    
        $subjectDetails = DB::table('class_attendeds as ca')
            ->join('students as s', 'ca.studentID', '=', 's.id')
            ->join('products as p', 'ca.subjectID', '=', 'p.id')
            ->where('s.customer_id', $customer->id)
            ->where('ca.status', 'attended')
            ->select('p.name as subject_name', DB::raw('ROUND(SUM(ca.totalTime), 2) as total_time'))
            ->groupBy('p.name')
            ->get();
    
        $sumTotalTimeDecimal = $subjectDetails->sum('total_time');
        $hours = floor($sumTotalTimeDecimal);
        $minutes = round(($sumTotalTimeDecimal - $hours) * 60);
        $attendedHours = sprintf("%d hr and %.0f min", $hours, $minutes);
    
        $customerDetail->sumTotalTime = $attendedHours;
        $customerDetail->subjectDetails = $subjectDetails->map(function ($detail) {
            $detail->total_time = round($detail->total_time, 2);
            return $detail;
        });
    
        $approvedTicket = DB::table('job_tickets as t')
            ->join('students as s', 't.student_id', '=', 's.id')
            ->where('s.customer_id', $customer->id)
            ->where('t.status', 'approved')
            ->select('t.id')
            ->first();
    
        if ($approvedTicket && $customer->status === 'unverified') {
            $customerDetail->showCommitmentFeeButton = true;
            $customerDetail->approvedTicketId = $approvedTicket->id;
        } else {
            $customerDetail->showCommitmentFeeButton = false;
            $customerDetail->approvedTicketId = null;
        }
    
        // Fetch Blogs
        $blogs = Blog::where('is_deleted', 0)
            ->orderBy('id', 'desc')
            ->get();
    
        foreach ($blogs as $blog) {
            $blog->date_time = Carbon::parse($blog->created_at)->format('d M Y | h:i A');
            $blog->image = $blog->headerimage ? url("/public/MobileBlogImages/{$blog->headerimage}") : '';
        }
    
        // Fetch Due Invoices
        $studentIds = Student::where('customer_id', $customer->id)->pluck('id');
        $dueInvoices = Invoice::join('products', 'invoices.subjectID', '=', 'products.id')
            ->whereIn('invoices.studentID', $studentIds)
            ->orderBy('invoices.id', 'desc') // Qualify `id` column with the table name
            ->get();
    
        // Fetch Today's Classes
        $todayClasses = DB::table('class_schedules')
            ->join('products', 'class_schedules.subjectID', '=', 'products.id')
            ->join('students', 'class_schedules.studentID', '=', 'students.id')
            ->where('class_schedules.status', '=', 'scheduled')
            ->whereDate('class_schedules.date', '=', date("Y-m-d"))
            ->whereTime('class_schedules.endTime', '>', date("H:i:s"))
            ->get();
    
        // Fetch Tutor Attendance
        $tutorAttendance = DB::table('class_attendeds')
            ->where('status', 'attended')
            ->get();
    
        // Fetch News
        $news = DB::table('news')
            ->where('is_deleted', 0)
            ->where('type', 'Parent')
            ->orderBy('id', 'desc')
            ->get();
    
        // Fetch Notifications
        $notifications = DB::table('notifications')
            ->where(function ($query) use ($token) {
                $query->where('token', $token)->orWhereNull('token');
            })
            ->where('type', 'Parent')
            ->orderBy('id', 'desc')
            ->get();
    
        // Fetch Categories by Mode
        $mode = 'online';
        $categoriesByOnlineMode = DB::table('categories')
            ->where('mode', $mode)
            ->get();
    
        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Parent Home data retrieved successfully.',
            'data' => [
                'parentDetails' => $customerDetail,
                'blogs' => $blogs,
                'dueInvoices' => $dueInvoices,
                'todayClasses' => $todayClasses,
                'tutorAttendance' => $tutorAttendance,
                'news' => $news,
                'notifications' => $notifications,
                'categoriesByOnlineMode' => $categoriesByOnlineMode,
            ],
        ]);
    }



    public function paymentVerification(Request $request)
    {

        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        $token = $request->token;

        $customer = DB::table('customers')->where('token', $token)->first();

        if ($customer === null) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        $requiredFields = [
            'order_id' => 'Order ID',
            'type' => 'Type',
            'transaction_id' => 'Transaction ID'
        ];

        // Check for missing fields
        foreach ($requiredFields as $field => $displayName) {
            if (is_null($request->$field)) {
                return response()->json([
                    'ResponseCode' => '102',
                    'error' => "It looks like you missed the ($displayName) field. Please fill it in."
                ]);
            }

            if (empty($request->$field)) {
                return response()->json([
                    'ResponseCode' => '102',
                    'error' => "It looks like you missed the ($displayName) field. Please fill it in."
                ]);
            }
        }

        if($request->type=="invoice")
        {
            $data = DB::table("invoices")->where("id",$request->order_id)->first();
            $student_data = DB::table("students")->where("id",$data->studentID)->first();
            if($data!=null)
            {
                $updateInvoice = array(
                    'paymentDate' => date("Y-m-d"),
                    'transaction_id' => $request->transaction_id,
                    'status' => 'paid'
                );
                DB::table('invoices')->where('id', $request->order_id)->update($updateInvoice);
            }
            
            $ticket = DB::table("job_tickets")->where("id",$data->ticketID)->first();
            
            if($ticket!=null)
            {
                DB::table("job_tickets")->where("id",$ticket->id)->update(["remaining_classes"=>$ticket->remaining_classes+$ticket->classFrequency]);
                $class_schedule = DB::table("class_schedules")->where("ticketID",$ticket->id)->first();
            }
            
            if($class_schedule!=null)
            {
                DB::table("class_schedules")->where("id",$class_schedule->id)->update(["remaining_classes"=>$class_schedule->remaining_classes+$ticket->classFrequency]);
            }
            
            $invoice_detail = DB::table('invoices')->where('id', '=', $request->id)->orderBy('id', 'desc')->first();

            $invoicePaymentValues = array(
                'invoiceID' => $request->order_id,
                'invoiceReference' => $data->reference,
                'paymentID' => 'PC-' . date('dis'),
                'amount' => $request->amount,
                'receivingAccount' => $request->amount,
                'paymentAttachment' => NULL
            );
            $invoicePaymentLastID = DB::table('invoicePayments')->insertGetId($invoicePaymentValues);
            
                    // Prepare email data
            $emailData = [
                'parentName' => $customer->full_name,
                'invoiceNumber' => $data->reference ?? 'N/A',
                'studentName' => $student_data->full_name ?? 'N/A',
                'paymentDate' => now()->format('d F Y | h:i A'),
                'amountPaid' => $request->amount ?? '0',
            ];
            
            // Send email
            try {
                Mail::to($customer->email)->send(new PaymentReceiptMail($emailData));
            } catch (\Exception $e) {
                return response()->json([
                    'ResponseCode' => '103',
                    'error' => 'Failed to send payment receipt email. ' . $e->getMessage(),
                ]);
            }
            
            // if($customer->phone!=null)
            // {
            //     $phone = $customer->phone;
            //     $smsmessage = "Thank you! Your payment for [MONTH] has been received.";
            //     $this->sendMessage($phone, $smsmessage);
            // }
            
        } else {
           
        $cusomerCommitmentFee = array(
                'ticket_id' =>$request->ticket_id,
                'customer_id' => $customer->id,
                'payment_amount' =>$request->amount,
                'transaction_id' => $request->transaction_id,
                'payment_date' => date("Y-m-d"),
                'receiving_account' => "Online Payment",
                
            );
            DB::table('customer_commitment_fees')->where('id', $request->order_id)->insert($cusomerCommitmentFee);
            DB::table('customers')->where('id', $customer->id)->update(["status" => "active"]);
        }
        
        // Send push notification to parent devices
        $parent_device_tokens = DB::table('parent_device_tokens')->where('parent_id', '=', $customer->id)->distinct()->get(['device_token', 'parent_id']);
        foreach ($parent_device_tokens as $token) {
            $deviceToken = $token->device_token;
            $title = 'Payment Verified Successfully';
            $message = 'Payment Verified Successfully';
        
            $notificationdata = [
                'Sender' => 'PaymentHistory'
            ];
        
            // Dispatch push notification job
            SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
        
            // Store notification in the database
            DB::table('notifications')->insert([
                'page' => 'PaymentHistory',
                'token' => $customer->token,
                'title' => $title,
                'message' => $message,
                'type' => 'parent',
                'status' => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        


        
        return response()->json([
            'ResponseCode' => '100',
            'message' => "Payment Verified Successfully"
            
        ]);
    }

    public function specialNeeds(Request $request)
    {
         // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        $token = $request->token;

        // Fetch the customer based on the provided token
        $customer = DB::table('customers')->where('token', $token)->first();


        if ($customer === null) {
            // Return response when no customer is found
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }


        $data = DB::table("student_special_needs")->get();

        if($data==null)
        {
             return response()->json([
            'ResponseCode' => '104',
            "error"=> "Data not found."
        ]);
        }

      return response()->json([
            'ResponseCode' => '100',
            "message"=> "Data retrieved successfully.",
            'data' => $data

        ]);
    }

    public function parentLogin(Request $request)
    {
        // Validate that the phone is present in the request
        if (!$request->has('phone')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the phone field. Please fill it in.',
            ]);
        }

        if (empty($request->phone)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the phone field. Please fill it in.',
            ]);
        }

        $phone = $request->phone;
        $parentDetail = DB::table('customers')
            ->where('phone', '=', $phone)
            ->first();

        // If the customer is already registered
        if ($parentDetail) {
            $SixDigitRandomNumber = rand(10000, 99999);
            
            if($parentDetail->token === null){
                $parentDetail->token = (new Token())->Unique('customers', 'token', 60);
                
                $customervalues = [
                    'token' => $parentDetail->token
                ];
                
                DB::table('customers')->where('id', $parentDetail->id)->update($customervalues);
            }

            $values = [
                'parentID' => $parentDetail->id,
                'code' => $SixDigitRandomNumber,
                'token' => $parentDetail->token,
            ];

            $parentVerificationCodeCheck = DB::table('verificationCode')
                ->where('parentID', $parentDetail->id)
                ->first();

            if ($parentVerificationCodeCheck) {
                $updateResult = DB::table('verificationCode')
                    ->where('parentID', $parentDetail->id)
                    ->update($values);
            } else {
                $insertResult = DB::table('verificationCode')->insert($values);
            }

            if (isset($updateResult) && $updateResult || isset($insertResult) && $insertResult) {
                $message = "Your verification code is $SixDigitRandomNumber. It’s valid for 10 minutes.";
                $this->sendVerificationMessage($phone, $message);

                return Response::json([
                    'ResponseCode' => "100",
                    'message' => 'Login Successfully',
                    'data' => [
                        'parent_id' => $parentDetail->id,
                        'contact' => $parentDetail->phone,
                        'token' => $parentDetail->token
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
        $uuidForparent = rand(100, 99999);
        $parenttoken = (new Token())->Unique('customers', 'token', 60);
        $values = [
            'uid' => 'TU-' . $uuidForparent,
            'phone' => $phone,
            'status' => 'unverified',
            'whatsapp' => $phone,
            'token' => $parenttoken
        ];
        $parentLastID = DB::table('customers')->insertGetId($values);

        if ($parentLastID) {
            $parentDetail = DB::table('customers')
                ->where('id', '=', $parentLastID)
                ->first();

            $SixDigitRandomNumber = rand(10000, 99999);
            DB::table('verificationCode')->where('parentID', $parentDetail->id)->delete();
            $valuesVC = [
                'parentID' => $parentLastID,
                'code' => $SixDigitRandomNumber,
                'token' => $parenttoken,
            ];
            $insertVCResult = DB::table('verificationCode')->insert($valuesVC);

            if ($insertVCResult) {
                $message = "Your verification code is $SixDigitRandomNumber. It’s valid for 10 minutes.";
                $this->sendVerificationMessage($phone, $message);

                return response()->json([
                    'ResponseCode' => "100",
                    'message' => 'New Parent Registered Successfully.',
                    'data' => [
                        'parent_id' => $parentDetail->id,
                        'contact' => $parentDetail->phone,
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

    public function registerParentProfile(Request $request)
    {
        
        // Define required fields
        $requiredFields = [
            'token' => 'Parent Token',
            'full_name' => 'Full name',
            // 'dob' => 'Date of birth',
            'email' => 'Email',
            'address' => 'Address',
            'state' => 'State',
            'city' => 'City',
            'postal_code' => 'Postal code',
            'lat' => 'Latitude',
            'long' => 'Longitude',
            'gender' => 'Gender',
            // 'age' => 'Age',
            'phone' => 'Phone',
            'whatsapp' => 'Whatsapp'
        ];
        
        foreach ($requiredFields as $field => $displayName) {
            if (is_null($request->$field)) {
                return response()->json([
                    'ResponseCode' => '102',
                    'error' => "It looks like you missed the ($displayName) field. Please fill it in."
                ]);
            }

            if (empty($request->$field)) {
                return response()->json([
                    'ResponseCode' => '102',
                    'error' => "It looks like you missed the ($displayName) field. Please fill it in."
                ]);
            }
        }

        // Validate email format
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Invalid email format'
            ]);
        }

        // Retrieve the parent record
        $parent = DB::table('customers')->where('token', $request->token)->first();

        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'Parent not found'
            ]);
        }

        // Handle image file if present
        $parentImagePath = '';
        if ($request->hasFile('parentImage')) {
            $file = $request->file('parentImage');
            $parentImage = time() . '.' . $file->extension();
            $file->move(public_path('parentImage'), $parentImage);
            $parentImagePath = url("public/parentImage/{$parentImage}");
        }

        // Prepare update data
        $updateData = [
            'full_name' => $request->full_name,
            'dob' => $request->dob,
            'email' => $request->email,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'latitude' => $request->lat,
            'longitude' => $request->long,
            'gender' => $request->gender,
            'age' => $request->age,
            'phone' => $request->phone,
            'whatsapp' => $request->whatsapp,
             'image' => $parentImagePath
        ];

        // Perform the update
        DB::table('customers')
            ->where('token', $request->token)
            ->update($updateData);
        
        $to = $request->email;
        Mail::to($to)->send(new ParentRegistrationMail());

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Profile registered successfully!',
            'data' => $updateData
        ]);
    }

    public function updateParentProfile(Request $request)
    {
        // Ensure that the token is present in the request
        if (!$request->has('token') || empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the or empty token field. Please fill it in.',
            ]);
        }

        // Retrieve the parent record
        $parent = DB::table('customers')->where('token', $request->token)->first();

        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'Parent not found',
            ]);
        }

        // Define possible fields for update
        $possibleFields = [
            'full_name',
            'dob',
            'email',
            'address',
            'city',
            'state',
            'postal_code',
            'lat' => 'latitude',
            'long' => 'longitude',
            'gender',
            'age',
            'phone',
            'whatsapp'
        ];

        // Prepare update data only for fields present in the request
        // $updateData = [];
        // foreach ($possibleFields as $field => $column) {
        //     // Handle cases where the array key is numeric (no alias needed)
        //     $fieldKey = is_numeric($field) ? $column : $field;
        //     if ($request->filled($fieldKey)) {
        //         $updateData[is_numeric($field) ? $fieldKey : $column] = $request->$fieldKey;
        //     }
        // }


        // Prepare update data only for fields present in the request
        $updateData = [];
        foreach ($possibleFields as $field => $column) {
            // Handle cases where the array key is numeric (no alias needed)
            $fieldKey = is_numeric($field) ? $column : $field;
            if ($request->filled($fieldKey)) {
                // Validate email format if the field is 'email'
                if ($fieldKey === 'email' && !filter_var($request->$fieldKey, FILTER_VALIDATE_EMAIL)) {
                    return response()->json([
                        'ResponseCode' => '102',
                        'error' => 'Invalid email format'
                    ]);
                }
                $updateData[is_numeric($field) ? $fieldKey : $column] = $request->$fieldKey;
            }
        }

        // Handle image file if present
        if ($request->hasFile('parentImage')) {
            $file = $request->file('parentImage');
            $parentImageName = time() . '.' . $file->extension();
            $file->move(public_path('parentImage'), $parentImageName);
            $parentImagePath = url("public/parentImage/{$parentImageName}");

            // Include the image path in the update data
            $updateData['image'] = $parentImagePath;
        }


        // Check if there is any data to update
        if (empty($updateData)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'No data provided for update'
            ]);
        }

        // Perform the update
        DB::table('customers')
            ->where('token', $request->token)
            ->update($updateData);

         try {

            $data = [
                "ResponseCode" => "100",
                "message" => "Profile Updated"
            ];

            //tutor
            event(new ParentProfile($data,$parent->token));

        } catch(Exception $e) {
           return response()->json(["ResponseCode"=> "103",
        		"error"=> "Unable to get New Attendance"]);
        }

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Profile updated successfully!',
            'data' => $updateData
        ]);
    }

    public function parentStudents(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        $token = $request->token;

        // Fetch the customer based on the provided token
        $customer = DB::table('customers')->where('token', $token)->first();

        if ($customer === null) {
            // Return response when no customer is found
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        // Fetch students associated with the customer
        $students = Student::where('customer_id', $customer->id)->get();

        if ($students->isEmpty()) {
            // Return response when no students are found
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No students found for this customer.',
            ]);
        }

        // Prepare the result
        $result = [];
        foreach ($students as $key => $student) {
            $result[$key] = [
                'id' => $student->id,
                'name' => $student->full_name,
                'gender' => $student->gender,
                'age' => $student->age,
                'dob' => $student->dob,
                'subject' => $student->full_name,
                'specialNeed' => $student->specialNeed,
            ];
        }

        // Return the response with the student data
        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Students data fetched successfully.',
            'data' => $result,
        ]);
    }

    public function studentsDetails(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        // Validate that the token is present in the request
        if (!$request->has('student_id')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the student_id field. Please fill it in.',
            ]);
        }

        if (empty($request->student_id)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the student_id field. Please fill it in.',
            ]);
        }


        $student_id = $request->student_id;
        $token = $request->token;

        // Retrieve the parent record
        $parent = DB::table('customers')->where('token', $token)->first();

        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'Parent not found'
            ]);
        }

        // Fetch the student details based on the provided student ID
        $studentDetail = DB::table('students')->where('id', $student_id)->first();

        if (!$studentDetail) {
            // Return response when no student is found
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No student found with the provided ID.',
            ]);
        }

        // Check if the student belongs to the parent
        if ($studentDetail->customer_id != $parent->id) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'The student does not belong to this parent.',
            ]);
        }

        // Return the response with the student details
        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Student details fetched successfully.',
            'data' => $studentDetail,
        ]);
    }

    public function getParentDetailByID(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        $token = $request->token;

        // Fetch customer information
        $customer = DB::table('customers')->where('token', $token)->first();

        if ($customer === null) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
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
            ? url("{$customerDetail->image}")
            : url("/public/person_place_holder.png");

        // Use libphonenumber to parse the phone number
        try {
            $phoneUtil = PhoneNumberUtil::getInstance();
            $numberProto = $phoneUtil->parse($customerDetail->phone, null);

            $countryCodeNumeric = $numberProto->getCountryCode(); // Numeric country code
            $nationalNumber = $numberProto->getNationalNumber();
            $countryCodeAlpha = $phoneUtil->getRegionCodeForNumber($numberProto); // Alphabetic country code (ISO 3166-1 alpha-2)

            // Set the country code and phone number in the customer detail
            $customerDetail->country_code = "+{$countryCodeNumeric}"; // Numeric country code with '+' prefix
            $customerDetail->country_code_alpha = $countryCodeAlpha; // Alphabetic country code
            $customerDetail->phone_number = $nationalNumber;

        } catch (NumberParseException $e) {
            $customerDetail->country_code = '';
            $customerDetail->country_code_alpha = '';
            $customerDetail->phone_number = $customerDetail->phone;
        }


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
        
    
        // Get Attended Hours with Student Join
        $queryResult = DB::table('class_attendeds as ca')
            ->join('students as s', 'ca.studentID', '=', 's.id')  // Join students table
            ->select(DB::raw('SUM(
                    CASE
                        WHEN ca.totalTime LIKE "%.%" THEN ca.totalTime * 3600
                        ELSE TIME_TO_SEC(ca.totalTime)
                    END
                ) AS totalSeconds'))
            ->where([
                's.customer_id' => $customer->id,  // Use customer_id from students table
                'ca.status' => 'attended',
                'ca.is_paid' => 'unpaid',
                'ca.is_tutor_paid' => 'unpaid'
            ])
            ->first();


        $totalSeconds = $queryResult->totalSeconds ?? 0; // Default to 0 if null
        $attendedDurationInHours = $totalSeconds / 3600; // Convert seconds to hours
        // $attendedHours = number_format($attendedDurationInHours, 2);
        
        // Assuming $attendedDurationInHours is in total hours (float)
        // $attendedDurationInHours = 5.75; // Example value, replace with your actual value
        
        // Convert hours to hours and minutes
        $hours = floor($attendedDurationInHours); // Get the whole hours
        $minutes = ($attendedDurationInHours - $hours) * 60; // Get the remaining minutes
        
        // Format the output
        $attendedHours = sprintf("%d hr and %.0f min", $hours, $minutes);

        // Add sumTotalTime and subjectDetails to the customerDetail object
        $customerDetail->sumTotalTime = $attendedHours;
        $customerDetail->subjectDetails = $subjectDetails->map(function ($detail) {
            $detail->total_time = round($detail->total_time, 2);
            return $detail;
        });

        // Check Commitment fee
        $customerCommitmentFee = DB::table('customer_commitment_fees')->where('customer_id', $customer->id)->first();
        $customerCommitmentFeeCheck = $customerCommitmentFee ? true : false;

        $customerDetail->commitmentFeeAmount = 50;
        $customerDetail->commitmentFee = $customerCommitmentFee;
        
        // // Check if there is any ticket with status 'approved' for the customer's students
        // $ticketApproved = DB::table('job_tickets as t')
        //     ->join('students as s', 't.student_id', '=', 's.id')
        //     ->where('s.customer_id', $customer->id)
        //     ->where('t.status', 'approved')
        //     ->exists();
    
        // // Logic to determine if the commitment fee button should be displayed
        // if ($ticketApproved && $customer->status === 'unverified') {
        //     $customerDetail->showCommitmentFeeButton = true;
        // } else {
        //     $customerDetail->showCommitmentFeeButton = false;
        // }
        
        // Retrieve the id of the approved ticket for the customer's students
        $approvedTicket = DB::table('job_tickets as t')
            ->join('students as s', 't.student_id', '=', 's.id')
            ->where('s.customer_id', $customer->id)
            ->where('t.status', 'approved')
            ->select('t.id') // Retrieve the ticket id
            ->first(); // Get the first approved ticket (or null if none found)
        
        // Logic to determine if the commitment fee button should be displayed
        if ($approvedTicket && $customer->status === 'unverified') {
            $customerDetail->showCommitmentFeeButton = true;
            $customerDetail->approvedTicketId = $approvedTicket->id; // Set the approved ticket id
        } else {
            $customerDetail->showCommitmentFeeButton = false;
            $customerDetail->approvedTicketId = null; // No approved ticket
        }


        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Parent details fetched successfully.',
            'data' => $customerDetail,
        ]);
    }

    public function jobTicketDetails(Request $request)
    {

        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        $token = $request->token;
        $ticket_id = $request->ticket_id;

        // Fetch customer information
        $customer = DB::table('customers')->where('token', $token)->first();

        if ($customer === null) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        // Initialize an empty result array
        $resultData = [];

        // Check if a ticket exists with the given ticket_id
        $ticketExists = DB::table('job_tickets')
            ->where('job_tickets.uid', $ticket_id)
            ->leftJoin('students', 'students.id', '=', 'job_tickets.student_id')
            ->select('students.customer_id')
            ->first();


        if (!$ticketExists) {
            // Return a response indicating that no ticket was found with the given structure
            return Response::json([
                'ResponseCode' => '104',
                'error' => 'No tickets found. Invalid Ticket ID:' . $ticket_id
            ]);
        }

        // Check if the ticket belongs to the customer
        if ($ticketExists->customer_id != $customer->id) {
            return Response::json([
                'ResponseCode' => '104',
                'error' => 'The ticket does not belong to the given customer!'
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
                DB::raw('job_tickets.totalPrice - job_tickets.extra_student_total as basePrice'), // Fixed line
                'job_tickets.job_ticket_requirement as job_ticket_requirement',
                'job_tickets.totalPrice as totalTicketPrice',
                'job_tickets.extra_student_total as extraFee',
                'job_tickets.totalPrice as totalTicketPrice',
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
                'categories.price as subjectPrice',
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
                'total_commission_before_eight_hours' => $ticket->per_class_commission_before_eight_hours * $hoursBeforeEight,
                'total_commission_after_eight_hours' => $ticket->per_class_commission_after_eight_hours * $hoursAfterEight,
                'per_class_commission_before_eight_hours' => $ticket->per_class_commission_before_eight_hours,
                'per_class_commission_after_eight_hours' => $ticket->per_class_commission_after_eight_hours,
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
                'commitmentFee' => $commitmentFeeCheck ? $commitmentFeeCheck->payment_amount : "100",
                'commitmentFeePaid' => $commitmentFeePaid,
                'basePrice' => $ticket->basePrice,
                'extraFee' => $ticket->extraFee,
                'totalTicketPrice' => $ticket->totalTicketPrice,
                'subjectPrice' => $ticket->subjectPrice
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
                    'extraFee' => ($student->extra_fee * $ticketData['classFrequency'] * $ticketData['quantity']),
                ];
                $ticketData['jobTicketExtraStudents'][] = $studentData;
            }

            // Add the count of jobTicketExtraStudents
            $ticketData['extraStudentCount'] = count($ticketData['jobTicketExtraStudents']);

            $resultData[] = $ticketData;
        }

        return Response::json([
            'ResponseCode' => '100',
            'message' => 'Data retrieved successfully.',
            'data' => $resultData
        ]);
    }

    public function verificationCode(Request $req)
    {
        // Validate that the token is present in the request
        if (!$req->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($req->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        // Validate that the code is present in the request
        if (!$req->has('code')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the code field. Please fill it in.',
            ]);
        }

        if (empty($req->code)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the code field. Please fill it in.',
            ]);
        }

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
                'ResponseCode' => '101',
                'error' => 'Parent Not found!'

            ]);
        }

        // Check for verification code in database
        $verificationCode = DB::table('verificationCode')
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
                'ResponseCode' => '100',
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

    public function StoreDeviceToken(Request $req)
    {
        // Validate that the token is present in the request
        if (!$req->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($req->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        // Validate that the device_token is present in the request
        if (!$req->has('device_token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the device_token field. Please fill it in.',
            ]);
        }

        if (empty($req->device_token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the device_token field. Please fill it in.',
            ]);
        }

        $device_token = $req->device_token;
        $token = $req->token;

        // Fetch customer information
        $customer = DB::table('customers')->where('token', $token)->first();

        if ($customer === null) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        $values = array(
            'parent_id' => $customer->id,
            'device_token' => $device_token
        );

        $existingToken = DB::table('parent_device_tokens')
            ->where('parent_id', $customer->id)
            ->first();

        if ($existingToken) {
            // Update existing token
            DB::table('parent_device_tokens')
                ->where('parent_id', $customer->id)
                ->update(['device_token' => $device_token]);

            $tutorDeviceToken = DB::table('parent_device_tokens')
                ->where('parent_id', $customer->id)
                ->first();
        } else {
            // Insert new token
            $deviceTokenLastID = DB::table('parent_device_tokens')->insertGetId($values);

            $tutorDeviceToken = DB::table('parent_device_tokens')
                ->where('id', '=', $deviceTokenLastID)
                ->first();
        }

        return Response::json([
            'ResponseCode' => '100',
            'message' => 'Device Token Saved successfully.',
            'data' => $tutorDeviceToken
        ]);

    }


    public function storeStudent(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        // Define required fields
        $requiredFields = [
            'studentFullName' => 'Student Full Name',
            'studentDateOfBirth' => 'Date of Birth',
            'studentGender' => 'Gender',
            'specialNeed' => 'Special Need',
            'age' => 'Age'
        ];

        // Check for missing required fields
        foreach ($requiredFields as $field => $fieldName) {
            if (!$request->has($field) || empty($request->input($field))) {
                return response()->json([
                    'ResponseCode' => '102',
                    'error' => "It looks like you missed the ($fieldName) field. Please fill it in."
                ]);
            }
        }

        // Find the parent customer by token
        $parent = DB::table('customers')->where('token', $request->token)->first();

        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
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

        try {
            // Insert the student and get the last inserted ID
            $studentLastID = DB::table('students')->insertGetId($studentValues);
            
            // Retrieve the first parent device token
            $parent_device_token = DB::table('parent_device_tokens')->where('parent_id', '=', $parent->id)->distinct()->first(['device_token', 'parent_id']);
            
            if ($parent_device_token) {
                $deviceToken = $parent_device_token->device_token;
                $title = 'Student Added';
                $message = 'Student Added Successfully';
            
                $notificationdata = [
                    'Sender' => 'Students'
                ];
            
                // Dispatch push notification job
                SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
            
                // Store notification in the database
                DB::table('notifications')->insert([
                    'page' => 'Students',
                    'token' => $parent->token,
                    'title' => $title,
                    'message' => $message,
                    'type' => 'parent',
                    'status' => 'new',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Retrieve the newly inserted student
            $student = DB::table('students')->where('id', $studentLastID)->first();

            $data = [
                "ResponseCode" => "100",
                "message" => "New Student Stored Successfully"
            ];

            //parent
            event(new StudentList($data,$parent->token));


            return response()->json([
                'ResponseCode' => '100',
                'message' => 'Student added successfully.',
                'data' => $student
            ]);
        } catch (\Exception $e) {
            // Return a response indicating a database error with exception details
            return response()->json([
                'ResponseCode' => '103',
                'error' => 'Database Error: ' . $e->getMessage()
            ]);
        }
    }

    // public function submitTicket(Request $request)
//     {
//         // Define the required fields
//         $requiredFields = [
//             'token', 'subject', 'quantity', 'classFrequency', 'tutorPereference', 'day',
//             'time', 'subscription', 'registration_date', 'classType', 'job_ticket_requirement'
//         ];

//         // Check for missing or empty fields
//         foreach ($requiredFields as $field) {
//             if (!$request->has($field)) {
//                 return response()->json([
//                     'ResponseCode' => '102',
//                     'error' => "It looks like you missed the ($field) field. Please fill it in.",
//                 ]);
//             }

//             if (empty($request->$field)) {
//                 return response()->json([
//                     'ResponseCode' => '102',
//                     'error' => "It looks like you missed the ($field) field. Please fill it in.",
//                 ]);
//             }
//         }

//         // Validate the token
//         $token = $request->token;
//         $parent = DB::table('customers')->where('token', $token)->first();

//         if (!$parent) {
//             return response()->json([
//                 'ResponseCode' => '101',
//                 'error' => 'No Parent found!',
//             ]);
//         }

//         $data = $request->all();

//         $studentLastID = $request->students[0]["id"];

//         $latestTicketID = DB::table('job_tickets')->latest('created_at')->first();
//         if ($latestTicketID) {
//             $ticketIDs = $latestTicketID->id + 1;
//         } else {
//             $ticketIDs = 1;
//         }

//         $subject = $data['subject'];

//         $dayArray = array();

//         foreach ($data['day'] as $selectedDay) {
//             $dayArray[] = $selectedDay;
//         }

//         $extraStudentFeeCharges = DB::table('extra_student_charges')->orderBy('id', 'DESC')->first();

//         $subjectFee = DB::table('products')->join("categories", "products.category", "=", "categories.id")
//             ->select("products.*", "categories.price as category_price", "categories.mode as mode")
//             ->where('products.id', '=', $data['subject'])->first();
//         $uuidForTicket = rand(100, 99999);


//         $ticektValues = array(
//             'ticket_id' => $ticketIDs,
//             'uid' => 'JT-' . $uuidForTicket,
//             'student_id' => $studentLastID,
//             'admin_charge' => $request->inCharge,
//             'service' => $request->service,
//             'subjects' => $data['subject'],
//             'subject_fee' => $subjectFee->category_price,
//             'quantity' => $data['quantity'],
//             'classFrequency' => $data['classFrequency'],
//             'remaining_classes' => $data['classFrequency'],
//             'tutorPereference' => $data['tutorPereference'],
//             'day' => json_encode(implode(",", $dayArray)),
//             'time' => $data['time'],
//             'subscription' => $data['subscription'],
//             'specialRequest' => $request->job_ticket_requirement,
//             'classAddress' => $request->classAddress,
//             'classLatitude' => $request->classLatitude,
//             'classLongitude' => $request->classLongitude,
//             'classCity' => $request->classCity,
//             'classState' => $request->classState,
//             'classPostalCode' => $request->classPostalCode,
//             'register_date' => date("Y-m-d"),
//             'mode' => $request->classType,
//             'estimate_commission' => $request->estimate_commission,
//             'job_ticket_requirement' => $request->job_ticket_requirement,
//             'status' => 'pending'
//         );

//         $jobTicketLastID = DB::table('job_tickets')->insertGetId($ticektValues);

//         $student_data = array(
//             'student_id' => $studentLastID,
//             'ticket_id' => $jobTicketLastID,
//             'ticket_id2' => $ticketIDs,
//             'subject' => $data['subject'],
//             'quantity' => $data['quantity'],
//             'classFrequency' => $data['classFrequency'],
//             'remaining_classes' => $data['classFrequency'],
//             'day' => json_encode(implode(",", $dayArray)),
//             'time' => $data['time'],
//             'subscription' => $data['subscription'],
//             'specialRequest' => $request->job_ticket_requirement,
//         );
//         DB::table('student_subjects')->insertGetId($student_data);


//         if (isset($data["students"])) {
//     $i = 0; // Index counter
//     foreach ($data['students'] as $student) {
//         $i++;
//         if ($i == 1) {
//             continue; // Skip the first iteration
//         }

//         if ($student['studentFullName'] != NULL) {

//             if ($subjectFee->mode == "physical") {
//                 $extraStudentCharges = DB::table("extra_student_charges")->first();
//                 $extraStudentChargesDate = $extraStudentCharges->created_at;
//                 $extraStudentCharges = $extraStudentFeeCharges->physical_additional_charges;

//             } else {
//                 $extraStudentCharges = DB::table("extra_student_charges")->first();
//                 $extraStudentChargesDate = $extraStudentCharges->created_at;
//                 $extraStudentCharges = $extraStudentFeeCharges->online_additional_charges;
//             }

//             $uuidForStudent = rand(100, 99999);

//             $extra_student_charges = DB::table('extra_student_charges')->orderBy('id', 'DESC')->first();
//             $multipleStudent = array(
//                 'student_id' => $student['id'],
//                 'student_name' => $student['studentFullName'],
//                 'student_gender' => $student['studentGender'],
//                 'student_age' => $student['age'],
//                 'year_of_birth' => $student['studentDateOfBirth'],
//                 'special_need' => $student['specialNeed'],
//                 'job_ticket_id' => $jobTicketLastID,
//                 'subject_id' => $subject,
//                 'extra_fee' => $extraStudentCharges,
//                 'extra_fee_date' => $extraStudentChargesDate,
//             );

//             DB::table('job_ticket_students')->insertGetId($multipleStudent);
//         }
//     }
// }

//         $studentDetail = DB::table('students')->where('id', '=', $studentLastID)->first();
//         $customerDetail = DB::table('customers')->where('id', '=', $studentDetail->customer_id)->first();
//         $subjectDetail = DB::table('products')->join("categories", "products.category", "=", "categories.id")
//             ->select("products.*", "categories.price as category_price", "categories.mode as class_mode", "categories.category_name as category_name")->
//             where('products.id', '=', $data['subject'])->first();

//         $tableName = 'job_ticket_students';
//         $count = DB::table($tableName)
//             ->select(DB::raw('count(*) as count'))
//             ->where('job_ticket_id', '=', $jobTicketLastID)
//             ->first()
//             ->count;


//         if ($subjectDetail->class_mode == "physical") {
//             $extraStudentCharges = DB::table("extra_student_charges")->first();
//             $extraStudentCharges = $extraStudentFeeCharges->physical_additional_charges;

//         }
//         else {
//             $extraStudentCharges = DB::table("extra_student_charges")->first();
//             $extraStudentCharges = $extraStudentFeeCharges->online_additional_charges;

//         }

//         $extraStudentFeeCharges = DB::table('extra_student_charges')->orderBy('id', 'DESC')->first();

//         $jobTicketID = DB::table('job_tickets')->where('id', '=', $jobTicketLastID)->first();


//         $price = $subjectDetail->category_price;

//         $classFrequency = floatval($data['classFrequency']);

//         $quantity = floatval($data['quantity']);

//         if ($subjectDetail->class_mode == "physical") {
//             $extraCharges = $count * $extraStudentFeeCharges->physical_additional_charges;
//             $extraChargesTutorComission = $count * $extraStudentFeeCharges->tutor_physical;

//         }
//         else {
//             $extraCharges = $count * $extraStudentFeeCharges->online_additional_charges;
//             $extraChargesTutorComission = $count * $extraStudentFeeCharges->tutor_online;

//         }

//         $tableName = 'job_ticket_students';

//         $count = DB::table($tableName)
//             ->select(DB::raw('count(*) as count'))
//             ->where('job_ticket_id', '=', $ticketIDs)
//             ->first()
//             ->count;

//         $class_term = $jobTicketID->subscription;
//         $modeOfClass = $subjectDetail->class_mode;
//         $category_id_subject = $subjectDetail->category;
//         $category_level_subject = $subjectDetail->category_name;
//         $estimate_commission = 0;
//         $estimate_after_eight_hours = 0;



//         //Long Term Classes prices according to hours
//         $long_term_online_first_eight_hours = [
//             'Pre-school' => 9,
//             'UPSR' => 9,
//             'PT3' => 9,
//             'SPM' => 10.5,
//             'IGCSE' => 10.5,
//             'STPM' => 12,
//             'A-level/Pre-U' => 12,
//             'Diploma' => 13.5,
//             'Degree' => 15,
//             'ACCA' => 18,
//             'Master' => 18,
//         ];

//         $long_term_online_after_eight_hours = [
//             'Pre-school' => 21,
//             'UPSR' => 21,
//             'PT3' => 21,
//             'SPM' => 24.5,
//             'IGCSE' => 24.5,
//             'STPM' => 28,
//             'A-level/Pre-U' => 28,
//             'Diploma' => 31.5,
//             'Degree' => 35,
//             'ACCA' => 42,
//             'Master' => 42,
//         ];

//         $long_term_physical_first_eight_hours = [
//             'Pre-school' => 15,
//             'UPSR' => 15,
//             'PT3' => 15,
//             'SPM' => 18,
//             'IGCSE' => 21,
//             'STPM' => 24,
//             'A-level/Pre-U' => 24,
//             'Diploma' => 27,
//             'Degree' => 30,
//             'ACCA' => 36,
//             'Master' => 36,
//         ];

//         $long_term_physical_after_eight_hours = [
//             'Pre-school' => 35,
//             'UPSR' => 35,
//             'PT3' => 35,
//             'SPM' => 40,
//             'IGCSE' => 49,
//             'STPM' => 56,
//             'A-level/Pre-U' => 56,
//             'Diploma' => 63,
//             'Degree' => 70,
//             'ACCA' => 84,
//             'Master' => 84,
//         ];

//         //Short Term Classes prices according to hours
//         $short_term_online = [
//             'Pre-school' => 18,
//             'UPSR' => 18,
//             'PT3' => 18,
//             'SPM' => 21,
//             'IGCSE' => 21,
//             'STPM' => 24,
//             'A-level/Pre-U' => 24,
//             'Diploma' => 27,
//             'Degree' => 30,
//             'ACCA' => 36,
//             'Master' => 36,
//         ];

//         $short_term_physical = [
//             'Pre-school' => 30,
//             'UPSR' => 30,
//             'PT3' => 30,
//             'SPM' => 36,
//             'IGCSE' => 42,
//             'STPM' => 48,
//             'A-level/Pre-U' => 48,
//             'Diploma' => 54,
//             'Degree' => 60,
//             'ACCA' => 72,
//             'Master' => 72,
//         ];

//         $per_class_commission_before_eight_hours = 0;
//         $per_class_commission_after_eight_hours = 0;

//         if ($class_term == "Long-Term") {
//             if ($modeOfClass == "online") {
//                 switch ($category_level_subject) {

//                     case "Pre-school":
//                         $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
//                         $per_hour_charges = $long_term_online_first_eight_hours["Pre-school"];
//                         $per_hour_charges_addition = $long_term_online_after_eight_hours["Pre-school"];

//                         $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         if ($numberOfSessions <= 8) {
//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
//                             $per_class_commission_after_eight_hours = 0;

//                         } else {

//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
//                             $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
//                             $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

//                         }


//                         break;

//                     case "UPSR":
//                         $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);


//                         $per_hour_charges = $long_term_online_first_eight_hours["UPSR"];
//                         $per_hour_charges_addition = $long_term_online_after_eight_hours["UPSR"];
//                         $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         if ($numberOfSessions <= 8) {
//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
//                             $per_class_commission_after_eight_hours = 0;

//                         } else {

//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
//                             $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
//                             $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

//                         }


//                         break;

//                     case "PT3":
//                         $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
//                         $per_hour_charges = $long_term_online_first_eight_hours["PT3"];
//                         $per_hour_charges_addition = $long_term_online_after_eight_hours["PT3"];
//                         $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         if ($numberOfSessions <= 8) {
//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
//                             $per_class_commission_after_eight_hours = 0;

//                         } else {

//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
//                             $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
//                             $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

//                         }


//                         break;

//                     case "SPM":
//                         $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);

//                         $per_hour_charges = $long_term_online_first_eight_hours["SPM"];
//                         $per_hour_charges_addition = $long_term_online_after_eight_hours["SPM"];

//                         $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         if ($numberOfSessions <= 8) {
//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
//                             $per_class_commission_after_eight_hours = 0;

//                         } else {

//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
//                             $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
//                             $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

//                         }

//                         break;

//                     case "IGCSE":
//                         $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
//                         $per_hour_charges = $long_term_online_first_eight_hours["IGCSE"];
//                         $per_hour_charges_addition = $long_term_online_after_eight_hours["IGCSE"];
//                         $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         if ($numberOfSessions <= 8) {
//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
//                             $per_class_commission_after_eight_hours = 0;

//                         } else {

//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
//                             $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
//                             $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

//                         }

//                         break;

//                     case "STPM":
//                         $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
//                         $per_hour_charges = $long_term_online_first_eight_hours["STPM"];
//                         $per_hour_charges_addition = $long_term_online_after_eight_hours["STPM"];
//                         $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         if ($numberOfSessions <= 8) {
//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
//                             $per_class_commission_after_eight_hours = 0;

//                         } else {

//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
//                             $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
//                             $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

//                         }

//                         break;

//                     case "A-level/Pre-U":
//                         $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
//                         $per_hour_charges = $long_term_online_first_eight_hours["A-level/Pre-U"];
//                         $per_hour_charges_addition = $long_term_online_after_eight_hours["A-level/Pre-U"];
//                         $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
//                         if ($numberOfSessions <= 8) {
//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
//                             $per_class_commission_after_eight_hours = 0;

//                         } else {

//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
//                             $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
//                             $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

//                         }

//                         break;

//                     case "Diploma":
//                         $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
//                         $per_hour_charges = $long_term_online_first_eight_hours["Diploma"];
//                         $per_hour_charges_addition = $long_term_online_after_eight_hours["Diploma"];
//                         $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         if ($numberOfSessions <= 8) {
//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
//                             $per_class_commission_after_eight_hours = 0;

//                         } else {

//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
//                             $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
//                             $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

//                         }

//                         break;

//                     case "Degree":
//                         $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
//                         $per_hour_charges = $long_term_online_first_eight_hours["Degree"];
//                         $per_hour_charges_addition = $long_term_online_after_eight_hours["Degree"];
//                         $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         if ($numberOfSessions <= 8) {
//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
//                             $per_class_commission_after_eight_hours = 0;

//                         } else {

//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
//                             $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
//                             $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

//                         }

//                         break;

//                     case "ACCA":
//                         $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
//                         $per_hour_charges = $long_term_online_first_eight_hours["ACCA"];
//                         $per_hour_charges_addition = $long_term_online_after_eight_hours["ACCA"];
//                         $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         if ($numberOfSessions <= 8) {
//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
//                             $per_class_commission_after_eight_hours = 0;

//                         } else {

//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
//                             $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
//                             $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

//                         }

//                         break;

//                     case "Master":
//                         $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
//                         $per_hour_charges = $long_term_online_first_eight_hours["Master"];
//                         $per_hour_charges_addition = $long_term_online_after_eight_hours["Master"];
//                         $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));


//                         if ($numberOfSessions <= 8) {
//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
//                             $per_class_commission_after_eight_hours = 0;

//                         } else {

//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
//                             $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
//                             $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

//                         }


//                         break;

//                 }
//             } elseif ($modeOfClass == "physical") {

//                 switch ($category_level_subject) {

//                     case "Pre-school":
//                         $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
//                         $per_hour_charges = $long_term_physical_first_eight_hours["Pre-school"];
//                         $per_hour_charges_addition = $long_term_physical_after_eight_hours["Pre-school"];
//                         $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         if ($numberOfSessions <= 8) {
//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
//                             $per_class_commission_after_eight_hours = 0;

//                         } else {

//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
//                             $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
//                             $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

//                         }


//                         break;

//                     case "UPSR":
//                         $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
//                         $per_hour_charges = $long_term_physical_first_eight_hours["UPSR"];
//                         $per_hour_charges_addition = $long_term_physical_after_eight_hours["UPSR"];
//                         $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         if ($numberOfSessions <= 8) {
//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
//                             $per_class_commission_after_eight_hours = 0;

//                         } else {

//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
//                             $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
//                             $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

//                         }


//                         break;

//                     case "PT3":
//                         $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
//                         $per_hour_charges = $long_term_physical_first_eight_hours["PT3"];
//                         $per_hour_charges_addition = $long_term_physical_after_eight_hours["PT3"];
//                         $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         if ($numberOfSessions <= 8) {
//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
//                             $per_class_commission_after_eight_hours = 0;

//                         } else {

//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
//                             $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
//                             $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

//                         }


//                         break;

//                     case "SPM":
//                         $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
//                         $per_hour_charges = $long_term_physical_first_eight_hours["SPM"];
//                         $per_hour_charges_addition = $long_term_physical_after_eight_hours["SPM"];
//                         $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         if ($numberOfSessions <= 8) {
//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
//                             $per_class_commission_after_eight_hours = 0;

//                         } else {

//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
//                             $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
//                             $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

//                         }


//                         break;

//                     case "IGCSE":
//                         $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
//                         $per_hour_charges = $long_term_physical_first_eight_hours["IGCSE"];
//                         $per_hour_charges_addition = $long_term_physical_after_eight_hours["IGCSE"];
//                         $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         if ($numberOfSessions <= 8) {
//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
//                             $per_class_commission_after_eight_hours = 0;

//                         } else {

//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
//                             $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
//                             $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

//                         }


//                         break;

//                     case "STPM":
//                         $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
//                         $per_hour_charges = $long_term_physical_first_eight_hours["STPM"];
//                         $per_hour_charges_addition = $long_term_physical_after_eight_hours["STPM"];
//                         $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         if ($numberOfSessions <= 8) {
//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
//                             $per_class_commission_after_eight_hours = 0;

//                         } else {

//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
//                             $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
//                             $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

//                         }


//                         break;

//                     case "A-level/Pre-U":
//                         $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
//                         $per_hour_charges = $long_term_physical_first_eight_hours["A-level/Pre-U"];
//                         $per_hour_charges_addition = $long_term_physical_after_eight_hours["A-level/Pre-U"];
//                         $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
//                         if ($numberOfSessions <= 8) {
//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
//                             $per_class_commission_after_eight_hours = 0;

//                         } else {

//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
//                             $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
//                             $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

//                         }

//                         break;

//                     case "Diploma":
//                         $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
//                         $per_hour_charges = $long_term_physical_first_eight_hours["Diploma"];
//                         $per_hour_charges_addition = $long_term_physical_after_eight_hours["Diploma"];
//                         $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         if ($numberOfSessions <= 8) {
//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
//                             $per_class_commission_after_eight_hours = 0;

//                         } else {

//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
//                             $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
//                             $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

//                         }

//                         break;

//                     case "Degree":
//                         $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
//                         $per_hour_charges = $long_term_physical_first_eight_hours["Degree"];
//                         $per_hour_charges_addition = $long_term_physical_after_eight_hours["Degree"];
//                         $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         if ($numberOfSessions <= 8) {
//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
//                             $per_class_commission_after_eight_hours = 0;

//                         } else {

//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
//                             $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
//                             $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

//                         }


//                         break;

//                     case "ACCA":
//                         $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
//                         $per_hour_charges = $long_term_physical_first_eight_hours["ACCA"];
//                         $per_hour_charges_addition = $long_term_physical_after_eight_hours["ACCA"];
//                         $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         if ($numberOfSessions <= 8) {
//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
//                             $per_class_commission_after_eight_hours = 0;

//                         } else {

//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
//                             $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
//                             $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

//                         }


//                         break;

//                     case "Master":
//                         $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
//                         $per_hour_charges = $long_term_physical_first_eight_hours["Master"];
//                         $per_hour_charges_addition = $long_term_physical_after_eight_hours["Master"];
//                         $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         if ($numberOfSessions <= 8) {
//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
//                             $per_class_commission_after_eight_hours = 0;

//                         } else {

//                             $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
//                             $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
//                             $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
//                             $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

//                         }


//                         break;

//                 }
//             }
//         }
//         else {
//             if ($modeOfClass == "online") {


//                 switch ($category_level_subject) {

//                     case "Pre-school":

//                         $per_hour_charges = $short_term_online["Pre-school"];

//                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
//                         $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
//                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

//                         break;


//                     case "UPSR":
//                         $per_hour_charges = $short_term_online["UPSR"];

//                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
//                         $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
//                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

//                         break;

//                     case "PT3":
//                         $per_hour_charges = $short_term_online["PT3"];

//                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
//                         $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
//                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

//                         break;


//                     case "SPM":

//                         $per_hour_charges = $short_term_online["SPM"];

//                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
//                         $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
//                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

//                         break;


//                     case "IGCSE":
//                         $per_hour_charges = $short_term_online["IGCSE"];

//                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
//                         $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
//                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

//                         break;

//                     case "STPM":
//                         $per_hour_charges = $short_term_online["STPM"];

//                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
//                         $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
//                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

//                         break;

//                     case "A-level/Pre-U":
//                         $per_hour_charges = $short_term_online["A-level/Pre-U"];

//                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
//                         $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
//                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

//                         break;

//                     case "Diploma":
//                         $per_hour_charges = $short_term_online["Diploma"];

//                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
//                         $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
//                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

//                         break;

//                     case "Degree":
//                         $per_hour_charges = $short_term_online["Degree"];

//                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
//                         $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
//                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

//                         break;

//                     case "ACCA":
//                         $per_hour_charges = $short_term_online["ACCA"];

//                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
//                         $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
//                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

//                         break;

//                     case "Master":
//                         $per_hour_charges = $short_term_online["Master"];

//                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
//                         $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
//                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

//                         break;

//                 }
//             } elseif ($modeOfClass == "physical") {
//                 switch ($category_level_subject) {

//                     case "Pre-school":
//                         $per_hour_charges = $short_term_physical["Pre-school"];

//                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
//                         $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
//                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

//                         break;

//                     case "UPSR":
//                         $per_hour_charges = $short_term_physical["UPSR"];

//                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
//                         $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
//                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

//                         break;

//                     case "PT3":
//                         $per_hour_charges = $short_term_physical["PT3"];

//                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
//                         $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
//                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

//                         break;

//                     case "SPM":
//                         $per_hour_charges = $short_term_physical["SPM"];

//                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
//                         $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
//                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

//                         break;

//                     case "IGCSE":
//                         $per_hour_charges = $short_term_physical["IGCSE"];

//                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
//                         $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
//                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

//                         break;

//                     case "STPM":
//                         $per_hour_charges = $short_term_physical["STPM"];

//                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
//                         $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
//                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

//                         break;

//                     case "A-level/Pre-U":
//                         $per_hour_charges = $short_term_physical["A-level/Pre-U"];

//                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
//                         $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
//                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

//                         break;

//                     case "Diploma":
//                         $per_hour_charges = $short_term_physical["Diploma"];

//                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
//                         $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
//                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

//                         break;

//                     case "Degree":
//                         $per_hour_charges = $short_term_physical["Degree"];

//                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
//                         $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
//                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

//                         break;

//                     case "ACCA":
//                         $per_hour_charges = $short_term_physical["ACCA"];
//                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
//                         $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
//                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

//                         break;

//                     case "Master":
//                         $per_hour_charges = $short_term_physical["Master"];
//                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
//                         $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

//                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
//                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

//                         break;

//                 }
//             }
//         }


//         if ((isset($data["students"]))) {

//             // dd("1");

//             $jobTicketCalc = $subjectFee->category_price + $extraCharges;
//             $jobTicketCalc = $jobTicketCalc * $data['classFrequency'] * $data['quantity'];
//         } else {
//             // dd("2");
//             $jobTicketCalc = $subjectFee->category_price;
//             $jobTicketCalc = $jobTicketCalc * $data['classFrequency'] * $data['quantity'];
//         }


//         $additionalStudentChargesTutor = 0;
//         $additionalStudentChargesJobTicket = 0;
//         $additionalStudentCharges = 0;

//         if ($subjectDetail->class_mode == "physical") {
//             if ($count == 0) {
//                 $additionalStudentChargesTutor = 1 * $extraStudentFeeCharges->tutor_physical;
//                 $additionalStudentChargesJobTicket = 1 * $extraStudentFeeCharges->physical_additional_charges;
//             } else {

//                 $additionalStudentChargesTutor = $count * $extraStudentFeeCharges->tutor_physical;
//                 $additionalStudentChargesJobTicket = $count * $extraStudentFeeCharges->physical_additional_charges;
//             }

//         } else {
//             if ($count == 0) {

//                 $additionalStudentChargesTutor = 1 * $extraStudentFeeCharges->tutor_online;
//                 $additionalStudentChargesJobTicket = 1 * $extraStudentFeeCharges->online_additional_charges;
//             } else {
//                 $additionalStudentChargesTutor = $count * $extraStudentFeeCharges->tutor_physical;
//                 $additionalStudentChargesJobTicket = $count * $extraStudentFeeCharges->online_additional_charges;
//             }
//         }


//         if ($count == 0) {
//             $count = 1;
//             $additionalStudentCharges = $additionalStudentChargesTutor * 1 * (floatval($data['classFrequency']) * floatval($data['quantity']));
//             $additionalStudentChargesJobTicket = $additionalStudentChargesJobTicket * 1 * (floatval($data['classFrequency']) * floatval($data['quantity']));
//         } else {
//             $additionalStudentCharges = $additionalStudentChargesTutor * 1 * (floatval($data['classFrequency']) * floatval($data['quantity']));
//             $additionalStudentChargesJobTicket = $additionalStudentChargesJobTicket * 1 * (floatval($data['classFrequency']) * floatval($data['quantity']));

//         }


//         DB::table('job_tickets')
//             ->where('id', $jobTicketLastID)
//             ->update([
//                 'extra_student_total' => $additionalStudentChargesJobTicket,
//                 'extra_student_tutor_commission' => $additionalStudentCharges,
//                 'extra_estimate_commission_display_tutor' => $additionalStudentCharges,
//                 'estimate_commission' => $estimate_commission,
//                 'estimate_commission_display_tutor' => $estimate_after_eight_hours,
//                 'totalPrice' => $jobTicketCalc,
//                 'per_class_commission_before_eight_hours' => $per_class_commission_before_eight_hours,
//                 'per_class_commission_after_eight_hours' => $per_class_commission_after_eight_hours
//             ]);

//         if ((isset($data["studentFullName"]))) {

//             $calcPrice = $subjectDetail->category_price + $extraCharges;
//             $calcPrice = $calcPrice * $data['classFrequency'];
//             $calcPrice = $calcPrice * $data['quantity'];
//         } else {

//             $calcPrice = $subjectDetail->category_price;
//             $calcPrice = $calcPrice * $data['classFrequency'];
//             $calcPrice = $calcPrice * $data['quantity'];
//         }


//         // dd($estimate_commission);
//         $invoiceValue = array(
//             'studentID' => $studentLastID,
//             'ticketID' => $jobTicketLastID,
//             'subjectID' => $data['subject'],
//             'account_id' => $customerDetail->id,
//             'invoiceDate' => date('Y-m-d'),
//             'reference' => $jobTicketLastID,
//             'payerName' => $customerDetail->full_name,
//             'payerEmail' => $customerDetail->email,
//             'payerPhone' => $customerDetail->phone,
//             'quantity' => $data['quantity'],
//             'classFrequency' => $data['classFrequency'],
//             'day' => json_encode(implode(",", $dayArray)),
//             'time' => $data['time'],
//             'type' => 'd',
//             'debit' => ($subjectDetail->price * $data['classFrequency'] * $data['quantity']) + ($extraCharges),
//             'credit' => 0,
//             'invoice_status' => "First",
//             'invoiceTotal' => $calcPrice,
//             'sentEmail' => "true",
//             'brand' => $subjectDetail->brand);

//         $invoiceID = DB::table('invoices')->insertGetId($invoiceValue);

//         // Split the days string into an array
//         $daysArray = explode(',', json_decode($invoiceValue['day']));

//         // Get the initial date for the invoice
//         $initialDate = new DateTime($invoiceValue['invoiceDate']);
//         $perClassPriceInvoiceItem = $jobTicketCalc / $request->classFrequency;
//         // Insert records for each day and each occurrence based on ClassFrequency

//         //  return response()->json($data['classFrequency']."+++++".$perClassPriceInvoiceItem."---".$invoiceID."---".$studentLastID."---".$jobTicketLastID,200);

//         for ($j = 0; $j < $data['classFrequency']; $j++) {



//             // Iterate over each day
//             $currentDay = $daysArray[$j % count($daysArray)];
//             // Get the day for the current iteration using modulus


//             //  return response()->json($currentDay);


//             // Calculate the date based on the current day
//             $date = clone $initialDate;
//             while ($date->format('D') !== $currentDay) {
//                 $date->add(new DateInterval('P1D'));
//             }


//                 // Update the initial date to the next occurrence of the current day
//             $initialDate = clone $date;
//             $initialDate->add(new DateInterval('P1D'));





//             // Modify data as needed for each iteration
//             $invoiceItemsData['quantity'] = $data['quantity'];
//             $invoiceItemsData['time'] = $data['time'];
//             $invoiceItemsData['day'] = $currentDay;
//             $invoiceItemsData['isPaid'] = 'unPaid';
//             $invoiceItemsData['studentID'] = $studentLastID;
//             $invoiceItemsData['ticketID'] = $jobTicketLastID;
//             $invoiceItemsData['subjectID'] = $data['subject'];
//             $invoiceItemsData['invoiceID'] = $invoiceID;
//             $invoiceItemsData['invoiceDate'] = $date->format('Y-m-d');
//             $invoiceItemsData['price'] = $perClassPriceInvoiceItem;

//             // Add other fields as needed

//             // Insert into invoice_items table
//             DB::table('invoice_items')->insert($invoiceItemsData);

//         }

//         // Sample data to pass to the view
//         $invoice_detail = DB::table('invoices')->where('id', '=', $invoiceID)->orderBy('id', 'desc')->first();
//         $invoice_items = DB::table('invoice_items')->where('invoiceID', '=', $invoiceID)->orderBy('id', 'desc')->get();
//         $students = DB::table('students')->where('id', '=', $invoice_detail->studentID)->orderBy('id', 'DESC')->first();
//         $customer = DB::table('customers')->where('id', '=', $students->customer_id)->first();

//         $subjects = DB::table('products')->join("categories", "products.category", "=", "categories.id")
//             ->select("products.*", "categories.price as category_price")
//             ->where('products.id', '=', $invoice_detail->subjectID)->first();

//         $tutorListings = DB::table('tutors')->where('status', '=', 'verified')->get();
//         $jobTicketDeails = DB::table('job_tickets')->where('id', '=', $jobTicketLastID)->first();

//          if (isset($customer) && $customer->whatsapp != null) {
//                 $month_year = Carbon::now()->format('F Y'); // Using Carbon for date formatting
//                 $invoice_link = url("/invoicePublicLink") . $invoice_detail->id;
//                 $phone_number = $customer->whatsapp;
//                 $message = "Dear Parent/Student, Your SifuTutor invoice for $month_year is ready! You can easily view and pay your bill online at $invoice_link.
//                 The total amount due is " . $invoice_detail->invoiceTotal . ".
//                 If you prefer, you can also make a payment to our Maybank account: Sifu Edu & Learning Sdn Bhd Account No: 5621 1551 6678.
//                 Please send your payment confirmation to us via WhatsApp at www.wasap.my/60146037500. If you have any enquiry,
//                 feel free to call or WhatsApp us at 014-603 7500. Thank you! - SifuTutor Management Team [This is an automated message, please do not reply directly.]";

//                 SendWhatsAppMessageJob::dispatch($phone_number, $message);
//             }

//         foreach ($tutorListings as $rowTutorListings) {
//                 $phone_number = $rowTutorListings->whatsapp;
//                 $message = 'Dear Tutor: *' . $rowTutorListings->full_name . '*, A Class Ticket has been generated. Class Ticket # *' . $jobTicketDeails->uid . '*';

//                 SendWhatsAppMessageJob::dispatch($phone_number, $message);
//                 SendSmsMessageJob::dispatch($phone_number, $message);

//                 DB::table('text_messages')->insert([
//                     'recipient' => $phone_number,
//                     'message' => $message,
//                     'status' => 'sent',
//                 ]);
//             }

//         $tutorDevices = DB::table('tutor_device_tokens')->distinct()->get(['device_token', 'tutor_id']);
//             foreach ($tutorDevices as $rowDeviceToken) {
//                 $push_notification_api = new PushNotificationLibrary();
//                 $deviceToken = $rowDeviceToken->device_token;
//                 $title = 'Job-Ticket Create Successfully';
//                 $message = 'Message JOB Ticket ';
//                 $notificationdata = array(
//                     'sender' => 'jobTicket',
//                     'id' => $jobTicketDeails->uid,
//                 );
//                 // $push_notification_api->sendPushNotification($deviceToken, $title, $message, $notificationdata);
//                 SendPushNotificationJob::dispatch($deviceToken, $title, $message,$notificationdata);
//             }

//         //new ticket creation event
//         $data = ["New Ticket Created"];
//         event(new TicketCreated($data));


//         return response()->json(["ResponseCode"=>"100" , "message" => "Ticket has been added successfully"]);
//         // return redirect('TicketList')->with('success', 'ticket has been added successfully!');

//     }

    public function submitTicket(Request $request)
    {
        // Define required fields
        $requiredFields = [
            'token' => 'Parent Token',
            'subject' => 'Subject Name',
            'quantity' => 'Duration',
            'classFrequency' => 'Class Frequency',
            'tutorPereference' => 'Tutor Pereference',
            'day' => 'Day',
            'time' => 'Time',
            'subscription' => 'Subscription',
            'registration_date' => 'Registration Date',
            'classType' => 'Class Type',
            'job_ticket_requirement' => 'Job Ticket Requirement'
        ];
        
        foreach ($requiredFields as $field => $displayName) {
            if (is_null($request->$field)) {
                return response()->json([
                    'ResponseCode' => '102',
                    'error' => "It looks like you missed the ($displayName) field. Please fill it in."
                ]);
            }


            if (empty($request->$field)) {
                return response()->json([
                    'ResponseCode' => '102',
                    'error' => "It looks like you missed the ($field) field. Please fill it in.",
                ]);
            }
        }


        if (!$request->has('students')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the students field. Please fill it in.',
            ]);
        }

        $students = $request->input('students');
        if (!is_array($students) || empty($students)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the students Array',
            ]);
        }


        foreach ($students as $index => $student) {
            $requiredStudentFields = ['studentFullName', 'studentGender', 'age', 'studentDateOfBirth', 'id'];
            foreach ($requiredStudentFields as $field) {
                if (!isset($student[$field]) || empty($student[$field])) {
                    return response()->json([
                        'ResponseCode' => '102',
                        'error' => "It looks like you missed the or Empty students[$index][$field] field. Please fill it in.",
                    ]);
                }
            }
        }


        $parent = DB::table('customers')->where('token', $request->token)->first();

        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        $data = $request->all();
        $studentLastID = $request->students[0]["id"];


        $latestTicketID = DB::table('job_tickets')->latest('created_at')->value('id');
        $ticketIDs = $latestTicketID ? $latestTicketID + 1 : 1;

        $dayArray = implode(",", $data['day']);


        $subjectFee = DB::table('products')
            ->join("categories", "products.category", "=", "categories.id")
            ->select("products.*", "categories.price as category_price", "categories.mode as mode")
            ->where('products.id', '=', $data['subject'])
            ->first();

        $uuidForTicket = rand(100, 99999);

        // Step 5: Insert the job ticket details
        $ticketValues = [
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
            'day' => json_encode($dayArray),
            'time' => $data['time'],
            'subscription' => $data['subscription'],
            'specialRequest' => $request->job_ticket_requirement,
            'classAddress' => $request->classAddress,
            'classLatitude' => $request->classLatitude,
            'classLongitude' => $request->classLongitude,
            'classCity' => isset($request->classCity) ? $request->classCity : (is_numeric($request->customerCity) ? $request->classCity : $request->customerCity),
            'classState' => isset($request->classState) ? $request->classState : (is_numeric($request->customerState) ? $request->classState : $request->customerState),
            'classPostalCode' => $request->classPostalCode,
            // 'classAddress' => $request->classAddress,
            // 'classLatitude' => $request->classLatitude,
            // 'classLongitude' => $request->classLongitude,
            // 'classCity' => $request->classCity,
            // 'classState' => $request->classState,
            // 'classPostalCode' => $request->classPostalCode,
            'register_date' => date("Y-m-d"),
            'mode' => $request->classType,
            'estimate_commission' => $request->estimate_commission,
            'job_ticket_requirement' => $request->job_ticket_requirement,
            'status' => 'pending',
        ];

        $jobTicketLastID = DB::table('job_tickets')->insertGetId($ticketValues);

        $student_data = array(
             'student_id' => $studentLastID,
             'ticket_id' => $jobTicketLastID,
             'ticket_id2' => $ticketIDs,
             'subject' => $data['subject'],
             'quantity' => $data['quantity'],
             'classFrequency' => $data['classFrequency'],
             'remaining_classes' => $data['classFrequency'],
             'day' => json_encode($dayArray),
             'time' => $data['time'],
             'subscription' => $data['subscription'],
             'specialRequest' => $request->job_ticket_requirement,
        );
        DB::table('student_subjects')->insertGetId($student_data);

        // Step 6: Insert student data
        $this->insertStudentData($data['students'], $jobTicketLastID, $subjectFee);

        // Step 7: Additional calculations for extra charges and commissions
        $extraCharges = $this->calculateAndInsertExtraCharges($data['students'], $jobTicketLastID, $subjectFee, $data);
        
        // return response()->json($extraCharges);
   
        // Step 8: Generate invoice
        $invoice_detail = $this->generateInvoice($jobTicketLastID, $studentLastID, $subjectFee, $parent, $data, $dayArray);

        // dd($invoice_detail);

        // Step 9: Send WhatsApp Notifications
        $this->sendWhatsAppNotifications($parent, $jobTicketLastID, $invoice_detail);

        // Step 10: send SMS Notifications
        $this->sendSMSNotifications($jobTicketLastID);

        // Step 11: Send Push Notifications
        $this->sendPushNotifications($jobTicketLastID);

        try{

            $data = [
                "ResponseCode" => "100",
                "message" => "Job ticket Created successfully."
            ];

            event(new TicketCreated($data));

            event(new JobTicket($data, $parent->token));

        }catch(Exception $e)
        {
           return response()->json(["ResponseCode"=> "103",
        		"error"=> "Unable to created Job Ticket"]);
        }

        return response()->json(['ResponseCode' => '100', 'message' => 'Ticket submitted successfully']);
    }

    private function insertStudentData($students, $jobTicketLastID, $subjectFee)
    {
        foreach ($students as $index => $student) {
            if ($index == 0) continue;

            if (!empty($student['studentFullName'])) {
                $extraStudentCharges = $this->getExtraStudentCharges($subjectFee->mode);
                $uuidForStudent = rand(100, 99999);
                $multipleStudent = [
                    'student_id' => $student['id'],
                    'student_name' => $student['studentFullName'],
                    'student_gender' => $student['studentGender'],
                    'student_age' => $student['age'],
                    'year_of_birth' => $student['studentDateOfBirth'],
                    'special_need' => $student['specialNeed'],
                    'job_ticket_id' => $jobTicketLastID,
                    'subject_id' => $subjectFee->id,
                    'extra_fee' => $extraStudentCharges,
                    'extra_fee_date' => now(),
                ];
                DB::table('job_ticket_students')->insert($multipleStudent);
            }
        }
    }

    private function getExtraStudentCharges($mode)
    {
        $extraStudentFeeCharges = DB::table('extra_student_charges')->orderBy('id', 'DESC')->first();
        return $mode == 'physical' ? $extraStudentFeeCharges->physical_additional_charges : $extraStudentFeeCharges->online_additional_charges;
    }

    private function calculateAndInsertExtraCharges($students, $jobTicketLastID, $subjectFee, $data)
    {
        $count = DB::table('job_ticket_students')
            ->where('job_ticket_id', $jobTicketLastID)
            ->count();


        $classMode = $subjectFee->mode;
        $categoryName = DB::table('categories')->where('id', $subjectFee->category)->value('category_name');
        
        $extraCharges = $this->calculateExtraCharges($classMode, $count, $data);

        $commissionData = $this->calculateCommission($students, $classMode, $subjectFee, $categoryName, $data, $count);

        DB::table('job_tickets')
            ->where('id', $jobTicketLastID)
            ->update(array_merge($extraCharges, $commissionData));
    }

    private function calculateExtraCharges($classMode, $count, $data)
    {
        $extraStudentFeeCharges = DB::table('extra_student_charges')->orderBy('id', 'DESC')->first();

        // Calculate the additional student charges for the number of extra students
        $additionalStudentChargesTutor = $count; // Only charge for additional students beyond the first one
        $additionalStudentChargesJobTicket = $additionalStudentChargesTutor * floatval($data['classFrequency']) * floatval($data['quantity']);

        // dd($data['classFrequency']."/////".$data['quantity']);

        $extraCharges = ($classMode == "physical")
            ? $additionalStudentChargesJobTicket * $extraStudentFeeCharges->physical_additional_charges
            : $additionalStudentChargesJobTicket * $extraStudentFeeCharges->online_additional_charges;


            // dd($additionalStudentChargesJobTicket."----".$extraStudentFeeCharges->online_additional_charges);

        return [
            'extra_student_total' => $extraCharges,
            'extra_student_tutor_commission' => $additionalStudentChargesTutor * $extraStudentFeeCharges->{"tutor_{$classMode}"},
            'extra_estimate_commission_display_tutor' => $extraCharges,
        ];
    }

    private function calculateCommission($students, $classMode, $subjectFee, $categoryName, $data, $count)
    {
        
        $subscription = $data["subscription"];
        $longTermHours = 8;
        $extraStudentFeeCharges = DB::table('extra_student_charges')->orderBy('id', 'DESC')->first();

        // $hourlyRateFirst = DB::table("categories")->where(["mode" => $classMode, "category_name" => $categoryName])->value('tutor_longterm_commission_before_eight_hours');
        // $hourlyRateAfter = DB::table("categories")->where(["mode" => $classMode, "category_name" => $categoryName])->value('tutor_longterm_commission_after_eight_hours');
        
        
        if ($subscription == "Long-Term") {
            $hourlyRateFirst = DB::table("categories")->where(["mode" => $classMode, "category_name" => $categoryName])->value('tutor_longterm_commission_before_eight_hours');
            $hourlyRateAfter = DB::table("categories")->where(["mode" => $classMode, "category_name" => $categoryName])->value('tutor_longterm_commission_after_eight_hours');
        } elseif ($subscription == "Short-Term") {
            // dd("Asd");
            $hourlyRateFirst = DB::table("categories")->where(["mode" => $classMode, "category_name" => $categoryName])->value('tutor_shorterm_commission'); // Short-term rate
            $hourlyRateAfter = DB::table("categories")->where(["mode" => $classMode, "category_name" => $categoryName])->value('tutor_shorterm_commission'); 
        }

        $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
        $extraChargesTutorCommission = $extraStudentFeeCharges->{"tutor_{$classMode}"};

        $estimate_commission = 0;
        $estimate_commission_display_tutor = 0;
        $per_class_commission_before_eight_hours = 0;
        $per_class_commission_after_eight_hours = 0;

        // Determine the extra student charges based on the subject mode
        $extraStudentCharges = 0; // Initialize the extra student charge
        $extraStudentChargesData = DB::table("extra_student_charges")->first();

        if ($subjectFee->mode == "physical") {
            $extraStudentCharges = $extraStudentChargesData->physical_additional_charges; // Get the physical mode charges
            $extraChargesTutorComission = $extraStudentFeeCharges->tutor_physical;
        } else {
            $extraStudentCharges = $extraStudentChargesData->online_additional_charges; // Get the online mode charges
             $extraChargesTutorComission = $extraStudentFeeCharges->tutor_online;
        }
        
        // dd($extraChargesTutorComission);

        // Calculate base price
        $basePrice = $subjectFee->category_price * $data['classFrequency'] * $data['quantity'];

        if ($numberOfSessions <= $longTermHours) {
            $estimate_commission = ($hourlyRateFirst) * $numberOfSessions;
            $estimate_commission_display_tutor = 0;
            $per_class_commission_before_eight_hours = $estimate_commission / $numberOfSessions;
        } else {
            $estimate_commission = ($hourlyRateFirst) * $longTermHours;
            $estimate_commission += ($hourlyRateAfter) * ($numberOfSessions - $longTermHours);
            $per_class_commission_before_eight_hours = ($hourlyRateFirst) * $longTermHours / $longTermHours;
            $per_class_commission_after_eight_hours = ($hourlyRateAfter) * ($numberOfSessions - $longTermHours) / ($numberOfSessions - $longTermHours);
            
            $estimate_commission_display_tutor = $per_class_commission_after_eight_hours * $data['classFrequency'] * $data['quantity'];
        }

        // Determine if there are extra students and calculate the extra charges
        $extraStudentChargesTotal = 0;
        $extraStudentChargesTutorTotal = 0;
        if (count($students) > 1) {
            $extraStudentChargesTotal = $extraStudentCharges * (count($students) - 1) * $data['classFrequency'] * $data['quantity'];
            $extraStudentChargesTutorTotal =  $extraChargesTutorComission * (count($students) - 1) * $data['classFrequency'] * $data['quantity'];
        }

        // dd($estimate_commission);
        $totalPrice = $basePrice + $extraStudentChargesTotal;

        // dd($extraStudentChargesTutorTotal);

        return [
            'estimate_commission' => $estimate_commission+$extraStudentChargesTutorTotal,
            'estimate_commission_display_tutor' => $estimate_commission_display_tutor+$extraStudentChargesTutorTotal,
            'totalPrice' => $totalPrice,
            'per_class_commission_before_eight_hours' => $per_class_commission_before_eight_hours,
            'per_class_commission_after_eight_hours' => $per_class_commission_after_eight_hours,
        ];
    }

    private function generateInvoice($jobTicketLastID, $studentLastID, $subjectFee, $parent, $data, $dayArray)
    {
        // Calculate the price based on the frequency and quantity
        $calcPrice = $subjectFee->category_price * $data['classFrequency'] * $data['quantity'];
        $extraStudents = count($data["students"])-1;
        $extraCharges = $this->calculateExtraCharges($subjectFee->mode, $extraStudents!=0?$extraStudents:0, $data); // Assuming extra charges already calculated

        // dd($extraCharges);
        // Prepare the invoice data
        $invoiceData = [
            'studentID' => $studentLastID,
            'ticketID' => $jobTicketLastID,
            'subjectID' => $data['subject'],
            'account_id' => $parent->id,
            'invoiceDate' => now()->toDateString(),
            'reference' => $jobTicketLastID,
            'payerName' => $parent->full_name,
            'payerEmail' => $parent->email,
            'payerPhone' => $parent->phone,
            'quantity' => $data['quantity'],
            'classFrequency' => $data['classFrequency'],
            'day' => json_encode($dayArray),
            'time' => $data['time'],
            'type' => 'd',
            'debit' => $calcPrice + $extraCharges['extra_student_total'],
            'credit' => 0,
            'invoice_status' => "First",
            'invoiceTotal' => $calcPrice+ $extraCharges['extra_student_total'],
            'sentEmail' => "true",
            'brand' => $subjectFee->brand,
        ];

        // Insert the invoice and get its ID
        $invoiceID = DB::table('invoices')->insertGetId($invoiceData);

        // Generate invoice items
        $this->generateInvoiceItems($invoiceData, $dayArray, $invoiceID, $data['classFrequency'], $data, $studentLastID, $jobTicketLastID);

        // Return detailed invoice information
        $invoiceDetail = DB::table('invoices')->where('id', $invoiceID)->first();
        $invoiceItems = DB::table('invoice_items')->where('invoiceID', $invoiceID)->get();

        return [
            'invoice_id' => $invoiceID,
            'invoice_detail' => $invoiceDetail,
            'invoice_items' => $invoiceItems
        ];
    }

    private function generateInvoiceItems($invoiceData, $dayArray, $invoiceID, $classFrequency, $data, $studentLastID, $jobTicketLastID)
    {
        // Ensure $dayArray is an array
        if (is_string($dayArray)) {
            $dayArray = explode(',', $dayArray); // Convert string to array
        }

        $initialDate = new DateTime($invoiceData['invoiceDate']);
        $perClassPriceInvoiceItem = $invoiceData['debit'] / $classFrequency;

        for ($j = 0; $j < $classFrequency; $j++) {
            $currentDay = $dayArray[$j % count($dayArray)];
            $currentDate = (clone $initialDate)->modify("+{$j} days")->format('Y-m-d');

            // Prepare the data for each invoice item
            $invoiceItemsData = [
                'quantity' => $data['quantity'],
                'time' => $data['time'],
                'day' => $currentDay,
                'isPaid' => 'unPaid',
                'studentID' => $studentLastID,
                'ticketID' => $jobTicketLastID,
                'subjectID' => $data['subject'],
                'invoiceID' => $invoiceID,
                'invoiceDate' => $currentDate,
                'price' => $perClassPriceInvoiceItem,
                'description' => "Class on {$currentDay}"
            ];

            // Insert into the invoice_items table
            DB::table('invoice_items')->insert($invoiceItemsData);
        }
    }

    private function sendWhatsAppNotifications($parent, $jobTicketLastID, $invoice_detail)
    {
        $tutorListings = DB::table('tutors')->where('status', '=', 'verified')->get();

        // Send WhatsApp notifications to parent
        if (isset($parent) && $parent->whatsapp != null) {
            $month_year = Carbon::now()->format('F Y'); // Using Carbon for date formatting
            $invoice_link = url("/invoicePublicLink") . $invoice_detail['invoice_detail']->id;
            $phone_number = $parent->whatsapp;
            $message = "Dear Parent/Student, Your SifuTutor invoice for $month_year is ready! You can easily view and pay your bill online at $invoice_link.
            The total amount due is " . $invoice_detail['invoice_detail']->invoiceTotal . ".
            If you prefer, you can also make a payment to our Maybank account: Sifu Edu & Learning Sdn Bhd Account No: 5621 1551 6678.
            Please send your payment confirmation to us via WhatsApp at www.wasap.my/60146037500. If you have any enquiry,
            feel free to call or WhatsApp us at 014-603 7500. Thank you! - SifuTutor Management Team [This is an automated message, please do not reply directly.]";

            SendWhatsAppMessageJob::dispatch($phone_number, $message);
        }

        // Send WhatsApp messages to tutor listings
        foreach ($tutorListings as $rowTutorListings) {
            $phone_number = $rowTutorListings->whatsapp;
            $message = 'Dear Tutor: *' . $rowTutorListings->full_name . '*, A Class Ticket has been generated. Class Ticket # *' . $jobTicketLastID . '*';

            SendWhatsAppMessageJob::dispatch($phone_number, $message);
        }
    }

    private function sendSMSNotifications($jobTicketLastID)
    {
        $tutorListings = DB::table('tutors')->where('status', '=', 'verified')->get();

        // Send SMS messages to tutor listings
        foreach ($tutorListings as $rowTutorListings) {
            $phone_number = $rowTutorListings->whatsapp;
            $message = 'Dear Tutor: *' . $rowTutorListings->full_name . '*, A Class Ticket has been generated. Class Ticket # *' . $jobTicketLastID . '*';

            SendSmsMessageJob::dispatch($phone_number, $message);

            DB::table('text_messages')->insert([
                'recipient' => $phone_number,
                'message' => $message,
                'status' => 'sent',
            ]);
        }
    }

    private function sendPushNotifications($jobTicketLastID)
    {   

        $ticketDetail = DB::table('job_tickets')->where('id', '=', $jobTicketLastID)->first();
        // Send push notifications to tutor devices
        $tutorDevices = DB::table('tutor_device_tokens')->distinct()->get(['device_token', 'tutor_id']);
        foreach ($tutorDevices as $rowDeviceToken) {
            $push_notification_api = new PushNotificationLibrary();
            $deviceToken = $rowDeviceToken->device_token;
            $title = 'Nearby Job Tickets';
            $message = 'Fresh opportunity! Apply for a nearby job now.';
        
            $notificationdata = [
                'id' => $ticketDetail->uid,
                'Sender' => 'jobTicket'
            ];
        
            // Dispatch push notification job
            SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
        
            // Store notification in the database
            DB::table('notifications')->insert([
                'page' => 'jobTicket',
                'token' => $deviceToken,
                'title' => $title,
                'message' => $message,
                'type' => 'tutor',
                'status' => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        $student = DB::table('students')->where('id', '=', $ticketDetail->student_id)->first();
        // Send push notification to parent devices
        $parent_device_tokens = DB::table('parent_device_tokens')->where('parent_id', '=', $student->customer_id)->distinct()->get(['device_token', 'parent_id']);
        foreach ($parent_device_tokens as $token) {
            $deviceToken = $token->device_token;
            $title = 'New Job Ticket Created';
            $message = 'Job Ticket Create Successfully';
        
            $notificationdata = [
                'id' => $ticketDetail->uid,
                'Sender' => 'jobTicket'
            ];
        
            // Dispatch push notification job
            SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
        
            // Store notification in the database
            DB::table('notifications')->insert([
                'page' => 'jobTicket',
                'token' => $deviceToken,
                'title' => $title,
                'message' => $message,
                'type' => 'parent',
                'status' => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    // public function getJobTicketEstimation(Request $request)
    // {
    //     // Define the required fields
    //     $requiredFields = [
    //         'token', 'subject', 'quantity', 'classFrequency', 'tutorPereference', 'day',
    //         'time', 'subscription', 'registration_date', 'classType', 'job_ticket_requirement'
    //     ];

    //     // Check for missing or empty fields
    //     foreach ($requiredFields as $field) {
    //         if (!$request->has($field)) {
    //             return response()->json([
    //                 'ResponseCode' => '102',
    //                 'error' => "It looks like you missed the ($field) field. Please fill it in.",
    //             ]);
    //         }

    //         if (empty($request->$field)) {
    //             return response()->json([
    //                 'ResponseCode' => '102',
    //                 'error' => "It looks like you missed the ($field) field. Please fill it in.",
    //             ]);
    //         }
    //     }

    //     // Validate the token
    //     $token = $request->token;
    //     $parent = DB::table('customers')->where('token', $token)->first();

    //     if (!$parent) {
    //         return response()->json([
    //             'ResponseCode' => '101',
    //             'error' => 'No Parent found!',
    //         ]);
    //     }

    //     $data = $request->all();

    //     $studentLastID = $request->students[0]["id"];

    //     $subject = $data['subject'];

    //     $dayArray = array();

    //     foreach ($data['day'] as $selectedDay) {
    //         $dayArray[] = $selectedDay;
    //     }

    //     $extraStudentFeeCharges = DB::table('extra_student_charges')->orderBy('id', 'DESC')->first();

    //     $subjectFee = DB::table('products')->join("categories", "products.category", "=", "categories.id")
    //         ->select("products.*", "categories.price as category_price", "categories.mode as mode")
    //         ->where('products.id', '=', $data['subject'])->first();


    //     if (isset($data["students"])) {
    //     $i = 0; // Index counter
    //     foreach ($data['students'] as $student) {
    //         $i++;
    //         if ($i == 1) {
    //         continue; // Skip the first iteration
    //             }
    //         }
    //     }

    //     $studentDetail = DB::table('students')->where('id', '=', $studentLastID)->first();
    //     $customerDetail = DB::table('customers')->where('id', '=', $studentDetail->customer_id)->first();
    //     $subjectDetail = DB::table('products')->join("categories", "products.category", "=", "categories.id")
    //         ->select("products.*", "categories.price as category_price", "categories.mode as class_mode", "categories.category_name as category_name")->
    //         where('products.id', '=', $data['subject'])->first();

    //     $count = count($request->students);
    //     $count = $count-1;

    //     if ($subjectDetail->class_mode == "physical") {
    //         $extraStudentCharges = DB::table("extra_student_charges")->first();
    //         $extraStudentCharges = $extraStudentFeeCharges->physical_additional_charges;

    //     } else {
    //         $extraStudentCharges = DB::table("extra_student_charges")->first();
    //         $extraStudentCharges = $extraStudentFeeCharges->online_additional_charges;

    //     }

    //     $extraStudentFeeCharges = DB::table('extra_student_charges')->orderBy('id', 'DESC')->first();

    //     $price = $subjectDetail->category_price;

    //     $classFrequency = floatval($data['classFrequency']);

    //     $quantity = floatval($data['quantity']);

    //     if ($subjectDetail->class_mode == "physical") {
    //         $extraCharges = $count * $extraStudentFeeCharges->physical_additional_charges;
    //         $extraChargesTutorComission = $count * $extraStudentFeeCharges->tutor_physical;

    //     } else {
    //         $extraCharges = $count * $extraStudentFeeCharges->online_additional_charges;
    //         $extraChargesTutorComission = $count * $extraStudentFeeCharges->tutor_online;

    //     }



    //     $class_term = $request->subscription;
    //     $modeOfClass = $request->class_mode;
    //     $category_id_subject = $request->category;
    //     $category_level_subject = $request->category_name;
    //     $estimate_commission = 0;
    //     $estimate_after_eight_hours = 0;



    //     //Long Term Classes prices according to hours
    //     $long_term_online_first_eight_hours = [
    //         'Pre-school' => 9,
    //         'UPSR' => 9,
    //         'PT3' => 9,
    //         'SPM' => 10.5,
    //         'IGCSE' => 10.5,
    //         'STPM' => 12,
    //         'A-level/Pre-U' => 12,
    //         'Diploma' => 13.5,
    //         'Degree' => 15,
    //         'ACCA' => 18,
    //         'Master' => 18,
    //     ];

    //     $long_term_online_after_eight_hours = [
    //         'Pre-school' => 21,
    //         'UPSR' => 21,
    //         'PT3' => 21,
    //         'SPM' => 24.5,
    //         'IGCSE' => 24.5,
    //         'STPM' => 28,
    //         'A-level/Pre-U' => 28,
    //         'Diploma' => 31.5,
    //         'Degree' => 35,
    //         'ACCA' => 42,
    //         'Master' => 42,
    //     ];

    //     $long_term_physical_first_eight_hours = [
    //         'Pre-school' => 15,
    //         'UPSR' => 15,
    //         'PT3' => 15,
    //         'SPM' => 18,
    //         'IGCSE' => 21,
    //         'STPM' => 24,
    //         'A-level/Pre-U' => 24,
    //         'Diploma' => 27,
    //         'Degree' => 30,
    //         'ACCA' => 36,
    //         'Master' => 36,
    //     ];

    //     $long_term_physical_after_eight_hours = [
    //         'Pre-school' => 35,
    //         'UPSR' => 35,
    //         'PT3' => 35,
    //         'SPM' => 40,
    //         'IGCSE' => 49,
    //         'STPM' => 56,
    //         'A-level/Pre-U' => 56,
    //         'Diploma' => 63,
    //         'Degree' => 70,
    //         'ACCA' => 84,
    //         'Master' => 84,
    //     ];

    //     //Short Term Classes prices according to hours
    //     $short_term_online = [
    //         'Pre-school' => 18,
    //         'UPSR' => 18,
    //         'PT3' => 18,
    //         'SPM' => 21,
    //         'IGCSE' => 21,
    //         'STPM' => 24,
    //         'A-level/Pre-U' => 24,
    //         'Diploma' => 27,
    //         'Degree' => 30,
    //         'ACCA' => 36,
    //         'Master' => 36,
    //     ];

    //     $short_term_physical = [
    //         'Pre-school' => 30,
    //         'UPSR' => 30,
    //         'PT3' => 30,
    //         'SPM' => 36,
    //         'IGCSE' => 42,
    //         'STPM' => 48,
    //         'A-level/Pre-U' => 48,
    //         'Diploma' => 54,
    //         'Degree' => 60,
    //         'ACCA' => 72,
    //         'Master' => 72,
    //     ];

    //     $per_class_commission_before_eight_hours = 0;
    //     $per_class_commission_after_eight_hours = 0;

    //     if ($class_term == "Long-Term") {
    //         if ($modeOfClass == "online") {
    //             switch ($category_level_subject) {

    //                 case "Pre-school":
    //                     $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
    //                     $per_hour_charges = $long_term_online_first_eight_hours["Pre-school"];
    //                     $per_hour_charges_addition = $long_term_online_after_eight_hours["Pre-school"];

    //                     $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     if ($numberOfSessions <= 8) {
    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
    //                         $per_class_commission_after_eight_hours = 0;

    //                     } else {

    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
    //                         $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
    //                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

    //                     }


    //                     break;

    //                 case "UPSR":
    //                     $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);


    //                     $per_hour_charges = $long_term_online_first_eight_hours["UPSR"];
    //                     $per_hour_charges_addition = $long_term_online_after_eight_hours["UPSR"];
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     if ($numberOfSessions <= 8) {
    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
    //                         $per_class_commission_after_eight_hours = 0;

    //                     } else {

    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
    //                         $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
    //                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

    //                     }


    //                     break;

    //                 case "PT3":
    //                     $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
    //                     $per_hour_charges = $long_term_online_first_eight_hours["PT3"];
    //                     $per_hour_charges_addition = $long_term_online_after_eight_hours["PT3"];
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     if ($numberOfSessions <= 8) {
    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
    //                         $per_class_commission_after_eight_hours = 0;

    //                     } else {

    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
    //                         $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
    //                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

    //                     }


    //                     break;

    //                 case "SPM":
    //                     $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);

    //                     $per_hour_charges = $long_term_online_first_eight_hours["SPM"];
    //                     $per_hour_charges_addition = $long_term_online_after_eight_hours["SPM"];

    //                     $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     if ($numberOfSessions <= 8) {
    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
    //                         $per_class_commission_after_eight_hours = 0;

    //                     } else {

    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
    //                         $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
    //                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

    //                     }

    //                     break;

    //                 case "IGCSE":
    //                     $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
    //                     $per_hour_charges = $long_term_online_first_eight_hours["IGCSE"];
    //                     $per_hour_charges_addition = $long_term_online_after_eight_hours["IGCSE"];
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     if ($numberOfSessions <= 8) {
    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
    //                         $per_class_commission_after_eight_hours = 0;

    //                     } else {

    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
    //                         $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
    //                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

    //                     }

    //                     break;

    //                 case "STPM":
    //                     $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
    //                     $per_hour_charges = $long_term_online_first_eight_hours["STPM"];
    //                     $per_hour_charges_addition = $long_term_online_after_eight_hours["STPM"];
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     if ($numberOfSessions <= 8) {
    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
    //                         $per_class_commission_after_eight_hours = 0;

    //                     } else {

    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
    //                         $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
    //                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

    //                     }

    //                     break;

    //                 case "A-level/Pre-U":
    //                     $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
    //                     $per_hour_charges = $long_term_online_first_eight_hours["A-level/Pre-U"];
    //                     $per_hour_charges_addition = $long_term_online_after_eight_hours["A-level/Pre-U"];
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
    //                     if ($numberOfSessions <= 8) {
    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
    //                         $per_class_commission_after_eight_hours = 0;

    //                     } else {

    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
    //                         $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
    //                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

    //                     }

    //                     break;

    //                 case "Diploma":
    //                     $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
    //                     $per_hour_charges = $long_term_online_first_eight_hours["Diploma"];
    //                     $per_hour_charges_addition = $long_term_online_after_eight_hours["Diploma"];
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     if ($numberOfSessions <= 8) {
    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
    //                         $per_class_commission_after_eight_hours = 0;

    //                     } else {

    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
    //                         $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
    //                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

    //                     }

    //                     break;

    //                 case "Degree":
    //                     $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
    //                     $per_hour_charges = $long_term_online_first_eight_hours["Degree"];
    //                     $per_hour_charges_addition = $long_term_online_after_eight_hours["Degree"];
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     if ($numberOfSessions <= 8) {
    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
    //                         $per_class_commission_after_eight_hours = 0;

    //                     } else {

    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
    //                         $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
    //                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

    //                     }

    //                     break;

    //                 case "ACCA":
    //                     $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
    //                     $per_hour_charges = $long_term_online_first_eight_hours["ACCA"];
    //                     $per_hour_charges_addition = $long_term_online_after_eight_hours["ACCA"];
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     if ($numberOfSessions <= 8) {
    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
    //                         $per_class_commission_after_eight_hours = 0;

    //                     } else {

    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
    //                         $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
    //                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

    //                     }

    //                     break;

    //                 case "Master":
    //                     $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
    //                     $per_hour_charges = $long_term_online_first_eight_hours["Master"];
    //                     $per_hour_charges_addition = $long_term_online_after_eight_hours["Master"];
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));


    //                     if ($numberOfSessions <= 8) {
    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
    //                         $per_class_commission_after_eight_hours = 0;

    //                     } else {

    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
    //                         $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
    //                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

    //                     }


    //                     break;

    //             }
    //         } elseif ($modeOfClass == "physical") {
    //             switch ($category_level_subject) {

    //                 case "Pre-school":
    //                     $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
    //                     $per_hour_charges = $long_term_physical_first_eight_hours["Pre-school"];
    //                     $per_hour_charges_addition = $long_term_physical_after_eight_hours["Pre-school"];
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     if ($numberOfSessions <= 8) {
    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
    //                         $per_class_commission_after_eight_hours = 0;

    //                     } else {

    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
    //                         $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
    //                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

    //                     }


    //                     break;

    //                 case "UPSR":
    //                     $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
    //                     $per_hour_charges = $long_term_physical_first_eight_hours["UPSR"];
    //                     $per_hour_charges_addition = $long_term_physical_after_eight_hours["UPSR"];
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     if ($numberOfSessions <= 8) {
    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
    //                         $per_class_commission_after_eight_hours = 0;

    //                     } else {

    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
    //                         $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
    //                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

    //                     }


    //                     break;

    //                 case "PT3":
    //                     $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
    //                     $per_hour_charges = $long_term_physical_first_eight_hours["PT3"];
    //                     $per_hour_charges_addition = $long_term_physical_after_eight_hours["PT3"];
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     if ($numberOfSessions <= 8) {
    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
    //                         $per_class_commission_after_eight_hours = 0;

    //                     } else {

    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
    //                         $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
    //                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

    //                     }


    //                     break;

    //                 case "SPM":
    //                     $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
    //                     $per_hour_charges = $long_term_physical_first_eight_hours["SPM"];
    //                     $per_hour_charges_addition = $long_term_physical_after_eight_hours["SPM"];
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     if ($numberOfSessions <= 8) {
    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
    //                         $per_class_commission_after_eight_hours = 0;

    //                     } else {

    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
    //                         $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
    //                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

    //                     }


    //                     break;

    //                 case "IGCSE":
    //                     $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
    //                     $per_hour_charges = $long_term_physical_first_eight_hours["IGCSE"];
    //                     $per_hour_charges_addition = $long_term_physical_after_eight_hours["IGCSE"];
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     if ($numberOfSessions <= 8) {
    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
    //                         $per_class_commission_after_eight_hours = 0;

    //                     } else {

    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
    //                         $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
    //                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

    //                     }


    //                     break;

    //                 case "STPM":
    //                     $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
    //                     $per_hour_charges = $long_term_physical_first_eight_hours["STPM"];
    //                     $per_hour_charges_addition = $long_term_physical_after_eight_hours["STPM"];
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     if ($numberOfSessions <= 8) {
    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
    //                         $per_class_commission_after_eight_hours = 0;

    //                     } else {

    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
    //                         $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
    //                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

    //                     }


    //                     break;

    //                 case "A-level/Pre-U":
    //                     $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
    //                     $per_hour_charges = $long_term_physical_first_eight_hours["A-level/Pre-U"];
    //                     $per_hour_charges_addition = $long_term_physical_after_eight_hours["A-level/Pre-U"];
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
    //                     if ($numberOfSessions <= 8) {
    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
    //                         $per_class_commission_after_eight_hours = 0;

    //                     } else {

    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
    //                         $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
    //                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

    //                     }

    //                     break;

    //                 case "Diploma":
    //                     $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
    //                     $per_hour_charges = $long_term_physical_first_eight_hours["Diploma"];
    //                     $per_hour_charges_addition = $long_term_physical_after_eight_hours["Diploma"];
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     if ($numberOfSessions <= 8) {
    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
    //                         $per_class_commission_after_eight_hours = 0;

    //                     } else {

    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
    //                         $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
    //                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

    //                     }

    //                     break;

    //                 case "Degree":
    //                     $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
    //                     $per_hour_charges = $long_term_physical_first_eight_hours["Degree"];
    //                     $per_hour_charges_addition = $long_term_physical_after_eight_hours["Degree"];
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     if ($numberOfSessions <= 8) {
    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
    //                         $per_class_commission_after_eight_hours = 0;

    //                     } else {

    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
    //                         $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
    //                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

    //                     }


    //                     break;

    //                 case "ACCA":
    //                     $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
    //                     $per_hour_charges = $long_term_physical_first_eight_hours["ACCA"];
    //                     $per_hour_charges_addition = $long_term_physical_after_eight_hours["ACCA"];
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     if ($numberOfSessions <= 8) {
    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
    //                         $per_class_commission_after_eight_hours = 0;

    //                     } else {

    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
    //                         $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
    //                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

    //                     }


    //                     break;

    //                 case "Master":
    //                     $numberOfSessions = $data['classFrequency'] * floatval($data['quantity']);
    //                     $per_hour_charges = $long_term_physical_first_eight_hours["Master"];
    //                     $per_hour_charges_addition = $long_term_physical_after_eight_hours["Master"];
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     if ($numberOfSessions <= 8) {
    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * $numberOfSessions) / $numberOfSessions;
    //                         $per_class_commission_after_eight_hours = 0;

    //                     } else {

    //                         $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8);
    //                         $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * 8) / 8;
    //                         $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8));
    //                         $per_class_commission_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * ($numberOfSessions - 8)) / ($numberOfSessions - 8);

    //                     }


    //                     break;

    //             }
    //         }
    //     }
    //     else {
    //         if ($modeOfClass == "online") {
    //             switch ($category_level_subject) {

    //                 case "Pre-school":

    //                     $per_hour_charges = $short_term_online["Pre-school"];

    //                     $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
    //                     $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

    //                     break;


    //                 case "UPSR":
    //                     $per_hour_charges = $short_term_online["UPSR"];

    //                     $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
    //                     $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

    //                     break;

    //                 case "PT3":
    //                     $per_hour_charges = $short_term_online["PT3"];

    //                     $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
    //                     $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

    //                     break;


    //                 case "SPM":

    //                     $per_hour_charges = $short_term_online["SPM"];

    //                     $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
    //                     $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

    //                     break;


    //                 case "IGCSE":
    //                     $per_hour_charges = $short_term_online["IGCSE"];

    //                     $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
    //                     $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

    //                     break;

    //                 case "STPM":
    //                     $per_hour_charges = $short_term_online["STPM"];

    //                     $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
    //                     $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

    //                     break;

    //                 case "A-level/Pre-U":
    //                     $per_hour_charges = $short_term_online["A-level/Pre-U"];

    //                     $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
    //                     $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

    //                     break;

    //                 case "Diploma":
    //                     $per_hour_charges = $short_term_online["Diploma"];

    //                     $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
    //                     $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

    //                     break;

    //                 case "Degree":
    //                     $per_hour_charges = $short_term_online["Degree"];

    //                     $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
    //                     $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

    //                     break;

    //                 case "ACCA":
    //                     $per_hour_charges = $short_term_online["ACCA"];

    //                     $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
    //                     $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

    //                     break;

    //                 case "Master":
    //                     $per_hour_charges = $short_term_online["Master"];

    //                     $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
    //                     $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

    //                     break;

    //             }
    //         } elseif ($modeOfClass == "physical") {
    //             switch ($category_level_subject) {

    //                 case "Pre-school":
    //                     $per_hour_charges = $short_term_physical["Pre-school"];

    //                     $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
    //                     $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

    //                     break;

    //                 case "UPSR":
    //                     $per_hour_charges = $short_term_physical["UPSR"];

    //                     $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
    //                     $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

    //                     break;

    //                 case "PT3":
    //                     $per_hour_charges = $short_term_physical["PT3"];

    //                     $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
    //                     $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

    //                     break;

    //                 case "SPM":
    //                     $per_hour_charges = $short_term_physical["SPM"];

    //                     $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
    //                     $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

    //                     break;

    //                 case "IGCSE":
    //                     $per_hour_charges = $short_term_physical["IGCSE"];

    //                     $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
    //                     $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

    //                     break;

    //                 case "STPM":
    //                     $per_hour_charges = $short_term_physical["STPM"];

    //                     $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
    //                     $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

    //                     break;

    //                 case "A-level/Pre-U":
    //                     $per_hour_charges = $short_term_physical["A-level/Pre-U"];

    //                     $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
    //                     $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

    //                     break;

    //                 case "Diploma":
    //                     $per_hour_charges = $short_term_physical["Diploma"];

    //                     $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
    //                     $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

    //                     break;

    //                 case "Degree":
    //                     $per_hour_charges = $short_term_physical["Degree"];

    //                     $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
    //                     $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

    //                     break;

    //                 case "ACCA":
    //                     $per_hour_charges = $short_term_physical["ACCA"];
    //                     $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
    //                     $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

    //                     break;

    //                 case "Master":
    //                     $per_hour_charges = $short_term_physical["Master"];
    //                     $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));
    //                     $estimate_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity']));

    //                     $per_class_commission_before_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];
    //                     $per_class_commission_after_eight_hours += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency']) * floatval($data['quantity'])) / $data['classFrequency'];

    //                     break;

    //             }
    //         }
    //     }


    //     if ((isset($data["students"]))) {

    //         $jobTicketCalc = $subjectFee->category_price + $extraCharges;
    //         $jobTicketCalc = $jobTicketCalc * $data['classFrequency'] * $data['quantity'];
    //     }
    //     else {

    //         $jobTicketCalc = $subjectFee->category_price;
    //         $jobTicketCalc = $jobTicketCalc * $data['classFrequency'] * $data['quantity'];
    //     }


    //     $additionalStudentChargesTutor = 0;
    //     $additionalStudentChargesJobTicket = 0;
    //     $additionalStudentCharges = 0;

    //     if ($subjectDetail->class_mode == "physical") {
    //         if ($count == 0) {
    //             $additionalStudentChargesTutor = 1 * $extraStudentFeeCharges->tutor_physical;
    //             $additionalStudentChargesJobTicket = 1 * $extraStudentFeeCharges->physical_additional_charges;
    //         } else {

    //             $additionalStudentChargesTutor = $count * $extraStudentFeeCharges->tutor_physical;
    //             $additionalStudentChargesJobTicket = $count * $extraStudentFeeCharges->physical_additional_charges;
    //         }

    //     } else {
    //         if ($count == 0) {

    //             $additionalStudentChargesTutor = 1 * $extraStudentFeeCharges->tutor_online;
    //             $additionalStudentChargesJobTicket = 1 * $extraStudentFeeCharges->online_additional_charges;
    //         } else {
    //             $additionalStudentChargesTutor = $count * $extraStudentFeeCharges->tutor_physical;
    //             $additionalStudentChargesJobTicket = $count * $extraStudentFeeCharges->online_additional_charges;
    //         }
    //     }


    //     if ($count == 0) {
    //         $count = 1;
    //         $additionalStudentCharges = $additionalStudentChargesTutor * 1 * (floatval($data['classFrequency']) * floatval($data['quantity']));
    //         $additionalStudentChargesJobTicket = $additionalStudentChargesJobTicket * 1 * (floatval($data['classFrequency']) * floatval($data['quantity']));
    //     } else {
    //         $additionalStudentCharges = $additionalStudentChargesTutor * 1 * (floatval($data['classFrequency']) * floatval($data['quantity']));
    //         $additionalStudentChargesJobTicket = $additionalStudentChargesJobTicket * 1 * (floatval($data['classFrequency']) * floatval($data['quantity']));

    //     }


    //     // DB::table('job_tickets')
    //     //     ->where('id', $jobTicketLastID)
    //     //     ->update([
    //     //         'extra_student_total' => $additionalStudentChargesJobTicket,
    //     //         'extra_student_tutor_commission' => $additionalStudentCharges,
    //     //         'extra_estimate_commission_display_tutor' => $additionalStudentCharges,
    //     //         'estimate_commission' => $estimate_commission,
    //     //         'estimate_commission_display_tutor' => $estimate_after_eight_hours,
    //     //         'totalPrice' => $jobTicketCalc,
    //     //         'per_class_commission_before_eight_hours' => $per_class_commission_before_eight_hours,
    //     //         'per_class_commission_after_eight_hours' => $per_class_commission_after_eight_hours
    //     //     ]);

    //     // return response()->json(["ResponseCode"=> "100","message"=>"Data Added successfully.","data"=>"RM".$jobTicketCalc]);

    //     return response()->json([
    //         "ResponseCode" => "100",
    //         "message" => "Fee Calculated Successfully.",
    //         "data" => [
    //             "Total Ticket Price" => "RM" . $jobTicketCalc,
    //             "Total Ticket Price" => "RM" . $jobTicketCalc,
    //             "Total Ticket Price" => "RM" . $jobTicketCalc,
    //             "Total Ticket Price" => "RM" . $jobTicketCalc,
    //             "Total Ticket Price" => "RM" . $jobTicketCalc,
    //             "Total Ticket Price" => "RM" . $jobTicketCalc,
    //             "Total Ticket Price" => "RM" . $jobTicketCalc
    //         ]
    //     ]);
    // }


    public function getTicketSummary(Request $request)
    {
        // Validate the required fields
        $requiredFields = [
            'token', 'subject', 'quantity', 'classFrequency', 'tutorPereference', 'day',
            'time', 'subscription', 'registration_date', 'classType', 'job_ticket_requirement'
        ];

        // // Check for missing or empty fields
        foreach ($requiredFields as $field) {
            if (!$request->has($field)) {
                return response()->json([
                    'ResponseCode' => '102',
                    'error' => "It looks like you missed the ($field) field. Please fill it in.",
                ]);
            }

            if (empty($request->$field)) {
                return response()->json([
                    'ResponseCode' => '102',
                    'error' => "It looks like you missed the ($field) field. Please fill it in.",
                ]);
            }
        }


        // Validate the 'students' array
        if (!$request->has('students')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the students field. Please fill it in.',
            ]);
        }

        $students = $request->input('students');
        if (!is_array($students) || empty($students)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the students Array',
            ]);
        }

        // Check each student entry
        foreach ($students as $index => $student) {
            $requiredStudentFields = ['studentFullName', 'studentGender', 'age', 'studentDateOfBirth', 'id'];
            foreach ($requiredStudentFields as $field) {
                if (!isset($student[$field]) || empty($student[$field])) {
                    return response()->json([
                        'ResponseCode' => '102',
                        'error' => "It looks like you missed the or Empty students[$index][$field] field. Please fill it in.",
                    ]);
                }
            }
        }

        // Find the parent customer by token
        $parent = DB::table('customers')->where('token', $request->token)->first();

        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        // Fetch the subject details and fee from the database
        $subjectFee = DB::table('products')
            ->join("categories", "products.category", "=", "categories.id")
            ->select("products.name as subjectName", "categories.price as subjectPrice", "categories.category_name as levelName", "categories.mode as mode")
            ->where('products.id', '=', $request->subject)
            ->first();

        if (!$subjectFee) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'Subject not found'
            ]);
        }

        // Determine the extra student charges based on the subject mode
        $extraStudentCharges = 0; // Initialize the extra student charge
        $extraStudentChargesData = DB::table("extra_student_charges")->first();

        if ($subjectFee->mode == "physical") {
            $extraStudentCharges = $extraStudentChargesData->physical_additional_charges; // Get the physical mode charges
        } else {
            $extraStudentCharges = $extraStudentChargesData->online_additional_charges; // Get the online mode charges
        }

        // Calculate the base job ticket price
        $basePrice = $subjectFee->subjectPrice * $request->classFrequency * $request->quantity;

        // Determine if there are extra students and calculate the extra charges
        $extraStudentChargesTotal = 0;
        if (count($students) > 1) {
            $extraStudentChargesTotal = $extraStudentCharges * (count($students) - 1) * $request->classFrequency * $request->quantity;
        }

        // Calculate the total ticket price
        $totalTicketPrice = $basePrice + $extraStudentChargesTotal;

        // Prepare the summary data
        $ticketSummary = [
            'registrationDate' => $request->registration_date,
            'classType' => $request->classType,
            'subject' => $subjectFee->subjectName,
            'subjectPrice' => $subjectFee->subjectPrice,
            'level' => $subjectFee->levelName,
            'tutorPereference' => $request->tutorPereference,
            'job_ticket_requirement' => $request->job_ticket_requirement,
            'classAddress' => $request->classAddress,
            'classLatitude' => $request->classLatitude,
            'classLongitude' => $request->classLongitude,
            'classCity' => isset($request->classCity) ? $request->classCity : (is_numeric($request->customerCity) ? $request->classCity : $request->customerCity),
            'classState' => isset($request->classState) ? $request->classState : (is_numeric($request->customerState) ? $request->classState : $request->customerState),
            'classPostalCode' => $request->classPostalCode,

            'classInformation' => [
                'classFrequency' => $request->classFrequency,
                'classDuration' => $request->quantity,
                'ticketType' => $request->subscription,
                'time' => $request->time,
                'day' => implode(", ", $request->day)
            ],

            'studentInformation' => [
                'mainStudent' => count($students) > 0 ? [
                    'fullName' => $students[0]['studentFullName'],
                    'gender' => $students[0]['studentGender'],
                    'age' => $students[0]['age'],
                    'specialNeed' => $students[0]['specialNeed'],
                    'extraFee' => ($extraStudentCharges * $request->classFrequency * $request->quantity)
                ] : null,
                'extraStudents' => array_map(function($student) use ($extraStudentCharges, $request) {
                    return [
                        'fullName' => $student['studentFullName'],
                        'gender' => $student['studentGender'],
                        'age' => $student['age'],
                        'specialNeed' => $student['specialNeed'],
                        'extraFee' => ($extraStudentCharges * $request->classFrequency * $request->quantity)
                    ];
                }, array_slice($students, 1)),
                        
                // Add the total count of extra students
                'extraStudentCount' => max(count($students) - 1, 0)
            ],

            'basePrice' => $basePrice,
            'extraStudentChargesTotal' => $extraStudentChargesTotal,
            'totalTicketPrice' => $totalTicketPrice
        ];

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Ticket summary fetched successfully.',
            'data' => $ticketSummary
        ]);
    }


    public function tutorAttendance(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        $token = $request->token;
        $parent = DB::table('customers')->where('token', $token)->first();

        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        $data = DB::table("class_attendeds")
            ->leftJoin("tutors", "class_attendeds.tutorID", "=", "tutors.id")
            ->leftJoin("products", "class_attendeds.subjectID", "=", "products.id")
            ->leftJoin("categories", "categories.id", "=", "products.category")
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

        if ($data->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'Data Not Found',
            ]);
        }

        // Format date, time, and add tutor_attendance_status and location
        $formattedData = $data->map(function ($item) {

            if ($item->tutor_image == null) {
                $item->tutor_image = url("/public/person_place_holder.png");
            } else {
                $item->tutor_image = $item->tutor_image;
            }

            if ($item->startTimeProofImage != null) {
                $item->startTimeProofImage = url("/public/signInProof"."/".$item->startTimeProofImage);
            }

             if ($item->endTimeProofImage != null) {
                $item->endTimeProofImage = url("/public/signOutProof"."/".$item->endTimeProofImage);
            }

            $item->start_date = Carbon::parse($item->date)->format('M d, Y');
            $item->start_time = Carbon::parse($item->startTime)->format('h:i A');

            $item->end_date = Carbon::parse($item->date)->format('M d, Y');
            $item->end_time = Carbon::parse($item->endTime)->format('h:i A');

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

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Data retrieved successfully.',
            'data' => $formattedData
        ]);

        // return Response::json(['attendance_data' => $data]);

    }

    public function tutorRequests(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        $parent_token = $request->token;
        $parent = DB::table('customers')->where('token', $parent_token)->first();

        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        
        $tutorRequests = DB::table('job_tickets')
            ->select([
                'job_tickets.id as ticket_id',
                'students.full_name as student_name',
                'job_tickets.created_at as ticket_created_at',
                'job_tickets.subscription as ticket_subscription',
                'job_tickets.mode as mode',
                'job_tickets.application_status as status',
                'job_tickets.uid as uid',
                'job_tickets.classFrequency as sessions_per_month',
                'job_tickets.quantity as class_duration',
                'job_tickets.ticket_approval_date as completion_date',
                'products.name as subject',
                'students.full_name as student_name',
                'tutors.full_name as tutor_name',
                'tutors.tutorImage as tutor_image',
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
        
                // Subquery to count distinct tutor offers
                DB::raw('(
                    SELECT COUNT(DISTINCT tutoroffers.tutorID)
                    FROM tutoroffers
                    WHERE tutoroffers.ticketID = job_tickets.id
                ) as total_tutors_applied')
            ])
            ->join('students', 'job_tickets.student_id', '=', 'students.id')
            ->join('customers', 'students.customer_id', '=', 'customers.id')
            ->leftJoin('tutoroffers', function($join) {
                $join->on('job_tickets.id', '=', 'tutoroffers.ticketID')
                     ->where('tutoroffers.status', '=', 'approved');
            })
            ->leftJoin('tutors', 'tutoroffers.tutorID', '=', 'tutors.id')
            ->leftJoin('states', 'customers.state', '=', 'states.id')   // Join with states table
            ->leftJoin('cities', 'customers.city', '=', 'cities.id')    // Join with cities table
            ->leftJoin('products', 'job_tickets.subjects', '=', 'products.id')
            ->where('customers.token', $parent_token)
    
            // Group only by job ticket ID and related unique fields
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
                'job_tickets.ticket_approval_date',
                'tutors.full_name',
                'tutors.tutorImage',
                'students.age',
                'students.specialNeed',
                'students.gender'
            )
            ->orderBy("job_tickets.id", "desc")
            ->get();
        
        // Check if any data is found
        if ($tutorRequests->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'Data Not Found'
            ]);
        }

            // dd($tutorRequests);

            // Iterate through the results to set the tutor image URL
            $tutorRequests->each(function ($request) {
                if ($request->tutor_image == null) {
                    $request->tutor_image = url("/public/person_place_holder.png");
                } else {
                    // $request->tutor_image = url("/public/tutorImage") . "/" . $request->tutor_image;
                    $request->tutor_image = $request->tutor_image;
                }
            });

        // return Response::json(['result' => $tutorRequests,'code'=>200,"msg"=>"Data found"]);

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Data retrieved successfully.',
            'data' => $tutorRequests
        ]);
    }

    public function tutorRequestDetails(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        $token = $request->token;
        $id = $request->id;
        $parent = DB::table('customers')->where('token', $token)->first();

        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

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

        // Return error response if no data is found
        if (!$tutorRequests) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'Data Not Found',
            ]);
        }

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

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Data retrieved successfully.',
            'data' => $tutorRequests,
        ]);

        // return Response::json(['code'=>200,"msg"=>"Data found",'result' => $tutorRequests]);

    }

    public function getStates(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        $token = $request->token;
        $parent = DB::table('customers')->where('token', $token)->first();

        // Return error response if no parent found
        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        $states = DB::table('states')->orderBy('name', 'asc')->get();

        // Check if states data is found
        if ($states->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No States found!',
            ]);
        }

        // Return success response with states data
        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Data retrieved successfully.',
            'data' => $states,
        ]);
    }

    public function getCities(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }


        // Validate that the state_id is present in the request
        if (!$request->has('state_id')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the state_id field. Please fill it in.',
            ]);
        }

        if (empty($request->state_id)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the state_id field. Please fill it in.',
            ]);
        }

        $state_id = $request->state_id;
        $token = $request->token;
        $parent = DB::table('customers')->where('token', $token)->first();

        // Return error response if no parent found
        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        $cities = DB::table('cities')->where("state_id", $state_id)->orderBy('name', 'asc')->get();

        // Check if cities data is found
        if ($cities->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No Cities found!',
            ]);
        }

        // Return success response with cities data
        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Data retrieved successfully.',
            'data' => $cities,
        ]);
    }

    public function getClassSchedules(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        $token = $request->token;
        $parent = DB::table('customers')->where('token', $token)->first();

        // Return error response if no parent found
        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        // return response()->json($parent);

        $classSchedules = DB::table('class_schedules')
            ->select([
                'class_schedules.id as class_schedule_id',
                'class_schedules.date as class_date',
                'class_schedules.status as class_status',
                DB::raw("DATE_FORMAT(class_schedules.startTime, '%h:%i %p') as start_time"),
                DB::raw("DATE_FORMAT(class_schedules.endTime, '%h:%i %p') as end_time"),
                'job_tickets.mode as mode',
                'job_tickets.id as job_ticket_id',
                'job_tickets.uid as uid',
                'job_tickets.classFrequency as class_frequency',
                'class_schedules.subjectID as subject_id',
                'students.id as student_id',
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
            ->leftjoin('states', 'customers.state', '=', 'states.id')
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
            ->orderBy(DB::raw("STR_TO_DATE(class_schedules.startTime, '%h:%i %p')"), 'asc') // Ordering by original startTime field
            ->get();

            // return response()->json($classSchedules);

        // Check if any class schedules are found
        if ($classSchedules->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No Class Schedules found!',
            ]);
        }

        $baseUrl = rtrim(url("/template/"), '/') . '/';

        foreach ($classSchedules as $schedule) {
            $schedule->formatted_position = $this->ordinal($schedule->schedule_position);
            $schedule->students_count = DB::table("job_ticket_students")->where("job_ticket_id", $schedule->job_ticket_id)->count() + 1;
            
            $studentid = $schedule->student_id;
            $subjectid = $schedule->subject_id;
            $scheduleID = $schedule->class_schedule_id;
    
            // Fetch tutor report listing for each student based on studentID
            $tutorReportListing = DB::table('tutorFirstSubmittedReportFromApps')
                ->leftJoin('students', 'tutorFirstSubmittedReportFromApps.studentID', '=', 'students.id')
                ->leftJoin('tutors', 'tutorFirstSubmittedReportFromApps.tutorID', '=', 'tutors.id')
                ->leftJoin('products', 'tutorFirstSubmittedReportFromApps.subjectID', '=', 'products.id')
                ->leftJoin('class_schedules', 'tutorFirstSubmittedReportFromApps.scheduleID', '=', 'class_schedules.id')
                ->leftJoin('job_tickets', 'class_schedules.ticketID', '=', 'job_tickets.id')
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
                ->where('students.id', $studentid)
                ->where('products.id', $subjectid)
                ->where('class_schedules.id', $scheduleID)
                ->orderBy('tutorFirstSubmittedReportFromApps.id', 'desc')
                ->get();
    

            // Format the fetched data
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
                        'q2' => 'How well is the student able to answer questions using a variety of methods and concepts?',
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
                    'additional_assessment' => [
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
                    'report_type' => 'Evaluation Report',
                    'uid' => $report->uid
                ];
            }
        
            // Add the report data to the class schedule
            $schedule->evaluationReport = $allFormattedData;
        }


        // return response()->json("I amhere");
        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Data retrieved successfully.',
            'data' => $classSchedules,
        ]);

        // return Response::json(['classSchedules' => $classSchedules]);
    }

    // public function getDueInvoices(Request $request)
    // {

    //     // Validate that the token is present in the request
    //     if (!$request->has('token')) {
    //         return response()->json([
    //             'ResponseCode' => '102',
    //             'error' => 'It looks like you missed the token field. Please fill it in.',
    //         ]);
    //     }

    //     if (empty($request->token)) {
    //         return response()->json([
    //             'ResponseCode' => '102',
    //             'error' => 'It looks like you missed the token field. Please fill it in.',
    //         ]);
    //     }

    //     $token = $request->token;
    //     $parent = DB::table('customers')->where('token', $token)->first();

    //     // Return error response if no parent found
    //     if (!$parent) {
    //         return response()->json([
    //             'ResponseCode' => '101',
    //             'error' => 'No Parent found!',
    //         ]);
    //     }

    //     $studentIds = Student::where('customer_id', $parent->id)->pluck('id');

    //     $invoices = Invoice::join("products", "invoices.subjectID", "=", "products.id")
    //         ->join("job_tickets", "invoices.ticketID", "=", "job_tickets.id")
    //         ->join("students", "invoices.studentID", "=", "students.id")
    //         ->join("categories", "products.category", "=", "categories.id")
    //         ->leftJoin("customer_commitment_fees", "job_tickets.id", "=", "customer_commitment_fees.ticket_id")
    //         ->leftJoin("class_schedules", "job_tickets.id", "=", "class_schedules.ticketID")
    //         ->select(
    //             "invoices.*",
    //             "invoices.id as invoiceID",
    //             "products.name as subject",
    //             "job_tickets.classFrequency as no_of_classes",
    //             "students.full_name as student",
    //             "students.gender as gender",
    //             "students.specialNeed as student_special_need",
    //             "categories.category_name as level",
    //             "job_tickets.uid as uid",
    //             "students.age as student_age",
    //             "job_tickets.classFrequency as no_of_sessions",
    //             "job_tickets.tutorPereference as tutor_preference",
    //             "job_tickets.subscription as subscription",
    //             "categories.price as subject_price",
    //             "job_tickets.quantity as class_duration",
    //             "customer_commitment_fees.payment_amount as commitment_fee_amount",
    //             "class_schedules.startTime as start_time",
    //             "class_schedules.endTime as end_time",
    //             // Calculate final amount
    //             DB::raw("IFNULL(invoices.invoiceTotal, 0) - IFNULL(customer_commitment_fees.payment_amount, 0) as total_final_amount"),
    //             // Determine if the show_invoice should be true or false
    //           DB::raw("
    //         CASE 
    //             WHEN SUM(CASE WHEN class_schedules.totalTime IS NOT NULL AND class_schedules.totalTime > 0 THEN 1 ELSE 0 END) > 0
    //                 AND SUM(CASE WHEN class_schedules.status = 'pending' OR class_schedules.status = 'attended' THEN 1 ELSE 0 END) > 0
    //                 THEN 'true'
    //             ELSE 'false'
    //         END as show_invoice"))
    //         ->whereIn('invoices.studentID', $studentIds)
    //         ->groupBy('invoices.id')  // Group by invoice ID since we are using aggregate functions
    //         ->orderBy("invoices.id", "desc")
    //         ->get();

    //     // Check if any invoices are found
    //     if ($invoices->isEmpty()) {
    //         return response()->json([
    //             'ResponseCode' => '104',
    //             'error' => 'No Due Invoices found!',
    //         ]);
    //     }

    //     // Fetch additional students for each invoice
    //     $invoices->each(function ($invoice) {
    //         // Fetch additional students related to the current job ticket
    //         $additional_students = DB::table("job_ticket_students")
    //             ->join("students", "job_ticket_students.student_id", "=", "students.id")
    //             ->where("job_ticket_students.job_ticket_id", $invoice->ticketID)
    //             ->select(
    //                 "students.full_name as student_name",
    //                 "students.age as student_age",
    //                 "students.gender as student_gender",
    //                 "students.dob",
    //                 "students.specialNeed as special_need",
    //                 "job_ticket_students.subject_id",
    //                 "job_ticket_students.extra_fee",
    //                 DB::raw("job_ticket_students.extra_fee * $invoice->classFrequency * $invoice->quantity as total_extra_fee")
    //             )
    //             ->get();

    //         // Attach additional students to the invoice
    //         $invoice->additionalStudents = $additional_students;
    //     });

    //     // Check if any invoices are found
    //     if ($invoices->isEmpty()) {
    //         return response()->json([
    //             'ResponseCode' => '104',
    //             'error' => 'No Due Invoices found!',
    //         ]);
    //     }

    //     // Format time && Remove extra quotes from the "day" field
    //     $formattedData = $invoices->map(function ($item) {
    //         $item->day = str_replace(['\\"', '"'], '', $item->day); // Remove extra backslashes and quotes
    //         $item->start_time = Carbon::parse($item->start_time)->format('h:i A');
    //         $item->end_time = Carbon::parse($item->end_time)->format('h:i A');

    //         return $item;
    //     });

    //     return response()->json([
    //         'ResponseCode' => '100',
    //         'message' => 'Invoices retrieved successfully.',
    //         'data' => $formattedData
    //     ]);

    //     // return response()->json(["result" => $invoices], 200);

    // }
    
    public function getDueInvoices(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }
    
        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }
    
        $token = $request->token;
        $parent = DB::table('customers')->where('token', $token)->first();
    
        // Return error response if no parent found
        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }
    
        $studentIds = Student::where('customer_id', $parent->id)->pluck('id');
    
        // Define URLs
        $groupImageUrl = "https://democlient.top/email-template/email-1/Group-1295.png";
        $logoUrl = "https://democlient.top/email-template/email-1/logo.png";
    
        // Fetch invoices data separately
        $invoices = Invoice::join("products", "invoices.subjectID", "=", "products.id")
            ->join("job_tickets", "invoices.ticketID", "=", "job_tickets.id")
            ->join("students", "invoices.studentID", "=", "students.id")
            ->join("categories", "products.category", "=", "categories.id")
            ->leftJoin("customer_commitment_fees", "job_tickets.id", "=", "customer_commitment_fees.ticket_id")
            ->leftJoin("class_schedules", "job_tickets.id", "=", "class_schedules.ticketID")
            ->select(
                "invoices.*",
                "invoices.id as invoiceID",
                "products.name as subject",
                "job_tickets.mode as mode",
                "job_tickets.classFrequency as no_of_classes",
                'job_tickets.id as ticketID',
                "students.full_name as student",
                "students.gender as gender",
                "students.specialNeed as student_special_need",
                "categories.category_name as level",
                "job_tickets.uid as uid",
                "students.age as student_age",
                "job_tickets.classFrequency as no_of_sessions",
                "job_tickets.tutorPereference as tutor_preference",
                "job_tickets.subscription as subscription",
                "categories.price as subject_price",
                "job_tickets.quantity as class_duration",
                'job_tickets.extra_student_total as extraFee',
                "customer_commitment_fees.payment_amount as commitment_fee_amount",
                "class_schedules.startTime as start_time",
                "class_schedules.endTime as end_time",
                DB::raw("IFNULL(invoices.invoiceTotal, 0) as total_invoice_amount"),
                // Calculate final amount
                DB::raw("IFNULL(invoices.invoiceTotal, 0) - IFNULL(customer_commitment_fees.payment_amount, 0) as total_final_amount"),
                DB::raw("
                CASE 
                    WHEN SUM(CASE WHEN class_schedules.totalTime IS NOT NULL AND class_schedules.totalTime > 0 THEN 1 ELSE 0 END) > 0
                    AND SUM(CASE WHEN class_schedules.status = 'pending' OR class_schedules.status = 'attended' THEN 1 ELSE 0 END) > 0
                    THEN 'true'
                    ELSE 'false'
                END as show_invoice")
            )
            ->whereIn('invoices.studentID', $studentIds)
            ->groupBy('invoices.id')
            ->orderBy("invoices.id", "desc")
            ->get();

    
        // Fetch customer commitment fees data separately
        $commitmentFees = DB::table('customer_commitment_fees')
            ->join('customers', 'customers.id', '=', 'customer_commitment_fees.customer_id')
            ->select(
                'customer_commitment_fees.*',
                'customer_commitment_fees.payment_amount as commitment_fee_amount',
                'customer_commitment_fees.payment_date as commitment_fee_date',
                'customer_commitment_fees.payment_attachment as commitment_fee_attachment',
                DB::raw("'' as extraFee"),
                DB::raw("'' as no_of_sessions"),
                DB::raw("customers.full_name as payerName"),
                DB::raw("customers.email as payerEmail"),
                DB::raw("'paid' as status"),
                DB::raw("'' as invoiceID"), // Empty invoice-related fields for commitment fees
                DB::raw("'' as subject"),
                DB::raw("'' as student"),
                DB::raw("'' as gender"),
                DB::raw("'' as student_special_need"),
                DB::raw("'' as level"),
                DB::raw("'' as uid"),
                DB::raw("'' as student_age"),
                DB::raw("'' as tutor_preference"),
                DB::raw("'' as subscription"),
                DB::raw("'' as subject_price"),
                DB::raw("'' as class_duration"),
                DB::raw("'' as start_time"),
                DB::raw("'' as end_time"),
                DB::raw("'' as total_invoice_amount"),
                DB::raw("'true' as show_invoice") // Default show_invoice as false for commitment fees
            )
            ->where('customer_commitment_fees.customer_id', $parent->id)
            ->orderBy('customer_commitment_fees.id', 'desc')
            ->get();
    
        // Check if any invoices or commitment fees are found
        if ($invoices->isEmpty() && $commitmentFees->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No Due Invoices or Commitment Fees found!',
            ]);
        }
    
        // Merge invoices and commitment fees into a single array
        $mergedData = [];
    
        // Add invoice data to the merged array
        foreach ($invoices as $invoice) {
            $invoice = (object) $invoice->toArray(); // Convert Invoice model to a modifiable object
            $invoice->day = str_replace(['\\"', '"'], '', $invoice->day); // Clean up day field
            $invoice->start_time = Carbon::parse($invoice->start_time)->format('h:i A');
            $invoice->end_time = Carbon::parse($invoice->end_time)->format('h:i A');
            $invoice->type = 'invoice'; // Add type for distinguishing
            $invoice->group_image_url = $groupImageUrl;
            $invoice->logo_url = $logoUrl;
            
            $students = DB::table('job_ticket_students')->where('job_ticket_id', $invoice->ticketID)->get();

            $invoice->jobTicketExtraStudents = [];
            
            foreach ($students as $student) {
                $studentData = [
                    'student_name' => $student->student_name,
                    'student_age' => $student->student_age,
                    'student_gender' => $student->student_gender,
                    'year_of_birth' => $student->year_of_birth,
                    'special_need' => $student->special_need,
                    'subject_id' => $student->subject_id,
                ];
        
                $invoice->jobTicketExtraStudents[] = $studentData;
            }
            
            // Determine if there are extra students and calculate the extra charges
            if (count($invoice->jobTicketExtraStudents) > 0) {
                $invoice->total_invoice_amount = $invoice->total_invoice_amount - $invoice->extraFee;
                // $invoice->extraFee = $invoice->extraFee * (count($students)) * $invoice->no_of_sessions * $invoice->class_duration;
            }
        
            // Add the count of jobTicketExtraStudents to the object
            $invoice->extraStudentCount = count($invoice->jobTicketExtraStudents);
            $mergedData[] = $invoice;
        }
    
        // Add commitment fees data to the merged array
        foreach ($commitmentFees as $commitmentFee) {
            $commitmentFee->payment_date = Carbon::parse($commitmentFee->payment_date)->format('Y-m-d');
            $commitmentFee->type = 'commitment_fee'; // Add type for distinguishing
            $commitmentFee->group_image_url = $groupImageUrl;
            $commitmentFee->logo_url = $logoUrl;
            $mergedData[] = $commitmentFee;
        }
    
        // Sort the merged data array by the latest `created_at`
        usort($mergedData, function ($a, $b) {
            return strtotime($b->created_at) - strtotime($a->created_at);
        });
    
        // Return the response with the merged data
        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Invoices and Commitment Fees retrieved successfully.',
            'data' => $mergedData
        ]);
    }

    public function getUpcomingClasses(Request $request)
    {

        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        $token = $request->token;
        $parent = DB::table('customers')->where('token', $token)->first();

        // Return error response if no parent found
        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
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

        // Check if any upcoming classes are found
        if ($classSchedules->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No Upcoming Classes found!',
            ]);
        }

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Upcoming classes retrieved successfully.',
            'data' => $classSchedules
        ]);
    }

    public function getTodayClasses(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        $token = $request->token;
        $parent = DB::table('customers')->where('token', $token)->first();

        // Return error response if no parent found
        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        // $classSchedules = DB::table('class_schedules')
        //     ->join('products', 'class_schedules.subjectID', '=', 'products.id')
        //     ->join('students', 'class_schedules.studentID', '=', 'students.id')
        //     ->join('customers', 'students.customer_id', '=', 'customers.id')
        //     ->leftjoin('states', 'customers.state', '=', 'states.id')
        //     ->leftjoin('cities', 'customers.city', '=', 'cities.id')
        //     ->where('customers.token', '=', $token)
        //     ->where('class_schedules.status', '=', 'scheduled')
        //     ->whereDate('class_schedules.date', '=', date("Y-m-d"))  // Filter for the current date
        //     ->select(
        //         'class_schedules.id as id',
        //         'class_schedules.ticketID as ticketID',
        //         'products.name as subject_name',
        //         'products.id as subject_id',
        //         'students.full_name as studentName',
        //         'customers.address1 as studentAddress1',
        //         'customers.address2 as studentAddress2',
        //         'states.name as studentState',
        //         'cities.name as studentCity',
        //         'class_schedules.date as date',
        //         DB::raw("DATE_FORMAT(class_schedules.startTime, '%h:%i %p') as startTime"),
        //         DB::raw("DATE_FORMAT(class_schedules.endTime, '%h:%i %p') as endTime")
        //     )
        //     ->get();
        
        $classSchedules = DB::table('class_schedules')
            ->join('products', 'class_schedules.subjectID', '=', 'products.id')
            ->join('students', 'class_schedules.studentID', '=', 'students.id')
            ->join('customers', 'students.customer_id', '=', 'customers.id')
            ->leftJoin('states', 'customers.state', '=', 'states.id')
            ->leftJoin('cities', 'customers.city', '=', 'cities.id')
            ->where('customers.token', '=', $token)
            ->where('class_schedules.status', '=', 'scheduled')
            ->whereDate('class_schedules.date', '=', date("Y-m-d"))  // Filter for the current date
            ->whereTime('class_schedules.endTime', '>', date("H:i:s")) // Filter for endTime greater than current time
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


        // Check if any classes are found for today
        if ($classSchedules->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No classes scheduled for today!',
            ]);
        }

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Classes for today retrieved successfully.',
            'data' => $classSchedules
        ]);
    }

    public function approveAttendance(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        // Validate that the id is present in the request
        if (!$request->has('id')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the id field. Please fill it in.',
            ]);
        }

        if (empty($request->id)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the id field. Please fill it in.',
            ]);
        }

        $id = $request->id;
        $token = $request->token;
        $parent = DB::table('customers')->where('token', $token)->first();

        // Return error response if no parent found
        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        // Find the class attendance record
        $classAttendance = DB::table('class_attendeds')->where('id', $id)->first();

        // Return error response if no class attendance record found
        if (!$classAttendance) {
            return response()->json([
                'ResponseCode' => '404',
                'error' => 'Class attendance record not found!',
            ]);
        }

        // Find the class schedule
        $classSchedule = DB::table('class_schedules')->where('id', $classAttendance->class_schedule_id)->first();
        
        // dd($classSchedule);
        
        $sTime = $classSchedule->startTime;
        $eTime = $classSchedule->endTime;
        $t1 = strtotime($sTime);
        $t2 = strtotime($eTime);
        $differenceInSeconds = $t2 - $t1;
        
        // If the end time is earlier than the start time, adjust for the next day
        if ($differenceInSeconds < 0) {
            $differenceInSeconds += 24 * 3600; // Add 24 hours in seconds
        }
        
        // Format the difference in HH:MM:SS
        $formattedDifference = gmdate('H:i:s', $differenceInSeconds);
        
        // dd($formattedDifference);

        // Return error response if no class schedule found
        if (!$classSchedule) {
            return response()->json([
                'ResponseCode' => '404',
                'error' => 'Class schedule not found!',
            ]);
        }

        // Update the class attendance and class schedule
        DB::table('class_attendeds')->where('id', $id)->update([
            'status' => 'attended',
            'totalTime' => $formattedDifference
        ]);

        DB::table('class_schedules')->where('id', $classSchedule->id)->update([
            'status' => 'attended'
        ]);
        
                
        $tutor = DB::table('tutors')->where('id', $classSchedule->tutorID)->first();

        // Send push notifications to tutor devices
        $tutorDevices = DB::table('tutor_device_tokens')->where('tutor_id', '=', $classSchedule->tutorID)->distinct()->get(['device_token', 'tutor_id']);
        foreach ($tutorDevices as $rowDeviceToken) {
            $push_notification_api = new PushNotificationLibrary();
            $deviceToken = $rowDeviceToken->device_token;
            $title = 'Attendance Approved';
            $message = 'Attendance Approved Successfully';
        
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
        
        $data = [
            "ResponseCode" => "100",
            "message" => "Attendance Approved Successfully"
        ];
        
        event(new TutorDashboard($data, $tutor->token));
        event(new ParentDashbaord($data, $parent->token));
        
        $students = DB::table('students')->where('id', '=', $classSchedule->studentID)->first();

        // Send push notification to parent devices
        $parent_device_tokens = DB::table('parent_device_tokens')->where('parent_id', '=', $students->customer_id)->distinct()->get(['device_token', 'parent_id']);
        
        foreach ($parent_device_tokens as $token) {
            $push_notification_api = new PushNotificationLibrary();
            $deviceToken = $token->device_token;
            $title = 'Attendance Approved';
            $message = 'Attendance Approved Successfully';
        
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
        

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Attendance approved successfully.'
        ]);
    }

    public function rejectAttendance(Request $request)
    {

        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        // Validate that the id is present in the request
        if (!$request->has('id')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the id field. Please fill it in.',
            ]);
        }

        if (empty($request->id)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the id field. Please fill it in.',
            ]);
        }

        $id = $request->id;
        $token = $request->token;
        $parent = DB::table('customers')->where('token', $token)->first();

        // Return error response if no parent found
        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        // Find the class attendance record
        $classAttendance = DB::table('class_attendeds')->where('id', $id)->first();

        // Return error response if no class attendance record found
        if (!$classAttendance) {
            return response()->json([
                'ResponseCode' => '404',
                'error' => 'Class attendance record not found!',
            ]);
        }

        // Find the class schedule
        $classSchedule = DB::table('class_schedules')->where('id', $classAttendance->class_schedule_id)->first();

        // Return error response if no class schedule found
        if (!$classSchedule) {
            return response()->json([
                'ResponseCode' => '404',
                'error' => 'Class schedule not found!',
            ]);
        }

        // Update the class attendance and class schedule
        DB::table('class_attendeds')->where('id', $id)->update([
            'status' => 'dispute'
        ]);

        DB::table('class_schedules')->where('id', $classSchedule->id)->update([
            'status' => 'dispute'
        ]);
        
        // Send push notifications to tutor devices
        $tutorDevices = DB::table('tutor_device_tokens')->where('tutor_id', '=', $classSchedule->tutorID)->distinct()->get(['device_token', 'tutor_id']);
        foreach ($tutorDevices as $rowDeviceToken) {
            $push_notification_api = new PushNotificationLibrary();
            $deviceToken = $rowDeviceToken->device_token;
            $title = 'Attendance rejected';
            $message = 'Attendance rejected Successfully';
        
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
        
        $students = DB::table('students')->where('id', '=', $classSchedule->studentID)->first();
        
        // Send push notification to parent devices
        $parent_device_tokens = DB::table('parent_device_tokens')->where('parent_id', '=', $students->customer_id)->distinct()->get(['device_token', 'parent_id']);
        
        foreach ($parent_device_tokens as $token) {
            $push_notification_api = new PushNotificationLibrary();
            $deviceToken = $token->device_token;
            $title = 'Attendance rejected';
            $message = 'Attendance rejected Successfully';
        
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


        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Attendance rejected successfully.'
        ]);
    }

    public function news(Request $request)
    {

        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        $token = $request->token;
        $parent = DB::table('customers')->where('token', $token)->first();

        // Return error response if no parent found
        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        $baseUrl = url("/public/MobileNewsImages/") . "/";

       $news = DB::table('news')
        ->where('is_deleted', 0)
        ->where('type', '=', 'Parent')
        ->select('*',
            DB::raw("CONCAT_WS('/', '$baseUrl', headerimage) AS headerimage"),
            DB::raw("CONCAT(DATE_FORMAT(created_at, '%e %b, %Y'), ' | ', DATE_FORMAT(created_at, '%l:%i %p')) AS date_time"))
        ->orderBy('id', 'desc')
        ->get();


        // Check if any news data is returned
        if ($news->isEmpty()) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'No news found.',
            ]);
        }

        return response()->json([
            'ResponseCode' => 100,
            'message' => 'News fetched successfully.',
            'data' => $news
        ]);
    }

    public function newsStatusUpdate(Request $request)
    {
        // Define required fields
        $requiredFields = ['id', 'status'];

        // Check for missing or empty fields
        foreach ($requiredFields as $field) {
            if (!$request->has($field) || empty($request->input($field))) {
                return response()->json([
                    'ResponseCode' => '102',
                    'error' => "It looks like you missed the or empty $field field. Please fill it in."
                ]);
            }
        }

        // Validate token
        $token = $request->input('token');
        $parent = DB::table('customers')->where('token', $token)->first();

        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'Invalid token or parent not found.'
            ]);
        }

        // Retrieve the ID and status from request
        $newsID = $request->input('id');
        $status = $request->input('status');

        // Check if the news item exists
        $newsExists = DB::table('news')->where('id', $newsID)->exists();

        if (!$newsExists) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'News not found.'
            ]);
        }

        // Update the news status
        $updatedRows = DB::table('news')->where('id', $newsID)->update(['latestStatus' => $status]);

        // Check if the update was successful
        if ($updatedRows === 0) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No news status records updated.'
            ]);
        }

        // Return success response
        return response()->json([
            'ResponseCode' => '100',
            'message' => 'News Status Updated'
        ]);
    }

    public function detailedNews(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        // Validate that the id is present in the request
        if (!$request->has('id')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the id field. Please fill it in.',
            ]);
        }

        if (empty($request->id)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the id field. Please fill it in.',
            ]);
        }

        $id = $request->id;
        $token = $request->token;
        $parent = DB::table('customers')->where('token', $token)->first();

        // Return error response if no parent found
        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        $baseUrl = url("/public/MobileNewsImages");

        $news = DB::table('news')
            ->where('id', '=', $id)
            ->select('*',
                DB::raw("CONCAT_WS('/', '$baseUrl', headerimage) AS headerimage"),
                DB::raw("CONCAT(DATE_FORMAT(created_at, '%e %b, %Y'), ' | ', DATE_FORMAT(created_at, '%l:%i %p')) AS date_time"))
            ->first();

        // Check if any news data is returned
        if (!$news) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'No news found.',
            ]);
        }

        return response()->json([
            'ResponseCode' => 100,
            'message' => 'News Detailed fetched successfully.',
            'data' => $news
        ]);
    }

    public function notifications(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }
    
        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }
    
        $token = $request->token;
        $parent = DB::table('customers')->where('token', $token)->first();
    
        // Return error response if no parent found
        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }
    
        // Query notifications based on whether the token is NULL or not
        $notifications = DB::table('notifications')
            ->when(
                !is_null($token),
                function ($query) use ($token) {
                    return $query->where(function ($q) use ($token) {
                        $q->where('token', $token)
                          ->orWhereNull('token');
                    });
                },
                function ($query) {
                    return $query->whereNull('token');
                }
            )
            ->where('type', '=', 'Parent')
            // ->where('status', '=', 'new')
            ->select('*',
                DB::raw("CONCAT(DATE_FORMAT(created_at, '%e %b, %Y'), ' | ', DATE_FORMAT(created_at, '%l:%i %p')) AS date_time"))
            ->orderBy('id', 'desc')
            ->get();
    
        // Check if any notifications data is returned
        if ($notifications->isEmpty()) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'No notifications found.',
            ]);
        }
    
        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Notifications fetched successfully.',
            'data' => $notifications
        ]);
    }


    public function notificationsStatusUpdate(Request $request)
    {
        // Define required fields
        $requiredFields = ['id', 'status'];

        // Check for missing or empty fields
        foreach ($requiredFields as $field) {
            if (!$request->has($field) || empty($request->input($field))) {
                return response()->json([
                    'ResponseCode' => '102',
                    'error' => "It looks like you missed the or empty $field field. Please fill it in."
                ]);
            }
        }

        // Validate token
        $token = $request->input('token');
        $parent = DB::table('customers')->where('token', $token)->first();

        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'Invalid token or parent not found.'
            ]);
        }

        // Retrieve the ID and status from request
        $notificationsID = $request->input('id');
        $status = $request->input('status');

        // Check if the notifications item exists
        $notificationsExists = DB::table('notifications')->where('id', $notificationsID)->exists();

        if (!$notificationsExists) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'Notifications not found.'
            ]);
        }

        // Update the news status
        $updatedRows = DB::table('notifications')->where('id', $notificationsID)->update(['status' => $status]);

        // Check if the update was successful
        if ($updatedRows === 0) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No Notifications status records updated.'
            ]);
        }

        // Return success response
        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Notifications Status Updated'
        ]);
    }

    public function detailedNotifications(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        // Validate that the id is present in the request
        if (!$request->has('id')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the id field. Please fill it in.',
            ]);
        }

        if (empty($request->id)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the id field. Please fill it in.',
            ]);
        }

        $id = $request->id;
        $token = $request->token;
        $parent = DB::table('customers')->where('token', $token)->first();

        // Return error response if no parent found
        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }


        $notifications = DB::table('notifications')
            ->where('id', '=', $id)
            ->select('*',
                DB::raw("CONCAT(DATE_FORMAT(created_at, '%e %b, %Y'), ' | ', DATE_FORMAT(created_at, '%l:%i %p')) AS date_time"))
            ->first();

        // Check if any Notifications data is returned
        if (!$notifications) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'No Notifications found.',
            ]);
        }

        return response()->json([
            'ResponseCode' => 100,
            'message' => 'Notifications Detailed fetched successfully.',
            'data' => $notifications
        ]);
    }


    public function payCommitmentFee(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        $token = $request->token;
        $parent = DB::table('customers')->where('token', $token)->first();

        // Return error response if no parent found
        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        // Define validation rules and custom messages
        $rules = [
            'feeAmount' => 'required|numeric|min:0',
            'feePaymentDate' => 'required|date',
            'receivingAccount' => 'required|string'
        ];

        $messages = [
            'feeAmount.required' => 'It looks like you missed the fee amount field. Please fill it in.',
            'feeAmount.numeric' => 'Invalid fee amount field. Please fill it in.',
            'feeAmount.min' => 'Fee amount must be at least 0',
            'feePaymentDate.required' => 'It looks like you missed the fee payment date field. Please fill it in.',
            'feePaymentDate.date' => 'Invalid fee payment date field. Please fill it in.',
            'receivingAccount.required' => 'It looks like you missed the receiving account field. Please fill it in.',
            'receivingAccount.string' => 'Invalid receiving account field. Please fill it in.'
        ];

        // Validate request data
        $validator = \Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json([
                'ResponseCode' => '102',
                'error' => $errorMessage,
            ]);
        }

        // Prepare data for insertion
        $feePaymentValue = [
            'customer_id' => $parent->id,
            'payment_amount' => $request->feeAmount,
            'payment_date' => $request->feePaymentDate,
            'receiving_account' => $request->receivingAccount,
        ];

        // Update customer status
        $updateStatus = DB::table('customers')->where('id', $parent->id)->update(['status' => 'active']);

        if ($updateStatus === 0) {
            return response()->json([
                'ResponseCode' => '103',
                'error' => 'Failed to update customer status.',
            ]);
        }

        // Insert fee payment record
        $insertResult = DB::table('customer_commitment_fees')->insert($feePaymentValue);

        if (!$insertResult) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'Failed to record fee payment.',
            ]);
        }

        return response()->json([
            'ResponseCode' => 100,
            'message' => 'Data Updated Successfully',
        ]);
    }

    public function faqs(Request $request)
    {

        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        $token = $request->token;
        $parent = DB::table('customers')->where('token', $token)->first();

        // Return error response if no parent found
        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        // Fetch FAQs from the database
        $type = 'parent'; // Can be 'tutor' or 'parent'
    
        // Retrieve FAQs based on type and group them by category
        $faqs = DB::table('faqs')
            ->where('type', $type)
            ->orderBy('category') // Group by category
            ->orderBy('id', 'asc')
            ->get()
            ->groupBy('category');
    
        if ($faqs->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No FAQs found for the selected type.',
            ]);
        }

        return response()->json([
            'ResponseCode' => 100,
            'message' => 'FAQs fetched successfully.',
            'data' => $faqs
        ]);
    }
    
    public function policies(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }
    
        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }
    
        $token = $request->token;
        $parent = DB::table('customers')->where('token', $token)->first();
    
        // Return error response if no parent found
        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }
    
        // Define the type for policies (e.g., 'parent' or 'tutor')
        $type = 'parent'; // Change this based on your logic
    
        // Fetch policies based on the user type and group them by policy_type
        $policies = DB::table('policies')
            ->where('user_role', $type)
            ->orderBy('policy_type') // Group by policy_type
            ->orderBy('id', 'asc')
            ->get()
            ->groupBy('policy_type');
    
        if ($policies->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No Policies found for the selected type.',
            ]);
        }
    
        return response()->json([
            'ResponseCode' => 100,
            'message' => 'Policies fetched successfully.',
            'data' => $policies
        ]);
    }


    public function submitClassSchedulesAdmin(Request $request)
    {

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        // Validate that the token and other required fields are present in the request
        $requiredFields = ['token', 'classScheduleID', 'date', 'tutorID', 'studentID', 'subjectID', 'ticketID', 'start_time', 'end_time'];
        foreach ($requiredFields as $field) {
            if (!$request->has($field)) {
                return response()->json([
                    'ResponseCode' => '102',
                    'error' => "It looks like you missed the $field field. Please fill it in.",
                ]);
            }
        }

        $token = $request->token;
        $parent = DB::table('customers')->where('token', $token)->first();

        // Return error response if no parent found
        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        // Calculate total time
        $sTime = $request->start_time;
        $eTime = $request->end_time;
        $t1 = strtotime($sTime);
        $t2 = strtotime($eTime);
        $differenceInSeconds = $t2 - $t1;
        $differenceInHours = $differenceInSeconds / 3600;

        if ($differenceInHours < 0) {
            $differenceInHours += 24;
        }

        // Prepare data for insertion
        $tutorOfferValues = [
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
        ];

        // Insert new class schedule
        DB::table('class_schedules')->insertGetId($tutorOfferValues);

        // Update job tickets
        $remaining_classes = DB::table('job_tickets')->where('id', $request->ticketID)->first();

        if (!$remaining_classes) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'Job ticket not found.',
            ]);
        }

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
        $customer = DB::table("customers")->where("id",$student->customer_id)->first();
        
        // Send push notifications to tutor devices
        $tutorDevices = DB::table('tutor_device_tokens')->where('tutor_id', '=', $tutor->id)->distinct()->get(['device_token', 'tutor_id']);
        foreach ($tutorDevices as $rowDeviceToken) {
            $deviceToken = $rowDeviceToken->device_token;
            $title = 'Class Schedules Submited';
            $message = 'Class Schedules Submited Successfully';
        
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
            $title = 'Class Schedules Submited';
            $message = 'Class Schedules Submited Successfully';
        
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

            //parent tokent
            $data = [
                "ResponseCode" => "100",
                "message" => "Admin Added Class Schedule from Admin Panel"
            ];

            event(new TutorDashboard($data,$tutor->token));
            event(new ParentDashbaord($data));
            event(new SingleParentDashboard($data,$customer->token));

        } catch(Exception $e) {
            return response()->json(["ResponseCode"=> "103",
                "error"=> "Unable to created Job Ticket"]);
        }

        return response()->json([
            'ResponseCode' => 100,
            'message' => 'Class Schedule Added Successfully',
        ]);
    }

    public function savePaymentInfo(Request $request)
    {

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        // Validate that the token and other required fields are present in the request
        $requiredFields = ['token', 'card_no', 'cvv', 'expiry_date', 'user_id'];
        foreach ($requiredFields as $field) {
            if (!$request->has($field)) {
                return response()->json([
                    'ResponseCode' => '102',
                    'error' => "It looks like you missed the $field field. Please fill it in.",
                ]);
            }
        }

        $token = $request->token;
        $parent = DB::table('customers')->where('token', $token)->first();

        // Return error response if no parent found
        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        // Prepare data for insertion
        $data = [
            'card_no' => $request->input('card_no'),
            'cvv' => $request->input('cvv'),
            'expiry_date' => $request->input('expiry_date'),
            'user_id' => $request->input('user_id'),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Insert payment card information into the database
        DB::table('payment_cards')->insert($data);

        return response()->json([
            'ResponseCode' => 100,
            'message' => 'Payment card stored successfully',
        ]);
    }

    public function paymentCards(Request $request)
    {

        // Validate that the token and id are present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (!$request->has('id')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the id field. Please fill it in.',
            ]);
        }

        if (empty($request->id)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the id field. Please fill it in.',
            ]);
        }

        $token = $request->token;
        $id = $request->id;

        $parent = DB::table('customers')->where('token', $token)->first();

        // Return error response if no parent found
        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        // Fetch payment information for the given user ID
        $data = DB::table('payment_cards')->where('user_id', $id)->get();

        // Return error response if no payment information is found
        if ($data->isEmpty()) {
            return response()->json([
                'ResponseCode' => '103',
                'error' => 'No payment information found for this user.',
            ]);
        }

        // Return the payment information
        return response()->json([
            'ResponseCode' => 100,
            'message' => 'Payment information fetched successfully.',
            'data' => $data,
        ]);
    }

    public function submitEvaluationReport(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        // Validate required fields
        $requiredFields = [
            'tutorID', 'studentID', 'scheduleID', 'subjectID', 'currentDate',
            'knowledge', 'knowledge2', 'understanding', 'understanding2',
            'criticalThinking', 'criticalThinking2', 'observation',
            'additionalAssisment', 'plan'
        ];

        foreach ($requiredFields as $field) {
            if (!$request->has($field)) {
                return response()->json([
                    'ResponseCode' => '102',
                    'error' => "It looks like you missed the $field field. Please fill it in.",
                ]);
            }
        }

        $token = $request->token;

        // Find parent record
        $parent = DB::table('customers')->where('token', $token)->first();

        // Return error response if no parent found
        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        // Prepare data for insertion
        $values = [
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
        ];

        // Insert data into the database
        try {
            $submitClassScheduleTime = DB::table('tutorFirstSubmittedReportFromApps')->insertGetId($values);

            // Fetch the inserted record
            $row = DB::table('tutorFirstSubmittedReportFromApps')
                ->where('id', $submitClassScheduleTime)
                ->select('reportType as reportType', 'currentDate as currentDate', 'knowledge as knowledge', 'understanding as understanding', 'additionalAssisment as additionalAssisment', 'plan as plan')
                ->first();



            $data = [
                "ResponseCode" => "100",
                "message" => "Evaluation Report Successfully."
            ];

            event(new StudentReport($data,$parent->token));

            return response()->json([
                'ResponseCode' => '100',
                'successMessage' => 'Report Submitted Successfully',
                'data' => $row
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'Failed to submit the report. Please try again later.',
                'details' => $e->getMessage()
            ]);
        }
    }

    public function GetStudentReportsListing(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        $token = $request->token;
        $parent = DB::table('customers')->where('token', $token)->first();

        // Return error response if no parent found
        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        $baseUrl = rtrim(url("/template/"), '/') . '/';
        $parentID = $parent->id;

        // Fetch evaluation reports
        $evaluationReportListing = DB::table('tutorFirstSubmittedReportFromApps')
            ->leftJoin('students', 'tutorFirstSubmittedReportFromApps.studentID', '=', 'students.id')
            ->leftJoin('tutors', 'tutorFirstSubmittedReportFromApps.tutorID', '=', 'tutors.id')
            ->leftJoin('products', 'tutorFirstSubmittedReportFromApps.subjectID', '=', 'products.id')
            ->leftJoin('class_schedules', 'tutorFirstSubmittedReportFromApps.scheduleID', '=', 'class_schedules.id')
            ->leftJoin('job_tickets', 'class_schedules.ticketID', '=', 'job_tickets.id')
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
                DB::raw("MONTHNAME(STR_TO_DATE(tutorFirstSubmittedReportFromApps.currentDate, '%m/%d/%Y')) as month"),
                'tutorFirstSubmittedReportFromApps.*'
            )
            ->where('students.customer_id', $parentID)
            ->orderBy('tutorFirstSubmittedReportFromApps.id', 'desc')
            ->get();

        // Fetch progress reports
        $progressReportListing = DB::table('progressReport')
            ->leftJoin('students', 'progressReport.studentID', '=', 'students.id')
            ->leftJoin('tutors', 'progressReport.tutorID', '=', 'tutors.id')
            ->leftJoin('products', 'progressReport.subjectID', '=', 'products.id')
            ->leftJoin('class_schedules', 'progressReport.scheduleID', '=', 'class_schedules.id')
            ->leftJoin('job_tickets', 'class_schedules.ticketID', '=', 'job_tickets.id')
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
            ->where('students.customer_id', $parentID)
            ->orderBy('progressReport.id', 'desc')
            ->get();

        // Return error response if no reports are found
        if ($evaluationReportListing->isEmpty() && $progressReportListing->isEmpty()) {
            return response()->json([
                'ResponseCode' => '103',
                'error' => 'No reports found for this parent.',
            ]);
        }

        // Format the fetched data
        $formattedData = [];
        foreach ($evaluationReportListing as $key => $report) {
            $formattedData[] = [
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
                    'q2' => 'How well is the student able to answer questions using a variety of methods and concepts?',
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
                'additional_assessment' => [
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
                'report_type' => 'Evaluation Report',
                'uid' => $report->uid
            ];
        }

        foreach ($progressReportListing as $key => $report) {
            $formattedData[] = [
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
                "report_type" => "Progress Report",
                "uid" => $report->uid
            ];
        }

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Reports fetched successfully.',
            'data' => $formattedData
        ]);
    }

    public function evaluationReportListing(Request $request)
    {
        // Validate that the token and id are present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }


        $token = $request->token;

        // Find parent record
        $parent = DB::table('customers')->where('token', $token)->first();

        // Return error response if no parent found
        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        $baseUrl = rtrim(url("/template/"), '/') . '/';
        $parentID = $parent->id;

        // Fetch tutor report listing
        $tutorReportListing = DB::table('tutorFirstSubmittedReportFromApps')
            ->leftJoin('students', 'tutorFirstSubmittedReportFromApps.studentID', '=', 'students.id')
            ->leftJoin('tutors', 'tutorFirstSubmittedReportFromApps.tutorID', '=', 'tutors.id')
            ->leftJoin('products', 'tutorFirstSubmittedReportFromApps.subjectID', '=', 'products.id')
            ->leftJoin('class_schedules', 'tutorFirstSubmittedReportFromApps.scheduleID', '=', 'class_schedules.id')
            ->leftJoin('job_tickets', 'class_schedules.ticketID', '=', 'job_tickets.id')
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

        // Return error response if no reports are found
        if ($tutorReportListing->isEmpty()) {
            return response()->json([
                'ResponseCode' => '103',
                'error' => 'No evaluation reports found for this parent.',
            ]);
        }

        // Format the fetched data
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
                    'q2' => 'How well is the student able to answer questions using a variety of methods and concepts?',
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
                'additional_assessment' => [
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
                'report_type' => 'Evaluation Report',
                'uid' => $report->uid
            ];
        }

        return response()->json([
            'ResponseCode' => 100,
            'message' => 'Evaluation reports fetched successfully.',
            'data' => $allFormattedData
        ]);
    }

    public function evaluationReportView(Request $request)
    {
        // Validate that the token and id are present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }


        if (!$request->has('id')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the id field. Please fill it in.',
            ]);
        }

        if (empty($request->id)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the id field. Please fill it in.',
            ]);
        }


        $token = $request->token;
        $id = $request->id;

        $parent = DB::table('customers')->where('token', $token)->first();

        // Return error response if no parent found
        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        $tutorReportListing = DB::table('tutorFirstSubmittedReportFromApps')
            ->join('students', 'tutorFirstSubmittedReportFromApps.studentID', '=', 'students.id')
            ->join('tutors', 'tutorFirstSubmittedReportFromApps.tutorID', '=', 'tutors.id')
            ->join('products', 'tutorFirstSubmittedReportFromApps.subjectID', '=', 'products.id')
            ->where('tutorFirstSubmittedReportFromApps.id', '=', $id)
            ->select(
                'tutorFirstSubmittedReportFromApps.id as id',
                'tutorFirstSubmittedReportFromApps.reportType as reportType',
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
                'tutorFirstSubmittedReportFromApps.created_at as created_at'
            )
            ->first(); // Use first() to get a single record

        if (!$tutorReportListing) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'Data Not Found',
            ]);
        }

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Data retrieved successfully.',
            'data' => $tutorReportListing
        ]);
    }

    public function progressReportListing(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        $token = $request->token;
        $parent = DB::table('customers')->where('token', $token)->first();

        // Return error response if no parent found
        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        $baseUrl = rtrim(url("/template/"), '/') . '/';
        $parentID = $parent->id; // Example parent ID value

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

        if ($progressReportListing->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No progress reports found for the given customer.',
            ]);
        }

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

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Data retrieved successfully.',
            'data' => $allFormattedData
        ]);

        // return response()->json(['code'=>200,'progressReportListing' => $allFormattedData]);

    }

    public function submitProgressReport(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }


        $token = $request->token;
        $parent = DB::table('customers')->where('token', $token)->first();

        // Return error response if no parent found
        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        // Validate required fields
        $validated = $request->validate([
            'tutorID' => 'required|integer',
            'studentID' => 'required|integer',
            'subjectID' => 'required|integer',
            'month' => 'required|string',
            'observation' => 'nullable|string',
            'observation2' => 'nullable|string',
            'observation3' => 'nullable|string',
            'observation4' => 'nullable|string',
            'observation5' => 'nullable|string',
            'observation6' => 'nullable|string',
            'performance' => 'nullable|string',
            'performance2' => 'nullable|string',
            'performance3' => 'nullable|string',
            'performance4' => 'nullable|string',
            'performance5' => 'nullable|string',
            'performance6' => 'nullable|string',
            'attitude' => 'nullable|string',
            'attitude2' => 'nullable|string',
            'attitude3' => 'nullable|string',
            'attitude4' => 'nullable|string',
            'attitude5' => 'nullable|string',
            'attitude6' => 'nullable|string',
            'result' => 'nullable|string',
            'result2' => 'nullable|string',
            'result3' => 'nullable|string',
            'result4' => 'nullable|string',
        ]);

        $values = [
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
        ];

        try {

              $data = [
                "ResponseCode" => "100",
                "message" => "Progress Report Successfully."
            ];

            event(new StudentReport($data,$parent->token));


            $submitClassScheduleTime = DB::table('progressReport')->insertGetId($values);

            return response()->json([
                'ResponseCode' => '100',
                'Message' => 'Progress Report Submitted Successfully',
                'data' => $submitClassScheduleTime,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ResponseCode' => '103',
                'error' => 'An error occurred while submitting the progress report.',
                'details' => $e->getMessage(),
            ]);
        }
    }

    public function blogs(Request $req)
    {
        // Validate that the token is present in the request
        if (!$req->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($req->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }


        $token = $req->token;
        $parent = DB::table('customers')->where('token', $token)->first();

        // Return error response if no parent found
        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        // Retrieve and format blogs
        // $blogs = Blog::orderBy("id", "desc")->get();
        
        // Retrieve and format blogs where is_deleted is 0 (not deleted)
        $blogs = Blog::where('is_deleted', 0)
            ->orderBy('id', 'desc')
            ->get();


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

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Blogs retrieved successfully',
            'data' => $blogs
        ]);
    }

    public function blogsDetails(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }


        // Validate that the ID is provided
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the ID field. Please fill it in.',
            ]);
        }

        $token = $request->token;
        $id = $request->id;
        $parent = DB::table('customers')->where('token', $token)->first();

        // Return error response if no parent found
        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        // Retrieve the blog details by ID
        $blog = Blog::find($id);

        if ($blog) {
            $blog->image = url("/public/MobileBlogImages" . "/" . $blog->headerimage);

            // Format the created_at field
            $blog->date_time = Carbon::parse($blog->created_at)->format('d M Y | h:i A');

            return response()->json([
                'ResponseCode' => '100',
                'msg' => 'Blog details retrieved successfully',
                'data' => $blog
            ]);
        } else {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No blog found',
                'data' => []
            ]);
        }
    }

    public function getSubjects(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }


        $token = $request->token;
        $parent = DB::table('customers')->where('token', $token)->first();

        // Return error response if no parent found
        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        $products = DB::table('products')
            ->join('categories', 'products.category', '=', 'categories.id')
            ->select('products.*', 'categories.mode as mode', 'categories.category_name as category_name')
            ->orderBy('name', 'asc')
            ->get();

        if ($products->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'Data Not Found'
            ]);
        }

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Data retrieved successfully.',
            'data' => $products
        ]);
    }

    public function getCategories(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }


        $token = $request->token;
        $parent = DB::table('customers')->where('token', $token)->first();

        // Return error response if no parent found
        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        $categories = DB::table('categories')->orderBy('category_name', 'asc')->get();

        if ($categories->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'Data Not Found'
            ]);
        }

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Data retrieved successfully.',
            'data' => $categories
        ]);
    }

    public function getCategoriesByMode(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (!$request->has('mode')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the mode field. Please fill it in.',
            ]);
        }

        if (empty($request->mode)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the mode field. Please fill it in.',
            ]);
        }

        $token = $request->token;
        $mode = $request->mode;
        $parent = DB::table('customers')->where('token', $token)->first();

        // Return error response if no parent found
        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        $categories = DB::table('categories')->where('mode', $mode)->orderBy('category_name', 'asc')->get();

        if ($categories->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'Data Not Found'
            ]);
        }

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Data retrieved successfully.',
            'data' => $categories
        ]);
    }

    public function getSubjectsByLevel(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'It looks like you missed the token field. Please fill it in.',
            ]);
        }


        $token = $request->token;
        $id = $request->id;
        $parent = DB::table('customers')->where('token', $token)->first();

        // Return error response if no parent found
        if (!$parent) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Parent found!',
            ]);
        }

        // Check if the category exists
        $category = DB::table('categories')->where('id', $id)->first();

        if (!$category) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'Data Not Found'
            ]);
        }

        // Retrieve the products associated with the category
        $products = DB::table('products')
            ->join('categories', 'products.category', '=', 'categories.id')
            ->select(
                'products.*',
                'categories.mode as mode',
                'categories.category_name as category_name',
            DB::raw("CONCAT('" . asset('public/images/products') . "/', products.image) as image_url") // Full URL for image
            )
            ->where('categories.id', $id)
            ->orderBy('name', 'asc')
            ->get();

        if ($products->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'Data Not Found'
            ]);
        }

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Data retrieved successfully.',
            'data' => $products
        ]);
    }


    #function not use in route

    // public function sendMessage(Request $request)
    // {
    //     $chat = new Chat();
    //     $chat->from = $request->from;
    //     $chat->to = $request->to;
    //     $chat->message = $request->message;
    //     $chat->save();
    //     return response()->json(["message" => "Message Sent Successfully"], 200);

    // }

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

    private function sendVerificationMessage($phone, $message)
    {
        $whatsapp_api = new WhatsappApi();
        $sms_api = new SmsNiagaApi();
        $whatsapp_api->send_message($phone, $message);
        $sms_api->sendSms($phone, $message);
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
            ]);
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
                    ]);
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
    
    // private function sendMessage($phone, $smsmessage)
    // {
    //     $sms_api = new SmsNiagaApi();
    //     $sms_api->sendSms($phone, $smsmessage);
    // }


}
