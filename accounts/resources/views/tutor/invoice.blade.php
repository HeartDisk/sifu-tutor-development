<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Tutor Invoice</title>

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
                                    <strong>SIFUTUTOR</strong><br />
                                    <span style="font-weight: 400;">Tutor ID </b> : {{$tutor->tutor_id}}</span><br>
                                    <span style="font-weight: 400;">Created Date </b>: {{$tutor->created_at}}</span>
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
                                    <p
                                        style="font-size: 22px;color: #000;font-weight: 500;margin-bottom: -10px; margin-top: 50px;">
                                        Tutor Details:</p>
                                    <p>Tutor Name: {{$tutor->full_name}}<br />
                                        Tutor Email:{{$tutor->email}}<br />
                                        Tutor Phone:{{$tutor->phoneNumber}}<br />
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
                <td>Date</td>
                <td style="width:80px">Student</td>
                <td style="width:80px">Class Description</td>
                <td style="width:80px">Mode</td>
                <td>Total Time(Hrs)</td>
                <td>Commission</td>
               
            </tr>
            @php
            $totaAttendedHours = 0;
          
            @endphp

            @foreach($tutorsClassData as $data)
            
            @php
             $studentName=DB::table("students")->where("id",$data->studentID)->first();
             
            @endphp
            
            

            <tr class="details">
                <td class="tb-col">{{Carbon\Carbon::create($data->created_at)->format("d-m-Y")}}</td>
                 <td class="tb-col">{{$studentName->full_name}}</td>
                <td class="tb-col">{{$data->product_name."-".$data->category_name}}</td>
                <td class="tb-col">{{$data->mode}}</td>
                <td class="tb-col">{{number_format($data->quantity,2)}}</td>
                <td class="tb-col">RM {{$data->commission}}</td>
                

            </tr>
         
            @endforeach

        </table>
        <table>
            
            <tr class="total">
               
                <td>
                    <div class="invoice-total-amount">
                        <p>One Time Bonus : RM {{isset($oneTimeBonus)?$oneTimeBonus:0}}</p>

                    </div>
                </td>

            </tr>
            
            
            <tr class="total">

                <td>
                    <div class="invoice-total-amount">
                        <p>Monthly bonus : RM {{isset($monthlyBonus)?$monthlyBonus:0}}</p>

                    </div>
                </td>
            </tr>
            
            
            <tr class="total">
               
                
                 <td>
                    <div class="invoice-total-amount">
                        <p>Total Commission : RM {{$totalAmount}}</p>

                    </div>
                </td>
            </tr>

        </table>
        
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