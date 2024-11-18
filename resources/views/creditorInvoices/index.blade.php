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
                        Credtior Invoices
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Cashflow</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Credtior Invoices</li>
                        </ol>
                     </nav>
                  </div>
                  <div class="nk-block-head-content">
                     <ul class="d-flex">
                        <li><a href="{{route('addCreditorInvoice')}}" class="btn btn-md d-md-none btn-primary"><em class="icon ni ni-plus"></em>Add</a></li>
                        <li><a href="{{route('addCreditorInvoice')}}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em>Add Creditor Invoice</a></li>
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
                              <th>Creditor Name</th>
                              <th>Description</th>
                              <th>Category</th>
                              <th>Total Amount</th>
                              <th>Occurance Date</th>
                              <th>Attachment</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach($creditorInvoices as $rowInvoice)
                           <tr>
                              <td>{{$rowInvoice->id}}</td>
                              <td>{{$rowInvoice->creditorName}}</td>
                              <td>{{$rowInvoice->description}}</td>
                              <td>{{$rowInvoice->category}}</td>
                              <td>RM {{$rowInvoice->quantity + $rowInvoice->costPrice}}</td>
                              <!--<td> &nbsp; </td>-->
                              <td>{{ \Carbon\Carbon::parse($rowInvoice->OccuranceDate)->format('D, d M Y') }}</td>
                              <td><a class="dtable-status-viewfile" data-lightbox="image" href="{{url("/public/creditorInvoice")."/".$rowInvoice->attachment}}" target="_blank">View</a></td>
                              <td>
                                 <a class="dtable-cbtn bt-view dtb-tooltip" dtb-tooltip="View" href="{{route('viewCreditorInvoice',$rowInvoice->id)}}"> <i class="fa fa-eye"></i></a>
                                 <a class="dtable-cbtn bt-edit dtb-tooltip" dtb-tooltip="Edit" href="{{route('editCreditorInvoice',$rowInvoice->id)}}"> <i class="fa fa-edit"></i></a>
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