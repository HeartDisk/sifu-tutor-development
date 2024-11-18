@extends('layouts.main')

@section('content')


<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
<div class="fluid-container">
    <br/><br/><br/>
    <h2 class="text-center">Mobile Notification Form</h2>
	<div class="row justify-content-center">
		<div class="col-12 col-md-8 col-lg-6 pb-5">


                    <!--Form with header-->

                    <form action="{{route('sendMobileNotification')}}" method="post">
                        @csrf
                        <div class="card border-primary rounded-0">
                            <div class="card-header p-0">
                                <div class="bg-info text-white text-center py-2">
                                    <h3><i class="fa fa-envelope"></i> Send Notification</h3>
                                    <p class="m-0">To all Tutors Devices</p>
                                </div>
                            </div>
                            <div class="card-body p-3">

                                <!--Body-->
                                <!--<div class="form-group">-->
                                <!--    <div class="input-group mb-2">-->
                                <!--        <div class="input-group-prepend">-->
                                <!--            <div class="input-group-text"><i class="fa fa-user text-info"></i></div>-->
                                <!--        </div>-->
                                <!--        <input type="text" class="form-control" id="device_token" name="device_token" placeholder="Device Token" required>-->
                                <!--    </div>-->
                                <!--</div>-->
                                
                                <div class="form-group">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-user text-info"></i></div>
                                        </div>
                                        <input type="text" class="form-control" id="title" name="title" placeholder="Title" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-comment text-info"></i></div>
                                        </div>
                                        <textarea class="form-control" placeholder="Message" id="message" name="message"  required></textarea>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <input type="submit" value="Send Mobile Notification" class="btn btn-info btn-block rounded-0 py-2">
                                </div>
                            </div>

                        </div>
                    </form>
                    <!--Form with header-->


                </div>
	</div>
</div>

@endsection
