@extends('layouts.main') @section('content')
<div class="nk-content sifu-view-page">
   <div class="fluid-container">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head">
               <div class="nk-block-head-between flex-wrap gap g-2">
                  <div class="nk-block-head-content">
                     <h2 class="nk-block-title">
                        View Job Ticket: {{$tickets->uid}}
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Job Ticket</a></li>
                           <li class="breadcrumb-item active" aria-current="page">View Job Ticket</li>
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
                     @endif @if (\Session::has('update'))
                     <div class="alert alert-primary">
                        <ul>
                           <li>{!! \Session::get('update') !!}</li>
                        </ul>
                     </div>
                     @endif
                     @if($tickets->ticket_tutor_status=="discontinued")
                     <div class="bio-block sifu-view-template">
                        <div class="row">
                           <div class="col-lg-6 details-item">
                              <p class="title">Ticket Status</p>
                              <p><strong>{{strtoupper($tickets->ticket_tutor_status)}}</strong></p>
                           </div>
                           <div class="col-lg-6 details-item">
                              <p class="title">Reason</p>
                              <p><strong>{{$tickets->reason}}</strong></p>
                           </div>
                        </div>
                        @endif
                        <div class="row g-1 view-sindetails">
                           <div class="col-md-2 details-item">
                              <p class="title">Registration Date</p>
                              <p><strong>{{$tickets->register_date}}</strong></p>
                           </div>
                           {{--
                           <div class="col-md-2 details-item">
                              <p class="title">Admin Incharge</p>
                              <p><strong>@php $staffName = DB::table('staffs')->where('id','=',$tickets->admin_charge)->first(); @endphp {{$staffName->full_name}}</strong></p>
                           </div>
                           --}}
                           <div class="col-md-2 details-item">
                              <p class="title">Ticket Price</p>
                              <p><strong>{{$tickets->totalPrice}}</strong></p>
                           </div>
                           <div class="col-md-2 details-item">
                              <p class="title">Estimate Comission</p>
                              <p><strong>{{$tickets->estimate_commission_display_tutor}}</strong></p>
                           </div>
                           <div class="col-md-2 details-item">
                              <p class="title">Class Type</p>
                              <p><strong>{{$tickets->mode}}</strong></p>
                           </div>
                           <div class="col-md-4 details-item">
                              <p class="title">Subject</p>
                              <p><strong>@php $subjectName = DB::table('products')->join("categories","products.category","=","categories.id") ->select("products.*","categories.price as category_price","categories.category_name as category_name","categories.mode as mode") ->where('products.id','=',$tickets->subjects)
                                 ->first(); //dd($subjectName); @endphp {{$subjectName->name}}, RM {{$subjectName->category_price}} ({{$subjectName->mode}}) ({{$subjectName->category_name}})</strong>
                              </p>
                           </div>
                        </div>
                        <div class="row g-1 view-sindetails existingCustomer customerInfo">
                           <h3 class="existingCustomertHeading"> CUSTOMER / PARENT INFORMATION</h3>
                           <div class="col-lg-3 details-item">
                              <p class="title">Full Name</p>
                              <p><strong>{{$tickets->customerName}}</strong></p>
                           </div>
                           <div class="col-lg-3 details-item">
                              <p class="title">Customer Gender</p>
                              <p><strong>{{$tickets->customerGender}}</strong></p>
                           </div>
                           <div class="col-lg-3 details-item">
                              <p class="title">Email address</p>
                              <p><strong>{{$tickets->customerEmail}}</strong></p>
                           </div>
                           <div class="col-lg-3 details-item">
                              <p class="title">Phone Number</p>
                              <p><strong>{{$tickets->customerPhone}}</strong></p>
                           </div>
                           <div class="col-lg-3 details-item">
                              <p class="title">Whatsapp Number</p>
                              <p><strong>{{$tickets->customerWhatsapp}}</strong></p>
                           </div>
                          <div class="col-lg-3 details-item">
                            <p class="title">State</p>
                            <p><strong>
                                @php 
                                    if($tickets->customerState){
                                        $stateName = DB::table('states')->where('id', '=', $tickets->customerState)->first();
                                        echo $stateName ? $stateName->name : 'N/A';
                                    } else {
                                        echo "No State Selected";
                                    } 
                                @endphp
                            </strong></p>
                        </div>
                        
                        <div class="col-lg-3 details-item">
                            <p class="title">City</p>
                            <p><strong>
                                @php 
                                    if($tickets->customerCity){
                                        $cityName = DB::table('cities')->where('id', '=', $tickets->customerCity)->first();
                                        echo $cityName ? $cityName->name : 'N/A';
                                    } else {
                                        echo "No City Selected";
                                    } 
                                @endphp
                            </strong></p>
                        </div>

                           <div class="col-lg-3 details-item">
                              <p class="title">Postal Code</p>
                              <p><strong>{{$tickets->customerPostalCode}}</strong></p>
                           </div>
                           <div class="col-lg-12 details-item">
                              <p class="title">Address</p>
                              <p><strong>{{$tickets->customerAddress}}</strong></p>
                           </div>
                        </div>
                        <div style="display:none;" class="row g-1 view-sindetails newCustomer">
                           <div class=" col-lg-2 details-item">
                              <p class="title">Latitude</p>
                              <p><strong>{{$tickets->customerLatitude}}</strong></p>
                           </div>
                           <div class=" col-lg-2 details-item">
                              <p class="title">Longitude</p>
                              <p><strong>{{$tickets->customerLongitude}}</strong></p>
                           </div>
                        </div>
                        <div class="row g-1 view-sindetails existingStudent">
                           <h3 class="existingStudentHeading">STUDENT INFORMATION</h3>
                           <div class="col-lg-3 details-item">
                              <p class="title">Full Name</p>
                              <p><strong>{{$studentDetail->full_name}}</strong></p>
                           </div>
                           <div class="col-lg-2 details-item">
                              <p class="title">Gender</p>
                              <p><strong>{{$studentDetail->gender}}</strong></p>
                           </div>
                           <div class="col-lg-1 details-item">
                              <p class="title">Age</p>
                              <p><strong>{{$studentDetail->age}}</strong></p>
                           </div>
                           <div class="col-lg-2 details-item">
                              <p class="title">Date of Birth</p>
                              <p><strong>{{$studentDetail->dob}}</strong></p>
                           </div>
                           <div class="col-lg-2 details-item">
                              <p class="title">Special Need</p>
                              <p><strong>{{$studentDetail->specialNeed}}</strong></p>
                           </div>
                           <div class="col-lg-2 details-item">
                              <p class="title">Subject Fee</p>
                              <p><strong>{{$tickets->subject_fee}}</strong></p>
                           </div>
                        </div>
                        @php $jobTicketStudents = DB::table('job_ticket_students')->where('job_ticket_id','=',$tickets->id)->get(); @endphp @if(count($jobTicketStudents)>0)
                        <div class="row g-1 view-sindetails">
                           <div class="table-responsive">
                              <table class="table sifu-view-table">
                                 <thead>
                                    <tr>
                                       <th>Student Name</th>
                                       <th>Student Gender</th>
                                       <th>Student Age</th>
                                       <th>Student Date of Birth</th>
                                       <th>Student Special Need</th>
                                       <th>Extra Fee</th>
                                       <th>Status</th>
                                    </tr>
                                 </thead>
                                 @foreach( $jobTicketStudents as $rowJobTicketStudents)
                                 <tbody>
                                    <tr>
                                       <td>{{$rowJobTicketStudents->student_name}}</td>
                                       <td>{{$rowJobTicketStudents->student_gender}}</td>
                                       <td>{{$rowJobTicketStudents->student_age}}</td>
                                       <td>{{$rowJobTicketStudents->year_of_birth}}</td>
                                       <td>{{$rowJobTicketStudents->special_need}}</td>
                                       <td>{{$rowJobTicketStudents->extra_fee}}</td>
                                       <td>{{$rowJobTicketStudents->status}}</td>
                                    </tr>
                                 </tbody>
                                 @endforeach
                              </table>
                           </div>
                        </div>
                        @endif
                        @if($customerPaymentFlag==true)
                        <div class="row existingStudent view-sindetails">
                           <h3 class="existingStudentHeading">COMMITMENT FEE</h3>
                           <div class="col-lg-4 details-item">
                              <p class="title">Payment Amount</p>
                              <p><strong><span class="studentFullName"> </span> {{$customerCommitmentFeeCheck->payment_amount}}</strong></p>
                           </div>
                           <div class="col-lg-4 details-item">
                              <p class="title">Payment Date</p>
                              <p><strong>{{$customerCommitmentFeeCheck->payment_date}}</strong></p>
                           </div>
                           <div class="col-lg-4 details-item">
                              <p class="title">File Attachment</p>
                              <p><strong><a href="{{url("/public/customerCommitmentFee")."/".$customerCommitmentFeeCheck->payment_attachment}}" data-lightbox="image">View Attachment</a></strong></p>
                           </div>
                        </div>
                        @endif
                        <div style="display:none;" id="classAddressPanel" class="row">
                           <h3>CLASS ADDRESS</h3>
                           <div class="col-lg-1 details-item2">
                              <label><strong>Same as Customer / Parrent Address</strong>
                              </label>
                              <input type="checkbox" style="width:10px; height:10px;" id="sameAsCustomerAddress" name="sameAsCustomerAddress">
                           </div>
                           <div class="row sameAsCustomer">
                              <div class="col-lg-6 details-item">
                                 <div class="form-group">
                                    <label for="city" class="form-label">Full Address</label>
                                    <div class="form-control-wrap">
                                       <input type="text" class="classAddress form-control" name="classAddress" id="classAddress" placeholder="Class Address ">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3 details-item">
                                 <div class="form-group">
                                    <label for="city" class="form-label">Latitude</label>
                                    <div class="form-control-wrap">
                                       <input type="text" class="classLatitude form-control" name="classLatitude" id="classLatitude" placeholder="Latitude">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3 details-item">
                                 <div class="form-group">
                                    <label for="city" class="form-label">Longitude</label>
                                    <div class="form-control-wrap">
                                       <input type="text" class="classLongitude form-control" id="classLongitude" name="classLongitude" placeholder="Longitude">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3 details-item">
                                 <div class="form-group">
                                    <label for="city" class="form-label">State</label>
                                    <div class="form-control-wrap">
                                       <select class="js-select" data-search="true" data-sort="true" name="classState" id="classState">
                                          @php $states = DB::table('states')->get(); @endphp @foreach($states as $rowStates)
                                          <option value="{{$rowStates->id}}">{{$rowStates->name}}</option>
                                          @endforeach
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3 details-item">
                                 <div class="form-group">
                                    <label for="city" class="form-label">City</label>
                                    <div class="form-control-wrap">
                                       <select class="form-control" data-search="true" data-sort="true" name="classCity" id="classCity">
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3 details-item">
                                 <div class="form-group">
                                    <label for="postalcode" class="form-label">Postal Code</label>
                                    <div class="form-control-wrap">
                                       <input type="text" class="classPostalCode form-control" name="classPostalCode" id="classPostalCode" placeholder="Class Postal Code">
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>

                        <h3>CLASS INFORMATION</h3>
                        <div class="table-responsive">
                           <table class="table sifu-view-table">
                              <thead>
                                 <tr>
                                    <th>Ticket ID</th>
                                    <th>Class Frequency</th>
                                    <th>Class Duration</th>
                                    <th>Subject</th>
                                    <th>Ticket Type</th>
                                    <th>Time</th>
                                    <th>Day</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr>
                                    <td>
                                       <span>{{$tickets->uid}}</span>
                                    </td>
                                    <td>
                                       <span>{{$tickets->classFrequency}}</span>
                                    </td>
                                    <td>
                                       <span>{{$tickets->quantity}}</span>
                                    </td>
                                    <td>
                                       <span>{{$tickets->subject_name}}
                                       @php
                                       $subjectID = DB::table('products')->where('id','=',$tickets->subject_id)->first();
                                       $categoryName = DB::table('categories')->where('id','=',$subjectID->category)->first();
                                       //dd($subjectID);
                                       @endphp
                                       ( {{$categoryName->category_name}} )
                                       </span>
                                    </td>
                                    <td>
                                       <span>{{$tickets->subscription}}</span>
                                    </td>
                                    <td>
                                       <span>{{$tickets->time}}</span>
                                    </td>
                                    <td>
                                       <span>{{$tickets->day}}</span>
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                           <input type="hidden" class="class_frequency" value="{{$tickets->quantity}}">
                        </div>

                        @if($tickets->application_status!="completed")
                        <h3>Assign Tutor</h3>
                        <form  method="post" action="{{url("/assignTutor"."/".$tickets->subjects."/".$tickets->id)}}">
                          <div class="row">
                             @csrf
                             <div class="col-md-4">
                                <select data-search="true" data-sort="false"  class="form-control" name="tutor_id" id="tutors">
                                   <option value="">Please select tutor</option>
                                   @foreach($tutors as $tut)
                                   <option value="{{$tut->id}}">{{$tut->full_name}}</option>
                                   @endforeach
                                </select>
                             </div>
                             <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">
                                Assign  Tutor
                                </button>
                             </div>
                          </div>
                        </form>
                        @endif

                        <h3>Assign Student Customer Service Incharge </h3>
                        <form  method="post" action="{{url("/assignAdminInCharge")}}">
                          <div class="row">
                             <input type="hidden" name="ticket_id" value="{{$tickets->id}}">
                             @csrf
                             <div class="col-md-4">
                                <select class="form-control" name="staff_id" data-search="true" data-sort="false" id="staffs">
                                   <option value="">Please select Admin Incharge</option>
                                   @foreach($staffs as $staff)
                                   <option {{$tickets->admin_charge==$staff->id?"selected":""}} value="{{$staff->id}}">{{$staff->full_name}}</option>
                                   @endforeach
                                </select>
                             </div>
                             <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">
                                Assign  Admin Incharge
                                </button>
                             </div>
                          </div>
                        </form>

                        <h3>Ticket Applications</h3>
                        @if($tickets)
                        <div class="table-responsive">
                           <table class="table sifu-view-table">
                              <thead>
                                 <tr>
                                    <th>Tutor Name</th>
                                    <th>Status</th>
                                    <th>Comment</th>
                                    <th>Action</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @php $ticketDetail = DB::table('tutoroffers')->join("tutors","tutoroffers.tutorID","=","tutors.id") ->select("tutoroffers.*","tutors.status as tutorStatus") ->where('tutoroffers.ticketID','=',$tickets->id)->get(); //dd($ticketDetail); @endphp @foreach($ticketDetail
                                 as $rowTicketOffer) @php $ticketStatus = DB::table('job_tickets')->where('id','=',$rowTicketOffer->ticketID)->first() @endphp
                                 <tr>
                                    <td>
                                       @php $tutorName = DB::table('tutors')->where('id','=',$rowTicketOffer->tutorID)->first(); @endphp @if($tutorName) {{$tutorName->full_name}}, {{$tutorName->uid}} @endif
                                    </td>
                                    <td>
                                       {{ucfirst($rowTicketOffer->status)}}
                                    </td>
                                    <td>{{$rowTicketOffer->comment=="null"?"":$rowTicketOffer->comment}}</td>
                                    <td>
                                       @if($rowTicketOffer->status == 'approved') @if($ticketOffersCheck==null)
                                       <button class="btn dtable-status-viewfile" style="cursor: not-allowed;" type="button" data-html="true" data-placement="right">Complete Job Ticket</button>
                                       @else @can("job-ticket-tutor-application")
                                       <button data-toggle="modal" data-target="#myModal" class="btn dtable-status-viewfile" data-placement="right">Complete Job Ticket</button>
                                       @endcan @endif
                                       <!-- The Modal -->
                                       <div class="modal fade dtable-modal" id="myModal">
                                          <div class="modal-dialog modal-lg">
                                             <div class="modal-content">
                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                   <h5 class="modal-title">Schedule Class</h5>
                                                   <button type="button" class="close" data-dismiss="modal">
                                                   &times;
                                                   </button>
                                                </div>
                                                <!-- Modal body -->
                                                <div class="modal-body">
                                                   <div class="card-body">
                                                      <form method="POST" action="{{route('submitClassSchedules')}}" enctype="multipart/form-data">
                                                         @csrf
                                                         <input type="hidden" name="studentID" value="{{$studentDetail->id}}" />
                                                         <input type="hidden" name="ticketID" value="{{$tickets->id}}" /> @if($tickets->tutor_id)
                                                         <input type="hidden" name="tutorID" value="{{$tickets->tutor_id}}" /> @endif
                                                         <div class="row view-sindetails g-1">
                                                            <div class="col-md-12 details-item">
                                                               <p class="item-title">Fullname</p>
                                                               <p><strong>{{$studentDetail->full_name}}</strong></p>
                                                            </div>
                                                            <div class="col-md-6 details-item">
                                                               <p class="item-title">StudentId</p>
                                                               <p><strong>{{$studentDetail->uid}}</strong></p>
                                                            </div>
                                                            <div class="col-md-6 details-item">
                                                               <p class="item-title">Subject</p>
                                                               <p><strong>@php
                                                                  $getSubject = DB::table('student_subjects')->where('ticket_id','=',$tickets->id)->first();
                                                                  $getSubjectName = DB::table('products')->where('id','=',$getSubject->subject)->first();
                                                                  @endphp
                                                                  {{$getSubjectName->name}}</strong>
                                                               </p>
                                                            </div>
                                                         </div>
                                                         <div class="row g-3">
                                                            <div class="col-lg-6">
                                                               <div class="form-group">
                                                                  <label for="firstname" class="form-label">Date</label>
                                                                  <div class="form-control-wrap">
                                                                     <input type="date" name="date" class="form-control" id="scheduleDate" min="" required>
                                                                  </div>
                                                               </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                               <div class="form-group">
                                                                  <label for="firstname" class="form-label">Subject</label>
                                                                  <div class="form-control-wrap">
                                                                     <input type="text" class="form-control" readonly value="{{$getSubjectName->name}}" />
                                                                     <input type="hidden" name="subjectID" value="{{$subjectID->id}}" />
                                                                  </div>
                                                               </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                               <div class="form-group">
                                                                  <label for="firstname" class="form-label">Start Time</label>
                                                                  <div class="form-control-wrap">
                                                                     <input type="time" name="start_time" class="form-control" id="startTime" onchange="updateEndTime()">
                                                                  </div>
                                                                  <label id="timeErrorMsg" style="color:red!important;display:none" class="form-label">There is already class scheduled for the given time slot, please select another time</label>
                                                               </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                               <div class="form-group">
                                                                  <label for="firstname" class="form-label">End Time</label>
                                                                  <div class="form-control-wrap">
                                                                     <input type="time" name="end_time" class="form-control" id="endTime" readonly>
                                                                  </div>
                                                               </div>
                                                            </div>
                                                            <div style="display:none" class="col-lg-6">
                                                               <div class="form-group">
                                                                  <label for="firstname" class="form-label">Has Incentive</label>
                                                                  <div class="form-control-wrap">
                                                                     <input type="checkbox" name="has_incentive">
                                                                  </div>
                                                               </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                               <div class="form-group">
                                                                  <label for="firstname" class="form-label">Total Classes</label>
                                                                  <div class="form-control-wrap">
                                                                     <input readonly type="text" name="totalClasses" value="{{$tickets->classFrequency}}" class="form-control">
                                                                  </div>
                                                               </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                               <div class="form-group">
                                                                  <label for="firstname" class="form-label">Remaining Classes</label>
                                                                  <div class="form-control-wrap">
                                                                     <input readonly type="text" name="remaining_classes" value="{{$tickets->remaining_classes}}" class="form-control">
                                                                  </div>
                                                               </div>
                                                            </div>
                                                            @if($customerPaymentFlag==false)
                                                            <div class="col-lg-12">
                                                               <h5 class="modal-title">CUSTOMER COMMITMENT FEE</h5>
                                                               <p>Payment receipt is required to be uploaded for a new tutor registration.</p>
                                                            </div>
                                                            <div class="col-lg-6">
                                                               <div class="form-group">
                                                                  <label for="city" class="form-label">Payment Attachment
                                                                  </label>
                                                                  <div class="form-control-wrap">
                                                                     <input type="file" class="form-control" name="paymentAttachment" id="paymentAttachment" required>
                                                                  </div>
                                                               </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                               <div class="form-group">
                                                                  <label for="city" class="form-label">Payment Amount
                                                                  </label>
                                                                  <div class="form-control-wrap">
                                                                     <input type="text" class="form-control" name="feeAmount" id="feeAmount" required>
                                                                  </div>
                                                               </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                               <div class="form-group">
                                                                  <label for="city" class="form-label">Fee Payment Date</label>
                                                                  <div class="form-control-wrap">
                                                                     <input type="date" class="form-control" name="feePaymentDate" id="feePaymentDate" required>
                                                                  </div>
                                                               </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                               <div class="form-group">
                                                                  <label for="city" class="form-label">Receiving Account</label>
                                                                  <div class="form-control-wrap">
                                                                     <select class="js-select" name="receivingAccount" id="receivingAccount" data-search="true" data-sort="false" required>
                                                                        <option value=""></option>
                                                                        <option value="Cash At Bank - My Bank">Cash At Bank - My Bank</option>
                                                                        <option value="Cash in Hand">Cash in Hand
                                                                        </option>
                                                                        <option value="Payment Gateway">Payment Gateway
                                                                        </option>
                                                                     </select>
                                                                  </div>
                                                               </div>
                                                            </div>
                                                            @endif
                                                            <div class="col-lg-12">
                                                               <input type="submit" class="btn btn-primary" value="Add Class Schedule">
                                                            </div>
                                                         </div>
                                                      </form>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       @else @if($rowTicketOffer->status == 'rejected') @else @can("job-ticket-complete") @if($rowTicketOffer->tutorStatus=="verified")
                                       <a class="btn btn-success" href="{{route('tutoOfferActionApprove',$rowTicketOffer->id)}}">Approve</a>
                                       <a class="btn btn-danger" href="{{route('tutoOfferActionReject',$rowTicketOffer->id)}}">Reject</a> @else
                                       <a class="btn btn-success" disabled style="cursor: not-allowed;" data-toggle="tooltip" data-placement="top" title="Tutor is unverified" href="javascript:void(0)">Approve</a>
                                       <a class="btn btn-danger" disabled style="cursor: not-allowed;" data-toggle="tooltip" data-placement="top" title="Tutor is unverified" href="javascript:void(0)">Reject</a> @endif @endcan @endif @endif
                                    </td>
                                 </tr>
                                 @endforeach
                              </tbody>
                           </table>
                        </div>
                        @else

                        <p><i>There's no application yet.</i></p>
                        @endif
                        <div class="row row-details mt-5">
                           <div class="col-md-3">
                              <a class="btn btn-light waves-effect waves-light" href="{{route('TicketList')}}">Back</a>
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
<script>
   function updateEndTime() {
          var startTimeInput = document.getElementById("startTime");
          var startTimeValue = startTimeInput.value;
          const duration = parseFloat($(".class_frequency").val());
          var [hours, minutes] = startTimeValue.split(":").map(Number);
          var totalMinutes = hours * 60 + minutes;
          totalMinutes += duration * 60;
          hours = Math.floor(totalMinutes / 60);
          minutes = totalMinutes % 60;
          var updatedEndTime = (hours < 10 ? "0" : "") + hours + ":" + (minutes < 10 ? "0" : "") + minutes;
          var endTimeInput = document.getElementById("endTime");
          endTimeInput.value = updatedEndTime;
      }
