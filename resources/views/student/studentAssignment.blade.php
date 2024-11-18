@extends('layouts.main')
@section('content')
    <div class="nk-content">
        <div class="fluid-container sifu-view-page">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head">
                        <div class="nk-block-head-between flex-wrap gap g-2">
                            <div class="nk-block-head-content mb-4">
                                <h2 class="nk-block-title">
                                    STUDENT - TUTOR ASSIGNMENTS</h1>
                                    <nav>
                                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                                            <li class="breadcrumb-item"><a href="#">Student Assignment</a></li>
                                        </ol>
                                    </nav>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{route('studentAssignments')}}" method="GET">
                                        <div class="row justify-content-between tableper-row">
                                            <div class="col-md-10">
                                                <div class="input-group input-group-md">
                                                    <span class="input-group-text" id="inputGroup-sizing-sm">Search</span>
                                                    <input name="search" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" placeholder="Search by Student Name, Tutor, or Subject" value="{{ Request::get('search') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="input-group input-group-md">
                                                    <input type="submit" class="btn btn-primary" style="background-color:#304bd4;" aria-label="Sizing example input" value="Search" aria-describedby="inputGroup-sizing-sm">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
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
                                    <table class="datatable-init table" data-nk-container="table-responsive">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Student Id</th>
                                            <th>Fullname</th>
                                            <th>Staff In Charge</th>
                                            <th>Subject</th>
                                            <th>Tutor</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data as $key=>$dt)
                                            @php
                                                $job_ticket=DB::table("job_tickets")->where("id",$dt->ticket_id)->first();
                                                $staff=DB::table("staffs")->where("id",$job_ticket->admin_charge)->first();
                                            @endphp
                                            @if(isset($staff) && isset($dt))
                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td>
                                                        <span class="fa fa-user user-active" title="Individual"></span>
                                                        {{$dt->student_id}}
                                                    </td>
                                                    <td>{{$dt->student_name}}</td>
                                                    <td>{{$staff->full_name}}</td>
                                                    <td>{{$dt->product_name}}</td>
                                                    <td>{{$dt->tutor_name}}</td>
                                                </tr>
                                            @endif
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
