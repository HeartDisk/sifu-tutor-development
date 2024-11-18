<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Response;

class CreditorInvoiceController extends Controller
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
     
     
     
    public function addCreditorPayment()
    {
        $accounts = DB::table('chart_accounts')->where("type", "Current assets")->get();
        return view("creditorInvoices.addPayment", ["accounts" => $accounts]);
    }

    public function submitCreditorPayment(Request $request)
    {
        $attachmentFile = time() . '.' . $request->attachmentFile->extension();
        $request->attachmentFile->move(public_path('creditorPayment'), $attachmentFile);


        $creditorInvoiceValues = array(
            'creditorName' => $request->creditorName,
            'amount' => $request->amount,
            'paymentDate' => $request->paymentDate,
            'attachment' => $attachmentFile
        );
        $saleInvoiceLastID = DB::table('creditorPayments')->insertGetId($creditorInvoiceValues);
        return redirect('CreditorpaymentList');
    }


    public function CreditorInvoices()
    {
        //
        $creditorInvoices = DB::table('creditorinvoices')->get();
        return view('creditorInvoices/index', Compact('creditorInvoices'));
    }

    public function CreditorpaymentList()
    {

        $creditorInvoices = DB::table('creditorPayments')
            ->get();

        return view('creditorInvoices/viewPayment', Compact('creditorInvoices'));
    }

    public function deleteCreditorPayment($id)
    {
        DB::table('creditorPayments')->where("id", $id)
            ->delete();
        return redirect("/CreditorpaymentList");

    }

    public function ViewCreditorPayment($id)
    {
        $data = DB::table('creditorPayments')->where("id", $id)
            ->first();
        return view("creditorInvoices.viewCreditorPayment", ["data" => $data]);

    }

    public function addCreditorInvoice()
    {
        $accounts = DB::table("chart_accounts")->get();
        return view('creditorInvoices/addCreditorInvoice', ['accounts'=>$accounts]);
    }


    public function viewCreditorInvoice($id)
    {
        $data = DB::table("creditorinvoices")->where("id", $id)->first();

        return view('creditorInvoices/viewCreditorInvoice', ['data' => $data]);
    }

    public function editCreditorInvoice($id)
    {
        //
        $data = DB::table("creditorinvoices")->where("id", $id)->first();
        // dd($data);

        return view('creditorInvoices/editCreditorInvoice', ['data' => $data]);
    }


    public function submitCreditorInvoice(Request $request)
    {
        // dd($request->all());
        
        // File upload process
        if ($request->hasFile('attachmentFile')) {
            $attachmentFile = time() . '.' . $request->file('attachmentFile')->extension();
            $request->file('attachmentFile')->move(public_path('creditorInvoice'), $attachmentFile);
        }

        $creditorInvoiceValues = array(
            'OccuranceDate' => $request->occuranceDate,
            'creditorName' => $request->creditorName,
            'description' => $request->description,
            'category' => $request->category,
            'quantity' => $request->quantity,
            'costPrice' => $request->costPrice,
            'paymentDueDate' => $request->paymentDueDate,
            'attachment' => $attachmentFile,
            'remarks' => $request->remarks
        );
        $saleInvoiceLastID = DB::table('creditorinvoices')->insertGetId($creditorInvoiceValues);
        return redirect('CreditorInvoices');
    }
    
    
    public function UpdateCreditorInvoice(Request $request)
    {
        
        $creditorID = $request->input('creditorID');
        // dd($request->all());
        
        $creditorInvoiceValues = array(
            'OccuranceDate' => $request->occuranceDate,
            'creditorName' => $request->creditorName,
            'description' => $request->description,
            'category' => $request->category,
            'quantity' => $request->quantity,
            'costPrice' => $request->costPrice,
            'paymentDueDate' => $request->paymentDueDate,
            'remarks' => $request->remarks
        );
        DB::table('creditorinvoices')->where('id', $creditorID)->update($creditorInvoiceValues);
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
