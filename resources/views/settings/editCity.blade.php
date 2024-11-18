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
                        Edit City
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">State Cities</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Edit City</li>
                        </ol>
                     </nav>
                  </div>
               </div>
            </div>
            <div class="nk-block">
               <div class="card">
                  <div class="card-body">
                     <form method="POST" action="{{route('submitEditCity')}}">
                        @csrf
                        <input type="hidden" value="{{$city->id}}" name="city_id"/>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="form-label" for="exampleInputEmail1">State</label>
                                 <select class="js-select" data-search="true" data-sort="false" name="state_id">
                                    <option value="{{$city->state_id}}">{{$getState->name}}</option>
                                    @foreach($states as $rowStateForCity) 
                                    <option value="{{$rowStateForCity->id}}">{{$rowStateForCity->name}}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="form-label" for="exampleInputEmail1">Cities</label>
                                 <input type="text" name="city" class="form-control" value="{{$city->name}}" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter City Name">
                              </div>
                           </div>
                           <div class="col-md-2">
                              <button type="submit" class="btn btn-primary">Edit City</button>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection