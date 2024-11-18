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
                                    CASH FLOW</h2>
                                    <nav>
                                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                                            <li class="breadcrumb-item"><a href="#">Cash Flow</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">Cash Flow</li>
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
                                    <td style="font-weight: bolder">CASH FLOWS FROM OPERATING ACTIVITIES</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Profit before taxation</td>
                                    <td>{{number_format($profit_before_taxation,2)}}</td>
                                </tr>
                                <tr>
                                    <td>Adjustment for:</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>Depreciatition of property, plant and equitpment</td>
                                    <td>{{number_format($depreciation_property_pant_equiment,2)}}</td>
                                </tr>

                                <tr>
                                    <td>Interest expense</td>
                                    <td>{{number_format($interest_expense,2)}}</td>
                                </tr>

                                <tr>
                                    <td>Operating profit before working capital changes</td>
                                    <td>{{number_format($operating_profit_before_working_capital,2)}}</td>
                                </tr>

                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td style="font-weight: bolder">Changes in working capital:</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>Amount due from directors</td>
                                    <td>{{number_format($amount_due_director,2)}}</td>
                                </tr>

                                <tr>
                                    <td>Amount due from related company</td>
                                    <td>{{number_format($amount_due_company,2)}}</td>
                                </tr>

                                <tr>
                                    <td>Payables</td>
                                    <td>{{number_format($other_payable,2)}}</td>
                                </tr>

                                <tr>
                                    <td>Amount due to directors</td>
                                    <td>{{number_format($amount_due_to_director,2)}}</td>
                                </tr>

                                <tr>
                                    <td>Amount due to related company</td>
                                    <td>{{number_format($amount_due_to_company,2)}}</td>
                                </tr>


                                <tr>
                                    <td style="font-weight: bolder">Cash Used in operation</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>Interest paid</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>Tax paid</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td style="font-weight: bolder">Net cash used in operations activities</td>
                                    <td>{{number_format($net_cash_used_in_operation_activities,2)}}</td>
                                </tr>

                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td style="font-weight: bolder">CASH FLOWS FROM INVESTING ACTIVITIES</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>Purchase of property, plant and equitment</td>
                                    <td>{{number_format($property_plant_equipment,2)}}</td>
                                </tr>

                                <tr>
                                    <td style="font-weight: bolder">Net cash used in investing activities</td>
                                    <td>{{number_format($net_cash_used_in_investing_activities,2)}}</td>
                                </tr>

                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td style="font-weight: bolder">CASH FLOW FROM FINANCING ACTIVITIES</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>Proceeds from issuance of share capital</td>
                                    <td>{{number_format($share_capital,2)}}</td>
                                </tr>
                                <tr>
                                    <td>Proceeds from bank borrowings</td>
                                    <td>{{number_format($bank_borrowings,2)}}</td>
                                </tr>
                                <tr>
                                    <td>Repayment of bank borrowings</td>
                                    <td>{{number_format($bank_borrowings,2)}}</td>
                                </tr>
                                <tr>
                                    <td>Repayment of finance lease</td>
                                    <td>{{number_format($finance_lease,2)}}</td>
                                </tr>
                                <td></td>
                                <td></td>

                                <tr>
                                    <td style="font-weight: bolder">Net cash from financing activities</td>
                                    <td>{{number_format($net_cash_from_financing_activities,2)}}</td>
                                </tr>
                                <td></td>
                                <td></td>

                                <tr>
                                    <td style="font-weight: bolder">Net Increase in cash and cash equivalents</td>
                                     <td>{{number_format($net_cash_from_financing_activities,2)}}</td>
                                </tr>
                                <td></td>
                                <td></td>

                                <tr>
                                    <td style="font-weight: bolder">Cash and cash equivalents brough forward</td>
                                    <td>{{number_format($cash_and_cash_brough_forward,2)}}</td>
                                </tr>

                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td style="font-weight: bolder">Cash and cash equivalents carried foward</td>
                                    <td>{{number_format($cash_and_cash_carried_forward,2)}}</td>
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

