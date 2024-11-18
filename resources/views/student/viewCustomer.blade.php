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
                        <div class="col-md-3 details-item">
                           <p class="item-title">Status</p>
                           @if(is_null($commitmentFee))
                           <p class="dtable-status-nostat">Unregistered</p>
                           @elseif(!is_null($commitmentFee) && is_null($jobticketCheck))
                           <p class="dtable-status-inactive">Inactive</p>
                           @elseif(!is_null($commitmentFee) && !is_null($jobticketCheck))
                           <p class="dtable-status-active">Active</p>
                           @endif
                        </div>
                        <div class="col-md-3 details-item">
                           <p class="item-title">Fullname</p>
                           <p><strong>{{ $customers->full_name ?? 'N/A' }}</strong></p>
                        </div>
                        <div class="col-md-3 details-item">
                           <p class="item-title">Gender</p>
                           <p><strong>{{ $customers->gender ?? 'N/A' }}</strong></p>
                        </div>
                        <div class="col-md-3 details-item">
                           <p class="item-title">Email</p>
                           <p><strong>{{ $customers->email ?? 'N/A' }}</strong></p>
                        </div>
                        <div class="col-md-3 details-item">
                           <p class="item-title">Phone No.</p>
                           <p><strong>{{ $customers->phone ?? 'N/A' }}</strong>
                              @if(!empty($customers->phone))
                              <a href="tel:{{ $customers->phone }}"><span class="fa fa-phone"></span></a>
                              @endif
                           </p>
                        </div>
                        
                        <div class="col-md-3 details-item">
                           <p class="item-title">Whatsapp</p>
                           <p><strong>{{ $customers->whatsapp ?? 'N/A' }}</strong>
                              @if(!empty($customers->whatsapp))
                              <a href="https://wa.me/{{ $customers->whatsapp }}" target="_blank"><strong><span class="fa fa-whatsapp text-success"></span></strong></a>
                              @endif
                           </p>
                        </div>
                        
                        <div class="col-md-3 details-item">
                           <p class="item-title">Latitude</p>
                           <p><strong>{{ $customers->latitude ?? 'N/A' }}</strong></p>
                        </div>
                        
                        <div class="col-md-3 details-item">
                           <p class="item-title">Longitude</p>
                           <p><strong>{{ $customers->longitude ?? 'N/A' }}</strong></p>
                        </div>
                        
                        <div class="col-md-3 details-item">
                           <p class="item-title">City</p>
                           <p><strong>{{ $customerCity->name ?? 'N/A' }}</strong></p>
                        </div>
                        
                        <div class="col-md-3 details-item">
                           <p class="item-title">State</p>
                           <p><strong>{{ $customerState->name ?? 'N/A' }}</strong></p>
                        </div>
                        
                        <div class="col-md-6 details-item">
                           <p class="item-title">Address</p>
                           <p><strong>{{ $customers->address ?? 'N/A' }}</strong></p>
                        </div>
                        
                        <div class="col-md-6 details-item">
                           <p class="item-title">Postal Code</p>
                           <p><strong>{{ $customers->postal_code ?? 'N/A' }}</strong></p>
                        </div>
                        
                        <div class="col-md-6 details-item">
                           <p class="item-title">Remark</p>
                           <p><strong>{{ $customers->remarks ?? 'N/A' }}</strong></p>
                        </div>
                        
                        
                        <h3>Assign Admin Incharge</h3>
                        <form method="post" action="{{url("/assignAdminInChargeCustomer")}}">
                           <div class="row">
                              <input type="hidden" name="customer_id" value="{{$customers->id}}">
                              @csrf
                              <div class="col-md-4">
                                 <select class="form-control" name="staff_id">
                                    <option value="">Please select Admin Incharge</option>
                                    @foreach($staffs as $staff)
                                    <option {{$customers->staff_id == $staff->id ? "selected" : ""}} value="{{$staff->id}}">{{$staff->full_name}}</option>
                                    @endforeach
                                 </select>
                              </div>
                              <div class="col-md-3">
                                 <button type="submit" class="btn btn-primary">
                                    Assign Admin Incharge
                                 </button>
                              </div>
                           </div>
                        </form>
                        
                        
                        
                        
                     </div>
                     <div class="row">
                        <div class="col-md-1">
                           <a class="btn btn-primary" href="{{ url('/Customers') }}">Back</a>
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
