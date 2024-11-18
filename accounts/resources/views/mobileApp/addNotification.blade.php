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
                        Add Notification
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Notification List</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Add Notification</li>
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
                     @endif
                     @if (\Session::has('update'))
                     <div class="alert alert-primary">
                        <ul>
                           <li>{!! \Session::get('update') !!}</li>
                        </ul>
                     </div>
                     @endif
                     <div class="bio-block">
                        <form method="POST" action="{{route('submitNotification')}}">
                        @csrf
                        <div class="row g-3">
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <label for="firstname" class="form-label">Notification Type</label>
                                 <div class="form-control-wrap">
                                    <select class="form-control notificationType" name="notificationType">
                                       <option value="">--- Select ---</option>
                                       <option value="Schedule Class">Schedule Class</option>
                                       <option value="Submit Evaluation Report">Submit Evaluation Report</option>
                                       <option value="Submit Progress Report">Submit Progress Report</option>
                                    </select>
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-4 progressReportMonth">
                              <div class="form-group">
                                 <label for="firstname"  class="form-label">Progress Report Month</label>
                                 <div class="form-control-wrap">
                                    <select class="form-control" name="progressReportMonth">
                                       <option>Please select month</option>
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
                           </div>
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <label for="firstname" class="form-label">Tutor</label>
                                 <div class="form-control-wrap">
                                    <select  class="js-select" data-search="true" data-sort="false" name="tutorID">
                                       <option value=""></option>
                                       @foreach($tutors as $rowTutor)
                                       <option value="{{$rowTutor->id}}"> ({{$rowTutor->tutor_id}}) - {{$rowTutor->full_name}} - {{$rowTutor->id}} </option>
                                       @endforeach
                                    </select>
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <label for="firstname" class="form-label">Student</label>
                                 <div class="form-control-wrap">
                                    <select  class="js-select" data-search="true" data-sort="false" name="studentID">
                                       <option value=""></option>
                                       @foreach($students as $rowStudent)
                                       <option value="{{$rowStudent->id}}"> ({{$rowStudent->student_id}}) - {{$rowStudent->full_name}}</option>
                                       @endforeach
                                    </select>
                                 </div>
                              </div>
                           </div>
                           <div  class="col-lg-4 subject">
                              <div class="form-group">
                                 <label for="country" class="form-label">Subject</label>
                                 <div class="form-control-wrap">
                                    <select  class="js-select" data-search="true" data-sort="false" name="subjectID">
                                       <option value=""></option>
                                       @foreach($subjects as $rowSubject)
                                       <option value="{{$rowSubject->id}}"> {{$rowSubject->name}} </option>
                                       @endforeach
                                    </select>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-2"><button class="btn btn-primary" type="submit">Submit</button></div>
                        <!--</form> -->
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection