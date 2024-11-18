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
                       Edit State
                    </h2>
                    <nav>
                       <ol class="breadcrumb breadcrumb-arrow mb-0">
                          <li class="breadcrumb-item"><a href="#">Home</a></li>
                          <li class="breadcrumb-item"><a href="#">State Cities</a></li>
                          <li class="breadcrumb-item active" aria-current="page"> Edit State</li>
                       </ol>
                    </nav>
                 </div>
              </div>
           </div>
           <div class="nk-block">
              <div class="card">
                 <div class="card-body">
                    <div class="row">
                       <div class="col-md-6">
                          <form method="POST" action="{{route('submitEditState')}}">
                             <input type="hidden" name="state_id" value="{{$states->id}}">
                             @csrf
                             <div class="form-group">
                                <label class="form-label" for="exampleInputEmail1">State</label>
                                <input type="text" name="state" class="form-control" value="{{$states->name}}" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter State Name">
                             </div>
                             <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Edit State</button>
                             </div>
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
