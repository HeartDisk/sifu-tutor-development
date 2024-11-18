<!DOCTYPE html>
<html lang="en, id">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>
        SifuTutor - Invoice
    </title>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet"
    />
    <style>
        @import "https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap";

        * {
            margin: 0 auto;
            padding: 0 auto;
            user-select: none;
        }

        body {
            padding: 10px;
            font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .wrapper-invoice {
            display: flex;
            justify-content: center;
        }

        .wrapper-invoice .invoice {
            height: auto;
            background: #fff;
            padding: 5vh;
            margin-top: 3vh;
            max-width: 110vh;
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #dcdcdc;
        }

        .wrapper-invoice .invoice .invoice-information {
            float: right;
            text-align: right;
        }

        .wrapper-invoice .invoice .invoice-information b {
            color: "#0F172A";
        }

        .wrapper-invoice .invoice .invoice-information p {
            font-size: 2vh;
            color: gray;
        }

        .wrapper-invoice .invoice .invoice-logo-brand h2 {
            text-transform: uppercase;
            font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
            font-size: 2.9vh;
            color: "#0F172A";
        }

        .wrapper-invoice .invoice .invoice-logo-brand img {
            max-width: 100px;
            width: 100%;
            object-fit: fill;
        }

        .wrapper-invoice .invoice .invoice-head {
            display: flex;
            margin-top: 8vh;
        }

        .wrapper-invoice .invoice .invoice-head .head {
            width: 100%;
            box-sizing: border-box;
        }

        .wrapper-invoice .invoice .invoice-head .client-info {
            text-align: left;
        }

        .wrapper-invoice .invoice .invoice-head .client-info h2 {
            font-weight: 500;
            letter-spacing: 0.3px;
            font-size: 2vh;
            color: "#0F172A";
        }

        .wrapper-invoice .invoice .invoice-head .client-info p {
            font-size: 2vh;
            color: gray;
        }

        .wrapper-invoice .invoice .invoice-head .client-data {
            text-align: right;
        }

        .wrapper-invoice .invoice .invoice-head .client-data h2 {
            font-weight: 500;
            letter-spacing: 0.3px;
            font-size: 2vh;
            color: "#0F172A";
        }

        .wrapper-invoice .invoice .invoice-head .client-data p {
            font-size: 2vh;
            color: gray;
        }

        .wrapper-invoice .invoice .invoice-body {
            margin-top: 8vh;
        }

        .wrapper-invoice .invoice .invoice-body .table {
            border-collapse: collapse;
            width: 100%;
        }

        .wrapper-invoice .invoice .invoice-body .table thead tr th {
            font-size: 2vh;
            border: 1px solid #dcdcdc;
            text-align: left;
            padding: 1vh;
            background-color: #eeeeee;
        }

        .wrapper-invoice .invoice .invoice-body .table tbody tr td {
            font-size: 2vh;
            border: 1px solid #dcdcdc;
            text-align: left;
            padding: 1vh;
            background-color: #fff;
        }

        .wrapper-invoice .invoice .invoice-body .table tbody tr td:nth-child(2) {
            text-align: right;
        }

        .wrapper-invoice .invoice .invoice-body .flex-table {
            display: flex;
        }

        .wrapper-invoice .invoice .invoice-body .flex-table .flex-column {
            width: 100%;
            box-sizing: border-box;
        }

        .wrapper-invoice .invoice .invoice-body .flex-table .flex-column .table-subtotal {
            border-collapse: collapse;
            box-sizing: border-box;
            width: 100%;
            margin-top: 2vh;
        }

        .wrapper-invoice .invoice .invoice-body .flex-table .flex-column .table-subtotal tbody tr td {
            font-size: 2vh;
            border-bottom: 1px solid #dcdcdc;
            text-align: left;
            padding: 1vh;
            background-color: #fff;
        }

        .wrapper-invoice .invoice .invoice-body .flex-table .flex-column .table-subtotal tbody tr td:nth-child(2) {
            text-align: right;
        }

        .wrapper-invoice .invoice .invoice-body .invoice-total-amount {
            margin-top: 1rem;
        }

        .wrapper-invoice .invoice .invoice-body .invoice-total-amount p {
            font-weight: bold;
            color: "#0F172A";
            text-align: right;
            font-size: 2vh;
        }

        .wrapper-invoice .invoice .invoice-footer {
            margin-top: 4vh;
        }

        .wrapper-invoice .invoice .invoice-footer p {
            font-size: 1.7vh;
            color: gray;
        }

        .copyright {
            margin-top: 2rem;
            text-align: center;
        }

        .copyright p {
            color: gray;
            font-size: 1.8vh;
        }

        @media print {
            .table thead tr th {
                -webkit-print-color-adjust: exact;
                background-color: #eeeeee !important;
            }

            .copyright {
                display: none;
            }
        }

        .rtl {
            direction: rtl;
            font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
        }

        .rtl .invoice-information {
            float: left !important;
            text-align: left !important;
        }

        .rtl .invoice-head .client-info {
            text-align: right !important;
        }

        .rtl .invoice-head .client-data {
            text-align: left !important;
        }

        .rtl .invoice-body .table thead tr th {
            text-align: right !important;
        }

        .rtl .invoice-body .table tbody tr td {
            text-align: right !important;
        }

        .rtl .invoice-body .table tbody tr td:nth-child(2) {
            text-align: left !important;
        }

        .rtl .invoice-body .flex-table .flex-column .table-subtotal tbody tr td {
            text-align: right !important;
        }

        .rtl .invoice-body .flex-table .flex-column .table-subtotal tbody tr td:nth-child(2) {
            text-align: left !important;
        }

        .rtl .invoice-body .invoice-total-amount p {
            text-align: left !important;
        }

        /*# sourceMappingURL=invoice.css.map */

    </style>
</head>
<body>
<section class="wrapper-invoice">
    <!-- switch mode rtl by adding class rtl on invoice class -->
    <div class="invoice">
        <div class="invoice-information">
            <p><b>Invoice #</b> : {{$invoice_detail->id}}<br/>
                <b>Created Date </b>: {{$invoice_detail->created_at}}<br/>
        </div>
        <!-- logo brand invoice -->
        <div class="invoice-logo-brand">
            <!-- <h2>Tampsh.</h2> -->
            <img src="{{url("/template/login.png")}}" alt=""/>
        </div>
        <!-- invoice head -->
        <div class="invoice-head">
            <div class="head client-info">
                <p>{{$customer->full_name}}<br/>
                    {{$customer->address1}}<br/>
            </div>
            <div class="head client-data">

            </div>
        </div>
        <!-- invoice body-->
        <div class="invoice-body">
            <table class="table">
                <thead>
                <tr>
                    <th>Description of Classes</th>
                    <th>Hours</th>
                    <th>Subject Price</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $totaAttendedHours = 0;
                    $totalAmount = 0;
                @endphp
                {{--                 @foreach($invoice_items as $rowInvoiceItems)--}}
                {{--                    <tr>--}}
                {{--                       <td class="tb-col"><span>--}}
                {{--                           {{ \Carbon\Carbon::parse($rowInvoiceItems->invoiceDate)->format('F') }} - {{$students->full_name}} - {{$subjects->name}}--}}
                {{--                        </span></td>--}}
                {{--                       <td class="tb-col"><span>--}}
                {{--                           @php --}}
                {{--                                echo round($rowInvoiceItems->quantity);--}}
                {{--                                $totaAttendedHours+=round($rowInvoiceItems->quantity);--}}
                {{--                            @endphp --}}
                {{--                           --}}
                {{--                           </span></td>--}}
                {{--                       <td class="tb-col"><span>@php echo $subjects->price; @endphp </span></td>--}}
                {{--                       <td class="tb-col"><span>--}}
                {{--                            @php --}}
                {{--                                echo $rowInvoiceItems->quantity * $subjects->price; --}}
                {{--                                $totalAmount+=$rowInvoiceItems->quantity * $subjects->price;--}}
                {{--                            @endphp </span></td>--}}
                {{--                    </tr>--}}
                {{--                 @endforeach--}}
    
                   @php
                    $rowInvoiceItems=$invoice_items[0];
                @endphp
                
                <tr>
                    <td class="tb-col"><span>
                         {{$subjects->name}}
                    </span></td>
                    <td class="tb-col"><span>
                        @php
                            echo $rowInvoiceItems->quantity;
                            $totaAttendedHours += $rowInvoiceItems->quantity;
                        @endphp
                    </span></td>
                    <td class="tb-col"><span>@php echo $subjects->category_price; @endphp </span></td>
                    <td class="tb-col"><span>
                    @php
                        echo $rowInvoiceItems->quantity * $subjects->category_price;
                        $totalAmount += $rowInvoiceItems->quantity * $subjects->category_price;
                    @endphp </span></td>
                </tr>

                </tbody>
            </table>
            <div class="flex-table">
                <div class="flex-column"></div>
                <div class="flex-column">
                    <table class="table-subtotal">
                        <tbody>
                        <tr>
                            <td>Total Attended Hours</td>
                            <td>{{$invoice_detail->classFrequency}}</td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td>{{$invoice_detail->classFrequency*$subjects->category_price}}</td>
                        </tr>
{{--                        <tr>--}}
{{--                            <td>Commetment Fees</td>--}}
{{--                            <td>-50</td>--}}
{{--                        </tr>--}}
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- invoice total  -->
            <div class="invoice-total-amount">
                <p>Total : {{$invoice_detail->invoiceTotal}}</p>
            </div>
        </div>
        <!-- invoice footer -->
        <div class="invoice-footer">
            <table class="table table-responsive no-border">
                <tbody>
                <tr>
                    <td><strong>Payer Name: </strong> {{$invoice_detail->payerName}}</td>
                    <td><strong>Payer Email: </strong> {{$invoice_detail->payerEmail}}</td>
                    <td><strong>Payer Phone Number: </strong> {{$invoice_detail->payerPhone}}</td>
                </tr>
                <tr>
                    <td colspan="3"><strong>Management Remark: </strong> {{$invoice_detail->remarks}}</td>
                </tr>
                <tr>
                    <td colspan="3"><strong>Customer Remark: </strong> <br/>
                        1) This invoice is computer-generated and no signature is required.<br/>
                        2) Payment is due within 3 working days of issuance of this invoice.<br/>
                        3) You can pay online via online banking by clicking the button PAY NOW or alternatively can
                        transfer to account no below :<br/>
                        <br/><strong>MAYBANK - 562115516678 SIFU EDU & LEARNING SDN BHD</strong></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
</body>
</html>
