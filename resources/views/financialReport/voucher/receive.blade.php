

@extends('layout.main') @section('content')

@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div> 
@endif

    <link href="{{asset('public/src/jquery.inputpicker.css')}}" rel="stylesheet" type="text/css">

    <!-- Bootstrap DatePicker -->
    
    <script src="jquery-1.3.2.min.js" type="text/javascript"></script>
    
    <script type="text/javascript">

        $(function () {
            $('#txtDate').datepicker({
                format: "dd-mm-yyyy"
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
       

    input:focus {
          outline: 5px solid #7CFC00 !important;
        }
    select:focus {
          outline: 5px solid #7CFC00 !important;
        }
    
    
    table.buttonTable, th.buttonTable, td.buttonTable {
        border: 0px !important;
    }
    
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

        /* Style the search field */
        form.example input[type=text] {
          padding: 10px;
          font-size: 17px;
          border: 1px solid grey;
          float: left;
          width: 80%;
          background: #f1f1f1;
        }
        
        /* Style the submit button */
        form.example button {
          float: left;
          width: 20%;
          padding: 10px;
          background: #2196F3;
          color: white;
          font-size: 17px;
          border: 1px solid grey;
          border-left: none; /* Prevent double borders */
          cursor: pointer;
        }
        
        form.example button:hover {
          background: #0b7dda;
        }
        
        /* Clear floats */
        form.example::after {
          content: "";
          clear: both;
          display: table;
        }
     
                .bootstrap-select.btn-group .dropdown-toggle .filter-option{
                    font-size: 18px !important;
                    font-weight: bold !important;
                    color: #000 !important;
                    width: 100%; !important;
                }

                .forms input.form-control{
                    font-size: 18px !important;
                    font-weight: bold !important;
                    color: #000 !important;
                    border:1px solid #000;
                    border-radius:7px 0px !important;
                }
                    table{
                        width: 100%;
                        margin: 20px 0;
                        border-collapse: collapse;
                    }
                    table, th, td{
                        border: 1px solid #cdcdcd;
                        text-align:center !important;
                        color:#000;
                        font-weight:bold;
                        font-size:21px;
                    }
                    table th, table td{
                        padding: 5px;
                        text-align: left;
                    }
                
    </style>
 
<section style="padding:0px;" class="forms">
    <div style="padding-right:50px !important; padding-left:50px !important" class="container-fluid">
        
        <div class="row">

            <div class="col-md-12">
                <div class="card">
            <h1 style="background:orange; padding:10px; text-align:center; color:#fff;"> A D D   &nbsp; &nbsp;  R E C E I V E  &nbsp; &nbsp;  V O U C H E R   </h1>
            
                    <div style="padding:0px !important" class="card-body">
                        {!! Form::open(['url' => 'sales/321/receive_voucher', 'method' => 'post', 'id'=> 'inputform', 'onsubmit'=>'return monitor()', 'files' => true, 'class' => 'payment-form']) !!}
                        <div style="padding:50px; " class="row">
                            <div class="col-md-12">
                                
                                <div style="width:97% !important;" class="row">
                                    <div class="col-md-6">
                                        <label style="color:#000;font-weight:bold;font-size:21px;">Date</label>
                                          <input name="receive_voucher_date" type="date" class="arrow-togglable form-control date-input" value="@php echo date('d-m-Y');@endphp"/>
                                    </div>
                                </div>
                            
                              <div style="width:99% !important; height:424px; overflow:auto;" class="row mt-12">
                                  
                                    <div class="col-md-12">
                                        <div class="table-responsive">
               
                                                <table class="order-list">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th>Party Name</th>
                                                            <th style="width:200px;" >City</th>
                                                            <th style="width:200px;">Previous Balance</th>
                                                            <th style="width:200px;">Amount</th>
                                                            <th style="width:500px;">Note </th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><input style="width:250px !important;"  class="arrow-togglable form-control product-id" name="customer_id[]" id="demo-1"/></td>
                                                            <td><input oninput="myinput(this.id,this.name)" class="arrow-togglable form-control lot1" type="text" id="1" name="lot[]" readonly/></td>
                                                            <td><input class='arrow-togglable form-control packing1' type='text'  name='packing' readonly value=""/></td>
                                                            <td><input  oninput="myinput(this.id,this.alt)" onkeypress="nextinput(this.id,this.alt)" alt="qty" class="arrow-togglable form-control qty1" type="text" id="1" name="qty[]"/></td>
                                                            <td><input  oninput="myinput(this.id,this.alt)" alt="add" class="arrow-togglable form-control add_w1" type="text" id="1" name="note[]" onkeydown="keyAddw(event)"/></td>
                                                            <td><select name="action[]"><option style="color:#fff; background:green;" value="credit">Credit</option><option  style="background:red;" value="debit">Debit</option></select></td>
                                                            <td></td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot style="background:#000" class="tfoot active">
                                                        <tr>
                                                            <th></th>
                                                            <th style="width:145px;"></th>
                                                            <th></th>
                                                            <th style="text-align:left; color:#fff; font-weight:bold; font-size:21px;" id="total_qty"></th>
                                                            <input id="total_qty_two" value="" name="total_qty_two" type="hidden"/>
                                                            <th style="text-align:left; color:#fff; font-weight:bold; font-size:21px;"></th>
                                                            <th style="text-align:left; color:#fff; font-weight:bold; font-size:21px;"></th>
                                                            <th style="text-align:left; color:#fff; font-weight:bold; font-size:21px;"></th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
            
                                        </div>
                                    </div>
                                </div>
                              
                                
                            
                                <div class="row">
                                    <div class="col-md-12">  
                                            <table style="width:100%; background:#e9ecef">
                                                <tr>
                                                    <td>
                                                        <div class="form-group">
                                                            <input class="btn3d btn btn-primary btn-lg" id="submit-button" name="sub" type="submit" value="Save Receive Voucher">
                                                        </div>
                                                        <!-- span id="currentBalance"></span -->
                                                    </td>
                                                </tr>
                                            </table>
                                    <div>

                                </div>
                                
                                
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                
           
            
            </div>
        </div>
    </div>
   
   
</section>

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="{{asset('public/src/jquery.inputpicker.js')}}"></script>
<script type="text/javascript">

        var url = 'http://sadaftraders.com/st/api/products/get_new_products';

            $.ajax( {
                type:'GET',
                header:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                url:url,
            })
            .done(function(data) {
                //var balance = jQuery.parseJSON(data);
                //alert(balance);
                var length = 'New Products ( '+data.length+' )';
                $('#remaining_product').text(length);
            })
            .fail(function() {
               // alert("error");
            });



        var elements = document.getElementsByClassName("arrow-togglable");
        var currentIndex = 0;
    
        function download_product(){
        var url = 'http://sadaftraders.com/st/api/products/get_new_products';
        $.ajax( {
                type:'GET',
                header:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                url:url,
            })
            .done(function(data) {
                //console.log(data);
                            // ARRAYS
                            $.each(data, function(index, value) {
                            //console.log(value);
                            // Will stop running after "three"
                                $.ajax({
                                    type:'GET',
                                        header:{
                                            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                                            },
                                    url:"http://sadaftraders.com/st/products_upload/"+value.name+"/"+value.urdu_name+"/"+value.packing+"/"+value.lot+"/"+value.category_id,
                                    success:function(response){
                                        console.log(response);
                                        //location.href = '../products';
                                    },
                                });
                            });
                        

         

                //console.log(data);
                //var balance = jQuery.parseJSON(data);
                //alert(balance);
                //var length = 'New Products ( '+data.length+' )';
                //$('#remaining_product').text(length);
            })
            .fail(function() {
               // alert("error");
            });


    }
        
        $(function(){
    $(".order-list tbody tr td ").on("click input keypress keydown","button,input,textarea,select",function(e){

           
        if(e.which==37){
            var fields = $(this).parents('form:eq(0),body').find('button,input,textarea,select');
            var index = fields.index(this);
            if (index > -1 && (index + 1) < fields.length) {
    
                fields.eq(index -1).focus();
            }
        }   
        if(e.which==39){
            var fields = $(this).parents('form:eq(0),body').find('button,input,textarea,select');
            var index = fields.index(this);
            if (index > -1 && (index + 1) < fields.length) {
    
                fields.eq(index +1).focus();
            }

        }   
        console.log(fields)
    

    })
})


        //document.onkeydown = function(e) {
          //switch (e.keyCode) {
            //case 37:
              //currentIndex = (currentIndex == 0) ? elements.length - 1 : --currentIndex;
              //elements[currentIndex].focus();
              //break;
            //case 39:
              //currentIndex = ((currentIndex + 1) == elements.length) ? 0 : ++currentIndex;
              //elements[currentIndex].focus();
              //break;
          //}
        //};



    $("ul#voucher").siblings('a').attr('aria-expanded','true');
    $("ul#voucher").addClass("show");
    $("ul#voucher #sale-voucher-menu").addClass("active");

$("#payment").hide();
$(".card-element").hide();
$("#gift-card").hide();
$("#cheque").hide();

// array data depend on warehouse
var lims_product_array = [];
var product_code = [];
var product_name = [];
var product_qty = [];
var product_type = [];
var product_id = [];
var product_list = [];
var qty_list = [];

// array data with selection
var product_price = [];
var product_discount = [];
var tax_rate = [];
var tax_name = [];
var tax_method = [];
var unit_name = [];
var unit_operator = [];
var unit_operation_value = [];
var gift_card_amount = [];
var gift_card_expense = [];
// temporary array
var temp_unit_name = [];
var temp_unit_operator = [];
var temp_unit_operation_value = [];


var rowindex;
var customer_group_rate;
var row_product_price;
var pos;

$('.selectpicker').selectpicker({
    style: 'btn-link',
});

$('[data-toggle="tooltip"]').tooltip();


$('select[name="customer_id"]').on('change', function() {
    var id = 'payment_'+$(this).val();
    $.ajax( {
                type:'GET',
                header:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                url:"http://sadaftraders.com/st/sales/"+id+"/total_balance",
            })
            .done(function(data) {
                //var balance = jQuery.parseJSON(data);
                $('#customerBalance').text(data);
                $('#currentBalance').text(data);
                var currentBalance = data;
            })
            .fail(function() {
               // alert("error");
            });
    //$.get('getcustomergroup/' + id, function(data) {
      //  customer_group_rate = (data / 100);
    //});
});

$('select[name="warehouse_id"]').on('change', function() {
    var id = $(this).val();
    $.get('getproduct/' + id, function(data) {
        lims_product_array = [];
        product_code = data[0];
        product_name = data[1];
        product_qty = data[2];
        product_type = data[3];
        product_id = data[4];
        product_list = data[5];
        qty_list = data[6];
        $.each(product_code, function(index) {
            lims_product_array.push(product_code[index] + ' (' + product_name[index] + ')');
        });
    });
});

$('#lims_productcodeSearch').on('input', function(){
    var customer_id = $('#customer_id').val();
    var warehouse_id = $('#warehouse_id').val();
    temp_data = $('#lims_productcodeSearch').val();
    if(!customer_id){
        $('#lims_productcodeSearch').val(temp_data.substring(0, temp_data.length - 1));
        alert('Please select Customer!');
    }
    else if(!warehouse_id){
        $('#lims_productcodeSearch').val(temp_data.substring(0, temp_data.length - 1));
        alert('Please select Warehouse!');
    }

});

var lims_productcodeSearch = $('#lims_productcodeSearch');

lims_productcodeSearch.autocomplete({
    source: function(request, response) {
        var matcher = new RegExp(".?" + $.ui.autocomplete.escapeRegex(request.term), "i");
        response($.grep(lims_product_array, function(item) {
            return matcher.test(item);
        }));
    },
    response: function(event, ui) {
        if (ui.content.length == 1) {
            var data = ui.content[0].value;
            $(this).autocomplete( "close" );
            productSearch(data);
        };
    },
    select: function(event, ui) {
        var data = ui.item.value;
        productSearch(data);
    }
});




//Change quantity
$("#myTable").on('input', '.qty', function() {
    rowindex = $(this).closest('tr').index();
    checkQuantity($(this).val(), true);
});


//Delete product
$("table.order-list tbody").on("click", ".ibtnDel", function(event) {
    rowindex = $(this).closest('tr').index();
    product_price.splice(rowindex, 1);
    product_discount.splice(rowindex, 1);
    tax_rate.splice(rowindex, 1);
    tax_name.splice(rowindex, 1);
    tax_method.splice(rowindex, 1);
    unit_name.splice(rowindex, 1);
    unit_operator.splice(rowindex, 1);
    unit_operation_value.splice(rowindex, 1);
    $(this).closest("tr").remove();
    calculateTotal();
});

//Edit product
$("table.order-list").on("click", ".edit-product", function() {
    rowindex = $(this).closest('tr').index();
    edit();
});


function calculateTotal() {
    //Sum of quantity
    var total_qty = 0;
    $(".qty").each(function() {
        if ($(this).val() == '') {
            total_qty += 0;
        } else {
            total_qty += parseFloat($(this).val());
        }
    });
    $("#total-qty").html(total_qty);
    $('input[name="total_qty"]').val(total_qty);

    //Sum of discount
    var total_discount = 0;
    $(".discount").each(function() {
        total_discount += parseFloat($(this).html());
    });
    $("#total-discount").html(total_discount.toFixed(2));
    $('input[name="total_discount"]').val(total_discount.toFixed(2));

    //Sum of tax
    var total_tax = 0;
    $(".tax").each(function() {
        total_tax += parseFloat($(this).html());
    });
    $("#total-tax").html(total_tax.toFixed(2));
    $('input[name="total_tax"]').val(total_tax.toFixed(2));

    //Sum of subtotal
    var total = 0;
    $(".sub-total").each(function() {
        total += parseFloat($(this).html());
    });
    $("#total").html(total.toFixed(2));
   
    calculateGrandTotal();
}



$("ul#sale").siblings('a').attr('aria-expanded','true');
$("ul#sale").addClass("show");
$("ul#sale li").eq(2).addClass("active");

function nextinput(index,field){
    
   qty = (".qty"+index).val()

}

function myinput(index,field){
    
    lot         = $(".lot"+index).val()
    packing     = parseInt($(".packing"+index).val())
    qty         = parseInt($(".qty"+index).val())
    t_weight    = $(".t_weight"+index).val()
    hidden_weight    = $(".hidden_weight"+index).val()
    price       = $(".price"+index).val()   
    bag_rate    = $(".bag_rate"+index).val()
    perKgRate   = $(".perKgRate"+index).val()
    less_w      = $(".less_w"+index).val()
    add_w       = $(".add_w"+index).val()
    linetotal   = $(".linetotal"+index).val()

    if(field=="qty")
    {
        var sum = (packing-qty);
        $(".t_weight"+index).text(sum)
        $(".t_weight"+index).val(sum)
        $(".linetotal"+index).val(bag_rate*qty)
        calculateGrandTotal()
        
    }else if(field=="bagRate")
    {
            
        var sum = (parseInt(bag_rate)/packing)
        var perKgRate = (parseInt(bag_rate)/packing)
        $(".price"+index).val(sum)
        $(".perKgRate"+index).val(sum)
        $(".linetotal"+index).val(bag_rate*qty)
        
        calculateGrandTotal()

            if(e.which==37){
                $('.price'+counter).focus();
            }
            
        
    }else if(field=="price")
    {
        $(".perKgRate"+index).val(price)
        $(".bag_rate"+index).val(packing*price)
        $(".linetotal"+index).val(t_weight*price)
        
        calculateGrandTotal()
        
    }else if(field=="less")
    {
        
        var sum_tw = (qty*packing - less_w)
        $(".t_weight"+index).val(sum_tw)
        var perKgRate = $(".perKgRate"+index).val()
        $(".linetotal"+index).val(sum_tw * perKgRate)
        
        calculateGrandTotal()
        

    }else if(field=="add")
    {
        if(less_w == 0){
            
            var sum_aw = (qty*packing + parseInt(add_w))
            $(".t_weight"+index).val(sum_aw)
            var perKgRate = $(".perKgRate"+index).val()
            $(".linetotal"+index).val(sum_aw * perKgRate)
            
            calculateGrandTotal()
        
        }else{

            var sum_aw = (qty*packing + parseInt(add_w) -  parseInt(less_w))
            $(".t_weight"+index).val(sum_aw)
            var perKgRate = $(".perKgRate"+index).val()
            $(".linetotal"+index).val(sum_aw * perKgRate)
            
            calculateGrandTotal()
        }
        
        
        
    }
}

var counter = 1;
function keyAddw(e){
    if(e.which==13){
        counter++;
        addRow(counter)
        $('#urdu_name'+counter).focus();
    }
}

$(document).ready(function () {
   

    $("table.order-list").on("click", "a.deleteRow", function (event) {
        $(this).closest("tr").remove();
        calculateGrandTotal();
    });

   
});

function addRow(counter){

    var newRow = $("<tr>");
    var cols = "";
    cols += '<td><input  style="width:250px !important;"  class="arrow-togglable form-control product-id" name="customer_id[]" id="demo-'+ counter +'"/></td>';
    cols += '<td><input oninput="myinput(this.id,this.name)" readonly class="arrow-togglable form-control lot' + counter + '" type="text" id="' + counter + '" name="lot[]"/></td>';
    cols += '<td><input oninput="myinput(this.id,this.name)" readonly class="arrow-togglable form-control packing' + counter + '" type="text" id="' + counter + '" name="packing[]"/></td>';
    cols += '<td><input  oninput="myinput(this.id,this.alt)" alt="qty" class="arrow-togglable form-control qty' + counter + '" type="number" id="'+ counter +'" name="qty[]"/></td>';
    
    cols += '<td><input  oninput="myinput(this.id,this.alt)" alt="add" class="arrow-togglable form-control add_w' + counter + '" onkeydown="keyAddw(event)" type="text" id="' + counter + '" name="note[]"/></td>';
    cols += '<td><select name="action[]"><option style="color:#fff; background:green;" value="credit">Credit</option><option  style="background:red;" value="debit">Debit</option></select></td>';
    cols += '<td><a style="color:#fff; font-weight:bold;" class="btn btn-danger deleteRow form-control"> X </a></td>';
    newRow.append(cols);
    $("table.order-list").append(newRow);
    
          

    $('#demo-'+counter).inputpicker({
       data:[ @foreach($lims_products_list as $product)
                        {value:"{{$product->id}}",text:"{{$product->name}}",urdu:"{{$product->city}}"},
                     @endforeach
                ],
                fields:[
                     {name:'value',text:'ID'},
                     {name:'text',text:'Name'},
                     {name:'urdu',text:'City'}
                 ],
                headShow: true,
                fieldText : 'text',
                fieldValue: 'value',
                filterOpen: true
                
                
        });
    
    
    
       $('#demo-'+counter).change(function(){
        
            $('.packing'+counter).focus();
            var id = $(this).val();
            $.ajax( {
                type:'GET',
                header:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                url:"http://sadaftraders.com/st/sales/"+id+"/customer/balance",
                
            })
            .done(function(data) {
                var product = jQuery.parseJSON(data);
                $('#price'+counter).val(product.price);
                $('#urdu_name'+counter).val(product.urdu_name);
                $('.lot'+counter).val(product.city);
                $('.packing'+counter).val(product.balance);
                $('.qty'+counter).val();
                $('#discount'+counter).val();
                $('#sub_total'+counter).val(product.price);
                $('#product_id'+counter).val(product.id);
                $('#code'+counter).val(product.code);
                $('#sale_unit'+counter).val(product.sale_unit);
                sessionStorage.setItem("product_id", product.id);
            })
            .fail(function() {
                alert("error");
            });
        }); 
    


}



function calculateGrandTotal() {

    var total_t_weight = 0;
    $("table.order-list").find('input[name^="t_weight"]').each(function () {
        total_t_weight += +$(this).val();
        $("#total_t_weight").text(total_t_weight);
        $("#total_t_weight_two").val(total_t_weight);
    });

    var total_qty = 0;
    $("table.order-list").find('input[name^="qty"]').each(function () {
        total_qty += +$(this).val();
        $("#total_qty").text(total_qty);
        $("#total_qty_two").val(total_qty);
    });

    
    var total_less_w = 0;
    $("table.order-list").find('input[name^="less_w"]').each(function () {
        total_less_w += +$(this).val();
        $("#total_less_w").text(total_less_w);
    });

    var total_add_w = 0;
    $("table.order-list").find('input[name^="add_w"]').each(function () {
        total_add_w += +$(this).val();
        var sum_tt = total_t_weight + total_add_w;
        $("#total_add_w").text(total_add_w);
   });

    
    
    var grandTotal = 0;
    $("table.order-list").find('input[name^="qty"]').each(function () {
        grandTotal += +$(this).val();
    });
    $("#grandtotal").text(grandTotal.toFixed(0));
    $("#total_inv").val(grandTotal.toFixed(0));
    $("#grandtotal_two").text(grandTotal.toFixed(0));
    $("#total_qty").text(grandTotal.toFixed(0));
    $("#grandtotal_two_val").val(grandTotal.toFixed(0));
    
    
    
    
    

}


        
$(document).ready(function(){
        $("#demo-1").change(function(){
            $('.packing1').focus();
            var id = $(this).val();
            $.ajax( {
                type:'GET',
                header:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                url:"http://sadaftraders.com/st/sales/"+id+"/customer/balance",
            })
            .done(function(data) {
                var product = jQuery.parseJSON(data);
                $('#price1').val(product.price);
                $('#urdu_name1').val(product.urdu_name);
                $('.lot1').val(product.city);
                $('.packing1').val(product.balance);
                $('.qty1').val();
                $('#discount1').val();
                $('#sub_total1').val(product.price);
                $('#product_id1').val(product.id);
                $('#code1').val(product.code);
                $('#sale_unit1').val(product.sale_unit);
                sessionStorage.setItem("product_id", product.id);
            })
            .fail(function() {
                alert("error");
            });
        });

        $(".add-row").click(function(){
            var markup = "<tr><td><select class='selectpickerOne form-control' data-live-search='true' data-live-search-style='begins' title='Select Product...'><option> Product 1 </option> <option> Product 2 </option> <option> Product 3 </option> <option> Product 4 </option></select></td><td><input  class='form-control' type='text' id='code' name='code'/></td><td><input  class='form-control' type='text' id='price' name='price'/></td><td><select class='selectpickerOne form-control' data-live-search='true' data-live-search-style='begins' title='Select Warehouse...'> <option> Main Shop </option> <option> Hirabad </option> </select></td><td><input class='form-control' type='text' id='warehouseQuantity' name='warehouseQuantity'/></td><td><input  class='form-control'  type='text' id='quantity' name='quantity'/></td></tr>";
            $("table tbody").append(markup);
        });
        
        // Find and remove selected table rows
        $(".delete-row").click(function(){
            $("table tbody").find('input[name="record"]').each(function(){
                if($(this).is(":checked")){
                    $(this).parents("tr").remove();
                }
            });
        });

            $('#add_lab').on("change",function () {
                var add_lab = $('#add_lab').val();
                var total_inv = $('#total_inv').val();
                add_lab_2 = parseInt(total_inv) + parseInt(add_lab);
                $('#grandtotal_two_val').val(add_lab_2);
                $('#grandtotal_two').text(add_lab_2);
            });
            
            $('#add_fare').on("change",function () {
                var add_lab = $('#add_lab').val();
                var add_fare = $('#add_fare').val();
               
                    var total_inv = $('#total_inv').val();
                    add_fare_2 = parseInt(total_inv) + parseInt(add_fare) + parseInt(add_lab);
                    $('#grandtotal_two_val').val(add_fare_2);
                    $('#grandtotal_two').text(add_fare_2);
               
            });

            $('#add_others').on("change",function () {
                
                var add_lab = $('#add_lab').val();
                var add_fare = $('#add_fare').val();
                var add_others = $('#add_others').val();
                    var total_inv = $('#total_inv').val();
                    add_others_2 = parseInt(total_inv) + parseInt(add_others)+ parseInt(add_fare) + parseInt(add_lab);
                    $('#grandtotal_two_val').val(add_others_2);
                    $('#grandtotal_two').text(add_others_2);
            });

            $('#less_lab').on("change",function () {
                var less_lab = $('#less_lab').val();
                
                var add_lab = $('#add_lab').val();
                var add_fare = $('#add_fare').val();
                var add_others = $('#add_others').val();
                    var total_inv = $('#total_inv').val();
                    add_less_2 = parseInt(total_inv) + parseInt(add_others)+ parseInt(add_fare) + parseInt(add_lab) - parseInt(less_lab);
                    $('#grandtotal_two_val').val(add_less_2);
                    $('#grandtotal_two').text(add_less_2);
                    
            });

            $('#less_fare').on("change",function (event) {
                var less_fare = $('#less_fare').val();
                 var less_lab = $('#less_lab').val();
                var add_lab = $('#add_lab').val();
                var add_fare = $('#add_fare').val();
                var add_others = $('#add_others').val();
                    var total_inv = $('#total_inv').val();
                    add_less_2 = parseInt(total_inv) + parseInt(add_others)+ parseInt(add_fare) + parseInt(add_lab) - parseInt(less_lab)  - parseInt(less_fare);
                    $('#grandtotal_two_val').val(add_less_2);
                    $('#grandtotal_two').text(add_less_2);
                    
            });

            $('#less_others').on("change",function (event) {
                var less_others = $('#less_others').val();
                var less_fare = $('#less_fare').val();
                 var less_lab = $('#less_lab').val();
                
                var add_lab = $('#add_lab').val();
                var add_fare = $('#add_fare').val();
                var add_others = $('#add_others').val();
                    var total_inv = $('#total_inv').val();
                    add_less_2 = parseInt(total_inv) + parseInt(add_others)+ parseInt(add_fare) + parseInt(add_lab) - parseInt(less_lab) - parseInt(less_others) - parseInt(less_fare);
                    $('#grandtotal_two_val').val(add_less_2);
                    $('#grandtotal_two').text(add_less_2);
                    
            });

    });    



function test(id){
    var option_id = $("#demo-"+id).val();
    $.ajax( {
        type:'GET',
        header:{
        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        url:"http://sadaftraders.com/st/sales/"+option_id,
    })
    .done(function(data) {
        var product = jQuery.parseJSON(data);
        $('.price'+id).val(product.price);
        $('#urdu_name'+id).val(product.urdu_name);
        $('.lot'+id).val(product.lot);
        $('.packing'+id).val(product.balance);
        $('.qty'+id).val();
        $('.discount'+id).val();
        $('.sub_total'+id).val(product.price);
        $('.product_id'+id).val(product.id);
        $('.code'+id).val(product.code);
        $('.sale_unit'+id).val(product.sale_unit);
        sessionStorage.setItem("product_id", product.id);
    })
    .fail(function() {
        alert("error");
    });
    

}

            $('#demo-1').inputpicker({
                data:[ @foreach($lims_products_list as $product)
                        {value:"{{$product->id}}",text:"{{$product->name}}",urdu:"{{$product->city}}"},
                     @endforeach
                ],
                fields:[
                     {name:'value',text:'Id'},
                     {name:'text',text:'Name'},
                     {name:'urdu',text:'City'}
                     
                 ],
                headShow: true,
                fieldText : 'text',
                fieldValue: 'value',
                filterOpen: true
                
             });
             


$('#inputform').keydown(function(event) {
  if (event.ctrlKey && event.keyCode === 13) {
    $(this).trigger('submit');
  }
})

jQuery.extend(jQuery.expr[':'], {
    focusable: function (el, index, selector) {
        return $(el).is('a, button, .selectpicker, :input, [tabindex]');local
    }
});

$(document).on('keypress keydown', 'input,select', function (e) {
    if (e.which == 13) {
        e.preventDefault();
        // Get all focusable elements on the page
        var $canfocus = $(':focusable');
        var index = $canfocus.index(document.activeElement) + 1;
        if (index >= $canfocus.length) index = 0;
        $canfocus.eq(index).focus();
    }
    

    if(e.which==37){
        console.log("hello")
        var fields = $(this).parents('form:eq(0),body').find('button,input,textarea,select');
        var index = fields.index(this);
        if (index > -1 && (index + 1) < fields.length) {

            fields.eq(index -1).focus();
        }
    }   
    if(e.which==39){
        var fields = $(this).parents('form:eq(0),body').find('button,input,textarea,select');
        var index = fields.index(this);
        if (index > -1 && (index + 1) < fields.length) {

            fields.eq(index +1).focus();
        }

    }   

    
    // if (e.which == 39) {
    //     e.preventDefault();
    //     // Get all focusable elements on the page 
    //     var $canfocus = $(':focusable');
    //     var index = $canfocus.index(document.activeElement) + 1;
    //     if (index >= $canfocus.length) index = 0;
    //     $canfocus.eq(index).focus();
    // }
    
    // if (e.which == 37) {
    //     e.preventDefault();
    //     // Get all focusable elements on the page
    //     var $canfocus = $(':focusable');
    //     var index = $canfocus.index(document.activeElement) - 1;
    //     if (index >= $canfocus.length) index = 0;
    //     $canfocus.eq(index).focus();
    // }
  
});

function haz(elem){
    $('input#bill_no').focus();
}


  var elements = document.getElementsByClassName("arrow-togglable");
    var currentIndex = 0;

    
var txt="";
$(function(){

txt =setInterval(function(){
    $('#bill_no').focus();
    resr()
},1000)

})

function resr(){
    clearInterval(txt)

}
</script>
@endsection @section('scripts')


      
@endsection