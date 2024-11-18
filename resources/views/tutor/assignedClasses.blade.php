@extends('layouts.main')
@section('content')


  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<br/><br/>
  
          <div class="nk-content">
            <div class="fluid-container">
              <div class="nk-content-inner">
                <div class="nk-content-body">
                  <div class="nk-block-head">
                    <div class="nk-block-head">
                      <div class="nk-block-head-between flex-wrap gap g-2 align-items-start">
                        <div class="nk-block-head-content">
                          <div class="d-flex flex-column flex-md-row">
                            <div class="media media-huge media-circle">
                              <img src="{{asset('template/studentImage.png')}}" class="img-thumbnail" alt="">
                            </div>
                            <div class="mt-3 mt-md-0 ms-md-3">
                              <h3 class="title mb-1" style="font-size:18px;font-weight:600 !important">TUTOR: {{$tutor->full_name}}</h3>
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
                            <a href="{{route('scheduledClasses',$tutor->id)}}"><button class="nav-link" type="button"> Scheduled Classes </button></a>
                          </li>
                          <li class="nav-item">
                            <a href="{{route('assignedClasses',$tutor->id)}}"><button class="nav-link active" type="button"> Assigned Classes </button></a>
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
                                  <h4 class="bio-block-title">Assigned Classes</h4>
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
                                                        
                                                              <tr style="">
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
                                                                @if($rowTicket->tutorID != NULL)
                                                                <td class="tb-col tb-col-start tb-col-xxl">
                                                                  <span class="small"><a class="btn btn-sm btn-default" href="">{{$rowTicket->ticket_status}}</a></span>
                                                                </td>
                                                                @else
                                                                
                                                                <td class="tb-col tb-col-start tb-col-xxl">
                                                                  <span class="small"><a class="btn btn-sm btn-success" href="{{ url('tutorOffer/'.$tutorID.'/'.$rowTicket->id.'/'.$rowTicket->subject_id)}}">Offer Pending</a></span>
                                                                </td>
                                                                
                                                                @endif
                                                              </tr>
                                                              
                                                              <tr style="background-color:#f2f2f2">
                                                                  <td colspan="6" style="font-size:14px;">
                                                                        Student Name: <span style="font-size:14px;" class="mr-4">{{$rowTicket->studentName}},</span> 
                                                                        Email: <span style="font-size:14px;" class="mr-4">{{$rowTicket->studentEmail}},</span>
                                                                        Age: <span style="font-size:14;" class="mr-4">{{$rowTicket->studentAge}},</span>
                                                                        Gender: <span style="font-size:14;" class="mr-4">{{$rowTicket->studentGender}}</span>
                                                                  </td>
                                                              </tr>
                                                              <tr>
                                                                  <td colspan="7" style="padding:15px 0 !important">
                                                                      <h4 class="bio-block-title pb-3" style="font-size:16px;">ASSIGNED for Class: <span style="color:#000; font-weight:600; font-size:16px;">{{$rowTicket->subject_name}}</span></h4>
                                                                        <div class="table-responsive">
                                                                          <table class="table table-middle mb-0">
                                                                            <thead class="table-dark table-head-sm">
                                                                              <tr>
                                                                                <th class="tb-col tb-col-start tb-col-sm">
                                                                                  <span class="overline-title">Class</span>
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
