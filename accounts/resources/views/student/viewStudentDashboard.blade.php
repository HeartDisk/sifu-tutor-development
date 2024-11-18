@extends('layouts.main')
@section('content')
<div class="nk-content sifu-dashboard-page">
   <div class="container-fluid">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head">
                <div class="nk-block-head-between flex-wrap gap g-2">
                   <div class="nk-block-head-content">
                      <h2 class="nk-block-title">
                         Student Dashboard
                      </h2>
                      <nav>
                         <ol class="breadcrumb breadcrumb-arrow mb-0">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Student List</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Student Dashboard</li>
                         </ol>
                      </nav>
                   </div>
                   <div class="nk-block-head-content">
                      <ul class="d-flex">
                         <li><a href="{{route('editStudent',$student->id)}}" class="btn btn-md d-md-none btn-primary"><span>Edit Profile</span></a></li>
                         <li><a href="{{route('editStudent',$student->id)}}" class="btn btn-primary d-none d-md-inline-flex"><span>Edit Student Profile</span></a></li>
                      </ul>
                   </div>
                </div>
            </div>

            <div class="nk-block-head-between flex-wrap gap g-2 align-items-start">
               <div class="col-4">
                  <div class="card">
                     <div class="card-body">
                        <div class="dcard-status">
                            <div class="col-md-8">
                               <img src="{{asset('public/customer.png')}}">
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                        <div class="bio-block">
                           <ul class="list-group list-group-borderless small">
                              <li class="list-group-item">
                                 <span class="image"><img src="{{asset('public/id.png')}}"></span>
                                 <span class="title">Account ID:</span>
                                 <span class="text">{{$student->uid}}</span>
                              </li>
                              <li class="list-group-item">
                                 <span class="image"><img src="{{asset('public/customer-ui.png')}}"></span>
                                 <span class="title">Full Name:</span>
                                 <span class="text">{{$student->full_name}}</span>
                              </li>
                              <li class="list-group-item">
                                 <span class="image"><img src="{{asset('public/gender.png')}}"></span>
                                 <span class="title">Gender:</span>
                                 <span class="text">{{$student->gender}}</span>
                              </li>
                              <li class="list-group-item">
                                 <span class="image"><img src="{{asset('public/location.png')}}"></span>
                                 <span class="title">Address:</span>
                                 <span class="text">{{$customer->address1}}</span>
                              </li>
                           </ul>
                        </div>
                     </div>
                  </div>
                  <div class="card mt-2 dashboard-subjects-view">
                     <div class="card-body">
                        <div class="bio-block">
                           <h6>Subjects</h6>
                           <ul class="d-flex flex-wrap gap gx-1">
                              @foreach($subjects as $rowSubjects)
                              @php
                              $subject = DB::table('products')->where('id','=',$rowSubjects->subject)->first();
                              @endphp
                              <li><a href="javascript:void(0)" class="badge text-bg-secondary-soft">{{$subject->name}}</a></li>
                              @endforeach
                           </ul>
                        </div>
                     </div>
                  </div>
               </div>   

               <div class="col-8 tabs-7">
                  <ul class="tabs">
                     <li class="active"><a href="#tab25">Overview</a></li>
                     <li><a href="#tab26">Tickets</a></li>
                     <li><a href="#tab27">Classes Schedules</a></li>
                     @can("student-invoice-view")
                     <li><a href="#tab28">Invoices</a></li>
                     @endcan
                  </ul>
                  <section class="tab_content_wrapper">
                     <article class="tab_content" id="tab25">
                        <div class="card">
                           <div class="card-body">
                               <div class="bio-block">
                                  <h3 class="bio-block-title">Analytics</h3>
                               </div>
                            </div>
                           <div class="card-body">
                              <div class="bio-block">
                                 <h6>Subject Subscription Hours</h6>
                                 <ul class="sifu-view-ulli">
                                    @foreach($subjects as $rowSubjects)
                                    @php
                                    $subject = DB::table('products')->where('id','=',$rowSubjects->subject)->first();
                                    $hours = DB::table('class_attendeds')->where('studentID','=',$student->id)->where('status','=','attended')->where('subjectID','=',$rowSubjects->subject)->sum('totalTime');
                                    @endphp
                                    <li>
                                       <span>{{$subject->name}} </span>
                                       <span>@php
                                       echo number_format($hours, 2, ".", "");
                                       @endphp</span>
                                    </li>
                                    @endforeach
                                 </ul>

                                 <h6>Active Month</h6>
                                 @php
                                 $ifExist = DB::table('class_attendeds')->where('studentID','=',$student->id)->where('status','=','attended')->get();
                                 if(count($ifExist)>0){
                                 $results = DB::table('class_attendeds')->where('studentID','=',$student->id)->where('status','=','attended')
                                 ->selectRaw('count(*) as total, sum(totalPrice) as totalPrice, sum(totalTime) as totalTime')
                                 ->get();
                                 }else{
                                 $results = [];
                                 }
                                 @endphp
                                 <div class="table-responsive">
                                    <table class="table sifu-view-dashtable">
                                       <tr>
                                          <th>Year</th>
                                          <th>Month</th>
                                          <th>Total Scheduled Classes</th>
                                          <th>Total Price Scheduled Classes</th>
                                          <th>Total Time Scheduled Classes</th>
                                       </tr>
                                       @foreach($results as $rowResult)
                                       <tr>
                                          <td></td>
                                          <td></td>
                                          <td>{{$rowResult->total}}</td>
                                          <td>{{$rowResult->totalPrice}}</td>
                                          <td>@php echo number_format($rowResult->totalTime, 2, ".", ""); @endphp</td>
                                       </tr>
                                       @endforeach
                                    </table> 
                                 </div> 
                              </div>
                           </div>
                        </div>
                     </article>

                     <article class="tab_content" id="tab26">
                        <div class="card">
                           <div class="card-body">
                              <div class="bio-block">
                                 <h6>Tickets</h6>
                                 @php
                                 $studentTickets = DB::table('job_tickets')->where('student_id','=',$student->id)->get();
                                 @endphp
                                 <div class="table-responsive">
                                    <table class="table sifu-view-dashtable">
                                       <tr>
                                          <th>Ticket ID</th>
                                          <th>Subject Name</th>
                                          <th>Mode</th>
                                       </tr>
                                       @foreach($studentTickets as $rowTickets)
                                       <tr>
                                          <td>
                                             <a target="_blank" href="{{route('viewTicket',$rowTickets->id)}}">{{$rowTickets->uid}}</a>
                                          </td>
                                          <td>
                                             @php
                                             $subject = DB::table('products')->where('id','=',$rowTickets->subjects)->first();
                                             @endphp
                                             {{$subject->name}}
                                          </td>
                                          <td>{{$rowTickets->mode}}</td>
                                       </tr>
                                       @endforeach
                                    </table>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </article>

                     <article class="tab_content" id="tab27">
                        <div class="card">
                           <div class="card-body">
                              <div class="bio-block">
                                 <h6>Class Schedules</h6>
                                 @php
                                 $studentTickets = DB::table('class_schedules')->where('studentID','=',$student->id)->get();
                                 @endphp
                                 <div class="table-responsive">
                                    <table class="table sifu-view-dashtable">
                                       <tr>
                                          <th>Ticket ID</th>
                                          <th>Subject Name</th>
                                          <th>Date</th>
                                          <th>Start Time</th>
                                          <th>End Time</th>
                                          <th>Total Time</th>
                                       </tr>
                                       @foreach($studentTickets as $rowTickets)
                                       <tr>
                                          <td>
                                             <a target="_blank" href="{{route('viewTicket',$rowTickets->id)}}">{{$rowTickets->id}}</a>
                                          </td>
                                          <td>
                                             @php
                                             $subject = DB::table('products')->where('id','=',$rowTickets->subjectID)->first();
                                             @endphp
                                             {{$subject->name}}
                                          </td>
                                          <td>{{$rowTickets->date}}</td>
                                          <td>{{$rowTickets->startTime}}</td>
                                          <td>{{$rowTickets->endTime}}</td>
                                          <td>{{$rowTickets->totalTime}}</td>
                                       </tr>
                                       @endforeach
                                    </table>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </article>

                     @can("student-invoice-view")
                     <article class="tab_content" id="tab28">
                        <div class="card">
                           <div class="card-body">
                              <div class="bio-block">
                                 <h6>Invoice List</h6>
                                 <div class="table-responsive">
                                    <table class="table sifu-view-dashtable">
                                       <tr>
                                          <th> Ticket ID </th>
                                          <th> Subjects </th>
                                       </tr>
                                       @php
                                       $studentTickets = DB::table('studentinvoices')->where('isPaid','=','unpaid')->where('studentID','=',$student->id)->get();
                                       @endphp
                                       @if($studentTickets)
                                       @foreach($studentTickets as $rowTickets)
                                       <tr>
                                          <td> <a target="_blank" href="{{route('viewTicket',$rowTickets->id)}}">{{$rowTickets->id}}</a> </td>
                                          <td>
                                          @php
                                          $subjectDetail = DB::table('products')->where('id','=',$rowTickets->subjectID)->first();
                                          echo $subjectDetail->name;
                                          @endphp
                                          </td>
                                       </tr>
                                       @endforeach
                                       @endif
                                    </table>
                                 </div>
                              </div>
                           </div>
                           <div class="card-body">
                              <div class="bio-block">
                                 <h6>Payment List</h6>
                                 <div class="table-responsive">
                                    <table class="table sifu-view-dashtable">
                                       <tr>
                                          <th>Ticket ID</th>
                                          <th>Subjects</th>
                                          <th>Amount</th>
                                       </tr>
                                       @php
                                       $tickets = DB::table('studentinvoices')->where('isPaid','=','paid')->where('studentID','=',$student->id)->get();
                                       @endphp
                                       @if($tickets)
                                       @foreach($studentTickets as $rowTickets)
                                       <tr>
                                          <td><a target="_blank" href="{{ url('/viewTicket/' . $rowTickets->ticketID) }}">{{ $rowTickets->ticketID }}</a></td>
                                          <td>
                                          @php
                                          $subjectDetail = DB::table('products')->where('id','=',$rowTickets->subjectID)->first();
                                          echo $subjectDetail->name;
                                          @endphp
                                          </td>
                                          <td>
                                          </td>
                                       </tr>
                                       @endforeach
                                       @endif
                                    </table>
                                 </div>
                              </div>
                           </div>

                        </div>
                     </article>
                     @endcan
                  </section>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery"></script>
<script src="{{asset('public/css/jQueryTab.js')}}"></script>
<script type="text/javascript">
   $('.tabs-7').jQueryTab({
       initialTab: 1,
       tabInTransition: 'fadeIn',
       tabOutTransition: 'fadeOut',
       cookieName: 'active-tab-7'
   });
</script>
@endsection