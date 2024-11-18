@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
   <div class="fluid-container">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head flex-wrap gap g-2">
               <div class="nk-block-head-content">
                  <h2 class="nk-block-title">
                     Tutor Not Submit Report
                  </h2>
                  <nav>
                     <ol class="breadcrumb breadcrumb-arrow mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Followup</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tutor Not Submit Report</li>
                     </ol>
                  </nav>
               </div>
            </div>
            <div class="nk-block">
               <div class="card">
                   <div class="card-body">
                     <table class="datatable-init table" data-nk-container="table-responsive">
                        <thead>
                           <tr>
                              <th>#</th>
                              <th>Tutor</th>
                              <th>Tutor Phone No.</th>
                              <th>Student</th>
                              <th>Related Subject</th>
                              <th>Expected Report</th>
                              <th>Notified Date / Time</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach($class_attendeds as $rowClassSchedules)
                           <tr>
                              <td>{{$rowClassSchedules->id}}</td>
                              <td>{{$rowClassSchedules->tutorName}}</td>
                              <td>{{$rowClassSchedules->tutorPhone}}</td>
                              <td>{{$rowClassSchedules->studentName}}</td>
                              <td>{{$rowClassSchedules->productName}}</td>
                               <td>Evaluation Report</td>
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