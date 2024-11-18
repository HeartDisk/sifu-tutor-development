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
                                    BALANCE SHEET</h1>
                                    <nav>
                                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                                            <li class="breadcrumb-item"><a href="#">Balance Sheet</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">Balance Sheet</li>
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

                                <form method="get" action="{{ url('/financialReport/balanceSheet') }}">
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

                            <table class="table" data-nk-container="table-responsive">

                                <thead class="thead-light">
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $a=0;
                                    $b=0;
                                    $c=0;
                                    $d=0;
                                    $e=0;

                                @endphp
                                <tr>
                                    <td style="font-weight: bolder">ASSETS</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bolder">NON-CURRENT ASSETS (4200)</td>
                                    <td></td>
                                </tr>
                                <tr>


                                    @php
                                        $a=$property_plant_equipment;
                                    @endphp


                                    <td>Property, plant and equipment</td>
                                    <td>{{number_format($property_plant_equipment,2)}}</td>
                                </tr>
                                <tr>
                                    <td>TOTAL NON-CURRENT ASSETS</td>
                                    <td>{{number_format($property_plant_equipment,2)}}</td>
                                </tr>

                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td style="font-weight: bolder">CURRENT ASSETS (4100)</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Other receivables and deposits</td>
                                    <td>{{number_format($other_receivable,2)}}</td>
                                </tr>
                                <tr>
                                    <td>Amount due from director</td>
                                    <td>{{number_format($mount_due_director,2)}}</td>
                                </tr>
                                <tr>
                                    <td>Amount due from related company</td>
                                    <td>{{number_format($mount_due_company,2)}}</td>
                                </tr>
                                <tr>
                                    <td>Fixed deposit</td>
                                    <td>{{number_format($fixed_deposit,2)}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    @php
                                        $b=$other_receivable+$mount_due_director+$mount_due_company+$fixed_deposit
                                    @endphp


                                    <td style="font-weight: bolder">TOTAL CURRENT ASSETS</td>
                                    <td>{{number_format($b,2)}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>


                                <tr>
                                    <td>TOTAL ASSETS</td>
                                    <td>{{number_format($a+$b,2)}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td style="font-weight: bolder">EQUITY (3000)</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Share capital</td>
                                    <td>{{number_format($share_capital,2)}}</td>
                                </tr>
                                <tr>
                                    <td>Retained profits</td>
                                    <td>{{number_format($retained_profits,2)}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>


                                <tr>
                                    @php
                                        $c=$share_capital+$retained_profits;
                                    @endphp

                                    <td>TOTAL EQUITY</td>
                                    <td>{{number_format($c,2)}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>


                                <tr>
                                    <td style="font-weight: bolder">NON-CURRENT LIABILITIES (5200)</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>Finance lease</td>
                                    <td>{{number_format($finance_lease,2)}}</td>
                                </tr>

                                <tr>
                                    <td>Bank borrowings</td>
                                    <td>{{number_format($bank_borrowings,2)}}</td>
                                </tr>

                                <tr>
                                    <td>Deferred tax liabilities</td>
                                    <td>{{number_format($deferred_tax_liabilities,2)}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    @php
                                        $d=$finance_lease+$bank_borrowings+$deferred_tax_liabilities
                                    @endphp
                                    <td>TOTAL NON-CURRENT LIABILITIES</td>
                                    <td>{{number_format($d,2)}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td style="font-weight: bolder">CURRENT LIABILITIES (5100)</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>Other payables and accruals</td>
                                    <td>{{number_format($other_payables_and_accruals,2)}}</td>
                                </tr>


                                <tr>
                                    <td>Finance lease</td>
                                    <td>{{number_format($financelease_current_libility,2)}}</td>
                                </tr>

                                <tr>
                                    <td>Bank borrowings</td>
                                    <td>{{number_format($bank_borrowings_current_libility,2)}}</td>
                                </tr>

                                <tr>
                                    <td>Amount due of directors</td>
                                    <td>{{number_format($mount_due_director_current_liabilitty,2)}}</td>
                                </tr>

                                <tr>
                                    <td>Current tax liabilities</td>
                                    <td>{{number_format($current_tax_liabilities,2)}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    @php
                                        $e=$other_payables_and_accruals+$financelease_current_libility+$bank_borrowings_current_libility+$mount_due_director_current_liabilitty+$current_tax_liabilities;
                                    @endphp

                                    <td style="font-weight: bolder">TOTAL CURRENT LIABILITIES</td>
                                    <td>{{number_format($e,2)}}</td>
                                </tr>

                                <tr>
                                    <td style="font-weight: bolder">TOTAL LIABILITIES</td>
                                    <td>{{number_format($d+$e,2)}}</td>
                                </tr>

                                <tr>
                                    <td style="font-weight: bolder">TOTAL EQUITY AND LIABILITIES</td>
                                    <td>{{number_format($c+$d+$e,2)}}</td>
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

