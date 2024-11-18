@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
   <div class="fluid-container">
      <div class="nk-content-inner">
         <div class="nk-content-body">

            <div class="nk-block-head">
               <div class="nk-block-head-between flex-wrap gap g-2">
                  <div class="nk-block-head-content">
                     <h2 class="nk-block-title">Tutor Details</h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Tutor List</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Tutor Details</li>
                        </ol>
                     </nav>
                  </div>
               </div>
            </div>

            <div class="nk-block">
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
                 <div class="row mb-5">
                    <div class="col-md-12">
                       <div class="card">
                          <div class="card-body">
                             <h3>Tutor Information</h3>
                             <div class="row g-1 view-sindetails">
                                <div class="col-md-3 details-item">
                                   <p class="item-title">Tutor Id</p>
                                   <p><strong>{{$tutor->tutor_id}}</strong></p>
                                </div>
                                <div class="col-md-3 details-item">
                                   <p class="item-title">Status</p>
                                   <p><strong>{{$tutor->status}}</strong></p>
                                </div>
                                <div class="col-md-3 details-item">
                                   <p class="item-title">Start Working Date</p>
                                   <p><strong>{{$tutor->start_date ? $tutor->start_date : "Not Provided"}}</strong></p>
                                </div>
                                <div class="col-md-3 details-item">
                                   <p class="item-title">Full Name</p>
                                   <p><strong>{{$tutor->full_name}}</strong></p>
                                </div>
                                <div class="col-md-3 details-item">
                                   <p class="item-title">Gender</p>
                                   <p><strong>{{$tutor->gender}}</strong></p>
                                </div>
                                <div class="col-md-3 details-item">
                                   <p class="item-title">ID Number</p>
                                   <p><strong>{{$tutor->nric}}</strong></p>
                                </div>
                                <div class="col-md-3 details-item">
                                   <p class="item-title">MaritalStatus</p>
                                   <p><strong>{{$tutor->marital_status}}</strong></p>
                                </div>
                                <div class="col-md-3 details-item">
                                   <p class="item-title">Email</p>
                                   <p><strong>{{$tutor->email}}</strong></p>
                                </div>
                                <div class="col-md-3 details-item">
                                   <p class="item-title">Phone No.</p>
                                   <p><strong>{{$tutor->phoneNumber}}</strong>
                                    <a href="https://wa.me/{{$tutor->whatsapp}}" target="_blank" style="margin-right:5px;"><strong><span class="fa fa-whatsapp text-success"></span></strong></a>
                                    <a href="tel:{{$tutor->phoneNumber}}"><span class="fa fa-phone"></span></a>
                                   </p>
                                </div>
                                <div class="col-md-3 details-item">
                                   <p class="item-title">BankName</p>
                                   <p><strong>{{$tutor->bank_name}}</strong></p>
                                </div>
                                <div class="col-md-3 details-item">
                                   <p class="item-title">Bank Account No.</p>
                                   <p><strong>{{$tutor->bank_account_number}}</strong></p>
                                </div>
                                <div class="col-md-3 details-item">
                                   <p class="item-title">Address</p>
                                   <p><strong>{{$tutor->street_address1}}</strong></p>
                                </div>
                                <div class="col-md-3 details-item">
                                   <p class="item-title">Remark</p>
                                   <p><strong>-</strong></p>
                                </div>
                                
                                <div class="col-md-12">
                                  <h3>Commitment Fee</h3>
                                </div>
                                <p>RM 50 payment receipt is required to be uploaded for a new tutor registration.</p>
                                @php
                                $tutorCommitmentFee = DB::table('tutor_commitment_fees')->where('tutor_id','=',$tutor->id)->get();
                                @endphp
                                @foreach($tutorCommitmentFee as $row)
                                 <div class="col-lg-3 details-item">
                                    <p class="item-title">Remark</p>
                                    <p><a class="dview-status-viewfile" data-lightbox="image" target="_blank" href="{{ asset('/public/tutorPaymentAttachment/' . $row->payment_attachment) }}">View File</a></p>
                                 </div>
                                 <div class="col-md-3 details-item">
                                   <p class="item-title">Fee Payment Date</p>
                                   <p><strong>{{$row->payment_date}}</strong></p>
                                 </div>
                                 <div class="col-md-3 details-item">
                                   <p class="item-title">Fees Paid</p>
                                   <p><strong>{{$row->payment_amount}}</strong></p>
                                 </div>
                                 <div class="col-md-3 details-item">
                                   <p class="item-title">Receiving Account</p>
                                   <p><strong>{{$row->receiving_account}}</strong></p>
                                 </div>
                                @endforeach
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
</div>
@endsection