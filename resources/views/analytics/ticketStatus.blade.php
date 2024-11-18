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
                                    Monthly Ticket Status
                                </h2>
                                <nav>
                                    <ol class="breadcrumb breadcrumb-arrow mb-0">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item"><a href="#">Analytics</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Monthly Ticket Status</li>
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
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="nk-content">
                                            <div class="nk-content-inner">
                                                <div class="nk-content-body">
                                                    <div class="nk-block">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <h5 class="card-title">Select Month-Year Range</h5>
                                                                <form action="{{ route('analytics/ticketStatus') }}" method="GET">
                                                                    <div class="row mb-2">
                                                                        <div class="col-md-3">
                                                                            <select name="fromMonth" class="form-control">
                                                                                @foreach($months as $index => $month)
                                                                                    <option value="{{ $index + 1 }}" {{ $fromMonth == $index + 1 ? 'selected' : '' }}>{{ $month }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <select name="fromYear" class="form-control">
                                                                                @for ($year = date('Y'); $year >= 2000; $year--)
                                                                                    <option value="{{ $year }}" {{ $fromYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                                                                                @endfor
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-1 text-center">
                                                                            <span>to</span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <select name="toMonth" class="form-control">
                                                                                @foreach($months as $index => $month)
                                                                                    <option value="{{ $index + 1 }}" {{ $toMonth == $index + 1 ? 'selected' : '' }}>{{ $month }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <select name="toYear" class="form-control">
                                                                                @for ($year = date('Y'); $year >= 2000; $year--)
                                                                                    <option value="{{ $year }}" {{ $toYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                                                                                @endfor
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <br/><br/>
                                                                            <button type="submit" class="btn btn-primary">Filter</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="nk-block">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <canvas id="myChart"></canvas>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                                <script>
                                    const ctx = document.getElementById('myChart');
                                    var data = {{ json_encode($data) }};
                                    new Chart(ctx, {
                                        type: 'bar',
                                        data: {
                                            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                                            datasets: [{
                                                label: '# of Job Tickets',
                                                data: data,
                                                backgroundColor: [
                                                    'rgba(255, 99, 132, 0.2)',
                                                    'rgba(54, 162, 235, 0.2)',
                                                    'rgba(255, 206, 86, 0.2)',
                                                    'rgba(75, 192, 192, 0.2)',
                                                    'rgba(153, 102, 255, 0.2)',
                                                    'rgba(255, 159, 64, 0.2)',
                                                    'rgba(255, 99, 132, 0.2)',
                                                    'rgba(54, 162, 235, 0.2)',
                                                    'rgba(255, 206, 86, 0.2)',
                                                    'rgba(75, 192, 192, 0.2)',
                                                    'rgba(153, 102, 255, 0.2)',
                                                    'rgba(255, 159, 64, 0.2)'
                                                ],
                                                borderColor: [
                                                    'rgba(255, 99, 132, 1)',
                                                    'rgba(54, 162, 235, 1)',
                                                    'rgba(255, 206, 86, 1)',
                                                    'rgba(75, 192, 192, 1)',
                                                    'rgba(153, 102, 255, 1)',
                                                    'rgba(255, 159, 64, 1)',
                                                    'rgba(255, 99, 132, 1)',
                                                    'rgba(54, 162, 235, 1)',
                                                    'rgba(255, 206, 86, 1)',
                                                    'rgba(75, 192, 192, 1)',
                                                    'rgba(153, 102, 255, 1)',
                                                    'rgba(255, 159, 64, 1)'
                                                ],
                                                borderWidth: 1
                                            }]
                                        },
                                        options: {
                                            scales: {
                                                y: {
                                                    beginAtZero: true
                                                }
                                            }
                                        }
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
