<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Event;
use DB;
class BillingController extends Controller
{
    public function invoices(){

        
        return view('billing.invoices');
    }
}