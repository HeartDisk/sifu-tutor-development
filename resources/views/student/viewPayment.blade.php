@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
   <div class="fluid-container">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head">
               <div class="nk-block-head-between flex-wrap gap g-2">
                  <div class="nk-block-head-content">
                     <h2 class="nk-block-title">
                        Sales Invoice Payments
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Cash Flow</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Sales Invoice Payments</li>
                        </ol>
                     </nav>
                  </div>
               </div>
            </div>
            <div class="nk-block">
               <div class="card">
                  <div class="card-body">
                     <table class="datatable-init table" data-nk-container="table-responsive">
                        <thead>
                           <tr>
                              <th>#</th>
                              <th>Receipt No.</th>
                              <th>Payment Date</th>
                              <th>Invoice Reference No.</th>
                              <th>Amount</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody id="scheduleAjaxCallBody">
                           @foreach($invoices as $key=>$invoice)
                           <tr>
                              <td>{{$key++}}</td>
                              <td>{{$invoice->paymentID}}</td>
                              <td>{{$invoice->paymentDate}}</td>
                              <td>{{$invoice->referenceNumber}}</td>
                              <td>{{$invoice->amount}}</td>
                              <td><i class="fa fa-eye"></i> {{$invoice->id}}</td>
                           </tr>
                           @endforeach
                        </tbody>
                        <tbody id="two">
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection