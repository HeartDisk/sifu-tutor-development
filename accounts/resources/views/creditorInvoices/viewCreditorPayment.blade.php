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
                        View Creditor Payment
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Creditor Payment</a></li>
                           <li class="breadcrumb-item active" aria-current="page">View Creditor Payment</li>
                        </ol>
                     </nav>
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
                     <div class="bio-block">
                        <div class="row g-1 view-sindetails">
                           <div class="col-md-3 details-item">
                              <p class="item-title">Creditor Name:</p>
                              <p><strong> {{$data->creditorName}}</strong></p>
                           </div>
                           <div class="col-md-2 details-item">
                              <p class="item-title">Payment Amount:</p>
                              <p><strong> {{$data->amount}}</strong></p>
                           </div>
                           <div class="col-md-3 details-item">
                              <p class="item-title">Payment Date:</p>
                              <p><strong> {{ \Carbon\Carbon::parse($data->paymentDate)->format('D, d M Y') }}</strong></p>
                           </div>
                           <div class="col-md-2 details-item">
                              <p class="item-title">Paying Account:</p>
                              <p><strong> {{$data->account}}</strong></p>
                           </div>
                           <div class="col-md-2 details-item">
                              <p class="item-title">Attachment</p>
                              <p><strong> <a class="dview-status-viewfile" data-lightbox="image" href="{{url("/public/creditorPayment")."/".$data->attachment}}">View Slip</a></strong></p>
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
<script src="https://code.jquery.com/jquery-3.6.4.min.js"
   integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
   $(document).ready(function () {
       $(document).on('click', 'li', function () {
           $('#user').val($(this).text());
           $('#userList').fadeOut();
       });
   });
</script>
@endsection