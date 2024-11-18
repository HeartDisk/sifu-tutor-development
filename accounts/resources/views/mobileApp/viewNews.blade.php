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
                        View News
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Mobile App News List</a></li>
                           <li class="breadcrumb-item active" aria-current="page">View News</li>
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
                        <div class="row g-3">
                           <div class="col-lg-6">
                              <div class="form-group">
                                 <label for="subject" class="form-label">Subject</label>
                                 <div class="form-control-wrap"><input type="text" readonly class="form-control" value="{{$singleNews->subject}}"  name="subject" id="subject"></div>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="form-group">
                                 <label for="image" class="form-label">Image</label>
                                 <div class="form-control-wrap">
                                    @if($singleNews->headerimage)
                                    <a href="{{URL::asset('/public/MobileNewsImages/'.$singleNews->headerimage)}}" data-lightbox="image"><img src="{{URL::asset('/public/MobileNewsImages/'.$singleNews->headerimage)}}" height="45px" width="50px"></a>  
                                    @endif
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="form-group">
                                 <label for="preheader" class="form-label">Preheader</label>
                                 <div class="form-control-wrap"><input type="text" readonly  class="form-control" name="preheader" value="{{$singleNews->preheader}}" id="preheader"></div>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="form-group">
                                 <label for="status" class="form-label">Status</label>
                                 <div class="form-control-wrap">
                                    <select class="js-select" id="status" data-search="true" name="status" data-sort="false">
                                       <option value="{{$singleNews->status}}" selected="selected">{{$singleNews->status}}</option>
                                    </select>
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-12">
                              <div class="form-group">
                                 <label for="body" class="form-label">Body</label>
                                 <div class="form-control-wrap">
                                    <textarea class="form-control" id="editor1" name="content" readonly  id="content">{{$singleNews->content}}</textarea>
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
   </div>
</div>
@endsection