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
                                    Bank Vouchers List
                                </h2>
                                <nav>
                                    <ol class="breadcrumb breadcrumb-arrow mb-0">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item"><a href="#">Bank Vouchers Details</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Bank Vouchers Details
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="nk-block">
                        <div class="card">
                            <div class="row g-3">

                               <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="firstname" class="form-label">Date: {{$customer_receive_voucher->created_at}}</label>
                                        <div class="form-control-wrap">

                                        </div>
                                    </div>
                                </div>
                                
                                
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="firstname" class="form-label">Voucher Type: {{ucfirst($customer_receive_voucher->type)}}</label>

                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="firstname" class="form-label">Bank: {{$customer_receive_voucher->bank}} </label>
                                    </div>
                                </div>

                             

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="firstname" class="form-label">Reference Number: {{$customer_receive_voucher->reference_no}}</label>

                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="firstname" class="form-label">Remarks: {{$customer_receive_voucher->note}}</label>

                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="nk-block-head">
                                        <div class="nk-block-head-between flex-wrap">
                                            <div class="nk-block-head-content">
                                                <h3 class="nk-block-title">ITEMS</h3>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Description</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody id="tbody">

                                            @foreach($voucher_items as $item)
                                                <tr>
                                                    <td>{{$item->description}}</td>
                                                    <td>{{$item->quantity}}</td>
                                                    <td>{{$item->price}}</td>
                                                </tr>
                                            @endforeach

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td></td>
                                                <td>Total</td>
                                                <td>{{$customer_receive_voucher->amount}}</td>
                                            </tr>
                                            </tfoot>
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
