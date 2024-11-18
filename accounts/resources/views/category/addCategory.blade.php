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
                        Add Level
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Level List</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Add Level</li>
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
                        <form method="POST" action="{{route('submitCategory')}}">
                           @csrf
                           <div class="row g-3">
                              <div class="col-lg-3" style="display:none">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Services</label>
                                    <div class="form-control-wrap">
                                       <select name="service_id" class="form-control">
                                          @foreach($services as $rowService)
                                          <option value="{{$rowService->id}}">{{$rowService->service}}</option>
                                          @endforeach
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Mode</label>
                                    <div class="form-control-wrap">
                                       <select name="mode" class="form-control">
                                          <option value="online">ONLINE</option>
                                          <option value="physical">PHYSICAL</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Level Name</label>
                                    <div class="form-control-wrap"><input type="text" class="form-control" name="category_name" id="category_name"></div>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Price Per Hour</label>
                                    <div class="form-control-wrap"><input type="text" class="form-control" name="price" id="price"></div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-2"><button class="btn btn-primary" type="submit">Submit</button></div>
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