</script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key=AIzaSyCoyz3w12jbAONjZzu6E4WO9ogZInFV5aM&libraries=places"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyCoyz3w12jbAONjZzu6E4WO9ogZInFV5aM"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script>
   $(document).ready(function () {
          function setToday() {
              var today = new Date();
              var dd = String(today.getDate()).padStart(2, '0');
              var mm = String(today.getMonth() + 1).padStart(2, '0');
              var yyyy = today.getFullYear();
              var todayFormatted = yyyy + '-' + mm + '-' + dd;
              document.getElementById('scheduleDate').value = todayFormatted;
              document.getElementById('scheduleDate').min = todayFormatted;
          }
          window.onload = setToday;
      });
</script>
<script>
   $(document).ready(function () {
          $("#startTime").on("change",function(){
             $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
              });
              let dateInput=$("#scheduleDate").val();
              let startTime=$("#startTime").val();
              let endTime=$("#endTime").val();
              let tutorId=$("input[name=tutorID]").val();
                 $.ajax({
                  url: '{{ route('checkSchedule') }}',
                  type: 'POST',
                  data: {
                      dateInput: dateInput,
                      startTime: startTime,
                      endTime: endTime,
                      tutorId: tutorId,
      
      
                  },
                  success: function (response) {
                      if(response.recordFound==true)
                      {
      
                          $("#timeErrorMsg").css("display","block");
                          $(".btn-success").css("display","none");
                          $('#startTime').val('');
                      }else{
                          $("#timeErrorMsg").css("display","none");
                          $(".btn-success").css("display","block");
                      }
                      // console.log(response.recordFound);
                      // alert(response.recordFound);
                  },
                  error: function (error) {
                      // Handle any errors during the AJAX request
                      console.error(error);
                      // alert('Error occurred during the request.');
                  }
              });
          });
      
          // Denotes total number of rows
          var rowIdx = 0;
          // jQuery button click event to add a row
          $('#addBtn').on('click', function () {
              // Adding a row inside the tbody.
              $('#tbody').append(`<tr id="R${++rowIdx}">
                                      <td><select class="form-control js-select" data-search="true" data-sort="true" name="subject[]"><option value=""></option>@foreach($subjects as $subjectRow)<option value="{{$subjectRow->id}}"> {{$subjectRow->name}}</option>@endforeach<select></td>
                                              <td><input class="form-control" type="text" value="" name="quantity[]"></td>
                                              <td><select class="form-control js-select" name="day[]"><option value="Monday">Monday</option><option value="Tuesday">Tuesday</option><option value="Wednesday">Wednesday</option><option value="Thursday">Thursday</option><option value="Friday">Friday</option><option value="Saturday">Saturday</option><option value="Sunday">Sunday</option><select> </td>
                                              <td><input class="form-control" type="time" value="22:00" name="time[]"></td>
                                              <td><select class="form-control js-select" name="tutorPereference[]"><option value="male">Male</option><option value="Female">Female</option><select></td>
                                              <td><select class="form-control js-select" name="subscription[]"><option value="LongTerm">Long Term</option><option value="shortTerm">Short Term</option><select></td>
                                              <td><input class="form-control" type="text"  name="specialRequest[]"></td>
                                        <td class="text-center">
                                          <button style="background-color:red; color:#fff" class="btn btn-sm remove"
                                            type="button"> X </button>
                                          </td>
                                        </tr>`);
          });
      
          // jQuery button click event to remove a row.
          $('#tbody').on('click', '.remove', function () {
      
              // Getting all the rows next to the row
              // containing the clicked button
              var child = $(this).closest('tr').nextAll();
      
              // Iterating across all the rows
              // obtained to change the index
              child.each(function () {
      
                  // Getting <tr> id.
                  var id = $(this).attr('id');
      
                  // Getting the <p> inside the .row-index class.
                  var idx = $(this).children('.row-index').children('p');
      
                  // Gets the row number from <tr> id.
                  var dig = parseInt(id.substring(1));
      
                  // Modifying row index.
                  idx.html(`Row ${dig - 1}`);
      
                  // Modifying row id.
                  $(this).attr('id', `R${dig - 1}`);
              });
      
              // Removing the current row.
              $(this).closest('tr').remove();
      
              // Decreasing total number of rows by 1.
              rowIdx--;
          });
      });
