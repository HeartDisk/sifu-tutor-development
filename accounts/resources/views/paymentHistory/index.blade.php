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
                        My Payment History</h1>
                        <nav>
                          <ol class="breadcrumb breadcrumb-arrow mb-0">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">My Payment History</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Student</li>
                          </ol>
                        </nav>
                      </div>
                      <div class="nk-block-head-content">
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
                             <table class="datatable-init table" data-nk-container="table-responsive table-border">

                                 <thead class="thead-light">
                                 <tr>
                                     <th scope="col">#</th>
                                     <th scope="col">Staff</th>
                                     <th scope="col">Month - Year</th>
                                     <th scope="col">Nett Pay</th>
                                     <th scope="col">Total Amount</th>
                                     <th scope="col">Payment Date</th>
                                     <th scope="col" width="150">Action</th>
                                 </tr>
                                 </thead>

                                 <tbody>
                                 @foreach($staffPayments as $key=>$staffPayment)
                                     <tr>
                                         <th scope="row">{{$key+1}}</th>
                                         <td>{{$staffPayment->name}}</td>
                                         <td>{{$staffPayment->salary_month."-".$staffPayment->salary_year}}</td>
                                         <td>{{$staffPayment->nett_amount}}</td>
                                         <td>{{$staffPayment->total}}</td>
                                         <td>{{$staffPayment->payment_date}}</td>
                                         @can("staff-payment-view-slip")
                                             <td><a href="{{url("/ViewPaymentSlip")."/".$staffPayment->id}}">View Slip</a> </td>
                                         @endcan
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

@endsection

