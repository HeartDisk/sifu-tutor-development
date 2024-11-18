@extends('layouts.main')

@section('content')

        <div class="nk-content">
            <div class="fluid-container">
              <div class="nk-content-inner">
                <div class="nk-content-body">
                  <div class="nk-block-head">
                    <div class="nk-block-head">
                      <div class="nk-block-head-between flex-wrap gap g-2 align-items-center">
                        <div class="nk-block-head-content">
                          <div class="d-flex flex-column flex-md-row align-items-md-center">
                            <div class="mt-3 mt-md-0 ms-md-3">
                              <h3 class="title mb-1">Add News</h3>
                            </div>
                          </div>
                        </div>
                        
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
                          <form method="POST" action="{{route('submitNews')}}" enctype="multipart/form-data">
                              @csrf
                            <div class="row g-3">
                            <div class="col-lg-12">
                                <div class="form-group">
                                  <label for="subject" class="form-label">Subject</label>
                                  <div class="form-control-wrap"><input type="text" class="form-control" value="{{$singleNews->subject}}"  name="subject" id="subject"></div>
                                </div>
                              </div>
                               <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="image" class="form-label">Image</label>
                                  <div class="form-control-wrap">
                                    @if($singleNews->headerimage)
                                      <img src="{{URL::asset('/public/MobileNewsImages/'.$singleNews->headerimage)}}" alt="" height="50" width="50">  
                                    @endif
                                  
                                  <input type="file" class="form-control" name="image" id="image"></div>
                                </div>
                              </div>
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="preheader" class="form-label">Preheader</label>
                                  <div class="form-control-wrap"><input type="text" class="form-control" name="preheader" value="{{$singleNews->preheader}}" id="preheader"></div>
                                </div>
                              </div>
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="status" class="form-label">Status</label>
                                  <div class="form-control-wrap">
                                    <select class="js-select" id="status" data-search="true" name="status" data-sort="false">
                                    <option value="{{$singleNews->status}}" selected="selected">{{$singleNews->status}}</option>
                                      <option value="Published" >Published</option>
                                      <option value="UnPublished">UnPublished</option>
                                    </select>
                                  </div>
                                </div>
                              </div>
                              <div class="col-lg-12">
                                <div class="form-group">
                                  <label for="body" class="form-label">Body</label>
                                    <div class="form-control-wrap">
                                        <textarea class="form-control" name="content" id="content">{{$singleNews->content}}</textarea>
                                    </div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-12"><button class="btn btn-primary" type="submit">Submit</button></div>
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
