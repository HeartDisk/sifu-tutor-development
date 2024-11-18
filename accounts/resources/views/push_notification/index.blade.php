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
                        Add Push Notification List
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Mobile App</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Add Push Notification List</li>
                        </ol>
                     </nav>
                  </div>
                  <div class="nk-block-head-content">
                     <ul class="d-flex">
                        <li><a href="{{route('selfpushnotification.create')}}" class="btn btn-md d-md-none btn-primary"><em class="icon ni ni-plus"></em><span>Add</span></a></li>
                        <li><a href="{{route('selfpushnotification.create')}}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Add Push Notification</span></a></li>
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
                              <th>Page</th>
                              <th>Subject</th>
                              <th>Message</th>
                              <th>Push Time</th>
                              <th>Push Type</th>
                              <th>Push Date</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           @php
                           $numbers = 1;
                           @endphp
                           @foreach($notifications as $notification)
                           <tr>
                              <td>{{$numbers++}}</td>
                              <td>{{$notification->page}}</td>
                              <td>{{\Str::limit($notification->subject ,10)}}</td>
                              <td>{{\Str::limit($notification->message ,10)}}</td>
                              <td>{{$notification->time}}</td>
                              <td>{{$notification->type}}</td>
                              <td>{{$notification->date}}</td>
                              <td>
                                 <a class="dtable-cbtn bt-view dtb-tooltip" dtb-tooltip="View" href="{{url('selfpushnotification/'.$notification->id)}}"> <i class="fa fa-eye"></i></a>
                                 <a class="dtable-cbtn bt-edit dtb-tooltip" dtb-tooltip="Edit" href="{{url('selfpushnotification/'.$notification->id.'/edit')}}"><i class="fa fa-edit"></i></a>
                                 <a class="dtable-cbtn bt-delete dtb-tooltip" dtb-tooltip="Delete" onclick="return confirm('Are you sure you want to delete this notification?');" href="{{url('selfpushnotification/delete',$notification->id)}}"><i class="fa fa-trash"></i></a>
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