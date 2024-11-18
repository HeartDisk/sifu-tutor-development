@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
   <div class="fluid-container">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head flex-wrap gap g-2">
               <div class="nk-block-head-content">
                  <h2 class="nk-block-title">
                     Not Updated class Sehedules
                  </h2>
                  <nav>
                     <ol class="breadcrumb breadcrumb-arrow mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Followup</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Not Updated class Sehedules</li>
                     </ol>
                  </nav>
               </div>
            </div>
            <div class="nk-block">
               <div class="card">
                   <div class="card-body">
                      <form action="{{route('TutorNotUpdateClassSchedule')}}" method="GET">
                         @csrf
                         <div class="row justify-content-between tableper-row">
                            <input name="classScheduleSearch" value="1" type="hidden">
                            <div class="col-md-4">
                               <div class="input-group input-group-md">
                                  <span class="input-group-text" id="inputGroup-sizing-sm">From Date</span>
                                  <input name="fromDate" type="date" class="search form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                               </div>
                            </div>
                            <div class="col-md-4">
                               <div class="input-group input-group-md">
                                  <span class="input-group-text" id="inputGroup-sizing-sm">To Date</span>
                                  <input name="toDate" type="date" class="search form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                               </div>
                            </div>
                            <div class="col-md-2">
                               <div class="input-group input-group-md justify-content-end">
                                  <input type="submit" class="btn btn-primary" aria-label="Sizing example input" value="Search" aria-describedby="inputGroup-sizing-sm">
                               </div>
                            </div>
                            <div class="col-md-1">
                               <div class="input-group input-group-md justify-content-end">
                                  <a href="{{url('export_excel/tutorNotUpdateClassSchedule')}}" class="btn-cdownload btn btn-success" title="Download">
                                  <i title="Download" class="fa fa-download"></i>
                                  </a>
                               </div>
                            </div>
                         </div>
                      </form>
                      <table class="datatable-init table" data-nk-container="table-responsive">
                         <thead>
                            <tr>
                               <th>#</th>
                               <th>Tutor</th>
                               <th>Tutor Phone No.</th>
                               <th>Student</th>
                               <th>Subject</th>
                               <th>Class on</th>
                            </tr>
                         </thead>
                         <tbody>
                            @foreach($class_schedules as $rowClassSchedules)
                            @php
                            $class_schedules = DB::table('class_schedules')->where('ticketID','=',$rowClassSchedules->ticketID)->orderBy('id', 'asc')->first();
                            $tutor = DB::table('tutors')->where('id','=',$class_schedules->tutorID)->first();
                            $student = DB::table('students')->where('id','=',$class_schedules->studentID)->first();
                            $subject = DB::table('products')->where('id','=',$class_schedules->subjectID)->first();
                            @endphp
                            <tr>
                               <td>{{$class_schedules->id}}</td>
                               @if(isset($tutor->full_name))
                               <td>{{$tutor->full_name}}</td>
                               @endif
                               @if(isset($tutor->phoneNumber))
                               <td>{{$tutor->phoneNumber}}</td>
                               @endif
                               <td>{{$student->full_name}}</td>
                               <td>{{$subject->name}}</td>
                               <td>{{$rowClassSchedules->created_at}}</td>
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
