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
                                    <td><h1 class="nk-block-title">TUTOR BONUS</h1></td>
                                    @can("tutor-bonus-add")
                                        <td><a href="{{route('addTutorBonus')}}" style="float:right; "
                                               class="btn btn-primary nk-block-title"><i class="fa fa-user"></i> &nbsp;
                                                Add Tutor Bonus</a></td>
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
                                    @foreach($tutorbonuses as $rowTutorbonuses)
                                        <tr>
                                            <th scope="row">{{$number++}}</th>
                                            <td>{{$rowTutorbonuses->fromDate}}</td>
                                            <td>{{$rowTutorbonuses->toDate}}</td>
                                            <td>{{$rowTutorbonuses->rangeFrom}}</td>
                                            <td>{{$rowTutorbonuses->rangeTo}}</td>
                                            <td>RM {{$rowTutorbonuses->bonusAmount}}</td>
                                            <td>
                                                @can("tutor-bonus-detail")
                                                    <a href="{{route('viewTutorBonuses',$rowTutorbonuses->id)}}"
                                                       class="btn btn-sm btn-outline-primary btn-table-action waves-effect waves-light"><span
                                                            class="fa fa-eye"></span></a>
                                                @endcan
                                                @can("tutor-bonus-edit")

                                                    <a href="{{route('editTutorBonuses',$rowTutorbonuses->id)}}"
                                                       class="btn btn-sm btn-outline-primary btn-table-action waves-effect waves-light"><span
                                                            class="fa fa-edit"></span></a>
                                                @endcan

                                                @can("tutor-bonus-delete")
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
