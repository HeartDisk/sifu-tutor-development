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
                        Daily Application Ticket
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Operation Report</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Daily Application Ticket</li>
                        </ol>
                     </nav>
                  </div>
               </div>
            </div>
            <div class="nk-block">
               <div class="card">
                  <div class="card-body">
                      <form action="{{ route('dailyTicketApplication') }}" method="GET">
                          <div class="row tableper-row flex-row align-items-center justify-content-between">
{{--                              <div class="col-md-3">--}}
{{--                                  <div class="input-group input-group-md">--}}
{{--                                      <label for="state" class="input-group-text">State</label>--}}
{{--                                      <select id="state" name="state" class="form-control">--}}
{{--                                          <option value="All">All</option>--}}
{{--                                          @foreach($states as $state)--}}
{{--                                              <option value="{{ $state->name }}">{{ $state->name }}</option>--}}
{{--                                          @endforeach--}}
{{--                                      </select>--}}
{{--                                  </div>--}}
{{--                              </div>--}}
{{--                              <div class="col-md-3">--}}
{{--                                  <div class="input-group input-group-md">--}}
{{--                                      <label for="city" class="input-group-text">City</label>--}}
{{--                                      <select id="city" name="city" class="form-control">--}}
{{--                                          <option value="All">All</option>--}}
{{--                                          @foreach($cities as $city)--}}
{{--                                              <option value="{{ $city->name }}">{{ $city->name }}</option>--}}
{{--                                          @endforeach--}}
{{--                                      </select>--}}
{{--                                  </div>--}}
{{--                              </div>--}}
                              <div class="col-md-3">
                                  <div class="input-group input-group-md">
                                      <label for="subject" class="input-group-text">Subject</label>
                                      <select id="subject" name="subject" class="form-control">
                                          <option value="All">All</option>
                                          @foreach($subjects as $subject)
                                              <option value="{{ $subject->name }}">{{ $subject->name }}</option>
                                          @endforeach
                                      </select>
                                  </div>
                              </div>
                              <div class="col-md-3">
                                  <div class="input-group input-group-md">
                                      <label for="_date" class="input-group-text">Date</label>
                                      <input type="date" class="form-control" name="_date" value="{{ $date }}">
                                  </div>
                              </div>
                              <div class="col-md-2">
                                  <div class="input-group input-group-md">
                                      <input type="submit" class="btn btn-primary" value="Search">
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
                     <table class="datatable-init table" data-nk-container="table-responsive">
                        <thead>
                           <tr>
                              <th>Total Ticket Created</th>
                              <th>Total Tutor Applied</th>
                              <th>Total Tutor Applied on Same Day</th>
                              <th>Subject</th>
                              <th>State</th>
                              <th>City</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach($subjects as $rowSubject)
                           @php
                           $appliedSubject = DB::table('student_subjects')
                           ->where('subject', $rowSubject->id)
                           ->whereIn('ticket_id', $job_tickets->pluck('id')->toArray()) // Filter job_tickets by their IDs
                           ->count();
                           $tutorApplied = DB::table('tutoroffers')
                           ->where('subject_id', $rowSubject->id)
                           ->whereIn('ticketID', $job_tickets->pluck('id')->toArray())
                           ->where('tutorID','!=',NULL)->count();
                           $tutorAppliedSameDay = DB::table('tutoroffers')
                           ->join("job_tickets", "tutoroffers.ticketID", "=", "job_tickets.id")
                           ->whereIn('ticketID', $job_tickets->pluck('id')->toArray())
                           ->where('subject_id', $rowSubject->id)
                           ->where('tutorID', '!=', NULL)
                           ->whereRaw('DATE(tutoroffers.created_at) = DATE(job_tickets.created_at)')
                           ->count();
                           $job_ticket_data = DB::table('tutoroffers')
                           ->join('job_tickets','tutoroffers.ticketID','=','job_tickets.id')
                           ->where('subject_id', $rowSubject->id)
                           ->whereIn('ticketID', $job_tickets->pluck('id')->toArray())
                           ->where('tutorID','!=',NULL)->first();
                           if(isset($job_ticket_data->id))
                           {
                           $job_ticket = DB::table("job_tickets")->where("id",$job_ticket_data->id)->first();
                           $student=DB::table("students")->where("id",$job_ticket->student_id)->first();
                           $customer = DB::table("customers")->where("id",$student->customer_id)->first();
                           $stateName = DB::table("states")->where("id", $customer->state)->value('name');
                           $cityName = DB::table("cities")->where("id", $customer->city)->value('name');
                           }
                           @endphp
                           @if(isset($job_ticket_data->id))
                           <tr>
                              <td>{{$appliedSubject}}</td>
                              <td>{{$tutorApplied}}</td>
                              <td>{{$tutorAppliedSameDay}}</td>
                              <td>{{$rowSubject->name."(".$rowSubject->category_name.")"."-".$rowSubject->mode}}</td>
                              <td>{{$stateName}}</td>
                              <td>{{$cityName}}</td>
                           </tr>
                           @endif
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
