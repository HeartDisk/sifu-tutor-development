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
                                    Job Ticket List
                                </h2>
                                <nav>
                                    <ol class="breadcrumb breadcrumb-arrow mb-0">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item"><a href="#">Job Ticket</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Job Ticket List</li>
                                    </ol>
                                </nav>
                            </div>
                            <div class="nk-block-head-content">
                                <ul class="d-flex">
                                    <li><a href="{{route('addTicket')}}" class="btn btn-md d-md-none btn-primary"><em class="icon ni ni-plus"></em><span>Add</span></a></li>
                                    @can("job-ticket-add")
                                        <li><a href="{{route('addTicket')}}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Add Job Ticket</span></a></li>
                                    @endcan
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="nk-block">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <form action="{{route('TicketList')}}" method="GET">
                                    @csrf
                                    <div class="row justify-content-end tableper-row">
                                        <div class="col-md-4">
                                            <div class="input-group  input-group-md">
                                                <label class="input-group-text" for="inputGroupSelect01">State</label>
                                                <select class="form-control" id="stateID" name="stateID">
                                                    <option value="">Select State</option>
                                                    @php
                                                        $states = DB::table('states')->get();
                                                    @endphp
                                                    @foreach($states as $rowState)
                                                        <option value="{{$rowState->id}}">{{$rowState->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group  input-group-md">
                                                <label class="input-group-text" for="inputGroupSelect01">Subject</label>
                                                <select class="form-control" id="subjectID" name="subjectID">
                                                    <option value="">Select Subject</option>
                                                    @php
                                                        $subjects = DB::table('products')->get();
                                                    @endphp
                                                    @foreach($subjects as $rowSubject)
                                                        <option
                                                            value="{{$rowSubject->id}}">{{$rowSubject->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group  input-group-md">
                                                <label class="input-group-text" for="inputGroupSelect01">Class
                                                    Type</label>
                                                <select class="form-control" id="classType" name="classType">
                                                    <option value="">Select Class Type</option>
                                                    <option value="online">Online</option>
                                                    <option value="physical">Physical</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group  input-group-md">
                                                <label class="input-group-text" for="inputGroupSelect01">Status</label>
                                                <select class="form-control" id="status" name="status">
                                                    <option  value="">Select Status</option>
                                                    <option value="">All</option>
                                                    <option value="no-application">no-application</option>
                                                    <option value="incomplete">incomplete</option>
                                                    <option value="completed">Completed</option>
                                                    <option value="Active">Active</option>
                                                    <option value="discontinued">Discontinued</option>
                                                </select>
                                            </div>
                                        </div>
                                         <div class="col-md-4">
                                            <div class="input-group  input-group-md">
                                                <label class="input-group-text" for="inputGroupSelect01">Person Incharge</label>
                                                <select class="form-control" id="staff" name="staff">
                                                    <option value="">Select option</option>
                                                    @foreach($staffs as $staff)
                                                     <option value="{{$staff->id}}">{{$staff->full_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group input-group-md">
                                                <span class="input-group-text" id="inputGroup-sizing-sm" >Search By Ticket</span>
                                                <input name="search" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" placeholder="Ticket Number">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group input-group-md">
                                                <input type="submit" class="btn btn-success" aria-label="Sizing example input" value="Search" aria-describedby="inputGroup-sizing-sm">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group input-group-md">
                                                <a class="btn btn-danger" href="{{url("/TicketList")}}">Reset</a>
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
                                <table class="datatable-init table" data-nk-container="table-responsive">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Ticket No</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                        <th>Student</th>
                                        <th>Customer</th>
                                        <th>Ticket Status</th>
                                        <th>Class Type</th>
                                        <th>Application Status</th>
                                        <th>Subjects</th>
                                        <th>Created On</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $numbers = 1;
                                        $staff_id=DB::table("staffs")->where("user_id",\Illuminate\Support\Facades\Auth::user()->id)->first();
    
    
                                    @endphp
                                    @foreach($tickets as $rows)
                                    
                                    @php
                                    $check=false;
                                    $student_detail = DB::table('students')->where('id','=',$rows->student_id)->first();
                                    if($student_detail!=null)
                                    {
                                        $check=true;
                                    }
                                    @endphp
                                    
                                        @if($staff_id!=null && $rows->admin_charge==$staff_id->id && $check==true)
                                        <tr>
                                            <td>{{$numbers++}}</td>
                                            <td>{{$rows->uid}}</td>
                                            <td><p class="dtb-tcstatus">{{$rows->ticket_tutor_status==null?"Active":ucfirst($rows->ticket_tutor_status)}}</p></td>
                                            <td>{{$rows->totalPrice}}RM</td>
                                            <td>
                                                @php
                                                    $student_detail = DB::table('students')->where('id','=',$rows->student_id)->first();
                                                    $parent_detail = DB::table('customers')->where('id','=',$student_detail->customer_id)->first();
                                                @endphp
                                                @if($student_detail)
                                                    {{$student_detail->full_name}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($parent_detail->full_name)
                                                    {{$parent_detail->full_name}}
                                                @endif
                                            </td>
                                            <td><p class="dtb-tcstatus">{{$rows->status}}</p></td>
                                            <td>{{$rows->mode}}</td>
                                            <td><p class="dtb-apstatus">{{$rows->application_status}}</p></td>
                                            <td>
                                                @php
                                                    $subject = DB::table('products')->where('id','=',$rows->subjects)->first();
                                                @endphp
                                                {{$subject->name}}
                                            </td>
                                            <td>{{$rows->created_at}}</td>
                                            <td>
                                                <!--<a class="dtable-cbtn bt-copy dtb-tooltip" dtb-tooltip="Duplicate Ticket" href="{{route('duplicateTicket',$rows->id)}}"> <i class="fa fa-copy"></i> </a>-->
                                                @can("job-ticket-detail")
                                                    <a class="dtable-cbtn bt-view dtb-tooltip" dtb-tooltip="View Detail" href="{{route('viewTicket',$rows->id)}}"> <i class="fa fa-eye"></i></a>
                                                @endcan
                                                @can("job-ticket-edit")
    {{--                                                <a class="dtable-cbtn bt-edit dtb-tooltip" dtb-tooltip="Edit" href="{{route('editTicket',$rows->id)}}"><i class="fa fa-edit"></i></a>--}}
                                                @endcan
                                                <a class="dtable-cbtn bt-edit dtb-tooltip" dtb-tooltip="Edit" href="{{route('editTicketTutor',$rows->id)}}"><i class="fa fa-edit"></i></a>
    
                                                @can("job-ticket-delete")
                                                    <a class="dtable-cbtn bt-delete dtb-tooltip" dtb-tooltip="Delete" onclick="return confirm('Are you sure you want to delete this Parrent?');" href="{{route('deleteTicket',$rows->id)}}"><i class="fa fa-trash"></i></a>
                                                @endcan
                                            </td>
                                        </tr>
                                            @elseif(\Illuminate\Support\Facades\Auth::user()->role==7 && $check==true)
                                            <tr>
                                                <td>{{$numbers++}}</td>
                                                <td>{{$rows->uid}}</td>
                                                <td><p class="dtb-tcstatus">{{$rows->ticket_tutor_status==null?"Active":ucfirst($rows->ticket_tutor_status)}}</p></td>
                                                <td>{{$rows->totalPrice}}RM</td>
                                                <td>
                                                    @php
                                                        $student_detail = DB::table('students')->where('id','=',$rows->student_id)->first();
                                                        $parent_detail = DB::table('customers')->where('id','=',$student_detail->customer_id)->first();
                                                    @endphp
                                                    @if($student_detail)
                                                        {{$student_detail->full_name}}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($parent_detail->full_name)
                                                        {{$parent_detail->full_name}}
                                                    @endif
                                                </td>
                                                <td><p class="dtb-tcstatus">{{$rows->status}}</p></td>
                                                <td>{{$rows->mode}}</td>
                                                <td><p class="dtb-apstatus">{{$rows->application_status}}</p></td>
                                                <td>
                                                    @php
                                                        $subject = DB::table('products')->where('id','=',$rows->subjects)->first();
                                                    @endphp
                                                    {{$subject->name}}
                                                </td>
                                                <td>{{$rows->created_at}}</td>
                                                <td>
                                                    <!--<a class="dtable-cbtn bt-copy dtb-tooltip" dtb-tooltip="Duplicate Ticket" href="{{route('duplicateTicket',$rows->id)}}"> <i class="fa fa-copy"></i> </a>-->
                                                    @can("job-ticket-detail")
                                                        <a class="dtable-cbtn bt-view dtb-tooltip" dtb-tooltip="View Detail" href="{{route('viewTicket',$rows->id)}}"> <i class="fa fa-eye"></i></a>
                                                    @endcan
                                                    @can("job-ticket-edit")
    {{--                                                    <a class="dtable-cbtn bt-edit dtb-tooltip" dtb-tooltip="Edit" href="{{route('editTicket',$rows->id)}}"><i class="fa fa-edit"></i></a>--}}
                                                    @endcan
                                                    <a class="dtable-cbtn bt-edit dtb-tooltip" dtb-tooltip="Edit" href="{{route('editTicketTutor',$rows->id)}}"><i class="fa fa-edit"></i></a>
    
                                                    @can("job-ticket-delete")
                                                        <a class="dtable-cbtn bt-delete dtb-tooltip" dtb-tooltip="Delete" onclick="return confirm('Are you sure you want to delete this Parrent?');" href="{{route('deleteTicket',$rows->id)}}"><i class="fa fa-trash"></i></a>
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