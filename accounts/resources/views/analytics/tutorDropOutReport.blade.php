@extends('layouts.main')

@section('content')

<div class="nk-content">
            <div class="fluid-container">
              <div class="nk-content-inner">
                <div class="nk-content-body">
                  <div class="nk-block-head">
                    <div class="nk-block-head-between flex-wrap gap g-2">
                      <div class="nk-block-head-content">
                        <h2 class="nk-block-title">
                        Tutor Dropout Rate</h1>
                        <nav>
                          <ol class="breadcrumb breadcrumb-arrow mb-0">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Analytics</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tutor Dropout Rate</li>
                          </ol>
                        </nav>
                      </div>
                      
                    </div>
                  </div>
                  <div class="nk-block">
                    <div class="card">
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
                      <table class="datatable-init table" data-nk-container="table-responsive">
                                <thead class="thead-light">
                                  
                                    <tr>
                                        <th scope="col">S.No</th>
                                         <th scope="col">Tutor</th>
                                        <th scope="col">Active Students</th>
                                        <th scope="col">Inctive Students Tutors</th>
                                        <th scope="col">Student Success Percentage</th>
                                        
                                        
                                         <th scope="col">Active Tickets</th>
                                        <th scope="col">Inactive Tickets Tutors</th>
                                        <th scope="col">Ticket Success Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  
                                    
                                    
                                     @foreach($results as $key=>$result)
                                     @if($result->tutor!=null)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{$result->tutor}}</td>
                                            <td>{{$result->active_students}}</td>
                                            <td>{{$result->inactive_students}}</td>
                                            <td>{{$result->students_success_percentage}}%</td>
                                            <td>{{$result->active_tickets}}</td>
                                            <td>{{$result->discontinued_tickets}}</td>
                                            <td>{{$result->tickets_success_percentage}}%</td>
                                        </tr>
                                        @endif
                                      
                                    @endforeach
                                        
                                </tbody>
                                <tfoot class="tfoot-light">
                                    <tr>
                                        <td>Total</td>
                                        <td>24624</td>
                                        <td>2018</td>
                                    </tr>
                                </tfoot>
                            
                        <tbody>
                                            
                          
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

@endsection

