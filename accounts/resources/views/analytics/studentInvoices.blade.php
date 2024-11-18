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
                        STUDENT INVOICES</h1>
                        <nav>
                          <ol class="breadcrumb breadcrumb-arrow mb-0">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Analytics</a></li>
                            <li class="breadcrumb-item active" aria-current="page">PROMOTION INVOICES</li>
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
                        
                        <div class="col-md-12">
        <div class="card">
            
            <div class="card-body">

                <h3>Class Type Invoices</h3>
                <div class="row">
                    <div class="col-md-12 pb-5">
                        <table class="table table-hover table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Month-Year</th>
                                    <th scope="col">Total Invoice</th>
                                    <th scope="col">Total Physical Invoice</th>
                                    <th scope="col">Total Online Invoice</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                               @foreach($rows as $row)
                                <tr>
                                    <td>{{ $row['month'] }} - {{ date("Y") }}</td>
                                    <td>{{ $row['total_job_tickets'] }}</td>
                                    <td>{{ $row['physical_job_tickets'] }}</td>
                                    <td>{{ $row['online_job_tickets'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="tfoot-light">
                                <tr>
                                    <td>Total</td>
                                    <td>{{ $total['total_job_tickets'] }}</td>
                                    <td>{{ $total['physical_job_tickets'] }}</td>
                                    <td>{{ $total['online_job_tickets'] }}</td>
                                                              
                                </tr>
                            </tfoot>
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
          </div>

@endsection

