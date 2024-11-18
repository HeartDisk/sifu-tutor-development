@extends('layouts.main')
@section('content')
    <div class="nk-content sifu-view-page">
        <div class="fluid-container">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head">
                        <div class="nk-block-head">
                            <div class="nk-block-head-between flex-wrap gap g-2">
                                <div class="nk-block-head-content">
                                    <h2 class="nk-block-title">
                                        Tutor Finder
                                    </h2>
                                    <nav>
                                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                                            <li class="breadcrumb-item"><a href="#">Tutors</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">Tutor Finder</li>
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
                                    <form action="{{ route('TutorFinder') }}" method="GET">
                                        @csrf
                                        <input type="hidden" name="tutorSearchValue" value="1"/>
                                        <div class="row justify-content-between tableper-row">
                                            <!--<div class="col-md-2">-->
                                            <!--    <div class="input-group input-group-md">-->
                                            <!--        <label class="input-group-text"-->
                                            <!--               for="inputGroupSelect01">Level</label>-->
                                            <!--        <select name="subject" class="form-control" id="inputGroupSelect01">-->
                                            <!--            <option value="">Select Subject</option>-->
                                            <!--            @foreach($subjects as $subject)-->
                                            <!--                <option-->
                                            <!--                    {{ Request::get('subject') == $subject->id ? 'selected' : '' }} value="{{ $subject->id }}">-->
                                            <!--                    {{ $subject->name . '-' . $subject->category_name . '(' . $subject->mode . ')' }}-->
                                            <!--                </option>-->
                                            <!--            @endforeach-->
                                            <!--        </select>-->
                                            <!--    </div>-->
                                            <!--</div>-->
                                            
                                            
                                            
                                             <div class="col-md-3">
                                                <div class="input-group input-group-md">
                                                    <label class="input-group-text"
                                                           for="inputGroupSelect01">Category</label>
                                                    <select name="category" class="form-control" id="inputGroupSelect01">
                                                        <option value="">Select Subject</option>
                                                        @foreach($categories as $category)
                                                            <option
                                                                {{ Request::get('subject') == $category->id ? 'selected' : '' }} value="{{ $category->id }}">
                                                                {{ $category->category_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="col-md-3">
                                                <div class="input-group input-group-md">
                                                    <label class="input-group-text"
                                                           for="inputGroupSelect01">Subject</label>
                                                    <select name="subject" class="form-control" id="inputGroupSelect01">
                                                        <option value="">Select Subject</option>
                                                        @foreach($subjects as $subject)
                                                            <option
                                                                {{ Request::get('subject') == $subject->id ? 'selected' : '' }} value="{{ $subject->id }}">
                                                                {{ $subject->name . '-' . $subject->category_name . '(' . $subject->mode . ')' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            

                                           <div class="col-md-3">
                                                <div class="input-group input-group-md">
                                                    <label class="input-group-text" for="inputGroupSelect01">State</label>
                                                    <select name="state" class="form-control" id="inputGroupSelect01">
                                                        <option value="">Select State</option>
                                                        @foreach($states as $state)
                                                            <option {{ Request::get('state') == $state->id ? 'selected' : '' }} value="{{ $state->id }}">
                                                                {{ $state->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <div class="input-group input-group-md">
                                                    <label class="input-group-text"
                                                           for="inputGroupSelect01">City</label>
                                                    <select name="city" class="form-control" id="inputGroupSelect01">
                                                        <option value="">Select City</option>
                                                        @foreach($cities as $city)
                                                            <option
                                                                {{ Request::get('city') == $city->id ? 'selected' : '' }} value="{{ $city->id }}">
                                                                {{ $city->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group input-group-md">
                                                    <label class="input-group-text"
                                                           for="inputGroupSelect01">Status</label>
                                                    <select name="status" class="form-control" id="inputGroupSelect01">
                                                        <option value="">Select Status</option>
                                                        <option value="">All</option>
                                                        <option
                                                            {{ Request::get('status') == 'active' ? 'selected' : '' }} value="active">
                                                            Active
                                                        </option>
                                                        <option
                                                            {{ Request::get('status') == 'inactive' ? 'selected' : '' }} value="inactive">
                                                            In-Active
                                                        </option>
                                                        <option
                                                            {{ Request::get('status') == 'terminated' ? 'selected' : '' }} value="terminated">
                                                            Terminated
                                                        </option>
                                                        <option
                                                            {{ Request::get('status') == 'resigned' ? 'selected' : '' }} value="resigned">
                                                            Resigned
                                                        </option>
                                                        <option
                                                            {{ Request::get('status') == 'unverified' ? 'selected' : '' }} value="unverified">
                                                            Unverified
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group input-group-md">
                                                    <label class="input-group-text" for="searchQuery">Search
                                                        Tutor</label>
                                                    <input type="text" name="searchQuery" class="form-control"
                                                           id="searchQuery" placeholder="Full Name or Email"
                                                           value="{{ Request::get('searchQuery') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="input-group input-group-md">
                                                    <input type="submit" class="btn btn-primary"
                                                           aria-label="Sizing example input" value="Search"
                                                           aria-describedby="inputGroup-sizing-sm">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="input-group input-group-md">
                                                    <a href="{{url()->current()}}" class="btn btn-danger">Reset</a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <table class="datatable-init table" data-nk-container="table-responsive">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tutor Id</th>
                                            <th>Fullname</th>
                                            <th>Email</th>
                                            <th>Phone No.</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $number = 1;
                                        @endphp
                                        @foreach($tutors as $rowTutor)
                                            <tr>
                                                <th>{{$number++}}</th>
                                                <td>{{$rowTutor->uid}}</td>
                                                <td>{{$rowTutor->full_name}}</td>
                                                <td>{{$rowTutor->email}}</td>
                                                <td>{{$rowTutor->phoneNumber}}</td>
                                                <td>
                                                    <a href="{{route('viewTutor',$rowTutor->id)}}"
                                                       class="dtable-cbtn bt-view dtb-tooltip"
                                                       dtb-tooltip="Details"><span class="fa fa-eye"></span></a>
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
    </div>
@endsection
