@extends('layouts.public')

@section('content')

<style>
    .progress .progress-bar{
        height:4px;
    }
    .row-details{
        border-bottom:1px solid grey;
    }
    .zeroPadding {
        padding:5px !important;
    }
    
</style>

<div style="padding-top:10px;" class="fluid-container">
    
        <div class="nk-content">
                  <div class="fluid-container">
                     <div class="nk-content-inner">
                        <div class="nk-content-body">
                           <div class="nk-block-head">
                              <div class="nk-block-head-between flex-wrap gap g-2">
                                 <div class="nk-block-head-content">
                                    
                                 </div>
                                 <div class="nk-block-head-content">
                                    <ul class="d-flex">
                                       
                                    </ul>
                                 </div>
                              </div>
                           </div>
                           <div class="nk-block">
                              <div class="card">
                                 <div style="padding:0px !important;" class="nk-invoice">
                                    <div class="nk-invoice-head flex-column flex-sm-row">
                                       <div class="nk-invoice-head-item mb-3 mb-sm-0">
                                          <div class="nk-invoice-brand mb-1">
                                              <img style="width:250px;" src="{{url("/template/login.png")}}"/>

                                          </div>
                                          <ul>
                                             
                                          </ul>
                                       </div>
                                       <div class="nk-invoice-head-item text-sm-end">
                                           <h1>Student Invoice</h1>
                                          <div class="text"><strong>Invoices No: # </strong>{{$invoice_detail->id}}</div>
                                          <ul>
                                             <li class="text"><strong> Issue Date: </strong>@php echo date('d-m-Y') @endphp</li>
                                             <!--<li>Due Date: 26 Oct 2022</li>-->
                                          </ul>
                                          
                                       </div>
                                    </div>
                                    
                                    <div class="nk-invoice-body">
                                       <div class="table-responsive">
                                          <table class="table nk-invoice-table">
                                             <thead class="table-dark">
                                                <tr>
                                                   <th class="zeroPadding tb-col"><span class="">Class Description</span></th>
                                                   <th class="zeroPadding tb-col"><span class="">Hrs</span></th>
                                                   <th class="zeroPadding tb-col"><span class="">Price / Hr</span></th>
                                                   <th class="zeroPadding tb-col"><span class="">Total</span></th>
                                                </tr>
                                             </thead>
                                              <tbody>
                                                 @php
                                                    $totaAttendedHours = 0;
                                                    $totalAmount = 0;
                                                    $startMonth = null;
                                                 @endphp
                                                 @foreach($invoice_items as $rowInvoiceItems)
                                                    @php
                                                        $invoiceMonth = \Carbon\Carbon::parse($rowInvoiceItems->invoiceDate)->format('m');
                                                    @endphp
                                        
                                                    @if ($startMonth === null || $invoiceMonth < $startMonth)
                                                        @php $startMonth = $invoiceMonth; @endphp
                                                    @endif
                                                     <tr>
                                                        <td class="zeroPadding tb-col"><span>
                                                            {{ \Carbon\Carbon::parse($rowInvoiceItems->invoiceDate)->format('F') }} - {{$students->full_name}} - {{$subjects->name}}
                                                        </span></td>
                                                        <td class="zeroPadding tb-col"><span>
                                                            @php 
                                                                echo $rowInvoiceItems->quantity;
                                                                $totaAttendedHours += $rowInvoiceItems->quantity;
                                                            @endphp 
                                                        </span></td>
                                                        <td class="zeroPadding tb-col"><span>@php echo $subjects->price; @endphp </span></td>
                                                        <td class="zeroPadding tb-col"><span>
                                                            @php 
                                                                echo $rowInvoiceItems->quantity * $subjects->price; 
                                                                $totalAmount += $rowInvoiceItems->quantity * $subjects->price;
                                                            @endphp </span></td>
                                                    </tr>
                                                 @endforeach
                                                
                                             </tbody>
                                             <tfoot class="table-dark">
                                                <tr>
                                                    <td>&nbsp;</td>
                                                   <td>{{$totaAttendedHours}}</td>
                                                   <td></td>
                                                   <td>Total: <strong>{{$totalAmount}}</strong></td>
                                                </tr>
                                             </tfoot>
                                          </table>
                                       </div>
                                    </div>
                                    
                                    <div class="container">
                                        <div class="row">
                                                <div class="col-md-4">
                                                    <strong>Payer Name: </strong> <br/>{{$invoice_detail->payerName}}    
                                                </div>
                                                <div class="col-md-4">
                                                    <strong>Payer Email: </strong><br/> {{$invoice_detail->payerEmail}}
                                                </div>
                                                <div class="col-md-4">
                                                    <strong>Payer Phone Number: </strong><br/> {{$invoice_detail->payerPhone}}
                                                </div>
                                                <div class="col-md-12">
                                                    <strong>Customer Remark: </strong>  <br/>
                                                        1) This invoice is computer-generated and no signature is required.<br/>
                                                        2) Payment is due within 3 working days of issuance of this invoice.<br/>
                                                        3) You can pay online via online banking by clicking the button PAY NOW or alternatively can transfer to account no below :<br/>
                                                        <strong>MAYBANK - 562115516678 SIFU EDU & LEARNING SDN BHD</strong>
                                                </div>
                                        </div>
                                    </div>

                                    <div class="row container">
                                        
                                        <form method="post" action="https://payment.ipay88.com.my/ePayment/entry.asp" id="makePaymentForm" name="makePaymentForm" novalidate="novalidate">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-8">
                                                    <input type="hidden" name="MerchantCode" value="M28937">
                                                    <input type="hidden" name="RefNo" value="ST120451">
                                                    <input type="hidden" name="Amount" id="Amount" value="300.00">
                                                    <input type="hidden" name="Currency" value="MYR">
                                                    <input type="hidden" name="ProdDesc" value="January 2024 - ST120451">
                                                    <input type="hidden" name="UserName" value="Mohd Izwan Bin Ibrahim">
                                                    <input type="hidden" name="UserEmail" value="idayu.iu@gmail.com">
                                                    <input type="hidden" name="UserContact" value="60172445598">
                                                    <input type="hidden" name="Remark">
                                                    <input type="hidden" name="Lang" value="UTF-8">
                                                    <input type="hidden" name="SignatureType" value="SHA256">
                                                    <input type="hidden" name="Signature" id="Signature" value="2adb959fca2cfbc6eaf383494fdc3f0bc7724b6b9ea8c28b556dd9ebac6541c3">
                                                    <input type="hidden" name="ResponseURL" value="https://portal.sifututor.my/Public/Invoices/RedirectPaymentStatus?invoiveReferenceNo=ST120451&amp;token=hy4q0S21Td5">
                                                    <input type="hidden" name="BackendURL" value="https://portal.sifututor.my/Public/Invoices/ConfirmPaymentStatus?invoiveReferenceNo=ST120451&amp;token=hy4q0S21Td5">
                                                    <label class="" for="PaymentId">Choose Payment Method:</label>
                                                    <select class="form-control valid" style="max-width:500px;" data-val="true" data-val-length-max="150" data-val-required="Please select payment method" id="PaymentId" name="PaymentId" aria-required="true" aria-invalid="false" aria-describedby="PaymentId-error">
                                                        <option value=""></option>
                                                            <option value="2">Credit Card (MYR)</option>
                                                            <option value="6">Maybank2U</option>
                                                            <option value="8">Alliance Online</option>
                                                            <option value="10">AmOnline</option>
                                                            <option value="14">RHB Online</option>
                                                            <option value="15">Hong Leong Online</option>
                                                            <option value="20">CIMB Click</option>
                                                            <option value="31">Public Bank Online</option>
                                                            <option value="102">Bank Rakyat Internet Banking</option>
                                                            <option value="103">Affin Online</option>
                                                            <option value="124">BSN Online</option>
                                                            <option value="134">Bank Islam</option>
                                                            <option value="152">UOB</option>
                                                            <option value="166">Bank Muamalat</option>
                                                            <option value="167">OCBC</option>
                                                            <option value="168">Standard Chartered Bank</option>
                                                            <option value="198">HSBC Online Banking</option>
                                                            <option value="199">Kuwait Finance House</option>
                                                            <option value="210">Boost Wallet</option>
                                                    </select>
                                                    <span class="text-danger field-validation-valid" data-valmsg-for="PaymentId" data-valmsg-replace="true"></span>
                                                    <button class="btn btn-primary btn-paynow waves-effect waves-light" id="Paynow" type="submit">Pay Now</button>
                                                    <br>
                                                        <br>
                                                        <p class="font-bold" style="font-size:16px">
                                                            Pay in advance 3 months of home or online tuition and enjoy 10% discount
                                                        </p>
                                                </div>
                                            </div>
                                        </form>
                                        
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

