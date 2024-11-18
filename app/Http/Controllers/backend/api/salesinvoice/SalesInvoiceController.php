<?php

namespace App\Http\Controllers\backend\api\salesinvoice;

use Illuminate\Support\Str;
use App\Models\SalesInvoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SalesInvoiceItem;

class SalesInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $q = !empty(request('query')) ? request('query') : '';
        $invoices = SalesInvoice::latest()
            ->where('payer_name', 'like', '%'.$q . '%')
            ->paginate(env('PAR_PAGE'));

        return response()->json(['invoices' => $invoices]);
    }
    public function salesInvoicePayments()
    {
        $q = !empty(request('query')) ? request('query') : '';
        $invoices = SalesInvoice::latest()
            ->where('payer_name', 'like', '%'.$q . '%')
            ->paginate(env('PAR_PAGE'));

        return response()->json(['invoices' => $invoices]);
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
            'refernce_no'=>'required',
            'invoice_date'=>'required',
            'payer_name'=>'required',
            'payer_email'=>'required|email',
            'payer_phone'=>'required',
            'remarks'=>'required',
            'item_description'=>'required',
            'item_quantity'=>'required',
            'item_price'=>'required',
            ]);

        $invoice = SalesInvoice::create([
            'uid'=>Str::uuid(),
            'slug'=>Str::slug($request->payer_name,'-'),
            'start_date'=>$request->invoice_date,
            'payer_name'=>$request->payer_name,
            'payer_email'=>$request->payer_email,
            'payer_phone'=>$request->payer_phone,
            'invoice_items'=>$request->invoice_items,
            'invoice_deductions'=>$request->invoice_deductions,
            'remarks'=>$request->required,
        ]);

        return response()->json(['message' => 'invoice has been created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = SalesInvoice::where('uid',$id)->first();

        return response()->json(['invoice' => $invoice]);
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
            'refernce_no'=>'required',
            'invoice_date'=>'required',
            'payer_name'=>'required',
            'payer_email'=>'required|email',
            'payer_phone'=>'required',
            'remarks'=>'required',
            'item_description'=>'required',
            'item_quantity'=>'required',
            'item_price'=>'required',
            ]);

        $invoice = SalesInvoice::where('id',$id)->update([
            'start_date'=>$request->invoice_date,
            'payer_name'=>$request->payer_name,
            'payer_email'=>$request->payer_email,
            'payer_phone'=>$request->payer_phone,
            'invoice_items'=>$request->invoice_items,
            'invoice_deductions'=>$request->invoice_deductions,
            'remarks'=>$request->required,
        ]);

        return response()->json(['message' => 'invoice has been updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoice = SalesInvoice::where('id',$id)->first();

        if ($invoice) {
            $invoice->delete();
            return response()->json(['message'=>'invoice has been deleted successfully']);
        }
        return response()->json(['message' => 'invoice has been removed successfully']);
    }
}
