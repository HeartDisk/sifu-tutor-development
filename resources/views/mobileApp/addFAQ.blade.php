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
                              <h3 class="title mb-1">Add FAQ</h3>
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
                          <form method="POST" action="{{route('submitFAQ')}}" enctype="multipart/form-data">
                              @csrf
                            <div class="row g-3">
                            <div class="col-lg-12"> 
                              <div class="col-lg-12">
                                <div class="form-group">
                                  <label for="body" class="form-label">Question</label>
                                    <div class="form-control-wrap">
                                        <textarea cols="80" id="editor1" name="editor1" rows="10"></textarea>
                                          <script>
                                            CKEDITOR.replace('editor1', {
                                              height: 260,
                                              width: 700,
                                              removeButtons: 'PasteFromWord'
                                            });
                                          </script>
                                    </div>
                                </div>
                              </div>
                              
                              <div class="col-lg-12">
                                <div class="form-group">
                                  <label for="body" class="form-label">Answer</label>
                                    <div class="form-control-wrap">
                                        <textarea cols="80" id="editor2" name="editor2" rows="10"></textarea>
                                          <script>
                                            CKEDITOR.replace('editor2', {
                                              height: 260,
                                              width: 700,
                                              removeButtons: 'PasteFromWord'
                                            });
                                          </script>
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
