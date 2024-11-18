@extends('layouts.main')

@section('content')

<div class="nk-content">
                  <div class="fluid-container">
                     <div class="nk-content-inner">
                        <div class="nk-content-body">
                           <div class="nk-block-head nk-page-head">
                              <div class="nk-block-head-content">
                                 <h1 class="nk-block-title">State Cities</h1>
                              </div>
                           </div>
                           <div class="nk-block">
                              <div class="card">
                                 <div class="card-body">
                                   
                                 <div class="row">
                                    <div class="col-md-6">
                                    <h1 style="border-radius:10px; background-color:skyblue; padding:10px;">State</h1>

                                    <div style="border-radius:10px; margin:10px; border:1px solid #000; padding:10px;" class="col-md-12">
                                    <form method="POST" action="{{route('submitState')}}">
                                       @csrf
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">State</label>
                                        <input type="text" name="state" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter State Name">
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary">add State</button>
                                    </form>
                                    </div>
                                    <table class="datatable-init table" data-nk-container="table-responsive table-border">
                                       <thead class="table-dark">
                                          <tr>
                                             <th><span class="overline-title">#</span></th>
                                             <th><span class="overline-title">State Name</span></th>
                                             <th><span class="overline-title">Action</span></th>
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
                                                   <a href="{{url('editState',$rowState->id)}}">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                   </a>
                                                </td>
                                            </tr>

                                        @endforeach
                                          
                                          
                                       </tbody>
                                    </table>
                                    </div>   
                                    <div class="col-md-6">
                                    <h1 style="border-radius:10px; background-color:skyblue; padding:10px;">Cities</h1>
                                    <div style="border-radius:10px; margin:10px; border:1px solid #000; padding:10px;" class="col-md-12">
                                    <form method="POST" action="{{route('submitCity')}}">
                                       @csrf
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">State</label>
                                        <select class="js-select" data-search="true" data-sort="false" name="state_id"><option>--Select State---</option>@foreach($states as $rowStateForCity) <option value="{{$rowStateForCity->id}}">{{$rowStateForCity->name}}</option>@endforeach</select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Cities</label>
                                        <input type="text" name="city" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter City Name">
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary">add City</button>
                                    </form>
                                    </div>
                                    <table class="datatable-init table" data-nk-container="table-responsive table-border">
                                       <thead class="table-dark">
                                          <tr>
                                             <th><span class="overline-title">#</span></th>
                                             <th><span class="overline-title">State Name</span></th>
                                             <th><span class="overline-title">City</span></th>
                                             <th><span class="overline-title">Action</span></th>
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
                                                <a href="{{url('editCity',$rowCity->id)}}">
                                                   <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                </a>
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
