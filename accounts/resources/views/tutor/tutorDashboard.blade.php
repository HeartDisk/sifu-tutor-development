@extends('layouts.main')
@section('content')
<div class="nk-content sifu-dashboard-page">
  <div class="fluid-container">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head">
                <div class="nk-block-head-between flex-wrap gap g-2">
                   <div class="nk-block-head-content">
                      <h2 class="nk-block-title">
                         Tutor Dashboard
                      </h2>
                      <nav>
                         <ol class="breadcrumb breadcrumb-arrow mb-0">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Tutor List</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tutor Dashboard</li>
                         </ol>
                      </nav>
                   </div>
                   <div class="nk-block-head-content">
                      <ul class="d-flex">
                         <li><a href="{{route('editTutor',$tutor->id)}}" class="btn btn-md d-md-none btn-primary"><span>Edit Profile</span></a></li>
                         <li><a href="{{route('editTutor',$tutor->id)}}" class="btn btn-primary d-none d-md-inline-flex"><span>Edit Tutor Profile</span></a></li>
                      </ul>
                   </div>
                </div>
            </div>

            <div class="nk-block-head-between flex-wrap gap g-2 align-items-start">
               <div class="col-4">
                  <div class="card">
                     <div class="card-body">
                        <div class="dcard-status">
                           <div class="col-md-8">
                              @if($tutor->tutorImage==null)
                              <img src="{{asset('template/studentImage.png')}}">
                              @else
                              <img src="{{url('/public/tutorImage')."/".$tutor->tutorImage}}">
                              @endif
                           </div>
                           <div class="col-md-4">
                              @if($tutor->status == 'verified' || $tutor->status == 'active')
                              <div class="dashtooltip">
                                 <img src="{{asset('template/verify.png')}}" alt="Sifututor" class="icon">
                                 <span class="dashtooltiptext">This Tutor's identity has been verified through a government ID check and a visual verification.</span>
                              </div>
                              @endif
                           </div>
                        </div>
                        <ul class="list-group list-group-borderless small">
                           <li class="list-group-item">
                              <span class="image"><img src="{{asset('public/id.png')}}"></span>
                              <span class="title">Account ID:</span>
                              <span class="text">{{$tutor->tutor_id}}</span>
                           </li>
                           <li class="list-group-item">
                              <span class="image"><img src="{{asset('public/customer-ui.png')}}"></span>
                              <span class="title">Full Name:</span>
                              <span class="text">{{$tutor->full_name}}</span>
                           </li>
                           <li class="list-group-item">
                              <span class="image"><img src="{{asset('public/email.png')}}"></span>
                              <span class="title">Email:</span>
                              <span class="text">{{$tutor->email}}</span>
                           </li>
                           <li class="list-group-item">
                              <span class="image"><img src="{{asset('public/phone.png')}}"></span>
                              <span class="title">Phone:</span>
                              <span class="text">{{$tutor->phoneNumber}}</span>
                           </li>
                           <li class="list-group-item">
                              <span class="image"><img src="{{asset('public/calendar.png')}}"></span>
                              <span class="title">Joining Date:</span>
                              <span class="text">{{$tutor->start_date}}</span>
                           </li>
                           <li class="list-group-item">
                              <span class="image"><img src="{{asset('public/location.png')}}"></span>
                              <span class="title">Address:</span>
                              @if($tutor->street_address1 != null && $tutor->city != null)
                                 <span class="text">{{$tutor->street_address1}}, {{$tutor->city}}</span>
                              @else
                                 <span class="text">Address not found</span>
                              @endif
                           </li>
                        </ul>
                     </div>
                  </div>

                  <div class="card mt-2 dashboard-subjects-view">
                     <div class="card-body">
                        <div class="bio-block">
                            <h6>Subjects:</h6>
                            <ul class="d-flex flex-wrap gap gx-1">
                                 @foreach($subjects as $rowSubject)
                                 <li><a href="javascript:void(0)" class="badge text-bg-secondary-soft">{{$rowSubject->subject_name}}</a></li>
                                 @endforeach
                            </ul>
                        </div>
                     </div>
                  </div>
               </div>

               <div class="col-8 tabs-7">
                  <ul class="tabs">
                      <li class="active"><a href="#tab25">Overview</a></li>
                      <li><a href="#tab26">All Tickets</a></li>
                      <li><a href="#tab27">Scheduled Classes</a></li>
                      <li><a href="#tab28">Assigned Classes</a></li>
                  </ul>
                  <section class="tab_content_wrapper">
                     <article class="tab_content" id="tab25">
                        <div class="card">
                           <div class="card-body">
                              <div class="bio-block">
                                 <h6>All Scheduled Classes and TimeClock</h6>
                                 @if($tutor->status == 'verified')
                                 @if($myApplicedTicektsOne)
                                 <div class="table-responsive">
                                    <table class="table sifu-view-cdashtable">
                                       <thead>
                                       <tr>
                                           <th>Ticket ID</th>
                                           <th>Subject</th>
                                           <th>Time</th>
                                           <th>Day</th>
                                           <th>status</th>
                                           <th>Action</th>
                                       </tr>
                                       </thead>
                                       <tbody>
                                       @foreach($myApplicedTicektsOne as $rowTicket)
                                       <tr class="sifu-view-cdtr">
                                            <td>{{$rowTicket->uid}}</td>
                                            <td>{{$rowTicket->subject_name}}</td>
                                            <td>{{$rowTicket->time}}</td>
                                            <td>{{$rowTicket->day}}</td>
                                            <td>
                                                @if($rowTicket->tutorID)
                                                @php
                                                   $getTutor = DB::table('tutors')->where('id','=',$rowTicket->tutorID)->first();
                                                @endphp
                                                @if($rowTicket->ticket_status)
                                                   <span class="small">Assigned To (<a href="{{route('tutorLogin',$rowTicket->tutorID)}}"> {{$getTutor->full_name}} </a>)</span>
                                                @else
                                                   <span class="small">Applied By (<strong>{{$getTutor->full_name}}</strong>) </span>
                                                @endif
                                                @else
                                                    <span class="small">Pending </span>
                                                @endif
                                             </td>
                                             @if($rowTicket->tutorID != NULL)
                                             <td><span class="small"><a class="btn btn-sm btn-default" href="">{{$rowTicket->ticket_status}}</a></span></td>
                                             @else
                                             <td><span class="small"><a class="btn btn-sm btn-success" href="{{ url('tutorOffer/'.$tutorID.'/'.$rowTicket->id.'/'.$rowTicket->subject_id)}}">Offer Pending</a></span></td>
                                             @endif
                                       </tr>
                                       <tr>
                                          <td colspan="6">
                                             <div class="view-tabdetails">
                                                <div class="details-item">
                                                   <p class="item-title">Name</p>
                                                   <p><strong>{{$rowTicket->studentName}}</strong></p>
                                                </div>
                                                <div class="details-item">
                                                   <p class="item-title">Age</p>
                                                   <p><strong>{{$rowTicket->studentAge}}</strong></p>
                                                </div>
                                                <div class="details-item">
                                                   <p class="item-title">Gender</p>
                                                   <p><strong>{{$rowTicket->studentGender}}</strong></p>
                                                </div>
                                                <div class="details-item">
                                                   <p class="item-title">Special Request</p>
                                                   <p><strong>{{$rowTicket->specialRequest}}</strong></p>
                                                </div>
                                             </div>
                                          </td>
                                       </tr>
                                       @endforeach
                                       </tbody>
                                    </table>
                                 </div>
                                 @else<p><i>There's no application yet.</i></p>@endif
                                 @else
                                    <p><i>Only <strong>Verified Account</strong> can access this area <br/> You dont have access please activate your account! <br/> for activation you need to pay 50RM</i></p>
                                    <p>For Account verification <a href="{{route('editTutor',$tutor->id)}}"> Click Here </a></p>
                                 @endif
                              </div>
                           </div>
                        </div>
                     </article>

                     <article class="tab_content" id="tab26">
                          <div class="card">   
                              <div class="card-body">
                                 <div class="bio-block">
                                    <h6>All Active Tickets</h6>
                                    @if($tutor->status == 'verified')
                                       @if($tickets)
                                          <div class="table-responsive">
                                             <table class="table sifu-view-cdashtable">
                                                <thead>
                                                   <tr>
                                                       <th>Ticket ID</th>
                                                       <th>Subject</th>
                                                       <th>Time</th>
                                                       <th>Day</th>
                                                       <th>status</th>
                                                       <th>Action</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                   @foreach($tickets as $rowTicket)
                                                       @php
                                                           $ticketStatus = DB::table('tutoroffers')->where('ticketID','=',$rowTicket->ticket_id)->where('tutorID','=',$tutor->id)->first();
                                                       @endphp
                                                       <tr class="sifu-view-cdtr">
                                                           <td>{{$rowTicket->id}} - {{$rowTicket->uid}}</td>
                                                           <td>{{$rowTicket->subject_name}}</td>
                                                           <td>{{$rowTicket->time}}</td>
                                                           <td>{{$rowTicket->day}}</td>
                                                           <td>{{$rowTicket->status}}</td>
                                                           <td>
                                                               <button type="button" class="dtable-status-active" data-bs-toggle="modal" data-bs-target="#exampleModal{{$rowTicket->id}}">View</button>
                                                               <div class="modal fade dtable-modal" id="exampleModal{{$rowTicket->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                   <div class="modal-dialog">
                                                                       <div class="modal-content">
                                                                           <div class="modal-header">
                                                                              <h5 class="modal-title" id="exampleModalLabel">Ticket: {{$rowTicket->uid}} - {{$rowTicket->subject_name}}</h5>
                                                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                 <span aria-hidden="true">×</span>
                                                                              </button>
                                                                           </div>
                                                                           <div class="modal-body">
                                                                              @php
                                                                                $studentDetail = DB::table('students')->where('id','=',$rowTicket->student_id)->first();
                                                                              @endphp
                                                                              <div class="card-aside">
                                                                                 <div class="card-body">
                                                                                    <div class="bio-block">
                                                                                       <h6>Student Details</h6>
                                                                                       <ul class="list-group list-group-borderless small">
                                                                                          <li class="list-group-item">
                                                                                             <span class="title">Student ID:</span>
                                                                                             <span class="text">{{$studentDetail->student_id}}</span>
                                                                                          </li>
                                                                                          <li class="list-group-item">
                                                                                             <span class="title">Full Name:</span>
                                                                                             <span class="text">{{$studentDetail->full_name}}</span>
                                                                                          </li>
                                                                                          <li class="list-group-item">
                                                                                             <span class="title">Email:</span>
                                                                                             <span class="text">{{$studentDetail->email}}</span>
                                                                                          </li>
                                                                                          <li class="list-group-item">
                                                                                             <span class="title">Gender:</span>
                                                                                             <span class="text">{{$studentDetail->gender}}</span>
                                                                                          </li>
                                                                                          <li  class="list-group-item">
                                                                                             <span class="title">Address</span>
                                                                                             <span class="text">{{$studentDetail->address1}} {{$studentDetail->address2}}</span>
                                                                                          </li>
                                                                                          <li class="list-group-item">
                                                                                             <span class="title">City</span>
                                                                                             <span class="text">{{$studentDetail->city}}</span>
                                                                                         </li>
                                                                                       </ul>
                                                                                    </div>
                                                                                 </div>
                                                                              </div>
                                                                           </div>
                                                                       </div>
                                                                   </div>
                                                               </div>
                                                               @if($rowTicket->ticket_status == 'pending')
                                                               @if($ticketStatus)
                                                               @if($ticketStatus->ticketID == $rowTicket->id)
                                                                  <span class="btn btn-sm btn-warning" >Offer Sent</span>
                                                                  @else
                                                                     <span class="small"><a class="btn btn-sm btn-primary" href="{{ url('tutorOffer/'.$rowTicket->subject_id.'/'.$tutorID.'/'.$rowTicket->ticket_id)}}">Send Offer</a></span>
                                                                  @endif
                                                                  @else
                                                                     <span class="small"><a class="btn btn-sm btn-primary" href="{{ url('tutorOffer/'.$rowTicket->subject_id.'/'.$tutorID.'/'.$rowTicket->ticket_id)}}">Send Offer</a></span>
                                                                  @endif
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
                                       @else
                                          <p><i>Only <strong>Verified Account</strong> can access this area <br/> You dont have access please activate your account! <br/> for activation you need to pay 50RM</i></p>
                                          <p>For Account verification <a href="{{route('makeTutorPayment',$tutor->id)}}"> Click Here </a></p>
                                       @endif
                                 </div>
                             </div>
                           </div>
                     </article>

                     <article class="tab_content" id="tab27">
                        <div class="card">
                           <div class="card-body">
                              <div class="bio-block">
                                 <h6>Scheduled Classes</h6>
                                 @if($tutor->status == 'verified')
                                 @if($myApplicedTicekts)

                                 <div class="table-responsive">
                                      <table class="table sifu-view-cdashtable">
                                          <thead>
                                          <tr>
                                              <th>Ticket ID</th>
                                              <th>Subject</th>
                                              <th>Time</th>
                                              <th>Day</th>
                                              <th>status</th>
                                              <!--<th>Action</th>-->
                                          </tr>
                                          </thead>
                                          <tbody>
                                          @foreach($myApplicedTicekts as $rowTicket)
                                             <tr class="sifu-view-cdtr">
                                                <td>{{$rowTicket->id}} - {{$rowTicket->uid}}</td>
                                                <td>{{$rowTicket->subject_name}}</td>
                                                <td>{{$rowTicket->time}}</td>
                                                <td>{{$rowTicket->day}}</td>
                                                <td>
                                                   @if($rowTicket->tutorID)
                                                   @php
                                                      $getTutor = DB::table('tutors')->where('id','=',$rowTicket->tutorID)->first();
                                                   @endphp
                                                   @if($rowTicket->ticket_status)
                                                      <span class="small">Assigned To (<strong><a href="{{route('tutorLogin',$rowTicket->tutorID)}}"> {{$getTutor->full_name}} </a></strong>) </span>
                                                   @else
                                                      <span class="small">Applied By (<strong>{{$getTutor->full_name}}</strong>) </span>
                                                   @endif
                                                   @else
                                                      <span class="small">Pending </span>
                                                   @endif
                                                </td>
                                                <td>
                                                   <!--<a data-toggle="modal" data-target="#exampleModal" class="dtable-status-active">Add Schedule</a>-->
                                                   <!-- Modal -->
                                                   <div class="modal fade dtable-modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                       <div class="modal-dialog modal-xl" role="document">
                                                           <div class="modal-content">
                                                               <div class="modal-header">
                                                                   <h5 class="modal-title" id="exampleModalLabel">Add Schedule for Subject {{$rowTicket->subject_name}} </h5>
                                                                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                       <span aria-hidden="true">&times;</span>
                                                                   </button>
                                                               </div>
                                                               <div class="modal-body">
                                                                   <form method="POST" action="{{route('submitClassSchedules')}}">
                                                                       @csrf
                                                                       <input type="hidden" name="studentID" value="{{$rowTicket->studentID}}"/>
                                                                       <input type="hidden" name="ticketID" value="{{$rowTicket->job_ticketsID}}"/>
                                                                       
                                                                        <div class="row view-sindetails">
                                                                           <div class="col-md-6 details-item">
                                                                               <p class="item-title">StudentId</p>
                                                                               <p><strong>{{$rowTicket->studentID}}</strong></p>
                                                                           </div>
                                                                           <div class="col-md-6 details-item">
                                                                               <p class="item-title">Fullname</p>
                                                                               <p><strong>{{$rowTicket->studentName}}</strong></p>
                                                                           </div>
                                                                        </div>
                                                                       
                                                                        <table class="table sifu-view-cdashtable">
                                                                           <tbody>
                                                                              <tr>
                                                                                <td>Subject: <strong>
                                                                                       @php
                                                                                          $getSubject = DB::table('student_subjects')->where('ticket_id','=',$rowTicket->id)->first();
                                                                                          $getSubjectName = DB::table('products')->where('id','=',$getSubject->subject)->first();
                                                                                       @endphp
                                                                                       {{$getSubjectName->name}}</strong></td>
                                                                                <td>Subscribed duration : <strong> 1 hr(s)</strong></td>
                                                                                <td>Assigned duration: <strong>2 hr(s)</strong></td>
                                                                              </tr>
                                                                           </tbody>
                                                                         </table>

                                                                       <div class="row g-3">
                                                                           <div class="col-lg-4">
                                                                               <div class="form-group">
                                                                                   <label for="firstname" class="form-label">Tutor Date</label>
                                                                                   <div class="form-control-wrap">
                                                                                       <select id="student_id"  required="required" name="tutor_id" class="js-select" data-search="true" data-sort="false">
                                                                                           <option selected value=""> - - - Select Tutor - - - </option>
                                                                                           @foreach($tutors as $rowTutor)
                                                                                               <option value="{{$rowTutor->id}}">{{$rowTutor->full_name}}</option>
                                                                                           @endforeach
                                                                                       </select>
                                                                                   </div>
                                                                               </div>
                                                                           </div>
                                                                           <div class="col-lg-4">
                                                                               <div class="form-group">
                                                                                   <label for="firstname" class="form-label">Date</label>
                                                                                   <div class="form-control-wrap"><input type="date" name="date" class="form-control"></div>
                                                                               </div>
                                                                           </div>
                                                                           <div class="col-lg-4">
                                                                               <div class="form-group">
                                                                                   <label for="firstname" class="form-label">Subject</label>
                                                                                   <div class="form-control-wrap">
                                                                                       <select id="student_id"  required="required" name="subject_id" class="js-select" data-search="true" data-sort="false">
                                                                                           <option selected value=""> - - - Select Subject - - - </option>
                                                                                           @foreach($allSubjects as $rowSubject)
                                                                                               <option value="{{$rowSubject->id}}">{{$rowSubject->name}}</option>
                                                                                           @endforeach
                                                                                       </select>
                                                                                   </div>
                                                                               </div>
                                                                           </div>

                                                                           <div class="col-lg-4">
                                                                               <div class="form-group">
                                                                                   <label for="firstname" class="form-label">Start Time</label>
                                                                                   <div class="form-control-wrap"><input type="time" name="start_time" class="form-control"></div>
                                                                               </div>
                                                                           </div>

                                                                           <div class="col-lg-4">
                                                                               <div class="form-group">
                                                                                   <label for="firstname" class="form-label">End Time</label>
                                                                                   <div class="form-control-wrap"><input type="time" name="end_time" class="form-control"></div>
                                                                               </div>
                                                                           </div>

                                                                           <div class="col-lg-4">
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
                                                               </div>
                                                               </form>
                                                           </div>
                                                       </div>
                                                   </div>
                                                </td>
                                              </tr>

                                              <tr>
                                                  <td colspan="6">
                                                      <div class="view-tabdetails">
                                                         <div class="details-item">
                                                            <p class="item-title">Student Name</p>
                                                            <p><strong>{{$rowTicket->studentName}}</strong></p>
                                                         </div>
                                                         <div class="details-item">
                                                            <p class="item-title">Age</p>
                                                            <p><strong>{{$rowTicket->studentAge}}</strong></p>
                                                         </div>
                                                         <div class="details-item">
                                                            <p class="item-title">Gender</p>
                                                            <p><strong>{{$rowTicket->studentGender}}</strong></p>
                                                         </div>
                                                         <div class="details-item">
                                                            <p class="item-title">Special Request</p>
                                                            <p><strong>{{$rowTicket->specialRequest}}</strong></p>
                                                         </div>
                                                      </div>
                                                  </td>
                                              </tr>
                                              <tr>
                                                  <td colspan="6">
                                                      <h6>Schedule for Class: <span>{{$rowTicket->subject_name}}</span></h6>
                                                      <div class="table-responsive">
                                                         <table class="table sifu-view-cdashtable">
                                                            <thead>
                                                               <tr>
                                                                  <th>Class</th>
                                                                  <th>Date</th>
                                                                  <th>Start Time</th>
                                                                  <th>End Time</th>
                                                                  <!--<th>CheckIN</th>-->
                                                                  <!--<th>CheckOUT</th>-->
                                                                  <th>IS PAID</th>
                                                                  <!--<th>Action</th>-->
                                                               </tr>
                                                            </thead>
                                                            <tbody>
                                                               @php
                                                                  $classNumber = 1;
                                                                     $myApplicedTicektsTwo = DB::table('class_schedules')->where('class_schedules.ticketID','=',$rowTicket->id)->get();
                                                               @endphp
                                                               @foreach($myApplicedTicektsTwo as $rowTicketTwo)
                                                               <form action="{{route('submitAttendance')}}" method="POST">
                                                                  @csrf
                                                                  <input type="hidden" name="csid" value="{{$rowTicketTwo->id}}"/>
                                                                  <input type="hidden" name="subject" value="{{$rowTicketTwo->subjectID}}"/>
                                                                  <input type="hidden" name="tutor_id" value="{{$rowTicketTwo->tutorID}}"/>
                                                                  <input type="hidden" name="ticket_id" value="{{$rowTicketTwo->ticketID}}"/>
                                                                  <tr>
                                                                     <td>{{$classNumber++}}</td>
                                                                     <td>
                                                                        @php
                                                                            echo $newDate = date("d-m-Y", strtotime($rowTicketTwo->date));
                                                                        @endphp
                                                                     </td>
                                                                     <td>{{$rowTicketTwo->startTime}}</td>
                                                                     <td>{{$rowTicketTwo->endTime}}</td>
                                                                     <!--<td><input id="" type="time" name="checkIN" class="checkIN form-control"/></td>-->
                                                                     <!--<td><input id="" type="time" name="checkOUT" class="checkOUT form-control"/></td>-->
                                                                     <td>
                                                                        @if($rowTicketTwo->is_paid == 0)
                                                                             <span >NOT PAID</span>
                                                                        @else
                                                                             PAID
                                                                        @endif
                                                                     </td>
                                                                     <!--<td>-->
                                                                     <!--   <input type="submit" name="submit" class="btn btn-success form-control"/>-->
                                                                     <!--</td>-->
                                                                  </tr>
                                                               </form>
                                                               @endforeach
                                                            </tbody>
                                                          </table>
                                                      </div>
                                                  </td>
                                              </tr>
                                          @endforeach
                                          </tbody>
                                      </table>
                                 </div>
                                 @else
                                    <p><i>There's no application yet.</i></p>
                                 @endif
                                 @else
                                    <p><i>Only <strong>Verified Account</strong> can access this area <br/> You dont have access please activate your account! <br/> for activation you need to pay 50RM</i></p>
                                    <p>For Account verification <a href="{{route('makeTutorPayment',$tutor->id)}}"> Click Here </a></p>
                                 @endif
                              </div>
                           </div>
                        </div>
                     </article>

                     <article class="tab_content" id="tab28">
                        <div class="card">
                           <div class="card-body">
                              <div class="bio-block">
                                 <h6>Assigned Classes</h6>
                                 @if($tutor->status == 'verified')
                                 @if($myApplicedTicekts)
                                     <div class="table-responsive">
                                         <table class="table sifu-view-cdashtable">
                                             <thead>
                                             <tr>
                                                <th>Ticket ID</th>
                                                <th>Subject</th>
                                                <th>Time</th>
                                                <th>Day</th>
                                                <th>status</th>
                                                <th>Action</th>
                                             </tr>
                                             </thead>
                                             <tbody>
                                             @foreach($myApplicedTicekts as $rowTicket)
                                                <tr class="sifu-view-cdtr">
                                                   <td>{{$rowTicket->id}} - {{$rowTicket->uid}}</td>
                                                   <td>{{$rowTicket->subject_name}}</td>
                                                   <td>{{$rowTicket->time}}</td>
                                                   <td>{{$rowTicket->day}}</td>
                                                   <td>
                                                      @if($rowTicket->tutorID)
                                                      @php
                                                         $getTutor = DB::table('tutors')->where('id','=',$rowTicket->tutorID)->first();
                                                      @endphp
                                                      @if($rowTicket->ticket_status)
                                                         <span class="small">Assigned To (<strong><a href="{{route('tutorLogin',$rowTicket->tutorID)}}"> {{$getTutor->full_name}} </a></strong>) </span>
                                                      @else
                                                         <span class="small">Applied By (<strong>{{$getTutor->full_name}}</strong>) </span>
                                                      @endif
                                                      @else
                                                         <span class="small">Pending </span>
                                                      @endif
                                                   </td>
                                                      @if($rowTicket->tutorID != NULL)
                                                         <td><a class="btn btn-sm btn-default" href="">{{$rowTicket->ticket_status}}</a></td>
                                                      @else
                                                         <td><a class="btn btn-sm btn-success" href="{{ url('tutorOffer/'.$tutorID.'/'.$rowTicket->id.'/'.$rowTicket->subject_id)}}">Offer Pending</a></td>
                                                      @endif
                                                </tr>

                                                <tr>
                                                   <td colspan="6">
                                                      <div class="view-tabdetails">
                                                         <div class="details-item">
                                                            <p class="item-title">Student Name</p>
                                                            <p><strong>{{$rowTicket->studentName}}</strong></p>
                                                         </div>
                                                         <div class="details-item">
                                                            <p class="item-title">Email</p>
                                                            <p><strong>{{$rowTicket->studentEmail}}</strong></p>
                                                         </div>
                                                         <div class="details-item">
                                                            <p class="item-title">Age</p>
                                                            <p><strong>{{$rowTicket->studentAge}}</strong></p>
                                                         </div>
                                                         <div class="details-item">
                                                            <p class="item-title">Gender</p>
                                                            <p><strong>{{$rowTicket->studentGender}}</strong></p>
                                                         </div>
                                                      </div>
                                                   </td>
                                                </tr>
                                                <tr>
                                                   <td colspan="6">
                                                      <h6>ASSIGNED for Class: <span>{{$rowTicket->subject_name}}</span></h6>
                                                      <div class="table-responsive">
                                                          <table class="table sifu-view-cdashtable">
                                                               <thead>
                                                               <tr>
                                                                  <th>Class</th>
                                                                  <th>Start Time</th>
                                                                  <th>End Time</th>
                                                                  <!--<th>CheckIN</th>-->
                                                                  <!--<th>CheckOUT</th>-->
                                                                  <th>IS PAID</th>
                                                                  <!--<th>Action</th>-->
                                                               </tr>
                                                               </thead>
                                                               <tbody>
                                                                  @php
                                                                     $classNumber = 1;
                                                                         $myApplicedTicektsTwo = DB::table('class_schedules')->where('class_schedules.ticketID','=',$rowTicket->id)->get();
                                                                  @endphp
                                                                  @foreach($myApplicedTicektsTwo as $rowTicketTwo)
                                                                  <form action="{{route('submitAttendance')}}" method="POST">
                                                                     @csrf
                                                                     <input type="hidden" name="csid" value="{{$rowTicketTwo->id}}"/>
                                                                     <input type="hidden" name="subject" value="{{$rowTicketTwo->subjectID}}"/>
                                                                     <input type="hidden" name="tutor_id" value="{{$rowTicketTwo->tutorID}}"/>
                                                                     <input type="hidden" name="ticket_id" value="{{$rowTicketTwo->ticketID}}"/>
                                                                     <tr>
                                                                        <td>{{$classNumber++}}</td>
                                                                        <td>{{$rowTicketTwo->startTime}}</td>
                                                                        <td>{{$rowTicketTwo->endTime}}</td>
                                                                        <!--<td><input id="" type="time" name="checkIN" class="checkIN form-control"/></td>-->
                                                                        <!--<td><input id="" type="time" name="checkOUT" class="checkOUT form-control"/></td>-->
                                                                        <td>
                                                                        @if($rowTicketTwo->is_paid == 0)
                                                                           <span>NOT PAID</span>
                                                                        @else
                                                                           PAID
                                                                        @endif
                                                                        </td>
                                                                        <!--<td class="tb-col tb-col-start tb-col-xxl">-->
                                                                        <!--   <input type="submit" name="submit" class="btn btn-success form-control"/>-->
                                                                        <!--</td>-->
                                                                     </tr>
                                                                  </form>
                                                                  @endforeach
                                                               </tbody>
                                                            </table>
                                                         </div>
                                                      </td>
                                                   </tr>
                                                @endforeach
                                                </tbody>
                                         </table>
                                     </div>
                                 @else
                                     <p><i>There's no application yet.</i></p>
                                 @endif
                                 @else
                                 <p><i>Only <strong>Verified Account</strong> can access this area <br/> You dont have access please activate your account! <br/> for activation you need to pay 50RM</i></p>
                                 <p>For Account verification <a href="{{route('makeTutorPayment',$tutor->id)}}"> Click Here </a></p>
                                 @endif
                              </div>
                           </div>
                        </div>
                     </article>

                  </section>
               </div>
            </div>
         </div>
      </div>
  </div>
