@extends('layouts.main')

@section('content')

    <div class="nk-content">
        <div class="fluid-container">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head">
                        <div class="nk-block-head-between flex-wrap gap g-2">
                            <div class="nk-block-head-content">
                                <h2 class="nk-block-title">
                                    Tutor Detail</h1>
                                    <nav class="mb-4">
                                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                                            <li class="breadcrumb-item"><a href="#">Tutor Detail</a></li>
                                        </ol>
                                    </nav>
                            </div>

                        </div>
                        <div class="nk-block">
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
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h2>{{isset($tutor->full_name)?$tutor->full_name:""}}
                                                - {{isset($tutor->uid)?$tutor->uid:""}}</h2>
                                        </div>
                                    </div>

                                </div>


                                <div class="card-body">
                                    <div class="row g-3 mb-3">
                                        <h3 style="color:#fff; border-radius:5px;background-color: #2e314a; box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;  padding:10px; ">
                                            Tutor Information</h3>
                                    </div>
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

                                        <div class="row g-3">
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label for="firstname" class="form-label">Tutor Id</label>
                                                    <div class="form-control-wrap">
                                                        {{isset($tutor->uid)?$tutor->uid:""}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label for="firstname" class="form-label">Status</label>
                                                    <div class="form-control-wrap">
                                                        Active
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label for="firstname" class="form-label">Start Working Date</label>
                                                    <div class="form-control-wrap">
                                                        {{isset($tutor)?$tutor->start_date:""}}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label for="firstname" class="form-label">Full Name</label>
                                                    <div class="form-control-wrap">
                                                        {{isset($tutor->full_name)?$tutor->full_name:""}}
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label for="firstname" class="form-label">Gender</label>
                                                    <div class="form-control-wrap">
                                                        {{isset($tutor->gender)?:""}}
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label for="firstname" class="form-label">Age</label>
                                                    <div class="form-control-wrap">
                                                        {{isset($tutor->age)?:""}}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="firstname" class="form-label">Dob</label>
                                                    <div class="form-control-wrap">
                                                        {{isset($tutor->dob)?:""}}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="customerInfo">
                                                <div class="row g-3 mb-3">
                                                    <h3 style="color:#fff; border-radius:5px;background-color: #2e314a; box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;  padding:10px; ">

                                                        <div class="existingCustomertHeading"> CUSTOMER / PARENT
                                                            INFORMATION
                                                        </div>
                                                    </h3>
                                                </div>
                                                <div class="row existingCustomer">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="firstname" class="form-label">Nric</label>
                                                            <div style="padding:5px; " class="form-control-wrap">

                                                                {{isset($tutor->nric)?:""}}

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="firstname" class="form-label">Marital
                                                                Status</label>
                                                            <div style="padding:5px; " class="form-control-wrap">
                                                                {{isset($tutor->marital_status)?:""}}
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="email" class="form-label">Email address</label>
                                                            <div style="padding:5px; " class="form-control-wrap">
                                                                {{isset($tutor->email)?:""}}

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="email" class="form-label">Phone Number</label>
                                                            <div style="padding:5px; " class="form-control-wrap">
                                                                {{isset($tutor->phoneNumber)?$tutor->phoneNumber:""}}

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <p style="font-size:1.5em;">
                                                                <a href="https://wa.me/{{isset($tutor->phoneNumber)?:""}}"
                                                                   target="_blank"
                                                                   style="margin-right:5px;"><strong><span
                                                                            class="fa fa-whatsapp text-success"></span></strong></a>
                                                                <a href="tel:{{isset($tutor->phoneNumber)?$tutor->phoneNumber:""}}"><span
                                                                        class="fa fa-phone"></span></a>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="city" class="form-label">Shirt Size</label>
                                                            <div style="padding:5px; " class="form-control-wrap">
                                                                {{isset($tutor->shirt_size)?$tutor->shirt_size:""}}

                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="city" class="form-label">BankName</label>
                                                            <div style="padding:5px;" class="form-control-wrap">

                                                                {{isset($tutor->bank_name)?:""}}

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="postalcode" class="form-label">Bank Account
                                                                No.</label>
                                                            <div style="padding:5px; " class="form-control-wrap">
                                                                {{isset($tutor->bank_account_number)?:""}}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="input-box">
                                                            <label for="email" class="form-label">Address</label>
                                                            <div class="form-group">

                                                                <div style="padding:5px; " class="form-control-wrap">

                                                                    <span><strong>{{isset($tutor->street_address1)?:""}} {{isset($tutor->street_address2)?:""}} {{isset($tutor->city)?:""}}</strong>,</span><br>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div style="display:none;" class="row newCustomer">
                                                        <div class=" col-lg-2">
                                                            <div class="form-group">
                                                                <label for="city" class="form-label">Remark</label>
                                                                <div class="form-control-wrap">
                                                                    {{isset($tutor->remark)?:""}}

                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class=" col-lg-2">
                                                            <h3>Commitment Fee</h3>
                                                            <div class="mb-5">
                                                                <div class="row row-details">
                                                                    <div class="col-md-3 details-item">
                                                                        <p class="item-title">Payment Date</p>
                                                                        <p><strong>15/05/2023</strong></p>
                                                                    </div>
                                                                    <div class="col-md-3 details-item">
                                                                        <p class="item-title">Receiving Account</p>
                                                                        <p><strong>Payment Gateway - BillPlz Sdn
                                                                                Bhd</strong></p>
                                                                    </div>
                                                                    <div class="col-md-3 details-item">
                                                                        <p class="item-title"><a
                                                                                href="/Files/StudentFees/22-05-2023-10-56-180149.jpeg"
                                                                                target="_blank">View Attachment</a></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>
{{--                                                    <div class="row g-3 mb-3">--}}
{{--                                                        <h3 style="color:#fff; border-radius:5px;background-color: #2e314a; box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;  padding:10px; ">--}}
{{--                                                            <div class="existingStudentHeading">Subject Chosen</div>--}}
{{--                                                        </h3>--}}
{{--                                                    </div>--}}

{{--                                                    <ul>--}}
{{--                                                        @foreach($tutor_subjects as $row_tutor_subjects)--}}
{{--                                                            @php--}}
{{--                                                                $subjectName = DB::table('products')->where('id','=',$row_tutor_subjects->subject)->first();--}}
{{--                                                            @endphp--}}
{{--                                                            <li>{{$subjectName->name}}</li>--}}

{{--                                                        @endforeach--}}


{{--                                                    </ul>--}}
{{--                                                    <div class="row row-details pb-5">--}}
{{--                                                        <div class="col-md-3">--}}
{{--                                                            <a class="btn btn-light waves-effect waves- mt-4"--}}
{{--                                                               href="{{route('TutorFinder')}}">Back</a>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}


                                                </div>


                                            </div>

                                            <div class="row g-3">

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

