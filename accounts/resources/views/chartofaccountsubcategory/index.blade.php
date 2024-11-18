@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
   <div class="nk-content-inner">
      <div class="nk-content-body">
         <div class="nk-block-head">
            <div class="nk-block-head-between flex-wrap gap g-2">
               <div class="nk-block-head-content">
                  <h2 class="nk-block-title">
                      Chart of Accounts Sub Category List
                  </h2>
                  <nav>
                     <ol class="breadcrumb breadcrumb-arrow mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Chart of Accounts Category</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Chart of Accounts Category List</li>
                     </ol>
                  </nav>
               </div>
               <div class="nk-block-head-content">
                  <ul class="d-flex">
                     <li><a href="{{url('create-chart-of-accounts-subcategory')}}" class="btn btn-md d-md-none btn-primary"><em class="icon ni ni-plus"></em><span>Add Chart of Accounts Sub Category</span></a></li>
                     <li><a href="{{url('create-chart-of-accounts-subcategory')}}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Add Chart of Accounts Sub Category</span></a></li>
                  </ul>
               </div>
            </div>
         </div>
         <div class="nk-block">
            <div class="card overflow-hidden">
               <div class="card-body">
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
                           <th>Category Name</th>
                           <th>Sub Category Name</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php
                        $numbers = 1;
                        @endphp
                        @foreach($data as $key=>$dt)
                        <tr>
                           <td>{{$key + 1}}</td>
                           <td>{{$dt->category->name}}</td>
                           <td>{{$dt->name}}</td>
                           <td>
                              <a class="dtable-cbtn bt-edit dtb-tooltip" dtb-tooltip="Edit" href="{{ url('edit-chart-of-accounts-subcategory/' . $dt->id) }}"><em class="icon ni ni-edit"></em></a>
                              <a class="dtable-cbtn bt-delete dtb-tooltip" dtb-tooltip="Delete" onclick="return confirm('Are you sure you want to delete this Category?');" href="{{ url('delete-chart-of-accounts-subcategory/' . $dt->id) }}"><em class="icon ni ni-trash"></em></a>
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