</script>
<script>
   $(document).ready(function () {
          // Get the source input field
          var $sourceInput = $('#mobile_code');
      
          // Get the target input field
          var $targetInput = $('#whatsapp_code');
      
          // Listen for input changes in the source input field
          $sourceInput.on('input', function () {
              // Get the value from the source input
              var inputValue = $sourceInput.val();
      
              // Set the same value in the target input field
              $targetInput.val(inputValue);
          });
      
      
          var $getAgeInput = $('#age');
          var $getStudentDateOfBirth = $('#studentDateOfBirth');
      
          // Listen for input changes in the source input field
          $getAgeInput.on('input', function () {
              // Get the value from the source input
              var ageInputValue = $getAgeInput.val();
              // Get the age input value
              var age = parseInt(ageInputValue);
              // Get the current date
              var currentDate = new Date();
              // Calculate the birth year by subtracting age from the current year
              var birthYear = currentDate.getFullYear() - age;
              // Create a new Date object for the calculated birth year
              var dob = new Date(birthYear, 0, 1); // Assuming January 1st as the birthdate
              // Format the date of birth (DOB)
              var dobFormatted = dob.toLocaleDateString('en-US', {
                  year: 'numeric',
                  month: 'long',
                  day: 'numeric'
              });
              // Display the calculated DOB
              $('#dobResult').text(dobFormatted);
              // Set the same value in the target input field
              $getStudentDateOfBirth.val(dobFormatted);
          });
      });
      
      
      $("select#customerState").change(function () {
          var customerState = $(this).children("option:selected").val();
      
          $.ajax({
              url: "{{url('addTicketAjaxPOSTcustomerState')}}",
              dataType: "json",
              data: {
                  customerState: customerState,
                  _token: '{{csrf_token()}}'
              },
              type: "post",
              success: function (data) {
                  $('#customerCity').append(data.cities);
              }
          });
      
      });
      
      
      $('[name="sameAsCustomerAddress"]').change(function () {
          if ($(this).is(':checked')) {
              // Do something...
      
              $('.sameAsCustomer').hide();
          } else {
              $('.sameAsCustomer').show();
          }
          ;
      });
      
      
      $("select#classType").change(function () {
          var classType = $(this).children("option:selected").val();
      
          if (classType == "online") {
      
              $('#classAddressPanel').hide();
      
      
          } else {
              $('#classAddressPanel').show();
      
      
          }
      
      });
      
      $("select#classState").change(function () {
          var classState = $(this).children("option:selected").val();
      
      
          $.ajax({
              url: "{{url('addTicketAjaxPOSTclassState')}}",
              dataType: "json",
              data: {
                  classState: classState,
                  _token: '{{csrf_token()}}'
              },
              type: "post",
              success: function (data) {
      
                  $('#classCity').append(data.cities);
              }
          });
      
      
      });
      
      
      google.maps.event.addDomListener(window, 'load', initialize);
      
      function initialize() {
          var input = document.getElementById('address');
          var studentInput = document.getElementById('studentAddress');
          var autocomplete = new google.maps.places.Autocomplete(input);
          var studentAutocomplete = new google.maps.places.Autocomplete(studentInput);
          autocomplete.addListener('place_changed', function () {
              var place = autocomplete.getPlace();
              // place variable will have all the information you are looking for.
      
              document.getElementById("customerLatitude").value = place.geometry['location'].lat();
              document.getElementById("customerLongitude").value = place.geometry['location'].lng();
          });
          studentAutocomplete.addListener('place_changed', function () {
              var place = studentAutocomplete.getPlace();
              document.getElementById("studentLatitude").value = place.geometry['location'].lat();
              document.getElementById("studentLongitude").value = place.geometry['location'].lng();
          });
      }
      
      $(document).ready(function () {
      
          var autocomplete;
          var studentAutocomplete;
          autocomplete = new google.maps.places.Autocomplete((document.getElementById(input)), {
              types: ['geocode'],
              componentRestrictions: {
                  country: "MY"
              }
          });
      
          studentAutocomplete = new google.maps.places.Autocomplete((document.getElementById(studentInput)), {
              types: ['geocode'],
              componentRestrictions: {
                  country: "MY"
              }
          });
      
          google.maps.event.addListener(autocomplete, 'place_changed', function () {
              var near_place = autocomplete.getPlace();
              document.getElementById('loc_lat').value = near_place.geometry.location.lat();
              document.getElementById('loc_long').value = near_place.geometry.location.lng();
      
              document.getElementById('customerLatitude').value = near_place.geometry.location.lat();
              document.getElementById('customerLongitude').value = near_place.geometry.location.lng();
      
              document.getElementById('latitude_view').innerHTML = near_place.geometry.location.lat();
              document.getElementById('longitude_view').innerHTML = near_place.geometry.location.lng();
          });
      
          google.maps.event.addListener(studentAutocomplete, 'place_changed', function () {
              var near_place = studentAutocomplete.getPlace();
              document.getElementById('studentLatitude').value = near_place.geometry.location.lat();
              document.getElementById('studentLongitude').value = near_place.geometry.location.lng();
      
              document.getElementById('latitude_view').innerHTML = near_place.geometry.location.lat();
              document.getElementById('longitude_view').innerHTML = near_place.geometry.location.lng();
          });
      
      
      });
      
      $(document).on('change', '#' + input, function () {
          document.getElementById('customerLatitude').value = '';
          document.getElementById('customerLongitude').value = '';
      
          document.getElementById('latitude_view').innerHTML = '';
          document.getElementById('longitude_view').innerHTML = '';
      
      
      });
      
      var wto;
      $(document).on('change', '#' + studentInput, function () {
          document.getElementById('customerLatitude').value = '';
          document.getElementById('customerLongitude').value = '';
          document.getElementById('latitude_view_two').innerHTML = '';
          document.getElementById('longitude_view_two').innerHTML = '';
      });
