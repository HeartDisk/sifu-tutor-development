@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
  <div class="fluid-container">
     <div class="nk-content-inner">
        <div class="nk-content-body">
           <div class="nk-block-head">
              <div class="nk-block-head-between flex-wrap gap g-2">
                 <div class="nk-block-head-content">
                    <h2 class="nk-block-title">Staff Payments</h2>
                    <nav>
                       <ol class="breadcrumb breadcrumb-arrow mb-0">
                          <li class="breadcrumb-item"><a href="#">Home</a></li>
                          <li class="breadcrumb-item"><a href="#">Staff</a></li>
                          <li class="breadcrumb-item active" aria-current="page">Staff Payments</li>
                       </ol>
                    </nav>
                 </div>
                 <div class="nk-block-head-content">
                    <ul class="d-flex">
                       <li><a href="{{route('StaffMakePayment')}}" class="btn btn-md d-md-none btn-primary"><em class="icon ni ni-plus"></em><span>Add</span></a></li>
                       <li><a href="{{route('StaffMakePayment')}}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Add Payment</span></a></li>
                    </ul>
                 </div>
              </div>
           </div>
           <div class="nk-block">
              <div class="card card-gutter-md">
                 <div class="card-body">
                    @if (\Session::has('success'))
                    <div class="alert alert-success">
                       <ul>
                          <li>{!! \Session::get('success') !!}</li>
                       </ul>
                    </div>
                    @endif
                    @if (\Session::has('update'))
                    <div class="alert alert-primary">
                       <ul>
                          <li>{!! \Session::get('update') !!}</li>
                       </ul>
                    </div>
                    @endif
                    <table class="datatable-init table" data-nk-container="table-responsive">
                       <thead>
                          <tr>
                             <th>#</th>
                             <th>Staff</th>
                             <th>Month - Year</th>
                             <th>Nett Pay</th>
                             <th>Total Amount</th>
                             <th>Payment Date</th>
                             <th>Action</th>
                          </tr>
                       </thead>
                       <tbody>
                          @foreach($staffPayments as $key=>$staffPayment)
                          <tr>
                             <th>{{$key+1}}</th>
                             <td>{{$staffPayment->name}}</td>
                             <td>{{$staffPayment->salary_month."-".$staffPayment->salary_year}}</td>
                             <td>{{$staffPayment->nett_amount}}</td>
                             <td>{{$staffPayment->total}}</td>
                             <td>{{$staffPayment->payment_date}}</td>
                             @can("staff-payment-view-slip")
                             <td><a class="dtable-cbtn bt-view dtb-tooltip" dtb-tooltip="View Slip" href="{{url("/ViewPaymentSlip")."/".$staffPayment->id}}"><i class="fa fa-eye"></i></a></td>
                             @endcan
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