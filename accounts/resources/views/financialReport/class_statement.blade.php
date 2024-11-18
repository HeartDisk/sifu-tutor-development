@extends('layouts.main')

@section('content')

    <br/><br/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;700&family=Raleway:wght@100&display=swap');

        .detail {
            font-size: 15px !important;
            width: 600px;
        }

        .city_color {
            color: red !important;
        }

        #customerUrduName {
            font-family: 'Noto Naskh Arabic', serif;
            font-size: 1.5em;
        }

        #customerUrduNameTwo {
            font-family: 'Noto Naskh Arabic', serif;
            font-size: 1.5em;
        }


        #customerUrduNameHeader {
            font-family: 'Noto Naskh Arabic', serif;
            font-size: 1.5em;
        }


        .dropdown-item .selected .active {
            color: #ffffff;
        }

        table tr td {
            font-size: 14px !important;
            color: #000;
        }

        .account {
            font-weight: bold !important;
            font-size: 27px !important;
            color: #000 !important;
        }

        .blue {
            background: blue;
        }


        body {
            margin: 0;
            background-color: #f1f1f1;
            font-family: Arial, Helvetica, sans-serif;
        }

        #navbar {
            background-color: #ffffff;
            position: fixed;
            top: -50px;
            width: 100%;
            display: block;
            transition: top 0.3s;
        }

        #navbar a {
            float: left;
            display: block;
            color: #000000;
            text-align: left;
            padding: 5px;
            text-decoration: none;
            font-size: 17px;
            border-bottom: 1px solid #000;
        }

        #navbar a:hover {
            background-color: yellow;
            color: purple;
        }

        .bootstrap-select {
            width: 500px !important;
        }

        .dropdown-item.active, .dropdown-item:active {
            background-color: yellow;
            color: #000000;
        }

        a {
            color: #000;
        }

        .overline-title {
            font-size: 12px;
        }
    </style>
    <section class="forms mt-5">
        <div class="bg container-fluid">
            <div class="card">
                <div style="background:#2e314a; padding:10px;" class="card-header mt-2">
                    <h2 style="color:#fff;" class="text-center"> Class Statement</h2>
                </div>
                <div class="card-body">

                    <div class="modal-content">

                        <table style="width:100%">
                            <tr>
                                <td valign="TOP" style="width:60% !important">
                                    <table style="width:100%">
                                        <tr>
                                            <td>
                                                <div class="modal-body">
                                                    <table style="width:100%">
                                                        <tr>
                                                            <td>
                                                                <form action="" method="POST">


                                                                    <div class="row mb-3">
                                                                        <div class="col-md-12 form-group">
                                                                            <select class="form-control selectpicker"
                                                                                    id="account_id" name="account_id"
                                                                                    data-live-search="true"
                                                                                    data-live-search-style="true"
                                                                                    title="Select customer...">
                                                                                <option style="font-size:16px;"
                                                                                        value="">Select Customer
                                                                                </option>
                                                                                @php $customers = DB::table('customers')->where('status', 'active')->get(); @endphp
                                                                                @foreach($customers as $account)
                                                                                    @php
                                                                                        $cityName = DB::table('cities')->where('id', $account->city)->first();
                                                                                    @endphp
                                                                                    <option style="font-size:16px;"
                                                                                            value="{{$account->id}}">{{$account->full_name}}
                                                                                        <span class="city_color">({{$cityName->name}})</span>
                                                                                        ({{$account->id}})
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>

                                        </tr>
                                    </table>
                                    <!--<table class="mb-4">-->
                                    <!--    <tr class="d-flex">-->
                                    <!--        <td style="font-size:16px !important;margin-right:20px;"> From Date: <input id="from_date" class="form-control mr-3" name="from_date" type="date"/> </td>-->
                                    <!--        <td style="font-size:16px !important;"> To Date:  <input id="to_date" class="form-control" name="to_date" type="date"/> </td>-->
                                    <!--        <td style="vertical-align: bottom;">-->

                                    <!--        </td>-->
                                    <!--    </tr>-->
                                    <!--</table>-->
                                    <div>
                                        <div class="table-responsive">
                                            <table class="table table-hover rounded overflow-hidden" id='userTable'>

                                                <thead class="table-dark">

                                                <tr style="font-size:16px;">
                                                    <th style="width:50px;">S.No</th>
                                                    <th style="width:100px;">Date</th>
                                                    <th style="width:350px;">Description</th>
                                                    <th style="text-align:right; width:100px;">Debit</th>
                                                    <th style="text-align:right; width:100px;">Credit</th>
                                                    <th style="text-align:right; width:100px;">Balance</th>

                                                </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                <tr>
                                                    <th style="font-size:16px;"></th>
                                                    <th style="width:70px; font-size:16px;"></th>
                                                    <th style="font-size:16px;"></th>
                                                    <th style="text-align: right;"><span id="total_debit"></span></th>
                                                    <th style="text-align: right;"><span id="total_credit"></span></th>
                                                    <th style="text-align: right;"><span id="total_balance"></span></th>

                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </td>


                            </tr>
                        </table>
                        <div id="cehck_records">

                        </div>
                    </div>


                </div>


            </div>
        </div>

        <div id="output"></div>

    </section>


    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"
            integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>

    <script>

       
        $('select[name="account_id"]').on('change', function () {
          
            var id = 'payment_' + $(this).val();
            var idTwo = $(this).val();
            $('#customerIDTWO').val(idTwo);

            $.ajax({
                type: 'GET',
                header: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('/financialReport/') }}/{{ $id ?? '' }}/customer_balance",
            })
                .done(function (data) {
                    console.log(data['closing_balance']);
                    //var balance = jQuery.parseJSON(data);
                    $('#customerBalance').text(data[0]);
                    $('#customerBalanceHeader').text(data[0]);
                    $('#customerBalance_two').text(data[0]);
                    $('#customerUrduName').text(data[1]);
                    $('#customerUrduNameTwo').text(data[1]);
                    $('#customerUrduNameHeader').text(data[1]);
                    $('.customerNameforBalance').text(data[1]);
                    $('.customerNameforBalanceTWO').text(data[1]);
                    $('#customerID').val(idTwo);
                    $('#amount').val(data['closing_balance']);
                    $('#customer_last_date').text(data[2]);
                    $('#total_receiving').text(data[3]);
                    $('#phone_number').text(data[4]);
                    $('#currentBalance').text(data);
                    $('#closingBalance').html(data['closing_balance']);
                    var currentBalance = data;
                    $('#is_checked_one').val(id);
                })
                .fail(function () {
                    // alert("error");
                });
            //$.get('getcustomergroup/' + id, function(data) {
            //  customer_group_rate = (data / 100);
            //});

            fetchRecords(id);

            checkRecords(id);

        });


        $('#from_date').change(function () {
            var date = $(this).val();
            var id = $('#account_id').val();
            fetchRecords_by_date(id, date);
        });

        function a_bill_hover() {
            $('.billHover').click(function (evt) {
                evt.preventDefault();
                //var divId = 'summary' + $(this).attr('id');
                var aBillHover = $(this).attr('id');

                $.ajax({
                    url: 'getBillRecords/' + aBillHover,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function () {
                        //   $("#solTitle a").hide();
                    },
                    success: function (response) {

                        $('.div_bill_hover').html();
                        Swal.fire({
                            title: 'INV No: ' + response.bill_no,
                            html: response.product_sales_json,
                            confirmButtonText: "Close",
                        });
                    }


                });
            });
        }

        function openSolution() {
            $('#solTitle a').click(function (evt) {
                evt.preventDefault();
                //var divId = 'summary' + $(this).attr('id');
                var divId = $(this).attr('id');
                var current_balance = $(this).attr('class');
                //alert(current_balance);
                //document.getElementById(divId).className = '';

                $.ajax({
                    url: 'getRecords_tick/' + divId + '/' + current_balance,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function () {
                        $("#solTitle a").hide();
                    },
                    success: function (response) {
                        alert(response.success);
                        $("#solTitle a").show();
                        if (response.success == 'Updated Successfully.') { // if true (1)
                            setTimeout(function () {// wait for 5 secs(2)
                                fetchRecords(response.id);
                            }, 3000);
                        }
                    }
                });

            });
        }


        $('select[name="is_checked"]').on('change', function () {
            var is_checked = $(this).val();
            var customerBalance_two = $('#customerBalance_two').html();

            var account_id = $('#is_checked_one').val();
            cbName = account_id.replace("payment_", "");

            if (is_checked != "") {

                if (confirm('کیا آپ نے اس کسٹمر کا ریکارڈ چیک کرلیا')) {

                    $.ajax({
                        url: 'is_checked/' + is_checked + '/' + cbName + '/' + customerBalance_two,
                        type: 'get',
                        dataType: 'json',

                        success: function (response) {
                            alert(response.date);
                            $("#solTitle a").show();
                            if (response.success == 'Updated Successfully.') { // if true (1)
                                setTimeout(function () {// wait for 5 secs(2)
                                    fetchRecords(response.id);
                                }, 3000);
                            }
                        }
                    });

                } else {
                    alert('you pressed Canceled Button!');
                    $('select[name="is_checked_one"]').val("");
                }
            }
        });

        $('#to_date').change(function () {
            var to_date = $(this).val();
            var id = $('#account_id').val();
            var from_date = $('#from_date').val();
            fetchRecords_by_todate(id, to_date, from_date);
        });


        function checkRecords(id) {
            $.ajax({
                url: 'checkRecords/' + id,
                type: 'get',
                dataType: 'json',
                success: function (response) {
                    $('#cehck_records').html(response);
                }
            })
        };

        function fetchRecords(id) {
            var customer_id = id;
            $.ajax({
                url: 'getRecords/' + id,
                type: 'get',
                dataType: 'json',
                success: function (response) {
                    var closing_balance = response['closing_balance'];
                    $('#closingBalance').html(closing_balance);
                    var total_debit = response['total_debit'];
                    $('#total_debit').html(total_debit);
                    var total_credit = response['total_credit'];
                    $('#total_credit').html(total_credit);
                    $('#out_standing').html(response['out_standing']);
                    var len = 0;
                    $('#userTable tbody').empty(); // Empty <tbody>
                    if (response['data'] != null) {
                        len = response['data'].length;
                    }

                    var currentBalance = 0;

                    if (len > 0) {
                        for (var i = 0; i < len; i++) {
                            var id = response['data'][i].id;
                            var date = response['data'][i].date;
                            var new_date = response['data'][i].formatted_date;
                            var expires_in = response['data'][i].expires_in;

                            var bill_no = response['data'][i].bill_no;
                            var sale_id = response['data'][i].sale_id;
                            var credit = response['data'][i].credit;
                            var debit = response['data'][i].debit;

                            var payment_note = response['data'][i].payment_note;
                            var sale_note = response['data'][i].sale_note;
                            var checked_amount = response['data'][i].checked_amount;
                            var receive_voucher_id = response['data'][i].receive_voucher_id;
                            var payment_voucher_id = response['data'][i].payment_voucher_id;

                            var checked_amount = response['data'][i].checked_amount;

                            if (checked_amount == null) {
                                var checked_amount = '';
                            }

                            if (receive_voucher_id == null) {
                                var receive_voucher_id = '';
                            }

                            if (payment_voucher_id == null) {
                                var payment_voucher_id = '';
                            }

                            if (payment_note == null) {
                                var payment_note = '';
                            }
                            if (sale_note == null) {
                                var sale_note = '';
                            }
                            var status = response['data'][i].status;

                            if (bill_no != null) {
                                var bill_no = "<a href='#' class='billHover' onclick='a_bill_hover();' id='" + sale_id + "' data-toggle='tooltip' data-html='true' title='Click Here'><i class='fa fa-eye' aria-hidden='true'></i></a> INV No: <a class='bill_hover' style=' font-weight:bold;' target='_blank' href='{{ url('/customerStatement/') }}/" + response['data'][i].bill_no + "/viewinvoice/" + customer_id + "'>" + response['data'][i].bill_no + '</a>';
                            } else {
                                if (sale_id == 'Transfered') {
                                    var bill_no = "<a target='_blank'  href='../customer-transferr-edit/" + id + "/" + date + "' style='font-weight:bold; color:purple'> Transfered </a>";
                                } else {
                                    var bill_no = sale_id;
                                }
                            }

                            if (debit != null) {
                                currentBalance = parseInt(debit) + parseInt(currentBalance);
                                var debit = response['data'][i].debit;
                                var credit = 0.00
                            } else {
                                currentBalance = parseInt(currentBalance) - parseInt(credit);
                                var credit = response['data'][i].credit;
                                var debit = 0.00;
                                if (sale_id == 'Transfered') {
                                    var bill_no = "<a target='_blank'  href='../customer-transferr-edit/" + id + "/" + date + "' style='font-weight:bold; color:purple'> Transfered </a>";
                                } else {
                                    var bill_no = "<a target='_blank'  href='../customer-receiving-voucher-edit/" + receive_voucher_id + "' style='font-weight:bold; color:#38BC1C'> Receipt: " + receive_voucher_id + "</a>";
                                }
                            }


                            if (status == 1) {
                                var tr_str = "<tr style='background:#D8D8D8' id='refinement-menu'>" +
                                    "<td style='width:50px;' align='left'>" + (i + 1) + "</td>" +
                                    "<td style='' align='left'>" + new_date + "</td>" +

                                    "<td style=' width:250px;' align='left'>" + bill_no + "<span style='display:block; font-size;:12px;>" + payment_note + "</span><span style='display:block; font-size:13px; font-weight:bold;'>" + sale_note + "</span></td>" +
                                    "<td style='' class='receipt' align='right'>" + debit + "</td>" +
                                    "<td style='' class='payment' align='right'>" + credit + "</td>" +
                                    "<td style='' class='balance' align='right'>" + currentBalance + "</td>" +
                                    "</tr>";

                            } else {
                                var tr_str = "<tr id='refinement-menu'>" +
                                    "<td style='' align='left'>" + (i + 1) + "</td>" +
                                    "<td style='' align='left'>" + new_date + "</td>" +

                                    "<td style=' width:250px;' align='left'>" + bill_no + "<span style='display:block; font-size:13px; font-weight:bold;'>" + payment_note + "</span><span style='display:block; font-size:13px; font-weight:bold;'>" + sale_note + "</span></td>" +
                                    "<td style=''class='receipt' align='right'>" + debit + "</td>" +
                                    "<td style='' class='payment' align='right'>" + credit + "</td>" +
                                    "<td style='' class='balance' align='right'>" + currentBalance + "</td>" +

                                    "</tr>";
                            }


                            $("#userTable tbody").append(tr_str);
                        }
                    } else {
                        var tr_str = "<tr>" +
                            "<td align='right' colspan='4'>No record found.</td>" +
                            "</tr>";

                        $("#userTable tbody").append(tr_str);
                    }


                    var len = 0;
                    $('#userTable_two tbody').empty(); // Empty <tbody>
                    if (response['data'] != null) {
                        len = response['data'].length;
                    }

                    var currentBalance_two = response['total_receiving'];

                    if (len > 0) {
                        for (var i = 0; i < len; i++) {
                            var id = response['data'][i].id;
                            var date = response['data'][i].date;
                            var new_date = response['data'][i].formatted_date;
                            var bill_no = response['data'][i].bill_no;
                            var sale_id = response['data'][i].sale_id;
                            var debit_two = response['data'][i].debit;
                            var payment_note = response['data'][i].payment_note;
                            var sale_note = response['data'][i].sale_note;
                            var checked_amount = response['data'][i].checked_amount;
                            var receive_voucher_id = response['data'][i].receive_voucher_id;
                            var payment_voucher_id = response['data'][i].payment_voucher_id;

                            if (receive_voucher_id == null) {
                                var receive_voucher_id = '';
                            }

                            if (checked_amount == null) {
                                var checked_amount = '';
                            }

                            if (payment_note == null) {
                                var payment_note = '';
                            }
                            if (sale_note == null) {
                                var sale_note = '';
                            }
                            var status = response['data'][i].status;
                            var isRow = 1;
                            if (bill_no != null) {
                                var bill_no = "<a href='#' data-toggle='tooltip' data-html='true' title='Click Here'><i class='fa fa-eye' aria-hidden='true'></i></a> INV No: <a style='font-weight:bold;' target='_blank' href='{{ url('/customerStatement/') }}/" + response['data'][i].bill_no + "/viewinvoice/" + customer_id + "'>" + response['data'][i].bill_no + '</a>';
                            } else {
                                var bill_no = sale_id;
                            }

                            if (debit_two != null) {
                                currentBalance_two = currentBalance_two - debit_two;
                                var debit_two = response['data'][i].debit;
                            } else {
                                bill_no = "";
                                new_date = "";
                            }
                            var tr_str = ""
                            if (isRow) {

                                if (status == 1) {
                                    var tr_str = "<tr style='background:#D8D8D8' id='refinement-menu'>" +
                                        "<td align='right'>" + (i + 1) + "</td>" +
                                        "<td align='right'>" + new_date + "</td>" +
                                        "<td align='right'>" + bill_no + "</td>" +
                                        "<td class='receipt' align='right'>" + debit_two + "</td>" +
                                        "<td class='balance' align='right'>" + currentBalance_two + "</td>" +


                                        "</tr>";
                                } else {
                                    tr_str = "<tr id='refinement-menu'>" +
                                        "<td align='right'>" + (i + 1) + "</td>" +
                                        "<td align='right'>" + new_date + "</td>" +
                                        "<td align='right'>" + bill_no + "</td>" +
                                        "<td class='receipt' align='right'>" + debit_two + "</td>" +
                                        "<td class='balance' align='right'>" + currentBalance_two + "</td>" +


                                        "</tr>";
                                }
                            }

                            $("#userTable_two tbody").append(tr_str);
                        }
                    } else {
                        var tr_str = "<tr>" +
                            "<td align='right' colspan='4'>No record found.</td>" +
                            "</tr>";

                        $("#userTable_two tbody").append(tr_str);
                    }


                }
            });
        }

        function fetchRecords_by_date(id, date) {
            $.ajax({
                url: 'getRecords_by_date/' + id + '/' + date,
                type: 'get',
                dataType: 'json',
                success: function (response) {
                    var total_debit = response['total_debit_by_date'];
                    $('#total_debit').html(total_debit);
                    var total_credit = response['total_credit_by_date'];
                    $('#total_credit').html(total_credit);
                    var len = 0;
                    $('#userTable tbody').empty(); // Empty <tbody>
                    if (response['data'] != null) {
                        len = response['data'].length;
                    }

                    var currentBalance = response['current_balance'];

                    if (len > 0) {
                        for (var i = 0; i < len; i++) {
                            var id = response['data'][i].id;
                            var date = response['data'][i].date;
                            var new_date = response['data'][i].formatted_date;
                            var bill_no = response['data'][i].bill_no;
                            var sale_id = response['data'][i].sale_id;
                            var credit = response['data'][i].credit;
                            var debit = response['data'][i].debit;
                            var payment_note = response['data'][i].payment_note;
                            var sale_note = response['data'][i].sale_note;
                            var checked_amount = response['data'][i].checked_amount;
                            var receive_voucher_id = response['data'][i].receive_voucher_id;
                            var payment_voucher_id = response['data'][i].payment_voucher_id;
                            var customer_id = response['data'][i].account_id;


                            if (receive_voucher_id == null) {
                                var receive_voucher_id = '';
                            }
                            if (checked_amount == null) {
                                var checked_amount = '';
                            }

                            if (payment_note == null) {
                                var payment_note = '';
                            }
                            if (sale_note == null) {
                                var sale_note = '';
                            }
                            var status = response['data'][i].status;

                            if (bill_no != null) {
                                var bill_no = "<a href='#' data-toggle='tooltip' data-html='true' title='Click Here'><i class='fa fa-eye' aria-hidden='true'></i></a> INV No: <a style='font-weight:bold;' target='_blank' href='{{ url('/customerStatement/') }}/" + response['data'][i].bill_no + "/viewinvoice/" + customer_id + "'>" + response['data'][i].bill_no + '</a>';


                            } else {
                                if (sale_id == 'Transfered') {
                                    var bill_no = "<a target='_blank'  href='../customer-transferr-edit/" + id + "/" + date + "' style='font-weight:bold; color:purple'> Transfered </a>";
                                } else {
                                    var bill_no = sale_id;
                                }
                            }

                            if (debit != null) {
                                currentBalance = parseInt(debit) + parseInt(currentBalance);
                                var debit = response['data'][i].debit;
                                var credit = 0.00
                            } else {
                                currentBalance = parseInt(currentBalance) - parseInt(credit);
                                var credit = response['data'][i].credit;
                                var debit = 0.00;
                                if (sale_id == 'Transfered') {
                                    var bill_no = "<a target='_blank'  href='../customer-transferr-edit/" + id + "/" + date + "' style='font-weight:bold; color:purple'> Transfered </a>";
                                } else {
                                    var bill_no = "<a target='_blank'  href='../customer-receiving-voucher-edit/" + receive_voucher_id + "' style='font-weight:bold; color:#38BC1C'> Receipt: " + receive_voucher_id + "</a>";
                                }

                            }


                            if (status == 1) {
                                var tr_str = "<tr style='background:#D8D8D8' id='refinement-menu'>" +
                                    "<td align='right'>" + (i + 1) + "</td>" +
                                    "<td align='right'>" + new_date + "</td>" +
                                    "<td align='right'> &nbsp; </td>" +
                                    "<td style='width:250px;' align='right'>" + bill_no + "<span style='font-size:13px; font-weight:bold;'>" + payment_note + "</span><span style='display:block; font-size:13px; font-weight:bold;'>" + sale_note + "</span></td>" +
                                    "<td class='receipt' align='right'>" + debit + "</td>" +
                                    "<td class='payment' align='right'>" + credit + "</td>" +
                                    "<td class='balance' align='right'>" + currentBalance + "</td>" +
                                    "<td align='right'><div id='solTitle'><a class='" + currentBalance + "' onClick='openSolution();' id='" + (i + 1) + "'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='display:block; font-weight:bold;'>" + checked_amount + "</span></td>" +


                                    "</tr>";
                            } else {
                                var tr_str = "<tr id='refinement-menu'>" +
                                    "<td align='right'>" + (i + 1) + "</td>" +
                                    "<td align='right'>" + new_date + "</td>" +
                                    "<td align='right'> &nbsp; </td>" +
                                    "<td style='width:250px;' align='right'>" + bill_no + "<span style='font-size:13px; font-weight:bold;'>" + payment_note + "</span><span style='display:block; font-size:13px; font-weight:bold;'>" + sale_note + "</span></td>" +
                                    "<td class='receipt' align='right'>" + debit + "</td>" +
                                    "<td class='payment' align='right'>" + credit + "</td>" +
                                    "<td class='balance' align='right'>" + currentBalance + "</td>" +
                                    "<td align='right'><div id='solTitle'><a class='" + currentBalance + "' onClick='openSolution();' id='" + (i + 1) + "'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='display:block; font-weight:bold;'>" + checked_amount + "</span></td>" +

                                    "</tr>";
                            }


                            $("#userTable tbody").append(tr_str);
                        }
                    } else {
                        var tr_str = "<tr>" +
                            "<td align='right' colspan='4'>No record found.</td>" +
                            "</tr>";

                        $("#userTable tbody").append(tr_str);
                    }


                    var len = 0;
                    $('#userTable_two tbody').empty(); // Empty <tbody>
                    if (response['data'] != null) {
                        len = response['data'].length;
                    }

                    var currentBalance_two = response['total_receiving'];

                    if (len > 0) {
                        for (var i = 0; i < len; i++) {
                            var id = response['data'][i].id;
                            var date = response['data'][i].date;
                            var new_date = response['data'][i].formatted_date;
                            var bill_no = response['data'][i].bill_no;
                            var sale_id = response['data'][i].sale_id;
                            var debit_two = response['data'][i].debit;
                            var payment_note = response['data'][i].payment_note;
                            var sale_note = response['data'][i].sale_note;
                            var checked_amount = response['data'][i].checked_amount;
                            var receive_voucher_id = response['data'][i].receive_voucher_id;
                            var payment_voucher_id = response['data'][i].payment_voucher_id;

                            if (receive_voucher_id == null) {
                                var receive_voucher_id = '';
                            }

                            if (checked_amount == null) {
                                var checked_amount = '';
                            }

                            if (payment_note == null) {
                                var payment_note = '';
                            }
                            if (sale_note == null) {
                                var sale_note = '';
                            }
                            var status = response['data'][i].status;
                            var isRow = 1;
                            if (bill_no != null) {
                                var bill_no = "<a href='#' data-toggle='tooltip' data-html='true' title='Click Here'><i class='fa fa-eye' aria-hidden='true'></i></a> INV No: <a target='_blank' style='font-weight:bold;' href='{{ url('/customerStatement/') }}/" + response['data'][i].bill_no + "/viewinvoice/" + customer_id + "'>" + response['data'][i].bill_no + '</a>';
                            } else {
                                var bill_no = sale_id;
                            }

                            if (debit_two != null) {
                                currentBalance_two = currentBalance_two - debit_two;
                                var debit_two = response['data'][i].debit;
                            } else {
                                bill_no = "";
                                new_date = "";
                            }
                            var tr_str = ""
                            if (isRow) {

                                if (status == 1) {
                                    var tr_str = "<tr style='background:#D8D8D8' id='refinement-menu'>" +
                                        "<td align='right'>" + (i + 1) + "</td>" +
                                        "<td align='right'>" + new_date + "</td>" +
                                        "<td align='right'> &nbsp; </td>" +
                                        "<td align='right'>" + bill_no + "</td>" +
                                        "<td class='receipt' align='right'>" + debit_two + "</td>" +
                                        "<td class='balance' align='right'>" + currentBalance_two + "</td>" +


                                        "</tr>";
                                } else {
                                    tr_str = "<tr id='refinement-menu'>" +
                                        "<td align='right'>" + (i + 1) + "</td>" +
                                        "<td align='right'>" + new_date + "</td>" +
                                        "<td align='right'> &nbsp; </td>" +
                                        "<td align='right'>" + bill_no + "</td>" +
                                        "<td class='receipt' align='right'>" + debit_two + "</td>" +
                                        "<td class='balance' align='right'>" + currentBalance_two + "</td>" +


                                        "</tr>";
                                }
                            }

                            $("#userTable_two tbody").append(tr_str);
                        }
                    } else {
                        var tr_str = "<tr>" +
                            "<td align='right' colspan='4'>No record found.</td>" +
                            "</tr>";

                        $("#userTable_two tbody").append(tr_str);
                    }


                }
            });
        }

        function fetchRecords_by_todate(id, to_date, from_date) {
            $.ajax({
                url: 'getRecords_by_todate/' + id + '/' + to_date + '/' + from_date,
                type: 'get',
                dataType: 'json',
                success: function (response) {

                    var len = 0;
                    var total_debit = response['total_debit_to_date'];
                    $('#total_debit').html(total_debit);
                    var total_credit = response['total_credit_to_date'];
                    $('#total_credit').html(total_credit);

                    $('#userTable tbody').empty(); // Empty <tbody>
                    if (response['data'] != null) {
                        len = response['data'].length;
                    }


                    var currentBalance = response['current_balance'];


                    if (len > 0) {
                        for (var i = 0; i < len; i++) {
                            var id = response['data'][i].id;
                            var date = response['data'][i].date;
                            var new_date = response['data'][i].formatted_date;
                            var bill_no = response['data'][i].bill_no;
                            var sale_id = response['data'][i].sale_id;
                            var credit = response['data'][i].credit;
                            var debit = response['data'][i].debit;
                            var payment_note = response['data'][i].payment_note;
                            var sale_note = response['data'][i].sale_note;
                            var checked_amount = response['data'][i].checked_amount;
                            var receive_voucher_id = response['data'][i].receive_voucher_id;
                            var payment_voucher_id = response['data'][i].payment_voucher_id;
                            var total_credit = response['data'].total_credit_to_date;
                            var total_debit = response['data'].total_debit_to_date;
                            var customer_id = response['data'].account_id;

                            if (receive_voucher_id == null) {
                                var receive_voucher_id = '';
                            }

                            if (checked_amount == null) {
                                var checked_amount = '';
                            }

                            if (payment_note == null) {
                                var payment_note = '';
                            }
                            if (sale_note == null) {
                                var sale_note = '';
                            }
                            var status = response['data'][i].status;

                            if (bill_no != null) {
                                var bill_no = "<a href='#' data-toggle='tooltip' data-html='true' title='Click Here'><i class='fa fa-eye' aria-hidden='true'></i></a> INV No: <a target='_blank' class='bill_hover' style='font-weight:bold;' href='{{ url('/customerStatement/') }}/" + response['data'][i].bill_no + "/viewinvoice/" + customer_id + "'>"
                            } else {
                                if (sale_id == 'Transfered') {
                                    var bill_no = "<a target='_blank'  href='../customer-transferr-edit/" + id + "/" + date + "' style='font-weight:bold; color:purple'> Transfered </a>";
                                } else {
                                    var bill_no = sale_id;
                                }
                            }

                            if (debit != null) {
                                currentBalance = parseInt(debit) + parseInt(currentBalance);
                                var debit = response['data'][i].debit;
                                var credit = 0.00
                            } else {
                                currentBalance = parseInt(currentBalance) - parseInt(credit);
                                var credit = response['data'][i].credit;
                                var debit = 0.00;
                                if (sale_id == 'Transfered') {
                                    var bill_no = "<a target='_blank'  href='../customer-transferr-edit/" + id + "/" + date + "' style='font-weight:bold; color:purple'> Transfered </a>";
                                } else {
                                    var bill_no = "<a target='_blank'  href='../customer-receiving-voucher-edit/" + receive_voucher_id + "' style='font-weight:bold; color:#38BC1C'> Receipt: " + receive_voucher_id + "</a>";
                                }

                            }


                            if (status == 1) {
                                var tr_str = "<tr style='background:#D8D8D8' id='refinement-menu'>" +
                                    "<td align='right'>" + (i + 1) + "</td>" +
                                    "<td align='right'>" + new_date + "</td>" +
                                    "<td align='right'> &nbsp; </td>" +
                                    "<td style='width:250px;' align='right'>" + bill_no + "<span style= font-weight:bold;'>" + payment_note + "</span><span style='display:block; font-size:13px; font-weight:bold;'>" + sale_note + "</span></td>" +
                                    "<td class='receipt' align='right'>" + debit + "</td>" +
                                    "<td class='payment' align='right'>" + credit + "</td>" +
                                    "<td class='balance' align='right'>" + currentBalance + "</td>" +
                                    "<td align='right'><div id='solTitle'><a class='" + currentBalance + "' onClick='openSolution();' id='" + (i + 1) + "'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='display:block; font-weight:bold;'>" + checked_amount + "</span> </td>" +


                                    "</tr>";
                            } else {
                                var tr_str = "<tr id='refinement-menu'>" +
                                    "<td align='right'>" + (i + 1) + "</td>" +
                                    "<td align='right'>" + new_date + "</td>" +
                                    "<td align='right'> &nbsp; </td>" +
                                    "<td style='width:250px;' align='right'>" + bill_no + "<span style= font-weight:bold;'>" + payment_note + "</span><span style='display:block; font-size:13px; font-weight:bold;'>" + sale_note + "</span></td>" +
                                    "<td class='receipt' align='right'>" + debit + "</td>" +
                                    "<td class='payment' align='right'>" + credit + "</td>" +
                                    "<td class='balance' align='right'>" + currentBalance + "</td>" +
                                    "<td align='right'><div id='solTitle'><a class='" + currentBalance + "' onClick='openSolution();' id='" + (i + 1) + "'> <i class='fa fa-check' aria-hidden='true'></i></a> <span style='display:block; font-weight:bold;'>" + checked_amount + "</span> </td>" +

                                    "</tr>";
                            }


                            $("#userTable tbody").append(tr_str);
                        }
                    } else {
                        var tr_str = "<tr>" +
                            "<td align='right' colspan='4'>No record found.</td>" +
                            "</tr>";

                        $("#userTable tbody").append(tr_str);
                    }


                    var len = 0;
                    $('#userTable_two tbody').empty(); // Empty <tbody>
                    if (response['data'] != null) {
                        len = response['data'].length;
                    }

                    var currentBalance_two = response['total_receiving'];

                    if (len > 0) {
                        for (var i = 0; i < len; i++) {
                            var id = response['data'][i].id;
                            var date = response['data'][i].date;
                            var new_date = response['data'][i].formatted_date;
                            var bill_no = response['data'][i].bill_no;
                            var sale_id = response['data'][i].sale_id;
                            var debit_two = response['data'][i].debit;
                            var payment_note = response['data'][i].payment_note;
                            var sale_note = response['data'][i].sale_note;
                            var checked_amount = response['data'][i].checked_amount;
                            var receive_voucher_id = response['data'][i].receive_voucher_id;
                            var payment_voucher_id = response['data'][i].payment_voucher_id;


                            if (receive_voucher_id == null) {
                                var receive_voucher_id = '';
                            }

                            if (checked_amount == null) {
                                var checked_amount = '';
                            }

                            if (payment_note == null) {
                                var payment_note = '';
                            }
                            if (sale_note == null) {
                                var sale_note = '';
                            }
                            var status = response['data'][i].status;
                            var isRow = 1;
                            if (bill_no != null) {
                                var bill_no = "<a href='#' data-toggle='tooltip' data-html='true' title='Click Here'><i class='fa fa-eye' aria-hidden='true'></i></a> INV No: <a target='_blank' class='bill_hover' style='font-weight:bold;' href='{{ url('/customerStatement/') }}/" + response['data'][i].bill_no + "/viewinvoice/" + customer_id + "'>" + response['data'][i].bill_no + '</a>';
                            } else {
                                var bill_no = sale_id;
                            }

                            if (debit_two != null) {
                                currentBalance_two = currentBalance_two - debit_two;
                                var debit_two = response['data'][i].debit;
                            } else {
                                bill_no = "";
                                new_date = "";
                            }
                            var tr_str = ""
                            if (isRow) {

                                if (status == 1) {
                                    var tr_str = "<tr style='background:#D8D8D8' id='refinement-menu'>" +
                                        "<td align='right'>" + (i + 1) + "</td>" +
                                        "<td align='right'>" + new_date + "</td>" +

                                        "<td align='right'>" + bill_no + "</td>" +
                                        "<td class='receipt' align='right'>" + debit_two + "</td>" +
                                        "<td class='balance' align='right'>" + currentBalance_two + "</td>" +


                                        "</tr>";
                                } else {
                                    tr_str = "<tr id='refinement-menu'>" +
                                        "<td align='right'>" + (i + 1) + "</td>" +
                                        "<td align='right'>" + new_date + "</td>" +

                                        "<td align='right'>" + bill_no + "</td>" +
                                        "<td class='receipt' align='right'>" + debit_two + "</td>" +
                                        "<td class='balance' align='right'>" + currentBalance_two + "</td>" +


                                        "</tr>";
                                }
                            }

                            $("#userTable_two tbody").append(tr_str);
                        }
                    } else {
                        var tr_str = "<tr>" +
                            "<td align='right' colspan='4'>No record found.</td>" +
                            "</tr>";

                        $("#userTable_two tbody").append(tr_str);
                    }


                }
            });
        }


        $("ul#account").siblings('a').attr('aria-expanded', 'true');
        $("ul#account").addClass("show");
        //$("ul#account #account-statement-menu").addClass("active");

        $('#account-table').DataTable({
            "order": [],
            'language': {
                'lengthMenu': '_MENU_ {{trans("file.records per page")}}',
                "info": '{{trans("file.Showing")}} _START_ - _END_ (_TOTAL_)',
                "search": '{{trans("file.Search")}}',
                'paginate': {
                    'previous': '{{trans("file.Previous")}}',
                    'next': '{{trans("file.Next")}}'
                }
            },
            'columnDefs': [
                {
                    "orderable": false,
                    'targets': 0
                },
                {
                    'checkboxes': {
                        'selectRow': true
                    },
                    'targets': 0
                }
            ],
            'select': {style: 'multi', selector: 'td:first-child'},
            'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
            dom: '<"row"lfB>rtip',
            buttons: [
                {
                    extend: 'pdf',
                    text: '{{trans("file.PDF")}}',
                    exportOptions: {
                        columns: ':visible:Not(.not-exported)',
                        rows: ':visible'
                    }
                },
                {
                    extend: 'csv',
                    text: '{{trans("file.CSV")}}',
                    exportOptions: {
                        columns: ':visible:Not(.not-exported)',
                        rows: ':visible'
                    }
                },
                {
                    extend: 'print',
                    text: '{{trans("file.Print")}}',
                    exportOptions: {
                        columns: ':visible:Not(.not-exported)',
                        rows: ':visible'
                    }
                },
                {
                    extend: 'colvis',
                    text: '{{trans("file.Column visibility")}}',
                    columns: ':gt(0)'
                },
            ],
        });


        function printData() {
            var divToPrint = document.getElementById("userTable");
            newWin = window.open("");
            newWin.document.write(divToPrint.outerHTML);
            newWin.print();
            newWin.close();
        }

        $('#take_print').on('click', function () {
            printData();
        })


        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })

        // When the user scrolls down 20px from the top of the document, slide down the navbar
        window.onscroll = function () {
            scrollFunction()
        };

        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                document.getElementById("navbar").style.top = "0";
            } else {
                document.getElementById("navbar").style.top = "-50px";
            }
        }

    </script>
@endsection
