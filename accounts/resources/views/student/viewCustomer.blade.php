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
                        Customer View
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Customer List</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Customer View</li>
                        </ol>
                     </nav>
                  </div>
               </div>
            </div>
            <div class="nk-block">
               <div class="card">
                  <div class="card-body">
                     <h3>Customer Information</h3>
                     <div class="row g-1 view-sindetails">
                        <div class="col-md-2 details-item">
                           <p class="item-title">Status</p>
                           @if($commitmentFee == null)
                           <p class="dtable-status-nostat">Unregistered</p>
                           @elseif($commitmentFee != null && $jobticketCheck == null)
                           <p class="dtable-status-inactive">Inactive</p>
                           @elseif($commitmentFee != null && $jobticketCheck != null)
                           <p class="dtable-status-active">Active</p>
                           @endif
                        </div>
                        <div class="col-md-3 details-item">
                           <p class="item-title">Fullname</p>
                           <p><strong>{{$customers->full_name}}</strong></p>
                        </div>
                        <div class="col-md-2 details-item">
                           <p class="item-title">Gender</p>
                           <p><strong>{{$customers->gender}}</strong></p>
                        </div>
                        <div class="col-md-3 details-item">
                           <p class="item-title">Email</p>
                           <p><strong>{{$customers->email}}</strong></p>
                        </div>
                        <div class="col-md-2 details-item">
                           <p class="item-title">Phone No.</p>
                           <p><strong>{{$customers->phone}}</strong>
                              <a href="https://wa.me/{{$customers->whatsapp}}" target="_blank" ><strong><span class="fa fa-whatsapp text-success"></span></strong></a>
                              <a href="tel:{{$customers->phone}}"><span class="fa fa-phone"></span></a></p>
                        </div>
                        <div class="col-md-6 details-item">
                           <p class="item-title">Address</p>
                           <p><strong>{{$customers->address1}}</strong></p>
                        </div>
                        <div class="col-md-6 details-item">
                           <p class="item-title">Remark</p>
                           <p><strong>{{-- $customers->remark --}}</strong></p>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-1">
                           <a class="btn btn-primary" href="{{url("/Customers")}}">Back</a>
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