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
                        TOTAL CLASS BY WEEKDAY/WEEKEND</h1>
                        <nav>
                          <ol class="breadcrumb breadcrumb-arrow mb-0">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Analytics</a></li>
                            <li class="breadcrumb-item active" aria-current="page">TOTAL CLASS BY WEEKDAY/WEEKEND</li>
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
                                        <th scope="col"></th>
                                        <th scope="col">8am – 12pm</th>
                                        <th scope="col">12pm – 4pm</th>
                                        <th scope="col">4pm - 8pm</th>
                                        <th scope="col">8pm - 12am</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">Weekday</th>
                                        <td>12216</td>
                                        <td>10471</td>
                                        <td>12597</td>
                                        <td>9774</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Weekend</th>
                                        <td>4454</td>
                                        <td>3078</td>
                                        <td>2986</td>
                                        <td>2453</td>
                                    </tr>
                                </tbody>
                                <tfoot class="tfoot-light">
                                    <tr>
                                        <td>Total</td>
                                        <td>16670</td>
                                        <td>13549</td>
                                        <td>15583</td>
                                        <td>12227</td>
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

