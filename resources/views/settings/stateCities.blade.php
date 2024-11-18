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
                        State Cities
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Setting</a></li>
                           <li class="breadcrumb-item active" aria-current="page">State Cities</li>
                        </ol>
                     </nav>
                  </div>
                  >
               </div>
            </div>
         </div>
         <div class="nk-block">
            <div class="row pt-4">
               <div class="col-md-6">
                  <div class="card">
                     <div class="card-body">
                        <div class="row">
                           <div class="col-md-12">
                              <h3>State</h3>
                              <form method="POST" action="{{route('submitState')}}">
                                 @csrf
                                 <div class="form-group">
                                    <label class="form-label" for="exampleInputEmail1">State</label>
                                    <input type="text" name="state" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter State Name">
                                 </div>
                                 <div class="col-lg-2">
                                    <button type="submit" class="btn btn-primary">Add State</button>
                                 </div>
                              </form>
                           </div>
                           <table class="datatable-init table" data-nk-container="table-responsive">
                              <thead>
                                 <tr>
                                    <th>#</th>
                                    <th>State Name</th>
                                    <th>Action</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @php
                                 $number = 1;
                                 @endphp
                                 @foreach($states as $rowState)
                                 <tr>
                                    <td>{{$number++}}</td>
                                    <td>{{$rowState->name}}</td>
                                    <td>
                                       <a class="dtable-cbtn bt-edit dtb-tooltip" dtb-tooltip="Edit" href="{{url('editState',$rowState->id)}}"><i class="fa fa-edit"></i> </a>
                                    </td>
                                 </tr>
                                 @endforeach
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="card">
                     <div class="card-body">
                        <div class="row">
                           <div class="col-md-12">
                              <h3>Cities</h3>
                              <form method="POST" action="{{route('submitCity')}}">
                                 @csrf
                                 <div class="row">
                                    <div class="col-sm-6">
                                       <div class="form-group">
                                          <label class="form-label" for="exampleInputEmail1">State</label>
                                          <select class="js-select" data-search="true" data-sort="false" name="state_id">
                                             <option>--Select State---</option>
                                             @foreach($states as $rowStateForCity) 
                                             <option value="{{$rowStateForCity->id}}">{{$rowStateForCity->name}}</option>
                                             @endforeach
                                          </select>
                                       </div>
                                    </div>
                                    <div class="col-sm-6">
                                       <div class="form-group">
                                          <label class="form-label" for="exampleInputEmail1">Cities</label>
                                          <input type="text" name="city" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter City Name">
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-2">
                                    <button type="submit" class="btn btn-primary">Add City</button>
                                 </div>
                              </form>
                           </div>
                           <table class="datatable-init table" data-nk-container="table-responsive">
                              <thead>
                                 <tr>
                                    <th>#</th>
                                    <th>State Name</th>
                                    <th>City</th>
                                    <th>Action</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @php
                                 $number = 1;
                                 @endphp
                                 @foreach($cities as $rowCity)
                                 <tr>
                                    <td>{{$number++}}</td>
                                    <td>
                                       @php
                                       $stateName = DB::table('cities')->where('state_id','=',$rowCity->state_id)->first();
                                       @endphp
                                       {{$stateName->name}}
                                    </td>
                                    <td>{{$rowCity->name}}</td>
                                    <td>
                                       <a class="dtable-cbtn bt-edit dtb-tooltip" dtb-tooltip="Edit" href="{{url('editCity',$rowCity->id)}}"><i class="fa fa-edit"></i> </a>
                                    </td>
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
   </div>
</div>
@endsection