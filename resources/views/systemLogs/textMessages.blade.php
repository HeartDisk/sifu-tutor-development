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
                        Text Messages
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">System Logs</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Text Messages</li>
                        </ol>
                     </nav>
                  </div>
               </div>
            </div>
            <div class="nk-block">
               <div class="card">
                  <div class="card-body">
                     <table class="datatable-init table" data-nk-container="table-responsive table-border">
                        <thead>
                           <tr>
                              <th>Receipent No.</th>
                              <th>Message</th>
                              <th>Status</th>
                              <th>Send Date Time</th>
                              <th>Channel</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach($messages as $message)
                           <tr>
                              <td>{{$message->recipient}}</td>
                              <td>{{$message->message}}</td>
                              <td>{{$message->status}}</td>
                              <td>{{$message->created_at}}</td>
                              <td>SMS</td>
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