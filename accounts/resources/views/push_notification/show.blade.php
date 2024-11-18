@extends('layouts.main')

@section('content')

<div class="nk-content">
    <div class="fluid-container">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head">
                    <div class="nk-block-head-between flex-wrap gap g-2 align-items-center">
                        <div class="nk-block-head-content">
                            <div class="d-flex flex-column flex-md-row align-items-md-center">
                                <div class="mt-3 mt-md-0 ms-md-3">
                                    <h3 class="title mb-1">Push Notification</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="nk-block">
                    <div class="card card-gutter-md">
                        <div class="card-body">
                            <div class="bio-block">
                                <div class="row g-3">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="subject" class="form-label">Page</label>
                                            <div class="form-control-wrap">
                                                {{ $notification->page }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="preheader" class="form-label">Subject</label>
                                            {{ $notification->subject }}
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="preheader" class="form-label">Message</label>
                                            {{ $notification->message }}
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="preheader" class="form-label">Time</label>
                                            {{ $notification->time }}
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="preheader" class="form-label">Type</label>
                                            {{ $notification->type }}
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="preheader" class="form-label">Date</label>
                                            {{ $notification->date }}
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="preheader" class="form-label">Remark</label>
                                            {{ $notification->remark }}
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
</div>

@endsection
