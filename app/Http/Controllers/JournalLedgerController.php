<?php

namespace App\Http\Controllers;

use App\Models\Ledger;
use App\Models\LedgerItem;
use App\Models\ChartOfAccount;
use Illuminate\Http\Request;
use DB;
use Response;

class JournalLedgerController extends Controller
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
    public function JournalLedger()
    {

        $ledgers = DB::table('ledgers')->orderBy('id', 'DESC')->get();
        return view('journalLedger/index', Compact('ledgers'));
    }

    public function viewJournalLedger($id)
    {

        $ledger = Ledger::find($id);
        $ledgerItems=LedgerItem::join('chart_accounts', 'chart_accounts.id', '=', 'ledger_items.account_id')
            ->select('ledger_items.*', 'chart_accounts.*', 'chart_accounts.name as accountName')
            ->where('ledger_items.ledger_id', '=', $id)->get();

        return view('journalLedger/viewJournalLedger', Compact('ledger', 'ledgerItems'));

    }

    public function reportGeneralLedger()
    {
        
        // $listOfAccounts=ChartOfAccount::all();
        
       $listOfAccounts = ChartOfAccount::with(['ledgerItems' => function ($query) {
            $query->has('ledger'); // Ensure ledger items have an associated ledger
        }])->get()->filter(function ($account) {
            return $account->ledgerItems->isNotEmpty(); // Filter out accounts with no ledger items
        });
        
        $result = [];
        
        foreach ($listOfAccounts as $account) {
            $ledgerItems = $account->ledgerItems->map(function ($ledgerItem) {
                return [
                    'ledger_item' => $ledgerItem->toArray(),
                    'ledger' => $ledgerItem->ledger->toArray()
                ];
            });
        
            $result[$account->name] = $ledgerItems;
        }


        // dd($result);
        
        // dd($listOfAccounts[0]);
        
        // $ledgers = DB::table('ledgers')
        //     ->select('accountID')
        //     ->groupBy('accountID')
        //     ->get();
        
        // dd($result);
        
       
        
        return view('journalLedger/reportGeneralLedger', Compact('result'));
    }

    public function addJournalLedger()
    {

        $chartOfAccounts = DB::table('chart_accounts')->orderBy('id', 'DESC')->get();
        return view('journalLedger/addJournalLedger', Compact('chartOfAccounts'));
    }

    public function submitJournalLedger(Request $request)
    {

//        dd($request->all());

        $ledger = new Ledger();
        $ledger->description = $request->description;
        $ledger->transactionDate = $request->transactionDate;
        $ledger->supportingDocumentDate = $request->supportingDocumentDate;
        $ledger->total_amount = 12873827;

        if ($request->supportingDocument) {
//            dD("!");
            $attachmentFile = time() . '.' . $request->file('supportingDocument')->extension();
            $request->file('supportingDocument')->move(public_path('supportingDocument'), $attachmentFile);
            $ledger->attachment = $attachmentFile;

        }
//dd("2");
        $ledger->save();

        $data = $request->all();
        $accountID = $data['chartOfAccounts'];
        $total = 0;

        foreach ($accountID as $key => $account) {


            $account_code=ChartOfAccount::find($account);
            $ledgerItem = new LedgerItem();
            $ledgerItem->ledger_id = $ledger->id;
            $ledgerItem->account_id = $account;
            $ledgerItem->debit = $request->debit[$key];
            $ledgerItem->credit = $request->credit[$key];

            $ledgerItem->account_code = $account_code->code;

            $ledgerItem->save();
            $total += $ledgerItem->debit;
        }

        Ledger::where('id', $ledger->id)->update(['total_amount' => $total]);


        return redirect('journalLedger');
    }

    public function editJournalLedger($id)
    {
        $chartOfAccounts = DB::table('chart_accounts')->orderBy('id', 'DESC')->get();
        $ledger=Ledger::find($id);
        $ledgerItems=LedgerItem::where("ledger_id",$id)->get();

        return view('journalLedger.edit', Compact('chartOfAccounts',
            'ledgerItems',
            'ledger'));
    }

    public function updateJournalLedger($id,Request $request)
    {
        $ledger =Ledger::find($id);
        $ledger->description = $request->description;
        $ledger->transactionDate = $request->transactionDate;
        $ledger->supportingDocumentDate = $request->supportingDocumentDate;
        $ledger->total_amount = 12873827;
        if ($request->supportingDocument) {
//            dD("!");
            $attachmentFile = time() . '.' . $request->file('supportingDocument')->extension();
            $request->file('supportingDocument')->move(public_path('supportingDocument'), $attachmentFile);
            $ledger->attachment = $attachmentFile;

        }
        $ledger->save();

        $oldItems=LedgerItem::where("ledger_id",$id)->get();
        foreach ($oldItems as $oldItem) {
            LedgerItem::find($oldItem->id)->delete();
        }


        $data = $request->all();
        $accountID = $data['chartOfAccounts'];
        $total = 0;

        foreach ($accountID as $key => $account) {
            $account_code=ChartOfAccount::find($account);

            $ledgerItem = new LedgerItem();
            $ledgerItem->ledger_id = $ledger->id;
            $ledgerItem->account_id = $account;
            $ledgerItem->debit = $request->debit[$key];
            $ledgerItem->credit = $request->credit[$key];

            $ledgerItem->account_code = $account_code->code;

            $ledgerItem->save();
            $total += $ledgerItem->debit;
        }

        Ledger::where('id', $ledger->id)->update(['total_amount' => $total]);


        return redirect('journalLedger');
    }

    public function deleteJournalLedger($id)
    {
        $ledgerItems = LedgerItem::where("ledger_id",$id)->get();
        foreach ($ledgerItems as $ledgerItem) {
//            dd($ledgerItem);
            LedgerItem::find($ledgerItem->id)->delete();

        }
        $ledger = Ledger::find($id)->delete();
        return redirect('journalLedger');
    }


}
