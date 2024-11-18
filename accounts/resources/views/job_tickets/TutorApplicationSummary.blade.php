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
                       Tutor Application Summary
                    </h2>
                    <nav>
                       <ol class="breadcrumb breadcrumb-arrow mb-0">
                          <li class="breadcrumb-item"><a href="#">Home</a></li>
                          <li class="breadcrumb-item"><a href="#">Job Ticket</a></li>
                          <li class="breadcrumb-item active" aria-current="page">Tutor Application Summary</li>
                       </ol>
                    </nav>
                 </div>
              </div>
            </div>
            <div class="nk-block">
               <div class="card overflow-hidden">
                 <div class="card-body">
                   <form action="{{route('TicketList')}}" method="GET">
                      @csrf
                      <div class="row justify-content-between tableper-row">
                         <div class="col-md-4">
                            <div class="input-group  input-group-md">
                               <label class="input-group-text" for="inputGroupSelect01">State</label>
                               <select class="form-control" id="stateID" name="stateID">
                                  <option selected="selected"></option>
                                  @php
                                  $states = DB::table('states')->get();
                                  @endphp
                                  @foreach($states as $rowState)
                                  <option value="{{$rowState->id}}">{{$rowState->name}}</option>
                                  @endforeach
                               </select>
                            </div>
                         </div>
                         <div class="col-md-4">
                            <div class="input-group  input-group-md">
                               <label class="input-group-text" for="inputGroupSelect01">Subject</label>
                               <select class="form-control" id="subjectID" name="subjectID">
                                  <option selected="selected"></option>
                                  @php
                                  $subjects = DB::table('products')->get();
                                  @endphp
                                  @foreach($subjects as $rowSubject)
                                  <option value="{{$rowSubject->id}}">{{$rowSubject->name}}</option>
                                  @endforeach
                               </select>
                            </div>
                         </div>
                         <div class="col-md-4">
                            <div class="input-group  input-group-md">
                               <label class="input-group-text" for="inputGroupSelect01">Class Type</label>
                               <select class="form-control" id="classType" name="classType">
                                  <option selected="selected"></option>
                                  <option value="online">Online</option>
                                  <option value="physical">Physical</option>
                               </select>
                            </div>
                         </div>
                         <div class="col-md-4">
                            <div class="input-group  input-group-md">
                               <label class="input-group-text" for="inputGroupSelect01">Status</label>
                               <select class="form-control" id="status" name="status">
                                  <option selected="selected"></option>
                                  <option value="">All</option>
                                  <option value="no-application">no-application</option>
                                  <option value="incomplete">incomplete</option>
                                  <option value="completed">Completed</option>
                               </select>
                            </div>
                         </div>
                         <div class="col-md-4">
                            <div class="input-group input-group-md">
                               <span class="input-group-text" id="inputGroup-sizing-sm">Search By Ticket</span>
                               <input name="search" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" placeholder="Ticket Number">
                            </div>
                         </div>
                         <div class="col-md-2">
                            <div class="input-group input-group-md">
                               <input type="submit" class="btn btn-primary" aria-label="Sizing example input" value="Search" aria-describedby="inputGroup-sizing-sm">
                            </div>
                         </div>
                         <div class="col-md-2"></div>
                      </div>
                   </form>
                   @if (\Session::has('success'))
                   <div class="alert alert-success">
                      <ul>
                         <li>{!! \Session::get('success') !!}</li>
                      </ul>
                   </div>
                   @endif
                   <table class="datatable-init table" data-nk-container="table-responsive">
                      <thead>
                         <tr>
                            <th>#</th>
                            <th>Tutor Id</th>
                            <th>Fullname</th>
                            <th>Email</th>
                            <th>Subject Applied</th>
                            <th>Application</th>
                            <th>State</th>
                            <th>City</th>
                         </tr>
                      </thead>
                      <tbody>
                         @foreach($data as $key=>$rows)
                         <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$rows->uid}}</td>
                            <td>{{$rows->tutor}}</td>
                            <td>{{$rows->email}}</td>
                            <td>{{$rows->subject}}</td>
                            <td><p class="dtb-astatus">{{$rows->status}}</p></td>
                            <td>{{$rows->state}}</td>
                            <td>{{$rows->city}}</td>
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