@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
   <div class="fluid-container">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head">
               <div class="nk-block-head-between flex-wrap gap g-2">
                  <div class="nk-block-head-content">
                     <h2 class="nk-block-title">Creditor Payments</h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Cashflow</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Creditor Payments</li>
                        </ol>
                     </nav>
                  </div>
                  <div class="nk-block-head-content">
                     <ul class="d-flex">
                        <li><a href="{{route('addCreditorPayment')}}" class="btn btn-md d-md-none btn-primary"><em class="icon ni ni-plus"></em><span>Add</span></a></li>
                        <li><a href="{{route('addCreditorPayment')}}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Add Payment</span></a></li>
                     </ul>
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
                              <th>Payment Date</th>
                              <th>Creditor Name</th>
                              <th>Amount</th>
                              <th>Attachment</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach($creditorInvoices as $rowInvoice)
                           <tr>
                              <td>{{$rowInvoice->id}}</td>
                              <td>{{ \Carbon\Carbon::parse($rowInvoice->paymentDate)->format('D, d M Y') }}</td>
                              <td>{{$rowInvoice->creditorName}}</td>
                              <td>{{$rowInvoice->amount}}</td>
                              <td><a data-lightbox="image" class="dtable-status-viewfile" href="{{url("/public/creditorPayment")."/".$rowInvoice->attachment}}" target="_blank">View</a></td>
                              <td>
                                 <a class="dtable-cbtn bt-view dtb-tooltip" dtb-tooltip="View" href="{{route('ViewCreditorPayment',$rowInvoice->id)}}"> <i class="fa fa-eye"></i></a>
                                 <a class="dtable-cbtn bt-delete dtb-tooltip" dtb-tooltip="Delete" href="{{route('deleteCreditorPayment',$rowInvoice->id)}}"> <i class="fa fa-trash"></i></a>
                              </td>
                           </tr>
                           @endforeach
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