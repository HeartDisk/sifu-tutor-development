@extends('layouts.main') @section('content')
    <div class="nk-content sifu-view-page">
        <div class="fluid-container">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head">
                        <div class="nk-block-head-between flex-wrap gap g-2">
                            <div class="nk-block-head-content">
                                <h2 class="nk-block-title">View Class Schedules</h2>
                                <nav>
                                    <ol class="breadcrumb breadcrumb-arrow mb-0">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item"><a href="#">Class Schedules</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">View Class Schedules</li>
                                    </ol>
                                </nav>
                            </div>
                            <div class="nk-block-head-content">
                                <ul class="d-flex">
                                    @can("class-schedule-edit")
                                        <li>
                                              @if($tickets->ticket_tutor_status != "discontinued")
                                            <a data-toggle="modal" data-target="#exampleModal"
                                               class="btn btn-primary"><i class="batch-icon batch-icon-add"></i> Add Schedule</a>
                                                  @endif
                                            <!-- Modal -->
                                            <div class="modal fade dtable-modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xl" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Add Schedule for Subject {{$subjectDetail->name}} </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="POST" action="{{route('submitClassSchedulesAdmin')}}">
                                                                @csrf
                                                                <input type="hidden" name="classScheduleID" value="{{$classScheduleId}}"/>
                                                                <input type="hidden" name="studentID" value="{{$studentDetail->id}}"/>
                                                                <input type="hidden" name="ticketID" value="{{$tickets->id}}"/>
                                                                <div class="row view-sindetails">
                                                                    <div class="col-md-2 details-item">
                                                                        <p class="item-title">StudentId</p>
                                                                        <p><strong>{{$studentDetail->uid}}</strong></p>
                                                                    </div>
                                                                    <div class="col-md-3 details-item">
                                                                        <p class="item-title">Fullname</p>
                                                                        <p><strong>{{$studentDetail->full_name}}</strong></p>
                                                                    </div>
                                                                    <div class="col-md-3 details-item">
                                                                        <p class="item-title">Subject</p>
                                                                        <p><strong>@php
                                                                                    $getSubject = DB::table('student_subjects')->where('ticket_id','=',$tickets->id)->first();
                                                                                    $getSubjectName = DB::table('products')->where('id','=',$getSubject->subject)->first();
                                                                                @endphp
                                                                                {{$getSubjectName->name}}</strong>
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-md-2 details-item">
                                                                        <p class="item-title">Subscribed Duration</p>
                                                                        <p><strong> {{$total_subscribed_hours}} hr(s)</strong></p>
                                                                    </div>
                                                                    <div class="col-md-2 details-item">
                                                                        <p class="item-title">Assigned Duration</p>
                                                                        <p>
                                                                            <strong> {{number_format($total_attended_hours,2)}} hr(s)</strong></p>
                                                                    </div>
                                                                </div>
                                                                <div class="row g-3 mb-4">
                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">
                                                                            <label for="firstname" class="form-label">Tutor
                                                                                Name</label>
                                                                            <div class="form-control-wrap">
                                                                                <input type="text" readonly class="form-control" value="{{$tutorDetail->full_name}}"/>
                                                                                <input type="hidden" name="tutorID" value="{{$tutorDetail->id}}"/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    @php $currentDate = date("Y-m-d"); @endphp
                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">
                                                                            <label for="firstname" class="form-label">Date</label>
                                                                            <div class="form-control-wrap">
                                                                                <input  type="date" name="date" value="{{$currentDate}}" id="scheduleDate" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">
                                                                            <label for="firstname" class="form-label">Subject</label>
                                                                            <div class="form-control-wrap">
                                                                                <input readonly type="text" class="form-control" value="{{$subjectDetail->name}}"/>
                                                                                <input type="hidden" name="subjectID" value="{{$subjectDetail->id}}"/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-3">
                                                                        <div class="form-group">
                                                                            <label for="firstname" class="form-label">Start
                                                                                Time</label>
                                                                            <div class="form-control-wrap">
                                                                                <input required type="time" name="start_time" id="startTime" onchange="updateEndTime()" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-3">
                                                                        <div class="form-group">
                                                                            <label for="firstname" class="form-label">End
                                                                                Time</label>
                                                                            <div class="form-control-wrap">
                                                                                <input required type="time" name="end_time" id="endTime" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div style="display: none" class="col-lg-1">
                                                                        <div class="form-group">
                                                                            <label for="firstname" class="form-label">Has
                                                                                Incentive</label>
                                                                            <div class="form-control-wrap">
                                                                                <input type="checkbox" name="has_incentive">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-3">
                                                                        <div class="form-group">
                                                                            <label for="firstname" class="form-label">Total
                                                                                Classes</label>
                                                                            <div class="form-control-wrap">
                                                                                <input readonly type="text" name="totalClasses" value="{{$tickets->classFrequency}}" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-3">
                                                                        <div class="form-group">
                                                                            <label for="firstname" class="form-label">Remaining
                                                                                Classes</label>
                                                                            <div class="form-control-wrap">
                                                                                <input readonly type="text" name="remaining_classes" value="{{$tickets->remaining_classes}}" class="form-control">
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
                                            </div>
                                        </li>
                                    @endcan
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="nk-block">
                        <div class="card">
                          <div class="card-body">
                            <div class="row justify-content-between tableper-row">
                                <div class="col-md-5">
                                    <div class="input-group  input-group-md">
                                        <label class="input-group-text" for="inputGroupSelect01">Status</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="">All</option>
                                            <option value="no-application">no-application</option>
                                            <option value="scheduled">Scheduled</option>
                                            <option value="attended">Attended</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="input-group  input-group-md">
                                        <label class="input-group-text" for="inputGroupSelect01">Month</label>
                                        <select class="form-control" id="month" name="month">
                                            <option value="">All Month</option>
                                            <option value="January">January</option>
                                            <option value="February">February</option>
                                            <option value="March">March</option>
                                            <option value="April">April</option>
                                            <option value="May">May</option>
                                            <option value="June">June</option>
                                            <option value="July">July</option>
                                            <option value="August">August</option>
                                            <option value="September">September</option>
                                            <option value="October">October</option>
                                            <option value="November">November</option>
                                            <option value="December">December</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group input-group-md">
                                        <input type="submit" class="btn btn-primary" aria-label="Sizing example input"
                                               value="Search" aria-describedby="inputGroup-sizing-sm">
                                    </div>
                                </div>
                            </div>

                            <div class="row view-sindetails">
                                <div class="col-md-2 details-item">
                                    <p class="item-title">Ticket</p>
                                    <p><strong>{{$tickets->uid}}</strong></p>
                                </div>
                                <div class="col-md-2 details-item">
                                    <p class="item-title">Student</p>
                                    <p><strong>{{$studentDetail->uid}}</strong></p>
                                </div>
                                <div class="col-md-2 details-item">
                                    <p class="item-title">Fullname</p>
                                    <p><strong>{{$studentDetail->full_name}}</strong></p>
                                </div>
                                <div class="col-md-2 details-item">
                                    <p class="item-title">Subject</p>
                                    <p><strong>{{$subjectDetail->name}}</strong></p>
                                </div>
                                <div class="col-md-2 details-item">
                                    <p class="item-title">Subscribed duration</p>
                                    <p><strong>{{$total_subscribed_hours}} hrs</strong></p>
                                </div>
                                <div class="col-md-2 details-item">
                                    <p class="item-title">Assigned duration</p>
                                    <p><strong>{{number_format($total_attended_hours,2)}} hrs</strong></p>
                                </div>
                            </div>

                            <table class="datatable-init table" data-nk-container="table-responsive">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Subject</th>
                                    <th>Date</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Total Duration (Hrs)</th>
                                    <th>Tutor</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <input style="display:none" type="text" class="class_frequency" value="{{$tickets->quantity}}">

                                @php $number= 1; @endphp @foreach($class_schedulesByID as $row)
                                    <tr>
                                        <th>{{$number++}}</th>
                                        <td>{{$subjectDetail->name}}</td>
                                        <td>@php echo date("d-m-Y", strtotime($row->date)); @endphp</td>
                                        <td>{{$row->startTime}}</td>
                                        <td>{{$row->endTime}}</td>
                                        <td>@php echo number_format((float)$row->totalTime, 2, '.', ''); @endphp</td>
                                        <td>{{$tutorDetail->full_name}}</td>
                                        <td>
                                            <p class="dtb-vcsstatus">@php $class_attendeds = DB::table('class_attendeds')->where('class_schedule_id','=',$row->id)->where('status','=','attended')->first(); $okClass = DB::table('class_attendeds')->where('class_schedule_id','=',$row->id)->where('startTime','!=',NULL)->where('endTime','!=',NULL)->where('status','=','attended')->first();
                                                @endphp {{$row->status}}</p></td>
                                        <td>

                                            @if($row->status=="dispute" && $row->totalTime!=0)

                                                <a href="{{url("/approveDispute")."/".$row->id}}">  <button class="btn btn-success btn-sm">Approve Dispute</button></a>
                                                <a href="{{url("/rejectDispute")."/".$row->id}}">  <button class="btn btn-danger btn-sm">Decline Dispute</button></a>
                                            @else
                                                {{$row->statusReason}}
                                            @endif

                                        </td>
                                        <td>
                                           
                                            @if($class_attendeds)
                                                <a class="dtable-cbtn bt-view dtb-tooltip" dtb-tooltip="View"
                                                   data-toggle="modal"
                                                   data-target="#exampleModalAttendedImage{{$row->id}}"> <span
                                                        class="fa fa-eye"></span></a>
                                                <!-- Modal -->
                                                <div class="modal fade dtable-modal"
                                                     id="exampleModalAttendedImage{{$row->id}}" tabindex="-1"
                                                     role="dialog" aria-labelledby="exampleModalLabel"
                                                     aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">
                                                                    Attendance Proof
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row g-3 mb-4 mt-2">
                                                                    @php $getImages = DB::table('class_attendeds')->where('class_schedule_id','=',$row->id)->where('status','=','attended')->first(); @endphp
                                                                    <div class="row cclockio">
                                                                        <div class="col-12 cclockin">

                                                                            <div class="col-12">
                                                                                <img
                                                                                    src="{{asset('public/clock-in.png')}}"
                                                                                    class="img-fluid" alt="img">
                                                                            </div>

                                                                            <div class="col-12">
                                                                                <h6>Clock INN Proof</h6>
                                                                                <time>
                                                                                    @php echo date("d-m-Y", strtotime($row->date)); @endphp
                                                                                    <br/> {{$row->startTime}}
                                                                                </time>
                                                                            </div>

                                                                            <div class="col-12">
                                                                                <a href="@if($getImages){{asset('public/signInProof/'.$getImages->startTimeProofImage)}}@endif"
                                                                                   data-lightbox="image">View
                                                                                    Attachment</a>
                                                                            </div>

                                                                        </div>

                                                                        <div class="col-12 cclockout">

                                                                            <div class="col-12">
                                                                                <img
                                                                                    src="{{asset('public/clock-out.png')}}"
                                                                                    class="img-fluid" alt="img">
                                                                            </div>

                                                                            <div class="col-12">
                                                                                <h6>Clock OUT Proof</h6>
                                                                                <time>
                                                                                    @php echo date("d-m-Y", strtotime($row->date)); @endphp
                                                                                    <br/> {{$row->endTime}}
                                                                                </time>
                                                                            </div>

                                                                            <div class="col-12">
                                                                                <a href="@if($getImages){{asset('public/signOutProof/'.$getImages->endTimeProofImage)}}@endif"
                                                                                   data-lightbox="image">View
                                                                                    Attachment</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- Modal footer -->
                                                                <div class="modal-footer">
                                                                      <button type="button" class="close btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
                                                                </div
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <a class="dtable-cbtn bt-login dtb-tooltip"
                                                   dtb-tooltip="Clockin/Clockout" data-toggle="modal"
                                                   data-target="#exampleModalAttended{{$row->id}}"> <span
                                                        class="fa fa-sign-in"></span></a>
                                            @endif @if(!$okClass)
                                                <a class="dtable-cbtn bt-pay dtb-tooltip"
                                                   dtb-tooltip="Update Class Schedule Status" data-toggle="modal"
                                                   data-target="#exampleModalAttendedClassCheck{{$row->id}}"> <span
                                                        class="fa fa-calendar"></span></a>
                                                <!-- Modal -->
                                                <div class="modal fade dtable-modal"
                                                     id="exampleModalAttendedClassCheck{{$row->id}}" tabindex="-1"
                                                     role="dialog" aria-labelledby="exampleModalLabel"
                                                     aria-hidden="true">
                                                    <div class="modal-dialog modal-xl" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">
                                                                    Attended Class for Subject {{$subjectDetail->name}}
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                @php $startTimeAttended = DB::table('class_attendeds')->where('class_schedule_id','=',$row->id)->where('startTime','!=',NULL)->first(); $endTimeAttended = DB::table('class_attendeds')->where('class_schedule_id','=',$row->id)->where('endTime','!=',NULL)->first();
                                                        $submittedStatus = DB::table('class_attendeds')->where('class_schedule_id','=',$row->id)->where('status','=','attended')->first(); $getSubject = DB::table('student_subjects')->where('ticket_id','=',$tickets->id)->first();
                                                        $getSubjectName = DB::table('products')->where('id','=',$getSubject->subject)->first(); @endphp
                                                                <form method="POST" action="{{route('submitStatus')}}">
                                                                    @csrf @if($startTimeAttended)
                                                                        <input type="hidden" name="id"
                                                                               value="{{$startTimeAttended->id}}"/>
                                                                    @endif
                                                                    <input type="hidden" name="classScheduleID"
                                                                           value="{{$row->id}}"/>
                                                                    <input type="hidden" name="csID"
                                                                           value="{{$row->class_schedule_id}}"/>
                                                                    <input type="hidden" name="subjectID"
                                                                           value="{{ $subjectDetail->id}}"/>
                                                                    <div class="row view-sindetails">
                                                                        <div class="col-md-2 details-item">
                                                                            <p class="item-title">StudentId</p>
                                                                            <p><strong>{{$studentDetail->uid}}</strong>
                                                                            </p>
                                                                        </div>
                                                                        <div class="col-md-3 details-item">
                                                                            <p class="item-title">Fullname</p>
                                                                            <p>
                                                                                <strong>{{$studentDetail->full_name}}</strong>
                                                                            </p>
                                                                        </div>
                                                                        <div class="col-md-3 details-item">
                                                                            <p class="item-title">Subject</p>
                                                                            <p>
                                                                                <strong>{{$getSubjectName->name}}</strong>
                                                                            </p>
                                                                        </div>
                                                                        <div class="col-md-2 details-item">
                                                                            <p class="item-title">Subscribed
                                                                                Duration</p>
                                                                            <p><strong>{{$total_subscribed_hours}}
                                                                                    hr(s)</strong></p>
                                                                        </div>
                                                                        <div class="col-md-2 details-item">
                                                                            <p class="item-title">Assigned Duration</p>
                                                                            <p>
                                                                                <strong>{{number_format($total_attended_hours,2)}}
                                                                                    hr(s)</strong></p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row g-3 mb-4">
                                                                        <div class="col-lg-3"></div>
                                                                        <div class="col-lg-6">
                                                                            <div class="form-group">
                                                                                <label for="firstname"
                                                                                       class="form-label">Class
                                                                                    Status</label>
                                                                                <div class="form-control-wrap">
                                                                                    <select class="form-control"
                                                                                            required name="classStatus">
                                                                                        <option value="">Select status
                                                                                        </option>
                                                                                        <option value="attended">
                                                                                            Attended
                                                                                        </option>
                                                                                        <option value="postponed">
                                                                                            Postponed
                                                                                        </option>
                                                                                        <option value="cancelled">
                                                                                            Cancelled
                                                                                        </option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-3"></div>
                                                                    </div>
                                                                    <!-- Modal footer -->
                                                                    <div class="modal-footer">
                                                                        <input type="submit" class="btn btn-success"
                                                                               value="Submi Status">
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <!-- Modal -->
                                            <div class="modal fade dtable-modal" id="exampleModalAttended{{$row->id}}"
                                                 tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                 aria-hidden="true">
                                                <div class="modal-dialog modal-xl" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Attended
                                                                Class for Subject {{$subjectDetail->name}}</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @php $startTimeAttended = DB::table('class_attendeds')->where('class_schedule_id','=',$row->id)->where('startTime','!=',NULL)->first(); $endTimeAttended = DB::table('class_attendeds')->where('class_schedule_id','=',$row->id)->where('endTime','!=',NULL)->first();
                                                        $submittedStatus = DB::table('class_attendeds')->where('class_schedule_id','=',$row->id)->where('status','=','attended')->first(); $getSubject = DB::table('student_subjects')->where('ticket_id','=',$tickets->id)->first();
                                                        $getSubjectName = DB::table('products')->where('id','=',$getSubject->subject)->first(); @endphp @if($startTimeAttended)
                                                                <form method="POST" action="{{route('submitCheckOut')}}"
                                                                      enctype="multipart/form-data">
                                                                    @csrf
                                                                    <input type="hidden" name="id"
                                                                           value="{{$startTimeAttended->id}}"/>
                                                                    <input type="hidden" name="classScheduleID"
                                                                           value="{{$row->id}}"/>
                                                                    <input type="hidden" name="csID"
                                                                           value="{{$row->class_schedule_id}}"/> @else
                                                                        <form method="POST"
                                                                              action="{{route('submitCheckIn')}}"
                                                                              enctype="multipart/form-data">
                                                                            @csrf
                                                                            <input type="hidden" name="subjectID"
                                                                                   value="{{$getSubjectName->id}}"/>
                                                                            <input type="hidden" name="ticketID"
                                                                                   value="{{$tickets->id}}"/>
                                                                            <input type="hidden" name="studentID"
                                                                                   value="{{$studentDetail->id}}"/>
                                                                            <input type="hidden" name="classScheduleID"
                                                                                   value="{{$row->id}}"/>
                                                                            <input type="hidden" name="csID"
                                                                                   value="{{$row->class_schedule_id}}"/> @endif
                                                                            <div class="row view-sindetails">
                                                                                <div class="col-md-3 details-item">
                                                                                    <p class="item-title">Fullname</p>
                                                                                    <p>
                                                                                        <strong>{{$studentDetail->full_name}}</strong>
                                                                                    </p>
                                                                                </div>
                                                                                <div class="col-md-2 details-item">
                                                                                    <p class="item-title">StudentId</p>
                                                                                    <p>
                                                                                        <strong>{{$studentDetail->uid}}</strong>
                                                                                    </p>
                                                                                </div>
                                                                                <div class="col-md-3 details-item">
                                                                                    <p class="item-title">Subject</p>
                                                                                    <p>
                                                                                        <strong>{{$getSubjectName->name}}</strong>
                                                                                    </p>
                                                                                </div>
                                                                                <div class="col-md-2 details-item">
                                                                                    <p class="item-title">Subscribed
                                                                                        Duration</p>
                                                                                    <p>
                                                                                        <strong> {{$total_subscribed_hours}}
                                                                                            hr(s)</strong></p>
                                                                                </div>
                                                                                <div class="col-md-2 details-item">
                                                                                    <p class="item-title">Assigned
                                                                                        Duration</p>
                                                                                    <p>
                                                                                        <strong> {{number_format($total_attended_hours,2)}}
                                                                                            hr(s)</strong></p>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row g-3 mb-4">
                                                                                <div class="col-lg-4">
                                                                                    <div class="form-group">
                                                                                        <label for="firstname"
                                                                                               class="form-label">Tutor
                                                                                            Name</label>
                                                                                        <div class="form-control-wrap">
                                                                                            <input type="text"
                                                                                                    readonly
                                                                                                   class="form-control"
                                                                                                   value="{{$tutorDetail->full_name}}"/>
                                                                                            <input type="hidden"
                                                                                                   name="tutorID"
                                                                                                   value="{{$tutorDetail->id}}"/>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-4">
                                                                                    <div class="form-group">
                                                                                        @php $newDate = date("Y-m-d", strtotime($row->date)); @endphp
                                                                                        <label for="firstname"
                                                                                               class="form-label">Date</label>
                                                                                        <div class="form-control-wrap">
                                                                                            <input type="date"
                                                                                                   name="date"
                                                                                                   readonly
                                                                                                   value="{{$newDate}}"
                                                                                                   class="form-control">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-4">
                                                                                    <div class="form-group">
                                                                                        <label for="firstname"
                                                                                               class="form-label">Subject</label>
                                                                                        <div class="form-control-wrap">
                                                                                            <input type="text"
                                                                                                   class="form-control"
                                                                                                   readonly
                                                                                                   value="{{$subjectDetail->name}}"/>
                                                                                            <input type="hidden"
                                                                                                   name="subjectID"
                                                                                                   value="{{$subjectDetail->id}}"/>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-4">
                                                                                    <div class="form-group">
                                                                                        <label for="firstname"
                                                                                               class="form-label">Clock
                                                                                            in
                                                                                            : {{$row->startTime}}</label>
                                                                                        <div class="form-control-wrap">
                                                                                            <input type="time" required
                                                                                                   name="start_time"
                                                                                                   value="@if($startTimeAttended){{$startTimeAttended->startTime}}@endif"
                                                                                                   class="form-control">
                                                                                        </div>
                                                                                    </div>
                                                                                    @if($startTimeAttended)
                                                                                    @endif
                                                                                </div>
                                                                                <div class="col-lg-4">
                                                                                    <div class="form-group">
                                                                                        <label for="CheckOut"
                                                                                               class="form-label">Clockout
                                                                                            : {{$row->endTime}}</label>
                                                                                        <div
                                                                                            style="@if($startTimeAttended) display:block; @else display:none; @endif"
                                                                                            class="form-control-wrap">
                                                                                            <input type="time"
                                                                                                   @if($startTimeAttended) required
                                                                                                   @endif name="end_time"
                                                                                                   value="@if($startTimeAttended){{$startTimeAttended->endTime}}@endif"
                                                                                                   class="form-control">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div style="display: none"
                                                                                     class="col-lg-4">
                                                                                    <div class="form-group">
                                                                                        <label for="firstname"
                                                                                               class="form-label">Has
                                                                                            Incentive</label>
                                                                                        <div class="form-control-wrap">
                                                                                            <input type="checkbox"
                                                                                                   name="has_incentive">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                @if($endTimeAttended )
                                                                                    <div class="col-lg-4">
                                                                                        <div class="form-group">
                                                                                            <label for="firstname"
                                                                                                   class="form-label">Class
                                                                                                Status</label>
                                                                                            <div
                                                                                                class="form-control-wrap">
                                                                                                <select
                                                                                                    class="form-control"
                                                                                                    name="classStatus">
                                                                                                    <option
                                                                                                        value=""></option>
                                                                                                    <option
                                                                                                        value="attended">
                                                                                                        Attended
                                                                                                    </option>
                                                                                                    <option
                                                                                                        value="postponed">
                                                                                                        postponed
                                                                                                    </option>
                                                                                                    <option
                                                                                                        value="cancelled">
                                                                                                        cancelled
                                                                                                    </option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                @else @endif @if($startTimeAttended)
                                                                                    <div class="col-lg-4">
                                                                                        <div class="form-group">
                                                                                            <label for="firstname"
                                                                                                   class="form-label">Attendance
                                                                                                Proof at Clock
                                                                                                Out</label>
                                                                                            <div
                                                                                                class="form-control-wrap">
                                                                                                <input type="file"
                                                                                                       required
                                                                                                       class="form-control"
                                                                                                       name="signOutProof"/>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                @else
                                                                                    <div class="col-lg-4">
                                                                                        <div class="form-group">
                                                                                            <label for="firstname"
                                                                                                   class="form-label">Attendance
                                                                                                Proof at Clock
                                                                                                In</label>
                                                                                            <div
                                                                                                class="form-control-wrap">
                                                                                                <input type="file"
                                                                                                       required
                                                                                                       class="form-control"
                                                                                                       name="signInProof"/>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                            <!-- Modal footer -->
                                                                            <div class="modal-footer">
                                                                                <input type="submit"
                                                                                       class="btn btn-success"
                                                                                       value="@if($startTimeAttended) Clock Out @else Clock In @endif">
                                                                            </div>
                                                                        </form>
                                                                </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @can("class-schedule-edit")
                                                @if(!$class_attendeds)
                                                    <a class="dtable-cbtn bt-edit dtb-tooltip"
                                                       dtb-tooltip="Edit Class Schedule" data-toggle="modal"
                                                       data-target="#exampleModalEdit{{$row->id}}"> <span
                                                            class="fa fa-edit"></span></a>
                                                @endif
                                            @endcan
                                            <!-- Modal -->
                                            <div class="modal fade dtable-modal" id="exampleModalEdit{{$row->id}}"
                                                 tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                 aria-hidden="true">
                                                <div class="modal-dialog modal-xl" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Edit Schedule
                                                                for Subject {{$subjectDetail->name}}</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="POST"
                                                                  action="{{route('submitEditClassSchedules')}}">
                                                                @csrf
                                                                <input type="hidden" name="classScheduleID"
                                                                       value="{{$row->id}}"/>
                                                                <input type="hidden" name="studentID"
                                                                       value="{{$studentDetail->id}}"/>
                                                                <input type="hidden" name="ticketID"
                                                                       value="{{$tickets->id}}"/>
                                                                <div class="row view-sindetails">
                                                                    <div class="col-md-2 details-item">
                                                                        <p class="item-title">StudentId</p>
                                                                        <p><strong>{{$studentDetail->uid}}</strong></p>
                                                                    </div>
                                                                    <div class="col-md-3 details-item">
                                                                        <p class="item-title">Fullname</p>
                                                                        <p>
                                                                            <strong>{{$studentDetail->full_name}}</strong>
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-md-3 details-item">
                                                                        <p class="item-title">Subject</p>
                                                                        <p><strong>@php
                                                                                    $getSubject = DB::table('student_subjects')->where('ticket_id','=',$tickets->id)->first();
                                                                                    $getSubjectName = DB::table('products')->where('id','=',$getSubject->subject)->first();
                                                                                @endphp
                                                                                {{$getSubjectName->name}}</strong>
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-md-2 details-item">
                                                                        <p class="item-title">Subscribed Duration</p>
                                                                        <p><strong>{{$total_subscribed_hours}}
                                                                                hr(s)</strong></p>
                                                                    </div>
                                                                    <div class="col-md-2 details-item">
                                                                        <p class="item-title">Assigned Duration</p>
                                                                        <p>
                                                                            <strong><strong> {{number_format($total_attended_hours,2)}}
                                                                                    hr(s)</strong></strong></p>
                                                                    </div>
                                                                </div>
                                                                <div class="row g-3 mb-4">
                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">
                                                                            <label for="firstname" class="form-label">Tutor
                                                                                Name</label>
                                                                            <div class="form-control-wrap">
                                                                                <input type="text" class="form-control"
                                                                                       value="{{$tutorDetail->full_name}}"/>
                                                                                <input type="hidden" name="tutorID"
                                                                                       value="{{$tutorDetail->id}}"/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">
                                                                            @php $newDate = date("Y-m-d", strtotime($row->date)); @endphp
                                                                            <label for="firstname" class="form-label">Date</label>
                                                                            <div class="form-control-wrap">
                                                                                <input type="date" name="date"
                                                                                       value="{{$newDate}}"
                                                                                       class="form-control">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">
                                                                            <label for="firstname" class="form-label">Subject</label>
                                                                            <div class="form-control-wrap">
                                                                                <input type="text" class="form-control"
                                                                                       value="{{$subjectDetail->name}}"/>
                                                                                <input type="hidden" name="subjectID"
                                                                                       value="{{$subjectDetail->id}}"/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">
                                                                            <label for="firstname" class="form-label">Start
                                                                                Time</label>
                                                                            <div class="form-control-wrap">
                                                                                <input type="time" name="start_time"
                                                                                       value="{{$row->startTime}}"
                                                                                       class="form-control">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">
                                                                            <label for="firstname" class="form-label">End
                                                                                Time</label>
                                                                            <div class="form-control-wrap">
                                                                                <input type="time" name="end_time"
                                                                                       value="{{$row->endTime}}"
                                                                                       class="form-control">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div style="display: none" class="col-lg-4">
                                                                        <div class="form-group">
                                                                            <label for="firstname" class="form-label">Has
                                                                                Incentive</label>
                                                                            <div class="form-control-wrap">
                                                                                <input type="checkbox"
                                                                                       name="has_incentive">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- Modal footer -->
                                                                <div class="modal-footer">
                                                                    <input type="submit" class="btn btn-success"
                                                                           value="Update Class Schedule">
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @can("class-schedule-delete")
                                                @if(!$class_attendeds)
                                                    <a class="dtable-cbtn bt-delete dtb-tooltip"
                                                       dtb-tooltip="Delete Class Schedule"
                                                       onclick="return confirm('Are you sure you want to delete this item?');"
                                                       href="{{url("/deleteClassSchedule")."/".$row->id}}"> <span
                                                            class="fa fa-trash"></span></a>
                                                @endif
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script
        src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key=AIzaSyAP0kk0GU1hG9_-J8ovxiOogeWaOOtwSKo&libraries=places"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"
            integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyAP0kk0GU1hG9_-J8ovxiOogeWaOOtwSKo"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
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
            console.log("UPdated end time:"+updatedEndTime);
        }
    </script>
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
@endsection
