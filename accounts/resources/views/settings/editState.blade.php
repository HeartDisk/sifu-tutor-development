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
                                    <form method="POST" action="{{route('submitEditState')}}">
                                    <input type="hidden" name="state_id" value="{{$states->id}}">
                                       @csrf
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">State</label>
                                        <input type="text" name="state" class="form-control" value="{{$states->name}}" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter State Name">
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary">Edit State</button>
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


@endsection
