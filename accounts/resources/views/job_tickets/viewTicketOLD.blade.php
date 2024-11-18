@extends('layouts.main')

@section('content')

        <div class="nk-content">
            <div class="fluid-container">
              <div class="nk-content-inner">
                <div class="nk-content-body">
                  <div class="nk-block-head">
                    <div class="nk-block-head">
                      <div class="nk-block-head-between flex-wrap gap g-2 align-items-center">
                        <div class="nk-block-head-content">
                          <div class="d-flex flex-column flex-md-row align-items-md-center">
                            <div class="mt-3 mt-md-0 ms-md-3">
                              <h3 class="title mb-1">Edit Job Ticket : {{$tickets->uid}}</h3>
                            </div>
                          </div>
                        </div>
                        
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
                          <form method="POST" action="{{route('submitEditJobTicket')}}">
                              @csrf
                              <input type="hidden" name="jobTicketID" value="{{$tickets->id}}"/>
                            <div class="row g-3">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                      <label for="firstname" class="form-label">Registration Date</label>
                                      <div class="form-control-wrap"><input type="date" name="registration_date" class="form-control" value="{{$tickets->register_date}}" id="registrationDate"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                      <label for="firstname" class="form-label">Estimate Comission</label>
                                      <div class="form-control-wrap"><input required="required" type="text" value="{{$tickets->estimate_commission}}" name="estimate_comission" class="form-control"></div>
                                    </div>
                                </div>
                              
                                <div class="col-lg-3">
                                    <div class="form-group">
                                      <label for="firstname" class="form-label">Class Type</label>
                                      <div class="form-control-wrap">
                                          <select class="js-select" data-search="true" data-sort="false" name="classType" required>
                                              <option value="{{$tickets->mode}}">{{$tickets->mode}}</option>
                                              <option value="physical">Physical</option>
                                              <option value="online">Online</option>
                                          </select>
                                          </div>
                                    </div>
                                </div>
                                
                            <div  class="customerInfo">
                                
                                
                               <div class="row g-3">
                                    <h3 style="color:#fff; border-radius:5px;background-color: #2e314a; box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;  padding:10px; margin-top:50px;">CUSTOMER / PARENT INFORMATION</h3>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                              <label for="firstname" class="form-label">Full Name</label>
                                              <div class="form-control-wrap"><input type="text" class="customerFullName form-control" id="fullName" name="customerFullName"  value="{{$customers->full_name}}"></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="firstname" class="form-label">Gender</label>
                                                <div class="form-control-wrap">
                                                    <select class="js-select" id="customerGender" name="customerGender" data-search="true" data-sort="false">
                                                        <option value="{{$customers->gender}}">{{$customers->gender}}</option>
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                              <label for="email" class="form-label">Email address</label>
                                              <div class="form-control-wrap"><input type="text" class="customerEmail form-control" name="customerEmail" id="email" value="{{$customers->email}}"></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                              <label for="company" class="form-label">Date of Birth</label>
                                              <div class="form-control-wrap"><input type="date" class="customerDOB form-control" name="customerDateOfBirth" id="dateOfBirth" value="{{$customers->dob}}"></div>
                                            </div>
                                      </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                              <label for="address" class="form-label">NRIC</label>
                                              <div class="form-control-wrap"><input type="text" class="customerNRIC form-control" id="CNIC" name="customerCNIC" value="{{$customers->nric}}"></div>
                                            </div>
                                        </div>
                                </div>
                                <div class="row g-3">
                                    
                                    <h3 style="color:#fff; border-radius:5px;background-color: #2e314a; box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;  padding:10px; margin-top:50px;">CUSTOMER ADDRESS</h3>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                          <label for="city" class="form-label">Street Address 1</label>
                                          <div class="form-control-wrap"><input type="text" class="customerStreetAddress1 form-control" name="customerStreetAddress1" id="streetAddress1" value="{{$customers->address2}}"></div>
                                        </div>
                                    </div>
    
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                          <label for="city" class="form-label">Street Address 2</label>
                                          <div class="form-control-wrap"><input type="text" class="customerStreetAddress2 form-control" name="customerStreetAddress2" id="streetAddress2" value="{{$customers->address2}}"></div>
                                        </div>
                                    </div>
    
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                          <label for="city" class="form-label">City</label>
                                          <div class="form-control-wrap"><input type="text" class="customerCity form-control" name="customerCity" id="city" value="{{$customers->city}}"></div>
                                        </div>
                                    </div>
                                  <div class="col-lg-3">
                                    <div class="form-group">
                                      <label for="postalcode" class="form-label">Postal Code</label>
                                      <div class="form-control-wrap"><input type="text" class="customerPostalCode form-control" name="customerPostalcode" id="postalcode" value="{{$customers->postal_code}}"></div>
                                    </div>
                                  </div>
                                  
                                  <div class="col-lg-3">
                                    <div class="form-group">
                                      <label for="city" class="form-label">Latitude</label>
                                      <div class="form-control-wrap"><input type="text" class="customerLatitude form-control" name="customerLatitude" id="customerLatitude" value="{{$customers->latitude}}"></div>
                                    </div>
                                  </div>
    
                                  <div class="col-lg-3">
                                    <div class="form-group">
                                      <label for="city" class="form-label">Longitude</label>
                                      <div class="form-control-wrap"><input type="text" class="customerLongitude form-control" id="customerLongitude" name="customerLongitude" value="{{$customers->longitude}}"></div>
                                    </div>
                                  </div>
                                </div>
                            </div>
                            <div class="existingStudentInfoTwo row g-3">
                               <h3 style="color:#fff; border-radius:5px;background-color: #2e314a; box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;  padding:10px; margin-top:50px;">STUDENT INFORMATION</h3>
                                      <table class="table table-bordered">
                                        <thead class="table-dark">
                                          <tr>
                                            <th style="background-color:lightcyan; width:30%" class="text-center">Full Name</th>
                                            <th style="background-color:lightcyan; width:30%" class="text-center">Email Address</th>
                                            <th style="background-color:lightcyan; width:30%" class="text-center">Date of Birth</th>
                                            
                                            
                                          </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                             <td ><div class="form-control-wrap"><input type="text" class="studentFullName form-control" id="firstname" name="full_name" value="{{$students->full_name}}"></div></td>
                                             <td ><div class="form-control-wrap"><input type="text" class="studentEmail form-control" name="email" value="{{$students->email}}"></div></td>
                                             <td ><div class="form-control-wrap"><input type="date" class="studentDOB form-control" name="dob" value="{{$students->dob}}"></div></td>
                                             
                                             
                                            </tr>  
                                        </tbody>
                                      </table>
                                      <table class="table table-bordered">
                                        <thead class="table-dark">
                                          <tr>
                                            <th style="background-color:lightcyan; width:30%" class="text-center">Gender</th>
                                            <th style="background-color:lightcyan; width:30%" class="text-center">Age</th>
                                            <th style="background-color:lightcyan; width:30%" class="text-center">CNIC</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                             <td ><div class="form-control-wrap"><select class="studentGender js-select form-control" data-search="true" name="gender" data-sort="false">
                                                 <option value="{{$students->gender}}">{{$students->gender}}</option><option value="Male">Male</option><option value="Female">Female</option></select></div></td>
                                             <td ><div class="form-control-wrap"><input type="text" class="studentAge form-control" name="age" value="{{$students->age}}"></div></td>
                                             
                                             <td ><div class="form-control-wrap"><input type="text" class="studentCNIC form-control" name="cnic" value="{{$students->cnic}}"></div></td>
                                            </tr>  
                                        </tbody>
                                      </table>
                            </div>
                            
                            <div class="row g-3">
                                    
                                    <h3 style="color:#fff; border-radius:5px;background-color: #2e314a; box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;  padding:10px; margin-top:50px;">STUDENT ADDRESS</h3>
                                    <div class="col-lg-3">
                                    <div class="form-group">
                                      <label for="city" class="form-label">Street Address 1</label>
                                      <div class="form-control-wrap"><input type="text" class="studentStreetAddress1 form-control" name="studentStreetAddress1" id="streetAddress1" value="{{$students->address1}}"></div>
                                    </div>
                                  </div>
    
                                  <div class="col-lg-3">
                                    <div class="form-group">
                                      <label for="city" class="form-label">Street Address 2</label>
                                      <div class="form-control-wrap"><input type="text" class="studentStreetAddress2 form-control" name="studentStreetAddress2" id="streetAddress2" value="{{$students->address2}}"></div>
                                    </div>
                                  </div>
    
    
                                  <div class="col-lg-3">
                                    <div class="form-group">
                                      <label for="city" class="form-label">City</label>
                                      <div class="form-control-wrap"><input type="text" class="studentCity form-control" name="studentCity" id="city" value="{{$students->city}}"></div>
                                    </div>
                                  </div>
                                  <div class="col-lg-3">
                                    <div class="form-group">
                                      <label for="postalcode" class="form-label">Postal Code</label>
                                      <div class="form-control-wrap"><input type="text" class="studentPostalCode form-control" name="studentPostalcode" id="postalcode" value="{{$students->postal_code}}"></div>
                                    </div>
                                  </div>
    
                                  <div class="col-lg-3">
                                    <div class="form-group">
                                      <label for="city" class="form-label">Latitude</label>
                                      <div class="form-control-wrap"><input type="text" class="studentLatitude form-control" name="studentLatitude" id="studentLatitude" value="{{$students->latitude}}"></div>
                                    </div>
                                  </div>
    
                                  <div class="col-lg-3">
                                    <div class="form-group">
                                      <label for="city" class="form-label">Longitude</label>
                                      <div class="form-control-wrap"><input type="text" class="studentLongitude form-control" id="studentLongitude" name="studentLongitude" value="{{$students->longitude}}"></div>
                                    </div>
                                  </div>
                                </div>
                            
                            
                            
                            
                            <div class="row g-3">
                                
                                <div class="card-body">
                                <div class="bio-block">
                                  
                                  <h3 style="color:#fff; border-radius:5px;background-color: #2e314a; box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;  padding:10px; margin-top:50px;">COMMITMENT FEE</h3>
                                  <small>RM 50 payment receipt is required to be uploaded for a new student registration.</small>
                                  <p>
                                    <div class="row">
                                            <div class="col-md-4">
                                              <span class="title">Payment Attachment</span>
                                              <input class="form-control" name="PaymentAttachment" type="file"/>
                                              <small>Supported Extensions: doc,docx,pdf,jpg,jpeg,png <br/> Max Size: 10MB</small>
                                            </div>
                                            <div class="col-md-4">
                                              <span class="title">Fee Payment Date</span>
                                              <input class="form-control" name="PaymentDate" value="{{$tickets->fee_payment_date}}" type="date"/>
                                            </div>
                                            <div class="col-md-4">
                                              <span class="title">Receiving Account</span>
                                                <select id="ReceivingAccountId" name="ReceivingAccountId"  class="js-select" data-search="true" data-sort="false">><option value="44">Cash At Bank - Maybank</option>
                                                  <option value="45">Cash In Hand</option>
                                                  <option value="73">Payment Gateway - BillPlz Sdn Bhd</option>
                                                  <option value="76">Payment Gateway - Ipay88</option>
                                                  <option value="68">Public Bank</option>
                                                </select>

                                            </div>
                                    </div>
                                  </p>
                                  <div class="row g-gs">
                                     
                                  </div>
                                </div>
                              </div>
                                
                            </div>

                             <div class="row g-3">
                                <h3 style="color:#fff; border-radius:5px;background-color: #2e314a; box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;  padding:10px; margin-top:50px;">SUBJECT SUBSCRIBES</h3>
                                
                               
                                      <div class="container pt-4">
                                        
                                          <table style="width:100%">
                                            <thead class="table-dark">
                                              <tr>
                                                <th style="width:350px;" class="text-center">Subject Name</th>
                                                <th class="text-center">Day</th>
                                                <th class="text-center">Time (in 24 Hrs)</th>
                                                <th class="text-center">Subscription Duration Term</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                                
                                                <tr>
                                                    <td class=""><select class="form-control js-select" data-search="true" data-sort="true" name="subject[]">
                                                        <option value="{{$subjects[0]->id}}"> {{$subjects[0]->subject}}</option>@foreach($subjects as $subjectRow)
                                                    <option value="{{$subjectRow->id}}"> {{$subjectRow->subject}}</option>@endforeach<select></td>
                                                    <td class=""><select class="form-control" name="day[]"><option value="{{$subjects[0]->day}}">{{$subjects[0]->day}}</option><option value="Monday">Monday</option><option value="Tuesday">Tuesday</option><option value="Wednesday">Wednesday</option><option value="Thursday">Thursday</option><option value="Friday">Friday</option><option value="Saturday">Saturday</option><option value="Sunday">Sunday</option><select> </td>
                                                    <td class=""><input class="form-control" type="time" value="22:00" value="{{$subjects[0]->time}}" name="time[]"></td>
                                                    <td class=""><select class="form-control" name="subscription[]"><option value="{{$subjects[0]->subscription}}">{{$subjects[0]->subscription}}</option><option value="LongTerm">Long Term</option><option value="shortTerm">Short Term</option><select></td>
                                                </tr>
                                      
                                            </tbody>
                                          </table>
                                      </div>

                             
                             </div>
                             
                             <div class="row g-3">
                                <h3 style="color:#fff; border-radius:5px;background-color: #2e314a; box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;  padding:10px; margin-top:50px;">TICKETS APPLICATIONS</h3>
                                <h3 class="mt-4 mb-0" id="applicationSection">SUBJECTS REQUESTED</h3>
                @if($tickets)
                     <div class="table-responsive">
                          <table class="table table-middle mb-0">
                            <thead class="table-dark table-head-sm">
                              <tr>
                                <th class="tb-col  tb-col-end tb-col-sm">
                                  <span class="overline-title">Ticket ID</span>
                                </th>
                                <th class="tb-col tb-col-end tb-col-sm">
                                  <span class="overline-title">Subject</span>
                                </th>
                                <th class="tb-col tb-col-end  tb-col-sm">
                                  <span class="overline-title">Time</span>
                                </th>
                                <th class="tb-col tb-col-end  tb-col-sm">
                                  <span class="overline-title">Day</span>
                                </th>
                                <th class="tb-col tb-col-end">
                                  <span class="overline-title">status</span>
                                </th>
                                
                               
                              </tr>
                            </thead>
                            <tbody>
                                
                                                                      <tr>
                                        <td class="tb-col tb-col-end tb-col-xxl">
                                          <span class="small">{{$tickets->uid}}</span>
                                        </td>
                                        <td class="tb-col tb-col-end tb-col-xxl">
                                          <span class="small">{{$tickets->subjects}}</span>
                                        </td>
                                        <td class="tb-col tb-col-end tb-col-xxl">
                                          <span class="small">{{$tickets->time}}</span>
                                        </td>
                                        <td class="tb-col tb-col-end tb-col-xxl">
                                          <span class="small">{{$tickets->day}}</span>
                                        </td>
                                      </tr>
                                      
                                                                 </tbody>
                          </table>
                        </div>
                @else
                
                    <p><i>There's no application yet.</i></p>
                @endif
                <h3 class="mt-4 mb-0" id="applicationSection">Ticket Applications</h3>
                @if($tickets)
                     <div class="table-responsive">
                          <table class="table table-middle mb-0">
                            <thead class="table-dark table-head-sm">
                              <tr>
                                <th class="tb-col">
                                  <span class="overline-title">Tutor Name</span>
                                </th>
                                <th class="tb-col">
                                  <span class="overline-title">Status</span>
                                </th>
                                <th class="">
                                  <span class="overline-title">Action</span>
                                </th>
                               
                              </tr>
                            </thead>
                            <tbody>
                                @php
                                    $ticketDetail = DB::table('tutoroffers')->where('ticketID','=',$tickets->id)->get()
                                @endphp
                                @foreach($ticketDetail as $rowTicketOffer)
                                    @php
                                        $ticketStatus = DB::table('job_tickets')->where('id','=',$rowTicketOffer->ticketID)->first()
                                    @endphp
                                      <tr>
                                         <td class="">
                                            <span class="small">
                                              @php
                                                $tutorName = DB::table('tutors')->where('id','=',$rowTicketOffer->tutorID)->first()
                                            @endphp
                                            {{$tutorName->full_name}}
                                            </span>
                                        </td>
                                        <td class="">
                                            <span class="small">
                                              {{$rowTicketOffer->status}}
                                            </span>
                                        </td>
                                        <td>
                                            
                                            @if($rowTicketOffer->status == 'approved')
                                                    <button data-toggle="modal" data-target="#myModal" class="btn btn-warning waves-effect waves-light" style="background-color: #ecdf69 !important;" data-placement="right" title="1 application approval is required for subject: Lancar Mengaji: 5 sesi - ONLINE. Currently 0.<br/><br/>There are still pending application, approve or reject the application.<br/><br/>Student fee record and attachment is required before completing the job.">Complete Job Ticket</button>
