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
                        Notification List
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Mobile App</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Notification List</li>
                        </ol>
                     </nav>
                  </div>
                  <div class="nk-block-head-content">
                  <ul class="d-flex">
                     @can("mobile-notification-add")
                     <li><a href="{{route('addNotification')}}" class="btn btn-md d-md-none btn-primary"><em class="icon ni ni-plus"></em><span>Add</span></a></li>
                     @endcan
                     <li><a href="{{route('addNotification')}}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Add Notification</span></a></li>
                  </ul>
               </div>
               </div>
            </div>
            <div class="nk-block">
               <div class="card">
                  <div class="card-body">
                     <table class="datatable-init table" data-nk-container="table-responsive">
                        <thead>
                           <tr>
                              <th>#</th>
                              <th>Tutor ID</th>
                              <th>Student Full Name</th>
                              <th>Subject</th>
                              <th>Message</th>
                              <th>Notify Date</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           @php
                           $numbers = 1;
                           @endphp
                           @foreach($notifications as $rows)
                           @if($rows->is_deleted == 0)
                           <tr>
                              <td>{{$numbers++}}</td>
                              <td>
                                 @if($rows->tutorID)
                                 @php
                                 $tutorName = DB::table('tutors')->where('id','=',$rows->tutorID)->first();
                                 @endphp
                                 {{$tutorName->tutor_id}}
                                 @endif
                              </td>
                              <td>
                                 @php
                                 $studentName = DB::table('students')->where('id','=',$rows->studentID)->first();
                                 @endphp
                                 {{$studentName->full_name}}
                              </td>
                              <td>{{$rows->subjectID}}
                                 @php
                                 $subjectName = DB::table('products')->where('id','=',$rows->subjectID)->first();
                                 @endphp
                                 @if($subjectName)
                                 {{$subjectName->name}}
                                 @endif
                              </td>
                              <td>{{$rows->message}}</td>
                              <td>{{$rows->ProgressReportMonth}}</td>
                              @can("mobile-notification-delete")
                              <td>
                                 <a class="dtable-cbtn bt-delete dtb-tooltip" dtb-tooltip="Delete Notification" onclick="return confirm('Are you sure you want to delete this Notification ?');" href="{{route('deleteNotification',$rows->id)}}"><i class="fa fa-trash"></i></a>
                              </td>
                              @endcan
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