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
                                    Tutor Payment Journal
                                </h2>
                                <nav>
                                    <ol class="breadcrumb breadcrumb-arrow mb-0">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item"><a href="#">Tutors</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Tutor Payment Journal
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="nk-block">
                        <div class="card overflow-hidden">
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
                            <form action="{{route('TutorPaymentJournal')}}" method="GET">
                                @csrf
                                <input type="hidden" name="tutorSearchValue" value="1"/>
                                <div class="row justify-content-between tableper-row">
                                    <div class="col-md-3">
                                        <div class="input-group  input-group-md">
                                            <label class="input-group-text" for="inputGroupSelect01">Months</label>
                                            <select name="month" class="form-control" id="inputGroupSelect01">

                                                <option {{ $currentMonthNumber==13?"selected":""}} value="13">All
                                                </option>
                                                <option {{ $currentMonthNumber==1?"selected":""}} value="1">January
                                                </option>
                                                <option {{ $currentMonthNumber==2?"selected":""}}  value="2">February
                                                </option>
                                                <option {{ $currentMonthNumber==3?"selected":""}}  value="3">May
                                                </option>
                                                <option {{ $currentMonthNumber==4?"selected":""}}  value="4">April
                                                </option>
                                                <option {{ $currentMonthNumber==5?"selected":""}}  value="5">May
                                                </option>
                                                <option {{ $currentMonthNumber==6?"selected":""}}  value="6">June
                                                </option>
                                                <option {{ $currentMonthNumber==7?"selected":""}}  value="7">July
                                                </option>
                                                <option {{ $currentMonthNumber==8?"selected":""}}  value="8">August
                                                </option>
                                                <option {{ $currentMonthNumber==9?"selected":""}}  value="9">September
                                                </option>
                                                <option {{ $currentMonthNumber==10?"selected":""}}  value="10">October
                                                </option>
                                                <option {{ $currentMonthNumber==11?"selected":""}}  value="11">November
                                                </option>
                                                <option {{ $currentMonthNumber==12?"selected":""}}  value="12">December
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group  input-group-md">
                                            <label class="input-group-text" for="validation_status">Validation Status</label>
                                            <select name="validation_status" id="validation_status" class="form-control">
                                                <option value="all">All</option>
                                                <option value="fully_verified" {{ request('validation_status') == 'fully_verified' ? 'selected' : '' }}>Fully Verified</option>
                                                <option value="partial_verified" {{ request('validation_status') == 'partial_verified' ? 'selected' : '' }}>Partial Verified</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group  input-group-md">
                                            <label class="input-group-text" for="processing_status">Processing Status</label>
                                            <select name="processing_status" id="processing_status" class="form-control">
                                                <option value="all">All</option>
                                                <option value="ready" {{ request('processing_status') == 'ready' ? 'selected' : '' }}>Ready</option>
                                                <option value="pending" {{ request('processing_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="processing" {{ request('processing_status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group input-group-md">
                                            <label class="input-group-text" for="search">Tutor Name</label>
                                            <input name="search" type="text" class="form-control" value="{{ request('search') }}" placeholder="Tutor Name">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group input-group-md">
                                            <input type="submit" class="btn btn-primary"
                                                   aria-label="Sizing example input" value="Search"
                                                   aria-describedby="inputGroup-sizing-sm">
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <table class="datatable-init table" data-nk-container="table-responsive">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tutor ID</th>
                                    <th>Tutor name</th>
                                    <th>Month</th>
                                    <th>Year</th>
                                    <th>Total Duration</th>
                                    <th>Total Invoice Duration</th>
                                    <th>Total Amount</th>
                                    <th>Validation Type</th>
                                    <th>Completion Date</th>
                                    <th>Payment Status</th>
                                    <th>Processing Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($tutorpayments as $key=>$rowTutorPayment)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$rowTutorPayment->tutorID}}</td>
                                        <td>{{$rowTutorPayment->tutor_name}}</td>
                                        <td>{{$rowTutorPayment->month}}</td>
                                        <td>{{$rowTutorPayment->year}}</td>
                                        <td>{{$rowTutorPayment->total_duration}}</td>
                                        <td>{{$rowTutorPayment->total_invoice_duration}}</td>
                                        <td>{{$rowTutorPayment->total_amount}}</td>
                                        <td>{{$rowTutorPayment->total_duration==$rowTutorPayment->total_invoice_duration?"Fully Verified":"Partial Verified"}}</td>
                                        <!--<td>{{$rowTutorPayment->pending_count==0?"Fully Verified":"Partial Verified"}}</td>-->
                                        <td>{{$rowTutorPayment->completion_date}}</td>
                                        <td>Unpaid</td>
                                        <!--<td>{{$rowTutorPayment->pending_count==0?"Ready":"Pending"}}</td>-->
                                        <td>{{$rowTutorPayment->total_duration==$rowTutorPayment->total_invoice_duration?"Ready":"Pending"}}</td>
                                        <td>
                                            <a class="dtable-cbtn bt-pay dtb-tooltip" dtb-tooltip="Make Payment" href="{{route('makeTutorPayment',$rowTutorPayment->tutorID)}}"><i class="fa fa-dollar"></i> </a>
                                            <a class="dtable-cbtn bt-view dtb-tooltip" dtb-tooltip="View Payment Detail" href="{{route('viewTutorPaymentJournalBreakdown',$rowTutorPayment->tutorID)}}"><i class="fa fa-eye"></i> </a>
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
@endsection