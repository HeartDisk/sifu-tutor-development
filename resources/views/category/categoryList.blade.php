@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
   <div class="nk-content-inner">
      <div class="nk-content-body">
         <div class="nk-block-head">
            <div class="nk-block-head-between flex-wrap gap g-2">
               <div class="nk-block-head-content">
                  <h2 class="nk-block-title">Level List</h2>
                  <nav>
                     <ol class="breadcrumb breadcrumb-arrow mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Subjects</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Level List</li>
                     </ol>
                  </nav>
               </div>
               <div class="nk-block-head-content">
                  <ul class="d-flex">
                     <li><a href="{{url('/addCategory')}}" class="btn btn-md d-md-none btn-primary"><em class="icon ni ni-plus"></em><span>Add</span></a></li>
                     <li><a href="{{url('/addCategory')}}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Add Level</span></a></li>
                  </ul>
               </div>
            </div>
         </div>
         <div class="nk-block">
            <div class="card overflow-hidden">
               <div class="card-body">
                  <form action="{{ route('CategoryList') }}" method="GET">
                     <div class="row tableper-row flex-row align-items-center justify-content-between">
                        <div class="col-md-4">
                           <div class="input-group input-group-md">
                              <label for="mode" class="input-group-text">Mode</label>
                              <input type="text" class="form-control" id="mode" name="mode" value="{{ request('mode') }}">
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="input-group input-group-md">
                              <label for="level_name" class="input-group-text">Level Name</label>
                              <input type="text" class="form-control" id="level_name" name="level_name" value="{{ request('level_name') }}">
                           </div>
                        </div>
                        <div class="col-md-2">
                           <div class="input-group input-group-md">
                              <button type="submit" class="btn btn-primary">Search</button>
                           </div>
                        </div>
                        <div class="col-md-2">
                           <div class="input-group input-group-md">
                              <a href="{{url()->current()}}" class="btn btn-danger">Reset</a>
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
                        <th>Service</th>
                        <th>Mode</th>
                        <th>Level Name</th>
                        <th>Price Per Hour</th>
                        <th>Long-term Commission before 8 Hour</th>
                        <th>Long-term Commission after 8 Hours</th>
                        <th>Short Term Commission</th>
                        <th>Status</th>
                        <th>Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     @php
                     $numbers = 1;
                     @endphp
                     @foreach($categories as $key=>$rows)
                     <tr>
                        <td>{{$key + 1}}</td>
                        <td>{{$rows->service}}</td>
                        <td>{{ucfirst($rows->mode)}}</td>
                        <td>{{$rows->category_name}}</td>
                        <td>{{$rows->price}}</td>
                        <td>{{$rows->tutor_longterm_commission_before_eight_hours}}</td>
                        <td>{{$rows->tutor_longterm_commission_after_eight_hours}}</td>
                        <td>{{$rows->tutor_shorterm_commission}}</td>
                        <td>{{$rows->is_deleted == 1 ? "Inactive" : "Active"}}</td>
                        <td>
                           <a class="dtable-cbtn bt-edit dtb-tooltip" dtb-tooltip="Edit Category" href="{{ route('editCategory', $rows->id) }}"><em class="icon ni ni-edit"></em></a>
                           <a class="dtable-cbtn bt-delete dtb-tooltip" dtb-tooltip="Delete Category" onclick="return confirm('Are you sure you want to delete this Category?');" href="{{ route('deleteCategory', $rows->id) }}"><em class="icon ni ni-trash"></em></a>
                        </td>
                     </tr>
                     @php
                     $numbers++;
                     @endphp
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