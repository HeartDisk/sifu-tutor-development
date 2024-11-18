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
                                    Staff List
                                </h2>
                                <nav>
                                    <ol class="breadcrumb breadcrumb-arrow mb-0">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item"><a href="#">Staff</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Staff List</li>
                                    </ol>
                                </nav>
                            </div>
                            <div class="nk-block-head-content">
                                <ul class="d-flex">
                                    <li><a href="{{route('addStaff')}}" class="btn btn-md d-md-none btn-primary"><em
                                                class="icon ni ni-plus"></em><span>Add</span></a></li>
                                    @can("tutor-add")
                                        <li><a href="{{route('addStaff')}}"
                                               class="btn btn-primary d-none d-md-inline-flex"><em
                                                    class="icon ni ni-plus"></em><span>Add Staff</span></a></li>
                                    @endcan
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="nk-block">
                        <div class="card">
                            <div class="card-body">
                                @if(session("success"))
                                    <div class="alert alert-success">
                                        {{ session("success") }}
                                    </div>
                                @endif
                                
                                @if(session("error"))
                                    <div class="alert alert-danger">
                                        {{ session("error") }}
                                    </div>
                                @endif

                                    <form action="{{ route('StaffList') }}" method="GET">
                                        @csrf
                                        <div class="row justify-content-between tableper-row">
                                            <input name="classScheduleSearch" value="1" type="hidden">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-4">
                                                <div class="input-group input-group-md">
                                                    <span class="input-group-text" id="inputGroup-sizing-sm">Search by UID/Name/Email/Phone</span>
                                                    <input name="searchQuery" value="{{ request('searchQuery') }}" type="text" class="search form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="input-group input-group-md justify-content-end">
                                                    <input type="submit" class="btn btn-primary" value="Search">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-2">
                                                <div class="input-group input-group-md justify-content-end">
                                                    <a href="{{url()->current()}}" class="btn btn-danger">Reset</a>
                                                </div>
                                            </div>
                                            <div class="col-md-2"></div>
                                        </div>
                                    </form>

                                    <table class="datatable-init table" data-nk-container="table-responsive">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Role</th>
                                        <th>Gender</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $numbers = 1;
                                    @endphp
                                    @foreach($staffs as $rows)
                                        @if($rows->is_deleted == 0)
                                            <tr>
                                                <td>{{$numbers++}}</td>
                                                <td>{{$rows->full_name}}</td>
                                                <td>{{$rows->email}}</td>
                                                <td>{{$rows->phone}}</td>
                                                <td>{{$rows->role}}</td>
                                                <td>{{$rows->gender}}</td>
                                                <td>
                                                    @if($rows->status == "Inactive")
                                                        <p class="dtable-status-inactive">{{$rows->status}}</p>
                                                    @elseif($rows->status == "Resigned")
                                                        <p class="dtable-status-pending">{{$rows->status}}</p>
                                                    @elseif($rows->status == "Terminated")
                                                        <p class="dtable-status-nostat">{{$rows->status}}</p>
                                                    @elseif($rows->status == "Active")
                                                        <p class="dtable-status-active">{{$rows->status}}</p>
                                                    @endif
                                                </td>
                                                <td>
                                                    @can("staff-payment-make")
                                                        <a class="dtable-cbtn bt-pay dtb-tooltip"
                                                           dtb-tooltip="Make Payment"
                                                           href="{{route('staffPayment',$rows->id)}}"><i
                                                                class="fa fa-dollar"></i> </a>
                                                    @endcan
                                                    @can("staff-view-detail")
                                                        <a class="dtable-cbtn bt-view dtb-tooltip"
                                                           dtb-tooltip="View Staff"
                                                           href="{{route('viewStaff',$rows->id)}}"><i
                                                                class="fa fa-eye"></i></a>
                                                    @endcan
                                                    @can("staff-edit")
                                                        <a class="dtable-cbtn bt-edit dtb-tooltip"
                                                           dtb-tooltip="Edit Staff"
                                                           href="{{route('editStaff',$rows->id)}}"><i
                                                                class="fa fa-edit"></i></a>
                                                    @endcan
                                                    @can("staff-delete")
                                                        <a class="dtable-cbtn bt-delete dtb-tooltip"
                                                           dtb-tooltip="Delete Staff"
                                                           onclick="return confirm('Are you sure you want to delete this Staff Member?');"
                                                           href="{{route('deleteStaff',$rows->id)}}"><i
                                                                class="fa fa-trash"></i></a>
                                                    @endcan
                                                </td>
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
@endsection
