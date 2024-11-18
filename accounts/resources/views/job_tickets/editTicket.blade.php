@extends('layouts.main')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>


@section('content')

    <style>
        .form-control:not([type=file]):read-only {
            background-color: #fff !important;
        }

        .existingCustomer, .existingStudent {
            font-size: 21px;
            font-weight: bold;

        }
    </style>
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
                                            <h3 class="title mb-1">Edit Job Ticket</h3>
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
                                    <form method="POST" action="{{route('submitEditJobTicket')}}"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="jobTicketID" id="ticketIDD"
                                               value="{{$singleTicket->id}}"/>


                                        <div class="row g-3">
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="firstname" class="form-label">Registration Date</label>
                                                    <div class="form-control-wrap">
                                                        {{$singleTicket->register_date}}
                                                    </div>
{{--                                                    <div class="form-control-wrap"><input required--}}
{{--                                                                                          value="{{$singleTicket->register_date}}"--}}
{{--                                                                                          type="date"--}}
{{--                                                                                          name="registration_date"--}}
{{--                                                                                          class="form-control"--}}
{{--                                                                                          id="registrationDate"></div>--}}
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="firstname" class="form-label">Admin Incharge</label>
                                                    <div class="form-control-wrap">
                                                        @php
                                                            $staffs = DB::table('staffs')->get();
                                                            $selectedStaff = DB::table('staffs')->where('id','=',$singleTicket->admin_charge)->first();
                                                        @endphp
                                                        <select class="js-select" data-search="true" data-sort="false"
                                                                id="inCharge" name="inCharge">
                                                            <option selected
                                                                    value="{{$singleTicket->admin_charge}}">{{$selectedStaff->full_name}}</option>
                                                            @foreach($staffs as $rowStaff)

                                                                <option
                                                                    value="{{$rowStaff->id}}">{{$rowStaff->full_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
{{--                                            <div class="col-lg-3">--}}
{{--                                                <div class="form-group">--}}
{{--                                                    <label for="firstname" class="form-label">Service</label>--}}
{{--                                                    <div class="form-control-wrap">--}}
{{--                                                        <select class="form-control" name="service">--}}
{{--                                                            <option--}}
{{--                                                                value="{{$singleTicket->service}}">{{$singleTicket->service}}</option>--}}
{{--                                                            <option value="sifututor">Sifututor</option>--}}
{{--                                                        </select>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="firstname" class="form-label">Select Student</label>
                                                    <div class="form-control-wrap">
                                                        <select id="student_id" required="required" name="student_id"
                                                                class="js-select" data-search="true" data-sort="false">
                                                            <option
                                                                value="{{$singleStudent->id}}">{{$singleStudent->full_name}}</option>
                                                            <option value="newStudent">New Student</option>
                                                            @foreach($students as $rowStudents)
                                                                <option
                                                                    value="{{$rowStudents->id}}">{{$rowStudents->student_id}}
                                                                    - {{$rowStudents->full_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            @if($singleTicket->tutor_id != null)
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="firstname" class="form-label">Select Tutor</label>
                                                        <div class="form-control-wrap">
                                                            @php
                                                                $selectedTutor = DB::table('tutors')->where('id', '=', $singleTicket->tutor_id)->where('status', 'verified')->first();
                                                                   $tutors = DB::table('tutors')->where('status', 'verified')->get();
                                                            @endphp
                                                            <select class="form-control" id="changeTutorID"
                                                                    name="changeTutorID">


                                                                @foreach($tutors as $rowTutor)

                                                                    <option
                                                                        value="{{$rowTutor->id}}" {{$rowTutor->id==$selectedTutor->id?"selected":""}}>{{$rowTutor->full_name}}</option>
                                                                @endforeach

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="existingCustomerDD col-lg-3">
                                                <div class="form-group">
                                                    <label for="firstname" class="form-label">Customer / Parent
                                                        Name</label>
                                                    <div class="form-control-wrap">
                                                        <input type="hidden" class="existingParent_id form-control"
                                                               id="existingParent_id" name="existingParent_id"
                                                               placeholder="Parent ID">
                                                        <input type="text" readonly
                                                               value="{{$singleCustomer->full_name}}"
                                                               class="parentFullName form-control" id="parentFullName"
                                                               name="parentFullName">
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="display:none;" class="newCustomerDD col-lg-5">
                                                <div class="form-group">
                                                    <label for="firstname" class="form-label">Select
                                                        Parent/Customer</label>
                                                    <div class="form-control-wrap">
                                                        <select id="parent_id" required="required" name="parent_id"
                                                                class="js-select" data-search="true" data-sort="false">
                                                            <option
                                                                value="{{$singleCustomer->id}}">{{$singleCustomer->full_name}}</option>
                                                            <option value="newParent">New Customer</option>
                                                            @foreach($customers as $rowCustomers)
                                                                <option
                                                                    value="{{$rowCustomers->id}}">{{$rowCustomers->uid}}
                                                                    - {{$rowCustomers->full_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <!--<div class="col-lg-3">-->
                                            <!--        <div class="form-group">-->
                                            <!--          <label for="firstname" class="form-label"> &nbsp; </label>-->
                                            <!--            <div class="form-control-wrap">-->
                                            <!--              <span class="studentLastClass"> </span>-->
                                            <!--            </div>-->
                                            <!--        </div>-->
                                            <!--    </div>-->

                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="firstname" class="form-label">Class Type</label>
                                                    <div class="form-control-wrap">
{{--                                                        <select class="classType js-select" data-search="true"--}}
{{--                                                                id="classType" data-sort="false" name="classType"--}}
{{--                                                                required>--}}
{{--                                                            <option value="online">Online</option>--}}
{{--                                                            <option value="physical">Physical</option>--}}
{{--                                                        </select>--}}
                                                        {{$singleTicket->mode}}
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row g-3">
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="firstname" class="form-label">Ticket Price</label>
                                                        <div class="form-control-wrap">
                                                            {{$singleTicket->totalPrice}}
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="firstname" class="form-label">Estimate
                                                            Comission</label>
                                                        <div class="form-control-wrap">


                                                            {{$singleTicket->estimate_commission}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="customerInfo">
                                                <div class="row g-3">
                                                    <h3 style="color:#fff; border-radius:5px;background-color: #2e314a; box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;  padding:10px; ">
                                                        <div class="addNewCustomerHeading">CUSTOMER / PARENT
                                                            INFORMATION
                                                        </div>
                                                        <div style="display:none;" class="existingCustomertHeading">
                                                            CUSTOMER / PARENT INFORMATION
                                                        </div>
                                                    </h3>
                                                    <div class="row existingCustomer">
                                                        <div class="col-lg-3">
                                                            <div class="form-group">
                                                                <label for="firstname" class="form-label">Full
                                                                    Name</label>
                                                                <div class="form-control-wrap">
                                                                    {{$singleCustomer->full_name}}
                                                                </div>
{{--                                                                <div style="padding:5px;" class="form-control-wrap">--}}
{{--                                                                    <input type="text"--}}
{{--                                                                           value="{{$singleCustomer->full_name}}"--}}
{{--                                                                           readonly--}}
{{--                                                                           class="form-control customerFullName"/>--}}
{{--                                                                </div>--}}
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3">
                                                            <div class="form-group">
                                                                <label for="firstname" class="form-label">Customer
                                                                    Gender</label>
                                                                <div class="form-control-wrap">
                                                                    {{$singleCustomer->gender}}
                                                                </div>
{{--                                                                <div style="padding:5px; " class="form-control-wrap">--}}

{{--                                                                    <input type="text"--}}
{{--                                                                           value="{{$singleCustomer->gender}}" readonly--}}
{{--                                                                           class="form-control customerGender"/>--}}

{{--                                                                </div>--}}
                                                            </div>
                                                        </div>


                                                        <div class="col-lg-3">
                                                            <div class="form-group">
                                                                <label for="email" class="form-label">Email
                                                                    address</label>
                                                                <div class="form-control-wrap">
                                                                    {{$singleCustomer->email}}
                                                                </div>
{{--                                                                <div style="padding:5px; " class="form-control-wrap">--}}

{{--                                                                    <input type="text"--}}
{{--                                                                           value="{{$singleCustomer->email}}" readonly--}}
{{--                                                                           class="form-control customerEmail"/>--}}

{{--                                                                </div>--}}
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3">
                                                            <div class="form-group">
                                                                <label for="email" class="form-label">Phone
                                                                    Number</label>
                                                                <div class="form-control-wrap">
                                                                    {{$singleCustomer->phone}}
                                                                </div>
{{--                                                                <div style="padding:5px; " class="form-control-wrap">--}}

{{--                                                                    <input type="text" readonly--}}
{{--                                                                           value="{{$singleCustomer->phone}}"--}}
{{--                                                                           class="form-control customerPhone"/>--}}

{{--                                                                </div>--}}
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="email" class="form-label">Whatsapp
                                                                    Number</label>
                                                                <div class="form-control-wrap">
                                                                    {{$singleCustomer->whatsapp}}
                                                                </div>
{{--                                                                <div style="padding:5px; " class="form-co ntrol-wrap">--}}

{{--                                                                    <input type="text" readonly--}}
{{--                                                                           value="{{$singleCustomer->whatsapp}}"--}}
{{--                                                                           class="form-control customerWhatsapp"/>--}}

{{--                                                                </div>--}}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="input-box">
                                                                <label for="email" class="form-label">Address</label>
                                                                <div class="form-control-wrap">
                                                                    {{$singleCustomer->address1}}
                                                                </div>

{{--                                                                <div class="form-group">--}}

{{--                                                                    <div style="padding:5px; "--}}
{{--                                                                         class="form-control-wrap">--}}


{{--                                                                        <input type="text"--}}
{{--                                                                               value="{{$singleCustomer->address1}}"--}}
{{--                                                                               readonly class="form-control address1"/>--}}
{{--                                                                    </div>--}}
{{--                                                                </div>--}}
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="city" class="form-label">State</label>

                                                                <div class="form-control-wrap">
                                                                    {{$singleCustomer->state_name}}
                                                                </div>
{{--                                                                <div style="padding:5px; " class="form-control-wrap">--}}
{{--                                                                    <span class="customerState"></span>--}}
{{--                                                                    <input type="text" readonly--}}
{{--                                                                           value="{{$singleCustomer->state_name}}"--}}
{{--                                                                           class="customerState form-control"--}}
{{--                                                                           name="customerState" id="customerState">--}}
{{--                                                                </div>--}}
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="city" class="form-label">City</label>
                                                                <div class="form-control-wrap">
                                                                    {{$singleCustomer->city_name}}
                                                                </div>
{{--                                                                <div style="padding:5px; " class="form-control-wrap">--}}

{{--                                                                    <input type="text" readonly--}}
{{--                                                                           value="{{$singleCustomer->city_name}}"--}}
{{--                                                                           class="customerCity form-control"--}}
{{--                                                                           name="customerCity">--}}

{{--                                                                </div>--}}
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="postalcode" class="form-label">Postal
                                                                    Code</label>
                                                                <div class="form-control-wrap">
                                                                    {{$singleCustomer->postal_code}}
                                                                </div>
{{--                                                                <div style="padding:5px; " class="form-control-wrap">--}}

{{--                                                                    <input type="text" readonly--}}
{{--                                                                           class="customerPostalCode form-control"--}}
{{--                                                                           value="{{$singleCustomer->postal_code}}"--}}
{{--                                                                           name="customerPostalCode"--}}
{{--                                                                           id="customerPostalCode">--}}
{{--                                                                </div>--}}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div style="display:none;" class="row newCustomer">
                                                        <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="firstname" class="form-label">Full
                                                                    Name</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text"
                                                                           class="customerFullName form-control"
                                                                           id="fullName" name="customerFullName"
                                                                           placeholder="Full name"></div>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="firstname" class="form-label">Customer
                                                                    Gender</label>
                                                                <div class="form-control-wrap">
                                                                    <div class="">
                                                                        <select class="js-select" id="customerGender"
                                                                                name="customerGender" data-search="true"
                                                                                data-sort="false">
                                                                            <option value=""></option>
                                                                            <option value="Male">Male</option>
                                                                            <option value="Female">Female</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="email" class="form-label">Email
                                                                    address</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text"
                                                                           class=" customerEmail form-control"
                                                                           name="customerEmail" id="email"
                                                                           placeholder="Email address">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="email" class="form-label">Phone
                                                                    Number</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text"
                                                                           class=" customerPhone form-control"
                                                                           name="customerPhone" id="mobile_code"
                                                                           placeholder="Customer Phone"></div>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="email" class="form-label">Whatsapp
                                                                    Number</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text"
                                                                           class=" customerWhatsapp form-control"
                                                                           name="customerWhatsapp" id="whatsapp_code"
                                                                           placeholder="Whatsapp Number"></div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-9">
                                                            <div class="input-box">
                                                                <label class="label-text"> Address</label>
                                                                <div class="form-group">
                                                                    <span class="la la-map-marker form-icon"></span>
                                                                    <div class="form-control-wrap">
                                                                        <input type="text"
                                                                               class="newCustomerAddress1 form-control"
                                                                               name="address" id="address"
                                                                               placeholder="Address "></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="margin-top:30px; " class="row g-3">

                                                    <div style="display:none;" class="row newCustomer">
                                                        <div class=" col-lg-2">
                                                            <div class="form-group">
                                                                <label for="city" class="form-label">Latitude</label>
                                                                <div class="form-control-wrap"><input type="text"
                                                                                                      class="customerLatitude form-control"
                                                                                                      name="customerLatitude"
                                                                                                      id="customerLatitude"
                                                                                                      placeholder="Latitude">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class=" col-lg-2">
                                                            <div class="form-group">
                                                                <label for="city" class="form-label">Longitude</label>
                                                                <div class="form-control-wrap"><input type="text"
                                                                                                      class="customerLongitude form-control"
                                                                                                      id="customerLongitude"
                                                                                                      name="customerLongitude"
                                                                                                      placeholder="Longitude">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3">
                                                            <div class="form-group">
                                                                <label for="city" class="form-label">State</label>
                                                                <div class="form-control-wrap">
                                                                    <select class=" js-select customerState"
                                                                            data-search="true" data-sort="false"
                                                                            name="customerState" id="customerState">
                                                                        @php
                                                                            $states = DB::table('states')->get();
                                                                        @endphp
                                                                        @foreach($states as $rowStates)
                                                                            <option
                                                                                value="{{$rowStates->id}}">{{$rowStates->name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="city" class="form-label">City</label>
                                                                <div class="form-control-wrap">
                                                                    <select class="form-control customerCity"
                                                                            data-search="true" data-sort="false"
                                                                            name="customerCity" id="customerCity">

                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="postalcode" class="form-label">Postal
                                                                    Code</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text"
                                                                           class=" customerPostalCode form-control"
                                                                           name="customerPostalcode" id="postalcode"
                                                                           placeholder="Zip code"></div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row g-3">
                                                <h3 style="color:#fff; border-radius:5px;background-color: #2e314a; box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;  padding:10px; ">
                                                    <div style="display:none;" class="addNewStudentHeading"> ADD NEW
                                                        STUDENT INFORMATION
                                                    </div>
                                                    <div class="existingStudentHeading"> STUDENT INFORMATION</div>
                                                </h3>
                                                <div style="display:none" class="row newStudent">

                                                    <div class="fluid-container">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered">
                                                                <thead class="table-dark">
                                                                <tr style="background-color:#2e314a; color:#fff">
                                                                    <th style="width:300px;" class="text-center">Main
                                                                        Student Name
                                                                    </th>
                                                                    <th style="" class="text-center">Age</th>
                                                                    <th style="" class="text-center">Gender</th>
                                                                    <th style="" class="text-center">Year of Birth</th>
                                                                    <th style="" class="text-center">Special Need</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td><input type="text" class="form-control"
                                                                               name="mainStudentFullName"/></td>
                                                                    <td>
                                                                        <select class="form-control" id="studentGender"
                                                                                name="mainStudentGender"
                                                                                data-search="true" data-sort="false">
                                                                            <option value="Male">Male</option>
                                                                            <option value="Female">Female</option>
                                                                        </select>
                                                                    </td>
                                                                    <td><input type="text" class="form-control"
                                                                               id="mainAge" name="mainAge"/></td>
                                                                    <td><input type="text" class="form-control"
                                                                               id="mainStudentDateOfBirth"
                                                                               name="mainStudentYearOfBirth"/></td>
                                                                    <td>
                                                                        <select class="form-control" id="specialNeed"
                                                                                name="mainStudentSpecialNeed"
                                                                                data-search="true" data-sort="false">
                                                                            <option value=""></option>
                                                                            <option value="Dyslexia">Dyslexia</option>
                                                                            <option value="Slow Learner">Slow Learner
                                                                            </option>
                                                                            <option value="Autism">Autism</option>
                                                                            <option value="Down Syndrome">Down
                                                                                Syndrome
                                                                            </option>
                                                                            <option value="OKU">OKU</option>
                                                                        </select>
                                                                </tr>

                                                                </tbody>
                                                            </table>
                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="row existingStudent">

                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="firstname" class="form-label">Full Name</label>
                                                            <div style="padding:5px; " class="form-control-wrap">
                                                                <span
                                                                    class="studentFullName"> {{$singleStudent->full_name}} </span>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="firstname" class="form-label">Gender</label>
                                                            <div style="padding:5px; " class="form-control-wrap">
                                                                <span
                                                                    class="studentGender"> {{$singleStudent->gender}} </span>


                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="age" class="form-label">Age</label>
                                                            <div style="padding:5px; " class="form-control-wrap">
                                                                <span class="age"> {{$singleStudent->age}} </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="company" class="form-label">Date of
                                                                Birth</label>
                                                            <div style="padding:5px; " class="form-control-wrap">
                                                                <span
                                                                    class="studentDateOfBirth"> {{$singleStudent->dob}} </span>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="firstname" class="form-label">Special
                                                                Need</label>
                                                            <div style="padding:5px; " class="form-control-wrap">
                                                                <span
                                                                    class="specialNeed"> {{$singleStudent->specialNeed}} </span>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="fluid-container new_hide">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead class="table-dark">
                                                            <tr style="background-color:#2e314a; color:#fff">
                                                                <th class="text-center">Search Student</th>
                                                                <th style="width:300px;" class="text-center">Student
                                                                    Name
                                                                </th>
                                                                <th style="" class="text-center">Gender</th>
                                                                <th style="" class="text-center">Age</th>
                                                                <th style="" class="text-center">Year of Birth</th>
                                                                <th style="" class="text-center">Special Need</th>
                                                                <th style="" class="text-center">Status</th>
{{--                                                                <th style="" class="text-center">Remove</th>--}}
                                                            </tr>
                                                            </thead>
                                                            <tbody id="studentbody">

                                                            @foreach($extraStudents as $rowExtraStudents)

                                                                <tr>

                                                                    <td class="">
                                                                        <input type="hidden" name="extraOldStudent[]" value="extraOldStudent">
                                                                        <select class="form-control js-select"
                                                                                data-search="true" data-sort="true"
                                                                                id="selectStudent${rowStudentIdx}"
                                                                                name="student_ids[]">

                                                                            @foreach($students as $subjectRow)
                                                                                <option {{$rowExtraStudents->id==$subjectRow->student_id?"selected":""}}
                                                                                    value="{{$subjectRow->id}}"> {{$subjectRow->full_name}}
                                                                                    - {{$subjectRow->uid}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>

                                                                    <td class=""><input type="text" readonly
                                                                                        class="studentFullName${rowStudentIdx} form-control"
                                                                                        id="studentFullName${rowStudentIdx}"
                                                                                        value="{{$rowExtraStudents->student_name}}"
                                                                                        name="studentFullName[]"
                                                                                        placeholder="Student Full name">
                                                                    </td>
                                                                    <td class=""><select readonly class="form-control"
                                                                                         id="studentGender${rowStudentIdx}"
                                                                                         name="studentGender[]"
                                                                                         data-search="true"
                                                                                         data-sort="false">
                                                                            <option
                                                                                value="{{$rowExtraStudents->student_gender}}">{{$rowExtraStudents->student_gender}}</option>
                                                                            <option value="Male">Male</option>
                                                                            <option value="Female">Female</option>
                                                                        </select></td>
                                                                    <td class=""><input readonly type="number"
                                                                                        value="{{$rowExtraStudents->student_age}}"
                                                                                        class="age form-control"
                                                                                        id="age${rowStudentIdx}"
                                                                                        name="age[]" placeholder="Age">
                                                                    </td>
                                                                    <td class=""><input readonly type="number"
                                                                                        value="{{$rowExtraStudents->year_of_birth}}"
                                                                                        class="studentDateOfBirth form-control"
                                                                                        id="studentDateOfBirth${rowStudentIdx}"
                                                                                        name="studentDateOfBirth[]"
                                                                                        placeholder="Date of Birth">
                                                                    </td>
                                                                    <td class=""><select class="form-control"
                                                                                         id="specialNeed${rowStudentIdx}"
                                                                                         name="specialNeed[]"
                                                                                         data-search="true"
                                                                                         data-sort="false">
                                                                            <option selected
                                                                                    value="{{$rowExtraStudents->special_need}}">{{$rowExtraStudents->special_need}}</option>
                                                                            <option value="Dyslexia">Dyslexia</option>
                                                                            <option value="Slow Learner">Slow Learner
                                                                            </option>
                                                                            <option value="Autism">Autism</option>
                                                                            <option value="Down Syndrome">Down
                                                                                Syndrome
                                                                            </option>
                                                                            <option value="OKU">OKU</option>
                                                                        </select></td>
                                                                        <td class=""><select class="form-control"
                                                                                             id="status${rowStudentIdx}"
                                                                                             name="status[]"
                                                                                             data-search="true"
                                                                                             data-sort="false">
                                                                                <option
                                                                                    {{$rowExtraStudents->status=="active"?"selected":""}}
                                                                                        value="{{$rowExtraStudents->status}}">Active</option>
                                                                                <option  {{$rowExtraStudents->status=="inactive"?"selected":""}} value="inactive">Inactive</option>
                                                                            </select>
                                                                        </td>
{{--                                                                    <td class="text-center">--}}
{{--                                                                        <button style="background-color:red; color:#fff"--}}
{{--                                                                                class="btn btn-sm remove"--}}
{{--                                                                                type="button"> X--}}
{{--                                                                        </button>--}}
{{--                                                                    </td>--}}

                                                                </tr>

                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <button class="btn btn-md btn-warning"
                                                            id="addStudentBtn" type="button">
                                                        Add New Student
                                                    </button>
                                                </div>


                                            </div>

                                            <div style="display:none;" id="classAddressPanel" class="row g-3">

                                                <h3 style="color:#fff; border-radius:5px;background-color: #2e314a; box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;  padding:10px; ">
                                                    CLASS ADDRESS</h3>
                                                <div class="col-lg-12">
                                                    <label><strong>Same as Customer / Parent Address</strong> </label>
                                                    <input type="checkbox" style="width:20px; height:20px;"
                                                           id="sameAsCustomerAddress" name="sameAsCustomerAddress">
                                                </div>
                                                <div class="row sameAsCustomer">
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="city" class="form-label">Full Address</label>
                                                            <div class="form-control-wrap"><input type="text"
                                                                                                  class="classAddress form-control"
                                                                                                  name="classAddress"
                                                                                                  id="classAddress"
                                                                                                  placeholder="Class Address ">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="city" class="form-label">Latitude</label>
                                                            <div class="form-control-wrap"><input type="text"
                                                                                                  class="classLatitude form-control"
                                                                                                  name="classLatitude"
                                                                                                  id="classLatitude"
                                                                                                  placeholder="Latitude">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="city" class="form-label">Longitude</label>
                                                            <div class="form-control-wrap"><input type="text"
                                                                                                  class="classLongitude form-control"
                                                                                                  id="classLongitude"
                                                                                                  name="classLongitude"
                                                                                                  placeholder="Longitude">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="city" class="form-label">State</label>
                                                            <div class="classStateDropDown form-control-wrap">
                                                                <select class="js-select" data-search="true"
                                                                        data-sort="true" name="classState"
                                                                        id="classState">
                                                                    @php
                                                                        $states = DB::table('states')->get();
                                                                    @endphp
                                                                    @foreach($states as $rowStates)
                                                                        <option
                                                                            value="{{$rowStates->id}}">{{$rowStates->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="form-control-wrap"><input type="text"
                                                                                                  style="display:none;"
                                                                                                  class="classStateInput form-control"
                                                                                                  name="classState"
                                                                                                  id="classState"></div>
                                                        </div>
                                                    </div>


                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="city" class="form-label">City</label>
                                                            <div class="classCityDropDown form-control-wrap">
                                                                <select class="form-control" class="" data-search="true"
                                                                        data-sort="true" name="classCity"
                                                                        id="classCity">

                                                                </select>
                                                            </div>
                                                            <div class="form-control-wrap"><input type="text"
                                                                                                  style="display:none;"
                                                                                                  class="classCityInput form-control"
                                                                                                  name="classCity"
                                                                                                  id="classCity"></div>
                                                        </div>
                                                    </div>


                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="postalcode" class="form-label">Postal
                                                                Code</label>
                                                            <div class="form-control-wrap"><input type="text"
                                                                                                  class="classPostalCode form-control"
                                                                                                  name="classPostalCode"
                                                                                                  id="classPostalCode"
                                                                                                  placeholder="Class Postal Code">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>


                                            <div style="display:none" class="commitmentFee">
                                                <div class="row g-3">

                                                    <h3 style="color:#fff; border-radius:5px;background-color: #2e314a; box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;  padding:10px; margin-top:50px;">
                                                        COMMITMENT FEE</h3>
                                                    <small>RM 50 payment receipt is required to be uploaded for a new
                                                        student registration.</small>
                                                    <p>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <span class="title">Payment Attachment</span>
                                                            <input class="form-control" name="PaymentAttachment"
                                                                   type="file"/>
                                                            <small>Supported Extensions: doc,docx,pdf,jpg,jpeg,png <br/>
                                                                Max Size: 10MB</small>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <span class="title">Fee Payment Date</span>
                                                            <input class="form-control" name="PaymentDate" type="date"/>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <span class="title">Fee Amount</span>
                                                            <input class="form-control" name="feeAmount" type="text"/>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <span class="title">Receiving Account</span>
                                                            <select id="ReceivingAccountId" name="ReceivingAccountId"
                                                                    class="js-select" data-search="true"
                                                                    data-sort="false">>
                                                                <option value="44">Cash At Bank - Maybank</option>
                                                                <option value="Cash In Hand">Cash In Hand</option>
                                                                <option value="Payment Gateway - BillPlz Sdn Bhd">
                                                                    Payment Gateway - BillPlz Sdn Bhd
                                                                </option>
                                                                <option value="Payment Gateway - Ipay88">Payment Gateway
                                                                    - Ipay88
                                                                </option>
                                                                <option value="Public Bank">Public Bank</option>
                                                            </select>

                                                        </div>
                                                    </div>
                                                    </p>

                                                </div>
                                            </div>


                                            <div class="row g-3">
                                                <h3 style="color:#fff; border-radius:5px;background-color: #2e314a; box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;  padding:10px; ">
                                                    CLASS INFORMATION</h3>


                                                <div class="fluid-container">


                                                    <div class="row g-3">


                                                        <div class="fluid-container">
                                                            <div class="table-responsive">

                                                                <table class="table table-bordered">
                                                                    <thead class="table-dark">
                                                                    <tr style="background-color:#2e314a; color:#fff">
                                                                        <th style="width:300px;" class="text-center">
                                                                            Subject Name
                                                                        </th>
                                                                        <th style="" class="text-center">Class<br/>
                                                                            Frequency
                                                                        </th>
                                                                        <th style="" class="text-center">Class<br/>
                                                                            Duration
                                                                        </th>
                                                                        <th style="" class="text-center">Day</th>
                                                                        <th style="" class="text-center">Time <br/> (in
                                                                            24 Hrs)
                                                                        </th>
                                                                        <th style="" class="text-center">Tutor<br/>
                                                                            Pereference
                                                                        </th>
                                                                        <th style="" class="text-center">Ticket<br/>
                                                                            Type
                                                                        </th>
                                                                        <th style="" class="text-center">Special <br/>Request
                                                                        </th>
{{--                                                                        <th style="" class="text-center">Remove</th>--}}
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody id="tbody">

                                                                    <tr id="R${++rowIdx}">

{{--                                                                        <td class=""><select--}}
{{--                                                                                class="form-control js-select"--}}
{{--                                                                                data-search="true" data-sort="true"--}}
{{--                                                                                name="subject[]">--}}
{{--                                                                                <option--}}
{{--                                                                                    value="{{$singleTicket->subjects}}">{{$singleSubject->name}}</option>@foreach($subjects as $subjectRow)--}}
{{--                                                                                    <option--}}
{{--                                                                                        value="{{$subjectRow->id}}"> {{$subjectRow->name}}--}}
{{--                                                                                        ,--}}
{{--                                                                                        RM {{$subjectRow->price}}</option>--}}
{{--                                                                                @endforeach<select></td>--}}

                                                                        <td class=""><input type="hidden" class="form-control" readonly
                                                                                            type="text"
                                                                                            value="{{$singleSubject->id}}"
                                                                                            name="subject[]">
                                                                        <label>
                                                                            {{$singleSubject->name}}
                                                                        </label>
                                                                        </td>


                                                                        <td class=""><input type="hidden" class="form-control" readonly
                                                                                          
                                                                                            value="{{$singleTicket->classFrequency}}"
                                                                                            name="classFrequency[]">
                                                                            <label>
                                                                                {{$singleTicket->classFrequency}}
                                                                            </label>
                                                                        </td>
                                                                        <td class=""><input type="hidden" class="form-control" readonly
                                                                                            
                                                                                            value="{{$singleTicket->quantity}}"
                                                                                            name="quantity[]">
                                                                            <label>
                                                                                {{$singleTicket->quantity}}
                                                                            </label>
                                                                        </td>
                                                                        <td class="">

                                                                            <label><input
                                                                                    {{ in_array("Mon", str_replace('"', '', $ticketDays)) ? 'checked' : '' }} type="checkbox"
                                                                                    name="day[]" value="Mon">
                                                                                Mon</label>
                                                                            <label><input
                                                                                    {{ in_array("Tue", str_replace('"', '', $ticketDays)) ? 'checked' : '' }}  type="checkbox"
                                                                                    name="day[]" value="Tue">
                                                                                Tue</label>
                                                                            <label><input
                                                                                    {{ in_array("Wed", str_replace('"', '', $ticketDays)) ? 'checked' : '' }}  type="checkbox"
                                                                                    name="day[]" value="Wed">
                                                                                Wed</label>
                                                                            <label><input
                                                                                    {{ in_array("Thu", str_replace('"', '', $ticketDays)) ? 'checked' : '' }}  type="checkbox"
                                                                                    name="day[]" value="Thu">
                                                                                Thu</label>
                                                                            <label><input
                                                                                    {{ in_array("Fri", str_replace('"', '', $ticketDays)) ? 'checked' : '' }}  type="checkbox"
                                                                                    name="day[]" value="Fri">
                                                                                Fri</label>
                                                                            <label><input
                                                                                    {{ in_array("Sat", str_replace('"', '', $ticketDays)) ? 'checked' : '' }}  type="checkbox"
                                                                                    name="day[]" value="Sat">
                                                                                Sat</label>
                                                                            <label><input
                                                                                    {{ in_array("Sun", str_replace('"', '', $ticketDays)) ? 'checked' : '' }}  type="checkbox"
                                                                                    name="day[]" value="Sun">
                                                                                Sun</label>

                                                                        </td>


                                                                        <td class=""><input class="form-control"
                                                                                            type="time"
                                                                                            value="{{$singleTicket->time}}"
                                                                                            id="timePicker"
                                                                                            name="time[]"></td>


                                                                        <td class=""><select
                                                                                class="form-control js-select"
                                                                                name="tutorPereference[]">
                                                                                <option
                                                                                    value="{{$singleTicket->tutorPereference}}">{{$singleTicket->tutorPereference}}</option>
                                                                                <option value="male">Male</option>
                                                                                <option value="Female">Female</option>
                                                                                <select></td>
                                                                        <td class=""><select
                                                                                class="form-control js-select"
                                                                                name="subscription[]">
                                                                                <option
                                                                                    value="{{$singleTicket->subscription}}">{{$singleTicket->subscription}}</option>
                                                                                <option value="LongTerm">Long Term
                                                                                </option>
                                                                                <option value="shortTerm">Short Term
                                                                                </option>
                                                                                <select></td>
                                                                        <td class=""><input class="form-control"
                                                                                            type="text"
                                                                                            value="{{$singleTicket->specialRequest}}"
                                                                                            name="specialRequest[]">
                                                                        </td>
{{--                                                                        <td class="text-center">--}}
{{--                                                                            <button--}}
{{--                                                                                style="background-color:red; color:#fff"--}}
{{--                                                                                class="btn btn-sm remove"--}}
{{--                                                                                type="button"> X--}}
{{--                                                                            </button>--}}
{{--                                                                        </td>--}}
                                                                    </tr>

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>


                                            </div>

                                            <div class="row g-3">
                                                <div class="col-lg-12">
                                                    <button class="btn btn-primary" type="submit">Submit</button>
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

    <!-- Add the this google map apis to webpage -->
    <script
        src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key=AIzaSyAP0kk0GU1hG9_-J8ovxiOogeWaOOtwSKo&libraries=places"></script>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"
            integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyAP0kk0GU1hG9_-J8ovxiOogeWaOOtwSKo"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>

        function changeTutor() {

            // Get the dropdown element
            var dropdown = document.getElementById("changeTutorID");
            var ticketIDD = document.getElementById("ticketIDD");

            // Get the selected value
            var selectedValue = dropdown.value;
            var ticketID = ticketIDD.value;
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            // Do something with the selected value
            alert("Selected Value: " + selectedValue);
            // You can now use the 'selectedValue' variable in your code
            //var data = { selectedValue: selectedValue };

            var data = {
                selectedValue: selectedValue,
                ticketID: ticketID
            };

            // Make an AJAX request using the fetch API
            fetch('/changeTutorID', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify(data),
            })
                .then(response => response.json())
                .then(data => {
                    // Handle the response from the server if needed
                    console.log(data);
                })
                .catch(error => {
                    // Handle errors
                    console.error('Error:', error);
                });
        }

        $(document).ready(function () {
            // Denotes total number of rows
            var rowIdx = 0;
            // jQuery button click event to add a row
            $('#addBtn').on('click', function () {
                // Adding a row inside the tbody.
                $('#tbody').append(`<tr id="R${++rowStudentIdx}">

                                            <td class=""><select class="form-control js-select" data-search="true" data-sort="true" id="selectStudent${rowStudentIdx}" name="student_ids[]"><option value="newStudent">New Student</option>@foreach($students as $subjectRow)<option value="{{$subjectRow->id}}"> {{$subjectRow->full_name}} - {{$subjectRow->uid}}</option>@endforeach<select></td>
                                                <td class=""><input type="text" class="studentFullName form-control" id="studentFullName${rowStudentIdx}" name="studentFullName[]"  placeholder="Student Full name"></td>
                                                        <td class=""><select class="form-control" id="studentGender${rowStudentIdx}" name="studentGender[]" data-search="true" data-sort="false"><option value=""></option><option value="Male">Male</option><option value="Female">Female</option></select></td>
                                                        <td class=""><input type="number" class="age form-control" id="age${rowStudentIdx}" name="age[]"  placeholder="Age"></td>
                                                        <td class=""><input type="number" class="studentDateOfBirth form-control" id="studentDateOfBirth${rowStudentIdx}" name="studentDateOfBirth[]"  placeholder="Date of Birth"></td>
                                                        <td class=""><select class="form-control" id="specialNeed${rowStudentIdx}" name="specialNeed[]" data-search="true" data-sort="false">
                                                            <option value="">Select Option</option>
                                                            <option value="Dyslexia">Dyslexia</option>
                                                            <option value="Slow Learner">Slow Learner</option>
                                                            <option value="Autism">Autism</option>
                                                            <option value="Down Syndrome">Down Syndrome</option>
                                                            <option value="OKU">OKU</option>
                                                        </select></td>

                                                  </tr>`);
            });

            // jQuery button click event to remove a row.
            $('#tbody').on('click', '.remove', function () {

                // Getting all the rows next to the row
                // containing the clicked button
                var child = $(this).closest('tr').nextAll();

                // Iterating across all the rows
                // obtained to change the index
                child.each(function () {

                    // Getting <tr> id.
                    var id = $(this).attr('id');

                    // Getting the <p> inside the .row-index class.
                    var idx = $(this).children('.row-index').children('p');

                    // Gets the row number from <tr> id.
                    var dig = parseInt(id.substring(1));

                    // Modifying row index.
                    idx.html(`Row ${dig - 1}`);

                    // Modifying row id.
                    $(this).attr('id', `R${dig - 1}`);
                });

                // Removing the current row.
                $(this).closest('tr').remove();

                // Decreasing total number of rows by 1.
                rowIdx--;
            });

            // Denotes total number of rows
            var rowStudentIdx = 0;
            // jQuery button click event to add a row
            $('#addStudentBtn').on('click', function () {
                // Adding a row inside the tbody.

                $('#studentbody').append(`<tr id="R${++rowStudentIdx}">

                                            <td class=""><input type="hidden" name="extraNewStudent[]" value="extraNewStudent"><select class="form-control js-select" data-search="true" data-sort="true" id="selectStudent${rowStudentIdx}" name="student_ids[]"><option value="newStudent">New Student</option>@foreach($students as $subjectRow)<option value="{{$subjectRow->id}}"> {{$subjectRow->full_name}} - {{$subjectRow->uid}}</option>@endforeach<select></td>
                                                <td class=""><input type="text" class="studentFullName${rowStudentIdx} form-control" id="studentFullName${rowStudentIdx}" name="studentFullName[]"  placeholder="Student Full name"></td>
                                                        <td class=""><select class="form-control" id="studentGender${rowStudentIdx}" name="studentGender[]" data-search="true" data-sort="false"><option value=""></option><option value="Male">Male</option><option value="Female">Female</option></select></td>
                                                        <td class=""><input type="number" class="age form-control" id="age${rowStudentIdx}" name="age[]"  placeholder="Age"></td>
                                                        <td class=""><input type="number" class="studentDateOfBirth form-control" id="studentDateOfBirth${rowStudentIdx}" name="studentDateOfBirth[]"  placeholder="Date of Birth"></td>
                                                        <td class=""><select class="form-control" id="specialNeed${rowStudentIdx}" name="specialNeed[]" data-search="true" data-sort="false">
                                                            <option value=""></option>
                                                            <option value="Dyslexia">Dyslexia</option>
                                                            <option value="Slow Learner">Slow Learner</option>
                                                            <option value="Autism">Autism</option>
                                                            <option value="Down Syndrome">Down Syndrome</option>
                                                            <option value="OKU">OKU</option>
                                                        </select></td>
                                                        <td class=""><select class="form-control" id="specialNeed${rowStudentIdx}" name="status[]" data-search="true" data-sort="false">

                                                            <option value="active">Active</option>
                                                            <option value="inactive">Inactive</option>

                                                        </select></td>

                                                  </tr>`);


                $("#selectStudent1").on("change", function () {
                    var selectedValue1 = $(this).val();
                    $.ajax({
                        url: "{{ url('/getStudentByID/') }}" + "/" + selectedValue1,
                        type: 'GET',
                        success: function (response) {
                            $("#studentFullName1").val(response.studentDetail.full_name);
                            $("#age1").val(response.studentDetail.age);
                            $("#studentDateOfBirth1").val(response.studentDetail.dob);
                            $("#studentGender1").val(response.studentDetail.gender);
                            $("#specialNeed1").val(response.studentDetail.specialNeed);
                        },
                        error: function () {
                            $("#result").text("Error fetching value from the server");
                        }
                    });
                });

                $("#selectStudent2").on("change", function () {
                    var selectedValue2 = $(this).val();
                    $.ajax({
                        url: "{{ url('/getStudentByID/') }}" + "/" + selectedValue2,
                        type: 'GET',
                        success: function (response) {
                            $("#studentFullName2").val(response.studentDetail.full_name);
                            $("#age2").val(response.studentDetail.age);
                            $("#studentDateOfBirth2").val(response.studentDetail.dob);
                            $("#studentGender2").val(response.studentDetail.gender);
                            $("#specialNeed2").val(response.studentDetail.specialNeed);
                        },
                        error: function () {
                            $("#result").text("Error fetching value from the server");
                        }
                    });
                });
                $("#selectStudent3").on("change", function () {
                    var selectedValue3 = $(this).val();
                    $.ajax({
                        url: "{{ url('/getStudentByID/') }}" + "/" + selectedValue3,
                        type: 'GET',
                        success: function (response) {
                            $("#studentFullName3").val(response.studentDetail.full_name);
                            $("#age3").val(response.studentDetail.age);
                            $("#studentDateOfBirth3").val(response.studentDetail.dob);
                            $("#studentGender3").val(response.studentDetail.gender);
                            $("#specialNeed3").val(response.studentDetail.specialNeed);
                        },
                        error: function () {
                            $("#result").text("Error fetching value from the server");
                        }
                    });
                });
                $("#selectStudent4").on("change", function () {
                    var selectedValue4 = $(this).val();
                    $.ajax({
                        url: "{{ url('/getStudentByID/') }}" + "/" + selectedValue4,
                        type: 'GET',
                        success: function (response) {
                            $("#studentFullName4").val(response.full_name);
                            $("#age4").val(response.age);
                            $("#studentDateOfBirth4").val(response.studentDateOfBirth);
                            $("#specialNeed4").val(response.specialNeed);
                        },
                        error: function () {
                            $("#result").text("Error fetching value from the server");
                        }
                    });
                });

                $("#selectStudent5").on("change", function () {
                    var selectedValue5 = $(this).val();
                    $.ajax({
                        url: "{{ url('/getStudentByID/') }}" + "/" + selectedValue5,
                        type: 'GET',
                        success: function (response) {
                            $("#studentFullName5").val(response.full_name);
                            $("#age5").val(response.age);
                            $("#studentDateOfBirth5").val(response.studentDateOfBirth);
                            $("#specialNeed5").val(response.specialNeed);
                        },
                        error: function () {
                            $("#result").text("Error fetching value from the server");
                        }
                    });
                });

                $('#studentDateOfBirth1').on('input', function () {
                    var dob = $("#studentDateOfBirth1").val();
                    var today = new Date();
                    var birthDate = new Date(dob);
                    var age = today.getFullYear() - birthDate.getFullYear();
                    if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) {
                        age--;
                    }
                    $('#age1').val(age);
                });

                $('#studentDateOfBirth2').on('input', function () {
                    var dob = $("#studentDateOfBirth2").val();
                    var today = new Date();
                    var birthDate = new Date(dob);
                    var age = today.getFullYear() - birthDate.getFullYear();
                    if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) {
                        age--;
                    }
                    $('#age2').val(age);
                });

                $('#studentDateOfBirth3').on('input', function () {
                    var dob = $("#studentDateOfBirth3").val();
                    var today = new Date();
                    var birthDate = new Date(dob);
                    var age = today.getFullYear() - birthDate.getFullYear();
                    if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) {
                        age--;
                    }
                    $('#age3').val(age);
                });

                $('#studentDateOfBirth4').on('input', function () {
                    var dob = $("#studentDateOfBirth4").val();
                    var today = new Date();
                    var birthDate = new Date(dob);
                    var age = today.getFullYear() - birthDate.getFullYear();
                    if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) {
                        age--;
                    }
                    $('#age4').val(age);
                });

                $('#studentDateOfBirth5').on('input', function () {
                    var dob = $("#studentDateOfBirth5").val();
                    var today = new Date();
                    var birthDate = new Date(dob);
                    var age = today.getFullYear() - birthDate.getFullYear();
                    if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) {
                        age--;
                    }
                    $('#age5').val(age);
                });

                $('#studentDateOfBirth6').on('input', function () {
                    var dob = $("#studentDateOfBirth6").val();
                    var today = new Date();
                    var birthDate = new Date(dob);
                    var age = today.getFullYear() - birthDate.getFullYear();
                    if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) {
                        age--;
                    }
                    $('#age6').val(age);
                });

                $('#studentDateOfBirth7').on('input', function () {
                    var dob = $("#studentDateOfBirth7").val();
                    var today = new Date();
                    var birthDate = new Date(dob);
                    var age = today.getFullYear() - birthDate.getFullYear();
                    if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) {
                        age--;
                    }
                    $('#age7').val(age);
                });

                // Listen for input changes in the source input field
                $("#age1").on('input', function () {
                    // Get the value from the source input
                    var ageInputValue = $("#age1").val();
                    console.log(ageInputValue);
                    // Get the age input value
                    var age = parseInt(ageInputValue);
                    // Get the current date
                    var currentDate = new Date();
                    // Calculate the birth year by subtracting age from the current year
                    var birthYear = currentDate.getFullYear() - age;
                    // Create a new Date object for the calculated birth year
                    var dob = new Date(birthYear, 0, 1); // Assuming January 1st as the birthdate
                    // Format the date of birth (DOB)
                    var dobFormatted = dob.toLocaleDateString('en-US', {year: 'numeric'});
                    // Display the calculated DOB
                    $('#dobResult').text(dobFormatted);
                    // Set the same value in the target input field
                    $("#studentDateOfBirth1").val(dobFormatted);
                });

                // Listen for input changes in the source input field
                $("#age2").on('input', function () {
                    // Get the value from the source input
                    var ageInputValue = $("#age2").val();
                    console.log(ageInputValue);
                    // Get the age input value
                    var age = parseInt(ageInputValue);
                    // Get the current date
                    var currentDate = new Date();
                    // Calculate the birth year by subtracting age from the current year
                    var birthYear = currentDate.getFullYear() - age;
                    // Create a new Date object for the calculated birth year
                    var dob = new Date(birthYear, 0, 1); // Assuming January 1st as the birthdate
                    // Format the date of birth (DOB)
                    var dobFormatted = dob.toLocaleDateString('en-US', {year: 'numeric'});
                    // Display the calculated DOB
                    $('#dobResult').text(dobFormatted);
                    // Set the same value in the target input field
                    $("#studentDateOfBirth2").val(dobFormatted);
                });

                // Listen for input changes in the source input field
                $("#age3").on('input', function () {
                    // Get the value from the source input
                    var ageInputValue = $("#age3").val();
                    console.log(ageInputValue);
                    // Get the age input value
                    var age = parseInt(ageInputValue);
                    // Get the current date
                    var currentDate = new Date();
                    // Calculate the birth year by subtracting age from the current year
                    var birthYear = currentDate.getFullYear() - age;
                    // Create a new Date object for the calculated birth year
                    var dob = new Date(birthYear, 0, 1); // Assuming January 1st as the birthdate
                    // Format the date of birth (DOB)
                    var dobFormatted = dob.toLocaleDateString('en-US', {year: 'numeric'});
                    // Display the calculated DOB
                    $('#dobResult').text(dobFormatted);
                    // Set the same value in the target input field
                    $("#studentDateOfBirth3").val(dobFormatted);
                });

                // Listen for input changes in the source input field
                $("#age4").on('input', function () {
                    // Get the value from the source input
                    var ageInputValue = $("#age4").val();
                    console.log(ageInputValue);
                    // Get the age input value
                    var age = parseInt(ageInputValue);
                    // Get the current date
                    var currentDate = new Date();
                    // Calculate the birth year by subtracting age from the current year
                    var birthYear = currentDate.getFullYear() - age;
                    // Create a new Date object for the calculated birth year
                    var dob = new Date(birthYear, 0, 1); // Assuming January 1st as the birthdate
                    // Format the date of birth (DOB)
                    var dobFormatted = dob.toLocaleDateString('en-US', {year: 'numeric'});
                    // Display the calculated DOB
                    $('#dobResult').text(dobFormatted);
                    // Set the same value in the target input field
                    $("#studentDateOfBirth4").val(dobFormatted);
                });

                // Listen for input changes in the source input field
                $("#age5").on('input', function () {
                    // Get the value from the source input
                    var ageInputValue = $("#age5").val();
                    console.log(ageInputValue);
                    // Get the age input value
                    var age = parseInt(ageInputValue);
                    // Get the current date
                    var currentDate = new Date();
                    // Calculate the birth year by subtracting age from the current year
                    var birthYear = currentDate.getFullYear() - age;
                    // Create a new Date object for the calculated birth year
                    var dob = new Date(birthYear, 0, 1); // Assuming January 1st as the birthdate
                    // Format the date of birth (DOB)
                    var dobFormatted = dob.toLocaleDateString('en-US', {year: 'numeric'});
                    // Display the calculated DOB
                    $('#dobResult').text(dobFormatted);
                    // Set the same value in the target input field
                    $("#studentDateOfBirth5").val(dobFormatted);
                });

                // Listen for input changes in the source input field
                $("#age6").on('input', function () {
                    // Get the value from the source input
                    var ageInputValue = $("#age6").val();
                    console.log(ageInputValue);
                    // Get the age input value
                    var age = parseInt(ageInputValue);
                    // Get the current date
                    var currentDate = new Date();
                    // Calculate the birth year by subtracting age from the current year
                    var birthYear = currentDate.getFullYear() - age;
                    // Create a new Date object for the calculated birth year
                    var dob = new Date(birthYear, 0, 1); // Assuming January 1st as the birthdate
                    // Format the date of birth (DOB)
                    var dobFormatted = dob.toLocaleDateString('en-US', {year: 'numeric'});
                    // Display the calculated DOB
                    $('#dobResult').text(dobFormatted);
                    // Set the same value in the target input field
                    $("#studentDateOfBirth6").val(dobFormatted);
                });

                // Listen for input changes in the source input field
                $("#age7").on('input', function () {
                    // Get the value from the source input
                    var ageInputValue = $("#age7").val();
                    console.log(ageInputValue);
                    // Get the age input value
                    var age = parseInt(ageInputValue);
                    // Get the current date
                    var currentDate = new Date();
                    // Calculate the birth year by subtracting age from the current year
                    var birthYear = currentDate.getFullYear() - age;
                    // Create a new Date object for the calculated birth year
                    var dob = new Date(birthYear, 0, 1); // Assuming January 1st as the birthdate
                    // Format the date of birth (DOB)
                    var dobFormatted = dob.toLocaleDateString('en-US', {year: 'numeric'});
                    // Display the calculated DOB
                    $('#dobResult').text(dobFormatted);
                    // Set the same value in the target input field
                    $("#studentDateOfBirth7").val(dobFormatted);
                });
            });

            // jQuery button click event to remove a row.
            $('#studentbody').on('click', '.remove', function () {

                // Getting all the rows next to the row
                // containing the clicked button
                var child = $(this).closest('tr').nextAll();

                // Iterating across all the rows
                // obtained to change the index
                child.each(function () {

                    // Getting <tr> id.
                    var id = $(this).attr('id');

                    // Getting the <p> inside the .row-index class.
                    var idx = $(this).children('.row-index').children('p');

                    // Gets the row number from <tr> id.
                    var dig = parseInt(id.substring(1));

                    // Modifying row index.
                    idx.html(`Row ${dig - 1}`);

                    // Modifying row id.
                    $(this).attr('id', `R${dig - 1}`);
                });

                // Removing the current row.
                $(this).closest('tr').remove();

                // Decreasing total number of rows by 1.
                rowStudentIdx--;
            });


        });
    </script>

    <script>


        $(document).ready(function () {
            // Get the source input field
            var $sourceInput = $('#mobile_code');

            // Get the target input field
            var $targetInput = $('#whatsapp_code');

            // Listen for input changes in the source input field
            $sourceInput.on('input', function () {
                // Get the value from the source input
                var inputValue = $sourceInput.val();

                // Set the same value in the target input field
                $targetInput.val(inputValue);
            });


            var $getAgeInput = $('#age');
            var $getStudentDateOfBirth = $('#studentDateOfBirth');

            // Listen for input changes in the source input field
            $getAgeInput.on('input', function () {
                // Get the value from the source input
                var ageInputValue = $getAgeInput.val();
                // Get the age input value
                var age = parseInt(ageInputValue);
                // Get the current date
                var currentDate = new Date();
                // Calculate the birth year by subtracting age from the current year
                var birthYear = currentDate.getFullYear() - age;
                // Create a new Date object for the calculated birth year
                var dob = new Date(birthYear, 0, 1); // Assuming January 1st as the birthdate
                // Format the date of birth (DOB)
                var dobFormatted = dob.toLocaleDateString('en-US', {year: 'numeric'});
                // Display the calculated DOB
                $('#dobResult').text(dobFormatted);
                // Set the same value in the target input field
                $getStudentDateOfBirth.val(dobFormatted);
            });


            var $getMainAgeInput = $('#mainAge');
            var $getMainStudentDateOfBirth = $('#mainStudentDateOfBirth');

            // Listen for input changes in the source input field
            $getMainAgeInput.on('input', function () {
                // Get the value from the source input
                var mainAgeInputValue = $getMainAgeInput.val();
                // Get the age input value
                var age = parseInt(mainAgeInputValue);
                // Get the current date
                var currentDate = new Date();
                // Calculate the birth year by subtracting age from the current year
                var birthYear = currentDate.getFullYear() - age;
                // Create a new Date object for the calculated birth year
                var dob = new Date(birthYear, 0, 1); // Assuming January 1st as the birthdate
                // Format the date of birth (DOB)
                var dobFormatted = dob.toLocaleDateString('en-US', {year: 'numeric'});
                // Display the calculated DOB
                $('#dobResult').text(dobFormatted);
                // Set the same value in the target input field
                $getMainStudentDateOfBirth.val(dobFormatted);
            });

            $('#mainStudentDateOfBirth').on('input', function () {
                // Get the selected date of birth
                var dob = $("#mainStudentDateOfBirth").val();


                // Calculate age
                var today = new Date();
                var birthDate = new Date(dob);
                var age = today.getFullYear() - birthDate.getFullYear();

                // Check if birthday has occurred this year
                if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) {
                    age--;
                }

                // Display the result
                $('#mainAge').val(age);


            });


        });


        $("select#customerState").change(function () {
            $("#customerCity").html('');
            var customerState = $(this).children("option:selected").val();

            $.ajax({
                url: "{{url('addTicketAjaxPOSTcustomerState')}}",
                dataType: "json",
                data: {
                    customerState: customerState,
                    _token: '{{csrf_token()}}'
                },
                type: "post",
                success: function (data) {
                    $('#customerCity').append(data.cities);
                }
            });

        });


        $('[name="sameAsCustomerAddress"]').change(function () {
            if ($(this).is(':checked')) {
                // Do something...

                //$('.sameAsCustomer').hide();
                var inputCustomerAddressOne = $(".address1").val();
                var inputCustomerAddress = $(".classAddress").val(inputCustomerAddressOne);

                var inputCustomerAddressTwo = $(".newCustomerAddress1").val();
                var inputCustomerAddress = $(".classAddress").val(inputCustomerAddressTwo);

                var customerLatitude = $(".customerLatitude").val();
                $(".classLatitude").val(customerLatitude);

                var customerLongitude = $(".customerLongitude").val();
                $(".classLongitude").val(customerLongitude);

                $('.classStateDropDown').hide();
                $('.classCityDropDown').hide();

                $('.classStateInput').show();
                $('.classCityInput').show();


                var customerState = $('select.customerState option:selected').val();

                $(".customerStateInput").val(customerState);

                var customerPostalCode = $(".customerPostalCode").val();
                $(".classPostalCode").val(customerPostalCode);

                var customerCity = $(".customerCity").val();
                $(".customerCityInput").val(customerCity);


            } else {
                //$('.sameAsCustomer').show();
                $('.classStateDropDown').show();
                $('.classCityDropDown').show();

                $('.classStateInput').hide();
                $('.classCityInput').hide();


            }
            ;
        });


        $("select#classType").change(function () {
            var classType = $(this).children("option:selected").val();

            if (classType == "online") {

                $('#classAddressPanel').hide();


            } else {
                $('#classAddressPanel').show();


            }

        });

        $("select#classState").change(function () {
            var classState = $(this).children("option:selected").val();


            $.ajax({
                url: "{{url('addTicketAjaxPOSTclassState')}}",
                dataType: "json",
                data: {
                    classState: classState,
                    _token: '{{csrf_token()}}'
                },
                type: "post",
                success: function (data) {

                    $('#classCity').append(data.cities);
                }
            });


        });


        google.maps.event.addDomListener(window, 'load', initialize);

        function initialize() {
            var input = document.getElementById('address');
            var studentInput = document.getElementById('classAddress');
            var autocomplete = new google.maps.places.Autocomplete(input);
            var studentAutocomplete = new google.maps.places.Autocomplete(studentInput);
            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();
                // place variable will have all the information you are looking for.

                document.getElementById("customerLatitude").value = place.geometry['location'].lat();
                document.getElementById("customerLongitude").value = place.geometry['location'].lng();
            });
            studentAutocomplete.addListener('place_changed', function () {
                var place = studentAutocomplete.getPlace();
                document.getElementById("classLatitude").value = place.geometry['location'].lat();
                document.getElementById("classLongitude").value = place.geometry['location'].lng();
            });
        }

        $(document).ready(function () {

            var autocomplete;
            var studentAutocomplete;
            autocomplete = new google.maps.places.Autocomplete((document.getElementById(input)), {
                types: ['geocode'],
                componentRestrictions: {
                    country: "MY"
                }
            });

            studentAutocomplete = new google.maps.places.Autocomplete((document.getElementById(studentInput)), {
                types: ['geocode'],
                componentRestrictions: {
                    country: "MY"
                }
            });

            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                var near_place = autocomplete.getPlace();
                document.getElementById('loc_lat').value = near_place.geometry.location.lat();
                document.getElementById('loc_long').value = near_place.geometry.location.lng();

                document.getElementById('customerLatitude').value = near_place.geometry.location.lat();
                document.getElementById('customerLongitude').value = near_place.geometry.location.lng();

                document.getElementById('latitude_view').innerHTML = near_place.geometry.location.lat();
                document.getElementById('longitude_view').innerHTML = near_place.geometry.location.lng();
            });

            google.maps.event.addListener(studentAutocomplete, 'place_changed', function () {
                var near_place = studentAutocomplete.getPlace();
                document.getElementById('studentLatitude').value = near_place.geometry.location.lat();
                document.getElementById('studentLongitude').value = near_place.geometry.location.lng();

                document.getElementById('latitude_view').innerHTML = near_place.geometry.location.lat();
                document.getElementById('longitude_view').innerHTML = near_place.geometry.location.lng();
            });


        });

        $(document).on('change', '#' + input, function () {
            document.getElementById('customerLatitude').value = '';
            document.getElementById('customerLongitude').value = '';

            document.getElementById('latitude_view').innerHTML = '';
            document.getElementById('longitude_view').innerHTML = '';


        });

        var wto;
        $(document).on('change', '#' + studentInput, function () {
            document.getElementById('customerLatitude').value = '';
            document.getElementById('customerLongitude').value = '';
            document.getElementById('latitude_view_two').innerHTML = '';
            document.getElementById('longitude_view_two').innerHTML = '';
        });

    </script>



    <script>
        $(document).ready(function () {


            $("select#student_id").change(function () {
                var selectedStudent = $(this).children("option:selected").val();
                if (selectedStudent == 'newStudent') {
                    //$('.configform :input').val('');
                    $('.newCustomerDD').show();
                    $('.commitmentFee').show();

                    $('.existingCustomerDD').hide();

                    $('.newCustomer').show();
                    $('.existingCustomer').hide();

                    $('.newStudent').show();
                    $('.existingStudent').hide();

                    $('.addNewStudentHeading').show();
                    $('.existingStudentHeading').hide();


                    $('.addNewCustomerHeading').show();
                    $('.existingCustomertHeading').hide();

                } else {

                    $('.newStudent').hide();
                    $('.existingStudent').show();

                    $('.newCustomer').hide();
                    $('.existingCustomer').show();

                    $('.addNewStudentHeading').hide();
                    $('.existingStudentHeading').show();

                    $('.newCustomer').hide();
                    $('.existingCustomer').show();


                    $('.addNewCustomerHeading').hide();
                    $('.existingCustomertHeading').show();

                    $('.newCustomerDD').hide();
                    $('.existingCustomerDD').show();


                }
                var userURL = $(this).data('url');
                $.ajax({
                    url: "{{ url('/addTicket/') }}/" + selectedStudent,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        console.log(data);
                        $('.customerId').text(data.customer[0].uid);
                        $('.customerId').text(data.customer[0].uid);
                        $('.customerFullName').text(data.customer[0].full_name);
                        $('.customerFullName').val(data.customer[0].full_name);
                        $('.parentFullName').val(data.customer[0].full_name);


                        $('.customerEmail').text(data.customer[0].email);
                        $('.customerGender').text(data.customer[0].gender);
                        $('.customerEmail').val(data.customer[0].email);
                        $('.customerGender').val(data.customer[0].gender);

                        $('.existingParent_id').val(data.customer[0].id);
                        $('.customerPhone').text(data.customer[0].phone);
                        $('.customerPhone').val(data.customer[0].phone);


                        $('.customerWhatsapp').text(data.customer[0].whatsapp);

                        $('.customerWhatsapp').val(data.customer[0].whatsapp);

                        $('.address1').text(data.customer[0].address1);
                        $('.address1').val(data.customer[0].address1);
                        $('.customerStreetAddress2').val(data.customer[0].address2);
                        $('.customerNRIC').text(data.customer[0].nric);
                        $('.customerDOB').text(data.customer[0].dob);
                        $('.customerCity').text(data.customer[0].cityName);
                        $('.customerLatitude').text(data.customer[0].latitude);
                        $('.customerLongitude').text(data.customer[0].longitude);
                        $('.customerPostalCode').text(data.customer[0].postal_code);
                        $('.customerState').val(data.customer[0].stateName);
                        $('.customerCity').val(data.customer[0].cityName);


                        $('.studentRegisterDate').text(data.student.register_date);
                        $('.studentId').text(data.student.uid);
                        $('.studentFullName').text(data.student.full_name);
                        $('.studentPhone').text(data.student.phone);
                        $('.studentWhatsapp').text(data.student.whatsapp);
                        $('.studentEmail').text(data.student.email);
                        $('.studentAge').text(data.student.age);
                        $('.studentGender').text(data.student.gender);
                        $('.studentAddress').text(data.student.address1);
                        $('.studentStreetAddress2').text(data.student.address2);
                        $('.studentNRIC').val(data.student.cnic);
                        $('.studentDateOfBirth').text(data.student.dob);
                        $('.specialNeed').text(data.student.specialNeed);
                        $('.age').text(data.student.age);
                        $('.studentCity').text(data.student.city);
                        $('.studentLatitude').val(data.student.latitude);
                        $('.studentLongitude').val(data.student.longitude);
                        $('.studentPostalCode').val(data.student.postal_code);

                    }
                });
            });


            $("select#parent_id").change(function () {
                var selectedParent = $(this).children("option:selected").val();

                if (selectedParent == 'newParent') {

                    $('.commitmentFee').show();

                    $('.newCustomer').show();
                    $('.existingCustomer').hide();

                    $('.addNewCustomerHeading').show();
                    $('.existingCustomertHeading').hide();

                    console.log("Line 984");

                } else {

                    $('.commitmentFee').hide();
                    $('.addNewCustomerHeading').hide();
                    $('.existingCustomertHeading').show();

                    $('.newCustomer').hide();
                    $('.existingCustomer').show();

                    console.log("Line 994");

                }
                var userURL = $(this).data('url');
                $.ajax({
                    url: "{{ url('/addTicketAjaxCallParrent/') }}/" + selectedParent,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {

                        $('.customerId').text(data.customer[0].uid);
                        $('.customerId').val(data.customer[0].uid);

                        $('.customerFullName').val(data.customer[0].full_name);

                        $('.customerEmail').val(data.customer[0].email);
                        $('.customerGender').val(data.customer[0].gender);
                        $('.customerPhone').val(data.customer[0].phone);
                        $('.customerWhatsapp').val(data.customer[0].whatsapp);
                        $('.address1').val(data.customer[0].address1);
                        $('.customerStreetAddress2').val(data.customer[0].address2);
                        $('.customerNRIC').text(data.customer[0].nric);
                        $('.customerDOB').text(data.customer[0].dob);
                        $('.customerCity').val(data.customer[0].city);
                        $('.customerState').val(data.customer[0].state);
                        $('.customerLatitude').val(data.customer[0].latitude);
                        $('.customerLongitude').val(data.customer[0].longitude);
                        $('.customerPostalCode').val(data.customer[0].postal_code);
                        if (data.studentLastClass === null) {
                            $('.commitmentFee').show();
                            $('.studentLastClass').text("Student didn't get any class");
                        } else {

                            $('.studentLastClass').text(data.studentLastClass);
                        }


                    }
                });
            });

        });
    </script>

@endsection
