<?php

namespace App\Http\Controllers\backend\api\student;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\StudentInvoice;
use App\Http\Controllers\Controller;

class StudentInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $q=!empty(request('query')) ? request('query') : '';
        $invoices = StudentInvoice::latest()->where('reference_no', 'LIKE', '%' . $q . '%')
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
        //
        $request->validate([
            'invoice_date' =>'required',
            'management_status'=>'required',
            'brand'=>'required',
            'payer_name'=>'required',
            'payer_email'=>'required|email',
            'payer_phone'=>'required',
            'remarks'=>'required',
            'invoice_items'=>'required|array',
            'deductions_items'=>'required|array',
        ]);

        $last_id=StudentInvoice::orderBy('id','desc')->first();
        $last_id=!empty($last_id)  ? $last_id->id+1 : 1;

        $invoice = StudentInvoice::create([
            'slug'=>'ST0'. $last_id,
            'reference_no'=>'ST0'. $last_id,
            'uid'=>Str::uuid(),
            'invoice_date'=>$request->invoice_date,
            'management_status'=>$request->management_status,
            'brand'=>$request->brand,
            'payer'=>$request->payer_name,
            'payer_name'=>$request->payer_name['slug'],
            'payer_email'=>$request->payer_email,
            'payer_phone'=>$request->payer_phone,
            'remarks'=>$request->remarks,
            'invoice_items'=>$request->invoice_items,
            'deductions_items'=>$request->deductions_items,
        ]);

        return response()->json(['message' => 'student invoice created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $invoice = StudentInvoice::where('uid', $id)->first();
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
        $invoice = StudentInvoice::where('uid', $id)->first();
        return response()->json(['invoice' => $invoice]);
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
        //
        $request->validate([
            'invoice_date' =>'required',
            'management_status'=>'required',
            'brand'=>'required',
            'payer_name'=>'required',
            'payer_email'=>'required|email',
            'payer_phone'=>'required',
            'remarks'=>'required',
            'invoice_items'=>'required|array',
            'deductions_items'=>'required|array',
        ]);
        $invoice = StudentInvoice::where('id',$id)->update([
            'invoice_date'=>$request->invoice_date,
            'management_status'=>$request->management_status,
            'brand'=>$request->brand,
            'payer'=>$request->payer_name,
            'payer_name'=>$request->payer_name['slug'],
            'payer_email'=>$request->payer_email,
            'payer_phone'=>$request->payer_phone,
            'total_amount'=>collect($request->invoice_items)->sum('unit_price'),
            'remarks'=>$request->remarks,
            'invoice_items'=>$request->invoice_items,
            'deductions_items'=>$request->deductions_items,
        ]);

        return response()->json(['message' => 'student invoice has been updated successfully']);
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

        $invoice = StudentInvoice::where('id', $id)->delete();

        return response()->json(['message' => 'student invoice has been deleted successfully']);
    }
}
