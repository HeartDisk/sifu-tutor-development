@extends('layouts.main')
@section('content')
    <div class="nk-content sifu-view-page">
        <div class="fluid-container">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head">
                        <div class="nk-block-head-between flex-wrap gap g-2">
                            <div class="nk-block-head-content">
                                <h2 class="nk-block-title">
                                    Expense Vouchers List
                                </h2>
                                <nav>
                                    <ol class="breadcrumb breadcrumb-arrow mb-0">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item"><a href="#">Expense Vouchers List</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Expense Vouchers List
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="nk-block">
                        <div class="card">
                            <div class="row" style="margin: 12px;justify-content: end;">
                                <div class="col-2">
                                    <a href="{{url("/expense-voucher")}}">
                                        <button class="btn btn-primary">Create</button>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Date</th>
                                            <th>Voucher Type</th>
                                            <th>Expense</th>
                                            <th>Reference No</th>
                                            <th>Note</th>
                                            <th>Total</th>
                                            <th>Action</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($expense_vouchers as $key=>$expense)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{$expense->created_at}}</td>
                                                <td>{{$expense->type}}</td>
                                                <td>{{$expense->expense_name}}</td>
                                                <td>{{$expense->reference_no}}</td>
                                                <td>{{$expense->note}}</td>
                                                <td>{{$expense->amount}}</td>
                                                <td>

                                                    <a class="dtable-cbtn bt-view dtb-tooltip" dtb-tooltip="View" href="{{url("/expense-voucher-view")."/".$expense->id}}"><i class="fa fa-eye"></i></a>
                                                    <!--<a class="dtable-cbtn bt-edit dtb-tooltip" dtb-tooltip="Edit Invoice" href="https://sifututor.odits.co/new/students/editStudentInvoice/63"><i class="fa fa-edit"></i></a>-->
                                                    <a class="dtable-cbtn bt-delete dtb-tooltip" dtb-tooltip="Delete" onclick="return confirm('Are you sure you want to delete this ?');" href="{{url("/expense-voucher-delete")."/".$expense->id}}"><i class="fa fa-trash"></i></a>

                                                </td>

                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