<!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Schedule Class</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="card-body">
                        <form method="POST" action="{{route('submitClassSchedules')}}">
                        @csrf    
                        <input type="hidden" name="studentID" value="{{$students->id}}"/>
                        <input type="hidden" name="ticketID" value="{{$tickets->id}}"/>
                        <input type="hidden" name="tutorID" value="{{$tutorName->id}}"/>
                        <div class="row row-details">
                            <div class="col-md-3 details-item">
                                <p class="item-title">StudentId</p>
                                <p><strong>{{$students->uid}}</strong></p>
                            </div>
                            <div class="col-md-3 details-item">
                                <p class="item-title">Fullname</p>
                                <p><strong>{{$students->full_name}}</strong></p>
                            </div>
                        </div>
                        <div class="row row-details">
                            <table class="table table-responsive no-border" style="margin-left:12px;">
                                    <tbody><tr>
                                        <td>Subject: <strong> 
                                            @php
                                                
                                                $getSubjectName = DB::table('products')->where('id','=',$tickets->subjects)->first();
                                            @endphp
                                        {{$getSubjectName->name}}</strong></td>
                                        <td>Subscribed duration : <strong> 0 hr(s)</strong></td>
                                        <td>Assigned duration: <strong>0 hr(s)</strong></td>
                                    </tr>
                            </tbody></table>
                        </div>
                        
                        <div class="row g-3">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                              <label for="firstname" class="form-label">Tutor Name</label>
                                              <div class="form-control-wrap">
                                                  <input type="text" class="form-control" readonly value="{{$tutorName->full_name}}"/>
                                              </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                              <label for="firstname" class="form-label">Date</label>
                                              <div class="form-control-wrap"><input type="date" name="date" class="form-control"></div>
                                            </div>
                                        </div>
                                      <div class="col-lg-6">
                                        <div class="form-group">
                                          <label for="firstname" class="form-label">Subject</label>
                                          <div class="form-control-wrap">
                                              <input type="text" class="form-control" readonly value="{{$getSubjectName->name}}"/>
                                              <input type="hidden" name="subjectID" value="{{$getSubjectName->id}}"/>
                                        </div>
                                      </div>
                                    </div>
                                    
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                              <label for="firstname" class="form-label">Start Time</label>
                                              <div class="form-control-wrap"><input type="time" name="start_time" class="form-control"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                              <label for="firstname" class="form-label">End Time</label>
                                              <div class="form-control-wrap"><input type="time" name="end_time" class="form-control"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                              <label for="firstname" class="form-label">Has Incentive</label>
                                              <div class="form-control-wrap"><input type="checkbox" name="has_incentive" ></div>
                                            </div>
                                        </div>
                    </div>
              </div>

          <!-- Modal footer -->
          <div class="modal-footer">
              <input type="submit" class="btn btn-success" value="Add Class Schedule">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </form>
    </div>
  </div>
