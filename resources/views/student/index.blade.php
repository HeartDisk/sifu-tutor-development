x`@extends('layouts.main')
@section('content')
    <div class="nk-content sifu-view-page">
        <div class="fluid-container">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head">
                        <div class="nk-block-head-between flex-wrap gap g-2">
                            <div class="nk-block-head-content">
                                <h2 class="nk-block-title">
                                    Students List</h1>
                                    <nav>
                                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                                            <li class="breadcrumb-item"><a href="#">Student List</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">Student</li>
                                        </ol>
                                    </nav>
                            </div>
                            <div class="nk-block-head-content">
                                <ul class="d-flex">
                                    @can("student-add")
                                        <li><a href="{{route('addStudent')}}"
                                               class="btn btn-md d-md-none btn-primary"><em
                                                    class="icon ni ni-plus"></em><span>Add</span></a></li>
                                        <li><a href="{{route('addStudent')}}"
                                               class="btn btn-primary d-none d-md-inline-flex"><em
                                                    class="icon ni ni-plus"></em><span>Add Student</span></a></li>
                                    @endcan
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="nk-block">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{route('Students')}}" method="GET">
                                    @csrf
                                    <div class="row justify-content-between tableper-row">
                                        <input name="studentSearch" value="1" type="hidden">
                                        <div class="col-md-3">
                                            <div class="input-group input-group-md">
                                                <label class="input-group-text" for="inputGroupSelect01">Status</label>
                                                <select name="status" class="status form-select"
                                                        id="inputGroupSelect01">
                                                    <option
                                                        value="Active" {{ Request::get('status') == 'Active' ? 'selected' : '' }}>
                                                        Active
                                                    </option>
                                                    <option
                                                        value="Inactive" {{ Request::get('status') == 'Inactive' ? 'selected' : '' }}>
                                                        Inactive
                                                    </option>
                                                    <option
                                                        value="Pending" {{ Request::get('status') == 'Pending' ? 'selected' : '' }}>
                                                        Pending
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group input-group-md">
                                                <span class="input-group-text" id="inputGroup-sizing-sm">Search</span>
                                                <input name="search" type="text" class="form-control"
                                                       aria-label="Sizing example input"
                                                       aria-describedby="inputGroup-sizing-sm"
                                                       value="{{ Request::get('search')}}" placeholder="Student Name">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group input-group-md">
                                                <label class="input-group-text" for="from_date">From</label>
                                                <input type="date" name="from_date" class="form-control" id="from_date"
                                                       value="{{ Request::get('from_date') }}">

                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group input-group-md">
                                                <label class="input-group-text" for="to_date">To</label>
                                                <input type="date" name="to_date" class="form-control" id="to_date"
                                                       value="{{ Request::get('to_date') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group input-group-md">
                                                <input type="submit" class="btn btn-primary btn-md"
                                                       aria-label="Sizing example input" value="Search"
                                                       aria-describedby="inputGroup-sizing-sm">
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
                                        <th>Student ID</th>
                                        <th>Parent Name</th>
                                        <th>Full Name</th>
                                        <th>Gender</th>
                                        <th>Age</th>
                                        <th>Status</th>
                                        <th>Registration Date</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="studentListAjaxCallBody">

                                    @foreach($students as $key=>$rowStudents)
                                        @if($rowStudents->full_name != '')
                                            @if($rowStudents->is_deleted == 0)
                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td><i class="fa fa-user"></i> {{$rowStudents->student_id}}</td>
                                                    <td>
                                                        @php
                                                            $customers = DB::table('customers')->where('id','=',$rowStudents->customer_id)->get();
                                                        @endphp
                                                        @if(!$customers->isEmpty())
                                                            {{$customers[0]->full_name}}
                                                        @endif
                                                    </td>
                                                    <td>{{$rowStudents->full_name}}</td>
                                                    <td>
                                                        {{$rowStudents->gender}}
                                                    </td>
                                                    <td>{{$rowStudents->age}} Years</td>
                                                    <td>
                                                        @if($rowStudents->status == "inactive")
                                                            <p class="dtable-status-inactive">{{$rowStudents->status}}</p>
                                                        @elseif($rowStudents->status == "pending")
                                                            <p class="dtable-status-pending">{{$rowStudents->status}}</p>
                                                        @elseif($rowStudents->status == "active")
                                                            <p class="dtable-status-active">{{$rowStudents->status}}</p>
                                                        @endif
                                                    </td>
                                                    <td>{{$rowStudents->register_date}}</td>
                                                    <td>
                                                        <a class="dtable-cbtn bt-dashboard dtb-tooltip"
                                                           dtb-tooltip="Student Dashboard"
                                                           href="{{route('studentDashboard',$rowStudents->id)}}"><i
                                                                class="fa fa-dashboard"></i> </a>
                                                        @can("student-detail")
                                                            <a class="dtable-cbtn bt-view dtb-tooltip"
                                                               dtb-tooltip="View Student Detail"
                                                               href="{{route('viewStudent',$rowStudents->id)}}"><i
                                                                    class="fa fa-eye"></i> </a>
                                                        @endcan
                                                        @can("student-edit")
                                                            <a class="dtable-cbtn bt-edit dtb-tooltip"
                                                               dtb-tooltip="Edit Student"
                                                               href="{{route('editStudent',$rowStudents->id)}}"><i
                                                                    class="fa fa-edit"></i> </a>
                                                        @endcan
                                                        @can("student-delete")
                                                            <a class="dtable-cbtn bt-delete dtb-tooltip"
                                                               dtb-tooltip="Delete Student"
                                                               onclick="return confirm('Are you sure you want to delete this student?');"
                                                               href="{{route('deleteStudent',$rowStudents->id)}}"><i
                                                                    class="fa fa-trash"></i> </a>
                                                        @endcan
                                                    </td>
                                                </tr>
                                            @endif
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
    <!-- script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
   <script>
       $(document).ready(function(){
           $("#studentTable").submit(function(event){
               event.preventDefault();
               var formValues = $(this).serialize();
               var studentStatus = $(".status").val();
               var ajaxCall = "studentList";
               console.log(ajaxCall);

                       $.ajax({
                        type:'POST',
                        url:'{{route("ajaxCall")}}',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success:function(data){
                           //$("#result").html(data);
                           $("#"+ajaxCall+"AjaxCallBody").hide();
                           console.log(data);
                        }
                     });
           });
       });
   </script -->
@endsection
