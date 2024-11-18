<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="{{url('public/logo', $general_setting->site_logo)}}" />
    <title>Roznamcha - {{$date}}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script type="text/javascript" src="http://mamoojitraders.com/sadaftraders/public/qrcode/jquery.qrcode.min.js"></script>

<script>
    //setTimeout(function(){ window.location.href = "{{url('sales/create')}}"; }, 3000);
</script>

    <style type="text/css">
            @font-face {
              font-family: 'Jameel Noori Nastaleeq';
              src: url('http://sadaftraders.com/st/public/fonts/jameel-noori-nastaleeq.ttf');
            }

        * {
            font-size: 13px;
            line-height: 20px;
            font-family: 'Jameel Noori Nastaleeq', serif;
        }
        .btn {
            padding: 7px 10px;
            text-decoration: none;
            border: none;
            display: block;
            text-align: center;
            margin: 7px;
            cursor:pointer;
        }

        .btn-info {
            background-color: #999;
            color: #FFF;
        }

        .btn-primary {
            background-color: #6449e7;
            color: #FFF;
            width: 100%;
        }
        tr {
            border-top:1px solid #000;
            height: 14px;
        }
        td {
            border-right:1px solid #000;
            height: 14px;
            border-bottom:1px solid #000;
        }
        table {
            border:1px solid #000;
            height: 14px;
        }
        table {width: 100%;}
        tfoot tr th:first-child {text-align: center;}

        .centered {
            text-align: center;
            align-content: center;
        }
        small{font-size:11px;}

        @media print {
            * {
                font-size:11px;
                line-height: 18px;
                font-weight: bold !important;
            }
            table{
                border:1px solid #000;
            }
            .addLab{
                background-color:#000 !important;
            }
            .hidden-print {
                display: none !important;
                font-size:11px;
            }
            
            @page { margin: 0; } body { margin: 0.3cm; margin-bottom:1.0cm; } 
        }
    </style>
  </head>
<body>