</div>
                                           @else
                                                        <a class="btn btn-success" href="{{route('tutoOfferActionApprove',$rowTicketOffer->id)}}">Approve</a>
                                                        <a class="btn btn-danger" href="{{route('tutoOfferActionReject',$rowTicketOffer->id)}}">Reject</a>
                                            @endif
                                        </td>
                                      </tr>
                                @endforeach
                            </tbody>
                          </table>
                        </div>
                @else
                
                    <p><i>There's no application yet.</i></p>
                @endif
                    
                <hr>
                <div class="row row-details pb-5">
                    <div class="col-md-3">
                        <a class="btn btn-light waves-effect waves-light" href="{{route('TicketList')}}">Back</a>
                    </div>
                </div>
            </div>
                              </div>
                              
                                
                              
                            
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
<!-- Add the this google map apis to webpage -->
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key=AIzaSyCZQ_rRma_Na5RITdZyZrVXfoVmCTw2VUM&libraries=places"></script>

<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyCZQ_rRma_Na5RITdZyZrVXfoVmCTw2VUM"></script>
<script>

google.maps.event.addDomListener(window, 'load', initialize);
function initialize() {
var input = document.getElementById('address');
var autocomplete = new google.maps.places.Autocomplete(input);
autocomplete.addListener('place_changed', function () {
var place = autocomplete.getPlace();
// place variable will have all the information you are looking for.

  document.getElementById("latitude").value = place.geometry['location'].lat();
  document.getElementById("longitude").value = place.geometry['location'].lng();
});
}

    var searchInput = 'search_input';
    var dropoff_searchInput = 'dropoff_search_input';
    
    var searchInput_two = 'search_input_two';
    var dropoff_searchInput_two = 'dropoff_search_input_two';
    
    var searchInput_three = 'search_input_three';
    var dropoff_searchInput_three = 'dropoff_search_input_three';

    $(document).ready(function () {
        
    // Get value on button click and show alert
    $("#subscriptionSubmit").click(function(e){
        
        var strEmail = $("#email").val();
        alert(strEmail);
        
    });
            
        var autocomplete;
        autocomplete = new google.maps.places.Autocomplete((document.getElementById(searchInput)), {
            types: ['geocode'],
            componentRestrictions: {
                country: "MY"
            }
        });
        
         dropoff_autocomplete = new google.maps.places.Autocomplete((document.getElementById(dropoff_searchInput)), {
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
        

        
    });
    
    $(document).on('change', '#'+searchInput, function () {
        document.getElementById('latitude_input').value = '';
        document.getElementById('longitude_input').value = '';
    	
        document.getElementById('latitude_view').innerHTML = '';
        document.getElementById('longitude_view').innerHTML = '';
        
        
    });
    
    var wto;
     $(document).on('change', '#'+searchInput_two, function () {
        document.getElementById('latitude_input_two').value = '';
        document.getElementById('longitude_input_two').value = '';
    	
        document.getElementById('latitude_view_two').innerHTML = '';
        document.getElementById('longitude_view_two').innerHTML = '';
        
        
    });
    
    
    
            

    

</script>

          
          
<script>
$(document).ready(function(){
    $("select#student_id").change(function(){
        var selectedCustomer = $(this).children("option:selected").val();
        
        if(selectedCustomer == '000123'){
            $('.customerInfo').show();
            
            
            
            
        }else{
            
            $('.existingStudentInfo').show();
        }
        var userURL = $(this).data('url');
            $.ajax({
                url: "{{ url('/addTicket') }}/" + selectedCustomer,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    
                    console.log(data);
                    
                    
                    $('.customerId').text(data.customer.uid);
                    $('.customerId').val(data.customer.uid);
                    $('.customerFullName').val(data.customer.full_name);
                    $('.customerEmail').val(data.customer.email);
                    $('#customerGender').append($('<option>').val(data.customer.gender).text(data.customer.gender))
                    $('.customerPhone').val(data.customer.phone);
                    $('.customerStreetAddress1').val(data.customer.address1);
                    $('.customerStreetAddress2').val(data.customer.address2);
                    $('.customerNRIC').val(data.customer.nric);
                    $('.customerDOB').val(data.customer.dob);
                    $('.customerCity').val(data.customer.city);
                    $('.customerLatitude').val(data.customer.latitude);
                    $('.customerLongitude').val(data.customer.longitude);
                    $('.customerPostalCode').val(data.customer.postal_code);
                    
                    
                    
                    $('.studentRegisterDate').text(data.student.register_date);
                    $('.studentId').text(data.student.uid);
                    $('.studentFullName').val(data.student.full_name);
                    $('.studentPhone').val(data.student.phone);
                    $('.studentEmail').val(data.student.email);
                    $('.studentAge').val(data.student.age);
                    $('.studentStreetAddress1').val(data.student.address1);
                    $('.studentStreetAddress2').val(data.student.address2);
                    $('.studentCNIC').val(data.student.cnic);
                    $('.studentDOB').val(data.student.dob);
                    $('.studentCity').val(data.student.city);
                    $('.studentLatitude').val(data.student.latitude);
                    $('.studentLongitude').val(data.student.longitude);
                    $('.studentPostalCode').val(data.student.postal_code);
                    
                    
                    
                    
                    var json = JSON.stringify(data.subjects);
                    
                        
                            $('#subject1').html('1.<br/> <strong>Subject :</strong><span class="text">'+data.subjects[0]['subject']+'</span></br>  <strong>Day :</strong> <span class="text">'+data.subjects[0]['day']+'</span></br> <strong>Time :</strong> <span class="text">'+data.subjects[0]['time']+'</span></br> <strong>Tutor :</strong>'+' <u>'+data.subjects[0]['newstatus']+' </u>');
                            $('#subject2').html('2.<br/> <strong>Subject :</strong><span class="text">'+data.subjects[1]['subject']+'</span></br> <strong>Day :</strong> <span class="text">'+data.subjects[1]['day']+'</span></br> <strong>Time :</strong> <span class="text">'+data.subjects[1]['time']+'</span></br> <strong>Tutor :</strong>'+' <u>'+data.subjects[1]['newstatus']+' </u>');
                            $('#subject3').html('3.<br/> <strong>Subject :</strong><span class="text">'+data.subjects[2]['subject']+'</span></br> <strong>Day :</strong> <span class="text">'+data.subjects[2]['day']+'</span></br> <strong>Time :</strong> <span class="text">'+data.subjects[2]['time']+'</span></br> <strong>Tutor :</strong>'+' <u>'+data.subjects[2]['newstatus']+' </u>');
                            $('#subject4').html('4.<br/> <strong>Subject :</strong><span class="text">'+data.subjects[2]['subject']+'</span></br> <strong>Day :</strong> <span class="text">'+data.subjects[3]['day']+'</span></br> <strong>Time :</strong> <span class="text">'+data.subjects[3]['time']+'</span></br> <strong>Tutor :</strong>'+' <u>'+data.subjects[3]['newstatus']+' </u>');
                            
                            $('#subject12').html('1.<br/> <strong>Subject :</strong><span class="text">'+data.subjects[0]['subject']+'</span></br>  <strong>Day :</strong> <span class="text">'+data.subjects[0]['day']+'</span></br> <strong>Time :</strong> <span class="text">'+data.subjects[0]['time']+'</span></br> <strong>Tutor :</strong>'+' <u>'+data.subjects[0]['newstatus']+'  </u>');
                            $('#subject22').html('2.<br/> <strong>Subject :</strong><span class="text">'+data.subjects[1]['subject']+'</span></br> <strong>Day :</strong> <span class="text">'+data.subjects[1]['day']+'</span></br> <strong>Time :</strong> <span class="text">'+data.subjects[1]['time']+'</span></br> <strong>Tutor :</strong>'+' <u>'+data.subjects[1]['newstatus']+'  </u>');
                            $('#subject32').html('3.<br/> <strong>Subject :</strong><span class="text">'+data.subjects[2]['subject']+'</span></br> <strong>Day :</strong> <span class="text">'+data.subjects[2]['day']+'</span></br> <strong>Time :</strong> <span class="text">'+data.subjects[2]['time']+'</span></br> <strong>Tutor :</strong>'+' <u>'+data.subjects[2]['newstatus']+'  </u>');
                            $('#subject33').html('4.<br/> <strong>Subject :</strong><span class="text">'+data.subjects[2]['subject']+'</span></br> <strong>Day :</strong> <span class="text">'+data.subjects[3]['day']+'</span></br> <strong>Time :</strong> <span class="text">'+data.subjects[3]['time']+'</span></br> <strong>Tutor :</strong>'+' <u>'+data.subjects[3]['newstatus']+'  </u>');
                        
                        
                        
                    

                    // $('#userShowModal').modal('show');
                    // $('#user-id').text(data.id);
                    // $('#user-name').text(data.name);
                    // $('#user-email').text(data.email);
                }
            });
    });
    
    $("select#parent_id").change(function(){
        var selectedParent = $(this).children("option:selected").val();
        
        if(selectedParent == 'newParent'){
            
            
        }else{
            
            
        }
        var userURL = $(this).data('url');
            $.ajax({
                url: "{{ url('/addTicketAjaxCallParrent') }}/" + selectedParent,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    
                    console.log(data);
                    
                    
                    $('.customerId').text(data.customer.uid);
                    $('.customerId').val(data.customer.uid);
                    $('.customerFullName').val(data.customer.full_name);
                    $('.customerEmail').val(data.customer.email);
                    $('#customerGender').append($('<option>').val(data.customer.gender).text(data.customer.gender))
                    $('.customerPhone').val(data.customer.phone);
                    $('.customerStreetAddress1').val(data.customer.address1);
                    $('.customerStreetAddress2').val(data.customer.address2);
                    $('.customerNRIC').val(data.customer.nric);
                    $('.customerDOB').val(data.customer.dob);
                    $('.customerCity').val(data.customer.city);
                    $('.customerLatitude').val(data.customer.latitude);
                    $('.customerLongitude').val(data.customer.longitude);
                    $('.customerPostalCode').val(data.customer.postal_code);
                    
                    
                   
                    
                }
            });
    });
    
});
</script>

@endsection



