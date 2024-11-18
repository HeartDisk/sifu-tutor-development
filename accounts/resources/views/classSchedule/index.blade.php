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
                        Class Schedules
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Class Schedules</li>
                        </ol>
                     </nav>
                  </div>
               </div>
            </div>
            <div class="nk-block">
               <div class="card overflow-hidden">
                   <div class="card-body">
                      <form action="{{route('ClassSchedules')}}" method="GET">
                         @csrf
                         <div class="row justify-content-between tableper-row">
                            <input name="classScheduleSearch" value="1" type="hidden">
                            <div class="col-md-5">
                               <div class="input-group  input-group-md">
                                  <label class="input-group-text" for="inputGroupSelect01">Status</label>
                                  <select name="status" class="status form-select" id="inputGroupSelect01">
                                     <option value=""> All</option>
                                     @foreach($statuses as $status)
                                        <option value="{{ $status->status }}">{{ ucfirst($status->status) }}</option>
                                     @endforeach
                                  </select>
                               </div>
                            </div>
                            <div class="col-md-5">
                               <div class="input-group input-group-md">
                                  <span class="input-group-text" id="inputGroup-sizing-sm">Search</span>
                                  <input name="search" type="text" class="search form-control" placeholder="Student Name" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                               </div>
                            </div>
                            <div class="col-md-2">
                               <div class="input-group input-group-md">
                                  <input type="submit" class="btn btn-primary" aria-label="Sizing example input" value="Search" aria-describedby="inputGroup-sizing-sm">
                               </div>
                            </div>
                         </div>
                      </form>
                      <table class="datatable-init table" data-nk-container="table-responsive">
                         <thead>
                            <tr>
                               <th>Ticket ID</th>
                               <th>Tutor Name</th>
                               <th>Student ID</th>
                               <th>Student Name.</th>
                               <th>Level</th>
                               <th>Mode</th>
                               <th>Subject</th>
                               <th>Subscribed Duration hr(s)</th>
                               <th>Assigned Duration hr(s)</th>
                               <th>Status</th>
                               <th>Action</th>
                            </tr>
                         </thead>
                         <tbody id="scheduleAjaxCallBody">
                            @php
                            $number = 1;
                            @endphp
                            @foreach($class_schedules as $rowClassSchedule)
                            @php
                            $studentName = DB::table('students')->where('id','=',$rowClassSchedule->studentID)->first();
                            $subjectName = DB::table('products')->where('id','=',$rowClassSchedule->subjectID)->first();
                            $subjectName = DB::table('products')->join("categories","products.category","=","categories.id")
                            ->select("products.*","categories.category_name as category_name")
                            ->where('products.id','=',$rowClassSchedule->subjectID)->first();
                            $totalSubscribedHours = DB::table('class_schedules')->where('ticketID','=',$rowClassSchedule->ticketID)
                            ->where('status','=',"attended")
                            ->sum('totalTime');
                            $job_ticket_id = DB::table('job_tickets')->where('id','=',$rowClassSchedule->ticketID)->first();
                            @endphp
                            <tr>
                               <td>
                                  @php
                                  $job_ticket_id = DB::table('job_tickets')->where('id','=',$rowClassSchedule->ticketID)->first();
                                  if($job_ticket_id){echo $job_ticket_id->uid;}
                                  @endphp
                               </td>
                               <td>
                                  @php
                                  if($job_ticket_id)
                                  {
                                  $tutorDetail = DB::table('tutors')->where('id','=',$rowClassSchedule->tutorID)->first();
                                  if($tutorDetail){
                                  echo $tutorDetail->full_name;
                                  }
                                  }
                                  @endphp
                               </td>
                               <td><i class="fa fa-user"></i> {{$studentName!=null?$studentName->uid:""}}</td>
                               <td> @if($studentName) {{$studentName->full_name}} @endif </td>
                               <td> @if($subjectName) {{$subjectName->category_name}} @endif </td>
                               <td> @if($subjectName) {{$job_ticket_id->mode}} @endif </td>
                               <td>@if($subjectName) {{$subjectName->name}} @endif</td>
                               <td>{{$job_ticket_id->quantity*$job_ticket_id->classFrequency}}</td>
                               <td>@php echo ($totalSubscribedHours);  @endphp</td>
                               <td>
                                  @php
                                  $total_hours=$job_ticket_id->quantity*$job_ticket_id->classFrequency;
                                  $scheduled_hours=$totalSubscribedHours;
                                  @endphp
                                  @if($scheduled_hours==0)
                                  <p class="dtable-status-pending">Pending</p>
                                  @elseif($scheduled_hours != 0 && $scheduled_hours<$total_hours)
                                  <p class="dtable-status-inactive">Incomplete</p>
                                  @elseif($scheduled_hours==$total_hours)
                                  <p class="dtable-status-active">Complete</p>
                                  @elseif($scheduled_hours>$total_hours)
                                  <p class="dtable-status-pending">Undercharge</p>
                                  @endif
                               </td>
                               @can("class-schedule-view-history")
                               <td><a class="dtable-cbtn bt-view dtb-tooltip" dtb-tooltip="View" href="{{url('viewClassSchedules',$rowClassSchedule->ticketID)}}"><i class="fa fa-eye"></i></a></td>
                               @endcan
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
@endsection