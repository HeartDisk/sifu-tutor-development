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
                                    Total Class by Weekday/Weekend
                                </h2>
                                <nav>
                                    <ol class="breadcrumb breadcrumb-arrow mb-0">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item"><a href="#">Analytics</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Total Class by Weekday/Weekend</li>
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

                                <!-- Date Range Filter Form -->
                                <form action="{{ route('analytics/classesByWeekday') }}" method="GET" class="mb-4">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="from_date">From</label>
                                                <input type="month" name="from_date" id="from_date" class="form-control" value="{{ $from_date }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="to_date">To</label>
                                                <input type="month" name="to_date" id="to_date" class="form-control" value="{{ $to_date }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4 d-flex align-items-end">
                                            <button type="submit" class="btn btn-primary">Filter</button>
                                        </div>
                                    </div>
                                </form>

                                <table class="datatable-init table" data-nk-container="table-responsive">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>8am – 12pm</th>
                                        <th>12pm – 4pm</th>
                                        <th>4pm - 8pm</th>
                                        <th>8pm - 12am</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <th>Weekday</th>
                                        <td>{{ $data['Weekday']['8am – 12pm'] }}</td>
                                        <td>{{ $data['Weekday']['12pm – 4pm'] }}</td>
                                        <td>{{ $data['Weekday']['4pm – 8pm'] }}</td>
                                        <td>{{ $data['Weekday']['8pm – 12am'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Weekend</th>
                                        <td>{{ $data['Weekend']['8am – 12pm'] }}</td>
                                        <td>{{ $data['Weekend']['12pm – 4pm'] }}</td>
                                        <td>{{ $data['Weekend']['4pm – 8pm'] }}</td>
                                        <td>{{ $data['Weekend']['8pm – 12am'] }}</td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td>Total</td>
                                        <td>{{ $data['Total']['8am – 12pm'] }}</td>
                                        <td>{{ $data['Total']['12pm – 4pm'] }}</td>
                                        <td>{{ $data['Total']['4pm – 8pm'] }}</td>
                                        <td>{{ $data['Total']['8pm – 12am'] }}</td>
                                    </tr>
                                    </tfoot>
                                </table>
                                <p><i>**Based on classes scheduled from {{ $from_date }} to {{ $to_date }}</i></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
`