<div style="margin:0 auto">
    
    <div class="hidden-print">
        
    </div>
        
        <div id="receipt-data">
            <div class="centered">
                <h1>Print General Journal - {{$date}} - ROZNAMCHA روزنامچہ</h1>
                    <hr/>
           <style>
              .receive_vouchers tr td{
                  border:0px;
              } 
           </style>
            <table style="width:100%;" class="receive_vouchers">
                <tr>
                    <td style="text-align:left; border:1px solid #000"> Total Credits : <span style="font-size:21px; font-weight:bold;"> {{$cashInHand + $receive_vouchers + $payment_vouchers + $expense_vouchers + $bank_vouchers}} </span></td>
                    <td>  </td>
                    <td style="border:1px solid #000; font-size:12px; font-weight:bold; text-align:left;"> 
                        @php 
                            $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                                echo strtoupper($f->format($cashInHand + $receive_vouchers + $payment_vouchers + $expense_vouchers + $bank_vouchers));
                        @endphp
                        
                        </td>
                </tr>
                <tr>
                    <td style="text-align:left; border:1px solid #000"> Total Debits: <span style="font-size:21px; font-weight:bold;">{{$receive_vouchers_debit + $payment_vouchers_debit + $expense_vouchers_debit + $bank_vouchers_debit}}</span> </td>
                    <td>  </td>
                    <td style="border:1px solid #000; font-size:12px; font-weight:bold; text-align:left;"> 
                        @php 
                            $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                                echo strtoupper($f->format($receive_vouchers_debit + $payment_vouchers_debit + $expense_vouchers_debit + $bank_vouchers_debit));
                        @endphp
                        
                    </td>
                    
                </tr>
                <tr>
                    <td style="text-align:left; border:1px solid #000"> Cash in Hand : <span style="font-size:21px; font-weight:bold;">{{$cashInHand + $receive_vouchers - $receive_vouchers_debit + $payment_vouchers - $payment_vouchers_debit  - $expense_vouchers_debit + $expense_vouchers + $bank_vouchers - $bank_vouchers_debit}} </span>
                        @php
                            $total = $cashInHand + $receive_vouchers - $receive_vouchers_debit + $payment_vouchers - $payment_vouchers_debit  - $expense_vouchers_debit + $expense_vouchers + $bank_vouchers - $bank_vouchers_debit;
                        @endphp</td>
                    <td>  </td>
                    <td style="border:1px solid #000; font-size:12px; font-weight:bold; text-align:left;"> 
                            @php 
                            $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                                echo strtoupper($f->format($cashInHand + $receive_vouchers - $receive_vouchers_debit + $payment_vouchers - $payment_vouchers_debit  - $expense_vouchers_debit + $expense_vouchers + $bank_vouchers - $bank_vouchers_debit));
                        @endphp
                    
                    </td>
                </tr>
            </table>
                                @php 
                                $count = 1;
                                $number = 1;
                                $total_customer_debit = 0;
                                $total_customer_credit = 0;
                                $total_supplier_debit = 0;
                                $total_supplier_credit = 0;
                                $total_expense_credit = 0;
                                $total_expense_debit = 0;
                                $total_bank_debit = 0;
                                $total_bank_credit = 0;
                                $balance = $cashInHand; @endphp
            <div class="table-responsive">
                        <table style="color:#000; font-size:23px !important;" id="example" class="table table-striped table-bordered" style="width:100%">
                                 
                                <tr>
                                    <td colspan="8" style="background-color:#D3D3D3; text-align:left"><h2> Cash in Hand </h2></td>
                                </tr>
                                <tr>
                                    <th style="border:1px solid #000;">S.No</th>
                                    <th style="border:1px solid #000;">ID</th>
                                    <th style="border:1px solid #000;">Customer</th>
                                    <th style="border:1px solid #000;">City</th>
                                    <th style="border:1px solid #000;">Particulars</th>
                                    <th style="border:1px solid #000;">Debit</th>
                                    <th style="border:1px solid #000;">Credit</th>
                                    <th style="border:1px solid #000;">Balance</th>
                                </tr>
                                 <tr>
                                    <td>1</td>
                                    <td></td>
                                    <td> Cash In Hand  </td>
                                    <td></td></td>
                                    <td></td>
                                    <td></td>
                                    <td> {{$cashInHand}}</td>
                                    <td>{{$balance}}</td>
                                </tr>
                                
                                <tr style="border:2px solid #000">
                                        <th style="border:1px solid #000;"></th>
                                        <th style="border:1px solid #000;"></th>
                                        <th style="border:1px solid #000;"></th>
                                        <th style="border:1px solid #000;"></th>
                                        <th style="border:1px solid #000;"></th>
                                        <th style="font-size:17px; font-weight:bold; border:1px solid #000;">0</th>
                                        <th style="font-size:17px; font-weight:bold; border:1px solid #000;">{{$cashInHand}}</th>
                                        <th style="border:1px solid #000;"></th>
                                    </tr>
                                
                                <tr>
                                    <td colspan="8" style="background-color:#D3D3D3; text-align:left"><h2> Customers (Receivings) </h2></td>
                                </tr>
                                
                                <tbody>
                                
                                    @foreach($receive_vouchers_list as $row_rvl)
                                        <tr>
                                                <td tabindex="@php echo $count++; @endphp">
                                                    {{$number++}}
                                                </td>
                                                <td tabindex="@php echo $count++; @endphp">
                                                    {{$row_rvl->customer_id}}
                                                </td>
                                                <td tabindex="@php echo $count++; @endphp">
                                                    @php 
                                                        $customer_name = DB::table('customers')->where('id', $row_rvl->customer_id)->get(); 
                                                    @endphp
                                                    @if(!$customer_name->isEmpty())
                                                        @php
                                                        echo $customer_name[0]->name;
                                                        @endphp
                                                    @endif
                                                </td>
                                                <td tabindex="@php echo $count++; @endphp">
                                                    {{$customer_name[0]->city}}
                                                </td>
                                                <td tabindex="@php echo $count++; @endphp">{{$row_rvl->note}}</td>
                                                <td tabindex="@php echo $count++; @endphp">
                                                            @php
                                                            if($row_rvl->action == "debit"){
                                                                    echo $row_rvl->amount;
                                                                    $total_customer_debit += $row_rvl->amount;
                                                            }else{
                                                                echo 0;
                                                            }
                                                            @endphp
                                                            
                                                    </td>
                                                <td tabindex="@php echo $count++; @endphp">
                                                    @php
                                                    if($row_rvl->action == "credit"){
                                                                    echo $row_rvl->amount;
                                                                    $total_customer_credit += $row_rvl->amount;
                                                            }else{
                                                            echo 0;
                                                            }
                                                    @endphp
                                                    </td>
                                                <td tabindex="@php echo $count++; @endphp">
                                                        @php 
                                                            if($row_rvl->action == "credit"){
                                                                    $balance = $row_rvl->amount + $balance; 
                                                            }else{
                                                                    $balance = $balance - $row_rvl->amount; 
                                                            }
                                                        @endphp
                                                    {{$balance}}
                                                </td>
                                        </tr>
                                    @endforeach
                                    
                                    <tr style="border:2px solid #000">
                                        <th style="border:1px solid #000;"></th>
                                        <th style="border:1px solid #000;"></th>
                                        <th style="border:1px solid #000;"></th>
                                        <th style="border:1px solid #000;"></th>
                                        <th style="border:1px solid #000;"></th>
                                        <th style="font-size:17px; font-weight:bold; border:1px solid #000;">{{$total_customer_debit}}</th>
                                        <th style="font-size:17px; font-weight:bold; border:1px solid #000;">{{$total_customer_credit}}</th>
                                        <th style="border:1px solid #000;"></th>
                                    </tr>
                                    
                                    <tr>
                                        <td colspan="8" style="background-color:#D3D3D3; text-align:left"> <h2> Suppliers (Payments) </h2> </td>
                                    </tr>
                                    <tr>
                                        <th style="border:1px solid #000;">S.No</th>
                                        <th style="border:1px solid #000;">ID</th>
                                        <th style="border:1px solid #000;">Customer</th>
                                        <th style="border:1px solid #000;">City</th>
                                        <th style="border:1px solid #000;">Particulars</th>
                                        <th style="border:1px solid #000;">Debit</th>
                                        <th style="border:1px solid #000;">Credit</th>
                                        <th style="border:1px solid #000;">Balance</th>
                                    </tr>
                                     @foreach($payment_vouchers_list as $row_rvl)
                                        <tr>
                                                <td tabindex="@php echo $count++; @endphp">
                                                    {{$number++}}
                                                </td>
                                                <td tabindex="@php echo $count++; @endphp">
                                                    {{$row_rvl->supplier_id}}
                                                </td>
                                                
                                               
                                                <td tabindex="@php echo $count++; @endphp">
                                                    @php 
                                                        $supplier_name = DB::table('suppliers')->where('id', $row_rvl->supplier_id)->get(); 
                                                        
                                                    @endphp
                                                    @if(!$supplier_name->isEmpty())
                                                        @php
                                                            echo $supplier_name[0]->name;
                                                        @endphp
                                                    @endif
                                                </td>
                                                <td tabindex="@php echo $count++; @endphp">
                                                    {{$supplier_name[0]->city}}
                                                </td>

                                                <td tabindex="@php echo $count++; @endphp">{{$row_rvl->note}}</td>
                                                <td tabindex="@php echo $count++; @endphp">
                                                            @php
                                                            if($row_rvl->action == "debit"){
                                                                    echo $row_rvl->amount;
                                                                    $total_supplier_debit += $row_rvl->amount;
                                                            }else{
                                                                echo 0;
                                                            }
                                                            @endphp
                                                            
                                                    </td>
                                                <td tabindex="@php echo $count++; @endphp">
                                                    @php
                                                    if($row_rvl->action == "credit"){
                                                                    echo $row_rvl->amount;
                                                                    $total_supplier_credit += $row_rvl->amount;
                                                            }else{
                                                            echo 0;
                                                            }
                                                    @endphp
                                                    </td>
                                                <td tabindex="@php echo $count++; @endphp">
                                                        @php 
                                                            if($row_rvl->action == "credit"){
                                                                    $balance = $row_rvl->amount + $balance; 
                                                            }else{
                                                                    $balance = $balance - $row_rvl->amount; 
                                                            }
                                                        @endphp
                                                    {{$balance}}
                                                </td>

                                        </tr>
                                    @endforeach
                                    
                                     <tr style="border:2px solid #000">
                                        <th style="border:1px solid #000;"></th>
                                        <th style="border:1px solid #000;"></th>
                                        <th style="border:1px solid #000;"></th>
                                        <th style="border:1px solid #000;"></th>
                                        <th style="border:1px solid #000;"></th>
                                        <th style="font-size:17px; font-weight:bold; border:1px solid #000;">{{$total_supplier_debit}}</th>
                                        <th style="font-size:17px; font-weight:bold; border:1px solid #000;">{{$total_supplier_credit}}</th>
                                        <th style="border:1px solid #000;"></th>
                                    </tr>
                                    <tr>
                                        <td colspan="8" style="background-color:#D3D3D3; text-align:left"> <h2> Expenses </h2> </td>
                                    </tr>
                                    <tr>
                                        <th style="border:1px solid #000;">S.No</th>
                                        <th style="border:1px solid #000;">ID</th>
                                        <th style="border:1px solid #000;">Customer</th>
                                        <th style="border:1px solid #000;">City</th>
                                        <th style="border:1px solid #000;">Particulars</th>
                                        <th style="border:1px solid #000;">Debit</th>
                                        <th style="border:1px solid #000;">Credit</th>
                                        <th style="border:1px solid #000;">Balance</th>
                                    </tr>
                                    
                                    @foreach($expense_vouchers_list as $row_rvl)
                                        <tr>
                                            
                                                
                                                <td tabindex="@php echo $count++; @endphp">
                                                    {{$number++}}
                                                </td>
                                                <td tabindex="@php echo $count++; @endphp">
                                                    {{$row_rvl->expense_id}}
                                                </td>
                                                
                                                <td tabindex="@php echo $count++; @endphp">
                                                    @php 
                                                        $expenses = DB::table('accounts')->where('id', $row_rvl->expense_id)->where('type', 'Expense')->get(); 
                                                        echo $expenses[0]->name;
                                                        
                                                    @endphp
                                                </td>
                                                <td tabindex="@php echo $count++; @endphp">
                                                    &nbsp;
                                                </td>
                                                <td tabindex="@php echo $count++; @endphp">{{$row_rvl->note}}</td>
                                                <td tabindex="@php echo $count++; @endphp">
                                                            @php
                                                            if($row_rvl->action == "debit"){
                                                                    echo $row_rvl->amount;
                                                                    $total_expense_debit += $row_rvl->amount;
                                                            }else{
                                                                echo 0;
                                                            }
                                                            @endphp
                                                            
                                                    </td>
                                                <td tabindex="@php echo $count++; @endphp">
                                                    @php
                                                    if($row_rvl->action == "credit"){
                                                                    echo $row_rvl->amount;
                                                                    $total_expense_credit += $row_rvl->amount;
                                                            }else{
                                                            echo 0;
                                                            }
                                                    @endphp
                                                    </td>
                                                <td tabindex="@php echo $count++; @endphp">
                                                    @php 
                                                            if($row_rvl->action == "credit"){
                                                                    $balance = $row_rvl->amount + $balance; 
                                                            }else{
                                                                    $balance = $balance - $row_rvl->amount; 
                                                            }
                                                        @endphp
                                                    {{$balance}}
                                                </td>
                                        </tr>
                                    @endforeach
                                     <tr style="border:2px solid #000">
                                        <th style="border:1px solid #000;"></th>
                                        <th style="border:1px solid #000;"></th>
                                        <th style="border:1px solid #000;"></th>
                                        <th style="border:1px solid #000;"></th>
                                        <th style="border:1px solid #000;"></th>
                                        <th style="font-size:17px; font-weight:bold; border:1px solid #000;">{{$total_expense_debit}}</th>
                                        <th style="font-size:17px; font-weight:bold; border:1px solid #000;">{{$total_expense_credit}}</th>
                                        <th style="border:1px solid #000;"></th>
                                    </tr>
                                    
                                    <tr>
                                        <td colspan="8" style="background-color:#D3D3D3; text-align:left"> <h2> Bank </h2> </td>
                                    </tr>
                                    <tr>
                                        <th style="border:1px solid #000;">S.No</th>
                                        <th style="border:1px solid #000;">ID</th>
                                        <th style="border:1px solid #000;">Customer</th>
                                        <th style="border:1px solid #000;">City</th>
                                        <th style="border:1px solid #000;">Particulars</th>
                                        <th style="border:1px solid #000;">Debit</th>
                                        <th style="border:1px solid #000;">Credit</th>
                                        <th style="border:1px solid #000;">Balance</th>
                                    </tr>
                                    
                                    @foreach($bank_vouchers_list as $row_rvl)
                                        <tr>
                                                <td tabindex="@php echo $count++; @endphp">
                                                    {{$number++}}
                                                </td>
                                                <td tabindex="@php echo $count++; @endphp">
                                                    {{$row_rvl->bank_id}}
                                                </td>
                                                <td tabindex="@php echo $count++; @endphp">
                                                    @php 
                                                        $Banks = DB::table('accounts')->where('id', $row_rvl->bank_id)->where('type', 'Bank')->get(); 
                                                        echo $Banks[0]->name;
                                                        
                                                    @endphp
                                                </td>
                                                <td tabindex="@php echo $count++; @endphp">
                                                    &nbsp;
                                                </td>
                                                <td tabindex="@php echo $count++; @endphp">{{$row_rvl->note}}</td>
                                                <td tabindex="@php echo $count++; @endphp">
                                                            @php
                                                            if($row_rvl->action == "debit"){
                                                                    echo $row_rvl->amount;
                                                                    $total_bank_debit += $row_rvl->amount;
                                                            }else{
                                                                echo 0;
                                                            }
                                                            @endphp
                                                            
                                                    </td>
                                                <td tabindex="@php echo $count++; @endphp">
                                                    @php
                                                    if($row_rvl->action == "credit"){
                                                                    echo $row_rvl->amount;
                                                                    $total_bank_credit += $row_rvl->amount;
                                                            }else{
                                                            echo 0;
                                                            }
                                                    @endphp
                                                    </td>
                                                <td tabindex="@php echo $count++; @endphp">
                                                    @php 
                                                            if($row_rvl->action == "credit"){
                                                                    $balance = $row_rvl->amount + $balance; 
                                                            }else{
                                                                    $balance = $balance - $row_rvl->amount; 
                                                            }
                                                        @endphp
                                                    {{$balance}}
                                                </td>
                                        </tr>
                                    @endforeach
                                    <tr style="border:2px solid #000">
                                        <th style="border:1px solid #000;"></th>
                                        <th style="border:1px solid #000;"></th>
                                        <th style="border:1px solid #000;"></th>
                                        <th style="border:1px solid #000;"></th>
                                        <th style="border:1px solid #000;"></th>
                                        <th style="font-size:17px; font-weight:bold; border:1px solid #000;">{{$total_bank_debit}}</th>
                                        <th style="font-size:17px; font-weight:bold; border:1px solid #000;">{{$total_bank_credit}}</th>
                                        <th style="border:1px solid #000;"></th>
                                    </tr>
                                
                                    @php
                                    $count;
                                    @endphp
                            </tbody>
                        </table>
                        
                        
                </div>
               
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">


jQuery(function(){
	//jQuery('#output').qrcode({width: 94,height: 94,text: ""});
})

    function auto_print() {     
        window.print()
    }
    setTimeout(auto_print, 1000);
</script>

</body>
</html>