</div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="{{asset('public/css/jQueryTab.js')}}"></script>
    <script type="text/javascript">
        $('.tabs-7').jQueryTab({
            initialTab: 1,
            tabInTransition: 'fadeIn',
            tabOutTransition: 'fadeOut',
            cookieName: 'active-tab-7'

        });
    </script>
    <script>
        $(document).ready(function(){
            $("select.attendance").change(function(){
                var selectedattendance = $(this).children("option:selected").val();
                var subject = $(this).find(':selected').data('subject');
                var tutor_id = $(this).find(':selected').data('tutor_id');
                var ticket_id = $(this).find(':selected').data('ticket_id');
                if(selectedattendance){
                    $.ajax({
                        url: "{{url('getAttendance')}}/"+selectedattendance+"/"+subject+"/"+tutor_id+"/"+ticket_id,
                        type: "get",
                        success: function(response) {
                            console.log(response);
                        },
                        error: function(xhr) {
                        }
                    });
                }
            });

            $("select.checkIN").change(function(){
                var selectedcheckIN = $(this).children("option:selected").val();
                var id = $(this).find(':selected').data('id');
                var subject = $(this).find(':selected').data('subject');
                var tutor_id = $(this).find(':selected').data('tutor_id');
                var ticket_id = $(this).find(':selected').data('ticket_id');
                if(selectedcheckIN){
                    $.ajax({
                        url: "{{url('getCheckINN')}}/"+selectedcheckIN+"/"+subject+"/"+tutor_id+"/"+ticket_id,
                        type: "get",
                        success: function(response) {
                            console.log(response);
                        },
                        error: function(xhr) {
                        }
                    });
                }
            });
            $("select.checkOUT").change(function(){
                var selectedcheckOUT = $(this).children("option:selected").val();
                var subject = $(this).find(':selected').data('subject');
                var tutor_id = $(this).find(':selected').data('tutor_id');
                var ticket_id = $(this).find(':selected').data('ticket_id');
                if(selectedcheckOUT){
                    $.ajax({
                        url: "{{url('getCheckOUT')}}/"+selectedcheckOUT+"/"+subject+"/"+tutor_id+"/"+ticket_id,
                        type: "get",
                        success: function(response) {
                            console.log(response);
                        },
                        error: function(xhr) {
                        }
                    });
                }
            });
        });
    </script>

@endsection
