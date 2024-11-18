@extends('layouts.main')
@section('content')
    <div class="nk-content sifu-view-page">
        <div class="fluid-container">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head flex-wrap gap g-2">
                        <div class="nk-block-head-content">
                            <h2 class="nk-block-title">
                                Student Invoices For Confirmation
                            </h2>
                            <nav>
                                <ol class="breadcrumb breadcrumb-arrow mb-0">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item"><a href="#">Followup</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Student Invoices For
                                        Confirmation
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <div class="nk-block">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                @if(session()->has('success'))
                                    <div class="alert alert-success alert alert-success alert-dismissible fade show">
                                        {{ session()->get('success') }}
                                    </div>
                                @endif
                                <form action="{{ route('StudentInvoiceReadyConfirmation') }}" method="GET">
                                    @csrf
                                    <input name="classScheduleSearch" value="1" type="hidden">
                                    <div class="row justify-content-between tableper-row">
                                        <!--<div class="col-md-3">-->
                                        <!--   <div class="input-group  input-group-md">-->
                                        <!--      <label class="input-group-text" for="inputGroupSelect01">Filter By</label>-->
                                        <!--      <select name="status" class="status form-select" id="inputGroupSelect01">-->
                                        <!--         <option value=""> Class Scheduled</option>-->
                                        <!--         <option value=""> Report Submitted</option>-->
                                        <!--      </select>-->
                                        <!--   </div>-->
                                        <!--</div>-->
                                        <div class="col-md-4">
                                            <div class="input-group input-group-md">
                                                <span class="input-group-text"
                                                      id="inputGroup-sizing-sm">From Date</span>
                                                <input name="fromDate" type="date" class="search form-control"
                                                       value="{{ request('fromDate') }}"
                                                       aria-label="Sizing example input"
                                                       aria-describedby="inputGroup-sizing-sm">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group input-group-md">
                                                <span class="input-group-text" id="inputGroup-sizing-sm">To Date</span>
                                                <input name="toDate" type="date" class="search form-control"
                                                       value="{{ request('toDate') }}" aria-label="Sizing example input"
                                                       aria-describedby="inputGroup-sizing-sm">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group input-group-md justify-content-end">
                                                <input type="submit" class="btn btn-primary"
                                                       aria-label="Sizing example input" value="Search"
                                                       aria-describedby="inputGroup-sizing-sm">
                                            </div>
                                        </div>
{{--                                        <div class="col-md-2">--}}
{{--                                            <div class="input-group input-group-md justify-content-end">--}}
{{--                                                <a href="{{ url('export_excel/StudentInvoiceReadyForConfirmationList') }}"--}}
{{--                                                   class="btn-cdownload btn btn-success" title="Download"><i--}}
{{--                                                        title="Download" class="fa fa-download"></i></a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                    </div>
                                </form>
                                <table class="datatable-init table" data-nk-container="table-responsive">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Reference No</th>
                                        <th>Invoice Date</th>
                                        <th>Total Price</th>
                                        <th>Class Schedule on</th>
                                        <th>Report Submit on</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($invoices as $key=>$rowInvoices)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{$rowInvoices->reference}}</td>
                                            <td>{{$rowInvoices->invoiceDate}}</td>
                                            <td>{{$rowInvoices->invoiceTotal}}</td>
                                            <td>{{ $rowInvoices->schedule_date ?? 'N/A' }}</td>
                                            <td>{{ $rowInvoices->report_date ?? 'N/A' }}</td>
                                            <td>
                                                <a class="dtable-cbtn bt-view dtb-tooltip"
                                                   dtb-tooltip="View Tutor Detail"
                                                   href="{{route('viweStudentInvoiceReadyConfirmation',$rowInvoices->id)}}"><i
                                                        class="fa fa-eye"></i> </a>
                                                <!--<a class="dtable-cbtn bt-edit dtb-tooltip" dtb-tooltip="Edit Tutor" href="{{route('editStudentInvoiceReadyConfirmation',$rowInvoices->id)}}"><i class="fa fa-edit"></i> </a>-->
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
