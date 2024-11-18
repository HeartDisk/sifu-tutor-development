@extends('layout.main') @section('content')

<style>
.form-control{
    font-size:19px !important; font-weight:bold !important;
}
    .main{
 	padding: 40px 0;
}
.main input,
.main input::-webkit-input-placeholder {
    font-size: 11px;
    padding-top: 3px;
}
.main-center{
 	margin-top: 30px;
 	margin: 0 auto;
 	max-width: 600px;
    padding: 10px 40px;
	background:#009edf;
	    color: #000;
    text-shadow: none;
	-webkit-box-shadow: 0px 3px 5px 0px rgba(0,0,0,0.31);
-moz-box-shadow: 0px 3px 5px 0px rgba(0,0,0,0.31);
box-shadow: 0px 3px 5px 0px rgba(0,0,0,0.31);

}
span.input-group-addon i {
    color: #009edf;
    font-size: 17px;
}

</style>
<div class="rows">
    <div class="fluid-container">
        
    @php
        $customers = DB::table('suppliers')
            ->where('id', '=', $supplier_receive_voucher[0]->supplier_id)
            ->get();
        $all_customers = DB::table('suppliers')->get();
    @endphp
                  
    <center><h1 style="background:green; padding:15px; color:#fff"> ( {{$customers[0]->company_name}} ) -   {{$customers[0]->name}} Voucher Edit </h1></center>            
        
        <table style="box-shadow:0px 0px 20px black; border-radius:20px; background:#fff; width:100%">
            <tr>
                <td>
                        <table style="margin:0px auto; width:60%; background:#D5D5D5;">
                            <thead class="table-dark">
                                <td style="font-weight:bold; font-size:19px;">
                                    Date
                                </th>
                                <td style="font-weight:bold; font-size:19px;">
                                    Supplier Name
                                </th>
                                <td style="font-weight:bold; font-size:19px;">
                                    Amount
                                </th>
                                <td style="font-weight:bold; font-size:19px;">
                                    Dr / Cr
                                </th>
                            </thead>
                            <tr>
                                <td style="font-size:19px;">
                                    @php
                                        $newDate = date("d-m-Y", strtotime($supplier_receive_voucher[0]->receive_voucher_date));
                                        $newDate_two = date("Y-m-d", strtotime($supplier_receive_voucher[0]->receive_voucher_date));  
                                    @endphp
                                    {{$newDate}}
                                </td>
                                <td style="font-size:19px;">
                                    ( {{$customers[0]->company_name}} ) <br/> {{$customers[0]->name}} <br/> {{$customers[0]->city}}
                                </td>
                                <td style="font-size:19px;">
                                    {{$supplier_receive_voucher[0]->amount}}
                                </td>
                                <td style="font-size:19px;">
                                    {{$supplier_receive_voucher[0]->action}}
                                </td>
                            </tr>
                        </table>                        
                </td>
            </tr>
            <tr>
                <td>
        			<div class="main">
        				<div class="main-center">
        					<form class="" method="post" action="{{url('supplier-receiving-voucher-edit-post')}}">
        						@csrf
        						<input type="hidden" class="form-control" value="{{$supplier_receive_voucher[0]->id}}" name="id"/>
        						<div class="form-group">
        							<label for="name">Date</label>
        								<div class="input-group">
        									<span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
        				                    <input type="date" class="form-control" value="{{$newDate_two}}" name="date"/>
        							</div>
        						</div>
        
        						<div class="form-group">
        							<label for="email">Supplier Name</label>
        								<div class="input-group">
        									<span class="input-group-addon"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>
        									<select class="form-control selectpicker" id="account_id" name="account_id" data-live-search="true" data-live-search-style="begins" title="Select Supplier...">
                                                @php $customers = DB::table('suppliers')->where('is_active', true)->get(); @endphp
                                                @foreach($customers as $account)
                                                    <option style="font-weight:bold; font-size:21px;" value="{{$account->id}}">{{$account->name}} <span style="color:red !mportant">({{$account->city}})</span> ({{$account->id}})</option>
                                                @endforeach
                                            </select>
                                                                    
        							</div>
        						</div>
        
        						<div class="form-group">
        							<label for="username">Amount</label>
        								<div class="input-group">
        									<span class="input-group-addon"><i class="fa fa-users fa" aria-hidden="true"></i></span>
        									<input type="text" class="form-control" name="amount" value="{{$supplier_receive_voucher[0]->amount}}"/>
        								</div>
        						</div>
        
        						<div class="form-group">
        							<label for="username">Status</label>
        								<div class="input-group">
        									<span class="input-group-addon"><i class="fa fa-users fa" aria-hidden="true"></i></span>
        									<select name="action" class="form-control">
        									    <option selected value="{{$supplier_receive_voucher[0]->action}}"> {{$supplier_receive_voucher[0]->action}} </option>
        									        <option value="credit"> Credit </option>
        									        <option value="debit"> Debit </option>
        									</select>
        								</div>
        						</div>
        
        				            <button class="btn btn-success form-control" type="submit">Edit Voucher</button>
        						
        					</form>
        				</div><!--main-center"-->
        			</div><!--main-->
                </td>
            </tr>
        </table>
                
    </div>
