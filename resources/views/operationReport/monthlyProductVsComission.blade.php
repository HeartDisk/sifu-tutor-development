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
                                    Monthly Product vs Commission
                                </h2>
                                <nav>
                                    <ol class="breadcrumb breadcrumb-arrow mb-0">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item"><a href="#">Operation Report</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Monthly Product vs Commission
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="nk-block">
                        <div class="card">
                            <div class="card-body">
                                <form method="get" enctype="multipart/form-data"
                                      action="{{url("/monthlyProductVsComission")}}">
                                    @csrf
                                    <div class="row tableper-row flex-row align-items-center justify-content-between">
                                        <div class="col-md-4">
                                            <div class="input-group input-group-md">
                                                <label for="month" class="input-group-text">Select Month</label>
                                                <select name="month" class="form-control">
                                                    <option value="">All</option>
                                                    @php
                                                        $selectedMonth = isset($selectedMonth) ? $selectedMonth : null;
                                                        for ($month = 1; $month <= 12; $month++) {
                                                        $monthName = date('F', mktime(0, 0, 0, $month, 1));
                                                        $selectedAttribute = ($selectedMonth == $monthName) ? 'selected' : '';
                                                        echo "<option value=\"$monthName\" $selectedAttribute>$monthName</option>";
                                                        }
                                                    @endphp
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group input-group-md">
                                                <label for="year" class="input-group-text">Select Year</label>
                                                <select name="year" class="form-control">
                                                    <option value="">All</option>
                                                    @php
                                                        $currentYear = date('Y');
                                                        $selectedYear = isset($selectedYear) ? $selectedYear : null;
                                                        for ($year = $currentYear; $year >= ($currentYear - 10); $year--) {
                                                        $selectedAttribute = ($selectedYear == $year) ? 'selected' : '';
                                                        echo "<option value=\"$year\" $selectedAttribute>$year</option>";
                                                        }
                                                    @endphp
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group input-group-md">
                                                <button class="btn btn-primary" aria-label="Sizing example input"
                                                        aria-describedby="inputGroup-sizing-sm">Search
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group input-group-md">
                                                <a href="{{url()->current()}}" class="btn btn-danger">Reset</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <table class="datatable-init table" data-nk-container="table-responsive">
                                    <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Staff</th>
                                        <th>Product</th>
                                        <th>Month - Year</th>
                                        <th>Total Invoices</th>
                                        <th>Total Invoices Amount</th>
                                        <th>Total Commissions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data as $key=>$rows)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{$rows->staff_name}}</td>
                                            <td>{{$rows->product_name}}</td>
                                            <td>{{$rows->month_name}} - {{$rows->year}}</td>
                                            <td>{{$rows->total_job_tickets}}</td>
                                            <td>{{$rows->total_amount}}</td>
                                            <td>{{$rows->bonus}}</td>
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
@endsection