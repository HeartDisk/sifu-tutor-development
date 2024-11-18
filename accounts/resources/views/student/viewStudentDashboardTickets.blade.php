@extends('layouts.main')

@section('content')

<style>
    .card{
        box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;
    }
</style>
<br/><br/><br/>
<div class="nk-content">
  <div class="container-fluid">
    <div class="nk-content-inner">
      <div class="nk-content-body">
        <div class="nk-block-head">
          <div class="nk-block-head">
            <div class="nk-block-head">
            <div class="nk-block-head-between flex-wrap gap g-2 align-items-start">
              <div class="nk-block-head-content">
                <div class="d-flex flex-column flex-md-row align-items-md-center">
                  <div class="media media-huge media-circle">
                    <img src="{{url('public/template/images/avatar/a.jpg')}}" class="img-thumbnail" alt="">
                  </div>
                  <div class="mt-3 mt-md-0 ms-md-3">
                    <h3 class="title mb-1">{{$student->full_name}}</h3>
                    <ul class="nk-list-option pt-1">
                      <li>
                        <em class="icon ni ni-map-pin"></em>
                        <span class="small">{{$student->address1}}</span>
                      </li>
                      <li>
                        <em class="icon ni ni-building"></em>
                        <span class="small"></span>
                      </li>
                      <li>
                        <em class="icon ni ni-building"></em>
                        <span class="small"></span>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="nk-block-head-content">
                <div class="d-flex gap g-3">
                  <div class="gap-col">
                    <div class="box-dotted py-2">
                      <div class="d-flex align-items-center">
                        <div class="h4 mb-0">Account ID</div>
                        <span class="change up ms-1 small">
                          <em class="icon ni ni-arrow-down"></em>
                        </span>
                      </div>
                      <div class="smaller">{{$student->uid}}</div>
                    </div>
                  </div>
                  <div class="gap-col">
                    <div class="box-dotted py-2">
                      <div class="d-flex align-items-center">
                        <div class="h4 mb-0">Customer / Parent:</div>
                        <span class="change up ms-1 small">
                          <em class="icon ni ni-arrow-up"></em>
                        </span>
                      </div>
                      <div class="smaller">{{$customer->full_name}}</div>
                    </div>
                  </div>
                  
                  <div class="gap-col">
                    <div class="box-dotted py-2">
                      <div class="d-flex align-items-center">
                        <div class="h4 mb-0">Parent Email</div>
                        <span class="change up ms-1 small">
                          <em class="icon ni ni-arrow-up"></em>
                        </span>
                      </div>
                      <div class="smaller">{{$customer->email}}</div>
                    </div>
                  </div>
                  
                  <div class="gap-col">
                    <div class="box-dotted py-2">
                      <div class="d-flex align-items-center">
                        <div class="h4 mb-0">Parent Addess</div>
                        <span class="change up ms-1 small">
                          <em class="icon ni ni-arrow-up"></em>
                        </span>
                      </div>
                      <div class="smaller">
                                {{$customer->address1}}
                      </div>
                    </div>
                  </div>
                  
                  <div class="gap-col">
                    <div class="box-dotted py-2">
                      <div class="d-flex align-items-center">
                        <div class="h4 mb-0">Unpaid INV</div>
                        <span class="change up ms-1 small">
                          <em class="icon ni ni-arrow-up"></em>
                        </span>
                      </div>
                      <div class="smaller">
                                
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          </div>
          <div class="nk-block-head-between gap g-2">
            <div class="gap-col">
              <ul class="nav nav-pills nav-pills-border gap g-3" role="tablist">
                <li class="nav-item" role="presentation">
                  <a href="{{route('studentDashboard',$student->id)}}" class="nav-link"> Overview </a>
                </li>
                <li class="nav-item" role="presentation">
                  <a href="{{route('studentDashboardTickets',$student->id)}}" class="nav-link active" > Tickets </a>
                </li>
                <li class="nav-item" role="presentation">
                  <a href="{{route('studentDashboardClassSchedules',$student->id)}}" class="nav-link" > Classes Schedules </a>
                </li>
                <li class="nav-item" role="presentation">
                  <a href="{{route('studentDashboardInvoices',$student->id)}}" class="nav-link" > Invoices </a>
                </li>
              </ul>
            </div>
            <div class="gap-col">
              <ul class="d-flex gap g-2">
                <li class="d-none d-md-block">
                  <a href="{{route('editStudent',$student->id)}}" class="btn btn-soft btn-primary">
                    <em class="icon ni ni-edit"></em>
                    <span>Edit Profile</span>
                  </a>
                </li>
                <li class="d-md-none">
                  <a href="{{route('editStudent',$student->id)}}" class="btn btn-soft btn-primary btn-icon">
                    <em class="icon ni ni-edit"></em>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="nk-block">
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane show active" id="tab-1" tabindex="0" role="tabpanel">
              <div class="card card-gutter-md">
                <div class="card-row card-row-lg col-sep col-sep-lg">
                  <div class="card-aside">
                    <div class="card-body">
                      
                      <div class="bio-block">
                        <h4 class="bio-block-title mb-2">Subjects</h4>
                        <ul class="d-flex flex-wrap gap gx-1">
                            
                            @foreach($subjects as $rowSubjects)
                                @php
                                    $subject = DB::table('products')->where('id','=',$rowSubjects->subject)->first();
                                @endphp
                                <li>
                                    <a href="#" class="badge text-bg-secondary-soft">{{$subject->name}}</a>
                                </li>
                            
                            
                            
                            @endforeach
                          
                        </ul>
                      </div>
                      
                    </div>
                  </div>
                  <div class="card-content col-sep">
                    <div class="card-body">
                      <div class="bio-block">
                        <h4 class="bio-block-title">Tickets</h4>
                        @php
                            $studentTickets = DB::table('job_tickets')->where('student_id','=',$student->id)->get();
                        @endphp
                        
                        <div class="row">
                            <table class="table table-strip">
                                <tr>
                                    <td>Ticket ID</td>
                                    <td>Subject Name</td>
                                    <td>Mode</td>
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
                        <!--<div class="row g-gs">-->
                        <!--  <div class="col-lg-6">-->
                        <!--    <div class="small">Designation:</div>-->
                        <!--    <h5 class="small">Node.js Developer</h5>-->
                        <!--  </div>-->
                        <!--  <div class="col-lg-6">-->
                        <!--    <div class="small">Website:</div>-->
                        <!--    <h5 class="small">www.softnio.com</h5>-->
                        <!--  </div>-->
                        <!--</div>-->
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
