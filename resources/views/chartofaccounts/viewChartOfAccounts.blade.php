@extends('layouts.main')

@section('content')
<div class="nk-content">
    <div class="fluid-container">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head">
                    <div class="nk-block-head">
                        <div class="nk-block-head-between flex-wrap gap g-2 align-items-center">
                            <div class="nk-block-head-content">
                                <div class="d-flex flex-column flex-md-row align-items-md-center">
                                    <div class="mt-3 mt-md-0 ms-md-3">
                                        <h3 class="title mb-1">View Chart Of Accounts</h3>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="nk-block">
                    <div class="card card-gutter-md">
                        <div class="card-body">
                            @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <ul>
                                    <li>{!! \Session::get('success') !!}</li>
                                </ul>
                            </div>
                            @endif

                            @if (\Session::has('update'))
                            <div class="alert alert-primary">
                                <ul>
                                    <li>{!! \Session::get('update') !!}</li>
                                </ul>
                            </div>
                            @endif
                            <div class="bio-block">
                                <div class="row g-3">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="firstname" class="form-label">Account ID</label>
                                            <div class="form-control-wrap">{{$chartofaccounts->uid}}</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="firstname" class="form-label">Account Code</label>
                                            <div class="form-control-wrap">{{$chartofaccounts->code}}</div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="firstname" class="form-label">Account Name</label>
                                            <div class="form-control-wrap">{{$chartofaccounts->name}}</div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="country" class="form-label">Account Type</label>
                                            <div class="form-control-wrap">{{$chartofaccounts->type}}</div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4" style="margin-top:40px;">
                                        <div class="form-group" style="display:flex;">
                                            <label for="firstname" class="form-label"
                                                style="width:50%;">Is a Cash Source</label>
                                            <div class="form-control-wrap">@if($chartofaccounts->is_cash_source ==
                                                1) Yes @else No @endif</div>
                                        </div>
                                    </div>

                                    <div class="col-lg-8">
                                        <div class="form-group">
                                            <label for="firstname" class="form-label">Description</label>
                                            <div class="form-control-wrap">{{$chartofaccounts->description}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
