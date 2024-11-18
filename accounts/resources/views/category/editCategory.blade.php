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
                        Edit Category
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Level List</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Edit Category</li>
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
                        <form method="POST" action="{{route('submitEditCategory')}}">
                           @csrf
                           <input type="hidden" class="form-control" name="category_id" value="{{$category->id}}">
                           <div class="row">
                              <div class="col-lg-3" style="display:none">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Services</label>
                                    <div class="form-control-wrap">
                                       <select name="service_id" class="form-control">
                                          @foreach($services as $rowService)
                                          @if($category->service_id == $rowService->id)
                                          <option value="{{$rowService->id}}">{{$rowService->service}}</option>
                                          @endif
                                          <option value="{{$rowService->id}}">{{$rowService->service}}</option>
                                          @endforeach
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Mode</label>
                                    <div class="form-control-wrap">
                                       <select name="mode" class="form-control" required>
                                       <option @if($category->mode == "online")  selected  @endif value="online">ONLINE</option>
                                       <option @if($category->mode == "physical")  selected  @endif value="physical">PHYSICAL</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Level Name</label>
                                    <div class="form-control-wrap"><input type="text" class="form-control" name="category_name" value="{{$category->category_name}}" id="category_name" required></div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Price Per Hour</label>
                                    <div class="form-control-wrap"><input type="text" class="form-control"value="{{$category->price}}"  name="price" id="price" required></div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Status</label>
                                    <div class="form-control-wrap">
                                       <select name="status" class="form-control">
                                       <option {{$category->is_deleted==1?"selected":""}} value="1">Inactive</option>
                                       <option {{$category->is_deleted==0?"selected":""}}  value="0">Active</option>
                                       </select>
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