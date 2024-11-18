@extends('layouts.main')

@section('content')

        <div class="nk-content">
            <div class="fluid-container">
              <div class="nk-content-inner">
                <div class="nk-content-body">
                  <div class="nk-block-head">
                    <div class="nk-block-head">
                      <div class="nk-block-head-between flex-wrap gap g-2 align-items-center">
                        <div class="nk-block-head-content">
                          <div class="d-flex flex-column flex-md-row align-items-md-center">
                            <div class="mt-3 mt-md-0 ms-md-3">
                              <h3 class="title mb-1">Add Notification</h3>
                            </div>
                          </div>
                        </div>
                        
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
                            <div class="card">
            <div class="card-body">

            <form method="POST" action="{{route('submitNews')}}" enctype="multipart/form-data">
                              @csrf
                            <div class="row g-3">
                            <div class="col-lg-4">
                                <div class="form-group">
                                <label class="" for="PageToOpen">Page To Open</label>
                                    <select class="form-control valid" data-val="true" data-val-length="The field Page To Open must be a string with a maximum length of 150." data-val-length-max="150" id="PageToOpen" name="PageToOpen" aria-invalid="false" aria-describedby="PageToOpen-error"><option>Dashboard</option>
<option>Profile</option>
<option>Cumulative Commission</option>
<option>Payment History</option>
<option>Inbox</option>
<option>Job Ticket List</option>
<option>Class Schedule List</option>
<option>Submission History</option>
<option>Student List</option>
<option>Pending Actions</option>
<option>Faq</option>
</select>
                                </div>
                              </div>
                               <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="image" class="form-label">Subject</label>
                                  <div class="form-control-wrap"><input type="text" class="form-control" name="subject" id="subject"></div>
                                </div>
                              </div>
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="preheader" class="form-label">Message</label>
                                  <div class="form-control-wrap"><input type="text" class="form-control" name="message" id="message"></div>
                                </div>
                              </div>
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="status" class="form-label">Push Time (24hrs format)</label>
                                  <div class="form-control-wrap">
                                        <input type="time" class="form-control" name="PushTime" id="PushTime">
                                  </div>
                                </div>
                              </div>

                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="status" class="form-label">Push Type</label>
                                  <div class="form-control-wrap">
                                  <select class="form-control" data-val="true" data-val-length="The field Push Type must be a string with a maximum length of 50." data-val-length-max="50" data-val-required="The Push Type field is required." id="PushType" name="PushType">
                                    <option selected="selected">One-Time</option>
                                    <option>Recurrence</option>
                                    </select>
                                  </div>
                                </div>
                              </div>

                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="status" class="form-label">Push Date</label>
                                  <div class="form-control-wrap">
                                        <input type="date" class="form-control" name="PushDate" id="PushDate">
                                  </div>
                                </div>
                              </div>

                              <div class="col-lg-12">
                                <div class="form-group">
                                  <label for="body" class="form-label">Remark</label>
                                    <div class="form-control-wrap">
                                        <textarea class="form-control" name="Remark" id="Remark"></textarea>
                                    </div>
                                </div>
                              </div>

                            </div>
                            <div class="col-lg-12"><button class="btn btn-primary" type="submit">Submit</button></div>
                          </form>
            </div>
        </div>
                          <!--<form method="POST" action="{{route('submitNotification')}}">-->
                          <!--    @csrf-->
                          <!--  <div class="row g-3">-->
                            
                          <!--  <div class="col-lg-12">-->
                          <!--      <div class="form-group">-->
                          <!--        <label for="firstname" class="form-label">Subject</label>-->
                          <!--        <div class="form-control-wrap"><input type="text" class="form-control" name="subject" id="subject"></div>-->
                          <!--      </div>-->
                          <!--    </div>-->
                              
                          <!--     <div class="col-lg-4">-->
                          <!--      <div class="form-group">-->
                          <!--        <label for="firstname" class="form-label">Image</label>-->
                          <!--        <div class="form-control-wrap"><input type="file" class="form-control" name="image" id="image"></div>-->
                          <!--      </div>-->
                          <!--    </div>-->
                              
                          <!--    <div class="col-lg-4">-->
                          <!--      <div class="form-group">-->
                          <!--        <label for="firstname" class="form-label">Preheader</label>-->
                          <!--        <div class="form-control-wrap"><input type="text" class="form-control" name="preheader" id="preheader"></div>-->
                          <!--      </div>-->
                          <!--    </div>-->
                          <!--    <div class="col-lg-4">-->
                          <!--      <div class="form-group">-->
                          <!--        <label for="country" class="form-label">Status</label>-->
                          <!--        <div class="form-control-wrap">-->
                          <!--          <select class="js-select" id="status" data-search="true" name="status" data-sort="false">-->
                          <!--            <option value="Published" selected="selected">Published</option>-->
                          <!--            <option value="UnPublished">UnPublished</option>-->
                          <!--          </select>-->
                          <!--        </div>-->
                          <!--      </div>-->
                          <!--    </div>-->
                              
                              
                          <!--    <div class="col-lg-12">-->
                          <!--      <div class="form-group">-->
                          <!--        <label for="firstname" class="form-label">Body</label>-->
                          <!--          <div class="form-control-wrap">-->
                          <!--              <textarea class="form-control" name="content" id="content"></textarea>-->
                          <!--          </div>-->
                          <!--      </div>-->
                          <!--    </div>-->
                              
                              

                          <!--  </div>-->
                            
                            
                          <!--  <div class="col-lg-12"><button class="btn btn-primary" type="submit">Submit</button></div>-->
                          <!--</form>-->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

@endsection
