@extends('layout.main') @section('content')

<div class="fluid-container">
<div class="rows">
    
    <br/><br/><br/>

    <div class="table-responsive">
        <center><h1 style="background:#090127; padding:15px; color:#fff"> Customers Voucher List </h1></center>
        <table id="customer-table" class="table table-striped">
              <thead class="table-dark">
                  
                <tr>
                  <th style="font-size:19px;"scope="col">#</th>
                  <th style="font-size:19px;"scope="col">Date</th>
                  <th style="font-size:19px;"scope="col">Customer Detail</th>
                  <th style="font-size:19px;"scope="col">Amount</th>
                  <th style="font-size:19px;"scope="col">Debit / Credit</th>
                  <th style="font-size:19px;"scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                  @foreach($customer_receive_voucher as $row)
                  @php
                        $customers = DB::table('customers')
                            ->where('id', '=', $row->customer_id)
                            ->get();

                  @endphp
                        <tr>
                          <th style="font-size:19px;" scope="row">{{$row->id}}</th>
                          <th style="font-size:19px;" scope="row">
                              @php
                                    $newDate = date("d-m-Y", strtotime($row->receive_voucher_date));  
                                @endphp
                                {{$newDate}}
                              </th>
                          <th style="font-size:19px;" scope="row">@if(!$customers->isEmpty()) {{$customers[0]->company_name}} <br/> {{$customers[0]->name}} <span style="font-size:14px !important;"> {{$customers[0]->city}} </span> @endif</th>
                          <th style="font-size:19px;" scope="row">{{$row->amount}}</th>
                          <th style="font-size:19px;" scope="row">
                                @if($row->action == 'debit')
                                    Payments
                                @else
                                    Receivings
                                @endif
                                
                          </th>
                          <th style="font-size:19px;" scope="row"> <a target="_blank" href="{{url('customer-receiving-voucher-edit', $row->id)}}"> Edit {{$row->id}}</a>| Delete</th>
                        </tr>
                    @endforeach
            
              </tbody>
            </table>
    </div>
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
                cols += '<td><div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{trans("file.action")}}<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button><ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu"><li><button type="button" class="btn btn-link edit-btn" data-id="' + data[0][index] +'" data-toggle="modal" data-target="#edit-deposit"><i class="fa fa-edit"></i> {{trans("file.edit")}}</button></li><li class="divider"></li><li><input type="hidden" name="id" value="' + data[0][index] + '" /> <button type="submit" class="btn btn-link" onclick="return confirmDelete()"><i class="fa fa-trash"></i> {{trans("file.delete")}}</button></li></ul></div></td>'
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