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
                                    <td><h1 class="nk-block-title">STUDENT PIC BONUSES</h1></td>
                                    @can("student-pic-bonus-add")
                                        <td><a href="{{route('addStudentPicBonuses')}}" style="float:right; "
                                               class="btn btn-primary nk-block-title"><i class="fa fa-user"></i> &nbsp;
                                                Add Student PIC Bonuses</a></td>
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
                                        <th scope="col">Range From</th>
                                        <th scope="col">Range To</th>
                                        <th scope="col">Bonus Amount</th>
                                        <th scope="col" width="150">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $number = 1;
                                    @endphp
                                    @foreach($studentbonuses as $rowStudentbonuses)
                                        <tr>
                                            <th scope="row">{{$number++}}</th>
                                            <td>{{$rowStudentbonuses->fromDate}}</td>
                                            <td>{{$rowStudentbonuses->toDate}}</td>
                                            <td>{{$rowStudentbonuses->rangeFrom}}</td>
                                            <td>{{$rowStudentbonuses->rangeTo}}</td>
                                            <td>RM {{$rowStudentbonuses->bonusAmount}}</td>
                                            <td>
                                                @can("student-pic-bonus-details")
                                                    <a href="{{route('viewStudentPICBonuses',$rowStudentbonuses->id)}}"
                                                       class="btn btn-sm btn-outline-primary btn-table-action waves-effect waves-light"><span
                                                            class="fa fa-eye"></span></a>
                                                @endcan

                                                @can("student-pic-commission-edit")
                                                    <a href="{{route('editStudentPICBonuses',$rowStudentbonuses->id)}}"
                                                       class="btn btn-sm btn-outline-primary btn-table-action waves-effect waves-light"><span
                                                            class="fa fa-edit"></span></a>
                                                @endcan

                                                @can("student-pic-bonus-delete")
                                                    <a href="#"
                                                       class="btn btn-sm btn-outline-danger btn-table-action waves-effect waves-light"><span
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
