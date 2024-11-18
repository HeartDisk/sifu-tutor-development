@extends('layout.main') @section('content')

    <script type="text/javascript">
        $(function () {
            $('#txtDate').datepicker({
                format: "dd/mm/yyyy"
            });
        });
    </script>
    
    
<div class="row">
    <div class="col-md-3 modal-body">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="exampleModalLabel" class="modal-title">Receive Voucher</h5>
                    </div>
                    <div class="modal-body">
                        {!! Form::open(['route' => 'receive-voucher.store', 'method' => 'post']) !!}
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label><strong> Date </strong></label>
                                <input  id="txtDate" name="date" type="text" class="arrow-togglable form-control date-input" value="{{$latest_payment->date}}" readonly="readonly" />
                            </div>
                            <div class="col-md-12 form-group">
                                <label><strong> User </strong></label>
                                <select class="form-control selectpicker" name="user_id">
                                @foreach($lims_user_list as $user)
                                    @if($user->id == Auth::id())
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endif
                                @endforeach
                                </select>
                            </div>

                            <div class="col-md-12 form-group">
                                <label><strong> {{trans('file.Account')}}</strong></label>
                                <select class="form-control selectpicker" name="account_id">
                                @foreach($lims_account_list as $account)
                                    @if($account->name == 'Cash Sale')
                                        <option value="{{$account->id}}">{{$account->name}} [{{$account->account_no}}]</option>
                                    @endif
                                @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 form-group">
                                <label><strong> {{trans('file.Type')}}</strong></label>
                                <select class="form-control" name="type">
                                    <option value="d">{{trans('file.Debit')}}</option>
                                    <option selected value="c">{{trans('file.Credit')}}</option>
                                </select>
                            </div>
                            
                            <div class="col-md-12 form-group">
                                <label><strong> amount </strong></label>
                                <input type="text" name="amount" value="" class="form-control"/>
                            </div>
                            
                            
                            <div class="col-md-12 form-group">
                                <label><strong>Note:</strong></label>
                                <textarea class="form-control"> </textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" name="received-voucher" class="btn btn-primary">{{trans('file.submit')}}</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
    </div>

    <div class="col-md-9 modal-body">
    <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="exampleModalLabel" class="modal-title">Receive Voucher List</h5>
                    </div>
                    <div class="modal-body">
                        
                    <table id="account-table" class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Date</th>
                                <th>Bill Number</th>
                                <th>User ID</th>
                                <th>Account ID</th>
                                <th>Sale ID</th>
                                <th>{{trans('file.Credit')}}</th>
                                <th>{{trans('file.Debit')}}</th>
                                <th>User Balance</th>
                            </tr>
                        </thead>
                        <tbody>


                        @foreach($receive_voucher_payment_list as $key=>$rvpl)
                            
                            <tr>
                                <td>{{$rvpl->date}}</td>
                                <td>{{$rvpl->bill_no}}</td>
                                <td>
                                    
                                    @php
                                        $userName = DB::table('users')
                                        ->where('id', '=', $rvpl->user_id)
                                        ->get();
                                        echo $userName[0]->name;
                                    @endphp
                                    
                                </td>
                                <td>
                                    
                                    @php
                                        $account = DB::table('accounts')
                                        ->where('id', '=', $rvpl->account_id)
                                        ->get();
                                        
                                        echo $account[0]->name;
                                        
                                    @endphp
                                </td>
                                <td>
                                    <a target="_blank" href="{{url('sales/'.$rvpl->sale_id.'/invoice')}}">
                                        {{$rvpl->sale_id}}
                                    </a>
                                    
                                </td>
                                @if($rvpl->type == "c")
                                    <td>{{number_format((float)$rvpl->credit, 2, '.', '')}}</td>
                                    <td>0.00</td>
                                    <td>{{number_format((float)$rvpl->balance, 2, '.', '')}}</td>
                                @else
                                    <td>0.00</td>
                                    <td>{{number_format((float)$rvpl->debit, 2, '.', '')}}</td>
                                    
                                    <td>{{number_format((float)$rvpl->balance, 2, '.', '')}}</td>
                                @endif
                            </tr>
                        @endforeach


                            


                            

                        </tbody>
                    </table>
                        
                    </div>
                </div>
    </div>


</div>

<script type="text/javascript">
    $("ul#account").siblings('a').attr('aria-expanded','true');
    $("ul#account").addClass("show");
    $("ul#account #account-statement-menu").addClass("active");

    $('#account-table').DataTable( {
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
                'targets': 0
            },
            
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
    } );

</script>
@endsection