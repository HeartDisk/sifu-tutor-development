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
                                    TRIAL BALANCE</h1>
                                    <nav>
                                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                                            <li class="breadcrumb-item"><a href="#">Financial Report</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">Trial Balance</li>
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

                                <form method="get" action="{{ url('/financialReport/trialBalance') }}">
                                    @csrf
                                    <div class="row">
                                        @php
                                            $year = request('year', date('Y')); // Get the value of 'year' parameter from the URL or default to current year
                                        @endphp
                                        <div class="col-md-4">
                                            <label for="year">Year</label>
                                            <select class="form-control" id="year" name="year">
                                                <option value="all">All</option>
                                                @foreach (range(2024, 2035) as $yr)
                                                    <option value="{{ $yr }}" {{ $yr == $year ? 'selected' : '' }}>
                                                        {{ $yr }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-primary">Search</button>
                                        </div>
                                    </div>
                                </form>

                            <table class="datatable-init table" data-nk-container="table-responsive">

                                <thead class="thead-light">
                                <tr>
                                    <th scope="col">Account Name</th>
                                    <th scope="col">Debit</th>
                                    <th scope="col">Credit</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Assets</td>
                                    <td>RM {{number_format($assets_sum,2)}}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Expenses</td>
                                    <td>RM {{number_format($expense_sum,2)}}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Capital</td>
                                    <td></td>
                                    <td>RM {{number_format($capital_sum,2)}}</td>
                                </tr>
                                <tr>
                                    <td>Liabilities</td>
                                    <td></td>
                                    <td>RM {{number_format($liability_sum,2)}}</td>
                                </tr>
                                <tr>
                                    <td>Incomes</td>
                                    <td></td>
                                    <td>RM {{number_format($income_sum,2)}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total</strong></td>
                                    <td>RM {{number_format($debit_sum,2)}}</td>
                                    <td>RM {{number_format($credit_sum,2)}}</td>
                                </tr>
                                </tbody>
                            </table>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

