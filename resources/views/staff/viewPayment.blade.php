@extends('layouts.main')

@section('content')

    <style>
        .progress .progress-bar {
            height: 4px;
        }

        .row-details {
            border-bottom: 1px solid grey;
        }

        td {
            font-size: 14px;
            color: #696969;
        }
    </style>

    <div style="padding-top:100px;" class="container">

        <div class="nk-content">
            <div class="fluid-container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block">
                            <div class="card">
                                
                              
                                @if(session("success"))
                                    <div class="alert alert-success" role="alert">
                                        {{session("success")}}
                                    </div>
                                @endif

                                <div class="nk-invoice">
                                    <div class="nk-invoice-head flex-column flex-sm-row">
                                        <p>
                                            <a href="{{url("/downloadStaffPaymentSlip")."/".$payment_slip->id}}">
                                                <button class="btn btn-primary btn-sm">Download</button>
                                            </a>
                                        </p>
                                        <p>
                                            <a href="{{url("/sendStaffPaymentSlip")."/".$payment_slip->id}}">
                                            <button class="btn btn-primary btn-sm">Send Payment Slip</button>
                                            </a>
                                        </p>


                                    </div>
                                    <div class="nk-invoice-head flex-column flex-sm-row">
                                        <div class="nk-invoice-head-item mb-3 mb-sm-0">
                                            <div class="nk-invoice-brand mb-1">
                                                <img style="width:250px;" src="{{url('template/login.png')}}"/>
                                            </div>
                                            <ul>
                                                <!--<li>info@company.com</li>-->
                                                <!--<li>(120) 456 789</li>-->


                                            </ul>
                                        </div>
                                        <div class="nk-invoice-head-item text-sm-end">
                                            <h1>{{$payment_slip->name}}</h1>
                                            <ul>
                                                <li class="text"><strong> Issue
                                                        Date: </strong>{{$payment_slip->payment_date}}</li>
                                            </ul>
                                            {{--                                            <div>--}}
                                            {{--                                                <strong> Public Link </strong> <a target="_blank" href="{{url('invoicePublicLink',$payment_slip->id)}}"><i style="font-size:31px" class="fa fa-link" aria-hidden="true"></i></a>--}}
                                            {{--                                                <strong> Download PDF </strong> <a target="_blank" href="{{url('pdfFile',$payment_slip->id)}}"><i style="font-size:31px" class="fa fa-file-pdf-o" aria-hidden="true"></i></a>--}}
                                            {{--                                                <strong> Send Email </strong> <a target="_blank" href="{{url('sendEmailInvoice',$payment_slip->id)}}"><i style="font-size:31px" class="fa fa-envelope" aria-hidden="true"></i></a>--}}
                                            {{--                                            </div>--}}
                                        </div>
                                    </div>

                                    <div class="nk-invoice-head flex-column flex-sm-row">

                                        <table class="table table-responsive no-border">
                                            <tbody>

                                            <tr>
                                                <td colspan="2"><strong>Nett
                                                        Pay: </strong> {{$payment_slip->nett_amount}}</td>
                                                <td colspan="2"><strong>Total: </strong> {{$payment_slip->total}}</td>


                                            </tr>

                                            <tr>
                                                <td><strong>Salary Month: </strong> {{$payment_slip->salary_month}}</td>
                                                <td><strong>Salary Year: </strong> {{$payment_slip->salary_year}}</td>
                                                <td><strong>Basic Salary: </strong> {{$payment_slip->basic_salary}}</td>


                                            </tr>

                                            <tr>

                                                <td><strong>Bonus Amount: </strong> {{$payment_slip->bonus_amount}}
                                                </td>
                                                <td><strong>Commission: </strong> {{$payment_slip->comission}}</td>
                                                <td><strong>Overtime Amount (Per
                                                        Hour): </strong> {{$payment_slip->overtime_amount_perhour}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Overtime (Hour): </strong> {{$payment_slip->overtime_hour}}
                                                </td>
                                                <td><strong>Claim: </strong> {{$payment_slip->claim}}</td>
                                                <td><strong>No. of unpaid
                                                        leave: </strong> {{$payment_slip->no_unpaid_leave}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Deduction: </strong> {{$payment_slip->deduction}}</td>
                                                <td><strong>Paying Account: </strong> {{$payment_slip->paying_account}}
                                                </td>

                                                <td><strong>Remarks: </strong> {{$payment_slip->remark}}</td>
                                            </tr>


                                            </tbody>
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

@endsection

