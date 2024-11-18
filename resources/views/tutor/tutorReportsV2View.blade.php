@extends('layouts.main')
@section('content')
    <div class="nk-content sifu-view-page">
        <div class="fluid-container">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head">
                        <div class="nk-block-head-between flex-wrap gap g-2">
                            <div class="nk-block-head-content">
                                <h2 class="nk-block-title">Evaluation Report</h2>
                                <nav>
                                    <ol class="breadcrumb breadcrumb-arrow mb-0">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item"><a href="#">Students Invoices</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Evaluation Report</li>
                                    </ol>
                                </nav>
                            </div>
{{--                            <div class="nk-block-head-content">--}}
{{--                                <ul class="d-flex">--}}
{{--                                    <li><a href="TutorReportsV2DownloadReport/38394" class="btn btn-primary"--}}
{{--                                           title="View Report" target="_blank"><i class="fa fa-download"></i>--}}
{{--                                            Download</a></li>--}}
{{--                                </ul>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                    <div class="nk-block">
                        <div class="card card-gutter-md">
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
                                <div class="bio-block">
                                    <div class="row view-sindetails">
                                        <div class="col-md-2 details-item">
                                            <p class="item-title">Date</p>
                                            <p><strong>{{$tutorReport->currentDate}}</strong></p>
                                        </div>
                                        <div class="col-md-2 details-item">
                                            <p class="item-title">TutorId</p>
                                            <p><strong>{{$tutorReport->tutorID}}</strong></p>
                                        </div>
                                        <div class="col-md-2 details-item">
                                            <p class="item-title">Tutor Name</p>
                                            <p><strong>{{$tutorReport->tutorName}}</strong></p>
                                        </div>
                                        <div class="col-md-2 details-item">
                                            <p class="item-title">StudentId</p>
                                            <p><strong>{{$tutorReport->studentID}}</strong></p>
                                        </div>
                                        <div class="col-md-2 details-item">
                                            <p class="item-title">Student Name</p>
                                            <p><strong>{{$tutorReport->studentName}}</strong></p>
                                        </div>
                                        <div class="col-md-2 details-item">
                                            <p class="item-title">Product Name</p>
                                            <p><strong>{{$tutorReport->subjectName}}</strong></p>
                                        </div>
                                    </div>
                                    <div class="row view-ans">
                                        <div class="col-md-12">
                                            <p class="item-title item-ans">A. Knowledge</p>
                                            <p class="text-question">1. How well does the student recall basic concepts?</p>
                                            <ul class="list-answeres">
                                                @if(isset($tutorReport->knowledge))
                                                    <li>
                                                        <div class="form-check checked">
                                                            <input class="form-check-input"
                                                                   value="{{$tutorReport->knowledge}}" type="radio"
                                                                   checked="">
                                                            <label class="form-check-label">
                                                                {{$tutorReport->knowledge}}
                                                            </label>
                                                        </div>
                                                    </li><br/>
                                                @endif

                                                <p>2. How well does the student share their ideas on the topics under discussion?</p>
                                                @if(isset($tutorReport->knowledge2))
                                                    <li>
                                                        <div class="form-check checked">
                                                            <input class="form-check-input"
                                                                   value="{{$tutorReport->knowledge2}}" type="radio"
                                                                   checked="">
                                                            <label class="form-check-label">
                                                                {{$tutorReport->knowledge2}}
                                                            </label>
                                                        </div>
                                                    </li><br/>
                                                @endif


                                                <p class="item-title item-ans">B. Understanding</p>
                                                <p>1. How well does the student explain the basic concepts?</p>
                                                @if(isset($tutorReport->understanding))
                                                    <li>
                                                        <div class="form-check checked">
                                                            <input class="form-check-input"
                                                                   value="{{$tutorReport->understanding}}" type="radio"
                                                                   checked="">
                                                            <label class="form-check-label">
                                                                {{$tutorReport->understanding}}
                                                            </label>
                                                        </div>
                                                    </li><br/>
                                                @endif

                                                <p>2. How well does the student apply learned concepts to solve problems or answer questions?</p>
                                                @if(isset($tutorReport->understanding2))
                                                    <li>
                                                        <div class="form-check checked">
                                                            <input class="form-check-input"
                                                                   value="{{$tutorReport->understanding2}}" type="radio"
                                                                   checked="">
                                                            <label class="form-check-label">
                                                                {{$tutorReport->understanding2}}
                                                            </label>
                                                        </div>
                                                    </li><br/>
                                                @endif


                                                <p class="item-title item-ans">C. Critical Thinking</p>
                                                <p>1. How well does the student solve different types of questions with
                                                    minimal guidance?</p>
                                                @if(isset($tutorReport->criticalThinking))
                                                    <li>
                                                        <div class="form-check checked">
                                                            <input class="form-check-input"
                                                                   value="{{$tutorReport->criticalThinking}}"
                                                                   type="radio"
                                                                   checked="">
                                                            <label class="form-check-label">
                                                                {{$tutorReport->criticalThinking}}
                                                            </label>
                                                        </div>
                                                    </li><br/>
                                                @endif

                                                <p>2. How well is the is the student able to answer questions using a variety of methods and concepts? </p>
                                                @if(isset($tutorReport->criticalThinking2))
                                                    <li>
                                                        <div class="form-check checked">
                                                            <input class="form-check-input"
                                                                   value="{{$tutorReport->criticalThinking2}}"
                                                                   type="radio"
                                                                   checked="">
                                                            <label class="form-check-label">
                                                                {{$tutorReport->criticalThinking2}}
                                                            </label>
                                                        </div>
                                                    </li><br/>
                                                @endif


                                                <p class="item-title item-ans">D. Observation</p>
                                                <p>1. What is the student's learning style? Do you believe it is effective for them?</p>
                                                @if(isset($tutorReport->observation))
                                                    <li>
                                                        <div class="form-check checked">
                                                            <input class="form-check-input"
                                                                   value="{{$tutorReport->observation}}"
                                                                   type="radio"
                                                                   checked="">
                                                            <label class="form-check-label">
                                                                {{$tutorReport->observation}}
                                                            </label>
                                                        </div>
                                                    </li><br/>
                                                @endif


                                                    <p class="item-title item-ans">E. Additional Assessment</p>
                                                    <p>1. What is the current score for the first assessement?
                                                        out of 10</p>
                                                    @if(isset($tutorReport->additionalAssisment))
                                                        <li>
                                                            <div class="form-check checked">
                                                                <label class="form-check-label">
                                                                    {{$tutorReport->additionalAssisment}}/10
                                                                </label>
                                                            </div>
                                                        </li><br/>
                                                    @endif
                                                    <p>2. This is the tutoring plan designed to provide the most effective support for the student.</p>
                                                    @if(isset($tutorReport->plan))
                                                        <li>
                                                            <div class="form-check checked">
                                                                <input class="form-check-input"
                                                                       value="{{$tutorReport->plan}}"
                                                                       type="radio"
                                                                       checked="">
                                                                <label class="form-check-label">
                                                                    {{$tutorReport->plan}}
                                                                </label>
                                                            </div>
                                                        </li><br/>
                                                    @endif
                                            </ul>
                                            <p></p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
