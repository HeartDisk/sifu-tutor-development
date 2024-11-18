<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use Response;

class ExportExcelController extends Controller
{
    function index()
    {
        $customer_data = DB::table('customers')->get();
        return view('export/export_excel', Compact('customer_data'));
    }

    function excel()
    {
     $customer_data = DB::table('customers')->get()->toArray();
     
     $customer_array[] = array('Customer Name', 'Address', 'City', 'Postal Code', 'Country');
     foreach($customer_data as $customer)
     {
      $customer_array[] = array(
       'Customer Name'  => $customer->full_name,
       'Address'   => $customer->email,
       'City'    => $customer->latitude,
       'Postal Code'  => $customer->longitude,
       'Country'   => $customer->email
      );
     }

     (new FastExcel($customer_array))->export('public/excel/'.$customer->full_name.'.xlsx');
     
     return redirect()->back()->with('success','Data  Downloaded Successfully in Excel sheet File');
    }

    function StudentInvoiceReadyForConfirmationList()
    {
     $invoice_data = DB::table('invoices')->get()->toArray();
     
     $invoice_array[] = array('S.No','Reference No', 'Invoice Date', 'Total Price', 'Class Schedule On', 'Report Submitted On');
     $number = 1;
     foreach($invoice_data as $invoice)
     {
      $invoice_array[] = array(
        'S.No'  => $number++,
        'Reference No'  => $invoice->reference,
        'Invoice Date'   => $invoice->invoiceDate,
        'Total Price'    => $invoice->invoiceTotal,
        'Class Schedule On'  => 0,
        'Report Submitted On'   => 0
      );
     }

     (new FastExcel($invoice_array))->export('public/excel/'.$invoice->reference.'.xlsx');
     
     return redirect()->back()->with('success','Data  Downloaded Successfully in Excel sheet File');
    }

    
    function tutorNotUpdateClassSchedule()
    {
     $invoice_data = DB::table('class_schedule')->get()->toArray();
     
     $invoice_array[] = array('S.No','Reference No', 'Invoice Date', 'Total Price', 'Class Schedule On', 'Report Submitted On');
     $number = 1;
     foreach($invoice_data as $invoice)
     {
      $invoice_array[] = array(
        'S.No'  => $number++,
        'Reference No'  => $invoice->reference,
        'Invoice Date'   => $invoice->invoiceDate,
        'Total Price'    => $invoice->invoiceTotal,
        'Class Schedule On'  => 0,
        'Report Submitted On'   => 0
      );
     }

     (new FastExcel($invoice_array))->export('public/excel/'.$invoice->reference.'.xlsx');
     
     return redirect()->back()->with('success','Data  Downloaded Successfully in Excel sheet File');
    }

    
}

?>