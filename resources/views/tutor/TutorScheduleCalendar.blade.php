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
                        Tutors Schedule Calendar
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Tutors</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Tutors Schedule Calendar</li>
                        </ol>
                     </nav>
                  </div>
               </div>
            </div>
            <div class="nk-block">
               <div class="card overflow-hidden">
                  <form action="{{route('TutorScheduleCalendar')}}" method="GET">
                     <div class="row justify-content-between tableper-row">
                        <div class="col-md-4">
                           <div class="input-group  input-group-md">
                              <label class="input-group-text" for="inputGroupSelect01">Tutors</label>
                              <select name="tutors" class="form-control" id="inputGroupSelect01">
                                 <option value=""> Select tutor</option>
                                 @foreach($tutors as $rowTutor)
                                 <option {{$tutorSearch==$rowTutor->id?"selected":""}} value="{{$rowTutor->id}}"> {{$rowTutor->full_name}}</option>
                                 @endforeach
                              </select>
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="input-group  input-group-md">
                              <label class="input-group-text" for="inputGroupSelect01">Subjects</label>
                              <select name="subjects" class="form-control" id="inputGroupSelect01">
                                 <option value=""> Select Subject</option>
                                 @foreach($subjects as $rowSubjects)
                                 <option {{$subjectsSearch==$rowSubjects->id?"selected":""}}  value="{{$rowSubjects->id}}">
                                 {{$rowSubjects->name."-".$rowSubjects->category_name."(".$rowSubjects->mode.")"}}
                                 </option>
                                 @endforeach
                              </select>
                           </div>
                        </div>
                        <div class="col-md-2">
                           <div class="input-group input-group-md">
                              <input type="submit" class="btn btn-primary" aria-label="Sizing example input" value="Search" aria-describedby="inputGroup-sizing-sm">
                           </div>
                        </div>
                     </div>
                  </form>
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
                  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/main.css"/>
                  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/index.global.min.js'></script>
                  <script>
                     document.addEventListener('DOMContentLoaded', function() {
                             // Get the current date
                     var currentDate = new Date();
                     
                     // Format the date as 'YYYY-MM-DD'
                     var formattedDate = currentDate.toISOString().split('T')[0];
                         var events = new Array();
                         @foreach($jsonticket as $rowTicket)
                             events.push({
                                 title: '{{$rowTicket->subject_name."-".$rowTicket->category_name."-".$rowTicket->mode}}',
                                 start: '{{$rowTicket->date}}',
                                 end: '{{$rowTicket->date}}'
                             });
                         @endforeach
                     var calendarEl = document.getElementById('calendar');
                     
                     var calendar = new FullCalendar.Calendar(calendarEl, {
                     headerToolbar: {
                     left: 'prev,next',
                     center: 'register_date',
                     right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                     },
                     initialDate: formattedDate,
                     navLinks: true, // can click day/week names to navigate views
                     nowIndicator: true,
                     
                     weekNumbers: true,
                     weekNumberCalculation: 'ISO',
                     
                     selectable: true,
                     dayMaxEvents: true, // allow "more" link when too many events
                     events:events,
                     
                     });
                     
                     calendar.render();
                     });
                                                 
                                                 
                  </script>
                  <div id='calendar'></div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection