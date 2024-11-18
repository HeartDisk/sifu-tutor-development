<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>A simple, clean, and responsive HTML invoice template</title>

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
                            Tutor Details:</p>
                        </tr>
                        <tr>
                            <td style="width: 50%;">

                                <div class="cinfo">
                                   
                                    <p><strong>Tutor Name: </strong> {{$tutor->full_name}}</p>
                                    <p><strong>Tutor NRIC: </strong> {{$tutor->nric}}</p>
                                       
                                </div>


                            </td>
                            <td style="width: 50%;">

                                <div class="cinfo">

                                    <p> <strong>Month &amp; Year: </strong> {{$tutor_payment->comissionMonth}} {{$tutor_payment->comissionYear}} </p>

                                </div>


                            </td>


                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table style="margin-top: 30px;">
            <tr class="heading">
                <td>Student</td>
                <td>Subject</td>
                <td>Date</td>
                <td>Attended Duration</td>
                <td>Commission</td>
            </tr>
            @php
            $total = 0;
            $additionaltotal=0;
            $deductionstotal=0;
        @endphp
         @foreach($paidClasses as $rowSS)
         @php
                $studentDetail = DB::table('students')->where('id','=',$rowSS->studentID)->first();
                $subjectDetail = DB::table('products')->where('id','=',$rowSS->subjectID)->first();
        @endphp
                
            <tr class="details">
                <td class="tb-col">
                {{$studentDetail->full_name}} - {{$studentDetail->student_id}}
                </td>

                <td class="tb-col">
                {{$subjectDetail->name}}</td>
                <td class="tb-col">
                    {{ date("d-m-Y", strtotime($rowSS->date)) }}
                </td>
                <td class="tb-col">{{$rowSS->totalTime}}</td>
                <td class="tb-col">RM {{number_format($rowSS->commission,2)}}
                </td>
            </tr>
            @endforeach
        </table>

        <h2 style="text-align: left;">Additionals</h2>
        <table style="margin-top: 30px;">
            <tr class="heading">
                <td>
                    Description</td>
                <td>Amount</td>
                
            </tr>
           
        @foreach($additionals as $additional)
        <tr>
           <td style="text-align: center;">{{$additional->description}}</td>
            <td style="text-align: center;">{{$additional->amount}}</td>
        </tr>
        @php $additionaltotal+=$additional->amount; @endphp
        @endforeach
        </table>
        <h2 style="text-align: left;">Deductions</h2>
        <table style="margin: 30px 0;">
            <tr class="heading">
                <td>
                    Description</td>
                <td>Amount</td>
                
            </tr>
           
            @foreach($deductions as $deduction)
            <tr>
               <td style="text-align: center;">{{$deduction->description}}</td>
                <td style="text-align: center;">{{$deduction->amount}}</td>
            </tr>
            @php $deductionstotal+=$deduction->amount; @endphp
            @endforeach
            <tfoot>
                <tr>
                                       
                    <th scope="col" style="text-align: center;">Gross Total</th>
                    <th scope="col" style="text-align: center;">RM {{number_format($tutor_payment->payAmount,2)}}</th>
                </tr>
            </tfoot>
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