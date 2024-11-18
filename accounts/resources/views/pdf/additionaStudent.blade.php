<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <title>SifuTutor | Invoice</title>

    <!-- Favicon -->
    <link rel="icon" href="./images/favicon.png" type="image/x-icon"/>
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
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td.title {
            font-size: 30px;
            line-height: 45px;
            color: #ffffff;
            background-color: #000;
            font-weight: 500;
            letter-spacing: 12px;
            margin-bottom: 20px;

        }

        .title p {
            margin: 15px 20px !important;
            text-align: right;
        }


        .invoice-box table tr.information table td {
            padding-bottom: 10px;
        }

        tr.heading {
            padding: 10px;
        }

        .invoice-box table tr.heading td {
            background: #000000;
            border-bottom: 1px solid #ddd;
            font-weight: 500;
            color: #fff;
            padding: 15px 3px;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-align: center;
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
                            <p>INVOICE</p>
                        </td>

                        <td class="fifty" style="
    display: flex;
    align-items: center;
    justify-content: flex-end;
    width: 90%;

">
                            <div class="content">
                                <strong>SIFUTUTOR</strong><br/>
                                <span style="font-weight: 400;">#{{$invoice_detail->id}}</span>
                            </div>

                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="information ">
            <td colspan="2" style="padding: 0;">
                <table>
                    <tr>
                        <td style="width: 50%;padding: 0;">

                            <div class="cinfo">
                                <p style="font-size: 22px;color: #000;font-weight: 500;margin-bottom: -10px; margin-top: 50px;">
                                    BILLING TO:</p>
                                <p>{{$customer->full_name}}</br>
                                    {{$customer->email}}</br>
                                    {{$customer->address1}}</p>
                            </div>


                        </td>

                        <td>

                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table style="margin-top: 30px;">
        <tr class="heading">
            <td style="width:140px">Class Description</td>
            <td>Duration</td>
            <td>Frequencyy</td>
            <td>Subject Price</td>
            <td>Total</td>
        </tr>
        @php $totaAttendedHours = 0; $totalAmount = 0; @endphp @php
            $rowInvoiceItems=$invoice_items[0];

        @endphp
        <tr class="details">
            <td class="tb-col"><span>{{$subjects->name}}</span></td>

            <td class="tb-col"><span>@php echo $invoice_detail->quantity; @endphp</span></td>
            <td class="tb-col"><span>@php echo $remaining_classes; @endphp</span>
            </td>
            <td class="tb-col"><span>RM @php echo $subjects->category_price; @endphp</span></td>
            <td class="tb-col"><span>RM @php echo $invoice_detail->invoiceTotal;@endphp</span>
            </td>
        </tr>

    </table>
    <table>
        <tr class="total">
            <td style="
    width: 0;
    padding: 0;
    margin: 0;
"></td>


        </tr>


        <tr class="total">
            <td></td>

            <td>
                <div class="invoice-total-amount">


                    <p>Total : RM {{$invoice_detail->invoiceTotal}}</p>


                </div>
            </td>
        </tr>
    </table>
    <div class="invoice-footer"
         style="text-align: left;display: flex;align-items: center;margin-top: 15px;margin-bottom: 15px;">
        <div>
            <p><strong>Payer Name: </strong> {{$invoice_detail->payerName}}</p>
            <p><strong>Payer Email: </strong> {{$invoice_detail->payerEmail}}</p>
            <p><strong>Payer Phone Number: </strong>
                {{$invoice_detail->payerPhone}}</p>
            <p><strong>Management Remark: </strong> {{$invoice_detail->remarks}}</p>
            <p><strong>Customer Remark: </strong> <br/>
                1) This invoice is computer-generated and no signature is required.<br/>
                2) Payment is due within 3 working days of issuance of this
                invoice.<br/>
                3) You can pay online via online banking by clicking the button PAY
                NOW or alternatively can transfer to account no below :<br/>
                <br/><strong>MAYBANK - 562115516678 SIFU EDU & LEARNING SDN BHD</strong>
            </p>
        </div>
    </div>
    <table>
        <tr>
            <td
                style="width: 100%;padding: 20px;background-color: #000;color: #fff;font-weight: 500;letter-spacing: 2px;font-size: 14px;">
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
