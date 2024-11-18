<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Response;

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
        $news = DB::table('news')->get();
        return view('mobileApp/news',Compact('news'));
    }
    public function addNews()
    {
        return view('mobileApp/addNews');
    }
    public function editNews($id)
    {
        $singleNews = DB::table('news')->where('id','=',$id)->first();
        return view('mobileApp/editNews',Compact('singleNews'));
    }
    public function deleteNews($id)
    {
        $news_values = array('is_deleted' => 1);

        $var1 = DB::table('news')->where('id', $id)->update($news_values);

        return redirect()->back();
    }

    public function submitNews(Request $request)
    {





        // $request->validate([
        //     'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        // ]);

        $imageName = time().'.'.$request->image->extension();

        $request->image->move(public_path('MobileNewsImages'), $imageName);

        $values = array(
            'subject' => $request->subject,
            'headerimage' => $imageName,
            'preheader' => $request->preheader,
            'content' => ($request->editor1),
            'status' => $request->status,
        );

        $customerLastID = DB::table('news')->insertGetId($values);



        return redirect('MobileAppNews');
    }
    public function singleMobileAppNews($id)
    {
        $singleNews = DB::table('news')->where('id','=',$id)->first();
        return view('mobileApp/viewNews',Compact('singleNews'));
    }


    public function mobileAppFAQ()
    {
        //
        $news = DB::table('faqs')->get();
        return view('mobileApp/faqs',Compact('news'));
    }
    public function addFAQ()
    {
        return view('mobileApp/addFAQ');
    }
    public function editFAQ($id)
    {
        $singleFAQ = DB::table('faqs')->where('id','=',$id)->first();
        return view('mobileApp/editFAQ',Compact('singleFAQ'));
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
        $singleNews = DB::table('faqs')->where('id','=',$id)->first();
        return view('mobileApp/viewFAQ',Compact('singleNews'));
    }





    public function deleteNotification($id)
    {

        $notificationValues = ['is_deleted' => 1];
        $result = DB::table('notifications')->where('id', $id)->update($notificationValues);
        
        return redirect()->back();
    }


     public function notification()
    {
        //
        $notifications = DB::table('notifications')->where("is_deleted","0")->get();

        return view('mobileApp/notification',Compact('notifications'));
    }
    public function addNotification()
    {
        $tutors = DB::table('tutors')->get();
        $students = DB::table('students')->get();
        $subjects = DB::table('products')->get();
        return view('mobileApp/addNotification',Compact('tutors','students','subjects'));
    }

    public function submitNotification(Request $request)
    {
        $tutorName = DB::table('tutors')->where('id','=',$request->tutorID)->first();
        $studentName = DB::table('students')->where('id','=',$request->studentID)->first();
        $subjectName = DB::table('products')->where('id','=',$request->subjectID)->first();
        if($request->subjectID == NULL){
            $values = array(
                'NotificationType' => $request->notificationType,
                'progressReportMonth' => $request->progressReportMonth,
                'tutorID' => $request->tutorID,
                'studentID' => $request->studentID,
                'subjectID' => $request->subjectID,
                'message' => $request->notificationType.', TutorName:'.$tutorName->full_name.', Student Name:'.$studentName->full_name,
                );
        }else{
            $values = array(
                'NotificationType' => $request->notificationType,
                'progressReportMonth' => $request->progressReportMonth,
                'tutorID' => $request->tutorID,
                'studentID' => $request->studentID,
                'subjectID' => $request->subjectID,
                'message' => $request->notificationType.', TutorName:'.$tutorName->full_name.', Student Name:'.$studentName->full_name.', Subject:'.$subjectName->name
                );
        }

        $customerLastID = DB::table('notifications')->insertGetId($values);

        return redirect('notification');
    }

     public function selfPushNotification()
    {
        //
        $selfPushNotifications = DB::table('selfpushnotifications')->get();
        return view('mobileApp/selfPushNotification',Compact('selfPushNotifications'));
    }
    public function submitSelfPushNotification(Request $request)
    {
        //
        $selfPushNotifications = DB::table('selfpushnotifications')->get();
        return view('mobileApp/selfPushNotification',Compact('selfPushNotifications'));
    }

    public function addSelfPushNotification()
    {
        return view('mobileApp/addSelfPushNotification');
    }

     public function bannerAds()
    {
        //
               $bannerAds = DB::table('bannerads')->get();

        return view('mobileApp/bannerAds',Compact('bannerAds'));
    }
         public function deleteBannerAds($id)
    {
        $bannerAds_values = array('is_deleted' => 1);

        $var1 = DB::table('bannerads')->where('id', $id)->update($bannerAds_values);

        return redirect()->back();
    }

    public function addBannerAds()
    {
        return view('mobileApp/addBannerAds');
    }
    public function submitBannerAds(Request $request){

        $imageName = time().'.'.$request->BannerImage->extension();
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

    public function submitCreditorInvoice(Request $request){

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

    public function submitSaleInvoice(Request $request){

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
    public function addSaleInvoice ()
    {
        //
        $students = DB::table('students')->get();
        $subjects = DB::table('products')->get();
        return view('student/addSaleInvoice',Compact('students','subjects'));
    }

    public function addJournalLedger()
    {
        $ledgers = DB::table('ledgers')->orderBy('id','DESC')->get();
        $chartOfAccounts = DB::table('chart_accounts')->orderBy('id','DESC')->get();
        return view('journalLedger/addJournalLedger', Compact('ledgers','chartOfAccounts'));
    }
    public function submitJournalLedger(Request $request)
    {
        $data = $request->all();
        $accountID = $data['chartOfAccounts'];

            for ($i = 0; $i < count($accountID); $i++) {
                if($data['debit'][$i] > 0){
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
                }else{
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

    public function viewJournalLedger($id){

        $viewLedgerEntry = DB::table('ledgers')->join('chart_accounts', 'chart_accounts.id', '=', 'ledgers.accountID')
                ->select('ledgers.*', 'chart_accounts.*', 'chart_accounts.name as accountName', 'ledgers.description as ledgerDescription')
                ->where('ledgers.type','=','d')->where('ledgers.id','=',$id)->first();

        $viewLedgerEntryCredit = DB::table('ledgers')->join('chart_accounts', 'chart_accounts.id', '=', 'ledgers.accountID')
                ->select('ledgers.*', 'chart_accounts.*', 'chart_accounts.name as accountName')
                ->where('ledgers.type','=','c')->where('ledgers.description','=',$viewLedgerEntry->ledgerDescription)->first();

        return view('journalLedger/viewJournalLedger', Compact('viewLedgerEntry','viewLedgerEntryCredit'));

    }

}
