@extends('layouts.main')

@section('content')

<div class="nk-content">
                  <div class="fluid-container">
                     <div class="nk-content-inner">
                        <div class="nk-content-body">
                           <div class="nk-block-head nk-page-head">
                              <div class="nk-block-head-content">
                                 <h1 class="nk-block-title">Edit Cities</h1>
                              </div>
                           </div>
                           <div class="nk-block">
                              <div class="card">
                                 <div class="card-body">
                                   
                                 <div class="row">
                                    <div class="col-md-6">
                                    <h1 style="border-radius:10px; background-color:skyblue; padding:10px;">Cities</h1>
                                    <div style="border-radius:10px; margin:10px; border:1px solid #000; padding:10px;" class="col-md-12">
                                    <form method="POST" action="{{route('submitEditCity')}}">
                                       @csrf
                                       <input type="hidden" value="{{$city->id}}" name="city_id"/>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">State</label>
                                        <select class="js-select" data-search="true" data-sort="false" name="state_id"><option value="{{$city->state_id}}">{{$getState->name}}</option>@foreach($states as $rowStateForCity) <option value="{{$rowStateForCity->id}}">{{$rowStateForCity->name}}</option>@endforeach</select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Cities</label>
                                        <input type="text" name="city" class="form-control" value="{{$city->name}}" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter City Name">
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary">Edit City</button>
                                    </form>
                                    </div>
                                    
                                    </div>
                                 </div>

                
                                 </div>
                              </div>
                           </div>
                           
                        </div>
                     </div>
                  </div>
               </div>


@endsection
