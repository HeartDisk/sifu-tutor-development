<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Student Invoice PDF</title>

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


        .invoice-box table tr td:nth-child(2) {
            text-align: right;
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
                            <p>Student Invoice</p>
                            <img src="https://sifututor.odits.co/new/template/login.png" width="120px">
                        </td>

                        <!-- <td class="fifty" style="
    display: flex;
    align-items: center;
    justify-content: flex-end;
    width: 90%;

">
                                <div class="content">
                                    <img src="https://sifututor.odits.co/new/template/login.png" width="140px"><br />
                                    <span style="font-weight: 400;">#{{$invoice_detail->id}}</span>
                                </div>

                            </td> -->
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
                            Invoice Details:</p>
                    </tr>
                    <tr>
                        <td style="width: 50%;">

                            <div class="cinfo">

                                <p><strong>Invoices No: # </strong>{{$invoice_detail->id}}</p>


                            </div>


                        </td>
                        <td style="width: 50%;">

                            <div class="cinfo">

                                <p><strong> Issue Date: </strong>@php echo date('d-m-Y') @endphp</p>

                            </div>


                        </td>


                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table style="margin-top: 30px;">
        <tr class="heading">
            <td style="width: 30%;">Class Description</td>
            <td>Hrs</td>
            <td>Price</td>
            <td>Total</td>
        </tr>
        @php
            $totaAttendedHours = 0;
            $totalAmount = 0;
            $startMonth = null;
        @endphp
        @foreach($invoice_items as $rowInvoiceItems)
            @php
                $invoiceMonth = \Carbon\Carbon::parse($rowInvoiceItems->invoiceDate)->format('m');
            @endphp

            @if ($startMonth === null || $invoiceMonth < $startMonth) 
                @php $startMonth=$invoiceMonth; @endphp 
            @endif 
            @if($invoiceMonth==$startMonth)

                <tr class="details">

                    <td class="tb-col">{{ \Carbon\Carbon::parse($rowInvoiceItems->invoiceDate)->format('F') }} - {{$students->full_name}} - {{$subjects->name}}</td>

                    <td class="tb-col"> @php
                            echo $rowInvoiceItems->quantity;
                            $totaAttendedHours += $rowInvoiceItems->quantity;
                        @endphp </td>
                    <td class="tb-col">@php echo $subjects->price; @endphp
                    </td>
                    <td class="tb-col">@php
                            echo $rowInvoiceItems->quantity * $subjects->price;
                            $totalAmount += $rowInvoiceItems->quantity * $subjects->price;
                        @endphp </td>

                </tr>
            @endif
        @endforeach

        <tfoot class="table-dark">
        <tr>
            <td>&nbsp;</td>
            <td>{{$totaAttendedHours}}</td>
            <td></td>
            <td>Total: <strong>{{$totalAmount}}</strong></td>
        </tr>
        </tfoot>
    </table>
    <div class="container" style="
        text-align: left;
        margin: 30px 0;
    ">
        <div class="row">
            <div class="col-md-4">
                <strong>Payer Name: </strong> <br/>{{$invoice_detail->payerName}}
            </div>
            <div class="col-md-4">
                <strong>Payer Email: </strong><br/> {{$invoice_detail->payerEmail}}
            </div>
            <div class="col-md-4">
                <strong>Payer Phone Number: </strong><br/> {{$invoice_detail->payerPhone}}
            </div>
            <div class="col-md-12">
                <strong>Customer Remark: </strong>  <br/>
                1) This invoice is computer-generated and no signature is required.<br/>
                2) Payment is due within 3 working days of issuance of this invoice.<br/>
                3) You can pay online via online banking by clicking the button PAY NOW or alternatively can transfer to account no below :<br/>
                <strong>MAYBANK - 562115516678 SIFU EDU & LEARNING SDN BHD</strong>
            </div>
        </div>

    </div>




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
