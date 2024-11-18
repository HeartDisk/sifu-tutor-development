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
                              <h3 class="title mb-1">Add Services</h3>
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
                            @if (\Session::has('danger'))
                                <div class="alert alert-danger">
                                    <ul>
                                        <li>{!! \Session::get('danger') !!}</li>
                                    </ul>
                                </div>
                            @endif
                        <div class="bio-block">
                            
                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="mt-3">
                                    </div>
                                    <form method="POST" action="{{route('submitService')}}">
                                      @csrf
                                    <div class="row g-3">
                                        <div class="col-lg-10">
                                            <div class="form-group">
                                              <label for="firstname" class="form-label mb-2">Service Name</label>
                                              <div class="form-control-wrap"><input type="text" class="form-control" name="service" id="service"></div>
                                            </div>
                                      </div>
                                    </div>
                                    <div class="col-lg-12"><button class="btn btn-primary mt-4" type="submit">Submit</button></div>
                                  </form>
                                
                                
                                
                                </div> 
                                <div class="col-md-6">
                                    <div class="mt-3 mt-md-0 ms-md-3">
                                      <h3 class="title mb-1">Service List</h3>
                                    </div>
                                
                                <table class="table table-striped">
                                  <thead class="table-dark">
                                    <tr>
                                      <th scope="col">#</th>
                                      <th scope="col">Service Name</th>
                                      <th scope="col">Action</th>
                                      
                                    </tr>
                                  </thead>
                                  <tbody>
                                      @php
                                        $number = 1;
                                      @endphp
                                      @foreach($services as $rowService)
                                            <tr>
                                              <th scope="row">{{$number++}}</th>
                                              <td>{{$rowService->service}}</td>
                                              <td><a href="{{url('deleteService',$rowService->id)}}"> Delete </a></td>
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
          </div>

@endsection
