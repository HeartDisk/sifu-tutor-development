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
                        Sale Invoices
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Cash Flow</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Sale Invoices</li>
                        </ol>
                     </nav>
                  </div>
                  <div class="nk-block-head-content">
                     <ul class="d-flex">
                       <li><a href="{{route('addSaleInvoice')}}" class="btn btn-md d-md-none btn-primary"><em class="icon ni ni-plus"></em><span>Add</span></a></li>
                       <li><a href="{{route('addSaleInvoice')}}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Add Sale Invoice</span></a></li>
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
                              <th>Reference No.</th>
                              <th>Student Detail</th>
                              <th>Payer Name</th>
                              <th>Invoice Date</th>
                              <th>Total</th>
                              <th>Status</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach($invoices as $rowInvoice)
                           <tr>
                                <td>{{$rowInvoice->id}}</td>
                                <td><i class="fa fa-user"></i> {{$rowInvoice->referenceNumber}}</td>
                                <td>Student Name</td>
                                <td>{{$rowInvoice->payerName}}</td>
                                <td>{{$rowInvoice->invoiceDate}}</td>
                                <td>{{$rowInvoice->invoiceTotal}}</td>
                                <td>{{$rowInvoice->status}}</td>
                                <td>
                                    <a class="dtable-cbtn bt-view dtb-tooltip" dtb-tooltip="View" href="{{route('viewSaleInvoice',$rowInvoice->id)}}"> <i class="fa fa-eye"></i></a>
                                    <a class="dtable-cbtn bt-edit dtb-tooltip" dtb-tooltip="Edit" href="{{route('editSaleInvoice',$rowInvoice->id)}}"> <i class="fa fa-edit"></i></a>
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