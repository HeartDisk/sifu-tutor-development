@extends('layouts.main')

@section('content')

<style>
    .progress .progress-bar{
        height:4px;
    }
    .row-details{
        border-bottom:1px solid grey;
    }
    .form-select {
    font-size: 0.65rem;
        
    }
    .input-group-text {
    font-size: 0.65rem;
        
    }
    .form-control {
    font-size: 0.85rem;
        
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
                                    <h1 class="nk-block-title">EDIT STUDENT INVOICES FOR CONFIRMATION</h1>
                                    <nav>
                                       <ol class="breadcrumb breadcrumb-arrow mb-0">
                                          <li class="breadcrumb-item"><a href="#">Home</a></li>
                                          <li class="breadcrumb-item"><a href="#">Invoice</a></li>
                                          <li class="breadcrumb-item active" aria-current="page">Edit Student Invoice</li>
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
                                 <div class="nk-invoice">
                                    <div class="nk-invoice-head flex-column flex-sm-row">
                                       <div class="nk-invoice-head-item mb-3 mb-sm-0">
                                          <div class="nk-invoice-brand mb-1">
                                             <h1>SifuTutor</h1>
                                          </div>
                                          <ul>
                                             <!--<li>info@company.com</li>-->
                                             <!--<li>(120) 456 789</li>-->
                                          </ul>
                                       </div>
                                       <div class="nk-invoice-head-item text-sm-end">
                                          <div class="h3">Invoices No: #{{$invoice_detail->id}}</div>
                                          <ul>
                                             <li>Issue Date: @php echo date('d-m-Y') @endphp</li>
                                             <!--<li>Due Date: 26 Oct 2022</li>-->
                                          </ul>
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
                        <!--<tr>-->
                        <!--    <td colspan="3"><strong>Customer Remark: </strong>  1) This invoice is computer generated and no signature is required-->
                        <!--    <br>2) Payment is due within 3 working days of issuance of this invoice-->
                        <!--    <br>3) You can pay online via online banking by clicking the button PAY NOW or alternatively can transfer to account no below :    -->
                        <!--    <br>-->
                        <!--    <br>MAYBANK - 562115516678    SIFU EDU &amp; LEARNING SDN BHD   </td>-->
                        <!--</tr>-->
                    </tbody>
                    </table>
                    
                                    </div>
                                    <div class="nk-invoice-body">
                                       <div class="table-responsive">
                                          <table class="table nk-invoice-table">
                                             <thead class="table-dark">
                                                <tr>
                                                   <th class="tb-col"><span class="overline-title">Student Name</span></th>
                                                   <th class="tb-col"><span class="overline-title">Subject</span></th>
                                                   <th class="tb-col"><span class="overline-title">Attended Hours</span></th>
                                                   <th class="tb-col"><span class="overline-title">Price</span></th>
                                                   <th class="tb-col"><span class="overline-title">total</span></th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                                 @php
                                                    $totaAttendedHours = 0;
                                                    $totalAmount = 0;
                                                 @endphp
                                                 @foreach($invoice_items as $rowInvoiceItems)
                                                    <tr>
                                                       <td class="tb-col"><span>
                                                           {{$rowInvoiceItems->full_name}}
                                                        
                                                        </span></td>
                                                       <td class="tb-col"><span>
                                                           {{$rowInvoiceItems->name}}
                                                        </span></td>
                                                       <td class="tb-col"><span>
                                                           @php 
                                                                echo round($rowInvoiceItems->quantity);
                                                                $totaAttendedHours+=round($rowInvoiceItems->quantity);
                                                            @endphp 
                                                           
                                                           </span></td>
                                                       <td class="tb-col"><span>@php echo round($rowInvoiceItems->price); @endphp </span></td>
                                                       <td class="tb-col"><span>
                                                            @php 
                                                                echo round($rowInvoiceItems->quantity) * $rowInvoiceItems->price; 
                                                                $totalAmount+=round($rowInvoiceItems->quantity) * $rowInvoiceItems->price;
                                                            @endphp </span></td>
                                                    </tr>
                                                
                                                 @endforeach
                                                
                                             </tbody>
                                             <tfoot class="table-dark">
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                   <td>{{$totaAttendedHours}}</td>
                                                   <td>Total:</td>
                                                   <td>{{$totalAmount}}</td>
                                                </tr>
                                             </tfoot>
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
          
          </div>

@endsection

