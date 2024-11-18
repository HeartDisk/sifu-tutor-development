<?php

namespace App\Http\Controllers\backend\api\creditorpayment;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CreditorPayment;
use App\Http\Controllers\Controller;

class CreditorPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $q = !empty(request('query')) ? request('query') : '';
        $creditor_payments = CreditorPayment::latest()
            ->where('creditor_name', 'like', '%'.$q . '%')
            ->paginate(env('PAR_PAGE'));

        return response()->json(['creditor_payments' => $creditor_payments]);
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
            'creditor_name'=>'required',
            'payment_amount'=>'required',
            'payment_date'=>'required',
            'paying_account'=>'required',
            ]);

            $attachment = null;
            if($request->hasFile('attachment')){
                $name = singleImgUpload($request,"img/creditor_payment");
                $attachment = $name;
            }
            
            $creditor_payments = CreditorPayment::create([
                'uid'=>Str::uuid(),
                'slug'=>Str::slug($request->creditor_name,'-'),
                'creditor_name'=>$request->creditor_name,
                'payment_amount'=>$request->payment_amount,
                'payment_date'=>$request->payment_date,
                'paying_account'=>$request->paying_account,
                'attachment'=>$attachment,
            ]);
    
            return response()->json(['message' => 'Creditor Payment has been created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $creditor_payment = CreditorPayment::where('uid',$id)->first();

        return response()->json(['creditor_payment' => $creditor_payment]);
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
            'creditor_name'=>'required',
            'payment_amount'=>'required',
            'payment_date'=>'required',
            'paying_account'=>'required',
            ]);
        if($request->hasFile('attachment')){
            $name = singleImgUpload($request,"img/creditor_payment");
            CreditorPayment::where('id',$id)->update([
                'attachment'=>$name,
            ]);
        }
        
        $creditor_payment = CreditorPayment::where('id',$id)->update([
                'creditor_name'=>$request->creditor_name,
                'payment_amount'=>$request->payment_amount,
                'payment_date'=>$request->payment_date,
                'paying_account'=>$request->paying_account,
        ]);

        return response()->json(['message' => 'Creditor Payment has been updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $creditor_payment = CreditorPayment::where('id',$id)->first();

        if ($creditor_payment) {
            $creditor_payment->delete();
            return response()->json(['message'=>'Creditor Payment has been deleted successfully']);
        }
        return response()->json(['message' => 'Creditor Payment has been removed successfully']);
    }
}
