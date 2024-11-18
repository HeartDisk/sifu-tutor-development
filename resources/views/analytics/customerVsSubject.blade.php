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
                                    Customer vs Subject
                                </h2>
                                <nav>
                                    <ol class="breadcrumb breadcrumb-arrow mb-0">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item"><a href="#">Analytics</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Customer vs Subject</li>
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

                                <!-- Filter Form -->
                                <form method="GET" action="{{ route('analytics/customerVsSubject') }}">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="month">Month</label>
                                            <select name="month" id="month" class="form-control">
                                                @for ($m = 1; $m <= 12; $m++)
                                                    <option value="{{ $m }}" {{ $m == $currentMonth ? 'selected' : '' }}>
                                                        {{ date('F', mktime(0, 0, 0, $m, 10)) }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="year">Year</label>
                                            <select name="year" id="year" class="form-control">
                                                @for ($y = 2020; $y <= date('Y'); $y++)
                                                    <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>
                                                        {{ $y }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>&nbsp;</label>
                                            <button type="submit" class="btn btn-primary">Filter</button>
                                        </div>
                                    </div>
                                </form>
                                <!-- End Filter Form -->

                                <div class="row headwtb">
                                    <div class="col-sm-12">
                                        <h3>Comparison number of customers by each subject.</h3>
                                    </div>
                                </div>
                                <table class="datatable-init table" data-nk-container="table-responsive">
                                    <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th><span>Subscribed Customers</span><br><small>(for {{$currentMonthFull}}, {{$currentYear}})</small></th>
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
                                    <tfoot>
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
    </div>
@endsection
