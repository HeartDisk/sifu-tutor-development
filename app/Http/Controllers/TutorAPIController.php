<?php

namespace App\Http\Controllers;

use App\Events\Parent\SingleParentDashboard;
use App\Events\Tutor\TutorDashboard;
use App\Events\Parent\ParentDashbaord;
use App\Events\Parent\ParentNotification;
use App\Events\Parent\ClassSchedule as ParentClassSchedule;
use App\Events\Tutor\ClassSchedule as TutorClassSchedule;
use App\Events\Tutor\TutorOffers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use DB;
use DateTime;
use DateInterval;
use Auth;
use App\Jobs\SendPushNotificationJob;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Libraries\WhatsappApi;
use App\Libraries\SmsNiagaApi;
use App\Libraries\PushNotificationLibrary;
use Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Pusher\Pusher;
use App\Mail\InvoiceMail;
use App\Mail\TutorRegistrationMail;
use App\Mail\TutorVerificationCode;
use App\Mail\AttendanceReportMail;
use App\Mail\ParentProgressReportMail;
use App\Mail\EvaluationReportMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Dirape\Token\Token;
use App\Events\Parent\JobTicket;
use App\Events\MobileHomePageUpdated;
use App\Events\Parent\ClassAttendance;
use App\Events\Parent\StudentReport;


class TutorAPIController extends Controller
{

