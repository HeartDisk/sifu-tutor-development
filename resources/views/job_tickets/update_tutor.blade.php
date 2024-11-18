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
                                    Update Ticket Status
                                </h2>
                                <nav>
                                    <ol class="breadcrumb breadcrumb-arrow mb-0">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item"><a href="#">Job Ticket</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Update Ticket Status</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="nk-block">
                        <div class="card card-gutter-md">
                          <div class="card-body">
                            <form action="{{route('updateTicketTutor')}}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{$data->id}}">
                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <label for="postalcode" class="form-label">Status</label>
                                        <div class="form-control-wrap">
                                            <select class="form-control" id="classType" name="ticket_tutor_status">
                                              {{--<option value="">Select status</option>--}}
                                              <option selected value="discontinued">Discontinue</option>
                                            </select>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-3"></div>

                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <label for="postalcode" class="form-label">Reason</label>
                                        <div class="form-control-wrap">
                                            <textarea class="form-control" rows="6" cols="10" name="reason"></textarea>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-3"></div>

                                    <div class="col-md-3"></div>
                                    <div class="col-md-2">
                                        <div class="input-group input-group-md">
                                            <input type="submit" class="btn btn-success" aria-label="Sizing example input" value="Submit" aria-describedby="inputGroup-sizing-sm">
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
@endsection