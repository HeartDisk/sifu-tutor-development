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
                     Income Statement</h1>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Financial Report</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Income Statement</li>
                        </ol>
                     </nav>
                  </div>
               </div>
            </div>
            <div class="nk-block">
               <div class="card">
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
                     <form method="get" action="{{url("/financialReport/incomeStatement")}}">
                     @csrf
                     <div class="row tableper-row">
                        @php
                        $month = request('month', date('n')); // Get the value of 'month' parameter from the URL or default to current month
                        @endphp
                        <div class="col-md-1"></div>
                        <div class="col-md-4">
                           <div class="input-group  input-group-md">
                              <label class="input-group-text">Month</label>
                              <select class="form-control" id="month" name="month">
                                 <option value="all">All</option>
                                 @foreach (range(1, 12) as $monthNumber)
                                 <option value="{{ $monthNumber }}" {{ $monthNumber == $month ? 'selected' : '' }}>
                                 {{ date('F', mktime(0, 0, 0, $monthNumber, 10)) }}
                                 </option>
                                 @endforeach
                              </select>
                           </div>
                        </div>
                        @php
                        $year = request('year', date('Y')); // Get the value of 'year' parameter from the URL or default to current year
                        @endphp
                        <div class="col-md-4">
                           <div class="input-group  input-group-md">
                              <label class="input-group-text">Year</label>
                              <select class="form-control" id="year" name="year">
                                 <option value="all">All</option>
                                 @foreach (range(2024, 2035) as $yr)
                                 <option value="{{ $yr }}" {{ $yr == $year ? 'selected' : '' }}>
                                 {{ $yr }}
                                 </option>
                                 @endforeach
                              </select>
                           </div>
                        </div>
                        <div class="col-md-2">
                           <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                     </div>
                     </form>
                     <table class="datatable-init table" data-nk-container="table-responsive">
                        <thead>
                           <tr>
                              <th>Account Code</th>
                              <th>Account Name</th>
                              <th>Account Type</th>
                              <th>Balance</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <td>SALES (1100)</td>
                              <td></td>
                              <td>Income</td>
                              <td>RM {{number_format($sales_sum,2)}}</td>
                           </tr>
                           <tr>
                              <td>COST OF GOOD SOLD (1200)</td>
                              <td></td>
                              <td>Expense</td>
                              <td>RM {{number_format($tutor_payments,2)}}</td>
                           </tr>
                           <tr>
                              <td>GROSS PROFIT</td>
                              <td></td>
                              <td></td>
                              <td>RM {{number_format($gross_profit,2)}}</td>
                           </tr>
                           <tr>
                              <td>OPERATING EXPENSES (2100)</td>
                              <td></td>
                              <td>Expense</td>
                              <td>RM {{number_format($operating_expense,2)}}</td>
                           </tr>
                           <tr>
                              <td>PROVISIONAL EXPENSES (2400)</td>
                              <td></td>
                              <td>Expense</td>
                              <td>RM {{number_format($provisional_expense,2)}}</td>
                           </tr>
                           <tr>
                              <td>STAFF COSTS (2200)</td>
                              <td></td>
                              <td>Expense</td>
                              <td>RM {{number_format($staff_cost,2)}}</td>
                           </tr>
                           <tr>
                              <td>FINANCE COSTS (2300)</td>
                              <td></td>
                              <td>Expense</td>
                              <td>RM {{number_format($finance_cost,2)}}</td>
                           </tr>
                           <tr>
                              <td><strong>Profit for the Financial Year</strong></td>
                              <td></td>
                              <td></td>
                              <td>RM {{number_format($profit,2)}}</td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection