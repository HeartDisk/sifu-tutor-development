@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
    <div class="nk-content-inner">
       <div class="nk-content-body">
          <div class="nk-block-head">
            <div class="nk-block-head-between flex-wrap gap g-2">
               <div class="nk-block-head-content">
                  <h2 class="nk-block-title">
                     Tutors List
                  </h2>
                  <nav>
                     <ol class="breadcrumb breadcrumb-arrow mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Tutors</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tutors List</li>
                     </ol>
                  </nav>
               </div>
               <div class="nk-block-head-content">
                  <ul class="d-flex">
                     <li><a href="{{route('addTutor')}}" class="btn btn-md d-md-none btn-primary"><em class="icon ni ni-plus"></em><span>Add</span></a></li>
                     @can("tutor-add") 
                     <li><a href="{{route('addTutor')}}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Add Tutor</span></a></li>
                     @endcan 
                  </ul>
               </div>
            </div>
          </div>
          <div class="nk-block">
             <div class="card overflow-hidden">
                <div class="card-body">
                  <form action="{{route('TutorList')}}" method="GET">
                     @csrf
                     <input type="hidden" name="tutorSearchValue" value="1"/>
                     <div class="row justify-content-between tableper-row">
                         <div class="col-md-3">
                            <div class="input-group  input-group-md">
                               <label class="input-group-text" for="inputGroupSelect01">From Date</label>
                               <input type="date" class="form-control" placeholder="From Payment Date" value="{{ Request::get('fromDate')}}" name="fromDate"/>
                            </div>
                         </div>
                         <div class="col-md-3">
                            <div class="input-group  input-group-md">
                               <label class="input-group-text" for="inputGroupSelect01">To Date</label>
                               <input type="date" class="form-control" placeholder="To Payment Date" value="{{ Request::get('toDate')}}" name="toDate"/>
                            </div>
                         </div>
                         <div class="col-md-3">
                            <div class="input-group  input-group-md">
                               <label class="input-group-text" for="inputGroupSelect01">Status</label>
                               <select name="status" class="form-control" id="inputGroupSelect01">
                                  <option value="{{ Request::get('status')}}" selected> {{ Request::get('status')}} </option>
                                  <option value=""> All</option>
                                  <option value="verified"> Verified</option>
                                  <option value="Inactive"> Inactive</option>
                                  <option value="Terminated"> Terminated</option>
                                  <option value="Resigned"> Resigned</option>
                                  <option value="Unverified"> Unverified</option>
                               </select>
                            </div>
                         </div>
                         <div class="col-md-3">
                            <div class="input-group input-group-md">
                               <span class="input-group-text" id="inputGroup-sizing-sm">Search</span>
                               <input name="search" type="text" class="form-control" aria-label="Sizing example input" value="{{ Request::get('search')}}" aria-describedby="inputGroup-sizing-sm" placeholder="Tutor Name">
                            </div>
                         </div>
                        
                        <div class="col-md-2">
                           <div class="input-group input-group-md">
                              <input type="submit" class="btn btn-primary" aria-label="Sizing example input" value="Search" aria-describedby="inputGroup-sizing-sm">
                           </div>
                        </div>
                     </div>
                  </form>
                  <table class="datatable-init table" data-nk-container="table-responsive">
                      <thead>
                         <tr>
                            <th>#</th>
                            <th>Tutor ID</th>
                            <th>Full Name</th>
                            <th>Gender</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Status</th>
                            <th>Fee Payment Date</th>
                            <th>Action</th>
                         </tr>
                      </thead>
                      <tbody>
                         @php
                         $numbers = 1;
                         @endphp
                         @foreach($tutors as $rows)
                        @if($rows->is_deleted == 0 && $rows->full_name!=null && $rows->email!=null)

                         <tr>
                            <td>{{$numbers++}}</td>
                            @if($rows->status=="unverified")
                            <td><i class="fa fa-mobile"></i>   STXXXXXX</td>
                            @else
                            <td><i class="fa fa-mobile"></i>  {{$rows->tutor_id}}</td>
                            @endif
                            <td>{{$rows->full_name}}</td>
                            <td>{{$rows->gender}}</td>
                            <td>{{$rows->email}}</td>
                            <td>{{$rows->phoneNumber}}</td>
                            <td><p class="dtb-vstatus">{{$rows->status}}</p></td>
                            <td>{{$rows->fee_payment_date}}</td>
                            <td>
                               <a class="dtable-cbtn bt-dashboard dtb-tooltip" dtb-tooltip="Login" href="{{route('tutorLogin',$rows->id)}}"><i class="fa fa-dashboard"></i> </a>

                               <a class="dtable-cbtn bt-pay dtb-tooltip" dtb-tooltip="Make Payment" href="{{route('makeTutorPayment',$rows->id)}}"><i class="fa fa-dollar"></i> </a>
                               @can("tutor-detail")
                               <a class="dtable-cbtn bt-view dtb-tooltip" dtb-tooltip="View Tutor Detail" href="{{route('viewTutor',$rows->id)}}"><i class="fa fa-eye"></i> </a>
                               @endcan
                               @can("tutor-edit")
                               <a class="dtable-cbtn bt-edit dtb-tooltip" dtb-tooltip="Edit Tutor" href="{{route('editTutor',$rows->id)}}"><i class="fa fa-edit"></i> </a>
                               @endcan
                               @can("tutor-delete")
                               <a class="dtable-cbtn bt-delete dtb-tooltip" dtb-tooltip="Delete Tutor" onclick="return confirm('Are you sure you want to delete this Parrent?');" href="{{route('deleteTutor',$rows->id)}}"><i class="fa fa-trash"></i> </a>
                               @endcan
                            </td>
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
@endsection