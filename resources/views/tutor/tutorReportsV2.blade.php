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
                        Student Evaluation Report
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Tutors</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Student Evaluation Report</li>
                        </ol>
                     </nav>
                  </div>
                  <div class="nk-block-head-content">
                     <ul class="d-flex">
                        <!--<li><a href="{{route('addTutor')}}" class="btn btn-md d-md-none btn-primary"><em class="icon ni ni-plus"></em><span>Add</span></a></li>-->
                        <!--@can("tutor-add")-->
                        <!--<li><a href="{{route('addTutor')}}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Add Tutor</span></a></li>-->
                        <!--@endcan-->
                     </ul>
                  </div>
               </div>
            </div>
            <div class="nk-block">
               <div class="card overflow-hidden">
                <div class="card-body">
                    <form action="{{route('TutorReportsV2')}}" method="GET">
                       @csrf
                       <input type="hidden" name="TutorReportsV2Value" value="1"/>
                       <div class="row justify-content-between tableper-row">
                          <!--<div class="col-md-2">-->
                          <!--   <div class="input-group  input-group-md">-->
                          <!--      <label class="input-group-text" for="inputGroupSelect01">Month</label>-->
                          <!--      <select id="Month" name="Month" class="form-control">-->
                          <!--         <option value="January"> January</option>-->
                          <!--         <option value="February"> February</option>-->
                          <!--         <option value="March"> March</option>-->
                          <!--         <option value="April"> April</option>-->
                          <!--         <option value="May"> May</option>-->
                          <!--         <option value="June" selected=""> June</option>-->
                          <!--         <option value="July"> July</option>-->
                          <!--         <option value="August"> August</option>-->
                          <!--         <option value="September"> September</option>-->
                          <!--         <option value="October"> October</option>-->
                          <!--         <option value="November"> November</option>-->
                          <!--         <option value="December"> December</option>-->
                          <!--      </select>-->
                          <!--   </div>-->
                          <!--</div>-->
                          <!--<div class="col-md-2">-->
                          <!--   <div class="input-group  input-group-md">-->
                          <!--      <label class="input-group-text" for="inputGroupSelect01">Year</label>-->
                          <!--      <select id="Year" name="Year" class="form-control">-->
                          <!--         <option value="2023" selected=""> 2023</option>-->
                          <!--         <option value="2024"> 2024</option>-->
                          <!--         <option value="2025"> 2025</option>-->
                          <!--         <option value="2026"> 2026</option>-->
                          <!--         <option value="2027"> 2027</option>-->
                          <!--      </select>-->
                          <!--   </div>-->
                          <!--</div>-->
                          <div class="col-md-5">
                             <div class="input-group  input-group-md">
                                <label class="input-group-text" for="inputGroupSelect01">Report Type</label>
                                <select id="ReportType" name="ReportType" class="form-control">
                                   <option value="" selected=""> All</option>
                                   <option value="Student Evaluation Report"> Student Evaluation Report</option>
                                   <option value="Progressive/Attendance Report"> Progressive/Attendance Report</option>
                                </select>
                             </div>
                          </div>
                          <div class="col-md-5">
                             <div class="input-group input-group-md">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Search</span>
                                <input name="search" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" placeholder="Tutor ID">
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
                    <table class="datatable-init table" data-nk-container="table-responsive">
                       <thead>
                          <tr>
                             <th>#</th>
                             <th>TutorId</th>
                             <th>Tutor</th>
                             <th>StudentId</th>
                             <th>Student</th>
                             <th>Report Type</th>
                             <th>Submitted On</th>
                             <th>Action</th>
                          </tr>
                       </thead>
                       <tbody>
                          @php
                          $number = 1;
                          @endphp
                          @foreach($tutorReport as $row)
                          <tr>
                             <th>{{$number++}}</th>
                             <td>{{$row->tutorID}}</td>
                             <td>{{$row->tutorName}}</td>
                             <td>{{$row->studentID}}</td>
                             <td>{{$row->studentName}}</td>
                             <td>{{$row->reportType}}</td>
                             <td>{{$row->created_at}}</td>
                             <td>
                                <a href="{{route('TutorReportsV2View',$row->id)}}" class="dtable-cbtn bt-view dtb-tooltip" dtb-tooltip="Details"><span class="fa fa-eye"></span></a>
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
@endsection
