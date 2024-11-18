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
                                    Student Invoice
                                </h2>
                                <nav>
                                    <ol class="breadcrumb breadcrumb-arrow mb-0">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item"><a href="#">Analytics</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Student Invoice</li>
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
                                <form method="GET" action="{{ route('analytics/studentInvoices') }}">
                                  <div class="row tableper-row flex-row align-items-center justify-content-between">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-4">
                                      <div class="input-group input-group-md">
                                        <label for="month"  class="input-group-text">Month:</label>
                                        <select class="form-control" name="month" id="month">
                                            @foreach($months as $key => $month)
                                                <option value="{{ $key + 1 }}" {{ $selectedMonth == ($key + 1) ? 'selected' : '' }}>{{ $month }}</option>
                                            @endforeach
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="input-group input-group-md">
                                        <label for="year"  class="input-group-text">Year:</label>
                                        <select class="form-control" name="year" id="year">
                                            @for ($i = date('Y'); $i >= 2010; $i--)
                                                <option value="{{ $i }}" {{ $selectedYear == $i ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-md-2">
                                      <div class="input-group input-group-md">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                      </div>
                                    </div>
                                    <div class="col-md-1"></div>
                                  </div>
                                </form>
                                <div class="row headwtb">
                                    <div class="col-md-12">
                                        <h3>All Data</h3>
                                    </div>
                                </div>
                                <table class="datatable-init table" data-nk-container="table-responsive">
                                    <thead>
                                    <tr>
                                        <th>Month-Year</th>
                                        <th>Total Invoice</th>
                                        <th>Total Physical Invoice</th>
                                        <th>Total Online Invoice</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($allData as $data)
                                        <tr>
                                            <td>{{ $data->month }} - {{ $data->year }}</td>
                                            <td>{{ $data->total_job_tickets }}</td>
                                            <td>{{ $data->physical_job_tickets }}</td>
                                            <td>{{ $data->online_job_tickets }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td>Total</td>
                                        <td>
                                            @php
                                                $total_job_tickets = 0;
                                                foreach ($allData as $data) {
                                                   $total_job_tickets += $data->total_job_tickets;
                                                }
                                                echo $total_job_tickets;
                                            @endphp
                                        </td>
                                        <td>
                                            @php
                                                $total_physical_job_tickets = 0;
                                                foreach ($allData as $data) {
                                                   $total_physical_job_tickets += $data->physical_job_tickets;
                                                }
                                                echo $total_physical_job_tickets;
                                            @endphp
                                        </td>
                                        <td>
                                            @php
                                                $total_online_job_tickets = 0;
                                                foreach ($allData as $data) {
                                                   $total_online_job_tickets += $data->online_job_tickets;
                                                }
                                                echo $total_online_job_tickets;
                                            @endphp
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    @foreach($filteredData as $filtered)
                        <div class="nk-block">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row headwtb">
                                        <div class="col-md-12">
                                            <h3>{{ $filtered['month'] }} - {{ $selectedYear }}</h3>
                                        </div>
                                    </div>
                                    <table class="datatable-init table" data-nk-container="table-responsive">
                                        <thead>
                                        <tr>
                                            <th>Month-Year</th>
                                            <th>Total Invoice</th>
                                            <th>Total Physical Invoice</th>
                                            <th>Total Online Invoice</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($filtered['data'] as $item)
                                            <tr>
                                                <td>{{ $item->month }} - {{ $item->year }}</td>
                                                <td>{{ $item->total_job_tickets }}</td>
                                                <td>{{ $item->physical_job_tickets }}</td>
                                                <td>{{ $item->online_job_tickets }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
