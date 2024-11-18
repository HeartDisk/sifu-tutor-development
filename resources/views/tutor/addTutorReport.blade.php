@extends('layouts.main')

@section('content')
<style>
    label{
        font-weight:bold;
    }
</style>
<br/><br/>
<div class="nk-content">
            <div class="fluid-container">
              <div class="nk-content-inner">
                <div class="nk-content-body">
                  <div class="nk-block-head">
                    <div class="nk-block-head-between flex-wrap gap g-2">
                      <div class="nk-block-head-content">
                        <h2 class="nk-block-title">
                        SUBMIT TUTOR REPORT</h1>
                        <nav>
                          <ol class="breadcrumb breadcrumb-arrow mb-0">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Submit Tutor Report</a></li>
                          </ol>
                        </nav>
                      </div>
                      
                  </div>
                  <div class="nk-block">
                    <div class="card">
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
                        <div style="height:700px">
                            
                            <form method="POST" action="{{route('submitTutorReport')}}" enctype="multipart/form-data">
                                @csrf
                            <div style="padding:20px;" class="row">
                              <div class="col-md-12">
                                  <label>Tutor</label>
                                  <select class="js-select" data-search="true" data-sort="false" id="tutorID" name="tutorID" >
                                      @foreach($tutors as $rowTutor)
                                        <option value="{{$rowTutor->id}}">{{$rowTutor->full_name}}</option>
                                      @endforeach
                                      
                                  </select>
                              </div>
                             </div>
                             <div style="padding:20px;" class="row">
                              <div class="col-md-6">
                                  <label>Type Report</label>
                                    <select required class="js-select" data-search="true" data-sort="false" id="reportType" name="reportType">
                                      <option selected="selected">Evaluation Report</option>
                                      <option>Progressive/Attendance Report</option>
                                    </select>
                              </div>
                              
                              <div class="col-md-6">
                                  <label>Students</label>
                                  <select class="js-select" data-search="true" data-sort="false" id="studentID" name="studentID" >
                                      @foreach($students as $rowStudent)
                                        <option value="{{$rowStudent->id}}">{{$rowStudent->full_name}}</option>
                                      @endforeach
                                  </select>
                              </div>
                              </div>
                             <div style="padding:20px;" class="row">
                              <div class="col-md-6">
                                  <label>First Class Date</label>
                                  <input type="date" required name="date" name="date" class="form-control"/>
                              </div>
                              
                              <div class="col-md-6">
                                  <label>Evaluation Form</label>
                                  <input type="file" required name="reportFile" class="form-control"/>
                                  Supported Extensions: doc,docx,pdf,jpg,jpeg,png,zip
                              </div>
                              </div>
                             <div style="padding:20px;" class="row">
                              
                              <div class="col-md-12">
                                  <label>Remarks</label>
                                  <textarea required class="form-control" cols="6" name="remarks" rows="6"></textarea>
                              </div>
                              
                              </div>
                                <div style="padding:20px;" class="row">
                                  <div class="col-md-2">
                                        <div class="btn-group" role="group">
                                            <input type="submit" class="btn btn-primary d-none d-md-inline-flex" value="Submit Tutor Report"/>
                                        </div>
                                  </div>
                                </div>
                            </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

@endsection

