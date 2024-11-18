@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
   <div class="fluid-container">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head flex-wrap gap g-2">
               <div class="nk-block-head-content">
                  <h2 class="nk-block-title">
                     Tutor Never Schedule Class
                  </h2>
                  <nav>
                     <ol class="breadcrumb breadcrumb-arrow mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Followup</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tutor Never Schedule Class</li>
                     </ol>
                  </nav>
               </div>
            </div>
            <div class="nk-block">
               <div class="card">
                   <div class="card-body">
                       <form action="{{ route('TutorNeverScheduleClass') }}" method="GET">
                           @csrf
                           <div class="row justify-content-between tableper-row">
                               <input name="classScheduleSearch" value="1" type="hidden">
                               <div class="col-md-4">
                                   <div class="input-group input-group-md">
                                       <span class="input-group-text" id="inputGroup-sizing-sm">Search by Name or ID</span>
                                       <input name="searchQuery" value="{{ request('searchQuery') }}" type="text" class="search form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                                   </div>
                               </div>
                               <div class="col-md-2">
                                   <div class="input-group input-group-md justify-content-end">
                                       <input type="submit" class="btn btn-primary" value="Search">
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
{{--                            <th>Student</th>--}}
{{--                            <th>Subject</th>--}}
                            <!--<th>Class on</th>-->
                         </tr>
                      </thead>
                      <tbody>
                         @foreach($class_schedules as $rowClassSchedules)

                         <tr>
                            <td>{{$rowClassSchedules->id}}</td>
                            <td>{{$rowClassSchedules->full_name}} </td>
                            <td>{{$rowClassSchedules->phoneNumber}}</td>
{{--                            <td></td>--}}
{{--                            <td></td>--}}

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
