@extends('layouts.main')

@section('content')

<div class="nk-content">
            <div class="fluid-container">
              <div class="nk-content-inner">
                <div class="nk-content-body">
                  <div class="nk-block-head">
                    <div class="nk-block-head-between flex-wrap gap g-2">
                      <div class="nk-block-head-content">
                        <h2 class="nk-block-title">
                        Daily Application Ticket</h1>
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
                        <div class="row">
                            <div class="col-3">
                                <label for="city" class="form-label">State</label>
                                <select id="State" name="State" class="form-control">
                                    <option value="All" selected="">All</option>
                                    @foreach($states as $state)
                                    <option value="{{$state->name}}"> {{$state->name}}</option>
                                    @endforeach
                                
                                </select>
                            </div>
                            
                            
                            <div class="col-3">
                                <label for="city" class="form-label">City</label>
                                <select id="State" name="State" class="form-control">
                                    <option value="All" selected="">All</option>
                                   @foreach($cities as $city)
                                    <option value="{{$city->name}}"> {{$city->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            
                            <div class="col-3">
                                <label for="city" class="form-label">Subject</label>
                                <select id="State" name="State" class="form-control">
                                    <option value="All" selected="">All</option>
                                     @foreach($subjects as $subject)
                                    <option value="{{$subject->name}}"> {{$subject->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                             
                            <div class="col-3">
                                <label for="city" class="form-label">Date</label>
                               <input type="date" value={{ date('Y-m-d')}} class="form-control" name="_date">
                            </div>
                            
                            
                        </div>
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
                        <div class="row">
                            <div class="col-4">
                                
                            </div>
                        </div>
                      <table class="datatable-init table" data-nk-container="table-responsive">
                         
                        <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Total Ticket Created</th>
                                        <th scope="col">Total Tutor Applied</th>
                                        <th scope="col">Total Tutor Applied on Same Day</th>
                                        <th scope="col">Subject</th>
                                        <th scope="col">State</th>
                                        <th scope="col">City</th>
                                    </tr>
                                </thead>
                        <tbody>
                            @foreach($subjects as $rowSubject)
                                @php
                                    
                                    $job_tickets = DB::table("job_tickets")
                                                        ->where(["register_date" => \Carbon\Carbon::now()->toDateString()])
                                                        ->where("application_status", "!=", "no-application")
                                                        ->get();
                                 
                                 
                                    $appliedSubject = DB::table('student_subjects')
                                                        ->where('subject', $rowSubject->id)
                                                        ->whereIn('ticket_id', $job_tickets->pluck('id')->toArray()) // Filter job_tickets by their IDs
                                                        ->count();
                                  
                                     
                                   
                                    $tutorApplied = DB::table('tutoroffers')->
                                                    where('subject_id','=',$rowSubject->id)->
                                                    whereIn('ticketID', $job_tickets->pluck('id')->toArray())->
                                                    where('tutorID','!=',NULL)->count();
                                                    
                                 
                                                    
                                                    
                                    
                                    $tutorAppliedSameDay = DB::table('tutoroffers')
                                                        ->join("job_tickets", "tutoroffers.ticketID", "=", "job_tickets.id")
                                                        ->whereIn('ticketID', $job_tickets->pluck('id')->toArray())
                                                        ->where('subject_id', '=', $rowSubject->id)
                                                        ->where('tutorID', '!=', NULL)
                                                        ->whereRaw('DATE(tutoroffers.created_at) = DATE(job_tickets.created_at)')
                                                        ->count();
                                                        
                                    $job_ticket_data= $tutorApplied = DB::table('tutoroffers')->
                                                    join('job_tickets','tutoroffers.ticketID','=','job_tickets.id')->
                                                    where('subject_id','=',$rowSubject->id)->
                                                    whereIn('ticketID', $job_tickets->pluck('id')->toArray())->
                                                    where('tutorID','!=',NULL)->first();
                                                    
                                                    
                                                  
                                  
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
                                        <td>{{isset($tutorApplied)?isset($tutorApplied):0}}</td>
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

@endsection

