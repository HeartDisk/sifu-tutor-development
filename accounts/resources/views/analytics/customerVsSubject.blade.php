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
                        CUSTOMER VS SUBJECT</h1>
                        <nav>
                          <ol class="breadcrumb breadcrumb-arrow mb-0">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Analytics</a></li>
                            <li class="breadcrumb-item active" aria-current="page">CUSTOMER VS SUBJECT</li>
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
                                        <th scope="col" colspan="3" style="background-color: #fff;color: #7e858c;padding-left: 2px;">Comparison number of customers by each subjects.</th>
                                    </tr>
                                    <tr>
                                        <th scope="col">
                                            Subject <br>
                                            &nbsp;
                                        </th>
                                        <th scope="col">
                                            <span>Subscribed Customers</span>
                                            <br>
                                            <small>(for {{$currentMonthFull}}, {{$currentYear}})</small>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                     @foreach($tutors as $tutor)
                                    
                                        <tr>
                                            <td>{{$tutor->name}} ({{$tutor->category_name}}) - {{$tutor->mode}}</td>
                                            <td>{{$tutor->current_month_count}}</td>
                                        </tr>
                                    @endforeach
                                        
                                </tbody>
                                <tfoot class="tfoot-light">
                                    <tr>
                                        <td>Total</td>
                                        <td>3291</td>
                                    </tr>
                                </tfoot>
                            
                      </table>
                      
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

@endsection

