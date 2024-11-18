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
                        Staff View
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Staff List</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Staff View</li>
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
                           <div class="col-lg-3 details-item">
                              <p class="item-title">Staff ID</p>
                              <p><strong>{{$staff->uid}}</strong></p>
                           </div>
                           <div class="col-lg-3 details-item">
                              <p class="item-title">Start Working Date</p>
                              <p><strong>{{$staff->start_date}}</strong></p>
                           </div>
                           <div class="col-lg-3 details-item">
                              <p class="item-title">Role</p>
                              <p><strong>{{$role_name->name}}</strong></p>
                           </div>
                           <div class="col-lg-3 details-item">
                              <p class="item-title">Basic Salary</p>
                              <p><strong>{{$staff->basic_salary}}</strong></p>
                           </div>
                        </div>

                        <h3>Staff Information</h3>
                        <div class="row g-1 view-sindetails">
                           <div class="col-md-3 details-item">
                              <p class="item-title">Fullname</p>
                              <p><Strong>{{$staff->full_name}}</Strong></p>
                           </div>
                           <div class="col-md-3 details-item">
                              <p class="item-title">Gender</p>
                              <p><Strong>{{$staff->gender}}</Strong></p>
                           </div>
                           <div class="col-md-3 details-item">
                              <p class="item-title">Age</p>
                              <p><Strong>{{$staff->age}}</Strong></p>
                           </div>
                           <div class="col-md-3 details-item">
                              <p class="item-title">Dob</p>
                              <p><Strong>{{$staff->dob}}</Strong></p>
                           </div>
                           <div class="col-md-3 details-item">
                              <p class="item-title">Nric</p>
                              <p><Strong>{{$staff->nric}}</Strong></p>
                           </div>
                           <div class="col-md-3 details-item">
                              <p class="item-title">Email</p>
                              <p><Strong>{{$staff->email}}</Strong></p>
                           </div>
                           <div class="col-md-3 details-item">
                              <p class="item-title">Phone No.</p>
                              <p><Strong>{{$staff->phone}}</Strong></p>
                           </div>
                           <div class="col-lg-3 details-item">
                              <p class="item-title">Marital Status</p>
                              <p><Strong>{{$staff->marital_status}}</Strong></p>
                           </div>
                           <div class="col-lg-3 details-item">
                              <p class="item-title">Attended Training Date</p>
                              <p><Strong>{{$staff->attended_training_date}}</Strong></p>
                           </div>
                           <div class="col-lg-3 details-item">
                              <p class="item-title">Bank Name</p>
                              <p><Strong>{{$staff->bank_name}}</Strong></p>
                           </div>
                           <div class="col-lg-3 details-item">
                              <p class="item-title">Bank Account Number</p>
                              <p><Strong>{{$staff->bank_account_number}}</Strong></p>
                           </div>
                        </div>

                        <h3>Staff Address</h3>
                        <div class="row g-1 view-sindetails">
                           <div class="col-lg-6 details-item">
                              <p class="item-title">Street Address 1</P>
                              <p><Strong>{{$staff->address}}</Strong></P>
                           </div>
                           <div class="col-lg-2 details-item">
                              <p class="item-title">City</p>
                              <p><Strong>{{$city}}</Strong></p>
                           </div>
                           <div class="col-lg-2 details-item">
                              @php
                              $state=DB::table("states")->where("id",$staff->id)->first();
                              @endphp
                              <p class="item-title">State</p>
                              @if(isset($state))
                              <p><Strong>{{$state->name}}</Strong></p>
                              @endif
                           </div>
                           <div class="col-lg-2 details-item">
                              <p class="item-title">Postal Code</p>
                              <p><Strong>{{$staff->postal_code}}</Strong></p>
                           </div>
                           <div class="col-md-12 details-item">
                              <p class="item-title">Remarks</p>
                              <p><Strong>{{$staff->remark}}</Strong></p>
                           </div>
                        </div>
                        <div class="col-md-2">
                           <a class="btn btn-primary" href="/StaffList?status=All&amp;page=1&amp;sortOrder=created">Back</a>
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