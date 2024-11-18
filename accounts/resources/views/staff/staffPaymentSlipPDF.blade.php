<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Staff Payment Slip</title>

    <!-- Favicon -->
    <link rel="icon" href="./images/favicon.png" type="image/x-icon" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

    <!-- Invoice styling -->
    <style>
        body {
            font-family: "Outfit", sans-serif;
            text-align: center;
            color: #777;
        }

        body h1 {
            font-weight: 300;
            margin-bottom: 0px;
            padding-bottom: 0px;
            color: #000;
        }

        body h3 {
            font-weight: 300;
            margin-top: 10px;
            margin-bottom: 20px;
            font-style: italic;
            color: #555;
        }

        body a {
            color: #06f;
        }

        .invoice-box {
            max-width: 600px;
            margin: auto;
            font-size: 12px;
            line-height: 14px;
            /* font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; */
            color: #555;
            padding-top: 30px;
            overflow: hidden;
            padding: 10px;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }


        .invoice-box table tr.top table td.title {
            font-size: 30px;
            color: #444444;
            font-weight: 500;
            margin-bottom: 20px;

        }

        .invoice-box table tr.information table td {
            padding-bottom: 10px;
        }

        tr.heading {
            padding: 10px;
        }

        .value-tab td {
            padding: 10px;
            text-align: center;
        }

        .invoice-box table tr.heading td {
            background: #000;
            border-bottom: 1px solid #ddd;
            font-weight: 500;
            color: #fff;
            padding: 15px 3px;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-align: center;
            font-size: 11px;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
            text-align: center;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #707070;
            font-weight: 500;
            padding: 20px 0;
        }

        .invoice-footer p {
            font-size: 12px;
        }

        .fifty {
            width: 50%;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        .commitment {
            font-weight: 400;
        }

        span.box {
            display: block;
            width: 40px;
            height: 40px;
            background: #000;
            margin-left: 20px;
        }


        .line {
            display: block;
            width: 60%;
            height: 3px;
            background-color: #000;
            margin-top: 10px;
            border-radius: 0 20px 20px 0;

        }

        .titles {
            font-weight: 600;
            font-size: 13px;

        }
    </style>
</head>

<body>
<div class="invoice-box">
    <table>
        <tr class="top">
            <td colspan="2" style="padding: 0;margin-bottom: 20px;">
                <table>
                    <tr>
                        <td class="title fifty">
                            <p>Payment Slip</p>
                            <img src="https://sifututor.odits.co/new/template/login.png" width="120px">
                        </td>

                    </tr>
                </table>
            </td>
        </tr>

        <tr class="information ">
            <td colspan="2" style="padding: 0;">
                <table>
                    <tr>
                        <p
                            style="font-size: 20px;color: #000;font-weight: 500;margin-bottom: -2px; margin-top: 10px;">
                            Staff Details:</p>
                    </tr>
                    <tr>
                        <td style="width: 50%;">

                            <div class="cinfo">

                                <p><strong>{{$payment_slip->name}}</p>


                            </div>


                        </td>
                        <td style="width: 50%;">

                            <div class="cinfo">

                                <p><strong> Issue Date: </strong>{{$payment_slip->payment_date}}</p>

                            </div>


                        </td>


                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="value-tab" style="margin: 15px 0;">
        <tr class="heading">
            <td>Nett Pay</td>
            <td></td>
            <td>Total</td>
        </tr>
        <tr>
            <td>{{$payment_slip->nett_amount}}</td>
            <td></td>
            <td>{{$payment_slip->total}}</td>

        </tr>
    </table>
    <table class="value-tab" style="margin: 15px 0;">
        <tr class="heading">
            <td>Salary Month</td>
            <td>Salary Year</td>
            <td>Basic Salary</td>
        </tr>
        <tr>
            <td>{{$payment_slip->salary_month}}</td>
            <td>{{$payment_slip->salary_year}}</td>
            <td>{{$payment_slip->basic_salary}}</td>
        </tr>
    </table>
    <table class="value-tab" style="margin: 15px 0;">
        <tr class="heading">
            <td>Bonus Amount</td>
            <td>Commission</td>
            <td>Overtime Amount (Per Hour)</td>
        </tr>
        <tr>
            <td>{{$payment_slip->bounus_amount}}</td>
            <td>{{$payment_slip->comission}}</td>
            <td>{{$payment_slip->overtime_amount_perhour}}</td>
        </tr>
    </table>
    <table class="value-tab" style="margin: 15px 0;">
        <tr class="heading">
            <td>Overtime (Hour)</td>
            <td>Claim</td>
            <td>No. of unpaid leave</td>
        </tr>
        <tr>
            <td>{{$payment_slip->overtime_hour}}</td>
            <td>{{$payment_slip->claim}}</td>
            <td>{{$payment_slip->no_unpaid_leave}}</td>
        </tr>
    </table>
    <table class="value-tab" style="margin: 15px 0;">
        <tr class="heading">
            <td>Deduction</td>
            <td>Paying Account</td>
            <td>Management Remark</td>
        </tr>
        <tr>
            <td>{{$payment_slip->deduction}}</td>
            <td>{{$payment_slip->paying_account}}</td>
            <td> {{$payment_slip->remark}}</td>
        </tr>
    </table>


    <hr>
    <table>
        <tr>
            <td
                style="width: 100%;padding: 10px 0;color: #4b4b4b;font-weight: 500;letter-spacing: 2px;font-size: 14px;">
                <table>
                    <tr>
                        <td>
                            <div class="social">
                                @Sifututor
                            </div>
                        </td>
                        <td>
                            <div class="email">
                                email@Sifututor.com
                            </div>
                        </td>
                    </tr>
                </table>

            </td>

        </tr>
    </table>
    </td>
    </tr>
    </table>
</div>
</body>

</html>
