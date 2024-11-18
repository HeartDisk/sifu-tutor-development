<?php

namespace App\Http\Controllers\backend\api\creditorinvoice;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CreditorInvoice;
use App\Http\Controllers\Controller;

class CreditorInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $q = !empty(request('query')) ? request('query') : '';
        $creditor_invoices = CreditorInvoice::latest()
            ->where('description', 'like', '%'.$q . '%')
            ->paginate(env('PAR_PAGE'));

        return response()->json(['creditor_invoices' => $creditor_invoices]);
    
    }
    public function creditorInvoicePayments()
    {
        $q = !empty(request('query')) ? request('query') : '';
        $creditor_invoices = CreditorInvoice::latest()
            ->where('description', 'like', '%'.$q . '%')
            ->paginate(env('PAR_PAGE'));

        return response()->json(['creditor_invoices' => $creditor_invoices]);
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
            'occurance_date'=>'required',
            'creditor_name'=>'required',
            'description'=>'required',
            'category'=>'required',
            'quantity'=>'required',
            'cost_price'=>'required',
            'payment_due_date'=>'required',
            'remarks'=>'required',
            ]);

        $attachment = null;
        if($request->hasFile('attachment')){
            $name = singleImgUpload($request,"img/creditor_invoice");
            $attachment = $name;
        }
        
        $creditor_invoice = CreditorInvoice::create([
            'uid'=>Str::uuid(),
            'slug'=>Str::slug($request->creditor_name,'-'),
            'occurance_date'=>$request->occurance_date,
            'creditor_name'=>$request->creditor_name,
            'description'=>$request->description,
            'category'=>$request->category,
            'quantity'=>$request->quantity,
            'cost_price'=>$request->cost_price,
            'payment_due_date'=>$request->payment_due_date,
            'attachment'=>$attachment,
            'remarks'=>$request->remarks,
        ]);

        return response()->json(['message' => 'Creditor Invoice has been created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $creditor_invoice = CreditorInvoice::where('uid',$id)->first();

        return response()->json(['creditor_invoice' => $creditor_invoice]);
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
            'occurance_date'=>'required',
            'creditor_name'=>'required',
            'description'=>'required',
            'category'=>'required',
            'quantity'=>'required',
            'cost_price'=>'required',
            'payment_due_date'=>'required',
            'remarks'=>'required',
            ]);
        if($request->hasFile('attachment')){
            $name = singleImgUpload($request,"img/creditor_invoice");
            CreditorInvoice::where('id',$id)->update([
                'attachment'=>$name,
            ]);
        }
        
        $creditor_invoice = CreditorInvoice::where('id',$id)->update([
            'occurance_date'=>$request->occurance_date,
            'category'=>$request->category,
            'description'=>$request->description,
            'chart_of_account'=>$request->chart_of_account,
            'quantity'=>$request->quantity,
            'cost_per_unit'=>$request->cost_per_unit,
            'paying_account'=>$request->paying_account,
            'payment_date'=>$request->payment_date,
            'remarks'=>$request->remarks,
        ]);

        return response()->json(['message' => 'Creditor Invoice has been updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $creditor_invoice = CreditorInvoice::where('id',$id)->first();

        if ($creditor_invoice) {
            $creditor_invoice->delete();
            return response()->json(['message'=>'Creditor Invoice has been deleted successfully']);
        }
        return response()->json(['message' => 'Creditor Invoice has been removed successfully']);
    }
}