</script>
<script>
   $(document).ready(function () {
      
      
          $("select#student_id").change(function () {
              var selectedStudent = $(this).children("option:selected").val();
              if (selectedStudent == 'newStudent') {
                  
                  $('.newCustomerDD').show();
                  $('.existingCustomerDD').hide();
      
                  $('.newCustomer').show();
                  $('.existingCustomer').hide();
      
                  $('.newStudent').show();
                  $('.existingStudent').hide();
      
                  $('.addNewStudentHeading').show();
                  $('.existingStudentHeading').hide();
      
      
                  $('.addNewCustomerHeading').show();
                  $('.existingCustomertHeading').hide();
      
              } else {
      
                  $('.newStudent').hide();
                  $('.existingStudent').show();
      
                  $('.newCustomer').hide();
                  $('.existingCustomer').show();
      
                  $('.addNewStudentHeading').hide();
                  $('.existingStudentHeading').show();
      
                  $('.newCustomer').hide();
                  $('.existingCustomer').show();
      
      
                  $('.addNewCustomerHeading').hide();
                  $('.existingCustomertHeading').show();
      
                  $('.newCustomerDD').hide();
                  $('.existingCustomerDD').show();
      
      
              }
              var userURL = $(this).data('url');
              $.ajax({
                  url: "{{ url('/addTicket') }}/" + selectedStudent,
                  type: 'GET',
                  dataType: 'json',
                  success: function (data) {
                      console.log(data);
                      $('.customerId').text(data.customer.uid);
                      $('.customerId').text(data.customer.uid);
                      $('.customerFullName').text(data.customer.full_name);
                      $('.parentFullName').val(data.customer.full_name);
                      $('.customerEmail').text(data.customer.email);
                      $('.customerGender').text(data.customer.gender);
                      $('.existingParent_id').val(data.customer.id);
                      $('.customerPhone').text(data.customer.phone);
                      $('.customerWhatsapp').text(data.customer.whatsapp);
                      $('#address').text(data.customer.address1);
                      $('.customerStreetAddress2').val(data.customer.address2);
                      $('.customerNRIC').text(data.customer.nric);
                      $('.customerDOB').text(data.customer.dob);
                      $('.customerCity').text(data.customer.city);
                      $('.customerLatitude').text(data.customer.latitude);
                      $('.customerLongitude').text(data.customer.longitude);
                      $('.customerPostalCode').text(data.customer.postal_code);
                      $('.customerState').text(data.customer.state);
                      $('.studentRegisterDate').text(data.student.register_date);
                      $('.studentId').text(data.student.uid);
                      $('.studentFullName').text(data.student.full_name);
                      $('.studentPhone').text(data.student.phone);
                      $('.studentWhatsapp').text(data.student.whatsapp);
                      $('.studentEmail').text(data.student.email);
                      $('.studentAge').text(data.student.age);
                      $('.studentGender').text(data.student.gender);
                      $('.studentAddress').text(data.student.address1);
                      $('.studentStreetAddress2').text(data.student.address2);
                      $('.studentNRIC').val(data.student.cnic);
                      $('.studentDateOfBirth').text(data.student.dob);
                      $('.studentCity').text(data.student.city);
                      $('.studentLatitude').val(data.student.latitude);
                      $('.studentLongitude').val(data.student.longitude);
                      $('.studentPostalCode').val(data.student.postal_code);
                  }
              });
          });
      
          $("select#parent_id").change(function () {
              var selectedParent = $(this).children("option:selected").val();
              if (selectedParent == 'newParent') {       
                  $('.newCustomer').show();
                  $('.existingCustomer').hide();
                  $('.addNewCustomerHeading').show();
                  $('.existingCustomertHeading').hide();
                  console.log("Line 984");
              } else {
                  $('.addNewCustomerHeading').hide();
                  $('.existingCustomertHeading').show();
                  $('.newCustomer').hide();
                  $('.existingCustomer').show();
                  console.log("Line 994");
              }
              var userURL = $(this).data('url');
              $.ajax({
                  url: "{{ url('/addTicketAjaxCallParrent') }}/" + selectedParent,
                  type: 'GET',
                  dataType: 'json',
                  success: function (data) {
                      console.log(data);
                      $('.customerId').text(data.customer.uid);
                      $('.customerId').val(data.customer.uid);
                      $('.customerFullName').text(data.customer.full_name);
                      $('.customerEmail').text(data.customer.email);
                      $('.customerGender').text(data.customer.gender);
                      $('.customerPhone').text(data.customer.phone);
                      $('.customerStreetAddress1').text(data.customer.address1);
                      $('.customerStreetAddress2').text(data.customer.address2);
                      $('.customerNRIC').text(data.customer.nric);
                      $('.customerDOB').text(data.customer.dob);
                      $('.customerCity').text(data.customer.city);
                      $('.customerLatitude').text(data.customer.latitude);
                      $('.customerLongitude').text(data.customer.longitude);
                      $('.customerPostalCode').text(data.customer.postal_code);
                  }
              });
          });
      });
</script>
@endsection
