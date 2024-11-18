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
                        Tutor Reports</h1>
                        <nav>
                          <ol class="breadcrumb breadcrumb-arrow mb-0">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Tutor Reports</a></li>
                          </ol>
                        </nav>
                      </div>
                      
                  </div>
                  <div class="nk-block">
                    <div class="card">
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
                      
                      
                      <form action="{{url("/")}}" method="GET">
                                        <div style="margin-bottom:10px; padding:10px 10px; border-bottom: 2px solid #2e314a;" class="row">
                                                <div class="col-md-2">
                                                    <div class="input-group  input-group-md">
                                                      <label class="input-group-text" for="inputGroupSelect01">Month</label>
                                                      <select id="Month" name="Month" class="form-control">
                                                            <option value="January"> January</option>
                                                            <option value="February"> February</option>
                                                            <option value="March"> March</option>
                                                            <option value="April"> April</option>
                                                            <option value="May"> May</option>
                                                            <option value="June" selected=""> June</option>
                                                            <option value="July"> July</option>
                                                            <option value="August"> August</option>
                                                            <option value="September"> September</option>
                                                            <option value="October"> October</option>
                                                            <option value="November"> November</option>
                                                            <option value="December"> December</option>
                                                    </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-2">
                                                    <div class="input-group  input-group-md">
                                                      <label class="input-group-text" for="inputGroupSelect01">Year</label>
                                                      <select id="Year" name="Year" class="form-control">
                                                                <option value="2023" selected=""> 2023</option>
                                                                <option value="2024"> 2024</option>
                                                                <option value="2025"> 2025</option>
                                                                <option value="2026"> 2026</option>
                                                                <option value="2027"> 2027</option>
                                                                
                                                    </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-2">
                                                    <div class="input-group  input-group-md">
                                                      <label class="input-group-text" for="inputGroupSelect01">Report Type</label>
                                                        <select id="ReportType" name="ReportType" class="form-control">
                                                            <option value="All" selected=""> All</option>
                                                            <option value="Evaluation Report"> Evaluation Report</option>
                                                            <option value="Progressive/Attendance Report"> Progressive/Attendance Report</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                
                                                
                                                
                                                <div class="col-md-2">
                                                        <div class="input-group input-group-md">
                                                          <span class="input-group-text" id="inputGroup-sizing-sm">Search</span>
                                                          <input name="search" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" placeholder="Tutor ID">
                                                        </div>
                                                </div>
                                                <div class="col-md-1">
                                                        <div class="input-group input-group-md">
                                                          <input type="submit" class="btn btn-success" aria-label="Sizing example input" value="Search" aria-describedby="inputGroup-sizing-sm">
                                                        </div>
                                                </div>
                                        </div>
                                    </form>
                      <table class="datatable-init table" data-nk-container="table-responsive table-border">
                                       <thead class="table-dark">
                                          <tr>
                                             <th><span class="overline-title">#</span></th>
                                             <th><span class="overline-title">Tutor ID</span></th>
                                             <th><span class="overline-title">Tturo Name</span></th>
                                             <th><span class="overline-title">Student ID</span></th>
                                             <th><span class="overline-title">Student Name</span></th>
                                             <th><span class="overline-title">Report Type</span></th>
                                             <th><span class="overline-title">Submitted On</span></th>
                                             <th><span class="overline-title">Action</span></th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                           @php
                                            $numbers = 1;
                                           @endphp
                                           @foreach($tutorSubmittedReports as $rows)
                                               @php
                                                    $tutorDetail = DB::table('tutors')->where('id','=',$rows->tutorID)->first();
                                                    $studentDetail = DB::table('students')->where('id','=',$rows->studentID)->first();
                                               @endphp
                                              <tr>
                                                 <td>{{$numbers++}}</td>
                                                 <td>{{$tutorDetail->tutor_id}}</td>
                                                 <td>{{$tutorDetail->full_name}}</td>
                                                 <td>{{$studentDetail->student_id}}</td>
                                                 <td>{{$studentDetail->full_name}}</td>
                                                 <td>{{$rows->reportType}}</td>
                                                 <td>{{$rows->date}}</td>
                                                 <td>
                                                     
                                                    <a href="{{route('viewTutor',$rows->id)}}"><i title="View Tutor Detail" style="border:1px solid #000; border-radius:5px; padding:5px;" class="fa fa-eye"></i> </a>
                                                    <a href="{{route('editTutor',$rows->id)}}"><i title="Edit Tutor" style="border:1px solid #000; border-radius:5px; padding:5px;" class="fa fa-edit"></i> </a>
                                                    <i title="Delete" style="border:1px solid #000; border-radius:5px; padding:5px;" class="fa fa-trash"></i>
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

@endsection

