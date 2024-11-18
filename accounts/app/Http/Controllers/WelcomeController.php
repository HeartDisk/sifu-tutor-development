<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Event;
use Auth;
use DB;
class WelcomeController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }
    
    public function index(){
        
        if(Auth::id()){
            return redirect('home');
        }else{
            return view('welcome');    
        }
        
        
    }
    
    public function welcomeMessage(){
        return view('welcomeMessage');
    }
    
      public function disputeMessage(){
        return view('disputeMessage');
    }
    
    
    public function invoicePublicLink($id){
        
        
         $invoice_detail = DB::table('invoices')->where('id', '=', $id)->orderBy('id', 'desc')->first();
    $invoice_items = DB::table('invoice_items')->where('invoiceID', '=', $id)->orderBy('id', 'desc')->get();
    $jobTicketDeails = DB::table('job_tickets')->where('id', '=', $invoice_detail->ticketID)->first();
    $students = DB::table('students')->where('id', '=', $invoice_detail->studentID)->orderBy('id', 'DESC')->first();
    $customer = DB::table('customers')->where('id', '=', $students->customer_id)->first();
    $subjects = DB::table('products')->join("categories", "products.category", "=", "categories.id")->select("products.*", "categories.price as category_price")->where('products.id', '=', $invoice_detail->subjectID)->orderBy('id', 'DESC')->first();

   
        
        // $invoice_detail = DB::table('invoices')->where('id','=',$id)->orderBy('id','desc')->first();
        // $invoice_items = DB::table('invoice_items')->where('invoiceID','=',$id)->orderBy('id','ASC')->get();
        // $students = DB::table('students')->where('id','=',$invoice_detail->studentID)->orderBy('id','DESC')->first();
        // $subjects = DB::table('products')->where('id','=',$invoice_detail->subjectID)->orderBy('id','DESC')->first();
        
        // $invoice_detail = DB::table('invoices')->where('id', '=', $id)->orderBy('id', 'desc')->first();
        // $invoice_items = DB::table('invoice_items')->where('invoiceID', '=', $id)->orderBy('id', 'ASC')->get();
        // $rowInvoiceItems = $invoice_items[0];
    
    
    
    
        // $students = DB::table('students')->where('id', '=', $invoice_detail->studentID)->orderBy('id', 'DESC')->first();
        // $subjects = DB::table('products')->where('id','=',$invoice_detail->subjectID)->orderBy('id','DESC')->first();
    
        // $subjects = DB::table('products')->join("categories", "products.category", "=", "categories.id")
        //   ->select("products.*", "categories.price as category_price")
        //   ->where('products.id', '=', $invoice_detail->subjectID)->first();
        
        
        return view('student.invoicePublicLink',[
      
      'invoice_items' => $invoice_items,
      'invoice_detail' => $invoice_detail,
      'students' => $students,
      'subjects' => $subjects,
      'customer' => $customer,
      'jobTicketDeails' => $jobTicketDeails,
    ]);
    }
    
    public function invoicePublicLink3Months($id){
        $invoice_detail = DB::table('invoices')->where('id','=',$id)->orderBy('id','desc')->first();
        $invoice_items = DB::table('invoice_items')->where('invoiceID','=',$id)->orderBy('id','ASC')->get();
        $students = DB::table('students')->where('id','=',$invoice_detail->studentID)->orderBy('id','DESC')->first();
        $subjects = DB::table('products')->where('id','=',$invoice_detail->subjectID)->orderBy('id','DESC')->first();
        return view('student.invoicePublicLink3Months',Compact('invoice_items','students','invoice_detail','subjects'));
    }
    
    
}