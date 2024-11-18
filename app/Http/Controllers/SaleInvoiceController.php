<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Response;

class SaleInvoiceController extends Controller
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
    public function saleInvoice()
    {
        //
        $invoices = DB::table('saleinvoices')->get();
        return view('student/saleInvoice', Compact('invoices'));
    }
    
    public function paymentList()
    {
         $invoices = DB::table('invoicePayments')->leftjoin("saleinvoices","invoicePayments.invoiceID","=","saleinvoices.id")
         ->select("invoicePayments.*","saleinvoices.referenceNumber as referenceNumber")
         ->get();
        // dd($invoices);
        //  dd("here");
        return view('student/viewPayment', Compact('invoices'));
    }


    public function submitSaleInvoice(Request $request)
    {

        $data = $request->all();
        $studentsID = $data['students'];

        $invoiceTotal = 0;
        for ($i = 0; $i < count($studentsID); $i++) {
            $invoiceTotal += $data['quantity'][$i] * $data['unitPrice'][$i];
        }
        
        $saleInvoiceValues = array(
            'invoiceDate' => $request->invoiceDate,
            'referenceNumber' => $request->reference,
            'managementStatus' => $request->managementStatus,
            'payerName' => $request->payerName,
            'payerEmail' => $request->payerEmail,
            'payerPhone' => $request->payerPhoneNumber,
            'status' => 'Paid',
            'invoiceTotal' => $invoiceTotal,
            'remarks' => $request->remarks
        );
        $saleInvoiceLastID = DB::table('saleinvoices')->insertGetId($saleInvoiceValues);


        for ($i = 0; $i < count($studentsID); $i++) {

            $journalLedgerValues = array(
                'saleInvoiceID' => $saleInvoiceLastID,
                'studentID' => $data['students'][$i],
                'subjectID' => $data['subject'][$i],
                'quantity' => $data['quantity'][$i],
                'unitPrice' => $data['unitPrice'][$i],
                'description' => $data['description'][$i]
            );
            $journalLedgerLastID = DB::table('saleinvoicitems')->insertGetId($journalLedgerValues);
        }


        return redirect('saleInvoice');

    }
    
    public function updateSaleInvoice(Request $request)
    {
        $saleInvoiceID = $request->input('saleInvoiceID');
        
        // Request se data get karein
        $data = $request->all();
        $studentsID = $data['students'];
    
        // Invoice total calculate karna
        $invoiceTotal = 0;
        for ($i = 0; $i < count($studentsID); $i++) {
            $invoiceTotal += $data['quantity'][$i] * $data['unitPrice'][$i];
        }
    
        // Sale invoice update karna
        $saleInvoiceValues = array(
            'invoiceDate' => $request->invoiceDate,
            'referenceNumber' => $request->reference,
            'managementStatus' => $request->managementStatus,
            'payerName' => $request->payerName,
            'payerEmail' => $request->payerEmail,
            'payerPhone' => $request->payerPhoneNumber,
            'status' => 'Paid',
            'invoiceTotal' => $invoiceTotal,
            'remarks' => $request->remarks
        );
        DB::table('saleinvoices')->where('id', $saleInvoiceID)->update($saleInvoiceValues);
    
        // Sale invoice items update karna
        // Pehle purane sale invoice items ko delete karen
        DB::table('saleinvoicitems')->where('saleInvoiceID', $saleInvoiceID)->delete();
    
        // Naye sale invoice items ko add karna
        for ($i = 0; $i < count($studentsID); $i++) {
            $journalLedgerValues = array(
                'saleInvoiceID' => $saleInvoiceID,
                'studentID' => $data['students'][$i],
                'subjectID' => $data['subject'][$i],
                'quantity' => $data['quantity'][$i],
                'unitPrice' => $data['unitPrice'][$i],
                'description' => $data['description'][$i]
            );
            DB::table('saleinvoicitems')->insert($journalLedgerValues);
        }
    
        // Redirect to sale invoice page ya koi aur page as per requirement
        return redirect('saleInvoice');
    }


    public function addSaleInvoice()
    {
        //
        $students = DB::table('students')->get();
        $subjects = DB::table('products')->get();
        return view('student/addSaleInvoice', Compact('students', 'subjects'));
    }

    public function viewSaleInvoice($id)
    {
        //
        $saleInvoice = DB::table('saleinvoices')->where('id', '=', $id)->first();

        $saleInvoiceItems = DB::table("saleinvoicitems")->join("products", "saleinvoicitems.subjectID", "=", "products.id")->
        join("students", "saleinvoicitems.studentID", "=", "students.id")->select("saleinvoicitems.*", "students.full_name as student","products.name as subject")->
        where("saleinvoicitems.saleInvoiceID", $id)->
        get();

        $students = DB::table('students')->get();
        $subjects = DB::table('products')->get();

        return view('student/viewSaleInvoice', Compact('students', 'subjects', 'saleInvoice','saleInvoiceItems'));
    }

    public function editSaleInvoice($id)
    {
        //
        $saleInvoice = DB::table('saleinvoices')->where('id', '=', $id)->first();

        $saleInvoiceItems = DB::table("saleinvoicitems")->join("products", "saleinvoicitems.subjectID", "=", "products.id")->
        join("students", "saleinvoicitems.studentID", "=", "students.id")->select("saleinvoicitems.*", "students.full_name as student","products.name as subject")->
        where("saleinvoicitems.saleInvoiceID", $id)->
        get();

        $students = DB::table('students')->get();
        $subjects = DB::table('products')->get();
        return view('student/editSaleInvoice', Compact('students', 'subjects', 'saleInvoice', 'saleInvoiceItems'));
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
