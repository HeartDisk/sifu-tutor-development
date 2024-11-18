@extends('layouts.main')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<style>
    .tb-col-end, .tb-col-action {
    text-align: left;
}
td.tb-col.tb-col-end.tb-col-xxl:last-child {
    text-align: right;
}
</style>
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
                              <h3 class="title mb-1" style="font-size:18px;font-weight:600 !important"> Name: {{$tutor->full_name}}</h3>
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
                            <a href="{{route('allTickets',$tutor->id)}}"><button class="nav-link active" type="button"> All Tickets </button></a>
                          </li>
                          <li class="nav-item">
                            <a href="{{route('scheduledClasses',$tutor->id)}}"><button class="nav-link" type="button"> Scheduled Classes </button></a>
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
                                  <h4 class="bio-block-title">All Active Tickets</h4>
                                  <div class="row g-gs">
                                      @if($tutor->status == 'verified')
                                        @if($tickets)
                                             <div class="table-responsive">
                                                  <table class="table table-middle mb-0">
                                                    <thead class="table-dark table-head-sm">
                                                      <tr>
                                                        <th class="tb-col">
                                                          <span class="overline-title">Ticket ID</span>
                                                        </th>
                                                        <th class="tb-col tb-col-end tb-col-sm">
                                                          <span class="overline-title">Subject</span>
                                                        </th>
                                                        <th class="tb-col tb-col-end  tb-col-xxl">
                                                          <span class="overline-title">Time</span>
                                                        </th>
                                                        <th class="tb-col tb-col-end  tb-col-xxl">
                                                          <span class="overline-title">Day</span>
                                                        </th>
                                                        <th class="tb-col tb-col-end">
                                                          <span class="overline-title">status</span>
                                                        </th>
                                                        <th class="tb-col tb-col-end"  style="text-align: right;">
                                                          <span class="overline-title">Action</span>
                                                        </th>
                                                       
                                                      </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($tickets as $rowTicket)
                                                            @php
                                                                $ticketStatus = DB::table('tutoroffers')->where('ticketID','=',$rowTicket->ticketID)->where('tutorID','=',$tutor->id)->first();
                                                            @endphp
                                                        
                                                                <tr>
                                                                    <td class="tb-col tb-col-end tb-col-xxl">
                                                                      <span class="small">{{$rowTicket->id}}-{{$rowTicket->uid}}</span>
                                                                    </td>
                                                                    <td class="tb-col tb-col-end tb-col-xxl">
                                                                      <span class="small">{{$rowTicket->subject_name}}</span>
                                                                    </td>
                                                                    <td class="tb-col tb-col-end tb-col-xxl">
                                                                      <span class="small">{{$rowTicket->time}}</span>
                                                                    </td>
                                                                    <td class="tb-col tb-col-end tb-col-xxl">
                                                                      <span class="small">{{$rowTicket->day}}</span>
                                                                    </td>
                                                                    <td class="tb-col tb-col-end tb-col-xxl">
                                                                         {{$rowTicket->status}}
                                                                    </td>
                                                                    <td class="tb-col tb-col-end tb-col-xxl">
                                                                      <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal{{$rowTicket->id}}">View</button>
                                                                        <div class="modal fade" id="exampleModal{{$rowTicket->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                           <div class="modal-dialog">
                                                                              <div class="modal-content">
                                                                                 <div class="modal-header">
                                                                                    <h5 class="modal-title" id="exampleModalLabel">Ticket: {{$rowTicket->uid}} - {{$rowTicket->subject_name}}</h5>
                                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>        
                                                                                 </div>
                                                                                    <div class="modal-body">
                                                                                        @php
                                                                                            $studentDetail = DB::table('students')->where('id','=',$rowTicket->studentID)->first();
                                                                                        @endphp
                                                                                       <div class="card-aside">
                                                                                          <div class="card-body">
                                                                                            <div class="bio-block">
                                                                                              <h4 style="text-align:left !important;"  class="bio-block-title">Student Details</h4>
                                                                                              <ul class="list-group list-group-borderless small">
                                                                                                <li style="text-align:left !important;" class="list-group-item">
                                                                                                  <span class="title fw-medium w-40 d-inline-block">Student ID:</span>
                                                                                                  <span class="text">{{$studentDetail->student_id}}</span>
                                                                                                </li>
                                                                                                <li style="text-align:left !important;" class="list-group-item">
                                                                                                  <span class="title fw-medium w-40 d-inline-block">Full Name:</span>
                                                                                                  <span class="text">{{$studentDetail->full_name}}</span>
                                                                                                </li>
                                                                                                <li style="text-align:left !important;" class="list-group-item">
                                                                                                  <span class="title fw-medium w-40 d-inline-block">Email:</span>
                                                                                                  <span class="text">{{$studentDetail->email}}</span>
                                                                                                </li>
                                                                                                <li style="text-align:left !important;" class="list-group-item">
                                                                                                  <span class="title fw-medium w-40 d-inline-block">Gender:</span>
                                                                                                  <span class="text">{{$studentDetail->gender}}</span>
                                                                                                </li>
                                                                                                <li style="text-align:left !important;"  class="list-group-item">
                                                                                                  <span class="title fw-medium w-40 d-inline-block">Address</span>
                                                                                                  <span class="text">{{$studentDetail->address1}} {{$studentDetail->address2}}</span>
                                                                                                </li>
                                                                                                <li style="text-align:left !important;" class="list-group-item">
                                                                                                  <span class="title fw-medium w-40 d-inline-block">City</span>
                                                                                                  <span class="text">{{$studentDetail->city}}</span>
                                                                                                </li>
                                                                                              </ul>
                                                                                            </div>
                                                                                            
                                                                                          </div>
                                                                                        </div>
                                                                                        
                                                                                    </div>
                                                                                 <div class="modal-footer">
                                                                                     <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                                </div>
                                                                              </div>
                                                                           </div>
                                                                        </div>
                                                                          @if($rowTicket->ticket_status == 'pending')
                                                                            @if($ticketStatus)
                                                                                @if($ticketStatus->ticketID == $rowTicket->id)
                                                                                    <span style="font-weight:bold" class="btn btn-sm btn-warning" >Offer Sent</span>
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
