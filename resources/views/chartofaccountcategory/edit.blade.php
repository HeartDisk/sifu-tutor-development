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
                        Edit Chart of Accounts Category
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Chart of Accounts Category List</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Edit Chart of Accounts Category</li>
                        </ol>
                     </nav>
                  </div>
               </div>
            </div>
            <div class="nk-block">
               <div class="card card-gutter-md">
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
                     <div class="bio-block">
                        <form method="POST" action="{{url('submit-edit-chart-of-accounts-category/'.$data->id)}}">
                           @csrf
                           <div class="row">
                              <div class="col-lg-6">
                                  <div class="form-group">
                                      <label for="firstname" class="form-label">Category Name</label>
                                      <div class="form-control-wrap">
                                          <input value="{{$data->name}}" type="text" class="form-control" name="name" id="name">
                                      </div>

                                  </div>
                              </div>
                           </div>
                           <div class="col-lg-2"><button class="btn btn-primary" type="submit">Update</button></div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