    public function __construct(Request $request)
    {
        // dd($request->all());
        // Log the request details
        Log::channel('api')->info('API Request:', [
            'ip' => $request->ip(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'headers' => $request->header(),
            'body' => $request->all()
        ]);
    }

    public function loginAPI(Request $req)
    {
        if ($req->phone == null) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => "Missing phone field"
            ]);
        }
        $phone = $req->phone;

        // Fetch tutor details based on the phone number
        $tutorDetail = DB::table('tutors')->where('phoneNumber', '=', $phone)->first();

        if (!$tutorDetail) {
            // Handle case where tutor is not found
            $uuidForTutor = rand(100, 99999);
            $values = [
                'uid' => 'TU-' . $uuidForTutor,
                'tutor_id' => 'TU-' . $uuidForTutor,
                'phoneNumber' => $phone,
                'status' => 'unverified',
                'whatsapp' => $phone,
                'token' => (new Token())->Unique('tutors', 'token', 60)
            ];
            $tutorLastID = DB::table('tutors')->insertGetId($values);
            $tutorDetail = DB::table('tutors')->where('id', '=', $tutorLastID)->first();
            $SixDigitRandomNumber = rand(10000, 99999);

            DB::table('tutorVerificationCode')->where('tutorID', $tutorDetail->id)->delete();
            $valuesVC = [
                'tutorID' => $tutorLastID,
                'code' => $SixDigitRandomNumber,
                'token' => bin2hex(random_bytes(16)),
            ];
            DB::table('tutorVerificationCode')->insertGetId($valuesVC);

            $message = "Your verification code is $SixDigitRandomNumber. It’s valid for 10 minutes.";
            $this->sendVerificationMessage($phone, $message);

            return response()->json([
                'ResponseCode' => '100',
                'message' => "Registration Successful. Enter verification code to continue",
                'data' => [
                    'tutor_id' => $tutorDetail->id,
                    'contact' => $tutorDetail->phoneNumber,
                    'token' => $tutorDetail->token
                ]
            ]);
        }

        // Handle cases where tutor is found
        if ($tutorDetail->status == 'terminated') {
            return response()->json([
                'ResponseCode' => '104',
                'error' => "Your Account has been terminated."

            ]);
        }

        if ($tutorDetail->status == 'resigned') {
            return response()->json([
                'ResponseCode' => '104',
                'error' => "You have resigned."

            ]);
        }

        if ($tutorDetail->status == 'inactive') {
            return response()->json([
                'ResponseCode' => '104',
                'error' => "Your Account is inactive."

            ]);
        }

        $SixDigitRandomNumber = rand(10000, 99999);
        $values = [
            'tutorID' => $tutorDetail->id,
            'code' => $SixDigitRandomNumber,
            'token' => bin2hex(random_bytes(16)),
        ];

        $tutorVerificationCodeCheck = DB::table('tutorVerificationCode')->where("tutorID", $tutorDetail->id)->first();

        if (!$tutorVerificationCodeCheck) {
            DB::table('tutorVerificationCode')->insertGetId($values);
        } else {
            DB::table('tutorVerificationCode')->where("tutorID", $tutorDetail->id)->update($values);
        }

        $message = "Your verification code is $SixDigitRandomNumber. It’s valid for 10 minutes.";
        $this->sendVerificationMessage($phone, $message);

        return response()->json([
            'ResponseCode' => '100',
            'message' => "Welcome back. Enter verification code to continue",
            'data' => [
                'tutor_id' => $tutorDetail->id,
                'contact' => $tutorDetail->phoneNumber,
                'token' => $tutorDetail->token
            ]
        ]);
    }

    public function appTutorRegister(Request $request)
    {
              
        // Validate that the token is present and not empty
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing token field',
            ]);
        }
        
        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Empty token field',
            ]);
        }
        
        // Define required fields with display names
        $requiredFields = [
            'email' => 'email',
            'phone_number' => 'phone_number',
            'full_name' => 'full_name',
            'display_name' => 'display_name',
        ];
        
        // Check for missing fields
        foreach ($requiredFields as $field => $displayName) {
            if (is_null($request->$field) || trim($request->$field) === '') {
                return response()->json([
                    'ResponseCode' => '102',
                    'error' => "Missing / Empty ($displayName) field"
                ]);
            }
        }
        
        // Check if the email already exists in the tutors table
        $tutorWithEmail = DB::table('tutors')->where('email', $request->email)->first();
        
        if ($tutorWithEmail) {
            if ($tutorWithEmail->token === $request->token) {
                // If the token matches, update the tutor's record
                DB::table('tutors')
                    ->where('token', $request->token)
                    ->update([
                        'email' => $request->email,
                        'phoneNumber' => $request->phone_number,
                        'full_name' => $request->full_name,
                        'displayName' => $request->display_name,
                        'status' => "unverified",
                    ]);
        
                // Send email notification
                $to = $request->email;
                Mail::to($to)->send(new TutorRegistrationMail());
        
                // Retrieve updated tutor details
                $tutorDetail = DB::table('tutors')->where('token', $request->token)->first();
        
                return response()->json([
                    'ResponseCode' => '100',
                    'message' => 'Tutor details updated successfully.',
                    'data' => $tutorDetail
                ]);
            } else {
                // Email exists but with a different token
                return response()->json([
                    'ResponseCode' => '104',
                    'error' => "Duplicate entry email already exists in another account"
                ]);
            }
        }
        
        // If the email does not exist in the tutors table, register the tutor
        // Assuming 'tutors' table is the target for registration
        // DB::table('tutors')->insert([
        //     'token' => $request->token,
        //     'email' => $request->email,
        //     'phoneNumber' => $request->phone_number,
        //     'full_name' => $request->full_name,
        //     'displayName' => $request->display_name,
        //     'status' => "unverified",
        // ]);
        
         DB::table('tutors')
                ->where('token', $request->token)
                ->update([
                    'email' => $request->email,
                    'phoneNumber' => $request->phone_number,
                    'full_name' => $request->full_name,
                    'displayName' => $request->display_name,
                    'status' => "unverified",
                ]);
        
        // Send email notification
        $to = $request->email;
        Mail::to($to)->send(new TutorRegistrationMail());
        
        // Retrieve newly created tutor details
        $tutorDetail = DB::table('tutors')->where('token', $request->token)->first();
        
        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Tutor registered successfully.',
            'data' => $tutorDetail
        ]);


    }
    
    public function direct(Request $request){
        
        // Send email notification
        $to = 'aasim.creative@gmail.com';
        Mail::to($to)->send(new TutorRegistrationMail());
    }

    public function getTutorDetailByID(Request $req)
    {
        // Validate that the token is present in the request
        if (!$req->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing token field',
            ]);
        }

        if (empty($req->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Empty token field',
            ]);
        }

        // Retrieve tutor details by unique code
        $tutorDetailByCode = DB::table('tutors')->where('token', '=', $req->token)->first();

        // Check if tutor details are found
        if (!$tutorDetailByCode) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'Tutor not found'
            ]);
        }

        // Check and set tutor image URL
        if ($tutorDetailByCode->tutorImage == null) {
            $tutorDetailByCode->tutorImage = url("/public/person_place_holder.png");
        } else {
            $tutorDetailByCode->tutorImage = $tutorDetailByCode->tutorImage;
        }

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Tutor details retrieved successfully.',
            'data' => $tutorDetailByCode
        ]);
    }

    public function verificationCode(Request $req)
    {
        // Validate that the token is present in the request
        if (!$req->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing token field',
            ]);
        }

        if (empty($req->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Empty token field',
            ]);
        }

        // Validate code and process accordingly
        if ($req->code == 12345) {
            // Retrieve tutor using unique code
            $tutorCheck = DB::table('tutors')->where('token', $req->token)->first();

            if ($tutorCheck) {
                // Update last login time
                DB::table('tutors')->where('token', $req->token)
                    ->update(['last_login' => date('Y-m-d H:i')]);

                // Retrieve updated tutor details
                $tutor = DB::table('tutors')->where('token', $req->token)
                    ->orderBy('id', 'desc')
                    ->first();


                return response()->json([
                    'ResponseCode' => '100',
                    'message' => 'Data retrieved successfully.',
                    'data' => [
                        'tutorID' => $tutor->uid,
                        'contact' => $tutor->phoneNumber,
                        'Tutorstatus' => $tutor->status,
                        'token' => $tutor->token,
                    ]
                ]);
            } else {
                return response()->json([
                    'ResponseCode' => '104',
                    'error' => 'Tutor not Found.'
                ]);
            }
        } else {
            $tutor = DB::table('tutors')->where('token', $req->token)->first();

            if ($tutor) {
                $verificationCode = DB::table('tutorVerificationCode')
                    ->where('code', '=', $req->code)
                    ->where('tutorID', '=', $tutor->id)
                    ->orderBy('id', 'desc')
                    ->first();

                if ($verificationCode) {
                    // Update last login time
                    DB::table('tutors')->where('token', $req->token)
                        ->update(['last_login' => date('Y-m-d H:i')]);

                    // Retrieve updated tutor details
                    $tutor = DB::table('tutors')->where('token', $req->token)
                        ->orderBy('id', 'desc')
                        ->first();

                    return response()->json([
                        'ResponseCode' => '100',
                        'message' => 'Data retrieved successfully.',
                        'data' => [
                            'tutorID' => $tutor->uid,
                            'contact' => $tutor->phoneNumber,
                            'Tutorstatus' => $tutor->status,
                            'token' => $tutor->token,
                        ]
                    ]);
                } else {
                    return response()->json([
                        'ResponseCode' => '104',
                        'error' => 'Sorry, code didn’t match! Please try again.'

                    ]);
                }
            } else {
                return response()->json([
                    'ResponseCode' => '104',
                    'error' => 'Tutor not Found.'
                ]);
            }
        }
    }

    public function StoreDeviceToken(Request $req)
    {
        // Validate that the token is present in the request
        if (!$req->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing token field',
            ]);
        }

        if (empty($req->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Empty token field',
            ]);
        }

        // Validate that the device_token is present in the request
        if (!$req->has('device_token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing device_token field',
            ]);
        }

        if (empty($req->device_token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Empty device_token field',
            ]);
        }

        $device_token = $req->device_token;
        $token = $req->token;

        // Fetch customer information
        $tutor = DB::table('tutors')->where('token', $token)->first();

        if ($tutor === null) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Tutor found!',
            ]);
        }

        $values = array(
            'tutor_id' => $tutor->id,
            'device_token' => $device_token
        );

        $existingToken = DB::table('tutor_device_tokens')
            ->where('tutor_id', $tutor->id)
            ->first();

        if ($existingToken) {
            // Update existing token
            DB::table('tutor_device_tokens')
                ->where('tutor_id', $tutor->id)
                ->update(['device_token' => $device_token]);

            $tutorDeviceToken = DB::table('tutor_device_tokens')
                ->where('tutor_id', $tutor->id)
                ->first();
        } else {
            // Insert new token
            $deviceTokenLastID = DB::table('tutor_device_tokens')->insertGetId($values);

            $tutorDeviceToken = DB::table('tutor_device_tokens')
                ->where('id', '=', $deviceTokenLastID)
                ->first();
        }

        return Response::json([
            'ResponseCode' => '100',
            'message' => 'Device Token Saved successfully.',
            'data' => $tutorDeviceToken
        ]);

    }

    public function getCategories(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing token field',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Empty token field',
            ]);
        }

        $token = $request->token;
        $tutor = DB::table('tutors')->where('token', $token)->first();

        // Return error response if no tutor found
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Tutor found!',
            ]);
        }

        // Retrieve categories
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
                'error' => 'Missing token field',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Empty token field',
            ]);
        }

        if (!$request->has('mode')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing mode field',
            ]);
        }

        if (empty($request->mode)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Empty mode field',
            ]);
        }

        $token = $request->token;
        $mode = $request->mode;
        $tutor = DB::table('tutors')->where('token', $token)->first();

        // Return error response if no tutor found
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Tutor found!',
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

    public function getStates(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing token field',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Empty token field',
            ]);
        }

        $token = $request->token;
        $tutor = DB::table('tutors')->where('token', $token)->first();

        // Return error response if no tutor found
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Tutor found!',
            ]);
        }

        // Retrieve states
        $states = DB::table('states')->orderBy('name', 'asc')->get();

        if ($states->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'Data Not Found'
            ]);
        }

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Data retrieved successfully.',
            'data' => $states
        ]);
    }

    public function getCities(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing token field',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Empty token field',
            ]);
        }

        $token = $request->token;
        $tutor = DB::table('tutors')->where('token', $token)->first();

        // Return error response if no tutor found
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Tutor found!',
            ]);
        }

        // Retrieve cities
        $cities = DB::table('cities')->orderBy('name', 'asc')->get();

        if ($cities->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'Data Not Found'
            ]);
        }

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Data retrieved successfully.',
            'data' => $cities
        ]);
    }

    public function getSubjects(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing token field',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Empty token field',
            ]);
        }

        $token = $request->token;
        $tutor = DB::table('tutors')->where('token', $token)->first();

        // Return error response if no tutor found
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Tutor found!',
            ]);
        }

        // Retrieve subjects (products)
        $products = DB::table('products')
            ->join('categories', 'categories.id', '=', 'products.category')
            ->select('products.*', 'categories.mode')
        ->orderBy('products.name', 'asc')
        ->get();
        
        foreach ($products as $key => $product) {

            $products[$key]->subject_name = $product->name." - ".$product->mode;
        }

        
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

    public function getSubjectsByLevel(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing token field',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Empty token field',
            ]);
        }


        $token = $request->token;
        $id = $request->id;
        $tutor = DB::table('tutors')->where('token', $token)->first();

        // Return error response if no tutor found
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Tutor found!',
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
                'categories.category_name as category_name'
            )
            ->where('categories.id', $id)
            ->orderBy('products.name', 'asc')
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

    public function tutorPayments(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing token field',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Empty token field',
            ]);
        }

        $token = $request->token;
        $tutor = DB::table('tutors')->where('token', $token)->first();

        // Return error response if no tutor found
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No tutor found',
            ]);
        }

        // Retrieve tutor payments
        $tutorPayments = DB::table('tutorpayments')
            ->select('*', DB::raw("DATE_FORMAT(paymentDate, '%e %b %Y') as paymentDate"))
            ->where('tutorID', $tutor->id)  // Use the tutor's ID from the token
            ->get();

        if ($tutorPayments->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No payments found for this tutor.'
            ]);
        }

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Data retrieved successfully.',
            'data' => $tutorPayments
        ]);
    }

    public function jobTicketDetails(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing token field',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Empty token field',
            ]);
        }

        $token = $request->token;
        $tutor = DB::table('tutors')->where('token', $token)->first();

        // Return error response if no tutor found
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No tutor found!',
            ]);
        }

        $tickets = DB::table('job_tickets')
            ->join('products', 'products.id', '=', 'job_tickets.subjects')
            ->join('categories', 'categories.id', '=', 'products.category')
            ->join('students', 'students.id', '=', 'job_tickets.student_id')
            ->join('customers', 'customers.id', '=', 'students.customer_id')
            ->join('cities', 'customers.city', '=', 'cities.id')
            ->join('states', 'customers.state', '=', 'states.id')
            ->where('job_tickets.uid', $request->ticket_id) // Use the ticket_id from request
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
                'job_tickets.per_class_commission_before_eight_hours as per_class_commission_before_eight_hours',
                'job_tickets.per_class_commission_after_eight_hours as per_class_commission_after_eight_hours',
                'job_tickets.extra_student_tutor_commission as extraFee',
                'job_tickets.estimate_commission_display_tutor',
                'products.id as subject_id',
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
            )
            ->orderBy('job_tickets.id', 'DESC')
            ->get();

        // Check if tickets are empty
        if ($tickets->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No tickets found!',
            ]);
        }

        $resultData = [];

        foreach ($tickets as $ticket) {
            $inputString = $ticket->classDay;
            $outputString = stripslashes(trim($inputString, "\""));

            $totalHours = $ticket->classFrequency * $ticket->quantity;
            $hoursBeforeEight = min($totalHours, 8);
            $hoursAfterEight = max($totalHours - 8, 0);
            
            // Check if the tutor has applied for this ticket
            $ifExist = DB::table('tutoroffers')
                        ->where('ticketID', '=', $ticket->ticketID)
                        ->where('tutorID', '=', $tutor->id) // Assuming 'tutor_id' is the current tutor's ID
                        ->first();
        
            // Determine if the tutor has applied
            $applied = $ifExist ? true : false;

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
                'display_price' => $ticket->estimate_commission_display_tutor,
                'extraFee' => $ticket->extraFee,
                'subscription' => $ticket->subscription,
                'classAddress' => $ticket->classAddress,
                'specialRequest' => $ticket->specialRequest,
                'subject_id' => $ticket->subject_id,
                'price' => $ticket->price,
                'studentName' => $ticket->studentName,
                'studentGender' => $ticket->studentGender,
                'studentAge' => $ticket->studentAge,
                'studentAddress' => $ticket->studentAddress,
                'city' => $ticket->city,
                'state' => $ticket->state,
                'cityID' => $ticket->cityID,
                'stateID' => $ticket->stateID,
                'categoryName' => $ticket->categoryName,
                'categoryID' => $ticket->categoryID,
                'jobTicketExtraStudents' => [],
                'applied' => $applied,  // Add applied status (true/false)
            ];

            $days = explode(',', str_replace('"', '', $ticket->classDay));
            $ticketData['classDayType'] = (in_array('Sat', $days) || in_array('Sun', $days)) ? 'weekend' : 'weekday';

            $students = DB::table('job_ticket_students')->where('job_ticket_id', $ticket->ticketID)->get();

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

            // Add the count of jobTicketExtraStudents
            $ticketData['extraStudentCount'] = count($ticketData['jobTicketExtraStudents']);

            $resultData[] = $ticketData;
        }
        

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Data retrieved successfully.',
            'data' => $resultData
        ]);
    }

    public function tutorFirstReportListing(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token') || empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing or empty token field',
            ]);
        }

        $token = $request->token;
        $tutor = DB::table('tutors')->where('token', $token)->first();

        // Return error response if no tutor found
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No tutor found!',
            ]);
        }

        $baseUrl = url("/template/") . "/";

        $tutorReportListing = DB::table('tutorFirstSubmittedReportFromApps')
            ->join('students', 'tutorFirstSubmittedReportFromApps.studentID', '=', 'students.id')
            ->join('tutors', 'tutorFirstSubmittedReportFromApps.tutorID', '=', 'tutors.id')
            ->join('products', 'tutorFirstSubmittedReportFromApps.subjectID', '=', 'products.id')
            ->where('tutorFirstSubmittedReportFromApps.tutorID', '=', $tutor->id)
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
                DB::raw("DATE_FORMAT(tutorFirstSubmittedReportFromApps.created_at, '%d-%b-%Y') as submittedDate")
            )
            ->get();

        // Check if no reports found
        if ($tutorReportListing->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No reports found.',
            ]);
        }

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Data retrieved successfully.',
            'data' => $tutorReportListing
        ]);
    }

    public function submitProgressReport(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token') || empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing or empty token field',
            ]);
        }

        // Retrieve the tutor based on the token
        $tutor = DB::table("tutors")->where('token', $request->token)->first();

        // Check if the tutor exists
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'Tutor not found',
            ]);
        }

        // Validate all required fields
        $requiredFields = [
            'student_id' => 'student_id',
            'subject_id' => 'subject_id',
            'month' => 'month',
            'observation' => 'observation',
            'observation2' => 'observation2',
            'observation3' => 'observation3',
            'observation4' => 'observation4',
            'observation5' => 'observation5',
            'observation6' => 'observation6',
            'performance' => 'performance',
            'performance2' => 'performance2',
            'performance3' => 'performance3',
            'performance4' => 'performance4',
            'performance5' => 'performance5',
            'performance6' => 'performance6',
            'attitude' => 'attitude',
            'attitude2' => 'attitude2',
            'attitude3' => 'attitude3',
            'attitude4' => 'attitude4',
            'attitude5' => 'attitude5',
            'attitude6' => 'attitude6',
            'result' => 'result',
            'result2' => 'result2',
            'result3' => 'result3',
            'result4' => 'result4',
        ];

        foreach ($requiredFields as $field => $displayName) {
            if (is_null($request->$field)) {
                return response()->json([
                    'ResponseCode' => '102',
                    'error' => "Missing ($displayName) field"
                ]);
            }
        }
        
        // Retrieve the scheduleID from the class_schedules table
        $schedule = DB::table('class_schedules')
            ->where('tutorID', $tutor->id)
            ->where('studentID', $request->student_id)
            ->where('subjectID', $request->subject_id)
            ->first();
    
        if (!$schedule) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'Schedule not found for the provided tutor, student and subject',
            ]);
        }

        // Prepare values for insertion, using 'tutorID' from the retrieved tutor
        $values = [
            'tutorID' => $tutor->id, // Derived from token
            'studentID' => $request->student_id,
            'subjectID' => $request->subject_id,
            'scheduleID' => $schedule->class_schedule_id,
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
            // Insert the progress report into the database
            $submitProgressReport = DB::table('progressReport')->insertGetId($values);
        } catch (\Exception $e) {
            // Handle insertion failure
            return response()->json([
                'ResponseCode' => '103',
                'error' => 'Failed to submit progress report. Please try again later.' . $e->getMessage(),
            ]);
        }
        
        // Send push notifications to tutor devices and parent devices
        $title = 'Progress Report Submitted';
        $message = 'Progress Report Submitted Successfully';
        
        $notificationdata = [
            'Sender' => 'Notifications'
        ];
        
        $tutorDevices = DB::table('tutor_device_tokens')->where('tutor_id', '=', $tutor->id)->distinct()->get(['device_token', 'tutor_id']);
        foreach ($tutorDevices as $rowDeviceToken) {
            $deviceToken = $rowDeviceToken->device_token;
        
            // Dispatch push notification job
            SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
        }
        
        $students = DB::table('students')->where('id', $request->student_id)->first();
        
        $parenttitle = 'Progress Report';
        $parentmessage = $students->full_name.'s progress report is ready. View it now.';

        $customer = DB::table('customers')->where('id', $students->customer_id)->first();
        $parent_device_tokens = DB::table('parent_device_tokens')->where('parent_id', '=', $customer->id)->distinct()->get(['device_token', 'parent_id']);
        foreach ($parent_device_tokens as $token) {
            $deviceToken = $token->device_token;
            // Dispatch push notification job
            SendPushNotificationJob::dispatch($deviceToken, $parenttitle, $parentmessage, $notificationdata);
        }
        
        // Store notification in the database
        DB::table('notifications')->insert([
            'page' => 'Notifications',
            'token' => $tutor->token,
            'title' => $title,
            'message' => $message,
            'type' => 'tutor',
            'status' => 'new',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Store notification in the database
        DB::table('notifications')->insert([
            'page' => 'Schedule',
            'token' => $customer->token,
            'title' => $parenttitle,
            'message' => $parentmessage,
            'type' => 'parent',
            'status' => 'new',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Retrieve the tutor, student, and parent details
        // $tutor = DB::table("tutors")->where('token', $request->token)->first();
        // $students = DB::table('students')->where('id', $request->student_id)->first();
        // $customer = DB::table('customers')->where('id', $students->customer_id)->first();
    
        if ($customer && $customer->email) {
            // Send the progress report email to the parent, including their name
            Mail::to($customer->email)->send(new ParentProgressReportMail($students->full_name, $tutor->full_name, $customer->full_name));
        }


        // Return a success response
        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Progress Report Submitted Successfully',
        ]);
    }

    public function progressReportListing(Request $request)
    {
        $baseUrl = rtrim(url("/template/"), '/') . '/';

        // Validate that the token is present in the request
        if (!$request->has('token') || empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing or empty token field',
            ]);
        }

        // Retrieve the tutor using the token
        $tutor = DB::table('tutors')->where('token', $request->token)->first();

        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'Invalid token or Tutor not found',
            ]);
        }

        // Fetch the progress report listings
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

        // Check if any reports were found
        if ($progressReportListing->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No progress reports found',
            ]);
        }

        // Return the progress report listings in the specified format
        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Data retrieved successfully.',
            'data' => $progressReportListing
        ]);

    }

    public function classScheduleStatusNotifications(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token') || empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing or empty token field',
            ]);
        }

        // Retrieve the tutor using the token
        $tutor = DB::table('tutors')->where('token', $request->token)->first();

        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'tutor not found',
            ]);
        }

        // Fetch class attended records for the tutor where status is NULL
        $attendedRecord = DB::table('class_attendeds')
            ->join('students', 'students.id', 'class_attendeds.studentID')
            ->join('products', 'products.id', 'class_attendeds.subjectId')
            ->select(
                'class_attendeds.*',
                'students.full_name as studentName',
                'products.name as subjectName'
            )
            ->where('class_attendeds.tutorID', '=', $tutor->id)
            ->whereNotNull('class_attendeds.endTime')
            ->whereNull('class_attendeds.status')
            ->get();

        // Check if no data found
        if ($attendedRecord->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No data found',
            ]);
        }

        // Return the attended record in the specified format
        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Data retrieved successfully.',
            'data' => $attendedRecord,
        ]);
    }

    public function getTutorStudents(Request $request)
    {
        // Validate the presence and non-empty value of the token
        if (!$request->has('token') || empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing or empty token field',
            ]);
        }

        // Retrieve the tutor using the token
        $tutor = DB::table('tutors')->where('token', $request->token)->first();

        // Check if the tutor exists
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'Invalid token or tutor not found',
            ]);
        }

        // Get the tutor ID from the retrieved tutor
        $tutorID = $tutor->id;

        // Retrieve the filtered class schedules
        // $filteredClassSchedules = DB::table('class_schedules')
        //     ->join('job_tickets', 'class_schedules.ticketID', '=', 'job_tickets.id')
        //     ->select('class_schedules.studentID')
        //     ->whereIn('class_schedules.id', function ($query) {
        //         $query->select(DB::raw('MAX(id)'))
        //             ->from('class_schedules')
        //             ->groupBy('ticketID');
        //     })
        //     ->where('class_schedules.class_schedule_id', '!=', 0)
        //     ->where('class_schedules.tutorID', '=', $tutorID)
        //     ->where('class_schedules.status',"!=" ,"scheduled")
        //     ->orwhere('class_schedules.status',"!=" ,"On going")
        //     ->orderBy('class_schedules.id', 'DESC')
        //     ->pluck('studentID');
        
        $filteredClassSchedules = DB::table('class_schedules')
            ->join('job_tickets', 'class_schedules.ticketID', '=', 'job_tickets.id')
            ->select('class_schedules.studentID')
            ->where('class_schedules.class_schedule_id', '!=', 0)
            ->where('class_schedules.tutorID', '=', $tutorID)
            ->whereNotIn('class_schedules.status', ['scheduled', 'On going'])
            ->orderBy('class_schedules.id', 'DESC')
            ->pluck('studentID');
        
        // Check if there are any student IDs
        if ($filteredClassSchedules->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No class schedules found for the given tutor.',
            ]);
        }


        // Retrieve the tutor student records
        $tutorStudentRecords = DB::table('students')
            ->join('class_schedules', 'students.id', '=', 'class_schedules.studentID')
            ->join('products', 'class_schedules.subjectID', '=', 'products.id')
            ->join('job_tickets', 'class_schedules.ticketID', '=', 'job_tickets.id')
            ->where('class_schedules.tutorID', '=', $tutorID)
            ->where('job_tickets.ticket_tutor_status', "Active")
            ->whereIn('students.id', $filteredClassSchedules)
            ->distinct()
            ->select(
                'products.name as subjectName',
                'students.id as studentID',
                'students.register_date as studentRegisterDate',
                'students.full_name as studentName',
                'class_schedules.ticketID as jobTicketId',
                'job_tickets.ticket_tutor_status as ticket_tutor_status',
                'job_tickets.admin_charge as admin_charge',
                'students.age as studentAge',
                'students.gender as studentGender',
                'students.student_id as uid',
                'students.reasonCategory as reasonCategory',
                'students.status as studentStatus',
                'students.reasonStatus as reasonStatus',
                DB::raw('(SELECT COUNT(DISTINCT subject) FROM student_subjects WHERE student_subjects.student_id = students.id) as subjectCount')
            )
            ->groupBy('students.id')
            ->get();

        // Check if there are any student records
        if ($tutorStudentRecords->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No student records found for the given tutor.',
            ]);
        }

        // Add additional information to each student record
        foreach ($tutorStudentRecords as $key => $record) {
            $jobTicketCheck = DB::table('job_tickets')->where('id', $record->jobTicketId)->first();

            if ($jobTicketCheck && $jobTicketCheck->ticket_tutor_status != 'discontinued') {
                $student = DB::table('students')->where('id', $record->studentID)->first();
                $customer = DB::table('customers')->where('id', $student->customer_id)->first();
                $city = DB::table('cities')->where('id', $customer->city)->first();

                if ($record->admin_charge != null) {
                    $staff = DB::table("staffs")->where("id", $record->admin_charge)->first();
                    $tutorStudentRecords[$key]->person_incharge = $staff->full_name;
                }

                $tutorStudentRecords[$key]->studentPhone = $customer->phone;
                $tutorStudentRecords[$key]->studentWhatsapp = $customer->whatsapp;
                $tutorStudentRecords[$key]->studentLatitude = $customer->latitude;
                $tutorStudentRecords[$key]->studentLongitude = $customer->longitude;
                $tutorStudentRecords[$key]->studentAddress1 = $customer->address1;
                $tutorStudentRecords[$key]->studentAddress2 = $customer->address2;
                $tutorStudentRecords[$key]->studentCity = $city->name;
                $tutorStudentRecords[$key]->uid = $student->student_id;
                $tutorStudentRecords[$key]->reasonStatus = $student->reasonStatus;
            }
        }

        // Return the response with tutor students
        return response()->json(['ResponseCode' => '100',
            "message" => "Data retrieved successfully",
            'data' => $tutorStudentRecords]);
    }

    public function getTutorSubjects(Request $request)
    {
        // Validate the presence and non-empty value of the token
        if (!$request->has('token') || empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing or empty token field',
            ]);
        }

        // Retrieve the tutor using the token
        $tutor = DB::table('tutors')->where('token', $request->token)->first();

        // Check if the tutor exists
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'tutor not found',
            ]);
        }

        // Get the tutor ID from the retrieved tutor
        $tutorID = $tutor->id;

        // Retrieve tutor subjects
        $tutorSubjects = DB::table('tutor_subjects')
            ->join('products', 'tutor_subjects.subject', '=', 'products.id')
            ->where('tutor_id', '=', $tutorID)
            ->get();

        // Check if there are any tutor subjects
        if ($tutorSubjects->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No subjects found for the given tutor.',
            ]);
        }

        return response()->json([
            'ResponseCode' => '100',
            "message" => "Subjects retrieved successfully",
            'data' => $tutorSubjects
        ]);
    }

    public function getUpcomingClassesByTutorID(Request $request)
    {
        // Validate the presence and non-empty value of the token
        if (!$request->has('token') || empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing or empty token field',
            ]);
        }

        // Retrieve the tutor using the token
        $tutor = DB::table('tutors')->where('token', $request->token)->first();

        // Check if the tutor exists
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'tutor not found',
            ]);
        }

        // Get the tutor ID from the retrieved tutor
        $tutorID = $tutor->id;

        // Retrieve upcoming class schedules for the tutor
        $classSchedules = DB::table('class_schedules')
            ->join('products', 'class_schedules.subjectID', '=', 'products.id')
            ->join('students', 'class_schedules.studentID', '=', 'students.id')
            ->join('job_tickets', 'class_schedules.ticketID', '=', 'job_tickets.id')
            ->join('customers', 'customers.id', '=', 'students.customer_id')
            ->join('cities', 'customers.city', '=', 'cities.id')
            ->where('class_schedules.tutorID', '=', $tutorID)
            ->where('class_schedules.status', '=', 'scheduled')
            ->where('class_schedules.class_schedule_id', '!=', 0)
            ->where('class_schedules.date', '>=', date("Y-m-d") . "%")
            ->whereTime('class_schedules.endTime', '>', date("H:i:s")) // Filter for endTime greater than current time
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
                DB::raw("DATE_FORMAT(class_schedules.startTime, '%h:%i %p') as startTime"),
                DB::raw("DATE_FORMAT(class_schedules.endTime, '%h:%i %p') as endTime"),
                'job_tickets.mode as mode',
                'job_tickets.day as day',
                'cities.name as city'
            )
            ->get();

        // Check if class schedules are found
        if ($classSchedules->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No upcoming classes found for the given tutor.',
            ]);
        }

        // Return the response with class schedules in 'data' key
        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Upcoming Classes retrieved successfully',
            'data' => $classSchedules
        ]);
    }

    public function newsAPI(Request $request)
    {
        // Validate the presence and non-empty value of the token
        if (!$request->has('token') || empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing or empty token field',
            ]);
        }

        $token = $request->input('token');

        // Validate token
        $tutor = DB::table('tutors')->where('token', $token)->first();

        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'tutor not found.',
            ]);
        }

        $baseUrl = url("/public/MobileNewsImages/") . "/";
        $news = DB::table('news')
            ->where('is_deleted', 0)
            ->where('type', '=', 'Tutor')
            ->select(
                '*',
                DB::raw("CONCAT('$baseUrl', headerimage) AS headerimage"),
                DB::raw("CONCAT(DATE_FORMAT(created_at, '%e %b, %Y'), ' | ', DATE_FORMAT(created_at, '%l:%i %p')) AS date_time")
            )
            ->orderBy("id", "desc")
            ->get();

        if ($news->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No news items found.',
            ]);
        }

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'News Retrieved Successfully',
            'data' => $news
        ]);
    }
    
    public function Reportnotifications(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing token field',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Empty token field',
            ]);
        }

        $token = $request->token;
        $tutor = DB::table('tutors')->where('token', $token)->first();

        // Return error response if no tutor found
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No tutor found!',
            ]);
        }

        // Retrieve reports_notifications for the tutor
        $reportsnotifications = DB::table('reports_notifications')
            ->leftJoin('students', 'reports_notifications.studentID', '=', 'students.id')
            ->leftJoin('tutors', 'reports_notifications.tutorID', '=', 'tutors.id')
            ->leftJoin('products', 'reports_notifications.subjectID', '=', 'products.id')
            ->where('reports_notifications.tutorID', '=', $tutor->id) // Use the tutor's ID from the token
            ->select(
                'reports_notifications.id as notificationID',
                'reports_notifications.notificationType as notificationType',
                'reports_notifications.status as status',
                'reports_notifications.message as notificationMessage',
                'reports_notifications.ProgressReportMonth as notificationProgressReportMonth',
                'reports_notifications.ticketID as ticketID',
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
                'students.city as studentCity'
            )
            ->get();

        if ($reportsnotifications->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No Report notifications found for this tutor.'
            ]);
        }

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Data retrieved successfully.',
            'data' => $reportsnotifications
        ]);
    }

    public function notifications(Request $request)
    {

        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing token field',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Empty token field',
            ]);
        }

        $token = $request->token;
        // Retrieve the tutor using the token
        $tutor = DB::table('tutors')->where('token', $request->token)->first();

        // Check if the tutor exists
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'tutor not found',
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
            ->where('type', '=', 'Tutor')
            // ->where('status', '=', 'new')
            ->select('*',
                DB::raw("CONCAT(DATE_FORMAT(created_at, '%e %b, %Y'), ' | ', DATE_FORMAT(created_at, '%l:%i %p')) AS date_time"))
            ->orderBy("id", "desc")
            ->get();

        // Check if any notifications data is returned
        if ($notifications->isEmpty()) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'No notifications found.',
            ]);
        }

        return response()->json([
            'ResponseCode' => 100,
            'message' => 'notifications fetched successfully.',
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
                    'error' => "Missing or empty $field field"
                ]);
            }
        }

        // Validate token
        $token = $request->input('token');
        // Retrieve the tutor using the token
        $tutor = DB::table('tutors')->where('token', $request->token)->first();

        // Check if the tutor exists
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'tutor not found',
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
                'error' => 'Missing token field',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Empty token field',
            ]);
        }

        // Validate that the id is present in the request
        if (!$request->has('id')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing id field',
            ]);
        }

        if (empty($request->id)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Empty id field',
            ]);
        }

        $id = $request->id;
        $token = $request->token;

        // Retrieve the tutor using the token
        $tutor = DB::table('tutors')->where('token', $request->token)->first();

        // Check if the tutor exists
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'tutor not found',
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
    
    // public function combinedNotifications(Request $request)
    // {
    //     // Validate that the token is present in the request
    //     if (!$request->has('token')) {
    //         return response()->json([
    //             'ResponseCode' => '102',
    //             'error' => 'Missing token field',
    //         ]);
    //     }
    
    //     if (empty($request->token)) {
    //         return response()->json([
    //             'ResponseCode' => '102',
    //             'error' => 'Empty token field',
    //         ]);
    //     }
    
    //     $token = $request->token;
    
    //     // Retrieve the tutor using the token
    //     $tutor = DB::table('tutors')->where('token', $token)->first();
    
    //     // Check if the tutor exists
    //     if (!$tutor) {
    //         return response()->json([
    //             'ResponseCode' => '101',
    //             'error' => 'tutor not found',
    //         ]);
    //     }
    
    //     // Retrieve notifications data from `notifications` table
    //     $notifications = DB::table('notifications')
    //         ->where(function ($query) use ($token) {
    //             $query->where('token', $token)
    //                 ->orWhereNull('token');
    //         })
    //         ->select(
    //             'id as NotificationID',
    //             DB::raw("'Notification' as NotificationType"),
    //             'page as Page',
    //             'title as Title',
    //             'message as Message',
    //             'status as Status',
    //             DB::raw("DATE_FORMAT(created_at, '%e %b, %Y | %l:%i %p') AS Date"),
    //             DB::raw("NULL as ExtraData") // Placeholder for consistent structure
    //         )
    //         ->where('type', 'Tutor')
    //         ->orderBy("id", "desc")
    //         ->get();
            
        
    //     // Retrieve reports notifications data from `reports_notifications` table
    //     $reportsNotifications = DB::table('reports_notifications')
    //         ->leftJoin('students', 'reports_notifications.studentID', '=', 'students.id')
    //         ->leftJoin('tutors', 'reports_notifications.tutorID', '=', 'tutors.id')
    //         ->leftJoin('products', 'reports_notifications.subjectID', '=', 'products.id')
    //         ->where('reports_notifications.tutorID', '=', $tutor->id)
    //         ->where('reports_notifications.status', '=', 'new')
    //         ->select(
    //             'reports_notifications.id as NotificationID',
    //             DB::raw("'ReportNotification' as NotificationType"),
    //             DB::raw("CASE 
    //                     WHEN reports_notifications.NotificationType = 'Submit Evaluation Report' 
    //                         THEN 'EvaluationReport'
    //                     WHEN reports_notifications.NotificationType = 'Submit Progress Report' 
    //                         THEN 'ProgressReport'
    //                     WHEN reports_notifications.NotificationType = 'Schedule Class'
    //                         THEN 'AddClass'
    //                     ELSE 'Other'
    //                  END as Page"),
    //             DB::raw("CASE 
    //                         WHEN reports_notifications.NotificationType = 'Submit Evaluation Report' 
    //                             THEN CONCAT('Evaluation Report for ', students.full_name) 
    //                         WHEN reports_notifications.NotificationType = 'Submit Progress Report' 
    //                             THEN CONCAT('Progress Report for ', students.full_name) 
    //                         WHEN reports_notifications.NotificationType = 'Schedule Class' 
    //                             THEN CONCAT('Class Scheduled for ', students.full_name) 
    //                         ELSE 'Notification' 
    //                      END as Title"),
    //             'reports_notifications.message as Message',
    //             'reports_notifications.status as Status',
    //             DB::raw("DATE_FORMAT(reports_notifications.created_at, '%e %b, %Y | %l:%i %p') AS Date"),
    //             DB::raw("CASE 
    //                 WHEN reports_notifications.NotificationType = 'Submit Evaluation Report' 
    //                     THEN JSON_OBJECT(
    //                         'StudentID', reports_notifications.studentID,
    //                         'SubjectID', reports_notifications.subjectID,
    //                         'StudentName', students.full_name,
    //                         'SubjectName', products.name
    //                     )
    //                 WHEN reports_notifications.NotificationType = 'Submit Progress Report'
    //                     THEN JSON_OBJECT(
    //                         'StudentID', reports_notifications.studentID,
    //                         'SubjectID', reports_notifications.subjectID,
    //                         'StudentName', students.full_name,
    //                         'SubjectName', products.name,
    //                         'Month', reports_notifications.ProgressReportMonth
    //                     )
    //                 WHEN reports_notifications.NotificationType = 'Schedule Class'
    //                     THEN JSON_OBJECT(
    //                         'StudentID', reports_notifications.studentID,
    //                         'SubjectID', reports_notifications.subjectID,
    //                         'StudentName', students.full_name,
    //                         'SubjectName', products.name
    //                     )
    //                 ELSE JSON_OBJECT()  -- Empty object for cases where no data is present
    //              END as ExtraData")
    //         )
    //         ->orderBy("reports_notifications.id", "desc")
    //         ->get();
            
    //     // Decode the ExtraData field from JSON string to array
    //     foreach ($reportsNotifications as $notification) {
    //         if (!is_null($notification->ExtraData)) {
    //             $notification->ExtraData = json_decode($notification->ExtraData, true); // Decode JSON to array
    //         }
    //     }
    
    //     // Merge both notification types into a single collection
    //     $allNotifications = $notifications->merge($reportsNotifications);
    
    //     // Check if any notifications data is returned
    //     if ($allNotifications->isEmpty()) {
    //         return response()->json([
    //             'ResponseCode' => '102',
    //             'error' => 'No notifications found.',
    //         ]);
    //     }
    
    //     return response()->json([
    //         'ResponseCode' => 100,
    //         'message' => 'Notifications fetched successfully.',
    //         'data' => $allNotifications
    //     ]);
    // }
    
    public function combinedNotifications(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing token field',
            ]);
        }
    
        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Empty token field',
            ]);
        }
    
        $token = $request->token;
    
        // Retrieve the tutor using the token
        $tutor = DB::table('tutors')->where('token', $token)->first();
    
        // Check if the tutor exists
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'tutor not found',
            ]);
        }
    
        // Retrieve notifications data from `notifications` table
        $notifications = DB::table('notifications')
            ->where(function ($query) use ($token) {
                $query->where('token', $token)
                    ->orWhereNull('token');
            })
            ->select(
                'id as NotificationID',
                DB::raw("'Notification' as NotificationType"),
                'page as Page',
                'title as Title',
                'message as Message',
                'status as Status',
                DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d %H:%i:%s') AS Date"),
                DB::raw("NULL as ExtraData") // Placeholder for consistent structure
            )
            ->where('type', 'Tutor')
            ->orderBy("id", "desc")
            ->get();
    
        // Retrieve reports notifications data from `reports_notifications` table
        $reportsNotifications = DB::table('reports_notifications')
            ->leftJoin('students', 'reports_notifications.studentID', '=', 'students.id')
            ->leftJoin('tutors', 'reports_notifications.tutorID', '=', 'tutors.id')
            ->leftJoin('products', 'reports_notifications.subjectID', '=', 'products.id')
            ->where('reports_notifications.tutorID', '=', $tutor->id)
            ->where('reports_notifications.status', '=', 'new')
            ->select(
                'reports_notifications.id as NotificationID',
                DB::raw("'ReportNotification' as NotificationType"),
                DB::raw("CASE 
                        WHEN reports_notifications.NotificationType = 'Submit Evaluation Report' 
                            THEN 'EvaluationReport'
                        WHEN reports_notifications.NotificationType = 'Submit Progress Report' 
                            THEN 'ProgressReport'
                        WHEN reports_notifications.NotificationType = 'Schedule Class'
                            THEN 'AddClass'
                        ELSE 'Other'
                     END as Page"),
                DB::raw("CASE 
                            WHEN reports_notifications.NotificationType = 'Submit Evaluation Report' 
                                THEN CONCAT('Evaluation Report for ', students.full_name) 
                            WHEN reports_notifications.NotificationType = 'Submit Progress Report' 
                                THEN CONCAT('Progress Report for ', students.full_name) 
                            WHEN reports_notifications.NotificationType = 'Schedule Class' 
                                THEN CONCAT('Class Scheduled for ', students.full_name) 
                            ELSE 'Notification' 
                         END as Title"),
                'reports_notifications.message as Message',
                'reports_notifications.status as Status',
                DB::raw("DATE_FORMAT(reports_notifications.created_at, '%Y-%m-%d %H:%i:%s') AS Date"),
                DB::raw("CASE 
                    WHEN reports_notifications.NotificationType = 'Submit Evaluation Report' 
                        THEN JSON_OBJECT(
                            'StudentID', reports_notifications.studentID,
                            'SubjectID', reports_notifications.subjectID,
                            'StudentName', students.full_name,
                            'SubjectName', products.name
                        )
                    WHEN reports_notifications.NotificationType = 'Submit Progress Report'
                        THEN JSON_OBJECT(
                            'StudentID', reports_notifications.studentID,
                            'SubjectID', reports_notifications.subjectID,
                            'StudentName', students.full_name,
                            'SubjectName', products.name,
                            'Month', reports_notifications.ProgressReportMonth
                        )
                    WHEN reports_notifications.NotificationType = 'Schedule Class'
                        THEN JSON_OBJECT(
                            'StudentID', reports_notifications.studentID,
                            'SubjectID', reports_notifications.subjectID,
                            'StudentName', students.full_name,
                            'SubjectName', products.name
                        )
                    ELSE JSON_OBJECT()  -- Empty object for cases where no data is present
                 END as ExtraData")
            )
            ->orderBy("reports_notifications.id", "desc")
            ->get();
    
        // Decode the ExtraData field from JSON string to array
        foreach ($reportsNotifications as $notification) {
            if (!is_null($notification->ExtraData)) {
                $notification->ExtraData = json_decode($notification->ExtraData, true); // Decode JSON to array
            }
        }
    
        // Merge both notification types into a single collection and sort by Date in descending order
        $allNotifications = $notifications->merge($reportsNotifications)->sortByDesc('Date');
    
        // Check if any notifications data is returned
        if ($allNotifications->isEmpty()) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'No notifications found.',
            ]);
        }
    
        return response()->json([
            'ResponseCode' => 100,
            'message' => 'Notifications fetched successfully.',
            'data' => $allNotifications->values()->all() // Reindex collection after sorting
        ]);
    }

    
    public function combinedNotificationsStatusUpdate(Request $request)
    {
        // Define required fields
        $requiredFields = ['NotificationID', 'NotificationType', 'status'];
    
        // Check for missing or empty fields
        foreach ($requiredFields as $field) {
            if (!$request->has($field) || empty($request->input($field))) {
                return response()->json([
                    'ResponseCode' => '102',
                    'error' => "Missing or empty $field field"
                ]);
            }
        }
    
        // Validate token
        $token = $request->input('token');
        // Retrieve the tutor using the token
        $tutor = DB::table('tutors')->where('token', $request->token)->first();
    
        // Check if the tutor exists
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'Tutor not found',
            ]);
        }
    
        // Retrieve NotificationID, NotificationType, and status from request
        $notificationID = $request->input('NotificationID');
        $notificationType = $request->input('NotificationType');
        $status = $request->input('status');
    
        // Determine the table based on NotificationType
        $table = $notificationType === 'Notification' ? 'notifications' : 'reports_notifications';
    
        // Check if the notification item exists in the appropriate table
        $notificationExists = DB::table($table)->where('id', $notificationID)->exists();
    
        if (!$notificationExists) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'Notification not found.'
            ]);
        }
    
        // Update the status based on the table
        $updatedRows = DB::table($table)->where('id', $notificationID)->update(['status' => $status]);
    
        // Check if the update was successful
        if ($updatedRows === 0) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No notification status records updated.'
            ]);
        }
    
        // Return success response
        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Notification status updated successfully.'
        ]);
    }


    public function faqsAPI(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing token field',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Empty token field',
            ]);
        }

    
        $token = $request->input('token');
    
        // Validate token
        $tutor = DB::table('tutors')->where('token', $token)->first();
    
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'Tutor not found.',
            ]);
        }
    
        $type = 'tutor'; // Can be 'tutor' or 'parent'
    
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
            'ResponseCode' => '100',
            'message' => 'FAQs Retrieved Successfully',
            'data' => $faqs
        ]);
    }
    
    public function policiesAPI(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing token field',
            ]);
        }
    
        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Empty token field',
            ]);
        }
    
        $token = $request->input('token');
    
        // Check if the token belongs to a tutor
        $tutor = DB::table('tutors')->where('token', $token)->first();
        
        // Return error response if no parent found
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No Tutor found!',
            ]);
        }
    
        // Define the type for policies (e.g., 'parent' or 'tutor')
        $type = 'tutor'; // Change this based on your logic
    
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
                'error' => 'No policies found for the selected type.',
            ]);
        }
    
        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Policies Retrieved Successfully',
            'data' => $policies
        ]);
    }



    public function bannerAds(Request $request)
    {
        // Validate the presence and non-empty value of the token
        if (!$request->has('token') || empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing or empty token field',
            ]);
        }

        $token = $request->input('token');

        // Validate token
        $tutor = DB::table('tutors')->where('token', $token)->first();

        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'tutor not found.',
            ]);
        }

        $baseUrl = url("/public/BannerImage/") . "/";

        $bannerAds = DB::table('bannerads')
            ->select('*', DB::raw("CONCAT('$baseUrl', bannerImage) AS bannerImage"))
            ->orderBy('id', 'desc')
            ->get();

        if ($bannerAds->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No banner ads found.',
            ]);
        }

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Banner Ads Retrieved Successfully',
            'data' => $bannerAds
        ]);
    }

    public function getDashboardValues(Request $request)
    {
        // Validate the presence and non-empty value of the token
        if (!$request->has('token') || empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing or empty token field',
            ]);
        }

        $token = $request->input('token');

        // Validate token and retrieve tutor ID
        $tutor = DB::table('tutors')->where('token', $token)->first();

        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'tutor not found.',
            ]);
        }

        $tutorID = $tutor->id;

        // Validate the presence and non-empty value of the filter_month
        if (!$request->has('filter_month') || empty($request->filter_month)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing or empty filter_month field',
            ]);
        }

        $filterMonth = $request->input('filter_month');

        // Convert month name to its numeric value
        $month = date('m', strtotime($filterMonth));
        $year = date('Y'); // Assuming the current year

        // Get Scheduled Hours
        $scheduledHours = DB::table('class_schedules')
            ->where('tutorID', '=', $tutorID)
            ->where('status', '=', 'scheduled')
            ->whereYear('created_at', '=', $year)
            ->whereMonth('created_at', '=', $month)
            ->sum('totalTime');
        $scheduledHours = number_format((float)$scheduledHours, 2, '.', '');

        // Get Attended Hours
        $queryResult = DB::table('class_attendeds')
            ->select(DB::raw('SUM(
                CASE
                    WHEN totalTime LIKE "%.%" THEN totalTime * 3600
                    ELSE TIME_TO_SEC(totalTime)
                END
            ) AS totalSeconds'))
            ->where([
                'tutorID' => $tutorID,
                'status' => 'attended',
                'is_paid' => 'unpaid',
                'is_tutor_paid' => 'unpaid'
            ])
            ->whereYear('created_at', '=', $year)
            ->whereMonth('created_at', '=', $month)
            ->first();

        $totalSeconds = $queryResult->totalSeconds ?? 0; // Default to 0 if null
        $attendedDurationInHours = $totalSeconds / 3600; // Convert seconds to hours
        $attendedHours = number_format($attendedDurationInHours, 2);

        // Get Cumulative Commission
        $class_attended = DB::table('class_attendeds')
            ->where(['tutorID' => $tutorID, 'status' => 'attended', 'is_paid' => 'unpaid', 'is_tutor_paid' => 'unpaid'])
            ->whereYear('created_at', '=', $year)
            ->whereMonth('created_at', '=', $month)
            ->get();

        $cumulativeCommission = 0;
        foreach ($class_attended as $rowClassAttended) {
            $cumulativeCommission += $rowClassAttended->commission;
        }

        $cumulativeCommissionRounded = number_format((float)$cumulativeCommission, 2, '.', '');
        
        // Get Total Active Students
        // $activeStudentsCount = DB::table('class_schedules')
        //     ->join('students', 'class_schedules.studentID', '=', 'students.id')
        //     ->where('class_schedules.tutorID', '=', $tutorID)
        //     ->whereIn('class_schedules.status', ['attended', 'pending', 'scheduled', 'On going'])
        //     ->whereYear('class_schedules.created_at', '=', $year)
        //     ->whereMonth('class_schedules.created_at', '=', $month)
        //     ->distinct()
        //     ->count('students.id');
        
        $activeStudentsCount = DB::table('students')
            ->join('class_schedules', 'students.id', '=', 'class_schedules.studentID')
            ->join('products', 'class_schedules.subjectID', '=', 'products.id')
            ->join('job_tickets', 'class_schedules.ticketID', '=', 'job_tickets.id')
            ->where('class_schedules.tutorID', '=', $tutorID) // Filter by tutor
            ->where('job_tickets.ticket_tutor_status', "Active")
            ->where('class_schedules.class_schedule_id', '!=', 0)
            ->whereNotIn('class_schedules.status', ['scheduled', 'On going'])
            ->distinct()
            ->select(
                'students.id as studentID',
                'students.full_name as studentName'
            )
            ->groupBy('students.id')
            ->orderBy('class_schedules.id', 'DESC') // Order by class schedule ID if needed
            ->count('students.id');

        // Return all values as JSON
        return response()->json([
            'ResponseCode' => '100',
            'data' => [
                'scheduledHours' => $scheduledHours,
                'attendedHours' => $attendedHours,
                'cumulativeCommission' => $cumulativeCommissionRounded,
            'totalActiveStudents' => $activeStudentsCount
            ]
        ]);
    }

    public function storeServicePreferences(Request $request)
    {
        // Validate the presence and non-empty value of the token
        if (!$request->has('token') || empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing or empty token field',
            ]);
        }

        $token = $request->input('token');

        // Validate token
        $tutor = DB::table('tutors')->where('token', $token)->first();

        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'tutor not found.',
            ]);
        }

        $tutor_id = $tutor->id;

        // Check for missing fields and include field names in the error message
        $missingFields = [];
        if (!$request->has('categories') || empty($request->categories)) $missingFields[] = 'categories';
        if (!$request->has('modes_of_tutoring') || empty($request->modes_of_tutoring)) $missingFields[] = 'modes_of_tutoring';
        if (!$request->has('preferable_locations') || empty($request->preferable_locations)) $missingFields[] = 'preferable_locations';
        if (!$request->has('teaching_experiences') || trim($request->teaching_experiences) === '') $missingFields[] = 'teaching_experiences';
        
        if (!empty($missingFields)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing required fields: ' . implode(', ', $missingFields),
            ]);
        }

        // Prepare the data for insertion
        $serviceData = [
            'category' => implode(', ', $request->categories),
            'mode_of_tutoring' => implode(', ', $request->modes_of_tutoring),
            'preferable_location' => implode(', ', $request->preferable_locations),
            'teaching_experience' => $request->teaching_experiences, // Assuming this is already a string
            'tutor_id' => $tutor_id, // Use the tutor_id for the foreign key
        ];

        // Check if record exists
        $existingRecord = DB::table('service_preferences')
            ->where('tutor_id', $tutor_id)
            ->first();

        if ($existingRecord) {
            DB::table('service_preferences')
                ->where('tutor_id', $tutor_id)
                ->update($serviceData);
        } else {
            DB::table('service_preferences')->insert($serviceData);
        }

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Service Preferences added successfully'
        ]);
    }
    
    public function getServicePreferences(Request $request)
    {
        // Validate the presence and non-empty value of the token
        if (!$request->has('token') || empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing or empty token field',
            ]);
        }
    
        $token = $request->input('token');
    
        // Validate token
        $tutor = DB::table('tutors')->where('token', $token)->first();
    
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'Tutor not found.',
            ]);
        }
    
        $tutor_id = $tutor->id;
    
        // Fetch service preferences data
        $data = DB::table('service_preferences')->where('tutor_id', $tutor_id)->get();
    
        if ($data->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No data found.',
            ]);
        }
    
        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Data Retrieved Successfully',
            'data' => $data,
        ]);
    }

    public function storeBioDetails(Request $request)
    {
        // Validate the presence and non-empty value of the token
        if (!$request->has('token') || empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing or empty token field',
            ]);
        }

        $token = $request->input('token');

        // Validate token
        $tutor = DB::table('tutors')->where('token', $token)->first();

        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'tutor not found.',
            ]);
        }

        $tutor_id = $tutor->id;

        // Check for missing fields and include field names in the error message
        $missingFields = [];
        if (!$request->has('full_name') || trim($request->full_name) === '') $missingFields[] = 'full_name';
        if (!$request->has('phone_number') || trim($request->phone_number) === '') $missingFields[] = 'phone_number';
        if (!$request->has('email') || trim($request->email) === '') $missingFields[] = 'email';
        if (!$request->has('ic_number') || trim($request->ic_number) === '') $missingFields[] = 'ic_number';
        if (!$request->has('residential_address') || trim($request->residential_address) === '') $missingFields[] = 'residential_address';
        if (!$request->has('postal_code') || trim($request->postal_code) === '') $missingFields[] = 'postal_code';
        
        if (!empty($missingFields)) {
            return response()->json([
                'ResponseCode' => '103',
                'error' => 'Missing required fields: ' . implode(', ', $missingFields),
            ]);
        }

        // Prepare the data for insertion
        $bioData = [
            'full_name' => $request->full_name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'ic_number' => $request->ic_number,
            'residential_address' => $request->residential_address,
            'postal_code' => $request->postal_code,
            'tutor_id' => $tutor_id, // Use the tutor_id obtained from token
        ];

        $tutorData = [
            'full_name' => $request->full_name,
            'phoneNumber' => $request->phone_number,
            'email' => $request->email,
            'nric' => $request->ic_number,
            'street_address1' => $request->residential_address,
            'postal_code' => $request->postal_code,
        ];

        // Check if bio details record exists
        $existingRecord = DB::table('bio_details')->where('tutor_id', $tutor_id)->first();

        if ($existingRecord) {
            DB::table('bio_details')->where('tutor_id', $tutor_id)->update($bioData);
            $message = "Bio details updated successfully";
        } else {
            DB::table('bio_details')->insert($bioData);
            $message = "Bio details added successfully";
        }

        // Update tutor record
        DB::table('tutors')->where('id', $tutor_id)->update($tutorData);

        return response()->json([
            'ResponseCode' => '100',
            'message' => $message,

        ]);
    }
    
    public function getBioDetails(Request $request)
    {
        // Validate the presence and non-empty value of the token
        if (!$request->has('token') || empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing or empty token field',
            ]);
        }
    
        $token = $request->input('token');
    
        // Validate token
        $tutor = DB::table('tutors')->where('token', $token)->first();
    
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'Tutor not found.',
            ]);
        }
    
        $tutor_id = $tutor->id;
    
        // Fetch bio details
        $data = DB::table('bio_details')->where('tutor_id', $tutor_id)->get();
    
        if ($data->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No data found.',
            ]);
        }
    
        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Data Retrieved Successfully',
            'data' => $data,
        ]);
    }

    public function storeEmergencyContact(Request $request)
    {
        // Validate the presence and non-empty value of the token
        if (!$request->has('token') || empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing or empty token field',
            ]);
        }

        $token = $request->input('token');

        // Validate token
        $tutor = DB::table('tutors')->where('token', $token)->first();

        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'tutor not found.',
            ]);
        }

        $tutor_id = $tutor->id;

        // Check for missing fields and include field names in the error message
        $missingFields = [];
        if (!$request->has('emergency_contact_name')) $missingFields[] = 'emergency_contact_name';
        if (!$request->has('relationship')) $missingFields[] = 'relationship';
        if (!$request->has('emergency_contact_number')) $missingFields[] = 'emergency_contact_number';

        if (!empty($missingFields)) {
            return response()->json([
                'ResponseCode' => '103',
                'error' => 'Missing required fields: ' . implode(', ', $missingFields),
            ]);
        }

        // Prepare the data for insertion
        $emergencyContactData = [
            'emergency_contact_name' => $request->emergency_contact_name,
            'relationship' => $request->relationship,
            'emergency_contact_number' => $request->emergency_contact_number,
            'tutor_id' => $tutor_id, // Use the tutor_id obtained from the token
        ];

        // Check if emergency contact record exists
        $existingRecord = DB::table('emergency_contacts')->where('tutor_id', $tutor_id)->first();

        if ($existingRecord) {
            DB::table('emergency_contacts')->where('tutor_id', $tutor_id)->update($emergencyContactData);
            $message = "Emergency contact updated successfully";
        } else {
            DB::table('emergency_contacts')->insert($emergencyContactData);
            $message = "Emergency contact added successfully";
        }

        return response()->json([
            'ResponseCode' => '100',
            'message' => $message,
        ]);
    }
    
    public function getEmergencyContact(Request $request)
    {
        // Validate the presence and non-empty value of the token
        if (!$request->has('token') || empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing or empty token field',
            ]);
        }
    
        $token = $request->input('token');
    
        // Validate token
        $tutor = DB::table('tutors')->where('token', $token)->first();
    
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'Tutor not found.',
            ]);
        }
    
        $tutor_id = $tutor->id;
    
        // Fetch emergency contact details
        $data = DB::table('emergency_contacts')->where('tutor_id', $tutor_id)->get();
    
        if ($data->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No data found.',
            ]);
        }
    
        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Data Retrieved Successfully',
            'data' => $data,
        ]);
    }

    public function storeEducation(Request $request)
    {
        // Validate the presence and non-empty value of the token
        if (!$request->has('token') || empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing or empty token field',
            ]);
        }

        $token = $request->input('token');

        // Validate token
        $tutor = DB::table('tutors')->where('token', $token)->first();

        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'tutor not found.',
            ]);
        }

        $tutor_id = $tutor->id;

        // Check for missing fields and include field names in the error message
        $missingFields = [];
        if (!$request->has('highest_education') || trim($request->highest_education) === '') $missingFields[] = 'highest_education';
        if (!$request->has('field_of_study') || trim($request->field_of_study) === '') $missingFields[] = 'field_of_study';
        if (!$request->has('academic_year') || trim($request->academic_year) === '') $missingFields[] = 'academic_year';
        if (!$request->has('institution_name') || trim($request->institution_name) === '') $missingFields[] = 'institution_name';
        
        if (!empty($missingFields)) {
            return response()->json([
                'ResponseCode' => '103',
                'error' => 'Missing required fields: ' . implode(', ', $missingFields),
            ]);
        }

        // Prepare the data for insertion
        $educationData = [
            'highest_education' => $request->highest_education,
            'field_of_study' => $request->field_of_study,
            'academic_year' => $request->academic_year,
            'institution_name' => $request->institution_name,
            'tutor_id' => $tutor_id, // Use the tutor_id obtained from the token
        ];

        // Check if education record exists
        $existingRecord = DB::table('educations')->where('tutor_id', $tutor_id)->first();

        if ($existingRecord) {
            DB::table('educations')->where('tutor_id', $tutor_id)->update($educationData);
            $message = "Education details updated successfully";
        } else {
            DB::table('educations')->insert($educationData);
            $message = "Education details added successfully";
        }

        return response()->json([
            'ResponseCode' => '100',
            'message' => $message,
        ]);
    }
    
    public function getEducationDetails(Request $request)
    {
        // Validate the presence and non-empty value of the token
        if (!$request->has('token') || empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing or empty token field',
            ]);
        }
    
        $token = $request->input('token');
    
        // Validate token
        $tutor = DB::table('tutors')->where('token', $token)->first();
    
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'Tutor not found.',
            ]);
        }
    
        $tutor_id = $tutor->id;
    
        // Fetch education details
        $data = DB::table('educations')->where('tutor_id', $tutor_id)->get();
    
        if ($data->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No data found.',
            ]);
        }
    
        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Data Retrieved Successfully',
            'data' => $data,
        ]);
    }

    // public function storeDocuments(Request $request)
    // {
    //     // Validate the presence and non-empty value of the token
    //     if (!$request->has('token') || empty($request->token)) {
    //         return response()->json([
    //             'ResponseCode' => '102',
    //             'message' => 'Missing or empty token field',
    //         ]);
    //     }

    //     $token = $request->input('token');

    //     // Validate token
    //     $tutor = DB::table('tutors')->where('token', $token)->first();
    //     if (!$tutor) {
    //         return response()->json([
    //             'ResponseCode' => '101',
    //             'message' => 'tutor not found.',
    //         ]);
    //     }

    //     // Initialize an array for missing fields
    //     $missingFields = [];

    //     // Define required file fields
    //     $requiredFiles = [
    //         'resume' => 'Resume',
    //         'education_transcript' => 'Education Transcript',
    //         'formal_photo' => 'Formal Photo',
    //         'identity_card_front' => 'Identity Card Front'
    //     ];
        
    //     // Check for missing files
    //     foreach ($requiredFiles as $file => $displayName) {
    //         if (!$request->hasFile($file) || !$request->file($file)->isValid()) {
    //             $missingFields[] = $displayName;
    //         }
    //     }
        
    //     if (!empty($missingFields)) {
    //         return response()->json([
    //             'ResponseCode' => '102',
    //             'message' => 'Missing files: ' . implode(', ', $missingFields),
    //         ]);
    //     }

    //     // Prepare the file paths for insertion
    //     $documentData = [];

    //     // Handle file uploads
    //     if ($request->hasFile('resume')) {
    //         $resume = $request->file('resume');
    //         $resumePath = $resume->store('documents/resumes', 'public');
    //         $documentData['resume_url'] = $resumePath;
    //     }

    //     if ($request->hasFile('education_transcript')) {
    //         $educationTranscript = $request->file('education_transcript');
    //         $educationTranscriptPath = $educationTranscript->store('documents/education_transcripts', 'public');
    //         $documentData['education_transcript_url'] = $educationTranscriptPath;
    //     }

    //     if ($request->hasFile('formal_photo')) {
    //         $formalPhoto = $request->file('formal_photo');
    //         $formalPhotoPath = $formalPhoto->store('documents/formal_photos', 'public');
    //         $documentData['formal_photo_url'] = $formalPhotoPath;
    //     }

    //     if ($request->hasFile('identity_card_front')) {
    //         $identityCardFront = $request->file('identity_card_front');
    //         $identityCardFrontPath = $identityCardFront->store('documents/identity_cards', 'public');
    //         $documentData['identity_card_front_url'] = $identityCardFrontPath;
    //     }

    //     // Check if record exists
    //     $existingRecord = DB::table('documents')->where('tutor_id', $tutor->id)->first();

    //     if ($existingRecord) {
    //         DB::table('documents')->where('tutor_id', $tutor->id)->update($documentData);
    //         $msg = "Document details updated successfully";
    //     } else {
    //         $documentData['tutor_id'] = $tutor->id;
    //         DB::table('documents')->insert($documentData);
    //         $msg = "Document details added successfully";
    //     }

    //     return response()->json([
    //         'ResponseCode' => '100',
    //         'message' => $msg,
    //         'data' => $documentData
    //     ]);
    // }
    
    public function storeDocuments(Request $request)
    {
        // Validate the presence and non-empty value of the token
        if (!$request->has('token') || empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'message' => 'Missing or empty token field',
            ]);
        }
    
        $token = $request->input('token');
    
        // Validate token
        $tutor = DB::table('tutors')->where('token', $token)->first();
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'message' => 'Tutor not found.',
            ]);
        }
    
        // Check if record exists
        $existingRecord = DB::table('documents')->where('tutor_id', $tutor->id)->first();
    
        // If inserting new record, check for required files
        if (!$existingRecord) {
            // Define required file fields
            $requiredFiles = [
                'resume' => 'Resume',
                'education_transcript' => 'Education Transcript',
                'formal_photo' => 'Formal Photo',
                'identity_card_front' => 'Identity Card Front'
            ];
            
            // Initialize an array for missing fields
            $missingFields = [];
            
            // Check for missing files
            foreach ($requiredFiles as $file => $displayName) {
                if (!$request->hasFile($file) || !$request->file($file)->isValid()) {
                    $missingFields[] = $displayName;
                }
            }
    
            if (!empty($missingFields)) {
                return response()->json([
                    'ResponseCode' => '102',
                    'message' => 'Missing files: ' . implode(', ', $missingFields),
                ]);
            }
        }
    
        // Prepare the file paths for insertion
        $documentData = [];
    
        // Handle file uploads
        if ($request->hasFile('resume')) {
            $resume = $request->file('resume');
            $resumePath = $resume->store('documents/resumes', 'public');
            $documentData['resume_url'] = $resumePath;
        }
    
        if ($request->hasFile('education_transcript')) {
            $educationTranscript = $request->file('education_transcript');
            $educationTranscriptPath = $educationTranscript->store('documents/education_transcripts', 'public');
            $documentData['education_transcript_url'] = $educationTranscriptPath;
        }
    
        if ($request->hasFile('formal_photo')) {
            $formalPhoto = $request->file('formal_photo');
            $formalPhotoPath = $formalPhoto->store('documents/formal_photos', 'public');
            $documentData['formal_photo_url'] = $formalPhotoPath;
        }
    
        if ($request->hasFile('identity_card_front')) {
            $identityCardFront = $request->file('identity_card_front');
            $identityCardFrontPath = $identityCardFront->store('documents/identity_cards', 'public');
            $documentData['identity_card_front_url'] = $identityCardFrontPath;
        }
    
        if ($existingRecord) {
            // Update only the fields that are present in the request
            DB::table('documents')->where('tutor_id', $tutor->id)->update($documentData);
            $msg = "Document details updated successfully";
        } else {
            // Insert new record
            $documentData['tutor_id'] = $tutor->id;
            DB::table('documents')->insert($documentData);
            $msg = "Document details added successfully";
        }
    
        return response()->json([
            'ResponseCode' => '100',
            'message' => $msg,
            'data' => $documentData
        ]);
    }

    
    public function getDocuments(Request $request)
    {
        // Validate the presence and non-empty value of the token
        if (!$request->has('token') || empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing or empty token field',
            ]);
        }
    
        $token = $request->input('token');
    
        // Validate token
        $tutor = DB::table('tutors')->where('token', $token)->first();
    
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'Tutor not found.',
            ]);
        }
    
        $tutor_id = $tutor->id;
    
        // Fetch document details
        $data = DB::table('documents')->where('tutor_id', $tutor_id)->first();
    
        if (!$data) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No data found.',
            ]);
        }
    
        // Base URL for document storage
        $baseUrl = 'https://sifu.qurangeek.com/storage/app/public/';
    
        // Append the base URL to each document URL
        $data->resume_url = $baseUrl . $data->resume_url;
        $data->education_transcript_url = $baseUrl . $data->education_transcript_url;
        $data->formal_photo_url = $baseUrl . $data->formal_photo_url;
        $data->identity_card_front_url = $baseUrl . $data->identity_card_front_url;
    
        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Data Retrieved Successfully',
            'data' => $data,
        ]);
    }

    public function storeDeclaration(Request $request)
    {
        // Validate the presence and non-empty value of the token
        if (!$request->has('token') || empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'message' => 'Missing or empty token field',
            ]);
        }

        $token = $request->input('token');

        // Validate token
        $tutor = DB::table('tutors')->where('token', $token)->first();
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'message' => 'tutor not found.',
            ]);
        }

        // Validate required field 'declaration'
        if (!$request->has('declaration') || empty($request->declaration)) {
            return response()->json([
                'ResponseCode' => '102',
                'message' => 'Missing or empty declaration field',
            ]);
        }

        // Prepare the data for insertion
        $declarationData = [
            'declaration' => $request->declaration,
        ];

        // Check if record exists
        $existingRecord = DB::table('declarations')->where('tutor_id', $tutor->id)->first();

        if ($existingRecord) {
            DB::table('declarations')->where('tutor_id', $tutor->id)->update($declarationData);
            $msg = "Declaration updated successfully";
        } else {
            $declarationData['tutor_id'] = $tutor->id;
            DB::table('declarations')->insert($declarationData);
            $msg = "Declaration added successfully";
        }

        return response()->json([
            'ResponseCode' => '100',
            'message' => $msg,
            'data' => $declarationData
        ]);
    }
    
    public function getDeclaration(Request $request)
    {
        // Validate the presence and non-empty value of the token
        if (!$request->has('token') || empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing or empty token field',
            ]);
        }
    
        $token = $request->input('token');
    
        // Validate token
        $tutor = DB::table('tutors')->where('token', $token)->first();
    
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'Tutor not found.',
            ]);
        }
    
        $tutor_id = $tutor->id;
    
        // Fetch declaration details
        $data = DB::table('declarations')->where('tutor_id', $tutor_id)->get();
    
        if ($data->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No data found.',
            ]);
        }
    
        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Data Retrieved Successfully',
            'data' => $data,
        ]);
    }


    public function checkTutorData(Request $request)
    {
        // Validate the presence and non-empty value of the token
        if (!$request->has('token') || empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'message' => 'Missing or empty token field',
            ]);
        }

        $token = $request->input('token');

        // Validate token
        $tutor = DB::table('tutors')->where('token', $token)->first();
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'message' => 'tutor not found.',
            ]);
        }


        // Retrieve the tutor_id from the request
        $tutor_id = $tutor->id;

        // Check if data exists in any of the tables
        $hasServicePreferences = DB::table('service_preferences')->where('tutor_id', $tutor_id)->exists();
        $hasBioDetails = DB::table('bio_details')->where('tutor_id', $tutor_id)->exists();
        $hasEmergencyContact = DB::table('emergency_contacts')->where('tutor_id', $tutor_id)->exists();
        $hasEducation = DB::table('educations')->where('tutor_id', $tutor_id)->exists();
        $hasDocuments = DB::table('documents')->where('tutor_id', $tutor_id)->exists();
        $hasDeclaration = DB::table('declarations')->where('tutor_id', $tutor_id)->exists();

        // Prepare the response data
        $dataStatus = [
            [
                "id" => 1,
                "title" => "Service Preferences",
                "screen" => "ServicePreference",
                "status" => $hasServicePreferences
            ],
            [
                "id" => 2,
                "title" => "Bio Details",
                "screen" => "BioDetails",
                "status" => $hasBioDetails
            ],
            [
                "id" => 3,
                "title" => "Emergency Contact",
                "screen" => "EmergencyContact",
                "status" => $hasEmergencyContact
            ],
            [
                "id" => 4,
                "title" => "Education",
                "screen" => "EducationDetails",
                "status" => $hasEducation
            ],
            [
                "id" => 5,
                "title" => "Documents",
                "screen" => "VerificationDocumentsUpload",
                "status" => $hasDocuments
            ],
            [
                "id" => 6,
                "title" => "Declaration",
                "screen" => "TutorVerificationDeclaration",
                "status" => $hasDeclaration
            ]
        ];

        // Return the response
        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Data retrieved successfully.',
            'data' => $dataStatus
        ]);
    }

    public function ticketsAPI(Request $request)
    {
        // Validate the presence and non-empty value of the token
        if (!$request->has('token') || empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing or empty token field',
            ]);
        }

        $token = $request->input('token');

        // Validate token
        $tutor = DB::table('tutors')->where('token', $token)->first();

        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'tutor not found.',
            ]);
        }

        $tutorID = $tutor->id; // Extract tutorID from the validated tutor

        $resultData = [];

        $tickets = DB::table('job_tickets')
            ->join('products', 'products.id', '=', 'job_tickets.subjects')
            ->join('categories', 'categories.id', '=', 'products.category')
            ->join('students', 'students.id', '=', 'job_tickets.student_id')
            ->join('customers', 'customers.id', '=', 'students.customer_id')
            ->join('cities', 'customers.city', '=', 'cities.id')
            ->join('states', 'customers.state', '=', 'states.id')
            ->leftJoin('tutoroffers', function ($join) use ($tutorID) {
                $join->on('job_tickets.id', '=', 'tutoroffers.ticketID')
                    ->where('tutoroffers.tutorID', '=', $tutorID);
            })
            ->where('job_tickets.status', '=', 'pending')
            ->whereNull('tutoroffers.tutorID')
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
                'job_tickets.per_class_commission_before_eight_hours as per_class_commission_before_eight_hours',
                'job_tickets.per_class_commission_after_eight_hours as per_class_commission_after_eight_hours',
                'products.id as subject_id',
                'job_tickets.totalPrice as price',
                'job_tickets.extra_student_tutor_commission as extraFee',
                'students.full_name as studentName',
                'students.gender as studentGender',
                'students.age as studentAge',
                'students.address1 as studentAddress',
                'students.specialNeed as special_need',
                'categories.category_name as categoryName',
                'categories.id as categoryID',
                'cities.name as city',
                'cities.id as cityID',
                'states.id as stateID',
                'states.name as state'
            )
            ->orderBy('job_tickets.id', 'DESC')
            ->get();

        // Check if tickets are found
        if ($tickets->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No tickets found.',
            ]);
        }

        $resultData = [];

        foreach ($tickets as $ticket) {
            $inputString = $ticket->classDay;
            $outputString = stripslashes(trim($inputString, "\""));

            $totalHours = $ticket->classFrequency * $ticket->quantity;

            // Calculate the hours before and after 8 hours
            $hoursBeforeEight = min($totalHours, 8);
            $hoursAfterEight = max($totalHours - 8, 0);

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
                'specialRequest' => $ticket->specialRequest,
                'subject_id' => $ticket->subject_id,
                'price' => $ticket->estimate_commission,
                'display_price' => $ticket->estimate_commission_display_tutor,
                'extraFee' => $ticket->extraFee,
                'studentName' => $ticket->studentName,
                'studentGender' => $ticket->studentGender,
                'student_age' => $ticket->studentAge,
                'studentAddress' => $ticket->studentAddress,
                'special_need' => $ticket->special_need,
                'city' => $ticket->city,
                'state' => $ticket->state,
                'cityID' => $ticket->cityID,
                'stateID' => $ticket->stateID,
                'categoryName' => $ticket->categoryName,
                'categoryID' => $ticket->categoryID,
                'jobTicketExtraStudents' => []
            ];

            // Determine the class day type
            $days = explode(',', str_replace('"', '', $ticket->classDay));
            $classDayType = (in_array('Sat', $days) || in_array('Sun', $days)) ? 'weekend' : 'weekday';
            $ticketData['classDayType'] = $classDayType;

            // Retrieve extra students associated with the job ticket
            $students = DB::table('job_ticket_students')
                ->join('students', 'job_ticket_students.student_id', '=', 'students.id')
                ->select('job_ticket_students.*', 'students.specialNeed as specialNeed')
                ->where('job_ticket_students.job_ticket_id', '=', $ticket->ticketID)
                ->get();

            foreach ($students as $student) {
                $studentData = [
                    'student_name' => $student->student_name,
                    'student_age' => $student->student_age,
                    'student_gender' => $student->student_gender,
                    'year_of_birth' => $student->year_of_birth,
                    'special_need' => $student->specialNeed,
                    'subject_id' => $student->subject_id
                ];

                $ticketData['jobTicketExtraStudents'][] = $studentData;
            }
            
            // Add the count of jobTicketExtraStudents
            $ticketData['extraStudentCount'] = count($ticketData['jobTicketExtraStudents']);

            $resultData[] = $ticketData;
        }

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Tickets retrieved successfully.',
            'data' => $resultData
        ]);
    }

    public function tutorFirstReportView(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token') || empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing or empty token field',
            ]);
        }

        // Retrieve the tutor based on the token
        $tutor = DB::table('tutors')->where('token', $request->token)->first();

        // Check if the tutor exists
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'Tutor not found',
            ]);
        }

        // Fetch the report data
        $tutorReportListing = DB::table('tutorFirstSubmittedReportFromApps')
            ->join('students', 'tutorFirstSubmittedReportFromApps.studentID', '=', 'students.id')
            ->join('tutors', 'tutorFirstSubmittedReportFromApps.tutorID', '=', 'tutors.id')
            ->join('products', 'tutorFirstSubmittedReportFromApps.subjectID', '=', 'products.id')
            ->where('tutorFirstSubmittedReportFromApps.id', '=', $request->id)
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
                'tutors.city as tutorCity',
                'products.name as subjectName',
                'students.uid as studentID',
                'students.full_name as studentName',
                'students.city as studentCity',
                'tutorFirstSubmittedReportFromApps.created_at as createdAt'
            )
            ->first();

        // Check if a report is found
        if (!$tutorReportListing) {
            return response()->json([
                'ResponseCode' => '404',
                'error' => 'Report not found.',
            ]);
        }

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'REport Data Retrieved Successfully.',
            'data' => $tutorReportListing,
        ]);
    }

    public function addMultipleClasses(Request $request)
    {
        // Validate the presence and non-empty value of the token
        if (!$request->has('token') || empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing or empty token field',
            ]);
        }

        // Retrieve the tutor based on the token
        $token = $request->input('token');
        $tutor = DB::table('tutors')->where('token', $token)->first();

        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'tutor not found.',
            ]);
        }

        $tutor_id = $tutor->id;

        // Check for required fields in the request
        $requiredFields = ['classes'];
        foreach ($requiredFields as $field) {
            if (!$request->has($field)) {
                return response()->json([
                    'ResponseCode' => '102',
                    'error' => "Missing $field field",
                ]);
            }
        }

        $data = $request->all();
        $classCount = 0;

        // Initialize getClassSchedule to ensure it is defined
        $getClassSchedule = null;

        // Validate each class entry
        foreach ($data['classes'] as $key => $value) {
            $requiredClassFields = ['student_id', 'subject_id', 'start_time', 'end_time', 'date'];
            foreach ($requiredClassFields as $field) {
                if (!isset($value[$field]) || empty($value[$field])) {
                    return response()->json([
                        'ResponseCode' => '102',
                        'error' => "Missing or empty $field field in class entry " . ($key + 1),
                    ]);
                }
            }

            // Validate date and time formats
            $dateInput = $value['date'];
            $startTime = $value['start_time'];
            $endTime = $value['end_time'];


            // Check for conflicting records
            $existingRecord = DB::table('class_schedules')
                ->where('date', '=', $dateInput)
                ->where('tutorID', '=', $tutor_id)
                ->where('status', '!=', 'attended')
                ->first();

            $conflictingRecord = null;
            if ($existingRecord) {
                // $conflictingRecord = DB::table('class_schedules')
                //     ->where('date', '=', $dateInput)
                //     ->where('tutorID', '=', $tutor_id)
                //     ->where('status', '!=', 'attended')
                //     ->where(function ($query) use ($startTime, $endTime) {
                //         $query->where(function ($query) use ($startTime, $endTime) {
                //             $query->where('startTime', '<', $endTime)
                //                   ->where('endTime', '>', $startTime);
                //         });
                //     })
                //     ->first();

                // $conflictingRecord = DB::table('class_schedules')
                //     ->where('date', '=', $dateInput)
                //     ->where('status', '!=', 'attended')
                //     ->where('tutorID', '=', $tutor_id)
                //     ->where(function ($query) use ($dateInput, $startTime, $endTime) {
                //         $query->where(function ($query) use ($startTime, $endTime) {
                //             $query->where('startTime', '<', $startTime)
                //                 ->where('endTime', '>', $startTime);
                //         })
                //             ->orWhere(function ($query) use ($startTime, $endTime) {
                //                 $query->where('startTime', '<', $endTime)
                //                     ->where('endTime', '>', $endTime);
                //             })
                //             ->orWhere(function ($query) use ($startTime, $endTime) {
                //                 $query->where('startTime', '>=', $startTime)
                //                     ->where('endTime', '<=', $endTime);
                //             });
                //     })
                //     ->first();
                
            $conflictingRecord = DB::table('class_schedules')
                ->where('date', '=', $dateInput)
                ->where('tutorID', '=', $tutor_id)
                // ->where('status', '!=', 'attended')
                ->whereNotIn('status', ['attended', 'pending', 'cancelled']) // Exclude both 'attended' and 'pending' statuses
                ->where(function ($query) use ($startTime, $endTime) {
                    // Check if the start time of the new class falls within an existing class time range
                    $query->where(function ($query) use ($startTime, $endTime) {
                        $query->where('startTime', '<', $endTime)
                              ->where('endTime', '>', $startTime);
                    });
                })
                ->first();

            }
            
            // dd($conflictingRecord);

            if ($conflictingRecord) {
                $message = 'You have already scheduled a class in this time slot. Date: ' . $dateInput . ' Start time: ' . $startTime . ' End time: ' . $endTime;
                return response()->json([
                    'ResponseCode' => '104',
                    'error' => $message,
                ]);
            } else {
                if ($getClassSchedule === null) {
                    // Fetch the latest class schedule if not already set
                    $getClassSchedule = DB::table('class_schedules')
                        ->where('tutorID', '=', $tutor_id)
                        ->where('studentID', '=', $value['student_id'])
                        ->where('subjectID', '=', $value['subject_id'])
                        ->where('class_schedule_id', '!=', 0)
                        ->orderBy('id', 'DESC')->first();

                    // dd($getClassSchedule);

                    if (!$getClassSchedule) {
                        return response()->json([
                            "ResponseCode" => "104",
                            "error" => "No Class Schedule Found."
                        ]);
                    }
                }

                // Calculate total time
                $t1 = strtotime($startTime);
                $t2 = strtotime($endTime);
                $differenceInSeconds = $t2 - $t1;
                $differenceInHours = $differenceInSeconds / 3600;
                $totalTime = number_format((float)$differenceInHours, 2, '.', '');

                if ($differenceInHours < 0) {
                    $differenceInHours += 24;
                    $totalTime = number_format((float)$differenceInHours, 2, '.', '');
                }

                $values = [
                    'tutorID' => $getClassSchedule->tutorID,
                    'class_schedule_id' => $getClassSchedule->class_schedule_id,
                    'studentID' => $getClassSchedule->studentID,
                    'subjectID' => $getClassSchedule->subjectID,
                    'ticketID' => $getClassSchedule->ticketID,
                    'date' => $value['date'],
                    'startTime' => $value['start_time'],
                    'endTime' => $value['end_time'],
                    'status' => 'scheduled',
                    'totalTime' => $totalTime,
                ];

                $classCount++;
                DB::table('class_schedules')->insertGetId($values);
            }
        }
        
        // dd($getClassSchedule);
        
        // Update remaining classes
        if ($getClassSchedule) {
            $remaining_classes = DB::table('job_tickets')->where('id', $getClassSchedule->ticketID)->first();

            DB::table('job_tickets')
                ->where('id', $getClassSchedule->ticketID)
                ->update(['remaining_classes' => $remaining_classes->remaining_classes - $classCount]);

            DB::table('student_subjects')
                ->where('ticket_id', $getClassSchedule->ticketID)
                ->update(['remaining_classes' => $remaining_classes->remaining_classes - $classCount]);
        }
        
        
        
        $student = DB::table("students")->where("id",$getClassSchedule->studentID)->first();
        $parent = DB::table("customers")->where("id",$student->customer_id)->first();
        $product = DB::table("products")->where("id",$getClassSchedule->subjectID)->first();
        try {

            $data = [
                "ResponseCode" => "100",
                "message" => "New Class Schedule Added by tutor"
            ];
            
            // Send push notifications to tutor devices and parent devices
            $title = 'New Class Schedule Added';
            $message = 'New Class Schedule Added by tutor';
        
            $notificationdata = [
                'Sender' => 'Schedule'
            ];
            
            $tutorDevices = DB::table('tutor_device_tokens')->where('tutor_id', '=', $tutor->id)->distinct()->get(['device_token', 'tutor_id']);
            foreach ($tutorDevices as $rowDeviceToken) {
                $deviceToken = $rowDeviceToken->device_token;

                // Dispatch push notification job
                SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
            }
            
            $parent_device_tokens = DB::table('parent_device_tokens')->where('parent_id', '=', $parent->id)->distinct()->get(['device_token', 'parent_id']);
            foreach ($parent_device_tokens as $token) {
                $deviceToken = $token->device_token;
                // Dispatch push notification job
                SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
            }
        
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
            
            // if($parent->phone!=null)
            // {
            //     $phone = $parent->phone;
            //     $smsmessage = "New Class Alert – {$student->full_name}'s first {$product->name} class is scheduled for {$value['date']} - {$value['start_time']}. Check the app for details.";
            //     $this->sendMessage($phone, $smsmessage);
            // }


            //tutor
            event(new TutorDashboard($data, $tutor->token));
            event(new TutorClassSchedule($data, $tutor->token));

            //parent
            event(new ParentDashbaord($data));
            event(new ParentClassSchedule($data, $parent->token));

        } catch (Exception $e) {
            return response()->json(["ResponseCode" => "103",
                "error" => "Unable to add class schedule"]);
        }

        
        

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Class added successfully.',
        ]);
    }

    public function attendedClassClockInTwo(Request $request)
    {
        $requiredFields = ['class_schedule_id', 'start_minutes', 'start_seconds', 'token'];

        foreach ($requiredFields as $field) {
            if (!$request->has($field) || empty($request->input($field))) {
                return response()->json([
                    'ResponseCode' => '102',
                    'error' => "Missing or empty $field field",
                ]);
            }
        }

        if (!$request->hasFile('start_time_proof_image') || $request->file('start_time_proof_image')->getSize() == 0) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing or empty startTimeProofImage field',
            ]);
        }

        $token = $request->input('token');
        $tutor = DB::table('tutors')->where('token', $token)->first();

        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'tutor not found.',
            ]);
        }

        // $id = $request->input('id');
        $class_schedule_id = $request->input('class_schedule_id');
        $min = $request->input('start_minutes');
        $sec = $request->input('start_seconds');

        $sTime = sprintf('%02d:%02d:00', $min, $sec);

        $startTimeProofImage = time() . '.' . $request->file('start_time_proof_image')->extension();
        $request->file('start_time_proof_image')->move(public_path('signInProof'), $startTimeProofImage);

        $getClassSchedule = DB::table('class_schedules')->where('id', $class_schedule_id)->first();

        if (!$getClassSchedule) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'Class schedule not found.',
            ]);
        }

        $values = [
            'tutorID' => $getClassSchedule->tutorID,
            'class_schedule_id' => $class_schedule_id,
            'csID' => $class_schedule_id,
            'studentID' => $getClassSchedule->studentID,
            'subjectID' => $getClassSchedule->subjectID,
            'ticketID' => $getClassSchedule->ticketID,
            'date' => $getClassSchedule->date,
            'startTimeProofImage' => $startTimeProofImage,
            'startTime' => $sTime,
            'totalTime' => 0,
        ];

        $classAttendedID = DB::table('class_attendeds')->insertGetId($values);

        $student = DB::table('students')->where('id', $getClassSchedule->studentID)->first();
        $parent = $student ? DB::table('customers')->where('id', $student->customer_id)->first() : null;
        $ticketUID = DB::table('job_tickets')->where('id', $getClassSchedule->ticketID)->first();

        if ($parent && !empty($parent->whatsapp)) {
            $whatsapp_api = new WhatsappApi();
            $phone_number = $parent->whatsapp;
            $message = "Clock In for: " . "<b>" . ($ticketUID ? $ticketUID->uid : 'N/A') . "</b>";
            $whatsapp_api->send_message($phone_number, $message);
        }

        DB::table('class_schedules')->where('id', $class_schedule_id)->update(['status' => 'On going']);
        
        // dd($parent);

        $tutordevicetoken = DB::table('tutor_device_tokens')->where('tutor_id', $tutor->id)->first();
        $parentdevicetoken = DB::table('parent_device_tokens')->where('parent_id', $parent->id)->first();
        
        
        $title = 'Your class has been started';
        $message = "Your class has been started successfully";
        $notificationdata = [
                'Sender' => 'Home'
            ];
            
        
        // Dispatch push notification job
        if($tutordevicetoken){
            // Dispatch push notification job
            SendPushNotificationJob::dispatch($tutordevicetoken->device_token, $title, $message, $notificationdata);
        
            // Store notification in the database
            DB::table('notifications')->insert([
                'page' => 'Home',
                'token' => $tutor->token,
                'title' => $title,
                'message' => $message,
                'type' => 'tutor',
                'status' => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        if($parentdevicetoken){
            // Dispatch push notification job
            SendPushNotificationJob::dispatch($parentdevicetoken->device_token, $title, $message, $notificationdata);
        
            // Store notification in the database
            DB::table('notifications')->insert([
                'page' => 'Home',
                'token' => $parent->token,
                'title' => $title,
                'message' => $message,
                'type' => 'Parent',
                'status' => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        $data = [
            "ResponseCode" => "100",
            "message" => "Your class has been started successfully."
        ];
        
        event(new ParentClassSchedule($data, $parent->token));
        event(new ParentDashbaord($data, $parent->token));
        event(new ParentNotification($data, $parent->token));

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Your class has been started successfully.'

        ]);
    }

    public function attendedClassClockOutTwo(Request $request)
    {
        // Define required fields
        $requiredFields = ['class_schedule_id', 'end_minutes', 'end_seconds', 'token'];

        // Check for missing or empty fields
        foreach ($requiredFields as $field) {
            if (!$request->has($field) || empty($request->input($field))) {
                return response()->json([
                    'ResponseCode' => '102',
                    'error' => "Missing or empty $field field",
                ]);
            }
        }

        // Check for missing or empty image file
        if (!$request->hasFile('end_time_proof_image') || $request->file('end_time_proof_image')->getSize() == 0) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing or empty end_time_proof_image field',
            ]);
        }

        // Validate token
        $token = $request->input('token');
        $tutor = DB::table('tutors')->where('token', $token)->first();

        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'Tutor not found.',
            ]);
        }

        // Extract request data
        $class_schedule_id = $request->class_schedule_id;
        $min = $request->end_minutes;
        $sec = $request->end_seconds;

        // Handle the image upload
        $endTimeProofImage = time() . '.' . $request->end_time_proof_image->extension();
        $request->end_time_proof_image->move(public_path('signOutProof'), $endTimeProofImage);

        // Format end time
        $endTime = $min . ':' . $sec . ':00';

        // Get the class schedule record
        $getClassSchedule = DB::table('class_attendeds')->where('class_schedule_id', $class_schedule_id)->first();
        
        // Assume $request contains the input data
        $startTime = $getClassSchedule->startTime; // e.g., '14:30:00'
        $min = $request->end_minutes; // e.g., 45
        $sec = $request->end_seconds; // e.g., 30
        
        // Create a Carbon instance for start time
        $start = Carbon::createFromFormat('H:i:s', $startTime);
        
        // Add minutes and seconds to the start time
        $end = $start->addMinutes($min)->addSeconds($sec);
        
        // Format end time
        // $endTime = $end->format('H:i:s');

        if (!$getClassSchedule) {
            return response()->json(['ResponseCode' => '104', 'error' => 'No on  going class found']);
        }

        // Get related data
        $studentName = DB::table('students')->where('id', $getClassSchedule->studentID)->first();
        $subjectName = DB::table('products')->where('id', $getClassSchedule->subjectID)->first();
        $tutors = DB::table('tutors')->where('id', $getClassSchedule->tutorID)->first();

        // Update the class attended record
        DB::table('class_attendeds')
            ->where('class_schedule_id', $class_schedule_id)
            ->update(['endTime' => $endTime, 'endTimeProofImage' => $endTimeProofImage, 'totalTime' => $getClassSchedule->totalTime]);

        // Fetch related records
        $attendedRecord = DB::table('class_attendeds')->where('class_schedule_id', $getClassSchedule->class_schedule_id)->first();
        $job_ticket = DB::table("job_tickets")->where("id", $getClassSchedule->ticketID)->first();
        $class_schedule = DB::table("class_schedules")->where("id", $request->class_schedule_id)->first();

        if ($class_schedule == null) {
            return response()->json(["ResponseCode" => "104", 'error' => 'Class Schedule Not Found']);
        }


        // Calculate total attended hours
        $totalAttendedHours = DB::table('class_schedules')
            ->where('ticketID', $job_ticket->id)
            ->where("status", "attended")
            ->sum('totalTime');

        $newTotalAttendedHours = $totalAttendedHours + $class_schedule->totalTime;

        // Calculate commissions
        $hoursBeforeEight = 0;
        if ($newTotalAttendedHours > 8) {
            if ($totalAttendedHours >= 8) {
                $hoursAfterEight = $class_schedule->totalTime;
            } else {
                $hoursBeforeEight = min($class_schedule->totalTime, 8 - $totalAttendedHours);
                $hoursAfterEight = $class_schedule->totalTime - $hoursBeforeEight;
            }
            $commissionBeforeEight = $hoursBeforeEight * $job_ticket->per_class_commission_before_eight_hours;
            $commissionAfterEight = $hoursAfterEight * $job_ticket->per_class_commission_after_eight_hours;
            $per_class_commission = $commissionBeforeEight + $commissionAfterEight;
        } else {
            $commissionBeforeEight = $class_schedule->totalTime * $job_ticket->per_class_commission_before_eight_hours;
            $per_class_commission = $commissionBeforeEight;
        }

        // Update records
        DB::table('class_attendeds')
            ->where('class_schedule_id', $getClassSchedule->class_schedule_id)
            ->update(['status' => 'pending', 'attendedStatusAttachment' => $endTimeProofImage, 'commission' => $per_class_commission]);

        DB::table('class_schedules')
            ->where('id', $getClassSchedule->class_schedule_id)
            ->update(['status' => 'pending', 'attendedStatusAttachment' => $endTimeProofImage]);

        // Fetch customer data
        $student_data = DB::table('students')->where('id', $getClassSchedule->studentID)->first();
        $customer_data = DB::table('customers')->where('id', $student_data->customer_id)->first();
        
        // Count the number of reports submitted for the given student
        $reportCount = DB::table('tutorFirstSubmittedReportFromApps')
            ->where('subjectID', $getClassSchedule->subjectID)
            ->where('studentID', $getClassSchedule->studentID)
            ->count();
        
        // Set $reportSubmitted to true if reports are found, otherwise false
        $reportSubmitted = $reportCount > 0;

        // Prepare email recipients
        $to = $customer_data->email ?? "binasift@gmail.com";
        $emailTwo = "binasift@gmail.com";

        // Send attendance report email
        try {
            Mail::to($to)->send(new AttendanceReportMail($attendedRecord, $studentName, $subjectName, $tutors));
            Mail::to($emailTwo)->send(new AttendanceReportMail($attendedRecord, $studentName, $subjectName, $tutors));
        } catch (Exception $e) {
            return response()->json([
                "ResponseCode" => "103",
                "error" => $e->getMessage(),
            ]);
        }

        // Send WhatsApp notification if available
        $parent = DB::table('customers')->where('id', $student_data->customer_id)->first();
        $ticketUID = DB::table('job_tickets')->where('id', $getClassSchedule->ticketID)->first();
        
        $tutordevicetoken = DB::table('tutor_device_tokens')->where('tutor_id', $tutor->id)->first();
        $parentdevicetoken = DB::table('parent_device_tokens')->where('parent_id', $parent->id)->first();

        // Send invoice email if not already sent
        $ticketDetails = DB::table('job_tickets')->where('id', $getClassSchedule->ticketID)->first();
        $invoice_detail = DB::table('invoices')->where('ticketID', $ticketDetails->id)->first();

        $pdfPath = public_path("/invoicePDF/Invoice-" . $invoice_detail->id . ".pdf");
        $pdfContent = file_get_contents($pdfPath);
        $base64Content = base64_encode($pdfContent);

        if (!$ticketDetails->first_invoice_sent) {

            DB::table('invoices')->where('ticketID', $ticketDetails->id)->update([
                'paymentDate' => now(),
                'dueDate' => now()->addWeek()
            ]);
            
            // Fetch necessary data
            $parentName = $customer_data->full_name;
            $ticketId = $ticketDetails->uid;
            $studentName = $student_data->full_name;
            $subjectName = $subjectName->name;
            $pricePerHour = $job_ticket->subject_fee;
            $totalHours = $class_schedule->totalTime;
            $classDate = $class_schedule->date; // Assuming there's a date field
            $totalAmount = $pricePerHour * $totalHours;

            // Prepare data for email
            $emailData = [
                'parentName' => $parentName,
                'ticketId' => $ticketId,
                'studentName' => $studentName,
                'subjectName' => $subjectName,
                'pricePerHour' => $pricePerHour,
                'totalHours' => $totalHours,
                'classDate' => $classDate,
                'totalAmount' => $totalAmount
            ];
            
            // Send the email
            Mail::to($to)->send(new InvoiceMail($invoice_detail, $base64Content, $emailData));

            // Mail::to($to)->send(new InvoiceMail($invoice_detail, $base64Content));
            DB::table('job_tickets')->where('id', $getClassSchedule->ticketID)->update(['first_invoice_sent' => true]);
            
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
                    'token' => $parent->token,
                    'title' => $parenttitle,
                    'message' => $parentmessage,
                    'type' => 'Parent',
                    'status' => 'new',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
                    
            // if($parent->phone!=null)
            // {
            //     $phone = $parent->phone;
            //     $smsmessage = "{$student_data->full_name}'s {$subjectName->name} invoice is ready. Please make payment via the SifuTutor app. Thank you!";
            //     $this->sendMessage($phone, $smsmessage);
            // }
        }

        if (isset($parent) && $parent->whatsapp) {
            $whatsapp_api = new WhatsappApi();
            $phone_number = $parent->whatsapp;
            $message = "Clock Out for: " . "<b>" . $ticketUID->uid . "</b>";
            $whatsapp_api->send_message($phone_number, $message);
        }

        $data = [
            "ResponseCode" => "100",
            "message" => "New Attendance"
        ];
        //tutor
        event(new TutorDashboard($data, $tutor->token));
        //parent
        event(new ParentClassSchedule($data, $parent->token));
        event(new SingleParentDashboard($data, $parent->token));
        event(new ParentNotification($data, $parent->token));
        
        $title = 'Your class has been End';
        $message = "Your class has been End successfully";

        $parenttitle = 'Class Attendance Verification';
        $parentmessage = "Verify ".$subjectName->name." attendance with ".$tutor->full_name;
        $notificationdata = [
                'Sender' => 'Home'
            ];
        
        // Dispatch push notification job
        if($tutordevicetoken){
            SendPushNotificationJob::dispatch($tutordevicetoken->device_token, $title, $message, $notificationdata);
        
            // Store notification in the database
            DB::table('notifications')->insert([
                'page' => 'Home',
                'token' => $tutor->token,
                'title' => $title,
                'message' => $message,
                'type' => 'tutor',
                'status' => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        if($parentdevicetoken){
            // Dispatch push notification job
            SendPushNotificationJob::dispatch($parentdevicetoken->device_token, $parenttitle, $parentmessage, $notificationdata);
        
            // Store notification in the database
            DB::table('notifications')->insert([
                'page' => 'Home',
                'token' => $parent->token,
                'title' => $parenttitle,
                'message' => $parentmessage,
                'type' => 'Parent',
                'status' => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // if($parent->phone!=null)
        // {
        //     $phone = $parent->phone;
        //     $smsmessage = "Please verify {$student_data->full_name}'s attendance for the recent {$subjectName->name} class in the app.";
        //     $this->sendMessage($phone, $smsmessage);
        // }
        

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Class Clockout Successfully',
            'data' => ["report_submitted"=>$reportSubmitted]

        ]);
    }

    public function tutorFirstReport(Request $request)
    {

        // Required fields check
        $requiredFields = [
            'student_id', 'schedule_id', 'subject_id',
            'knowledge', 'knowledge2',
            'understanding', 'understanding2', 'criticalThinking',
            'criticalThinking2', 'observation', 'additionalAssisment', 'plan', 'token'
        ];

        foreach ($requiredFields as $field) {
            if (!$request->has($field) || empty($request->input($field))) {
                return response()->json([
                    'ResponseCode' => '102',
                    'error' => "Missing or empty $field field",
                ]);
            }
        }

        // Token and tutor validation
        $token = $request->input('token');
        $tutor = DB::table('tutors')->where('token', $token)->first();

        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'tutor not found.',
            ]);
        }

        //check student
        $student = DB::table('students')->where('id', $request->input('student_id'))->first();

        if (!$student) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'Student Not Found.',
            ]);
        }

        //check subject
        $subject = DB::table('products')->where('id', $request->input('subject_id'))->first();

        if (!$subject) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'Subject Not Found.',
            ]);
        }


        //check schedule
        $schedule = DB::table('class_schedules')->where('id', $request->input('schedule_id'))->first();

        if (!$schedule) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'Class Schedule Not Found.',
            ]);
        }


        // Insert report data
        $values = [
            'tutorID' => $tutor->id, // Get tutorID from the tutors table using the token
            'studentID' => $request->student_id,
            'scheduleID' => $request->schedule_id,
            'subjectID' => $request->subject_id,
            'currentDate' => date("Y-m-d"),
            'reportType' => 'Student Evaluation Report',

            'knowledge' => $request->knowledge,
            'knowledge2' => $request->knowledge2,
            'understanding' => $request->understanding,
            'understanding2' => $request->understanding2,
            'criticalThinking' => $request->criticalThinking,
            'criticalThinking2' => $request->criticalThinking2,
            'observation' => $request->observation,
            'additionalAssisment' => $request->additionalAssisment,
            'plan' => $request->plan,
        ];

        $report = DB::table('tutorFirstSubmittedReportFromApps')->insertGetId($values);
        
        // Send push notifications to tutor devices and parent devices
        $title = 'Evaluation Report Submitted';
        $message = 'Evaluation Report Submitted Successfully';
        
        $notificationdata = [
            'Sender' => 'Schedule'
        ];
        
        $tutorDevices = DB::table('tutor_device_tokens')->where('tutor_id', '=', $tutor->id)->distinct()->get(['device_token', 'tutor_id']);
        foreach ($tutorDevices as $rowDeviceToken) {
            $deviceToken = $rowDeviceToken->device_token;
        
            // Dispatch push notification job
            SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
        }
        
        $students = DB::table('students')->where('id', $request->student_id)->first();
        $customers = DB::table('customers')->where('id', $students->customer_id)->first();
        $parent_device_tokens = DB::table('parent_device_tokens')->where('parent_id', '=', $customers->id)->distinct()->get(['device_token', 'parent_id']);
        foreach ($parent_device_tokens as $token) {
            $deviceToken = $token->device_token;
            // Dispatch push notification job
            SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
        }
        
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
        
        // Store notification in the database
        DB::table('notifications')->insert([
            'page' => 'Schedule',
            'token' => $customers->token,
            'title' => $title,
            'message' => $message,
            'type' => 'parent',
            'status' => 'new',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        if ($customers && $customers->email) {
            // Send the evaluation report email to the parent
            Mail::to($customers->email)->send(new EvaluationReportMail($customers->full_name, $tutor->full_name, $students->full_name));
        }
        
        // if($customers->phone!=null)
        // {
        //     $phone = $customers->phone;
        //     $smsmessage = "{$students->full_name}'s progress report is ready. View it on the SifuTutor app.";
        //     $this->sendMessage($phone, $smsmessage);
        // }

        // Return success response with the inserted data
        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Evaluation Report Submitted Successfully'
        ]);

    }
    
    public function offerSendByTutor(Request $request)
    {
        // Validate required fields
        $requiredFields = ['ticket_id', 'comment', 'token'];
        foreach ($requiredFields as $field) {
            if (!$request->has($field) || empty($request->input($field))) {
                return response()->json([
                    'ResponseCode' => '102',
                    'error' => "Missing or empty $field field",
                ]);
            }
        }

        // Check if token is valid and retrieve tutorID
        $token = $request->input('token');
        $tutor = DB::table('tutors')->where('token', $token)->first();

        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'tutor not found.',
            ]);
        }

        $tutorID = $tutor->id;

        $ticketID = $request->input('ticket_id');
        // Check if ticket exists
        $ticket = DB::table('job_tickets')->where('id', '=', $ticketID)->first();
        if (!$ticket) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'Invalid ticket.',
            ]);
        }


        // Get parameters from the request
        $job_ticket_data = DB::table("job_tickets")->where("id", $request->input('ticket_id'))->first();
        $subjectID = $job_ticket_data->subjects;

        $comment = $request->input('comment');


        // Check if subject exists
        $subject = DB::table('products')->where('id', '=', $subjectID)->first();
        if (!$subject) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'Invalid subject.',
            ]);
        }

        // Check if the offer already exists
        $ifExist = DB::table('tutoroffers')
            ->where('ticketID', '=', $ticketID)
            ->where('tutorID', '=', $tutorID)
            ->first();

        if (!$ifExist) {
            $tutorOfferValues = [
                'tutorID' => $tutorID,
                'subject_id' => $subjectID,
                'ticketID' => $ticketID,
                'ticketUID' => $ticket->uid,
                'status' => 'pending',
                'comment' => $comment,
            ];

            $ssa = DB::table('tutoroffers')->insertGetId($tutorOfferValues);

            $result = DB::table('tutoroffers')
                ->where('tutorID', '=', $tutorID)
                ->where('ticketID', '=', $ticketID)
                ->where('subject_id', '=', $subjectID)
                ->first();
        } else {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'You have already applied for this ticket.',
            ]);
        }

        // Update the total tutor applied count in job tickets
        $totalTutorApplied = DB::table('tutoroffers')
            ->where('ticketID', '=', $ticketID)
            ->count();

        DB::table('job_tickets')
            ->where('id', $ticketID)
            ->update([
                'totalTutorApplied' => $totalTutorApplied,
                'application_status' => 'incomplete',
            ]);
            
        $student = DB::table('students')->where('id', '=', $ticket->student_id)->first();
        $tutor = DB::table('tutors')->where('id', '=', $tutorID)->first();
        $customer = DB::table('customers')->where('id', '=', $student->customer_id)->first();
            
        // Send push notifications to tutor devices
        $tutorDevice = DB::table('tutor_device_tokens')->where('tutor_id', '=', $tutorID)->distinct()->first(['device_token', 'tutor_id']);
        if($tutorDevice) {
            $push_notification_api = new PushNotificationLibrary();
            $deviceToken = $tutorDevice->device_token;
            $title = 'Job Ticket Applied';
            $message = 'Job Ticket Applied Successfully';
        
            $notificationdata = [
                'id' => $job_ticket_data->uid,
                'Sender' => 'jobTicket'
            ];
        
            // Dispatch push notification job
            SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
        
            // Store notification in the database
            DB::table('notifications')->insert([
                'page' => 'jobTicket',
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
        $parent_device_tokens = DB::table('parent_device_tokens')->where('parent_id', '=', $student->customer_id)->distinct()->get(['device_token', 'parent_id']);
        foreach ($parent_device_tokens as $token) {
            $push_notification_api = new PushNotificationLibrary();
            $deviceToken = $token->device_token;
            $title = $tutor->full_name . ' Applied for your Job Ticket';
            $message = $tutor->full_name . ' Applied for your Job Ticket Successfully';

            $notificationdata = [
                'id' => $job_ticket_data->uid,
                'Sender' => 'jobTicket'
            ];
        
            // Dispatch push notification job
            SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
        
            // Store notification in the database
            DB::table('notifications')->insert([
                'page' => 'jobTicket',
                'token' => $customer->token,
                'title' => $title,
                'message' => $message,
                'type' => 'parent',
                'status' => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        $data = [
            "ResponseCode" => "100",
            "message" => "Offer sent successfully"
        ];
        
        event(new JobTicket($data, $customer->token));
        event(new TutorOffers($data, $tutor->token));

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Offer sent successfully',
            'data' => $result,
        ]);
    }

    public function getTutorOffers(Request $request)
    {
        // Validate token and get tutor ID
        $token = $request->input('token');
        $tutor = DB::table('tutors')->where('token', $token)->first();

        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'tutor not found.',
            ]);
        }

        $tutorID = $tutor->id;
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
            ->select(
                'job_tickets.*',
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
                'job_tickets.per_class_commission_before_eight_hours as per_class_commission_before_eight_hours',
                'job_tickets.per_class_commission_after_eight_hours as per_class_commission_after_eight_hours',
                'job_tickets.extra_student_tutor_commission as extraFee',
                'job_tickets.totalPrice as price',
                'job_tickets.subscription as subscription',
                'products.id as subject_id',
                'tutoroffers.tutorID as tutorID',
                'students.full_name as studentName',
                'students.city as cityID',
                'students.gender as studentGender',
                'students.age as studentAge',
                'students.address1 as studentAddress',
                'categories.category_name as categoryName',
                'categories.id as categoryID',
                'cities.name as city',
                'states.name as state',
                'cities.id as cityID',
                'states.id as stateID',
                'job_tickets.status as ticket_status',
                'tutoroffers.status as offer_status'
            )
            ->orderBy('tutoroffers.created_at', 'desc')
            ->get();

        $resultData = [];

        foreach ($getTutorOffers as $offer) {
            $ticket = DB::table("job_tickets")->where("id", $offer->ticketID)->first();
            $totalHours = $ticket->classFrequency * $ticket->quantity;

            // Calculate the hours before and after 8 hours
            $hoursBeforeEight = min($totalHours, 8);
            $hoursAfterEight = max($totalHours - 8, 0);

            $offerData = [
                'ticket_status' => $offer->ticket_status,
                'offer_status' => $offer->offer_status,
                'display_price' => $offer->estimate_commission_display_tutor,
                'price' => $offer->estimate_commission,
                'city' => $offer->city,
                'mode' => $offer->mode,
                'subject_name' => $offer->subject_name,
                'tutorPereference' => $offer->tutorPereference,
                'categoryName' => $offer->categoryName,
                'classTime' => $offer->classTime,
                'jtuid' => $offer->jtuid,
                'subscription' => $offer->subscription,
                'total_commission_before_eight_hours' => $ticket->per_class_commission_before_eight_hours * $hoursBeforeEight,
                'total_commission_after_eight_hours' => $ticket->per_class_commission_after_eight_hours * $hoursAfterEight,
                'per_class_commission_before_eight_hours' => $ticket->per_class_commission_before_eight_hours,
                'per_class_commission_after_eight_hours' => $ticket->per_class_commission_after_eight_hours,
                'classDay' => $offer->classDay,
                'ticketID' => $offer->ticketID,
                'tutor_id' => $offer->tutor_id,
                'status' => $offer->status,
                'totalTutorApplied' => $offer->totalTutorApplied,
                'classFrequency' => $offer->classFrequency,
                'quantity' => $offer->quantity,
                'classAddress' => $offer->classAddress,
                'specialRequest' => $offer->specialRequest,
                'subject_id' => $offer->subject_id,
                'extraFee' => $offer->extraFee,
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

            // Determine if class day is weekend or weekday
            $days = explode(',', str_replace('"', '', $offer->classDay));
            $offerData['classDayType'] = (in_array('Sat', $days) || in_array('Sun', $days)) ? 'weekend' : 'weekday';

            // Get additional students for the job ticket
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
        
            // Add the count of jobTicketExtraStudents
            $offerData['extraStudentCount'] = count($offerData['jobTicketExtraStudents']);

            $resultData[] = $offerData;
        }

        if (empty($resultData)) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => "No Data Found",
            ]);
        } else {
            return response()->json([
                'ResponseCode' => '100',
                'message' => "Data Found Successfully",
                'data' => $resultData,
            ]);
        }
    }

    public function getClassSchedulesTime(Request $request)
    {
        // Validate token and retrieve tutorID
        $token = $request->input('token');
        $tutor = DB::table('tutors')->where('token', $token)->first();

        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'tutor not found.'
            ]);
        }

        $tutorID = $tutor->id;

        // Query to fetch class schedules
        $classSchedulesTime = DB::table('class_schedules')
            ->join('job_tickets', 'job_tickets.id', '=', 'class_schedules.ticketID')
            ->join('products', 'products.id', '=', 'class_schedules.subjectID')
            ->join('students', 'students.id', '=', 'class_schedules.studentID')
            ->join('customers', 'customers.id', '=', 'students.customer_id')
            ->join('cities', 'cities.id', '=', 'customers.city')
            ->where('class_schedules.tutorID', '=', $tutorID)
            ->select(
                'class_schedules.*',
                'students.id as studentID',
                'students.full_name as studentName',
                'students.gender as studentGender',
                'products.id as subjectID',
                'products.name as subjectName',
                'class_schedules.date as dateTime',
                'job_tickets.uid as jtuid',
                'job_tickets.quantity as quantity',
                'cities.name as city',
                'products.category as category',
                DB::raw("DATE_FORMAT(class_schedules.startTime, '%h:%i %p') as start_time"),
                DB::raw("DATE_FORMAT(class_schedules.endTime, '%h:%i %p') as end_time"),
                DB::raw("DATE_FORMAT(class_schedules.startTime, '%h %p') as class_start_time"),
                DB::raw('TIMESTAMPDIFF(HOUR, class_schedules.date, NOW()) as remainingHours'),
                // DB::raw("
                //     CASE 
                //         WHEN class_schedules.date < NOW() THEN 'Past' 
                //         ELSE 'Upcoming' 
                //     END as class_schedules_status
                // "), // Add class_schedules_status field to check if it's past or upcoming
                DB::raw("
                        CASE
                            WHEN class_schedules.status = 'attended' THEN 'Past'
                            ELSE 'Upcoming'
                        END as class_schedules_status
                    ")
            )
            ->orderBy(DB::raw("STR_TO_DATE(start_time, '%h:%i %p')"), 'asc')
            ->get();

        // Determine mode based on category
        foreach ($classSchedulesTime as $classSchedule) {
            $category = DB::table('categories')
                ->where('id', '=', $classSchedule->category)
                ->first();

            $classSchedule->mode = $category->mode ?? "Online";
        }

        if ($classSchedulesTime->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No records found.',

            ]);
        }

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Data fetched successfully.',
            'data' => $classSchedulesTime
        ]);
    }

    public function attendedClassStatus(Request $request)
    {
        // Validate required fields
        $requiredFields = ['class_schedule_id', 'status', 'statusReason'];

        foreach ($requiredFields as $field) {
            if (!$request->has($field) || empty($request->input($field))) {
                return response()->json([
                    'ResponseCode' => '102',
                    'message' => "Missing or empty $field field",
                    'data' => []
                ]);
            }
        }

        // Retrieve and validate the token
        $token = $request->input('token');
        $tutor = DB::table('tutors')->where('token', $token)->first();

        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'tutor not found.'

            ]);
        }

        // Retrieve the class schedule ID, status, and status reason from the request
        $classScheduleId = $request->input('class_schedule_id');
        $status = $request->input('status');
        $statusReason = $request->input('statusReason');

        // Retrieve class schedule
        $getClassSchedule = DB::table('class_schedules')
            ->where('id', '=', $classScheduleId)
            ->where('tutorID', '=', $tutor->id) // Ensure the class schedule belongs to the tutor
            ->first();

        if (!$getClassSchedule) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'Class schedule not found or does not belong to the tutor.',
            ]);
        }

        // Update class attendance status
        DB::table('class_attendeds')
            ->where('class_schedule_id', $classScheduleId)
            ->update(['status' => $status, 'statusReason' => $statusReason]);

        // Update class schedule status
        DB::table('class_schedules')
            ->where('id', $classScheduleId)
            ->update(['status' => $status, 'statusReason' => $statusReason]);

        $getClassScheduleAfterUpdate = DB::table('class_schedules')->where('id', '=', $classScheduleId)->first();

        // Handle "cancelled" or "postponed" status
        if ($status == "cancelled" || $status == "postponed") {
            $job_ticket = DB::table("job_tickets")->where("id", $getClassSchedule->ticketID)->first();

            if ($job_ticket) {
                $remainingClasses = $job_ticket->remaining_classes + 1;
                DB::table("job_tickets")->where("id", $job_ticket->id)->update(["remaining_classes" => $remainingClasses]);

                DB::table('student_subjects')
                    ->where('ticket_id', $job_ticket->id)
                    ->update(['remaining_classes' => $remainingClasses]);
            }
            
            $students = DB::table('students')->where('id', $getClassSchedule->studentID)->first();
        
            $parenttitle = 'Reschedule / Cancellation';
            $parentmessage = 'Class rescheduled by ' .$tutor->full_name. '. Check the new time.';
            // Notification data
            $notificationdata = [
                'Sender' => 'Schedule'
            ];
    
            $customers = DB::table('customers')->where('id', $students->customer_id)->first();
            $parent_device_tokens = DB::table('parent_device_tokens')->where('parent_id', '=', $customers->id)->distinct()->get(['device_token', 'parent_id']);
            foreach ($parent_device_tokens as $token) {
                $deviceToken = $token->device_token;
                // Dispatch push notification job
                SendPushNotificationJob::dispatch($deviceToken, $parenttitle, $parentmessage, $notificationdata);
            }
            
            // Store notification in the database
            DB::table('notifications')->insert([
                'page' => 'Notifications',
                'token' => $customers->token,
                'title' => $parenttitle,
                'message' => $parentmessage,
                'type' => 'parent',
                'status' => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $subjects = DB::table('products')->where('id', $job_ticket->subjects)->first();

            // if($customers->phone!=null)
            // {
            //     $phone = $customers->phone;
            //     if($status  == "cancelled"){
            //         $smsmessage = "Class Alert – {$students->full_name}'s {$subjects->name} class on {$getClassSchedule->date} has been canceled. Please check the app for updates.";
            //     } else {
            //         $smsmessage = "Class Update – {$students->full_name}'s {$subjects->name} class has been rescheduled. Check the app for details.";
            //     }
            //     $this->sendMessage($phone, $smsmessage);
            // }
        }

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Class status has been updated.',
            'data' => ['status' => $status, 'record' => $getClassScheduleAfterUpdate]
        ]);
    }

    public function getStudentSubjects(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing token field',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Empty token field',
            ]);
        }


        $tutor = DB::table('tutors')->where('token', '=', $request->token)->first();

        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'Tutor not found.'
            ]);
        }

        // Validate the tutor exists in the database
        $tutorExists = DB::table('tutors')->where('id', $tutor->id)->exists();
        if (!$tutorExists) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'Tutor not found in the database.'

            ]);
        }

        // Retrieve the student ID from request
        $studentID = $request->input('student_id');

        if (!$studentID) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'Student ID is required.'
            ]);
        }

        // Check if the student exists in the database
        $studentExists = DB::table('students')->where('id', $studentID)->exists();
        if (!$studentExists) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'Student not found.',
            ]);
        }
        
        // Proceed to fetch student subjects
        $studentSubjects = DB::table('class_schedules')
            ->join('student_subjects', 'class_schedules.studentID', '=', 'student_subjects.student_id')
            ->join('products', 'class_schedules.subjectID', '=', 'products.id')
            ->join('job_tickets', 'class_schedules.ticketID', '=', 'job_tickets.id')
            ->where('class_schedules.studentID', '=', $studentID)
            ->whereIn('class_schedules.status', ['pending', 'attended'])
            ->where('class_schedules.tutorID', '=', $tutor->id)
            ->select('class_schedules.*', 'student_subjects.quantity as quantity','student_subjects.classFrequency as classFrequency', 
            'class_schedules.subjectID as subject_id' ,'products.category','class_schedules.status as class_schedule_status','job_tickets.remaining_classes as remaining_classes',
            'job_tickets.classFrequency as classFrequency'
            )
            ->groupBy("class_schedules.id")
            ->get();
        
        // return response()->json($studentSubjects);
                        

        foreach ($studentSubjects as $key => $subject) {
            $product = DB::table('products')->where("id", $subject->subjectID)->first();
            $category = DB::table('categories')->where("id", $product->category)->first();

            $studentSubjects[$key]->subject_name = $product->name." - ".$category->mode;
            $studentSubjects[$key]->total_classes = $subject->classFrequency;
            $studentSubjects[$key]->remaining_classes = $subject->remaining_classes;
        }

        if ($studentSubjects->isEmpty()) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'No records found.'

            ]);
        }

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Data retrieved successfully.',
            'data' => $studentSubjects
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
                    'error' => "Missing or empty $field field"
                ]);
            }
        }

        // Validate token
        $token = $request->input('token');
        $tutor = DB::table('tutors')->where('token', $token)->first();

        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'tutor not found.'
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

    public function tutorNewsStatusList(Request $request)
    {

        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing token field',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Empty token field',
            ]);
        }

        // Validate token
        $token = $request->input('token');
        $tutor = DB::table('tutors')->where('token', $token)->first();

        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'tutor not found.'

            ]);
        }

        // Retrieve tutorID from the validated tutor
        $tutorID = $tutor->id;

        // Fetch tutor news status
        $tutor_news_status = DB::table('tutor_news_status')
            ->where('tutorID', $tutorID)
            ->orderBy('id', 'desc')
            ->get();

        // Return response
        return response()->json([
            'ResponseCode' => '100',
            'message' => 'News status list retrieved successfully.',
            'data' => $tutor_news_status
        ]);
    }

    public function detailedNews(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing token field',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Empty token field',
            ]);
        }

        // Validate that the id is present in the request
        if (!$request->has('id')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing id field',
            ]);
        }

        if (empty($request->id)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Empty id field',
            ]);
        }

        $id = $request->id;
        $token = $request->input('token');
        $tutor = DB::table('tutors')->where('token', $token)->first();

        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'tutor not found.'

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

    public function editTutorProfile(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing token field',
            ]);
        }
    
        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Empty token field',
            ]);
        }
    
        // Validate token
        $token = $request->input('token');
        $tutor = DB::table('tutors')->where('token', $token)->first();
    
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'Tutor not found.',
            ]);
        }
    
        // Initialize update array
        $updateData = [];
    
        // Handle image file if present
        if ($request->hasFile('tutorImage')) {
            $file = $request->file('tutorImage');
            $tutorImage = time() . '.' . $file->extension();
            $file->move(public_path('tutorImage'), $tutorImage);
            $updateData['tutorImage'] = url("/public/tutorImage/{$tutorImage}");
        }
    
        // Fields that should be camel-cased
        $camelCaseFields = ['email', 'display_name', 'phone_number', 'whatsapp', 'gender', 'age', 'nric'];
        $otherFields = ['open_dashboard'];
    
        // Apply Str::camel() to camel-case fields
        foreach ($camelCaseFields as $field) {
            if ($request->filled($field)) {
                $updateData[Str::camel($field)] = $request->input($field);
            }
        }
    
        // Handle fields that should remain in snake case
        foreach ($otherFields as $field) {
            if ($request->filled($field)) {
                $updateData[$field] = $request->input($field);
            }
        }
    
        // Update tutor details if there is any data to update
        if (!empty($updateData)) {
            DB::table('tutors')->where('token', $token)->update($updateData);
        }
    
        // Prepare response data
        $responseData = $updateData;
    
        // Return JSON response
        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Tutor profile updated successfully.',
            'data' => $responseData
        ]);
    }


    public function editStatus(Request $request)
    {
        // Define required fields
        $requiredFields = ['student_id', 'status', 'reasonStatus', 'reasonCategory', 'token'];

        // Check for missing or empty fields
        foreach ($requiredFields as $field) {
            if (!$request->has($field) || empty($request->input($field))) {
                return response()->json([
                    'ResponseCode' => '102',
                    'error' => "Missing or empty $field field",
                ]);
            }
        }

        // Validate token
        $token = $request->input('token');
        $tutor = DB::table('tutors')->where('token', $token)->first();

        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'tutor not found.',
            ]);
        }

        // Check if the student exists
        $studentID = $request->input('student_id');
        $student = DB::table('students')->where('id', $studentID)->first();

        if (!$student) {
            return response()->json([
                'ResponseCode' => '104',
                'error' => 'Student not found.',
            ]);
        }

        // Update the student status
        $affected = DB::table('students')
            ->where('id', $studentID)
            ->update([
                'status' => $request->input('status'),
                'reasonStatus' => $request->input('reasonStatus'),
                'reasonCategory' => $request->input('reasonCategory'),
            ]);

        return response()->json([
            'ResponseCode' => '100',
            'message' => 'Student Status has been updated'

        ]);
    }

    public function getClassAttendedTime(Request $request)
    {
    // Define required fields
    $requiredFields = ['token'];

    // Check for missing or empty fields
    foreach ($requiredFields as $field) {
        if (!$request->has($field) || empty($request->input($field))) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => "Missing or empty $field field",
            ]);
        }
    }

    // Validate token
    $token = $request->input('token');
    $tutor = DB::table('tutors')->where('token', $token)->first();

    if (!$tutor) {
        return response()->json([
            'ResponseCode' => '101',
            'error' => 'Tutor not found.',
        ]);
    }

    // Get tutorID from the validated tutor
    $tutorID = $tutor->id;

    // Query class attendance time
    $classAttendedTime = DB::table('class_attendeds')
        ->join('products', 'products.id', '=', 'class_attendeds.subjectID')
        ->join('students', 'students.id', '=', 'class_attendeds.studentID')
        ->join('job_tickets', 'class_attendeds.ticketID', '=', 'job_tickets.id')
        ->leftJoin('cities', 'students.city', '=', 'cities.id')
        ->leftJoin('categories', 'products.category', '=', 'categories.id')
        ->whereNotNull('class_attendeds.endTime') // Corrected spelling from endTIme to endTime
        ->where('class_attendeds.tutorID', '=', $tutorID)
        ->select(
            'class_attendeds.*',
            'students.id as studentID',
            'students.full_name as studentName',
            'products.id as subjectID',
            'products.name as subjectName',
            'job_tickets.uid as jtid',
            'cities.name as city',
            'categories.category_name as level',
            'job_tickets.mode as classMode'
        )
        ->get();

    // Check if no data found
    if ($classAttendedTime->isEmpty()) {
        return response()->json([
            'ResponseCode' => '104',
            'error' => 'No data found',
        ]);
    }

    // Process each class attendance record
    foreach ($classAttendedTime as $time) {
        $time->totalPrice = number_format($time->commission, 2, '.', '');
        $time->totalTime = $time->totalTime;

        // Format date
        $dateTime = new DateTime($time->date);
        $dayString = $dateTime->format('d-M-Y');
        $time->classDate = $dayString;

        // Set default status if null
        $time->status = $time->status ?? "pending";
    }

    return response()->json([
        'ResponseCode' => '100',
        'message' => 'Class attended time retrieved successfully.',
        'data' => $classAttendedTime
    ]);
}

    private function sendVerificationMessage($phone, $message)
    {
        $whatsapp_api = new WhatsappApi();
        $sms_api = new SmsNiagaApi();

        $whatsapp_api->send_message($phone, $message);
        $sms_api->sendSms($phone, $message);

        DB::table('text_messages')->insert([
            'recipient' => $phone,
            'message' => $message,
            'status' => 'sent',
        ]);
    }

    public function getCancelledHours(Request $request)
    {
        // Define required fields
        $requiredFields = ['token'];

        // Check for missing or empty fields
        foreach ($requiredFields as $field) {
            if (!$request->has($field) || empty($request->input($field))) {
                return response()->json([
                    'ResponseCode' => '102',
                    'error' => "Missing or empty $field field",
                ]);
            }
        }

        // Validate token
        $token = $request->input('token');
        $tutor = DB::table('tutors')->where('token', $token)->first();

        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'tutor not found.',
            ]);
        }

        // Get tutorID from the validated tutor
        $tutorID = $tutor->id;
        $rounded = DB::table('class_schedules')->where('tutorID', '=', $tutorID)->where('status', '=', 'cancelled')->sum('totalTime');
        $cancelledHours = number_format((float)$rounded, 2, '.', '');
        return Response::json(['cancelledHours' => $cancelledHours]);
    }
    
    public function GetStudentReportsListing(Request $request)
    {
        // Validate that the token is present in the request
        if (!$request->has('token')) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Missing token field',
            ]);
        }

        if (empty($request->token)) {
            return response()->json([
                'ResponseCode' => '102',
                'error' => 'Empty token field',
            ]);
        }

        $token = $request->token;
        $tutor = DB::table('tutors')->where('token', $token)->first();

        // Return error response if no tutor found
        if (!$tutor) {
            return response()->json([
                'ResponseCode' => '101',
                'error' => 'No tutor found',
            ]);
        }

        $baseUrl = rtrim(url("/template/"), '/') . '/';
        $tutorID = $tutor->id;

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
            ->where('tutors.id', $tutorID)
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
            ->where('tutors.id', $tutorID)
            ->orderBy('progressReport.id', 'desc')
            ->get();

        // Return error response if no reports are found
        if ($evaluationReportListing->isEmpty() && $progressReportListing->isEmpty()) {
            return response()->json([
                'ResponseCode' => '103',
                'error' => 'No reports found for this tutor.',
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
                    'a2' => $report->plan,
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

    // private function sendMessage($phone, $smsmessage)
    // {
    //     $sms_api = new SmsNiagaApi();
    //     $sms_api->sendSms($phone, $smsmessage);
    // }
}