</div>

<script type="text/javascript">
    $("ul#people").siblings('a').attr('aria-expanded','true');
    $("ul#people").addClass("show");
    $("ul#people #customer-list-menu").addClass("active");

    function confirmDelete() {
      if (confirm("Are you sure want to delete?")) {
          return true;
      }
      return false;
    }

    var customer_id = [];
    var user_verified = <?php echo json_encode(env('USER_VERIFIED')) ?>;
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

  $(".deposit").on("click", function() {
        var id = $(this).data('id').toString();
        $("#depositModal input[name='customer_id']").val(id);
  });

  $(".getDeposit").on("click", function() {
        var id = $(this).data('id').toString();
        $.get('customer/getDeposit/' + id, function(data) {
            $(".deposit-list tbody").remove();
            var newBody = $("<tbody>");
            $.each(data[0], function(index){
                var newRow = $("<tr>");
                var cols = '';

                cols += '<td>' + data[1][index] + '</td>';
                cols += '<td>' + data[2][index] + '</td>';
                if(data[3][index])
                    cols += '<td>' + data[3][index] + '</td>';
                else
                    cols += '<td>N/A</td>';
                cols += '<td>' + data[4][index] + '<br>' + data[5][index] + '</td>';
                cols += '<td><div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{trans("file.action")}}<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button><ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu"><li><button type="button" class="btn btn-link edit-btn" data-id="' + data[0][index] +'" data-toggle="modal" data-target="#edit-deposit"><i class="fa fa-edit"></i> {{trans("file.edit")}}</button></li><li class="divider"></li>{{ Form::open(['route' => 'customer.deleteDeposit', 'method' => 'post'] ) }}<li><input type="hidden" name="id" value="' + data[0][index] + '" /> <button type="submit" class="btn btn-link" onclick="return confirmDelete()"><i class="fa fa-trash"></i> {{trans("file.delete")}}</button></li>{{ Form::close() }}</ul></div></td>'
                newRow.append(cols);
                newBody.append(newRow);
                $("table.deposit-list").append(newBody);
            });
            $("#view-deposit").modal('show');
        });
  });

  $("table.deposit-list").on("click", ".edit-btn", function(event) {
        var id = $(this).data('id');
        var rowindex = $(this).closest('tr').index();
        var amount = $('table.deposit-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(2)').text();
        var note = $('table.deposit-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(3)').text();
        if(note == 'N/A')
            note = '';
        
        $('#edit-deposit input[name="deposit_id"]').val(id);
        $('#edit-deposit input[name="amount"]').val(amount);
        $('#edit-deposit textarea[name="note"]').val(note);
        $('#view-deposit').modal('hide');
    });

  $('#customer-table').DataTable( {
        "order": [],
        'language': {
            'lengthMenu': '_MENU_ {{trans("file.records per page")}}',
             "info":      '{{trans("file.Showing")}} _START_ - _END_ (_TOTAL_)',
            "search":  '{{trans("file.Search")}}',
            'paginate': {
                    'previous': '{{trans("file.Previous")}}',
                    'next': '{{trans("file.Next")}}'
            }
        },
        'columnDefs': [
            {
                "orderable": false,
                'targets': [0, 2]
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
            },
            {
                extend: 'csv',
                text: '{{trans("file.CSV")}}',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
            },
            {
                extend: 'print',
                text: '{{trans("file.Print")}}',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
            },
            {
                text: '{{trans("file.delete")}}',
                className: 'buttons-delete',
                action: function ( e, dt, node, config ) {
                    if(user_verified == '1') {
                        customer_id.length = 0;
                        $(':checkbox:checked').each(function(i){
                            if(i){
                                customer_id[i-1] = $(this).closest('tr').data('id');
                            }
                        });
                        if(customer_id.length && confirm("Are you sure want to delete?")) {
                            $.ajax({
                                type:'POST',
                                url:'customer/deletebyselection',
                                data:{
                                    customerIdArray: customer_id
                                },
                                success:function(data){
                                    alert(data);
                                }
                            });
                            dt.rows({ page: 'current', selected: true }).remove().draw(false);
                        }
                        else if(!customer_id.length)
                            alert('No customer is selected!');
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
    } );

  $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

  if(all_permission.indexOf("customers-delete") == -1)
        $('.buttons-delete').addClass('d-none');

    $("#export").on("click", function(e){
        e.preventDefault();
        var customer = [];
        $(':checkbox:checked').each(function(i){
          customer[i] = $(this).val();
        });
        $.ajax({
           type:'POST',
           url:'/exportcustomer',
           data:{
                customerArray: customer
            },
           success:function(data){
             alert('Exported to CSV file successfully! Click Ok to download file');
             window.location.href = data;
           }
        });
    });
</script>


@endsection