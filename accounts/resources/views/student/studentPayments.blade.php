@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
   <div class="">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head nk-page-head">
               <div class="nk-block-head-between flex-wrap gap g-2">
               <div class="nk-block-head-content">
                  <h2 class="nk-block-title">
                     Students Payments
                  </h2>
                  <nav>
                     <ol class="breadcrumb breadcrumb-arrow mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Customer List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Students Payments</li>
                     </ol>
                  </nav>
               </div>
            </div>
            </div>
            <div class="nk-block">
               <div class="card overflow-hidden">
                  <div class="card-body">
                    <form action="{{route('StudentPaymentLists')}}" method="GET">
                       @csrf
                       <input type="hidden" name="studentPayment" value="1"/>
                       <div class="row justify-content-between tableper-row">
                           <div class="col-md-3">
                              <div class="input-group  input-group-md">
                                 <label class="input-group-text" for="inputGroupSelect01">From</label>
                                 <input type="date" class="form-control" name="fromDate"/>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="input-group  input-group-md">
                                 <label class="input-group-text" for="inputGroupSelect01">To</label>
                                 <input type="date" class="form-control" name="toDate"/>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="input-group input-group-md">
                                 <span class="input-group-text" id="inputGroup-sizing-sm">Search</span>
                                 <input name="search" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" placeholder="Receipt No.">
                              </div>
                           </div>
                           <div class="col-md-2">
                               <div class="input-group input-group-md">
                                  <input type="submit" class="btn btn-primary" aria-label="Sizing example input" value="Search" aria-describedby="inputGroup-sizing-sm">
                               </div>
                           </div>
                       </div>
                    </form>
                    <table class="datatable-init table" data-nk-container="table-responsive table-border">
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
                        <tbody>
                           @foreach($invoices as $rowInvoice)
                           <tr>
                              <td>{{$rowInvoice->id}}</td>
                              <td>{{$rowInvoice->paymentID}}</td>
                              <td>{{$rowInvoice->paymentDate}}</td>
                              <td>{{$rowInvoice->invoiceReference}}</td>
                              <td>RM {{$rowInvoice->amount}}</td>
                              <td>
                                 <a class="dtable-cbtn bt-edit dtb-tooltip" dtb-tooltip="View Invoice" href="{{route('viewStudentPayment',$rowInvoice->id)}}"><i class="fa fa-eye"></i></a>
                                 <!--<i title="Delete" style="border:1px solid #000; border-radius:5px; padding:5px;" class="fa fa-trash"></i>-->
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