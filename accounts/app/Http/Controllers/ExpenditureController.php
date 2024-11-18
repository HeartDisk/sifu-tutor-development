<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Response;

class ExpenditureController extends Controller
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
    public function expenditures()
    {
        //
        $expenditures = DB::table('expenditures')->orderBy('id','DESC')->get();
        //dd($expenditures);



        return view('expenditures/index', Compact('expenditures'));
    }


    public function deleteExpenditures($id)
    {
        DB::table('expenditures')->where('id',$id)->delete();
        return redirect('expenditures');

    }

    public function submitExpenditures(Request $request){

         $expenditureValues = array(
                            'occuranceDate' => $request->transactionDate,
                            'accountId' => $request->chartOfAccounts,
                            'description' => $request->description,
                            'quantity' => $request->quantity,
                            'costPerUnit' => $request->costPerUnit,
                            'total' => $request->costPerUnit * $request->quantity,
                            'PayingAccountId' => $request->PayingAccountId,
                            'paymentDate' => $request->paymentDate,
                            'remarks' => $request->remarks,
                            );
        $expendituresLastID = DB::table('expenditures')->insertGetId($expenditureValues);

        return redirect('expenditures');

    }

    public function addExpenditure()
    {
        //
        $ledgers = DB::table('ledgers')->orderBy('id','DESC')->get();
//        $chartOfAccounts = DB::table('chart_accounts')->orderBy('id','DESC')->get();
        $chartOfAccounts = DB::table('accounts')->where("type","Expense")->orderBy('id','DESC')->get();

        return view('expenditures/addExpenditure', Compact('ledgers','chartOfAccounts'));
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
