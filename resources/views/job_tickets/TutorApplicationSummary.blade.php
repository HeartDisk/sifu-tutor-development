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
                                    Tutor Application Summary
                                </h2>
                                <nav>
                                    <ol class="breadcrumb breadcrumb-arrow mb-0">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item"><a href="#">Job Ticket</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Tutor Application Summary</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="nk-block">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <form action="{{ route('TutorApplicationSummary') }}" method="GET">
                                    @csrf
                                    <div class="row justify-content-star tableper-row">
                                        <div class="col-md-3">
                                            <div class="input-group input-group-md">
                                                <label class="input-group-text" for="stateID">State</label>
                                                <select class="form-control" id="stateID" name="stateID">
                                                    <option value="">Select State</option>
                                                    @foreach($states as $rowState)
                                                        <option value="{{ $rowState->id }}" {{ old('stateID', request('stateID')) == $rowState->id ? 'selected' : '' }}>{{ $rowState->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group input-group-md">
                                                <label class="input-group-text" for="subjectID">Subject</label>
                                                <select class="form-control" id="subjectID" name="subjectID">
                                                    <option value="">Select Subject</option>
                                                    @foreach($subjects as $rowSubject)
                                                        <option value="{{ $rowSubject->id }}" {{ old('subjectID', request('subjectID')) == $rowSubject->id ? 'selected' : '' }}>{{ $rowSubject->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group input-group-md">
                                                <label class="input-group-text" for="classType">Class Type</label>
                                                <select class="form-control" id="classType" name="classType">
                                                    <option value="">Select Class Type</option>
                                                    <option value="online" {{ old('classType', request('classType')) == 'online' ? 'selected' : '' }}>Online</option>
                                                    <option value="physical" {{ old('classType', request('classType')) == 'physical' ? 'selected' : '' }}>Physical</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group input-group-md">
                                                <label class="input-group-text" for="status">Status</label>
                                                <select class="form-control" id="status" name="status">
                                                    <option value="">Select Status</option>
                                                    <option value="" {{ old('status', request('status')) == '' ? 'selected' : '' }}>All</option>
                                                    <option value="no-application" {{ old('status', request('status')) == 'no-application' ? 'selected' : '' }}>No Application</option>
                                                    <option value="incomplete" {{ old('status', request('status')) == 'incomplete' ? 'selected' : '' }}>Incomplete</option>
                                                    <option value="completed" {{ old('status', request('status')) == 'completed' ? 'selected' : '' }}>Completed</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group input-group-md">
                                                <label class="input-group-text" for="tutorStatus">Tutor Status</label>
                                                <select class="form-control" id="tutorStatus" name="tutorStatus">
                                                    <option value="">Select Tutor Status</option>
                                                    <option value="active" {{ old('tutorStatus', request('tutorStatus')) == 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="inactive" {{ old('tutorStatus', request('tutorStatus')) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group input-group-md">
                                                <label class="input-group-text" for="cityID">City</label>
                                                <select class="form-control" id="cityID" name="cityID">
                                                    <option value="">Select City</option>
                                                    @foreach($cities as $rowCity)
                                                        <option value="{{ $rowCity->id }}" {{ old('cityID', request('cityID')) == $rowCity->id ? 'selected' : '' }}>{{ $rowCity->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group input-group-md">
                                                <label class="input-group-text" for="applicationDateFrom">Date From</label>
                                                <input type="date" class="form-control" id="applicationDateFrom" name="applicationDateFrom" value="{{ old('applicationDateFrom', request('applicationDateFrom')) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group input-group-md">
                                                <label class="input-group-text" for="applicationDateTo">Date To</label>
                                                <input type="date" class="form-control" id="applicationDateTo" name="applicationDateTo" value="{{ old('applicationDateTo', request('applicationDateTo')) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group input-group-md">
                                                <span class="input-group-text" id="inputGroup-sizing-sm">Search</span>
                                                <input name="search" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" placeholder="Search by Tutor ID, Full Name, Email, Subject, State, City" value="{{ old('search', request('search')) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group input-group-md">
                                                <input type="submit" class="btn btn-primary" aria-label="Sizing example input" value="Search">
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

                                <table class="datatable-init table" data-nk-container="table-responsive" id="table-container">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tutor ID</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Subject Applied</th>
                                        <th>Application Status</th>
                                        <th>State</th>
                                        <th>City</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data as $key => $row)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $row->uid }}</td>
                                            <td>{{ $row->tutor }}</td>
                                            <td>{{ $row->email }}</td>
                                            <td>{{ $row->subject }}</td>
                                            <td><p class="dtb-astatus">{{ $row->status }}</p></td>
                                            <td>{{ $row->state_name }}</td>
                                            <td>{{ $row->city_name }}</td>
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
