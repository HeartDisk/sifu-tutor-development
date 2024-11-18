<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Response;

class PaymentHistoryController extends Controller
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
    public function PaymentHistory()
    {
      
        $staffPayments = DB::table('staff_payments')->join("staffs","staff_payments.staff_id","=","staffs.id")
            ->select("staff_payments.*","staffs.full_name as name")
            ->where("staff_payments.staff_id",Auth::user()->id)
            ->get();
        return view('paymentHistory/index',compact('staffPayments'));
    }

    public function addCreditorInvoice()
    {
        //
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
