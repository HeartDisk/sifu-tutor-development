<?php

namespace App\Http\Controllers\backend\api\journalledger;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\JournalLedger;
use App\Http\Controllers\Controller;

class JournalLedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $q = !empty(request('query')) ? request('query') : '';
        $ledgers = JournalLedger::latest()
            ->where('description', 'like', '%'.$q . '%')
            
            ->paginate(env('PAR_PAGE'));

        return response()->json(['ledgers' => $ledgers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'description'=>'required',
            'transaction_date'=>'required',
            'supporting_document_date'=>'required',
            'chart_of_accounts'=>'required',
            ]);

        $ledger = JournalLedger::create([
            'uid'=>Str::uuid(),
            'slug'=>Str::slug($request->description,'-'),
            'description'=>$request->description,
            'transaction_date'=>$request->transaction_date,
            'supporting_document_date'=>$request->supporting_document_date,
            'chart_of_accounts'=>$request->chart_of_accounts,
        ]);

        return response()->json(['message' => 'ledger has been created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ledger = JournalLedger::where('uid',$id)->first();

        return response()->json(['ledger' => $ledger]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'description'=>'required',
            'transaction_date'=>'required',
            'supporting_document_date'=>'required',
            'chart_of_accounts'=>'required',
            ]);

        $ledger = JournalLedger::where('id',$id)->update([
            'description'=>$request->description,
            'transaction_date'=>$request->transaction_date,
            'supporting_document_date'=>$request->supporting_document_date,
            'chart_of_accounts'=>$request->chart_of_accounts,
        ]);

        return response()->json(['message' => 'ledger has been updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         //
         $ledger = JournalLedger::where('id',$id)->first();

         if ($ledger) {
             $ledger->delete();
             return response()->json(['message'=>'ledger has been deleted successfully']);
         }
         return response()->json(['message' => 'ledger has been removed successfully']);
    }
}
