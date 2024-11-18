@extends('layouts.main')

@section('content')

<div class="nk-content">
    <div class="fluid-container">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head">
                    <div class="nk-block-head-between flex-wrap gap g-2">
                        <div class="nk-block-head-content">
                            <h2 class="nk-block-title">TOTAL CLASS BY WEEKDAY/WEEKEND</h1>
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
                        
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
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
                                        <td>{{ $data['Weekday']['8am – 12pm'] }}</td>
                                        <td>{{ $data['Weekday']['12pm – 4pm'] }}</td>
                                        <td>{{ $data['Weekday']['4pm – 8pm'] }}</td>
                                        <td>{{ $data['Weekday']['8pm – 12am'] }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Weekend</th>
                                        <td>{{ $data['Weekend']['8am – 12pm'] }}</td>
                                        <td>{{ $data['Weekend']['12pm – 4pm'] }}</td>
                                        <td>{{ $data['Weekend']['4pm – 8pm'] }}</td>
                                        <td>{{ $data['Weekend']['8pm – 12am'] }}</td>
                                    </tr>
                                </tbody>
                                <tfoot class="tfoot-light">
                                    <tr>
                                        <td>Total</td>
                                        <td>{{ $data['Total']['8am – 12pm'] }}</td>
                                        <td>{{ $data['Total']['12pm – 4pm'] }}</td>
                                        <td>{{ $data['Total']['4pm – 8pm'] }}</td>
                                        <td>{{ $data['Total']['8pm – 12am'] }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <p><i>**Based on classes scheduled from December - 2022 to June - 2023 </i></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
