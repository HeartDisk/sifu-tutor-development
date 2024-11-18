@extends('layout.main') @section('content')
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{!! session()->get('message') !!}</div> 
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div> 
@endif
<h1 style="background:#02b4ac; padding:10px; text-align:center; color:#fff;"> G E N E R A L &nbsp; &nbsp;  J O U R N A L  </h1>

    <link href="{{asset('public/src/jquery.inputpicker.css')}}" rel="stylesheet" type="text/css">

    <!-- Bootstrap DatePicker -->
    
    <script src="jquery-1.3.2.min.js" type="text/javascript"></script>
    
    <script type="text/javascript">

        $(function () {
            $('#txtDate').datepicker({
                format: "dd-mm-yyyy"r
            });
        });

        var submitCounter = 0;
        function monitor() {
            submitCounter++;
            if (submitCounter < 2) {
                console.log('Submitted. Attempt: ' + submitCounter);
                return true;
            }
            console.log('Not Submitted. Attempt: ' + submitCounter);
            return false;
        }

    </script>
    
<style>

.heading4{font-size:18px;font-weight:400;font-family:'Lato', sans-serif;color:#111111;margin:0px 0px 5px 0px;}
.heading1{font-size:30px;line-height:20px;font-family:'Lato', sans-serif;text-transform:uppercase;color:#1b2834;font-weight:900;}
.content-quality{float:left;width:193px;}
.content-quality p{margin-left:10px;font-family:'Open Sans', sans-serif;font-size:14px;font-weight:600;line-height:17px;}
.content-quality p span{display:block;}
.tabtop li a{font-family:'Lato', sans-serif;font-weight:700;color:#1b2834;border-radius:0px;margin-right:22.008px;border:1px solid #ebebeb !important;}

.tabtop li a:hover{color:#e31837 !important;text-decoration:none;}

.margin-tops{margin-top:30px;}
.tabtop li a:last-child{padding:10px 22px;}
.thbada{padding:10px 28px !important;}
section p{font-family:'Lato', sans-serif;}
.margin-tops4{margin-top:20px;}
.tabsetting{border-top:5px solid #ebebeb;padding-top:6px;}
.services{background-color:#d4d4d4;min-height:710px;padding:65px 0 27px 0;}
.services a:hover{color:#000;}
.services h1{margin-top:0px !important;}
.heading-container p{font-family:'Lato', sans-serif;text-align:center;font-size:16px !important;text-transform:uppercase;}


.btn3d {
        position:relative;
        top: -6px;
        border:0;
         transition: all 40ms linear;
         margin-top:10px;
         margin-bottom:10px;
         margin-left:2px;
         margin-right:2px;
        }
        .btn3d:active:focus,
        .btn3d:focus:hover,
        .btn3d:focus {
            -moz-outline-style:none;
                 outline:medium none;
        }
        .btn3d:active, .btn3d.active {
            top:2px;
        }
        
        .btn3d.btn-default {
                color: #666666;
                box-shadow:0 0 0 1px #ebebeb inset, 0 0 0 2px rgba(255,255,255,0.10) inset, 0 8px 0 0 #BEBEBE, 0 8px 8px 1px rgba(0,0,0,.2);
                background-color:#f9f9f9;
            }
            .btn3d.btn-default:active, .btn3d.btn-default.active {
                color: #666666;
                box-shadow:0 0 0 1px #ebebeb inset, 0 0 0 1px rgba(255,255,255,0.15) inset, 0 1px 3px 1px rgba(0,0,0,.1);
                background-color:#f9f9f9;
            }
            .btn3d.btn-primary {
                box-shadow:0 0 0 1px #417fbd inset, 0 0 0 2px rgba(255,255,255,0.15) inset, 0 8px 0 0 #4D5BBE, 0 8px 8px 1px rgba(0,0,0,0.5);
                background-color:#4274D7;
            }
            .btn3d.btn-primary:active, .btn3d.btn-primary.active {
                box-shadow:0 0 0 1px #417fbd inset, 0 0 0 1px rgba(255,255,255,0.15) inset, 0 1px 3px 1px rgba(0,0,0,0.3);
                background-color:#4274D7;
            }
            .btn3d.btn-success {
                box-shadow:0 0 0 1px #31c300 inset, 0 0 0 2px rgba(255,255,255,0.15) inset, 0 8px 0 0 #5eb924, 0 8px 8px 1px rgba(0,0,0,0.5);
                background-color:#78d739;
            }
            .btn3d.btn-success:active, .btn3d.btn-success.active {
                box-shadow:0 0 0 1px #30cd00 inset, 0 0 0 1px rgba(255,255,255,0.15) inset, 0 1px 3px 1px rgba(0,0,0,0.3);
                background-color: #78d739;
            }
            .btn3d.btn-info {
                box-shadow:0 0 0 1px #00a5c3 inset, 0 0 0 2px rgba(255,255,255,0.15) inset, 0 8px 0 0 #348FD2, 0 8px 8px 1px rgba(0,0,0,0.5);
                background-color:#39B3D7;
            }
            .btn3d.btn-info:active, .btn3d.btn-info.active {
                box-shadow:0 0 0 1px #00a5c3 inset, 0 0 0 1px rgba(255,255,255,0.15) inset, 0 1px 3px 1px rgba(0,0,0,0.3);
                background-color: #39B3D7;
            }
            .btn3d.btn-warning {
                box-shadow:0 0 0 1px #d79a47 inset, 0 0 0 2px rgba(255,255,255,0.15) inset, 0 8px 0 0 #D79A34, 0 8px 8px 1px rgba(0,0,0,0.5);
                background-color:#FEAF20;
            }
            .btn3d.btn-warning:active, .btn3d.btn-warning.active {
                box-shadow:0 0 0 1px #d79a47 inset, 0 0 0 1px rgba(255,255,255,0.15) inset, 0 1px 3px 1px rgba(0,0,0,0.3);
                background-color: #FEAF20;
            }
            .btn3d.btn-danger {
                box-shadow:0 0 0 1px #b93802 inset, 0 0 0 2px rgba(255,255,255,0.15) inset, 0 8px 0 0 #AA0000, 0 8px 8px 1px rgba(0,0,0,0.5);
                background-color:#D73814;
            }
            .btn3d.btn-danger:active, .btn3d.btn-danger.active {
                box-shadow:0 0 0 1px #b93802 inset, 0 0 0 1px rgba(255,255,255,0.15) inset, 0 1px 3px 1px rgba(0,0,0,0.3);
                background-color: #D73814;
            }
            
            .btn3d.btn-white {
                    color: #666666;
                    box-shadow:0 0 0 1px #ebebeb inset, 0 0 0 2px rgba(255,255,255,0.10) inset, 0 8px 0 0 #f5f5f5, 0 8px 8px 1px rgba(0,0,0,.2);
                    background-color:#fff;
                }
                .btn3d.btn-white:active, .btn3d.btn-white.active {
                    color: #666666;
                    box-shadow:0 0 0 1px #ebebeb inset, 0 0 0 1px rgba(255,255,255,0.15) inset, 0 1px 3px 1px rgba(0,0,0,.1);
                    background-color:#fff;
                }

</style>

<section>

    <div class="container-fluid">
       @if (session('success'))
        <div class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{ session('success') }}
        </div>
    @endif
                    @php
                        $date_two = \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('d-m-Y ');
                    @endphp
            <table style="width:100%;">
                <tr>
                    <td>
                            <form method="POST" action="{{url('general_journal23')}}">
                                @csrf
                                <input name="sale_date" type="date" class="arrow-togglable date-input" value="@php echo date('d-m-Y');@endphp"/>
                                <input type="submit" value="Submit"/>
                            </form>
                    </td>
                    <td>
                        
                        <a href="{{url('print_general_journal23',$date)}}" class="btn btn-primary"> PDF General Journal  {{$date_two}}</a>
                    </td>
                </tr>
            </table>
            
            <!-- a href="{{route('sales.create')}}" style="border-radius:10px;" class="btn3d btn btn-info"><i class="fa fa-plus"></i> {{trans('file.Add Sale')}}</a -->
            <hr/>
           <style>
              .receive_vouchers tr td{
                  border:1px solid #000;
                  text-align:Center;
              } 
           </style>
            <table style="width:100%;" class="receive_vouchers">
                <tr>
                    <td colspan="2">
                       
                        Cash in Hand <br/> AT <strong> {{$date_two}}  </strong>
                    </td>
                    
                    <td colspan="2">
                        Receive Vouchers 
                    </td>
                    <td colspan="2">
                        Payment Vouchers 
                    </td>
                    <td colspan="2">
                        Expenses Vouchers 
                    </td>
                    <td colspan="2">
                        Bank Vouchers 
                    </td>
                    <td colspan="2">
                        Cash in Hand <br/> for Next Day
                    </td>
                </tr>
                <tr>
                    <td>
                        Debit
                    </td>
                    <td>
                        Credit
                    </td>
                    <td>
                        Debit <br/>
                        (MINUS) (PAYMENTS)<br/>
                    </td>
                    <td>
                        Credit (DEFAULT)
                         <br/>
                        (PLUS) (RECEIVINGS)<br/>
                    </td>
                    <td>
                        Debit (DEFAULT)<br/>
                        (MINUS) (Payments)
                    </td>
                    <td>
                        Credit <br/>
                        (PLUS) (Receivings)
                    </td>
                    <td>
                        Debit (DEFAULT) <br/>
                        (MINUS) (Payments)
                    </td>
                    <td>
                        Credit
                        (PLUS) (Receivings)
                    </td>
                    <td>
                        Debit <br/>
                        ( MINUS ) (Payments)
                    </td>
                    <td>
                        Credit (DEFAULT)<br/>
                        ( PLUS ) (Receivings)
                    </td>
                    <td>Debit</td>
                    <td>Credit</td>
                </tr>
                <tr>
                    <td>
                        0
                    </td>
                    <td>
                        {{$cashInHand}}
                    </td>
                    <td>
                        {{$receive_vouchers_debit}}
                    </td>
                    <td>
                        {{$receive_vouchers}}
                    </td>
                    <td>
                        {{$payment_vouchers_debit}}
                    </td>
                    <td>
                        {{$payment_vouchers}}
                    </td>
                    <td>
                        {{$expense_vouchers_debit}}
                    </td>
                    <td>
                        {{$expense_vouchers}}
                    </td>
                    <td>
                        {{$bank_vouchers_debit}}
                    </td>
                    <td>
                        {{$bank_vouchers}}
                    </td>
                    <td>
                        0
                    </td>
                    <td>
                        {{$cashInHand + $receive_vouchers - $receive_vouchers_debit + $payment_vouchers - $payment_vouchers_debit  - $expense_vouchers_debit + $expense_vouchers + $bank_vouchers - $bank_vouchers_debit}}
                        @php
                            $total = $cashInHand + $receive_vouchers - $receive_vouchers_debit + $payment_vouchers - $payment_vouchers_debit  - $expense_vouchers_debit + $expense_vouchers + $bank_vouchers - $bank_vouchers_debit;
                        @endphp
                    </td>
                </tr>
                
                
            </table>
            <!-- table>
                
                <tr>
                    <td>
                        Account Head
                    </td>
                    <td>
                        Action
                    </td>
                    <td>
                        Amount
                    </td>
                    
                </tr>
                <tr>
                    <td colspan="2">
                        Cash in hand (CREDIT) at {{$date}} 
            
                    </td>
                    <td>
                        
                            {{$cashInHand}}            
                        
            
                    </td>
                    
                 <tr>
                    <td>
                        Receive Vouchers Credit DEFAULT
                    </td>
                    <td>
                         (PLUS) (Receivings)
                    </td>
                    <td>
                        
                    </td>
                </tr>
                
                <tr>
                    <td>
                        Receive Vouchers Debit
                    </td>
                    <td>
                         (MINUS) (Payments)
                    </td>
                    <td>
                        {{$receive_vouchers_debit}}
                    </td>
                </tr>
                
                <tr>
                    <td>
                        Payment Vouchers Credit
                    </td>
                    <td>
                         (PLUS) (Receivings)
                    </td>
                    <td>
                        {{$payment_vouchers}}
                    </td>
                </tr>
                
                <tr>
                    <td>
                        Payment Vouchers Debit DEFAULT
                    </td>
                    <td>
                         (MINUS) (Payments)
                    </td>
                    <td>
                        {{$payment_vouchers_debit}}
                    </td>
                </tr>
                
                <tr>
                    <td>
                        Expense Vouchers Credit
                    </td>
                    <td>
                         (PLUS) (Receivings)
                    </td>
                    <td>
                         {{$expense_vouchers}}
                    </td>
                </tr>
                
                <tr>
                    <td>
                        Expense Vouchers Debit DEFAULT
                    </td>
                    <td>
                         (Minus) (Payments)
                    </td>
                    <td>
                        {{$expense_vouchers_debit}}
                    </td>
                </tr>
                
                <tr>
                    <td>
                        Bank Vouchers Credit  DEFAULT
                    </td>
                    <td>
                        ( PLUS ) (Receivings)
                    </td>
                    <td>
                        {{$bank_vouchers}}
                    </td>
                </tr>
                
                <tr>
                    <td>
                        Bank Vouchers Debit
                    </td>
                    <td>
                        ( MINUS ) (Payments)
                    </td>
                    <td>
                        {{$bank_vouchers_debit}}
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                              CASH IN HAND  (CREDIT) for NEXT DAY = 
                    </td>
                    <td>
                        {{$cashInHand + $receive_vouchers - $receive_vouchers_debit + $payment_vouchers - $payment_vouchers_debit  - $expense_vouchers_debit + $expense_vouchers + $bank_vouchers - $bank_vouchers_debit}}                        
                    </td>
                </tr>
            </table -->
            
            
            <a style="border-radius:10px;" class="btn3d btn btn-default">
                 <span style="border-bottom:1px solid #000;">Date: <span style="border-bottom:1px solid #000; font-size:21px; font-weight:bold;">
                    
                    {{$date_two}}
                </span></span></a>&nbsp;
                 
            <a style="border-radius:10px;" class="btn3d btn btn-default">
                 <span style="border-bottom:1px solid #000;">Total Receivings: <span style="border-bottom:1px solid #000; font-size:21px; font-weight:bold;"> {{$cashInHand + $receive_vouchers + $payment_vouchers + $expense_vouchers + $bank_vouchers}} </span></span></a>&nbsp;
            <a style="border-radius:10px;" class="btn3d btn btn-default"> 
            <span style="border-bottom:1px solid #000;">Total Payments: <span style="border-bottom:1px solid #000; font-size:21px; font-weight:bold;"> 
            
            {{$receive_vouchers_debit + $payment_vouchers_debit + $expense_vouchers_debit + $bank_vouchers_debit}}
            
            </span></span>
            
            </a>&nbsp;
            

            <a style="border-radius:10px;" class="btn3d btn btn-default"> 
            <span style="border-bottom:1px solid #000;">Cash in Hand: <span style="border-bottom:1px solid #000; font-size:21px; font-weight:bold;">
                
                 {{$cashInHand + $receive_vouchers - $receive_vouchers_debit + $payment_vouchers - $payment_vouchers_debit  - $expense_vouchers_debit + $expense_vouchers + $bank_vouchers - $bank_vouchers_debit}} </span></span>
                
            </a>&nbsp;
            
            <hr/>
            
            <a style="border-radius:10px;" class="btn3d btn btn-default"> 
            <span style="border-bottom:1px solid #000;">Receivings in ROZNAMCHA: <span style="border-bottom:1px solid #000; font-size:21px; font-weight:bold;">
                
                 {{$cashInHand + $receive_vouchers + $payment_vouchers +  $bank_vouchers}}
                 @php
                    $receivingInRoznamcha = $cashInHand + $receive_vouchers + $payment_vouchers +  $bank_vouchers;
                 @endphp
                 </span></span>
                
            </a>&nbsp;
            
            <a style="border-radius:10px;" class="btn3d btn btn-default"> 
            <span style="border-bottom:1px solid #000;">Payments in ROZNAMCHA: <span style="border-bottom:1px solid #000; font-size:21px; font-weight:bold;">
                
                 {{$receive_vouchers_debit + $bank_vouchers_debit +  $payment_vouchers_debit + $expense_vouchers_debit + $total}} </span></span>
                 @php
                    $paymentsInRoznamcha = $receive_vouchers_debit + $bank_vouchers_debit +  $payment_vouchers_debit + $expense_vouchers_debit + $total
                 @endphp
                
            </a>&nbsp;
            
            <a style="border-radius:10px;" class="btn3d btn btn-default"> 
            <span style="border-bottom:1px solid #000;">Cash Short in ROZNAMCHA: <span style="border-bottom:1px solid #000; font-size:21px; font-weight:bold;">
                
                 {{$receivingInRoznamcha - $paymentsInRoznamcha}} </span></span>
                
            </a>&nbsp;

        
    </div>
    
    
    
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->


<!--home-content-top starts from here-->

       <div style="padding:30px; " class="tabbable-line">
        <ul class="nav nav-tabs tabtop  tabsetting">
          <li class="active"> <a href="#tab_default_1" data-toggle="tab"> Customer Vouchers </a> </li>
          <li> <a href="#tab_default_2" data-toggle="tab"> Supplier Vouchers</a> </li>
          <li> <a href="#tab_default_3" data-toggle="tab"> Expense Vouchers </a> </li>
          <li> <a href="#tab_default_4" data-toggle="tab"> Bank Vouchers</a> </li>
        </ul>
        <div class="tab-content margin-tops">
          <div class="tab-pane active fade in" id="tab_default_1">
            <div class="col-md-12">
                <h2>Customer Vouchers</h2>
                <div class="table-responsive">
                        <table style="color:#000; font-size:23px !important;" id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th>S.No</th>
                                    <th>ACC # ID</th>
                                    <th>Customer</th>
                                    <th>City</th>
                                    <th>Amount</th>
                                    <th>Dr / Cr</th>
                                    <th>Balance</th>
                                    <th>Note</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                $count = 1;
                                $number = 1;
                                $balance = $cashInHand; @endphp
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
                                                <td tabindex="@php echo $count++; @endphp">{{$row_rvl->amount}}</td>
                                                <td tabindex="@php echo $count++; @endphp">{{$row_rvl->action}}</td>
                                                <td tabindex="@php echo $count++; @endphp">
                                                    @php $balance = $row_rvl->amount + $balance; @endphp
                                                    {{$balance}}
                                                </td>
                                                <td tabindex="@php echo $count++; @endphp">{{$row_rvl->note}}</td>
                                                <td> <a target="_blank" href="{{url('customer-receiving-voucher-edit',$row_rvl->id)}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> | <a onclick="return confirm('Are you sure? You want to Delete')" href="{{url('receive-voucher/'.$row_rvl->id.'/receive_voucher_delete')}}"><i class="fa fa-trash" aria-hidden="true"></i> </a> | <a onclick="someFunction({{$row_rvl->id}})"><i class="fa fa-check" aria-hidden="true"></i></a> </td>
                                        </tr>
                                    @endforeach
                                    @php
                                    $count;
                                    @endphp
                            </tfoot>
                        </table>
                </div>
    
                
                
             </div>
          </div>
          <div class="tab-pane fade" class="active show" id="tab_default_2">
            
            <div class="col-md-12">
              <h2>Supplier Vouchers</h2>
              <span style="font-weight:bold; font-size:18px;float:left"><input type="checkbox" id="checkAll"> &nbsp; Select All</span>
              &nbsp; &nbsp; &nbsp; 
              <button style="padding:5px; margin:5px;" onclick="delete_all_selected()" class="btn btn-danger">Delete All Selected</button>
              
               <div class="table-responsive">
                        <table style="color:#000; font-size:23px !important;" id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th></th>
                                    <th>Date</th>
                                    <th>Supplier Name</th>
                                    <th>Amount</th>
                                    <th>Dr / Cr</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                    @php $count = 1; @endphp
                                    @foreach($payment_vouchers_list as $row_rvl)
                                        <tr>
                                                <td><input name='id[]' type="checkbox" id="checkItem" value="{{$row_rvl->id}}"</td>
                                                <td tabindex="@php echo $count++; @endphp">
                                                    @php
                                                        $newDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $row_rvl->receive_voucher_date)->format('d-m-Y ');
                                                    @endphp
                                                    {{$newDate}}
                                                </td>
                                                <td tabindex="@php echo $count++; @endphp">
                                                    @php 
                                                        $supplier_name = DB::table('suppliers')->where('id', $row_rvl->supplier_id)->get(); 
                                                        
                                                    @endphp
                                                    @if(!$supplier_name->isEmpty())
                                                        @php
                                                            echo $supplier_name[0]->name.' ('.$supplier_name[0]->city.') ';
                                                        @endphp
                                                    @endif
                                                </td>

                                                <td tabindex="@php echo $count++; @endphp">{{$row_rvl->amount}}</td>
                                                <td tabindex="@php echo $count++; @endphp">>{{$row_rvl->action}}</td>
                                                <td> <a href="" data-toggle="modal" data-target="#payment_voucherModal{{$row_rvl->id}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> | <a onclick="return confirm('Are you sure? You want to Delete')"  href="{{url('receive-voucher/'.$row_rvl->id.'/payment_voucher_delete')}}"><i class="fa fa-trash" aria-hidden="true"></i> </a></td>
                                          
                                        </tr>
                                        
                                        <!-- Modal -->
                                                <div id="payment_voucherModal{{$row_rvl->id}}" class="modal fade" role="dialog">
                                                  <div class="modal-dialog">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                      <div class="modal-header">
                                                        <h4 class="modal-title">Payment Voucher</h4>
                                                      </div>
                                                      <div class="modal-body">
                                                        <form action="{{url('receive-voucher/666/general_journal_edit_payment_voucher')}}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{$row_rvl->id}}"/>
                                                            <div class="form-group">
                                                              <label for="exampleInputPassword1">Date: {{$newDate}}</label>
                                                            <input type="date" class="form-control" value="{{$newDate}}" placeholder="{{$newDate}}" name="date">
                                                          </div>
                                                          <div class="form-group">
                                                            <label for="exampleInputEmail1">Party Name : 
                                                            @if(!$supplier_name->isEmpty())
                                                                @php echo $supplier_name[0]->name.' ('.$supplier_name[0]->city.') '; @endphp</label>
                                                            @endif
                                                            @php
                                                                $products = DB::table('suppliers')->get();
                                                            @endphp
                                                                    <select class="form-control" data-live-search="true" data-live-search-style="begins" title="Select customer..." name="customer_id">
                                                                        @if(!$supplier_name->isEmpty())
                                                                            <option value="{{$supplier_name[0]->id}}" selected="selected" >{{$supplier_name[0]->name}} &nbsp; ({{$supplier_name[0]->city}})</option>
                                                                        @endif
                                                                        
                                                                    </select>
                                                                    
                                                          </div>
                                                          <div class="form-group">
                                                            <label for="exampleInputPassword1">Amount</label>
                                                            <input type="text" class="form-control" name="amount" value="{{$row_rvl->amount}}">
                                                          </div>
                                                          <div class="form-group">
                                                            <label for="exampleInputPassword1">Note</label>
                                                            <input type="text" class="form-control" name="note" value="{{$row_rvl->note}}" >
                                                          </div>
                                                          <div class="form-group">
                                                            <label for="exampleInputPassword1">Debit / Credit : {{$row_rvl->action}}</label>
                                                                <select class="form-control" name="action">
                                                                    @if($row_rvl->action == "debit")
                                                                        <option value="debit"> Debit </option>
                                                                    @else
                                                                        <option value="credit"> Credit </option>
                                                                    @endif
                                                                    <option value="debit"> Debit </option>
                                                                    <option value="credit"> Credit </option>
                                                                </select>
                                                          </div>
                                                          
                                                          <button type="submit" class="btn btn-primary">Submit</button>
                                                        </form>
                                                      </div>
                                                      <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                      </div>
                                                    </div>
                                                
                                                  </div>
                                                </div>
                                            <!-- Modal -->
                                
                                
                                    @endforeach
                            </tfoot>
                        </table>
                </div>
              
              
              
             </div>
          </div>
          <div class="tab-pane fade" id="tab_default_3">
            
            <div class="col-md-12">
            <h2>Expense Vouchers</h2>
            <div class="table-responsive">
                        <table style="color:#000; font-size:23px !important;" id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th>Date</th>
                                    <th>Expense Account</th>
                                    <th>Amount</th>
                                    <th>Dr / Cr</th>
                                    <th>Note</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                    @php $count = 1; @endphp
                                    @foreach($expense_vouchers_list as $row_rvl)
                                        <tr>
                                            
                                                <td tabindex="@php echo $count++; @endphp">
                                                    @php
                                                        $expense_voucher_date = \Carbon\Carbon::createFromFormat('Y-m-d', $row_rvl->receive_voucher_date)->format('d-m-Y ');
                                                    @endphp
                                                
                                                    {{$expense_voucher_date}}
                                                    
                                                </td>
                                                <td tabindex="@php echo $count++; @endphp">
                                                    @php 
                                                        $expenses = DB::table('accounts')->where('id', $row_rvl->expense_id)->where('type', 'Expense')->get(); 
                                                        echo $expenses[0]->name;
                                                        
                                                    @endphp
                                                </td>
                                                <td tabindex="@php echo $count++; @endphp">{{$row_rvl->amount}}</td>
                                                <td tabindex="@php echo $count++; @endphp">{{$row_rvl->note}}</td>
                                                <td tabindex="@php echo $count++; @endphp">{{$row_rvl->action}}</td>
                                                <td> <a href="" data-toggle="modal" data-target="#expense_voucherModal{{$row_rvl->id}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> | <a onclick="return confirm('Are you sure? You want to Delete')"  href="{{url('receive-voucher/'.$row_rvl->id.'/expense_voucher_delete')}}"><i class="fa fa-trash" aria-hidden="true"></i> </a></td>
                                        </tr>
                                              <!-- Modal -->
                                                <div id="expense_voucherModal{{$row_rvl->id}}" class="modal fade" role="dialog">
                                                  <div class="modal-dialog">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                      <div class="modal-header">
                                                        <h4 class="modal-title">Expense Voucher</h4>
                                                      </div>
                                                      <div class="modal-body">
                                                        <form action="{{url('receive-voucher/666/general_journal_edit_expense_voucher')}}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{$row_rvl->id}}"/>
                                                            <div class="form-group">
                                                              <label for="exampleInputPassword1">Date: {{$newDate}}</label>
                                                            <input type="date" class="form-control" value="{{$newDate}}" placeholder="{{$newDate}}" name="date" required>
                                                          </div>
                                                          <div class="form-group">
                                                            <label for="exampleInputEmail1">Account Head : @php echo $expenses[0]->name; @endphp</label>
                                                                    <select class="form-control" data-live-search="true" data-live-search-style="begins" title="Select customer..." name="customer_id">
                                                                         <option value="{{$row_rvl->expense_id}}" selected="selected" >{{$expenses[0]->name}}</option>
                                                                            @php 
                                                                                $expenses_row = DB::table('accounts')->where('type', 'Expense')->get(); 
                                                                                echo $expenses[0]->name;
                                                                            @endphp
                                                                        @foreach($expenses_row as $expense)
                                                                            <option value="{{$expense->id}}">{{$expense->name}})</option>
                                                                        @endforeach
                                                                    </select>
                                                          </div>
                                                          <div class="form-group">
                                                            <label for="exampleInputPassword1">Amount</label>
                                                            <input type="text" class="form-control" name="amount" value="{{$row_rvl->amount}}">
                                                          </div>
                                                          <div class="form-group">
                                                            <label for="exampleInputPassword1">Note</label>
                                                            <input type="text" class="form-control" name="note" value="{{$row_rvl->note}}" >
                                                          </div>
                                                          <div class="form-group">
                                                            <label for="exampleInputPassword1">Debit / Credit : {{$row_rvl->action}}</label>
                                                                <select class="form-control" name="action">
                                                                    @if($row_rvl->action == "debit")
                                                                        <option value="debit"> Debit </option>
                                                                    @else
                                                                        <option value="credit"> Credit </option>
                                                                    @endif
                                                                    <option value="debit"> Debit </option>
                                                                    <option value="credit"> Credit </option>
                                                                </select>
                                                          </div>
                                                          
                                                          <button type="submit" class="btn btn-primary">Submit</button>
                                                        </form>
                                                      </div>
                                                      <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                      </div>
                                                    </div>
                                                
                                                  </div>
                                                </div>
                                            <!-- Modal -->                                   
                                    @endforeach
                            </tfoot>
                        </table>
                </div>
            </div>
          </div>
          <div class="tab-pane fade" id="tab_default_4">
            
            <div class="col-md-12">
              <h2>Bank Vouchers</h2>
              
               <div class="table-responsive">
                        <table style="color:#000; font-size:23px !important;" id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th>Date</th>
                                    <th>Bank Account</th>
                                    <th>Amount</th>
                                    <th>Dr / Cr</th>
                                    <th>Note</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                    @php $count = 1; @endphp
                                    @foreach($bank_vouchers_list as $row_rvl)
                                        <tr>
                                            <td tabindex="@php echo $count++; @endphp">
                                                @php
                                                $bank_receive_voucher_date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $row_rvl->receive_voucher_date)->format('d-m-Y ');
                                                @endphp
                                                
                                                {{$bank_receive_voucher_date}}
                                                </td>
                                                <td tabindex="@php echo $count++; @endphp">
                                                    @php 
                                                        $Banks = DB::table('accounts')->where('id', $row_rvl->bank_id)->where('type', 'Bank')->get(); 
                                                        echo $Banks[0]->name;
                                                        
                                                    @endphp
                                                </td>
                                                <td tabindex="@php echo $count++; @endphp">{{$row_rvl->amount}}</td>
                                                <td tabindex="@php echo $count++; @endphp">{{$row_rvl->action}}</td>
                                                <td tabindex="@php echo $count++; @endphp">{{$row_rvl->note}}</td>
                                                <td> <a href="" data-toggle="modal" data-target="#bank_voucherModal{{$row_rvl->id}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> | <a onclick="return confirm('Are you sure? You want to Delete')"  href="{{url('receive-voucher/'.$row_rvl->id.'/bank_voucher_delete')}}"><i class="fa fa-trash" aria-hidden="true"></i> </a></td>
                                        </tr>
                                                <!-- Modal -->
                                                <div id="bank_voucherModal{{$row_rvl->id}}" class="modal fade" role="dialog">
                                                  <div class="modal-dialog">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                      <div class="modal-header">
                                                        <h4 class="modal-title">Bank Voucher</h4>
                                                      </div>
                                                      <div class="modal-body">
                                                        <form action="{{url('receive-voucher/666/general_journal_edit_bank_voucher')}}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{$row_rvl->id}}"/>
                                                            <div class="form-group">
                                                              <label for="exampleInputPassword1">Date: {{$newDate}}</label>
                                                            <input type="date" class="form-control" value="{{$newDate}}" placeholder="{{$newDate}}" name="date" required>
                                                          </div>
                                                          <div class="form-group">
                                                            <label for="exampleInputEmail1">Account Head : @php echo $Banks[0]->name; @endphp</label>
                                                                    <select class="form-control" data-live-search="true" data-live-search-style="begins" title="Select customer..." name="customer_id">
                                                                         <option value="{{$row_rvl->bank_id}}" selected="selected" >{{$Banks[0]->name}}</option>
                                                                            @php 
                                                                                $expenses_row = DB::table('accounts')->where('type', 'Bank')->get(); 
                                                                            @endphp
                                                                            @if(!$expenses_row->isEmpty())
                                                                                    echo $expenses[0]->name;
                                                                                @endif
                                                                        @foreach($expenses_row as $bank)
                                                                            <option value="{{$bank->id}}">{{$bank->name}})</option>
                                                                        @endforeach
                                                                    </select>
                                                          </div>
                                                          <div class="form-group">
                                                            <label for="exampleInputPassword1">Amount</label>
                                                            <input type="text" class="form-control" name="amount" value="{{$row_rvl->amount}}">
                                                          </div>
                                                          <div class="form-group">
                                                            <label for="exampleInputPassword1">Note</label>
                                                            <input type="text" class="form-control" name="note" value="{{$row_rvl->note}}" >
                                                          </div>
                                                          <div class="form-group">
                                                            <label for="exampleInputPassword1">Debit / Credit : {{$row_rvl->action}}</label>
                                                                <select class="form-control" name="action">
                                                                    @if($row_rvl->action == "debit")
                                                                        <option value="debit"> Debit </option>
                                                                    @else
                                                                        <option value="credit"> Credit </option>
                                                                    @endif
                                                                    <option value="debit"> Debit </option>
                                                                    <option value="credit"> Credit </option>
                                                                </select>
                                                          </div>
                                                          
                                                          <button type="submit" class="btn btn-primary">Submit</button>
                                                        </form>
                                                      </div>
                                                      <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                      </div>
                                                    </div>
                                                
                                                  </div>
                                                </div>
                                            <!-- Modal -->    
                                            
                                    @endforeach
                            </tfoot>
                        </table>
                </div>
                
             </div>
          </div>
          
        </div>
      </div>
    
<!--home-content-top ends here--> 



   
</section>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		
		<script language="javascript">
			$("#checkAll").click(function () {
				//$('input:checkbox').not(this).prop('checked', this.checked);
				$('input:checkbox').not(this).prop('checked', this.checked);
				
			});
		</script>
		
		

<script type="text/javascript">

$('#gatepass-details').on('show.bs.modal', function (event) {
  var myVal = $(event.relatedTarget).data('val');
  var total_qty = $(event.relatedTarget).data('total_qty');
  var total_price = $(event.relatedTarget).data('total_price');
  var total_t_weight = $(event.relatedTarget).data('total_t_weight');
  var add_lab = $(event.relatedTarget).data('add_lab');
  var less_lab = $(event.relatedTarget).data('less_lab');
  var grand_total = $(event.relatedTarget).data('grand_total');

  var invoice_date = $(event.relatedTarget).data('invoice_date');
  var bill_no = $(event.relatedTarget).data('bill_no');
  //console.log(bill_no);
  var gp_party = $(event.relatedTarget).data('gp_party');
  //console.log(gp_party);
  //console.log(gp_city);
  var gp_city = $(event.relatedTarget).data('gp_city');
  var reference = $(event.relatedTarget).data('reference');
  var customer_city = $(event.relatedTarget).data('customer_city');
  var customer_name = $(event.relatedTarget).data('customer_name');
  var customer_phone = $(event.relatedTarget).data('customer_phone');
  
  $('#party_name').val(gp_party);
  $('#reff').val(reference);
  $('#bill_noo').val(bill_no);
  $('#inv_datee').val(invoice_date);
  $('#party_city').val(gp_city);
  $('#customer_name').val(customer_name);
  $('#customer_city').val(customer_city);
  $('#customer_phone').val(customer_phone);
  
  
  $("#gatepass-details input[name='sale_id']").val(sale[14]);
        var id = $(this).attr('data-id');
        var htmltext = '<div class="row"><div class="col-md-6"><strong>Invoice Detail</strong> <br/><strong>{{trans("file.Date")}}: </strong>'+invoice_date+'<br><strong>{{trans("file.reference")}}: </strong>'+reference+'<br><strong>Bill Number: </strong>'+bill_no+'</div><div class="col-md-6"><strong>Customer Detail:</strong><br> <strong>Customer Name:</strong> '+customer_name+'<br>Contact Number: </strong> g> '+customer_phone+' <br> <strong> Address: </strong> Market اناج منڈی <strong> <br/> City: </strong> '+customer_city+' <br> </div></div>';
        $.get('sales/product_sale/' + myVal, function(data){
            $(".product-sale-list tbody").remove();
            var name_code = data[0];
            var urdu_name = data[7];
            var lot = data[8];
            var packing = data[9];
            var qty = data[1];
            var unit_code = data[2];
            var tax = data[3];
            var tax_rate = data[4];
            var discount = data[5];
            var subtotal = data[6];
            var t_weight = data[10];
            var price = data[11];
            var bag_rate = data[12];
            var perKgRate = data[13];
            var newBody = $("<tbody>");
            $.each(name_code, function(index){
                var newRow = $("<tr style='border_bottom:1px solid #000 !important;'>");
                var cols = '';
                cols += '<td style="color:#000 !important; text-align:center !important;">' + subtotal[index] + '</td>';
                cols += '<td style="color:#000 !important; text-align:center !important;">' + bag_rate[index] + '</td>';
                cols += '<td style="color:#000 !important; text-align:center !important;">' + perKgRate[index] + '</td>';
                cols += '<td style="color:#000 !important; text-align:center !important;">' + price[index] + '</td>';
                cols += '<td style="color:#000 !important; text-align:center !important;">' + t_weight[index] + '</td>';
                cols += '<td style="color:#000 !important; text-align:center !important;">' + packing[index] + '</td>';
                cols += '<td style="color:#000 !important; text-align:center !important;">' + lot[index] + '</td>';
                cols += '<td style="color:#000 !important; text-align:right !important;"><span style="font-size:23px;  color:#000 !important; font-family: Jameel Noori Nastaleeq">'+ urdu_name[index] +'</span></td>';
                cols += '<td style="color:#000 !important; font-weight:bold; color:#000 !important; text-align:center !important; font-size:18px !important;">' + qty[index] +'</td>';
                cols += '<td style="color:#000 !important; text-align:center;">' + (index+1) + '</td>';
                newRow.append(cols);
                newBody.append(newRow);
            });
  
            var newRow = $("<tr style='background-color:#1cd922'>");
            cols = '';
            cols += '<td style="font-weight:bold; font-weight:bold; color:#000; font-size:21px; text-align:center !important;">' + total_price + '</td>';
            cols += '<td></td>';
            cols += '<td></td>';
            cols += '<td></td>';
            cols += '<td style="text-align:center; font-size:18px;  color:#000;  font-weight:bold;">'+total_t_weight+'</td>';
            cols += '<td></td>';
            cols += '<td></td>';
            cols += '<td></td>';
            cols += '<td style="text-align:center; font-size:18px;  color:#000;  font-weight:bold;">'+total_qty+'</td>';
            cols += '<td> </td>';
            newRow.append(cols);
            newBody.append(newRow);

            //var newRow = $("<tr style='background-color:#1cd922'>");
            //cols = '';
            //cols += '<td></td>';
            //cols += '<td></td>';
            //cols += '<td></td>';
            //cols += '<td></td>';
            //cols += '<td></td>';
            //cols += '<td></td>';
            //cols += '<td></td>';
            //cols += '<td></td>';
            //cols += '<td></td>';
            //cols += '<td> </td>';
            //newRow.append(cols);
            //newBody.append(newRow);
            


            var newRow = $("<tr style='background-color:#fff'>");
            cols = '';

            cols += '<td  style="border:1px solid #000 !important; font-size:22px; text-align:center !important; color:#000 !important; font-weight:bold;" > '+add_lab+' </td>';
            cols += '<td style="border:1px solid #000 !important;    font-size:27px; color:#000 !important; font-family: Jameel Noori Nastaleeq; font-weight:bold;">مزدوری جمع</td>';
            cols += '<td style="border:none !important;" colspan="8"></td>';
            
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr style='background-color:#fff'>");
            cols = '';
            
            cols += '<td  style="border:1px solid #000 !important; font-size:22px; text-align:center !important; color:#000 !important; font-weight:bold;"  > '+less_lab+' </td>';
            cols += '<td style="border:1px solid #000 !important;  font-size:27px; color:#000 !important;  font-family: Jameel Noori Nastaleeq;  font-weight:bold;">بل تفریق</td>';
            cols += '<td style="border:none !important;" colspan="8"></td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr style='background-color:#fff'>");
            cols = '';
            
            cols += '<td style="border:1px solid #000 !important;  font-size:22px; text-align:center !important; color:#000 !important; font-weight:bold;" > '+grand_total+' </td>';
            cols += '<td  style="border:1px solid #000 !important;    font-size:27px; color:#000 !important;  font-family: Jameel Noori Nastaleeq;  font-weight:bold;">کل رقم</td>';
            cols += '<td style="border:none !important;" colspan="8"></td>';
            
            newRow.append(cols);
            newBody.append(newRow);
            

     

            /*
            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.Order Tax")}}:</strong></td>';
            cols += '<td>' + sale[17] + '(' + sale[18] + '%)' + '</td>';
            newRow.append(cols);
            newBody.append(newRow);
            

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.Order Discount")}}:</strong></td>';
            cols += '<td>' + sale[19] + '</td>';
            newRow.append(cols);
            newBody.append(newRow);
            if(sale[28]) {
                var newRow = $("<tr>");
                cols = '';
                cols += '<td colspan=6><strong>{{trans("file.Coupon Discount")}} ['+sale[28]+']:</strong></td>';
                cols += '<td>' + sale[29] + '</td>';
                newRow.append(cols);
                newBody.append(newRow);
            }

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.Shipping Cost")}}:</strong></td>';
            cols += '<td>' + sale[20] + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.grand total")}}:</strong></td>';
            cols += '<td>' + sale[21] + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.Paid Amount")}}:</strong></td>';
            cols += '<td>' + sale[22] + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.Due")}}:</strong></td>';
            cols += '<td>' + parseFloat(sale[21] - sale[22]).toFixed(2) + '</td>';
            newRow.append(cols);
            newBody.append(newRow);
        */
            $("table.product-sale-list").append(newBody);
        });
        //var htmlfooter = '<hr/><center><table style="width:40%">@php $builty = DB::table('deliveries')->where('sale_id', '=', 116)->get(); @endphp @foreach($builty as $row)<tr><td><img style="width:100%;" src="{{ URL::to('public/documents/delivery/' . $row->file) }}"/></td></tr>@endforeach</table></center><p><strong>{{trans("file.Sale Note")}}:</strong> '+sale[23]+'</p><p><strong>{{trans("file.Staff Note")}}:</strong> '+sale[24]+'</p><strong>{{trans("file.Created By")}}:</strong><br>'+sale[25]+'<br>'+sale[26];
        //$('#sale-footer').html(htmlfooter);
        $('#gatepass-content').html(htmltext);
        $('#sale_idd').val(myVal);
        $('#gatepass-details').modal('show');
  
});


$('#sale-details').on('show.bs.modal', function (event) {
  var myVal = $(event.relatedTarget).data('val');
  var total_qty = $(event.relatedTarget).data('total_qty');
  var total_price = $(event.relatedTarget).data('total_price');
  var total_t_weight = $(event.relatedTarget).data('total_t_weight');
  var add_lab = $(event.relatedTarget).data('add_lab');
  var less_lab = $(event.relatedTarget).data('less_lab');
  var grand_total = $(event.relatedTarget).data('grand_total');

  var invoice_date = $(event.relatedTarget).data('invoice_date');
  var bill_no = $(event.relatedTarget).data('bill_no');
  var reference = $(event.relatedTarget).data('reference');
  var customer_name = $(event.relatedTarget).data('customer_name');
  var customer_phone = $(event.relatedTarget).data('customer_phone');
  var customer_city = $(event.relatedTarget).data('customer_city');
  
  $("#sale-details input[name='sale_id']").val(sale[14]);
        var id = $(this).attr('data-id');
        var htmltext = '<div class="row"><div class="col-md-6"><strong>Invoice Detail</strong> <br/><strong>{{trans("file.Date")}}: </strong>'+invoice_date+'<br><strong>{{trans("file.reference")}}: </strong>'+reference+'<br><strong>Bill Number: </strong>'+bill_no+'</div><div class="col-md-6"><strong>Customer Detail:</strong><br> <strong>Customer Name:</strong> '+customer_name+'<br> <strong>Contact Number: </strong> '+customer_phone+' <br> <strong> City: </strong> '+customer_city+' <br> </div></div>';
        $.get('sales/product_sale/' + myVal, function(data){
            $(".product-sale-list tbody").remove();
            var name_code = data[0];
            var urdu_name = data[7];
            var lot = data[8];
            var packing = data[9];
            var qty = data[1];
            var unit_code = data[2];
            var tax = data[3];
            var tax_rate = data[4];
            var discount = data[5];
            var subtotal = data[6];
            var t_weight = data[10];
            var price = data[11];
            var bag_rate = data[12];
            var perKgRate = data[13];
            var newBody = $("<tbody>");
            $.each(name_code, function(index){
                var newRow = $("<tr style='border_bottom:1px solid #000 !important;'>");
                var cols = '';
                cols += '<td style="color:#000 !important; text-align:center !important;">' + subtotal[index] + '</td>';
                cols += '<td style="color:#000 !important; text-align:center !important;">' + bag_rate[index] + '</td>';
                cols += '<td style="color:#000 !important; text-align:center !important;">' + perKgRate[index] + '</td>';
                cols += '<td style="color:#000 !important; text-align:center !important;">' + price[index] + '</td>';
                cols += '<td style="color:#000 !important; text-align:center !important;">' + t_weight[index] + '</td>';
                cols += '<td style="color:#000 !important; text-align:center !important;">' + packing[index] + '</td>';
                cols += '<td style="color:#000 !important; text-align:center !important;">' + lot[index] + '</td>';
                cols += '<td style="color:#000 !important; text-align:right !important;"><span style="font-size:23px;  color:#000 !important; font-family: Jameel Noori Nastaleeq">'+ urdu_name[index] +'</span></td>';
                cols += '<td style="color:#000 !important; font-weight:bold; color:#000 !important; text-align:center !important; font-size:18px !important;">' + qty[index] +'</td>';
                cols += '<td style="color:#000 !important; text-align:center;">' + (index+1) + '</td>';
                newRow.append(cols);
                newBody.append(newRow);
            });
  
            var newRow = $("<tr style='background-color:#1cd922'>");
            cols = '';
            cols += '<td style="font-weight:bold; font-weight:bold; color:#000; font-size:21px; text-align:center !important;">' + total_price + '</td>';
            cols += '<td></td>';
            cols += '<td></td>';
            cols += '<td></td>';
            cols += '<td style="text-align:center; font-size:18px;  color:#000;  font-weight:bold;">'+total_t_weight+'</td>';
            cols += '<td></td>';
            cols += '<td></td>';
            cols += '<td></td>';
            cols += '<td style="text-align:center; font-size:18px;  color:#000;  font-weight:bold;">'+total_qty+'</td>';
            cols += '<td> </td>';
            newRow.append(cols);
            newBody.append(newRow);

            //var newRow = $("<tr style='background-color:#1cd922'>");
            //cols = '';
            //cols += '<td></td>';
            //cols += '<td></td>';
            //cols += '<td></td>';
            //cols += '<td></td>';
            //cols += '<td></td>';
            //cols += '<td></td>';
            //cols += '<td></td>';
            //cols += '<td></td>';
            //cols += '<td></td>';
            //cols += '<td> </td>';
            //newRow.append(cols);
            //newBody.append(newRow);
            


            var newRow = $("<tr style='background-color:#fff'>");
            cols = '';

            cols += '<td  style="border:1px solid #000 !important; font-size:22px; text-align:center !important; color:#000 !important; font-weight:bold;" > '+add_lab+' </td>';
            cols += '<td style="border:1px solid #000 !important;    font-size:27px; color:#000 !important; font-family: Jameel Noori Nastaleeq; font-weight:bold;">مزدوری جمع</td>';
            cols += '<td style="border:none !important;" colspan="8"></td>';
            
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr style='background-color:#fff'>");
            cols = '';
            
            cols += '<td  style="border:1px solid #000 !important; font-size:22px; text-align:center !important; color:#000 !important; font-weight:bold;"  > '+less_lab+' </td>';
            cols += '<td style="border:1px solid #000 !important;  font-size:27px; color:#000 !important;  font-family: Jameel Noori Nastaleeq;  font-weight:bold;">بل تفریق</td>';
            cols += '<td style="border:none !important;" colspan="8"></td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr style='background-color:#fff'>");
            cols = '';
            
            cols += '<td style="border:1px solid #000 !important;  font-size:22px; text-align:center !important; color:#000 !important; font-weight:bold;" > '+grand_total+' </td>';
            cols += '<td  style="border:1px solid #000 !important;    font-size:27px; color:#000 !important;  font-family: Jameel Noori Nastaleeq;  font-weight:bold;">کل رقم</td>';
            cols += '<td style="border:none !important;" colspan="8"></td>';
            
            newRow.append(cols);
            newBody.append(newRow);
            

     

            /*
            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.Order Tax")}}:</strong></td>';
            cols += '<td>' + sale[17] + '(' + sale[18] + '%)' + '</td>';
            newRow.append(cols);
            newBody.append(newRow);
            

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.Order Discount")}}:</strong></td>';
            cols += '<td>' + sale[19] + '</td>';
            newRow.append(cols);
            newBody.append(newRow);
            if(sale[28]) {
                var newRow = $("<tr>");
                cols = '';
                cols += '<td colspan=6><strong>{{trans("file.Coupon Discount")}} ['+sale[28]+']:</strong></td>';
                cols += '<td>' + sale[29] + '</td>';
                newRow.append(cols);
                newBody.append(newRow);
            }

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.Shipping Cost")}}:</strong></td>';
            cols += '<td>' + sale[20] + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.grand total")}}:</strong></td>';
            cols += '<td>' + sale[21] + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.Paid Amount")}}:</strong></td>';
            cols += '<td>' + sale[22] + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.Due")}}:</strong></td>';
            cols += '<td>' + parseFloat(sale[21] - sale[22]).toFixed(2) + '</td>';
            newRow.append(cols);
            newBody.append(newRow);
        */
            $("table.product-sale-list").append(newBody);
        });
        //var htmlfooter = '<hr/><center><table style="width:40%">@php $builty = DB::table('deliveries')->where('sale_id', '=', 116)->get(); @endphp @foreach($builty as $row)<tr><td><img style="width:100%;" src="{{ URL::to('public/documents/delivery/' . $row->file) }}"/></td></tr>@endforeach</table></center><p><strong>{{trans("file.Sale Note")}}:</strong> '+sale[23]+'</p><p><strong>{{trans("file.Staff Note")}}:</strong> '+sale[24]+'</p><strong>{{trans("file.Created By")}}:</strong><br>'+sale[25]+'<br>'+sale[26];
        //$('#sale-footer').html(htmlfooter);
        $('#sale-content').html(htmltext);
        $('#sale-details').modal('show');
  
});


       //Start Uploading Code

       

$(document).ready(function() {
    $('#example').DataTable();
} );

var currCell = $('td').first();
var editing = false;

// User clicks on a cell
$('td').click(function() {
    currCell = $(this);
    edit();
});

// Show edit box
function edit() {
    editing = true;
    currCell.toggleClass("editing");
    $('#edit').show();
    $('#edit #text').val(currCell.html());
    $('#edit #text').select();
}

// User saves edits
$('#edit form').submit(function(e) {
    editing = false;
    e.preventDefault();
    // Ajax to update value in database
    $.get('#', '', function() {
        $('#edit').hide();
        currCell.toggleClass("editing");
        currCell.html($('#edit #text').val());
        currCell.focus();
    });
});

// User navigates table using keyboard
$('table').keydown(function (e) {
    var c = "";
    if (e.which == 39) {
        // Right Arrow
        c = currCell.next();
    } else if (e.which == 37) { 
        // Left Arrow
        c = currCell.prev();
    } else if (e.which == 38) { 
        // Up Arrow
        c = currCell.closest('tr').prev().find('td:eq(' + 
          currCell.index() + ')');
    } else if (e.which == 40) { 
        // Down Arrow
        c = currCell.closest('tr').next().find('td:eq(' + 
          currCell.index() + ')');
    } else if (!editing && (e.which == 13 || e.which == 32)) { 
        // Enter or Spacebar - edit cell
        e.preventDefault();
        edit();
    } else if (!editing && (e.which == 9 && !e.shiftKey)) { 
        // Tab
        e.preventDefault();
        c = currCell.next();
    } else if (!editing && (e.which == 9 && e.shiftKey)) { 
        // Shift + Tab
        e.preventDefault();
        c = currCell.prev();
    } 
    
    // If we didn't hit a boundary, update the current cell
    if (c.length > 0) {
        currCell = c;
        currCell.focus();
    }


// User can cancel edit by pressing escape
$('#edit').keydown(function (e) {
    if (editing && e.which == 27) { 
        editing = false;
        $('#edit').hide();
        currCell.toggleClass("editing");
        currCell.focus();
    }
});


    $("ul#sale").siblings('a').attr('aria-expanded','true');
    $("ul#sale").addClass("show");
    $("ul#sale #sale-list-menu").addClass("active");
    
    
    var sale_id = [];
    var user_verified = <?php echo json_encode(env('USER_VERIFIED')) ?>;
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    
    var current_date = <?php echo json_encode(date("Y-m-d")) ?>;
    var payment_date = [];
    var payment_reference = [];
    var paid_amount = [];
    var paying_method = [];
    var payment_id = [];
    var payment_note = [];
    var account = [];
    var deposit;

    $(".gift-card").hide();
    $(".card-element").hide();
    $("#cheque").hide();
    $('#view-payment').modal('hide');

    $(document).on("click", "tr.sale-link td:not(:first-child, :last-child)", function() {
        var sale = $(this).parent().data('sale');
        console.log(sale);
        saleDetails(sale);
    });

    $(document).on("click", ".view", function(){
        var sale = $(this).parent().parent().parent().parent().parent().data('sale');
        saleDetails(sale);
    });

    $("#print-btn").on("click", function(){
          var divToPrint=document.getElementById('sale-details');
          var newWin=window.open('','Print-Window');
          newWin.document.open();
          newWin.document.write('<link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap/css/bootstrap.min.css') ?>" type="text/css"><style type="text/css">@media print {.modal-dialog { max-width: 1000px;} }</style><body onload="window.print()">'+divToPrint.innerHTML+'</body>');
          newWin.document.close();
          setTimeout(function(){newWin.close();},10);
    });

    $(document).on("click", "table.sale-list tbody .add-payment", function() {
        $("#cheque").hide();
        $(".gift-card").hide();
        $(".card-element").hide();
        $('select[name="paid_by_id"]').val(1);
        $('.selectpicker').selectpicker('refresh');
        rowindex = $(this).closest('tr').index();
        deposit = $('table.sale-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.deposit').val();
        var sale_id = $(this).data('id').toString();
        var balance = $('table.sale-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(10)').text();
        balance = parseFloat(balance.replace(/,/g, ''));
        $('input[name="paying_amount"]').val(balance);
        $('#add-payment input[name="balance"]').val(balance);
        $('input[name="amount"]').val(balance);
        $('input[name="sale_id"]').val(sale_id);
    });

    $(document).on("click", "table.sale-list tbody .get-payment", function(event) {
        rowindex = $(this).closest('tr').index();
        deposit = $('table.sale-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.deposit').val();
        var id = $(this).data('id').toString();
        $.get('sales/getpayment/' + id, function(data) {
            $(".payment-list tbody").remove();
            var newBody = $("<tbody>");
            payment_date  = data[0];
            payment_reference = data[1];
            paid_amount = data[2];
            paying_method = data[3];
            payment_id = data[4];
            payment_note = data[5];
            cheque_no = data[6];
            gift_card_id = data[7];
            change = data[8];
            paying_amount = data[9];
            account_name = data[10];
            account_id = data[11];

            $.each(payment_date, function(index){
                var newRow = $("<tr>");
                var cols = '';

                cols += '<td>' + payment_date[index] + '</td>';
                cols += '<td>' + payment_reference[index] + '</td>';
                cols += '<td>' + account_name[index] + '</td>';
                cols += '<td>' + paid_amount[index] + '</td>';
                cols += '<td>' + paying_method[index] + '</td>';
                if(paying_method[index] != 'Paypal')
                    cols += '<td><div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{trans("file.action")}}<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button><ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu"><li><button type="button" class="btn btn-link edit-btn" data-id="' + payment_id[index] +'" data-clicked=false data-toggle="modal" data-target="#edit-payment"><i class="fa fa-edit"></i> {{trans("file.edit")}}</button></li><li class="divider"></li>{{ Form::open(['route' => 'sale.delete-payment', 'method' => 'post'] ) }}<li><input type="hidden" name="id" value="' + payment_id[index] + '" /> <button type="submit" class="btn btn-link" onclick="return confirmPaymentDelete()"><i class="fa fa-trash"></i> {{trans("file.delete")}}</button></li>{{ Form::close() }}</ul></div></td>';
                else
                    cols += '<td><div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{trans("file.action")}}<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button><ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">{{ Form::open(['route' => 'sale.delete-payment', 'method' => 'post'] ) }}<li><input type="hidden" name="id" value="' + payment_id[index] + '" /> <button type="submit" class="btn btn-link" onclick="return confirmPaymentDelete()"><i class="fa fa-trash"></i> {{trans("file.delete")}}</button></li>{{ Form::close() }}</ul></div></td>';

                newRow.append(cols);
                newBody.append(newRow);
                $("table.payment-list").append(newBody);
            });
            $('#view-payment').modal('show');
        });
    });
    
    $("table.payment-list").on("click", ".edit-btn", function(event) {
        $(".edit-btn").attr('data-clicked', true);        
        $(".card-element").hide();
        $("#edit-cheque").hide();
        $('.gift-card').hide();
        $('#edit-payment select[name="edit_paid_by_id"]').prop('disabled', false);
        var id = $(this).data('id').toString();
        $.each(payment_id, function(index){
            if(payment_id[index] == parseFloat(id)){
                $('input[name="payment_id"]').val(payment_id[index]);
                $('#edit-payment select[name="account_id"]').val(account_id[index]);
                if(paying_method[index] == 'Cash')
                    $('select[name="edit_paid_by_id"]').val(1);
                else if(paying_method[index] == 'Gift Card'){
                    $('select[name="edit_paid_by_id"]').val(2);
                    $('#edit-payment select[name="gift_card_id"]').val(gift_card_id[index]);
                    $('.gift-card').show();
                    $('#edit-payment select[name="edit_paid_by_id"]').prop('disabled', true);
                }
                else if(paying_method[index] == 'Credit Card'){
                    $('select[name="edit_paid_by_id"]').val(3);
                    $.getScript( "public/vendor/stripe/checkout.js" );
                    $(".card-element").show();
                    $('#edit-payment select[name="edit_paid_by_id"]').prop('disabled', true);
                }
                else if(paying_method[index] == 'Cheque'){
                    $('select[name="edit_paid_by_id"]').val(4);
                    $("#edit-cheque").show();
                    $('input[name="edit_cheque_no"]').val(cheque_no[index]);
                    $('input[name="edit_cheque_no"]').attr('required', true);
                }
                else
                    $('select[name="edit_paid_by_id"]').val(6);

                $('.selectpicker').selectpicker('refresh');
                $("#payment_reference").html(payment_reference[index]);
                $('input[name="edit_paying_amount"]').val(paying_amount[index]);
                $('#edit-payment .change').text(change[index]);
                $('input[name="edit_amount"]').val(paid_amount[index]);
                $('textarea[name="edit_payment_note"]').val(payment_note[index]);
                return false;
            }
        });
        $('#view-payment').modal('hide');
    });

    $('select[name="paid_by_id"]').on("change", function() {       
        var id = $(this).val();
        $('input[name="cheque_no"]').attr('required', false);
        $('#add-payment select[name="gift_card_id"]').attr('required', false);
        $(".payment-form").off("submit");
        if(id == 2){
            $(".gift-card").show();
            $(".card-element").hide();
            $("#cheque").hide();
            $('#add-payment select[name="gift_card_id"]').attr('required', true);
        }
        else if (id == 3) {
            $.getScript( "public/vendor/stripe/checkout.js" );
            $(".card-element").show();
            $(".gift-card").hide();
            $("#cheque").hide();
        } else if (id == 4) {
            $("#cheque").show();
            $(".gift-card").hide();
            $(".card-element").hide();
            $('input[name="cheque_no"]').attr('required', true);
        } else if (id == 5) {
            $(".card-element").hide();
            $(".gift-card").hide();
            $("#cheque").hide();
        } else {
            $(".card-element").hide();
            $(".gift-card").hide();
            $("#cheque").hide();
            if(id == 6){
                if($('#add-payment input[name="amount"]').val() > parseFloat(deposit))
                    alert('Amount exceeds customer deposit! Customer deposit : ' + deposit);
            }
        }
    });
    
    $('#add-payment select[name="gift_card_id"]').on("change", function() {
        var id = $(this).val();
        if(expired_date[id] < current_date)
            alert('This card is expired!');
        else if($('#add-payment input[name="amount"]').val() > balance[id]){
            alert('Amount exceeds card balance! Gift Card balance: '+ balance[id]);
        }
    });

    $('input[name="paying_amount"]').on("input", function() {
        $(".change").text(parseFloat( $(this).val() - $('input[name="amount"]').val() ).toFixed(2));
    });

    $('input[name="amount"]').on("input", function() {
        if( $(this).val() > parseFloat($('input[name="paying_amount"]').val()) ) {
            alert('Paying amount cannot be bigger than recieved amount');
            $(this).val('');
        }
        else if( $(this).val() > parseFloat($('input[name="balance"]').val()) ) {
            alert('Paying amount cannot be bigger than due amount');
            $(this).val('');
        }
        $(".change").text(parseFloat($('input[name="paying_amount"]').val() - $(this).val()).toFixed(2));
        var id = $('#add-payment select[name="paid_by_id"]').val();
        var amount = $(this).val();
        if(id == 2){
            id = $('#add-payment select[name="gift_card_id"]').val();
            if(amount > balance[id])
                alert('Amount exceeds card balance! Gift Card balance: '+ balance[id]);
        }
        else if(id == 6){
            if(amount > parseFloat(deposit))
                alert('Amount exceeds customer deposit! Customer deposit : ' + deposit);
        }
    });

    $('select[name="edit_paid_by_id"]').on("change", function() {        
        var id = $(this).val();
        $('input[name="edit_cheque_no"]').attr('required', false);
        $('#edit-payment select[name="gift_card_id"]').attr('required', false);
        $(".payment-form").off("submit");
        if(id == 2){
            $(".card-element").hide();
            $("#edit-cheque").hide();
            $('.gift-card').show();
            $('#edit-payment select[name="gift_card_id"]').attr('required', true);
        }
        else if (id == 3) {
            $(".edit-btn").attr('data-clicked', true);
            $.getScript( "public/vendor/stripe/checkout.js" );
            $(".card-element").show();
            $("#edit-cheque").hide();
            $('.gift-card').hide();
        } else if (id == 4) {
            $("#edit-cheque").show();
            $(".card-element").hide();
            $('.gift-card').hide();
            $('input[name="edit_cheque_no"]').attr('required', true);
        } else {
            $(".card-element").hide();
            $("#edit-cheque").hide();
            $('.gift-card').hide();
            if(id == 6){
                if($('input[name="edit_amount"]').val() > parseFloat(deposit))
                    alert('Amount exceeds customer deposit! Customer deposit : ' + deposit);
            }
        }
    });

    $('#edit-payment select[name="gift_card_id"]').on("change", function() {
        var id = $(this).val();
        if(expired_date[id] < current_date)
            alert('This card is expired!');
        else if($('#edit-payment input[name="edit_amount"]').val() > balance[id])
            alert('Amount exceeds card balance! Gift Card balance: '+ balance[id]);
    });

    $('input[name="edit_paying_amount"]').on("input", function() {
        $(".change").text(parseFloat( $(this).val() - $('input[name="edit_amount"]').val() ).toFixed(2));
    });

    $('input[name="edit_amount"]').on("input", function() {
        if( $(this).val() > parseFloat($('input[name="edit_paying_amount"]').val()) ) {
            alert('Paying amount cannot be bigger than recieved amount');
            $(this).val('');
        }
        $(".change").text(parseFloat($('input[name="edit_paying_amount"]').val() - $(this).val()).toFixed(2));
        var amount = $(this).val();
        var id = $('#edit-payment select[name="gift_card_id"]').val();
        if(amount > balance[id]){
            alert('Amount exceeds card balance! Gift Card balance: '+ balance[id]);
        }
        var id = $('#edit-payment select[name="edit_paid_by_id"]').val();
        if(id == 6){
            if(amount > parseFloat(deposit))
                alert('Amount exceeds customer deposit! Customer deposit : ' + deposit);
        }
    });

    $(document).on("click", "table.sale-list tbody .add-delivery", function(event) {
        var id = $(this).data('id').toString();
        $.get('delivery/create/'+id, function(data) {
            $('#dr').text(data[0]);
            $('#sr').text(data[1]);
            if(data[2]){
                $('select[name="status"]').val(data[2]);
                $('.selectpicker').selectpicker('refresh');
            }
            $('input[name="delivered_by"]').val(data[3]);
            $('input[name="recieved_by"]').val(data[4]);
            $('#customer').text(data[5]);
            $('textarea[name="address"]').val(data[6]);
            $('textarea[name="note"]').val(data[7]);
            $('input[name="reference_no"]').val(data[0]);
            $('input[name="sale_id"]').val(id);
            $('#add-delivery').modal('show');
        });
    });

    $('#sale-table').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax":{
            url:"sales/sale-data",
            data:{
                all_permission: all_permission
            },
            dataType: "json",
            type:"post"
        },
        "createdRow": function( row, data, dataIndex ) {
            $(row).addClass('sale-link');
            $(row).attr('data-sale', data['sale']);
        },
        "columns": [
            {"data": "key"},
            {"data": "date"},
            {"data": "customer"},
            {"data": "billNo"},
            {"data": "reference_no"},
            {"data": "biller"},
            {"data": "total_qty"},
            {"data": "sale_status"},
            {"data": "payment_status"},
            {"data": "grand_total"},
            {"data": "options"},
        ],
        'language': {
            'searchPlaceholder': "{{trans('file.Type date or sale reference...')}}",
            'lengthMenu': '_MENU_ {{trans("file.records per page")}}',
             "info":      '{{trans("file.Showing")}} _START_ - _END_ (_TOTAL_)',
            "search":  '{{trans("file.Search")}}',
            'paginate': {
                    'previous': '{{trans("file.Previous")}}',
                    'next': '{{trans("file.Next")}}'
            }
        },
        order:[['1', 'desc']],
        'columnDefs': [
            {
                "orderable": false,
                'targets': [0, 3, 4, 5, 6, 9, 10]
            },
            {
                'checkboxes': {
                   'selectRow': true
                },
                'targets': 0
            }
        ],
        'select': { style: 'multi',  selector: 'td:first-child'},
        'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
        dom: '<"row"lfB>rtip',
        buttons: [
            {
                extend: 'pdf',
                text: '{{trans("file.PDF")}}',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
                action: function(e, dt, button, config) {
                    datatable_sum(dt, true);
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, button, config);
                    datatable_sum(dt, false);
                },
                footer:true
            },
            {
                extend: 'csv',
                text: '{{trans("file.CSV")}}',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
                action: function(e, dt, button, config) {
                    datatable_sum(dt, true);
                    $.fn.dataTable.ext.buttons.csvHtml5.action.call(this, e, dt, button, config);
                    datatable_sum(dt, false);
                },
                footer:true
            },
            {
                extend: 'print',
                text: '{{trans("file.Print")}}',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
                action: function(e, dt, button, config) {
                    datatable_sum(dt, true);
                    $.fn.dataTable.ext.buttons.print.action.call(this, e, dt, button, config);
                    datatable_sum(dt, false);
                },
                footer:true
            },
            {
                text: '{{trans("file.delete")}}',
                className: 'buttons-delete',
                action: function ( e, dt, node, config ) {
                    if(user_verified == '1') {
                        sale_id.length = 0;
                        $(':checkbox:checked').each(function(i){
                            if(i){
                                var sale = $(this).closest('tr').data('sale');
                                sale_id[i-1] = sale[14];
                            }
                        });
                        if(sale_id.length && confirm("Are you sure want to delete?")) {
                            $.ajax({
                                type:'POST',
                                url:'sales/deletebyselection',
                                data:{
                                    saleIdArray: sale_id
                                },
                                success:function(data){
                                    dt.rows({ page: 'current', selected: true }).deselect();
                                    dt.rows({ page: 'current', selected: true }).remove().draw(false);
                                }
                            });
                        }
                        else if(!sale_id.length)
                            alert('Nothing is selected!');
                    }
                    else
                        alert('This feature is disable for demo!');
                }
            },
            {
                extend: 'colvis',
                text: '{{trans("file.Column visibility")}}',
                columns: ':gt(0)'
            },
        ],
        drawCallback: function () {
            var api = this.api();
            datatable_sum(api, false);
        }
    } );

    function datatable_sum(dt_selector, is_calling_first) {
        if (dt_selector.rows( '.selected' ).any() && is_calling_first) {
            var rows = dt_selector.rows( '.selected' ).indexes();

            $( dt_selector.column( 7 ).footer() ).html(dt_selector.cells( rows, 7, { page: 'current' } ).data().sum().toFixed(2));
            $( dt_selector.column( 8 ).footer() ).html(dt_selector.cells( rows, 8, { page: 'current' } ).data().sum().toFixed(2));
            $( dt_selector.column( 9 ).footer() ).html(dt_selector.cells( rows, 9, { page: 'current' } ).data().sum().toFixed(2));
        }
        else {
            $( dt_selector.column( 7 ).footer() ).html(dt_selector.cells( rows, 7, { page: 'current' } ).data().sum().toFixed(2));
            $( dt_selector.column( 8 ).footer() ).html(dt_selector.cells( rows, 8, { page: 'current' } ).data().sum().toFixed(2));
            $( dt_selector.column( 9 ).footer() ).html(dt_selector.cells( rows, 9, { page: 'current' } ).data().sum().toFixed(2));
        }
    }

    function saleDetails(sale){
      
    }

    $(document).on('submit', '.payment-form', function(e) {
        if( $('input[name="paying_amount"]').val() < parseFloat($('#amount').val()) ) {
            alert('Paying amount cannot be bigger than recieved amount');
            $('input[name="amount"]').val('');
            $(".change").text(parseFloat( $('input[name="paying_amount"]').val() - $('#amount').val() ).toFixed(2));
            e.preventDefault();
        }
        else if( $('input[name="edit_paying_amount"]').val() < parseFloat($('input[name="edit_amount"]').val()) ) {
            alert('Paying amount cannot be bigger than recieved amount');
            $('input[name="edit_amount"]').val('');
            $(".change").text(parseFloat( $('input[name="edit_paying_amount"]').val() - $('input[name="edit_amount"]').val() ).toFixed(2));
            e.preventDefault();
        }
        
        $('#edit-payment select[name="edit_paid_by_id"]').prop('disabled', false);
    });
    
    if(all_permission.indexOf("sales-delete") == -1)
        $('.buttons-delete').addClass('d-none');

        function confirmDelete() {
            if (confirm("Are you sure want to delete?")) {
                return true;
            }
            return false;
        }

    function confirmPaymentDelete() {
        if (confirm("Are you sure want to delete? If you delete this money will be refunded.")) {
            return true;
        }
        return false;
    }


function someFunction(id){
    
  alert(id);
}

function delete_all_selected(){

    var array = []
    var checkboxes = document.querySelectorAll('input[type=checkbox]:checked')
    
    for (var i = 0; i < checkboxes.length; i++) {
      array.push(checkboxes[i].value)
    }
                            $.ajax( {
                                type:'GET',
                                header:{
                                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                                },
                                url:"https://sadaftraders.com/st/delete_all_selected/"+array,
                                
                            })
                            .done(function(data) {
                                // var product = jQuery.parseJSON(data);
                                // $('#price').val(product.price);
                                // $('#quantity').val(0);
                                // $('#discount').val(0);
                                // $('#sub_total').val(product.price);
                                // $('#product_id').val(product.id);
                                // $('#code').val(product.code);
                                // $('#sale_unit').val(product.sale_unit);
                                // sessionStorage.setItem("product_id", product.id);
                                console.log(data);
                            })
                            .fail(function() {
                                alert("error");
                            });
                        });

}
</script>
@endsection

@section('scripts')
           

@endsection