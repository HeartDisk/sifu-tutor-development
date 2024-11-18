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
                        User Activities
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">System Logs</a></li>
                           <li class="breadcrumb-item active" aria-current="page">User Activities</li>
                        </ol>
                     </nav>
                  </div>
               </div>
            </div>
            <div class="nk-block">
               <div class="card">
                  <div class="card-body">
                     <table class="datatable-init table" data-nk-container="table-responsive">
                        <thead>
                           <tr>
                              <th>User</th>
                              <th>Module</th>
                              <th>Action</th>
                              <th>Occurance Date Time</th>
                           </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $dt)
                            <tr>
                                <td>{{$dt->user}}</td>
                                <td>{{$dt->module}}</td>
                                <td>{{$dt->action}}</td>
                                <td>{{$dt->created_at}}</td>

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
