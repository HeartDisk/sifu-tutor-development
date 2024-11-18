@extends('layouts.main')

@section('content')

<style>
    .progress .progress-bar{
        height:4px;
    }
    .row-details{
        border-bottom:1px solid grey;
    }
    
</style>

<div style="padding-top:100px;" class="container">
    
        <div class="nk-content">
                  <div class="fluid-container">
                     <div class="nk-content-inner">
                        <div class="nk-content-body">
                           <div class="nk-block-head">
                              <div class="nk-block-head-between flex-wrap gap g-2">
                                 <div class="nk-block-head-content">
                                    <h2 class="nk-block-title">
                                    Student Invoice </h1>
                                    <nav>
                                       <ol class="breadcrumb breadcrumb-arrow mb-0">
                                          <li class="breadcrumb-item"><a href="#">Home</a></li>
                                          <li class="breadcrumb-item"><a href="#">Invoice</a></li>
                                          <li class="breadcrumb-item active" aria-current="page">Student Invoice</li>
                                       </ol>
                                    </nav>
                                 </div>
                                 <div class="nk-block-head-content">
                                    <ul class="d-flex">
                                       
                                    </ul>
                                 </div>
                              </div>
                           </div>
                           <div class="nk-block">
                              <div class="card">
                                   @if(session("success"))
                                      <div class="alert alert-success" role="alert">
                                          {{session("success")}}
                                      </div>
                                  @endif
                                 <div class="nk-invoice">
                                    <div class="nk-invoice-head flex-column flex-sm-row">
                                       <div class="nk-invoice-head-item mb-3 mb-sm-0">
                                          <div class="nk-invoice-brand mb-1">
                                             <img style="width:250px;" src="{{url('template/login.png')}}"/>
                                          </div>
                                          <ul>
                                             <!--<li>info@company.com</li>-->
                                             <!--<li>(120) 456 789</li>-->
                                          </ul>
                                       </div>
                                       <div class="nk-invoice-head-item text-sm-end">
                                           @if($invoice_detail->status == 'paid')
                                              <p class='dtable-status-active'>Paid</p>
                                          @else
                                            <p class='dtable-status-nostat'>Unpaid</p>
                                          @endif
                                           <h1>Student Invoice</h1>
                                          <div class="text"><strong>Invoices No: # </strong>{{$invoice_detail->id}}</div>
                                          <ul>
                                             <li class="text"><strong> Issue Date: </strong>@php echo date('d-m-Y') @endphp</li>
                                             <!--<li>Due Date: 26 Oct 2022</li>-->
                                          </ul>
                                          <div>
                                              <strong> Public Link </strong> <a target="_blank" href="{{url('invoicePublicLink',$invoice_detail->id)}}"><i style="font-size:31px" class="fa fa-link" aria-hidden="true"></i></a>
                                             <strong> Download PDF </strong> <a target="_blank" href="{{url('pdfFile',$invoice_detail->id)}}"><i style="font-size:31px" class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
                                             <strong> Send Email </strong> <a target="_blank" href="{{url('sendEmailInvoice',$invoice_detail->id)}}"><i style="font-size:31px" class="fa fa-envelope" aria-hidden="true"></i></a>
                                          </div>
                                       </div>
                                    </div>
                                    
                                    <div class="nk-invoice-body">
                                       <div class="table-responsive">
                                          <table class="table nk-invoice-table">
                                             <thead class="table-dark">
                                                <tr>
                                                   <th class="tb-col"><span>Class Description</span></th>
                                                   <th class="tb-col"><span>Hours</span></th>
                                                   <th class="tb-col"><span>Sub Price / Hour</span></th>
                                                   <th class="tb-col"><span>Total</span></th>
                                                </tr>
                                             </thead>
                                              <tbody>
                                                 @php
                                                    $totaAttendedHours = 0;
                                                    $totalAmount = 0;
                                                    $startMonth = null;
                                                 @endphp
                                                 {{--@foreach($invoice_items as $rowInvoiceItems)
                                                    
                                                   
                                                   @php
                                                        $invoiceMonth = \Carbon\Carbon::parse($rowInvoiceItems->invoiceDate)->format('m');
                                                    @endphp
                                        
                                                    @if ($startMonth === null || $invoiceMonth < $startMonth)
                                                        @php $startMonth = $invoiceMonth; @endphp
                                                    @endif
                                                     @if ($invoiceMonth == $startMonth)
                                                     
                                                     --}}
                                                     <tr>
                                                        <td class="tb-col"><span>
                                                             {{$subjects->name}}
                                                        </span></td>
                                                        <td class="tb-col"><span>
                                                            @php 
                                                                echo $rowInvoiceItems->quantity;
                                                                $totaAttendedHours += $rowInvoiceItems->quantity;
                                                            @endphp 
                                                        </span></td>
                                                        <td class="tb-col"><span>@php echo $subjects->category_price; @endphp </span></td>
                                                        <td class="tb-col"><span>
                                                            @php 
                                                            
                                                                echo $invoice_detail->invoiceTotal;
                                                              
                                                                $totalAmount += $rowInvoiceItems->quantity * $subjects->category_price;
                                                            @endphp </span></td>
                                                    </tr>
                                                    {{--
                                                    @endif 
                                                 @endforeach
                                                 --}}
                                                 
                                                @php
                                                    // Get all students associated with the customer
                                                    $studentIds = DB::table('students')
                                                        ->where('customer_id', $customer->id)
                                                        ->pluck('id'); // Retrieve only student IDs
                                                    
                                                    // Check if any invoice for those students has been paid
                                                    $paidInvoice = DB::table("invoices")
                                                        ->whereIn("studentID", $studentIds)
                                                        ->where("status", "paid")
                                                        ->exists(); // Check if any of those invoices have status 'paid'
                                                
                                                    // Check customer commitment fee if no paid invoice exists
                                                    if (!$paidInvoice) {
                                                        $checkCustomerInvoice = DB::table("customer_commitment_fees")
                                                            ->where("customer_id", $customer->id)
                                                            ->first();
                                                    } else {
                                                        $checkCustomerInvoice = null; // Set commitment fee to null if a paid invoice exists
                                                    }
                                                @endphp
                                                
                                                
                                             
                                                @if($checkCustomerInvoice && $checkCustomerInvoice->payment_amount)
                                                    <tr>
                                                        <td colspan="3">Customer Commitment Fee</td>
                                                        <td>{{ $checkCustomerInvoice->payment_amount }}</td>
                                                    </tr>
                                                @endif
                                             </tbody>
                                             <tfoot class="table-dark">
                                                <tr>
                                                    
                                                    <td>Total</td>
                                                   <td>{{$invoice_detail->classFrequency}}</td>
                                                   <td>{{$invoice_detail->classFrequency*$subjects->category_price}}</td>
                                                   <td>Total:
                                                        @if($checkCustomerInvoice && $jobTicketDeails->first_invoice_sent == true)
                                                            <strong>Total : RM {{$invoice_detail->invoiceTotal - ($checkCustomerInvoice->payment_amount ?? 0)}}</strong>
                                                        @else
                                                            <strong>Total : RM {{$invoice_detail->invoiceTotal}}</strong>
                                                        @endif
                                                   </td>
                                                </tr>
                                             </tfoot>
                                          </table>
                                       </div>
                                    </div>
                                    
                                    <div class="nk-invoice-head flex-column flex-sm-row">
                                      
                                    <table class="table table-responsive no-border">
                                        <tbody>
                                            <tr>
                                            <td><strong>Payer Name: </strong> {{$invoice_detail->payerName}}</td>
                                            <td><strong>Payer Email: </strong> {{$invoice_detail->payerEmail}}</td>
                                            <td><strong>Payer Phone Number: </strong> {{$invoice_detail->payerPhone}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"><strong>Management Remark: </strong>  {{$invoice_detail->remarks}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"><strong>Customer Remark: </strong>  <br/>
                                                1) This invoice is computer-generated and no signature is required.<br/>
                                                2) Payment is due within 3 working days of issuance of this invoice.<br/>
                                                3) You can pay online via online banking by clicking the button PAY NOW or alternatively can transfer to account no below :<br/>
                                                <br/>
                                                <strong>MAYBANK - 562115516678 SIFU EDU & LEARNING SDN BHD</strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                    
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
          
          </div>

@endsection

