<?php

namespace App\Http\Controllers;

use App\Events\Parent\ParentDashbaord;
use Illuminate\Http\Request;
use DB;
use Response;
use App\Jobs\SendPushNotificationJob;
use App\Events\Parent\News as ParentNews;
use App\Events\Tutor\News as TutorNews;
use App\Events\Tutor\TutorNotification;

class MobileAppController extends Controller
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
    public function mobileAppNews()
    {
        //
        $news = DB::table('news')->orderBy("id", "desc")->get();
        return view('mobileApp/news', Compact('news'));
    }

    public function addNews()
    {
        return view('mobileApp/addNews');
    }

    public function editNews($id)
    {
        $singleNews = DB::table('news')->where('id', '=', $id)->first();
        return view('mobileApp/editNews', Compact('singleNews'));
    }

    public function deleteNews($id)
    {
        $news_values = array('is_deleted' => 1);

        $var1 = DB::table('news')->where('id', $id)->update($news_values);

        return redirect()->back();
    }

    public function submitNews(Request $request)
    {

        $imageName = time() . '.' . $request->image->extension();

        $request->image->move(public_path('MobileNewsImages'), $imageName);

        $values = array(
            'subject' => $request->subject,
            'headerimage' => $imageName,
            'preheader' => $request->preheader,
            'content' => $request->editor1,
            'type' => $request->type,
            'status' => $request->status,
        );

        $customerLastID = DB::table('news')->insertGetId($values);

        try {

            //parent tokent
            $data = [
                "ResponseCode" => "100",
                "message" => "News Created Successfully"
            ];

            if ($request->type == "Parent") {
                event(new ParentNews($data));
                event(new ParentDashbaord($data));
                
                // Send push notifications to parent devices
                $parentDevices = DB::table('parent_device_tokens')->distinct()->get(['device_token', 'parent_id']);
                foreach ($parentDevices as $rowDeviceToken) {
                    $customer = DB::table('customers')->where('id', '=', $rowDeviceToken->parent_id)->first();
                    $deviceToken = $rowDeviceToken->device_token;
                    $title = 'News Updates';
                    $message = 'Latest updates! Check the news in the app.';
                
                    $notificationdata = [
                        'Sender' => 'News'
                    ];
                
                    // Dispatch push notification job
                    SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
                
                    // Store notification in the database
                    DB::table('notifications')->insert([
                        'page' => 'News',
                        'token' => $customer->token,
                        'title' => $title,
                        'message' => $message,
                        'type' => 'parent',
                        'status' => 'new',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }


            } else {
                event(new TutorNews($data));
                
                // Send push notifications to tutor devices
                $tutorDevices = DB::table('tutor_device_tokens')->distinct()->get(['device_token', 'tutor_id']);
                foreach ($tutorDevices as $rowDeviceToken) {
                    $tutor = DB::table('tutors')->where('id', '=', $rowDeviceToken->tutor_id)->first();
                    $deviceToken = $rowDeviceToken->device_token;
                    $title = 'News Updates';
                    $message = 'Latest updates! Check the news in the app.';
                
                    $notificationdata = [
                        'Sender' => 'News'
                    ];
                
                    // Dispatch push notification job
                    SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
                
                    // Store notification in the database
                    DB::table('notifications')->insert([
                        'page' => 'News',
                        'token' => $tutor->token,
                        'title' => $title,
                        'message' => $message,
                        'type' => 'tutor',
                        'status' => 'new',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }


        } catch (Exception $e) {
            return response()->json(["ResponseCode" => "103",
                "error" => "Unable to created Job Ticket"]);
        }


        return redirect('MobileAppNews');
    }

    public function submitEditNews(Request $request)
    {

        if ($request->image) {
            $imageName = time() . '.' . $request->image->extension();

            $request->image->move(public_path('MobileNewsImages'), $imageName);
            $values = array(
                'subject' => $request->subject,
                'headerimage' => $imageName,
                'preheader' => $request->preheader,
                'content' => $request->editor1,
                'type' => ($request->type),
                'status' => $request->status,
            );
        } else {

            $values = array(
                'subject' => $request->subject,
                'preheader' => $request->preheader,
                'content' => $request->editor1,
                'type' => ($request->type),
                'status' => $request->status,
            );
        }


        DB::table('news')
            ->where('id', $request->news_id)
            ->update($values);

        return redirect('MobileAppNews');
    }

    public function singleMobileAppNews($id)
    {
        $singleNews = DB::table('news')->where('id', '=', $id)->first();
        return view('mobileApp/viewNews', Compact('singleNews'));
    }


    public function mobileAppFAQ()
    {
        //
        $news = DB::table('faqs')->get();
        return view('mobileApp/faqs', Compact('news'));
    }

    public function addFAQ()
    {
        return view('mobileApp/addFAQ');
    }

    public function editFAQ($id)
    {
        $singleFAQ = DB::table('faqs')->where('id', '=', $id)->first();
        return view('mobileApp/editFAQ', Compact('singleFAQ'));
    }

    public function deleteFAQ($id)
    {
        // $news_values = array('is_deleted' => 1);

        $var1 = DB::table('faqs')->where('id', $id)->delete();

        return redirect()->back();
    }

    public function submitFAQ(Request $request)
    {

        // dd($request->all());
        $values = array(
            'question' => $request->editor1,
            'answer' => $request->editor2,
        );

        $customerLastID = DB::table('faqs')->insertGetId($values);
        //   dd($request->all());
        return redirect('MobileAppFAQ');
    }

    public function singleMobileAppFAQ($id)
    {
        $singleNews = DB::table('faqs')->where('id', '=', $id)->first();
        return view('mobileApp/viewFAQ', Compact('singleNews'));
    }


    public function deleteNotification($id)
    {

        $notificationValues = ['is_deleted' => 1];
        $result = DB::table('reports_notifications')->where('id', $id)->update($notificationValues);

        return redirect()->back();
    }


    public function notification()
    {
        //
        $notifications = DB::table('reports_notifications')->where("is_deleted", "0")->orderBy("id", "desc")->get();

        return view('mobileApp/notification', Compact('notifications'));
    }

    public function addNotification()
    {
        $tutors = DB::table('tutors')->get();
        $students = DB::table('students')->get();
        $subjects = DB::table('products')->get();
        return view('mobileApp/addNotification', Compact('tutors', 'students', 'subjects'));
    }
    
    public function getStudentSubjects(Request $request)
    {
        $studentID = $request->studentID;
        $tutorID = $request->tutorID;

        // Retrieve student subjects with joins and filtering
        $studentSubjects = DB::table('class_schedules')
            ->join('student_subjects', 'class_schedules.studentID', '=', 'student_subjects.student_id')
            ->join('products', 'class_schedules.subjectID', '=', 'products.id')
            ->join('job_tickets', 'class_schedules.ticketID', '=', 'job_tickets.id')
            ->where('class_schedules.studentID', $studentID)
            ->where('class_schedules.tutorID', $tutorID)
            ->whereIn('class_schedules.status', ['pending', 'attended'])
            ->select('class_schedules.subjectID as subject_id', 'products.name as product_name', 'products.category as category_id')
            ->groupBy('class_schedules.subjectID', 'products.name', 'products.category')
            ->get();
    
        // Map through subjects to enrich them with category information
        $studentSubjects = $studentSubjects->map(function ($subject) {
            // Check if the category_id is set
            $category = null;
            if (isset($subject->category_id)) {
                $category = DB::table('categories')->where("id", $subject->category_id)->first();
            }
    
            // Assign subject name with category if available
            $subject->subject_name = $subject->product_name . " - " . ($category->mode ?? 'Unknown');
    
            return $subject;
        });
    
        return response()->json($studentSubjects);
    }

    
    public function getTutorStudents(Request $request)
    {
        // Get the selected tutor ID from the request
        $tutorID = $request->input('tutorID'); 
    
        // Retrieve the tutor's student records in a single query
        $tutorStudentRecords = DB::table('students')
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
            ->get();
    
        // Return the student records as a JSON response
        return response()->json($tutorStudentRecords);
    }




    // public function submitNotification(Request $request)
    // {
    //     $tutorName = DB::table('tutors')->where('id', '=', $request->tutorID)->first();
    //     $studentName = DB::table('students')->where('id', '=', $request->studentID)->first();
    //     $subjectName = DB::table('products')->where('id', '=', $request->subjectID)->first();
    //     if ($request->subjectID == NULL) {
    //         $values = array(
    //             'NotificationType' => $request->notificationType,
    //             'progressReportMonth' => $request->progressReportMonth,
    //             'tutorID' => $request->tutorID,
    //             'studentID' => $request->studentID,
    //             'subjectID' => $request->subjectID,
    //             'message' => $request->notificationType . ', TutorName:' . $tutorName->full_name . ', Student Name:' . $studentName->full_name,
    //         );
    //     } else {
    //         $values = array(
    //             'NotificationType' => $request->notificationType,
    //             'progressReportMonth' => $request->progressReportMonth,
    //             'tutorID' => $request->tutorID,
    //             'studentID' => $request->studentID,
    //             'subjectID' => $request->subjectID,
    //             'message' => $request->notificationType . ', TutorName:' . $tutorName->full_name . ', Student Name:' . $studentName->full_name . ', Subject:' . $subjectName->name
    //         );
    //     }
        
    //     $tutor = DB::table("tutors")->where("id",$request->tutorID)->first();
    //     try {
    //         $data = [
    //             "ResponseCode" => "100",
    //             "message" => $request->notificationType." notification"
    //         ];
    //         //tutor
    //         event(new TutorNotification($data, $tutor->token));

    //     } catch (Exception $e) {
    //         return response()->json(["ResponseCode" => "103",
    //             "error" => "Unable to get New Class Schedule"]);
    //     }

    //     $customerLastID = DB::table('reports_notifications')->insertGetId($values);

    //     return redirect('notification');
    // }
    
    public function submitNotification(Request $request)
    {
        // Validate the request
        $request->validate([
            'notificationType' => 'required',
            'tutorID' => 'required|exists:tutors,id',
            'studentID' => 'required|exists:students,id',
            'subjectID' => 'nullable|exists:products,id', // Make sure it points to the correct table
            'progressReportMonth' => 'nullable'
        ]);
    
        // Fetch tutor and student information
        $tutor = DB::table('tutors')->where('id', $request->tutorID)->first();
        $student = DB::table('students')->where('id', $request->studentID)->first();
    
        // Initialize the values array with common fields
        $values = [
            'NotificationType' => $request->notificationType,
            'tutorID' => $request->tutorID,
            'studentID' => $request->studentID,
            'subjectID' => $request->subjectID,
            'progressReportMonth' => $request->progressReportMonth,
        ];
    
        // Compose the message based on available data
        $message = $request->notificationType . ', Tutor Name: ' . $tutor->full_name . ', Student Name: ' . $student->full_name;
    
        // Add subject information if available
        if ($request->subjectID) {
            $subject = DB::table('products')->where('id', $request->subjectID)->first();
            $message .= ', Subject: ' . $subject->name;
        }
    
        // Add the message to the values array
        $values['message'] = $message;
    
        try {
            // Prepare data for the event
            $eventData = [
                "ResponseCode" => "100",
                "message" => $message
            ];
            
            // Fetch all device tokens associated with the tutor
            $deviceTokens = DB::table('tutor_device_tokens')
                ->where('tutor_id', $request->tutorID) // Assuming 'tutor_id' is the foreign key in the 'tutor_device_tokens' table
                ->pluck('device_token'); // Fetch only device tokens
    
            // Dispatch the job for each device token
            foreach ($deviceTokens as $token) {
                // Create notification data dynamically
                $notificationdata = [
                    'Sender' => $request->notificationType,
                ];
    
                // Dispatch the notification job with the device token
                SendPushNotificationJob::dispatch($token, 'New Notification', $message, $notificationdata);
            }
    
            // Trigger the event with the tutor's token
            event(new TutorNotification($eventData, $tutor->token));
    
        } catch (Exception $e) {
            return response()->json([
                "ResponseCode" => "103",
                "error" => "Unable to send notification."
            ]);
        }
    
        // Insert the notification into the database and get the inserted ID
        $customerLastID = DB::table('reports_notifications')->insertGetId($values);
    
        // Redirect back to the notification list page with a success message
        return redirect()->route('notification')->with('success', 'Notification has been added successfully.');
    }


    public function selfPushNotification()
    {
        //
        $selfPushNotifications = DB::table('selfpushnotifications')->get();
        return view('mobileApp/selfPushNotification', Compact('selfPushNotifications'));
    }

    public function submitSelfPushNotification(Request $request)
    {
        //
        $selfPushNotifications = DB::table('selfpushnotifications')->get();
        return view('mobileApp/selfPushNotification', Compact('selfPushNotifications'));
    }

    public function addSelfPushNotification()
    {
        return view('mobileApp/addSelfPushNotification');
    }

    public function bannerAds()
    {
        //
        $bannerAds = DB::table('bannerads')->get();

        return view('mobileApp/bannerAds', Compact('bannerAds'));
    }

    public function deleteBannerAds($id)
    {
        DB::table('bannerads')->where('id', $id)->delete();

        $bannerAds_values = array('is_deleted' => 1);

        $var1 = DB::table('bannerads')->where('id', $id)->update($bannerAds_values);

        return redirect()->back();
    }

    public function addBannerAds()
    {
        return view('mobileApp/addBannerAds');
    }

    public function submitBannerAds(Request $request)
    {

        $imageName = time() . '.' . $request->BannerImage->extension();
        $request->BannerImage->move(public_path('BannerImage'), $imageName);

        $bannerValues = array(
            'title' => $request->Title,
            'displayOnPage' => $request->DisplayOnPage,
            'bannerImage' => $imageName,
            'tutorStatusCriteria' => $request->TutorStatusCriteria,
            'callToActionType' => $request->CallToActionType,
            'urlToOpen' => $request->UrlToOpen,
            'pageToOpen' => $request->PageToOpen,
            'displayOnce' => $request->DisplayOnce
        );
        $saleInvoiceLastID = DB::table('bannerads')->insertGetId($bannerValues);

        return redirect('bannerAds');
    }

    public function addCreditorInvoice()
    {
        return view('creditorInvoices/addCreditorInvoice');
    }

    public function submitCreditorInvoice(Request $request)
    {

        $creditorInvoiceValues = array(
            'OccuranceDate' => $request->occuranceDate,
            'creditorName' => $request->creditorName,
            'description' => $request->description,
            'quantity' => $request->quantity,
            'costPrice' => $request->costPrice,
            'paymentDueDate' => $request->paymentDueDate,
            'remarks' => $request->remarks
        );
        $saleInvoiceLastID = DB::table('creditorInvoices')->insertGetId($creditorInvoiceValues);
        return redirect('CreditorInvoices');
    }

    public function submitSaleInvoice(Request $request)
    {

        $data = $request->all();
        $studentsID = $data['students'];

        $saleInvoiceValues = array(
            'invoiceDate' => $request->invoiceDate,
            'referenceNumber' => $request->reference,
            'managementStatus' => $request->managementStatus,
            'payerName' => $request->payerName,
            'payerEmail' => $request->payerEmail,
            'payerPhone' => $request->payerPhoneNumber,
            'status' => 'Paid',
            'invoiceTotal' => array_sum($data['quantity']) * array_sum($data['unitPrice']),
            'remarks' => $request->remarks
        );
        $saleInvoiceLastID = DB::table('saleInvoices')->insertGetId($saleInvoiceValues);


        for ($i = 0; $i < count($studentsID); $i++) {

            $journalLedgerValues = array(
                'saleInvoiceID' => $saleInvoiceLastID,
                'studentID' => $data['students'][$i],
                'subjectID' => $data['subject'][$i],
                'quantity' => $data['quantity'][$i],
                'unitPrice' => $data['unitPrice'][$i],
                'description' => $data['description'][$i]
            );
            $journalLedgerLastID = DB::table('saleInvoicItems')->insertGetId($journalLedgerValues);
        }


        return redirect('saleInvoice');

    }

    public function addSaleInvoice()
    {
        //
        $students = DB::table('students')->get();
        $subjects = DB::table('products')->get();
        return view('student/addSaleInvoice', Compact('students', 'subjects'));
    }

    public function addJournalLedger()
    {
        $ledgers = DB::table('ledgers')->orderBy('id', 'DESC')->get();
        $chartOfAccounts = DB::table('chart_accounts')->orderBy('id', 'DESC')->get();
        return view('journalLedger/addJournalLedger', Compact('ledgers', 'chartOfAccounts'));
    }

    public function submitJournalLedger(Request $request)
    {
        $data = $request->all();
        $accountID = $data['chartOfAccounts'];

        for ($i = 0; $i < count($accountID); $i++) {
            if ($data['debit'][$i] > 0) {
                $journalLedgerValues = array(
                    'description' => $request->description,
                    'transactionDate' => $request->transactionDate,
                    'supportingDocumentDate' => $request->supportingDocumentDate,
                    'accountID' => $data['chartOfAccounts'][$i],
                    'debit' => $data['debit'][$i],
                    'type' => 'd',
                    'credit' => $data['credit'][$i],
                );
                $journalLedgerLastID = DB::table('ledgers')->insertGetId($journalLedgerValues);
            } else {
                $journalLedgerValues = array(
                    'description' => $request->description,
                    'transactionDate' => $request->transactionDate,
                    'supportingDocumentDate' => $request->supportingDocumentDate,
                    'accountID' => $data['chartOfAccounts'][$i],
                    'debit' => $data['debit'][$i],
                    'type' => 'c',
                    'credit' => $data['credit'][$i],
                );
                $journalLedgerLastID = DB::table('ledgers')->insertGetId($journalLedgerValues);
            }

        }

        return redirect('journalLedger');
    }

    public function viewJournalLedger($id)
    {

        $viewLedgerEntry = DB::table('ledgers')->join('chart_accounts', 'chart_accounts.id', '=', 'ledgers.accountID')
            ->select('ledgers.*', 'chart_accounts.*', 'chart_accounts.name as accountName', 'ledgers.description as ledgerDescription')
            ->where('ledgers.type', '=', 'd')->where('ledgers.id', '=', $id)->first();

        $viewLedgerEntryCredit = DB::table('ledgers')->join('chart_accounts', 'chart_accounts.id', '=', 'ledgers.accountID')
            ->select('ledgers.*', 'chart_accounts.*', 'chart_accounts.name as accountName')
            ->where('ledgers.type', '=', 'c')->where('ledgers.description', '=', $viewLedgerEntry->ledgerDescription)->first();

        return view('journalLedger/viewJournalLedger', Compact('viewLedgerEntry', 'viewLedgerEntryCredit'));

    }

}
