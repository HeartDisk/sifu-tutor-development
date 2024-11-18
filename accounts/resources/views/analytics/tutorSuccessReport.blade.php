@extends('layouts.main')

@section('content')

    <div class="nk-content">
        <br/>
        <br/>
        <br/>
        <div class="fluid-container">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head">
                        <div class="nk-block-head-between flex-wrap gap g-2">
                            <div class="nk-block-head-content">
                                <h2 class="nk-block-title">
                                    TUTOR SUCCESS REPORT</h1>
                                    <nav>
                                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                                            <li class="breadcrumb-item"><a href="#">Analytics</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">Tutor Success
                                                Report
                                            </li>
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

                            <br/><br/>
                            <form method="get" action="{{url("/analytics/tutorsuccessreport")}}"
                                  enctype="multipart/form-data">

                                <div class="row">
                                    <div class="col-md-4">
                                        <lable>Month</lable>
                                        <select class="form-control" name="month">
                                            <option value="" {{ $currentMonth == 1 ? 'selected' : '' }}>All</option>
                                            <option value="1" {{ $currentMonth == 1 ? 'selected' : '' }}>January
                                            </option>
                                            <option value="2" {{ $currentMonth == 2 ? 'selected' : '' }}>February
                                            </option>
                                            <option value="3" {{ $currentMonth == 3 ? 'selected' : '' }}>March</option>
                                            <option value="4" {{ $currentMonth == 4 ? 'selected' : '' }}>April</option>
                                            <option value="5" {{ $currentMonth == 5 ? 'selected' : '' }}>May</option>
                                            <option value="6" {{ $currentMonth == 6 ? 'selected' : '' }}>June</option>
                                            <option value="7" {{ $currentMonth == 7 ? 'selected' : '' }}>July</option>
                                            <option value="8" {{ $currentMonth == 8 ? 'selected' : '' }}>August</option>
                                            <option value="9" {{ $currentMonth == 9 ? 'selected' : '' }}>September
                                            </option>
                                            <option value="10" {{ $currentMonth == 10 ? 'selected' : '' }}>October
                                            </option>
                                            <option value="11" {{ $currentMonth == 11 ? 'selected' : '' }}>November
                                            </option>
                                            <option value="12" {{ $currentMonth == 12 ? 'selected' : '' }}>December
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <lable>Year</lable>
                                        <select class="form-control" name="year">
                                            <option value="">All</option>
                                            @for ($year = 2000; $year <= 2050; $year++)
                                                <option
                                                    value="{{ $year }}" {{ $currentYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Date</label>
                                        <input type="date" class="form-control" name="custom_date">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-success">Search</button>
                                    </div>
                                </div>
                            </form>

                            <br/><br/>

                            <div class="row">

                                <div class="col-md-6">
                                    <canvas id="myChart"></canvas>
                                </div>
                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped">
                                            <thead class="thead-light">
                                            <tr>
                                                <th scope="col"></th>
                                                <th scope="col">Total</th>
                                                <th scope="col">Verified</th>
                                                <th scope="col">Unverified</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <th scope="row">Tutors</th>
                                                <!--<td>{{$verified."(".$verified_percentage."%".")"}}</td>-->
                                                <!--<td>{{$unverified."(".$unverified_percentage."%".")"}}</td>-->
                                                 <td>{{count($tutors)}}</td>
                                                 <td>{{$verified}}</td>
                                                <td>{{$unverified}}</td>

                                            </tr>
                                            </tbody>
                                            <tfoot class="tfoot-light">

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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    const ctx = document.getElementById('myChart');

// Assuming verified, unverified, verifiedPercentage, and unverifiedPercentage might be undefined
let verifiedPercentage = {{$verified_percentage}} || 0;
let unverifiedPercentage = {{$unverified_percentage}} || 0;
let verified = {{$verified}} || 0;
let unverified = {{$unverified}} || 0;

// Calculate total percentage
let totalPercentage = verifiedPercentage + unverifiedPercentage;

// Calculate percentage values with fallback for totalPercentage being zero
let verifiedLabel = totalPercentage > 0 ? `${((verifiedPercentage / totalPercentage) * 100).toFixed(2)}%` : '0.00%';
let unverifiedLabel = totalPercentage > 0 ? `${((unverifiedPercentage / totalPercentage) * 100).toFixed(2)}%` : '0.00%';

new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: [
            'Verified (' + verifiedLabel + ')',
            'Unverified (' + unverifiedLabel + ')',
        ],
        datasets: [{
            label: 'Tutor Success Report',
            data: [verified, unverified],
            backgroundColor: [
                'rgb(29,206,174)',
                'rgb(42,82,214)',
            ],
            hoverOffset: 4
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
@endsection

