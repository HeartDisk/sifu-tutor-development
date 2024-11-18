@extends('layouts.main')

@section('content')

<style>
    .card {
        box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;
    }
</style>

<div style="margin-top:50px;" class="nk-content">
    <div class="fluid-container">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head">
                    <div class="nk-block-head-between flex-wrap gap g-2 align-items-center">
                        <div class="nk-block-head-content">
                            <div class="d-flex flex-column flex-md-row align-items-md-center">
                                <div class="mt-3 mt-md-0 ms-md-3">
                                    <h3 class="title mb-1">General Ledger</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="nk-block">
                    <div class="card card-gutter-md">
                        <!--<div class="card-body">-->
                        <!--    <div class="card">-->
                                <!--<div class="card-header">-->
                                <!--    <h3>GENERAL LEDGER</h3>-->
                                <!--</div>-->
                                <div class="card-body">
                                    <div class="accordion" id="accordionPanelsStayOpenExample">
                                        @foreach($result as $accountName => $entries)
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#{{ Str::slug($accountName) }}">
                                                        <strong style="margin-right:20px">{{ $accountName }}:   </strong><br/>
                                                        <div style="float:right;">RM {{ number_format($entries->sum('ledger.total_amount'),2) }}</div>
                                                    </button>
                                                </h2>
                                                <div id="{{ Str::slug($accountName) }}" class="accordion-collapse collapse">
                                                    <div class="accordion-body">
                                                        <div class="table-responsive">
                                                            <table class="table table-hover table-striped">
                                                                <thead class="thead-light">
                                                                    <tr>
                                                                        <th scope="col">#</th>
                                                                        <th scope="col">Transaction Date</th>
                                                                        <th scope="col">Description</th>
                                                                        <th scope="col">Debit</th>
                                                                        <th scope="col">Credit</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @php $index = 1; @endphp
                                                                    @foreach($entries as $entry)
                                                                        <tr>
                                                                            <td>{{ $index++ }}</td>
                                                                            <td>{{ $entry['ledger']['transactionDate'] }}</td>
                                                                            <td>{{ $entry['ledger']['description'] }}</td>
                                                                            <td>{{ $entry['ledger_item']['debit'] }}</td>
                                                                            <td>{{ $entry['ledger_item']['credit'] }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr style="background-color:#2e314a; color:#fff">
                                                                        <td colspan="3"><strong>Total</strong></td>
                                                                        <td><strong>RM {{ $entries->sum(function($entry) { return $entry['ledger_item']['debit']; }) }}</strong></td>
                                                                        <td><strong>RM {{ $entries->sum(function($entry) { return $entry['ledger_item']['credit']; }) }}</strong></td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                        <!--    </div>-->
                        <!--</div>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
