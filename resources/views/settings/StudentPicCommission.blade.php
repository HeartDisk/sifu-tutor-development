@extends('layouts.main')

@section('content')

    <div class="nk-content">
        <div class="fluid-container">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-page-head">
                        <div class="nk-block-head-content">
                            <table style="width:100%">
                                <tbody>
                                <tr>
                                    <td><h1 class="nk-block-title">STUDENT PIC COMMISSIONS</h1></td>
                                    @can("student-pic-commission-add")
                                        <td><a href="{{route('addComission')}}" style="float:right; "
                                               class="btn btn-primary nk-block-title"><i class="fa fa-user"></i> &nbsp;
                                                Add Commission</a></td>
                                    @endcan

                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="nk-block">
                        <div class="card">
                            <div class="card-body">
                                <table class="datatable-init table" data-nk-container="table-responsive table-border">
                                    <thead class="table-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">From Date</th>
                                        <th scope="col">To Date</th>
                                        <th scope="col">First Invoice <br/>Amount Physical Class</th>
                                        <th scope="col">Recurrence Invoice <br/>Amount Physical Class</th>
                                        <th scope="col">First Invoice <br/> Amount Online Class</th>
                                        <th scope="col">Recurrence Invoice <br/>Amount Online Class</th>

                                        <th scope="col" width="150">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $number = 1;
                                    @endphp
                                    @foreach($studentcomissions as $rowStudentcomissions)

                                        <tr>
                                            <th scope="row">{{$number++}}</th>
                                            <td>{{$rowStudentcomissions->fromDate}}</td>
                                            <td>{{$rowStudentcomissions->ToDate}}</td>
                                            <td>{{$rowStudentcomissions->FirstInvoiceAmountPhysicalClass}}</td>
                                            <td>{{$rowStudentcomissions->RecurrenceInvoiceAmountPhysicalClass}}</td>
                                            <td>{{$rowStudentcomissions->FirstInvoiceAmountOnlineClass}}</td>
                                            <td>{{$rowStudentcomissions->RecurrenceInvoiceAmountOnlineClass}}</td>
                                            <td>
                                                @can("student-pic-commission-detail")
                                                    <a href="{{route('viewComission',$rowStudentcomissions->id)}}"
                                                       class="btn btn-sm btn-outline-success btn-table-action waves-effect waves-light"><span
                                                            class="fa fa-eye"></span></a>
                                                @endcan
                                                @can("student-pic-commission-edit")
                                                    <a href="{{route('editComission',$rowStudentcomissions->id)}}"
                                                       class="btn btn-sm  btn-outline-primary btn-table-action waves-effect waves-light"><span
                                                            class="fa fa-edit"></span></a>
                                                @endcan

                                                @can("student-pic-commission-delete")
                                                    <a href=""
                                                       class="btn btn-sm  btn-outline-danger btn-table-action waves-effect waves-light"><span
                                                            class="fa fa-trash"></span></a>
                                                @endcan

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
