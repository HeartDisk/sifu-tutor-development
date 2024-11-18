@extends('layouts.main')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  
<br/><br/>
          <div class="nk-content">
            <div class="fluid-container">
              <div class="nk-content-inner">
                <div class="nk-content-body">
                  <div class="nk-block-head">
                    <div class="nk-block-head">
                      <div class="nk-block-head-between flex-wrap gap g-2">
                        <div class="nk-block-head-content">
                          <div class="d-flex flex-column flex-md-row">
                            <div class="media media-huge media-circle">
                              <img src="{{asset('template/studentImage.png')}}" class="img-thumbnail" alt="">
                            </div>
                            <div class="mt-3 mt-md-0 ms-md-3">
                              <h3 class="title mb-1"  style="font-size:18px;font-weight:600 !important">TUTOR: {{$tutor->full_name}} </h3>
                              <span class="small">Subjects: @foreach($subjects as $rowSubject) <u>{{$rowSubject->subject_name}}</u>, @endforeach</span>
                              <ul class="nk-list-option pt-1">
                                <li>
                                  <em class="icon ni ni-map-pin"></em>
                                  <span class="small">{{$tutor->street_address1}}, {{$tutor->city}}</span>
                                </li>
                                <li>
                                  <em class="icon ni ni-building"></em>
                                  <span class="small">Softnio</span>
                                </li>
                              </ul>
                               <br><span style="@if($tutor->status == 'unverified') background:red;@else background:limegreen; @endif font-weight:400; font-size:16px;color:white;padding:4px 15px;border-radius:5px">{{$tutor->status}}</span>
                            </div>
                          </div>
                        </div>
                        <div class="nk-block-head-content">
                          <div class="d-flex gap g-3">
                            <div class="gap-col">
                              <div class="box-dotted py-2">
                                <div class="d-flex align-items-center">
                                  <div class="h4 mb-0">44.3K</div>
                                  <span class="change up ms-1 small">
                                    <em class="icon ni ni-arrow-down"></em>
                                  </span>
                                </div>
                                <div class="smaller">Followers</div>
                              </div>
                            </div>
                            <div class="gap-col">
                              <div class="box-dotted py-2">
                                <div class="d-flex align-items-center">
                                  <div class="h4 mb-0">4.5k</div>
                                  <span class="change up ms-1 small">
                                    <em class="icon ni ni-arrow-up"></em>
                                  </span>
                                </div>
                                <div class="smaller">Following</div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="nk-block-head-between gap g-2">
                        
                      <div class="gap-col">
                        <ul class="nav nav-pills nav-pills-border gap g-3">
                          <li class="nav-item">
                            <a href="{{route('tutorLogin',$tutor->id)}}"><button class="nav-link" type="button"> Overview </button></a>
                          </li>
                          <li class="nav-item">
                            
                            <a href="{{route('allTickets',$tutor->id)}}"><button class="nav-link" type="button"> All Tickets </button></a>
                          </li>
                          <li class="nav-item">
                            <a href="{{route('scheduledClasses',$tutor->id)}}"><button class="nav-link active" type="button"> Scheduled Classes </button></a>
                          </li>
                          <li class="nav-item">
                            <a href="{{route('assignedClasses',$tutor->id)}}"><button class="nav-link" type="button"> Assigned Classes </button></a>
                          </li>
                          
                        </ul>
                      </div>
                      <div class="gap-col">
                        <ul class="d-flex gap g-2">
                          <li class="d-none d-md-block">
                            <a href="user-edit.html" class="btn btn-soft btn-primary">
                              <em class="icon ni ni-edit"></em>
                              <span>Edit Profile</span>
                            </a>
                          </li>
                          <li class="d-md-none">
                            <a href="user-edit.html" class="btn btn-soft btn-primary btn-icon">
                              <em class="icon ni ni-edit"></em>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="nk-block">
                    <div class="tab-content" id="myTabContent">
                      <div class="tab-pane show active" id="tab-1" tabindex="0">
                        <div class="card card-gutter-md">
                          <div class="">
                            
                            <div class="card-content col-sep">
                              <div class="card-body">
                                <div class="bio-block">
                                  <h4 class="bio-block-title">Scheduled Classes</h4>
                                  <div class="row g-gs">
                                      
                                      @if($tutor->status == 'verified')
                                      
                                       @if($myApplicedTicekts)
                                             <div class="table-responsive">
                                                  <table class="table table-middle mb-0">
                                                    <thead class="table-dark table-head-sm">
                                                      <tr>
                                                        <th class="tb-col">
                                                          <span class="overline-title">Ticket ID</span>
                                                        </th>
                                                        <th class="tb-col tb-col-start tb-col-sm">
                                                          <span class="overline-title">Subject</span>
                                                        </th>
                                                        <th class="tb-col tb-col-start  tb-col-xxl">
                                                          <span class="overline-title">Time</span>
                                                        </th>
                                                        <th class="tb-col tb-col-start  tb-col-xxl">
                                                          <span class="overline-title">Day</span>
                                                        </th>
                                                        <th class="tb-col tb-col-start">
                                                          <span class="overline-title">status</span>
                                                        </th>
                                                        <th class="tb-col tb-col-start">
                                                          <span class="overline-title">Action</span>
                                                        </th>
                                                       
                                                      </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($myApplicedTicekts as $rowTicket)
                                                        
                                                              <tr >
                                                                <td class="tb-col tb-col-start tb-col-xxl">
                                                                  <span class="">{{$rowTicket->id}} - {{$rowTicket->uid}}</span>
                                                                </td>
                                                                <td class="tb-col tb-col-start tb-col-xxl">
                                                                  <span class="small">{{$rowTicket->subject_name}}</span>
                                                                </td>
                                                                <td class="tb-col tb-col-start tb-col-xxl">
                                                                  <span class="small">{{$rowTicket->time}}</span>
                                                                </td>
                                                                <td class="tb-col tb-col-start tb-col-xxl">
                                                                  <span class="small">{{$rowTicket->day}}</span>
                                                                </td>
                                                                <td class="tb-col tb-col-start tb-col-xxl">
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
                                                                       <a data-toggle="modal" data-target="#exampleModal" class="btn btn-primary waves-effect waves-light" href=""><i class="batch-icon batch-icon-add"></i> Add Schedule</a>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Schedule for Subject {{$rowTicket->subject_name}} </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="row row-details">
                    <div class="col-md-2 details-item">
                        <p class="item-title">Fullname</p>
                        <p>{{$rowTicket->studentName}}</p>
                    </div>
                    <div class="col-md-3 details-item">
                        <p class="item-title">Subject</p>
                        <p>{{$rowTicket->subject_name}}</p>
                    </div>
                    <div class="col-md-2 details-item">
                        <p class="item-title">Subscribed duration</p>
                        <p><strong>
                            0 hrs</strong></p>
                    </div>
                    
                    <div class="col-md-2 details-item">
                        <p class="item-title">Assigned duration</p>
                        <p><strong>
                           0 hrs</strong></p>
                    </div>
                </div>
        <form method="POST" action="{{route('submitClassSchedules')}}">
                        @csrf    
                        <input type="hidden" name="studentID" value="{{$rowTicket->studentID}}"/>
                        <input type="hidden" name="ticketID" value="{{$rowTicket->job_ticketsID}}"/>
                        <div class="row row-details">
                            <div class="col-md-3 details-item">
                                <p class="item-title">StudentId</p>
                                <p><strong>{{$rowTicket->studentID}}</strong></p>
                            </div>
                            <div class="col-md-3 details-item">
                                <p class="item-title">Fullname</p>
                                <p><strong>{{$rowTicket->studentName}}</strong></p>
                            </div>
                        </div>
                        <div class="row row-details">
                            <table class="table table-responsive no-border" style="margin-left:12px;">
                                    <tbody><tr>
                                        <td>Subject: <strong> 
                                            @php
                                                $getSubject = DB::table('student_subjects')->where('ticket_id','=',$rowTicket->id)->first();
                                                $getSubjectName = DB::table('products')->where('id','=',$getSubject->subject)->first();
                                            @endphp
                                        {{$getSubjectName->name}}</strong></td>
                                        <td>Subscribed duration : <strong> 1 hr(s)</strong></td>
                                        <td>Assigned duration: <strong>2 hr(s)</strong></td>
                                    </tr>
                            </tbody></table>
                        </div>
                        
                        <div class="row g-3">
                                        <div class="col-lg-3">
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
                                                <select id="student_id"  required="required" name="subject_id" class="js-select" data-search="true" data-sort="false">
                                                               <option selected value=""> - - - Select Subject - - - </option>
                                                    @foreach($allSubjects as $rowSubject)
                                                        <option value="{{$rowSubject->id}}">{{$rowSubject->name}}</option>
                                                    @endforeach
                                                </select>
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
  </td>
  </td>

                                                              </tr>
                                                              
                                                              <tr style="background-color:#f2f2f2">
                                                                  <td colspan="6" style="font-size:14px;">
                                                                        Student Name: <span style="font-size:14px;"class="mr-4">{{$rowTicket->studentName}},</span>
                                                                        Age: <span style="font-size:14px;"class="mr-4">{{$rowTicket->studentAge}},</span> 
                                                                        Gender: <span style="font-size:14px;"class="mr-4">{{$rowTicket->studentGender}},</span>
                                                                        Special Request: <span style="font-size:14px;"class="mr-4">{{$rowTicket->specialRequest}}</span>
                                                                  </td>
                                                              </tr>
                                                              <tr>
                                                                  <td colspan="7" style="padding:15px 0 !important">
                                                                      <h4 class="bio-block-title pb-3" style="font-size:16px;">Schedule for Class: <span style="color:#000;font-size:16px; font-weight:600;">{{$rowTicket->subject_name}}</span></h4>
                                                                        <div class="table-responsive">
                                                                          <table class="table table-middle mb-0">
                                                                            <thead class="table-dark table-head-sm">
                                                                              <tr>
                                                                                <th class="tb-col tb-col-start tb-col-sm">
                                                                                  <span class="overline-title">Class</span>
                                                                                </th>
                                                                                <th class="tb-col tb-col-start tb-col-sm">
                                                                                  <span class="overline-title">Date</span>
                                                                                </th>
                                                                                <th class="tb-col tb-col-start  tb-col-xxl">
                                                                                  <span class="overline-title">Start Time</span>
                                                                                </th>
                                                                                <th class="tb-col tb-col-start  tb-col-xxl">
                                                                                  <span class="overline-title">End Time</span>
                                                                                </th>
                                                                                <th class="tb-col tb-col-start">
                                                                                  <span class="overline-title">CheckIN</span>
                                                                                </th>
                                                                                <th class="tb-col tb-col-start">
                                                                                  <span class="overline-title">CheckOUT</span>
                                                                                </th>
                                                                                <th class="tb-col tb-col-start">
                                                                                  <span class="overline-title">IS PAID</span>
                                                                                </th>
                                                                                <th class="tb-col tb-col-start">
                                                                                  <span class="overline-title">Action</span>
                                                                                </th>
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
                                                                                        <td class="tb-col tb-col-start tb-col-xxl">
                                                                                          <span class="small">{{$classNumber++}}</span>
                                                                                        </td>
                                                                                        <td class="tb-col tb-col-start tb-col-xxl">
                                                                                          <span class="small">
                                                                                          @php
                                                                                            echo $newDate = date("d-m-Y", strtotime($rowTicketTwo->date));
                                                                                          @endphp
                                                                                          </span>
                                                                                        </td>
                                                                                        
                                                                                        <td class="tb-col tb-col-start tb-col-xxl">
                                                                                          <span class="small">{{$rowTicketTwo->startTime}}</span>
                                                                                        </td>
                                                                                        <td class="tb-col tb-col-start tb-col-xxl">
                                                                                          <span class="small">{{$rowTicketTwo->endTime}}</span>
                                                                                        </td>
                                                                                        <td class="tb-col tb-col-start tb-col-xxl">
                                                                                            <input id="" type="time" name="checkIN" class="checkIN form-control"/>
                                                                                        </td>
                                                                                        <td class="tb-col tb-col-start tb-col-xxl">
                                                                                            <input id="" type="time" name="checkOUT" class="checkOUT form-control"/>
                                                                                        </td>
                                                                                        <td class="tb-col tb-col-start tb-col-xxl">
                                                                                          <span class="small">
                                                                                              @if($rowTicketTwo->is_paid == 0)
                                                                                                        <span class="badge badge-warning">NOT PAID</span>
                                                                                                @else
                                                                                                        PAID
                                                                                              @endif
                                                                                              </span>
                                                                                        </td>
                                                                                        <td class="tb-col tb-col-start tb-col-xxl">
                                                                                            <input type="submit" name="submit" class="form-control"/>
                                                                                        </td>
                                                                                        
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
