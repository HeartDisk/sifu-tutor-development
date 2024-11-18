@extends('layouts.main')
@section('content')
    <div class="nk-content sifu-view-page">
        <div class="fluid-container">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head">
                        <div class="nk-block-head-between flex-wrap gap g-2">
                            <div class="nk-block-head-content">
                                <h2 class="nk-block-title">Progress Report</h2>
                                <nav>
                                    <ol class="breadcrumb breadcrumb-arrow mb-0">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item"><a href="#">Student Progress Report</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Progress Report</li>
                                    </ol>
                                </nav>
                            </div>
                            <div class="nk-block-head-content">
                                <ul class="d-flex">
                                    <li><a href="TutorReportsV2DownloadReport/38394" class="btn btn-primary"
                                           title="View Report" target="_blank"><i class="fa fa-download"></i>
                                            Download</a></li>
                                </ul>
                            </div>
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
                                            <p class="item-title">TutorId</p>
                                            <p><strong>{{$tutorReport->tutorID}}</strong></p>
                                        </div>
                                        <div class="col-md-3 details-item">
                                            <p class="item-title">Tutor Name</p>
                                            <p><strong>{{$tutorReport->tutorName}}</strong></p>
                                        </div>
                                        <div class="col-md-2 details-item">
                                            <p class="item-title">StudentId</p>
                                            <p><strong>{{$tutorReport->studentID}}</strong></p>
                                        </div>
                                        <div class="col-md-3 details-item">
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
                                            <p class="item-title item-ans">A. Observation</p>
                                            <p class="text-question">1. Did you (tutor) hold or carried out any form of
                                                examination/test/quiz for the student within this 3 months?</p>
                                            <ul class="list-answeres">
                                                @if(isset($tutorReport->observation))
                                                    <li>
                                                        <div class="form-check checked">
                                                            <input class="form-check-input"
                                                                   value="{{$tutorReport->observation}}" type="radio"
                                                                   checked="">
                                                            <label class="form-check-label">
                                                                {{$tutorReport->observation}}
                                                            </label>
                                                        </div>
                                                    </li><br/>
                                                @endif

                                                <p>2. What is the student's learning style?</p>
                                                @if(isset($tutorReport->observation2))
                                                    <li>
                                                        <div class="form-check checked">
                                                            <input class="form-check-input"
                                                                   value="{{$tutorReport->observation2}}" type="radio"
                                                                   checked="">
                                                            <label class="form-check-label">
                                                                {{$tutorReport->observation2}}
                                                            </label>
                                                        </div>
                                                    </li><br/>
                                                @endif

                                                <p>3. What significant improvement do you see in the student's
                                                    performance compared to before?</p>
                                                @if(isset($tutorReport->observation3))
                                                    <li>
                                                        <div class="form-check checked">
                                                            <label class="form-check-label">
                                                                {{$tutorReport->observation3}}
                                                            </label>
                                                        </div>
                                                    </li><br/>
                                                @endif

                                                <p>4. Please suggest the parts that the student should improve and focus
                                                    on?</p>
                                                @if(isset($tutorReport->observation4))
                                                    <li>
                                                        <div class="form-check checked">
                                                            <label class="form-check-label">
                                                                {{$tutorReport->observation4}}
                                                            </label>
                                                        </div>
                                                    </li><br/>
                                                @endif

                                                <p>5. Please elaborate your plans for the student in 3 months' time from
                                                    now?</p>
                                                @if(isset($tutorReport->observation5))
                                                    <li>
                                                        <div class="form-check checked">
                                                            <label class="form-check-label">
                                                                {{$tutorReport->observation5}}
                                                            </label>
                                                        </div>
                                                    </li><br/>
                                                @endif

                                                <p>5. Comment (Additional)</p>
                                                @if(isset($tutorReport->observation6))
                                                    <li>
                                                        <div class="form-check checked">
                                                            <label class="form-check-label">
                                                                {{$tutorReport->observation6}}
                                                            </label>
                                                        </div>
                                                    </li><br/>
                                                @endif


                                                <p class="item-title item-ans">B. Performance</p>
                                                <p>1. How well does the student understand this subject?</p>
                                                @if(isset($tutorReport->observation))
                                                    <li>
                                                        <div class="form-check checked">
                                                            <input class="form-check-input"
                                                                   value="{{$tutorReport->observation}}" type="radio"
                                                                   checked="">
                                                            <label class="form-check-label">
                                                                {{$tutorReport->observation}}
                                                            </label>
                                                        </div>
                                                    </li><br/>
                                                @endif

                                                <p>2. How well the student’s performance during these 3 months ?</p>
                                                @if(isset($tutorReport->observation2))
                                                    <li>
                                                        <div class="form-check checked">
                                                            <input class="form-check-input"
                                                                   value="{{$tutorReport->observation2}}" type="radio"
                                                                   checked="">
                                                            <label class="form-check-label">
                                                                {{$tutorReport->observation2}}
                                                            </label>
                                                        </div>
                                                    </li><br/>
                                                @endif

                                                <p>3. How well student’s participates in learning session?</p>
                                                @if(isset($tutorReport->observation3))
                                                    <li>
                                                        <div class="form-check checked">
                                                            <input class="form-check-input"
                                                                   value="{{$tutorReport->observation3}}" type="radio"
                                                                   checked="">
                                                            <label class="form-check-label">
                                                                {{$tutorReport->observation3}}
                                                            </label>
                                                        </div>
                                                    </li><br/>
                                                @endif

                                                <p>4. How well student answers/explains/elaborates questions given by
                                                    tutor?</p>
                                                @if(isset($tutorReport->observation4))
                                                    <li>
                                                        <div class="form-check checked">
                                                            <input class="form-check-input"
                                                                   value="{{$tutorReport->observation4}}" type="radio"
                                                                   checked="">
                                                            <label class="form-check-label">
                                                                {{$tutorReport->observation4}}
                                                            </label>
                                                        </div>
                                                    </li><br/>
                                                @endif

                                                <p>5. How would you rate the student's level of improvement over the
                                                    past month?</p>
                                                @if(isset($tutorReport->observation5))
                                                    <li>
                                                        <div class="form-check checked">
                                                            <input class="form-check-input"
                                                                   value="{{$tutorReport->observation5}}" type="radio"
                                                                   checked="">
                                                            <label class="form-check-label">
                                                                {{$tutorReport->observation5}}
                                                            </label>
                                                        </div>
                                                    </li><br/>
                                                @endif

                                                <p>6. Comment (Additional)</p>
                                                @if(isset($tutorReport->observation6))
                                                    <li>
                                                        <div class="form-check checked">
                                                            <input class="form-check-input"
                                                                   value="{{$tutorReport->observation6}}" type="radio"
                                                                   checked="">
                                                            <label class="form-check-label">
                                                                {{$tutorReport->observation6}}
                                                            </label>
                                                        </div>
                                                    </li><br/>
                                                @endif


                                                <p class="item-title item-ans">C. Attitude</p>
                                                <p>1. How well student’s attendance for 3 months?</p>
                                                @if(isset($tutorReport->attitude))
                                                    <li>
                                                        <div class="form-check checked">
                                                            <input class="form-check-input"
                                                                   value="{{$tutorReport->attitude}}" type="radio"
                                                                   checked="">
                                                            <label class="form-check-label">
                                                                {{$tutorReport->attitude}}
                                                            </label>
                                                        </div>
                                                    </li><br/>
                                                @endif

                                                <p>2. How well do you interact/communicate with student during/after
                                                    class?</p>
                                                @if(isset($tutorReport->attitude2))
                                                    <li>
                                                        <div class="form-check checked">
                                                            <input class="form-check-input"
                                                                   value="{{$tutorReport->attitude2}}" type="radio"
                                                                   checked="">
                                                            <label class="form-check-label">
                                                                {{$tutorReport->attitude2}}
                                                            </label>
                                                        </div>
                                                    </li><br/>
                                                @endif

                                                <p>3. How well the student manages their task given ? </p>
                                                @if(isset($tutorReport->attitude3))
                                                    <li>
                                                        <div class="form-check checked">
                                                            <input class="form-check-input"
                                                                   value="{{$tutorReport->attitude3}}" type="radio"
                                                                   checked="">
                                                            <label class="form-check-label">
                                                                {{$tutorReport->attitude3}}
                                                            </label>
                                                        </div>
                                                    </li><br/>
                                                @endif

                                                <p>4. How well student's willingness to learn ?</p>
                                                @if(isset($tutorReport->attitude4))
                                                    <li>
                                                        <div class="form-check checked">
                                                            <input class="form-check-input"
                                                                   value="{{$tutorReport->attitude4}}" type="radio"
                                                                   checked="">
                                                            <label class="form-check-label">
                                                                {{$tutorReport->attitude4}}
                                                            </label>
                                                        </div>
                                                    </li><br/>
                                                @endif

                                                <p>5. What are the student's interests towards the subject?</p>
                                                @if(isset($tutorReport->attitude5))
                                                    <li>
                                                        <div class="form-check checked">
                                                            <input class="form-check-input"
                                                                   value="{{$tutorReport->attitude5}}" type="radio"
                                                                   checked="">
                                                            <label class="form-check-label">
                                                                {{$tutorReport->attitude5}}
                                                            </label>
                                                        </div>
                                                    </li><br/>
                                                @endif

                                                <p>6. Comment (Additional)</p>
                                                @if(isset($tutorReport->attitude6))
                                                    <li>
                                                        <div class="form-check checked">
                                                            <input class="form-check-input"
                                                                   value="{{$tutorReport->attitude6}}" type="radio"
                                                                   checked="">
                                                            <label class="form-check-label">
                                                                {{$tutorReport->attitude6}}
                                                            </label>
                                                        </div>
                                                    </li><br/>
                                                @endif

                                                <p class="item-title item-ans">D. Result</p>
                                                <p>1. How well does the student performance in quizzes/test?</p>
                                                @if(isset($tutorReport->result))
                                                    <li>
                                                        <div class="form-check checked">
                                                            <input class="form-check-input"
                                                                   value="{{$tutorReport->result}}" type="radio"
                                                                   checked="">
                                                            <label class="form-check-label">
                                                                {{$tutorReport->result}}
                                                            </label>
                                                        </div>
                                                    </li><br/>
                                                @endif

                                                <p>2. How well the student prepares for test and assignment?</p>
                                                @if(isset($tutorReport->result2))
                                                    <li>
                                                        <div class="form-check checked">
                                                            <input class="form-check-input"
                                                                   value="{{$tutorReport->result2}}" type="radio"
                                                                   checked="">
                                                            <label class="form-check-label">
                                                                {{$tutorReport->result2}}
                                                            </label>
                                                        </div>
                                                    </li><br/>
                                                @endif

                                                <p>3. How is the student’s test score at school? </p>
                                                @if(isset($tutorReport->result3))
                                                    <li>
                                                        <div class="form-check checked">
                                                            <input class="form-check-input"
                                                                   value="{{$tutorReport->result3}}" type="radio"
                                                                   checked="">
                                                            <label class="form-check-label">
                                                                {{$tutorReport->result3}}
                                                            </label>
                                                        </div>
                                                    </li><br/>
                                                @endif

                                                <p>4. Comment (Additional)</p>
                                                @if(isset($tutorReport->result4))
                                                    <li>
                                                        <div class="form-check checked">
                                                            <input class="form-check-input"
                                                                   value="{{$tutorReport->result4}}" type="radio"
                                                                   checked="">
                                                            <label class="form-check-label">
                                                                {{$tutorReport->result4}}
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
