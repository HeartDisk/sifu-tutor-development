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
                        Tutor Assignments
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Tutors</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Tutor Assignments</li>
                        </ol>
                     </nav>
                  </div>
               </div>
            </div>
            <div class="nk-block">
               <div class="card overflow-hidden">
                  <form action="{{url("/")}}" method="GET">
                  <div class="row justify-content-between tableper-row">
                     <div class="col-md-5">
                        <div class="input-group input-group-md">
                           <label class="input-group-text" for="inputGroupSelect01">Status</label>
                           <select id="ReportType" name="ReportType" class="form-control">
                              <option value="All"> All</option>
                              <option value="Active" selected=""> Active</option>
                              <option value="Inactive"> Inactive</option>
                              <option value="Terminated"> Terminated</option>
                              <option value="Resigned"> Resigned</option>
                           </select>
                        </div>
                     </div>
                     <div class="col-md-5">
                        <div class="input-group input-group-md">
                           <span class="input-group-text" id="inputGroup-sizing-sm">Search</span>
                           <input name="search" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" placeholder="Tutor Name">
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
                           <th>Tutor Id</th>
                           <th>Fullname</th>
                           <th>Subject</th>
                           <th>Student</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <th>1</th>
                           <td>T230291</td>
                           <td>Siti Hajar bt Ibrahim</td>
                           <td>Asas Mengaji: 5 sesi - ONLINE</td>
                           <td>Mohamad Zahin Irsyad, Mohamad Zharif Iman bin Mohd Fazlin (S232923, S232924)</td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection