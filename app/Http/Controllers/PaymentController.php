<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use DB;

class PaymentController extends Controller
{
    public function success(Request $req)
    { 
       
       
        $data = DB::table("invoices")->where("id",$req->orderid)->first();
        
        if($data!=null)
        {
            $updateInvoice = array(
                'paymentDate' => date("Y-m-d"),
                'status' => 'paid'
            );
            DB::table('invoices')->where('id', $req->orderid)->update($updateInvoice);
        }
        $ticket = DB::table("job_tickets")->where("id",$data->ticketID)->first();
        if($ticket!=null)
        {
            DB::table("job_tickets")->where("id",$ticket->id)->update(["remaining_classes"=>$ticket->remaining_classes+$ticket->classFrequency]);
            $class_schedule = DB::table("class_schedules")->where("ticketID",$ticket->id)->first();
        }
        
        if($class_schedule!=null)
        {
            DB::table("class_schedules")->where("id",$class_schedule->id)->update(["remaining_classes"=>$class_schedule->remaining_classes+$ticket->classFrequency]);
        }
        
        
        return view("online_payment.success");
       
    }
    
    public function cancel(Request $req)
    {
        return view("online_payment.fail");
        // dd($req->all());
    }
}
