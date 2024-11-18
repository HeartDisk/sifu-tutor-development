@extends('layouts.main')
@section('content')
    <div class="nk-content sifu-view-page">
        <div class="fluid-container">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head">
                        <div class="nk-block-head-between flex-wrap gap g-2">
                            <div class="nk-block-head-content">
                                <h2 class="nk-block-title">Add Job Ticket</h2>
                                <nav>
                                    <ol class="breadcrumb breadcrumb-arrow mb-0">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item"><a href="#">Job Ticket</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Add Job Ticket</li>
                                    </ol>
                                </nav>
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
                                @endif @if (\Session::has('update'))
                                    <div class="alert alert-primary">
                                        <ul>
                                            <li>{!! \Session::get('update') !!}</li>
                                        </ul>
                                    </div>
                                @endif
                                <div class="bio-block">
                                    <form method="POST" action="{{route('submitJobTicket')}}"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                @if (\Session::has('danger'))
                                                    <div class="alert alert-danger">
                                                        <ul>
                                                            <li>{!! \Session::get('danger') !!}</li>
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="firstname" class="form-label">Date Created</label>
                                                    <div class="form-control-wrap">
                                                        <input readonly required="required" type="date" name="registration_date" value={{$today}} class="form-control" id="registrationDate" required>
                                                    </div>
                                                </div>
                                            </div>
                                            @if(\Illuminate\Support\Facades\Auth::user()->role != 7)
                                                @php 
                                                
                                                $staff_id=DB::table("staffs")->where("user_id",\Illuminate\Support\Facades\Auth::user()->id)->first();
                                                
                                                @endphp
                                                <input type="hidden" name="inCharge" value="{{$staff_id->id}}">
                                            @else
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="firstname" class="form-label">Student Customer
                                                            Service Incharge</label>
                                                        <div class="form-control-wrap">
                                                            @php $role=DB::table("roles")->
                                                                         where("name","Student Customer Service")->
                                                                         first();
                                                                         $staffs = DB::table('staffs')->
                                                                         join("users","staffs.user_id","=","users.id")->
                                                                         select("staffs.*","users.role as role")->
                                                                         where("staffs.status","Active")-> where("users.role",$role->id)->get();
                                                            @endphp
                                                            <select required data-search="true" data-sort="false" id="inCharge" name="inCharge">
                                                                <option value="">Select Admin Incharge</option>
                                                                @foreach($staffs as $rowStaff)
                                                                    <option
                                                                        value="{{$rowStaff->id}}">{{$rowStaff->full_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="col-lg-4" style="display:none">
                                                <div class="form-group">
                                                    <label for="firstname" class="form-label">Service</label>
                                                    <div class="form-control-wrap">
                                                        <select required name="service">
                                                            <option selected value="sifututor">Sifututor</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="firstname" class="form-label">Select Student</label>
                                                    <div class="form-control-wrap">
                                                        <select class="js-select" id="student_id" required name="student_id" data-search="true" data-sort="false">
                                                            <option value="">Please select option</option>
                                                            <option value="newStudent">New Student</option>
                                                           @foreach($students as $rowStudents)
                                                                @php

                                                                    $customersName = DB::table('customers')->where('id','=',$rowStudents->customer_id)->first();
                                                                    $customerCommitmentFeecheck = DB::table('customer_commitment_fees')->where('customer_id','=',$rowStudents->customer_id)->first();
                                                                @endphp
                                                                @if(isset($rowStudents) && isset($customersName))
                                                                <option
                                                                    value="{{$rowStudents->id}}">{{$rowStudents->student_id}}-{{$rowStudents->full_name}}({{$customersName->full_name}})
                                                                </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="existingCustomerDD col-lg-4">
                                                <div class="form-group">
                                                    <label for="firstname" class="form-label">Customer / Parent Name
                                                    </label>
                                                    <div class="form-control-wrap">
                                                        <input type="hidden" class="existingParent_id form-control" id="existingParent_id" name="existingParent_id" placeholder="Parent ID">
                                                        <input type="text" readonly class="parentFullName form-control" id="parentFullName" name="parentFullName" placeholder="Select Parent">
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="display:none;" class="newCustomerDD col-lg-4">
                                                <div class="form-group">
                                                    <label for="firstname" class="form-label">Select Parent/Customer
                                                    </label>
                                                    <div class="form-control-wrap">
                                                        <select id="parent_id" required name="parent_id" data-search="true" data-sort="false" required>
                                                            <option value="oldParent">Select parent</option>
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
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="firstname" class="form-label">Class Type</label>
                                                    <div class="form-control-wrap">
                                                        <select class="classType js-select" data-search="true" id="classType" data-sort="false" name="classType" required>
                                                            <option value="null">Select Option</option>
                                                            <option value="online">Online</option>
                                                            <option value="physical">Physical</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="customerInfo">
                                            <h3>
                                                <div class="addNewCustomerHeading"> ADD NEW CUSTOMER / PARENT INFORMATION</div>
                                                <div style="display:none;" class="existingCustomertHeading">CUSTOMER / PARENT INFORMATION</div>
                                            </h3>
                                            <div class="row existingCustomer">
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="firstname" class="form-label">Full Name</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" readonly class="form-control customerFullName existing"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="firstname" class="form-label">Customer
                                                            Gender</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" readonly class="form-control customerGender existing"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="email" class="form-label">Email address</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" readonly class="form-control customerEmail existing"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="email" class="form-label">Phone Number</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" readonly class="form-control customerPhone existing"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="email" class="form-label">Whatsapp Number</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" readonly class="form-control customerWhatsapp existing"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-box">
                                                        <label for="email" class="form-label">Address</label>
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <input type="text" readonly class="form-control address1 existing"/>
                                                                <input type="hidden" readonly class="form-control customerLatitude existing"/>
                                                                <input type="hidden" readonly class="form-control customerLongitude existing"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-box">
                                                        <label for="email" class="form-label">Landmark</label>
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <input type="text" readonly class="form-control landmark existing"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="city" class="form-label">State</label>
                                                        <div class="form-control-wrap">
                                                            <span class="customerState"></span>
                                                            <input type="text" readonly class="customerState existing form-control" name="customerState" id="customerState">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="city" class="form-label">City</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" readonly class="customerCity existing form-control" name="customerCity">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="postalcode" class="form-label">Postal Code</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" readonly class="customerPostalCode existing form-control" name="customerPostalCode" id="customerPostalCode">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div style="display:none;" class="row newCustomer">
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="firstname" class="form-label">Full Name</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="customerFullName new form-control" id="fullName" name="customerFullName" placeholder="Full name">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="firstname" class="form-label">Customer
                                                            Gender</label>
                                                        <div class="form-control-wrap">
                                                            <div class="">
                                                                <select class="js-select customerGender new" id="customerGender" name="customerGender" data-search="true" data-sort="false">
                                                                    <option value="">Select Gender</option>
                                                                    <option value="Male">Male</option>
                                                                    <option value="Female">Female</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="email" class="form-label">Email address</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class=" customerEmail new form-control" name="customerEmail" id="email" placeholder="Email address">
                                                            <label id="emailErrorMsg" style="color:red!important;display: none" class="form-label">Email already exist</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="email" class="form-label">Phone Number</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class=" customerPhone new form-control" name="customerPhone" id="mobile_code" placeholder="Customer Phone">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="email" class="form-label">Whatsapp Number</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text"
                                                                   class=" customerWhatsapp new form-control" name="customerWhatsapp" id="whatsapp_code" placeholder="Whatsapp Number">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-box">
                                                        <label class="form-label"> Address</label>
                                                        <div class="form-group">
                                                            <span class="la la-map-marker form-icon"></span>
                                                            <div class="form-control-wrap">
                                                                <input type="text"
                                                                       class="newCustomerAddress1 new form-control" name="address" id="address" placeholder="Address ">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-box">
                                                        <label class="form-label"> landmark</label>
                                                        <div class="form-group">
                                                            <span class="la la-map-marker form-icon"></span>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="landmark new form-control" name="landmark" id="landmark" placeholder="landmark ">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class=" col-lg-2">
                                                    <div class="form-group">
                                                        <label for="city" class="form-label">Latitude</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="customerLatitude new form-control" name="customerLatitude" id="customerLatitude" placeholder="Latitude">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class=" col-lg-2">
                                                    <div class="form-group">
                                                        <label for="city" class="form-label">Longitude</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="customerLongitude new form-control" id="customerLongitude" name="customerLongitude" placeholder="Longitude">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="city" class="form-label">State</label>
                                                        <div class="form-control-wrap">
                                                            <select class=" js-select customerState new" data-search="true" data-sort="false" name="customerState" id="customerState">
                                                                @php $states = DB::table('states')->get(); @endphp
                                                                <option>Please select state</option>
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
                                                            <select class="form-control customerCity new" data-search="true" data-sort="false" name="customerCity" id="customerCity">
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="postalcode" class="form-label">Postal Code</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class=" customerPostalCode new form-control" name="customerPostalcode" id="postalcode" placeholder="Zip code">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <h3><div class="existingStudentHeading">STUDENT INFORMATION</div></h3>
                                            <div class="row newStudent">
                                              <div class="table-responsive">
                                                  <table class="table">
                                                      <thead>
                                                      <!--Select Student-->
                                                      <tr>
                                                        <th>Main Student Name</th>
                                                        <th>Gender</th>
                                                        <th>Age</th>
                                                        <th>Year of Birth</th>
                                                        <th>Special Need</th>
                                                      </tr>
                                                      </thead>
                                                      <tbody>
                                                        <tr>
                                                          <td>
                                                              <input type="text" class="form-control" name="mainStudentFullName">
                                                          </td>
                                                          <td>
                                                              <select class="form-control" id="studentGender" name="mainStudentGender" data-search="true" data-sort="false">
                                                                <option value="">Please select Gender</option>
                                                                <option value="Male">Male</option>
                                                                <option value="Female">Female</option>
                                                              </select>
                                                          </td>
                                                          <td>
                                                              <input type="text" class="form-control" id="mainAge" name="mainAge">
                                                          </td>
                                                          <td>
                                                              <input type="text" class="form-control" id="mainStudentDateOfBirth" name="mainStudentYearOfBirth">
                                                          </td>
                                                          <td>
                                                              <select class="form-control" id="specialNeed" name="mainStudentSpecialNeed" data-search="true" data-sort="false">
                                                                <option value="">Select Option</option>
                                                                <option value="None">None</option>
                                                                <option value="Dyslexia">Dyslexia</option>
                                                                <option value="Slow Learner">Slow Learner</option>
                                                                <option value="Autism">Autism</option>
                                                                <option value="Down Syndrome">Down Syndrome</option>
                                                                <option value="OKU">OKU</option>
                                                              </select>
                                                          </td>
                                                        </tr>
                                                      </tbody>
                                                  </table>
                                              </div>
                                            </div>
                                            <div class="row existingStudent">
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="firstname" class="form-label">Full Name</label>
                                                        <div class="form-control-wrap">
                                                            <span class="studentFullName"> </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="firstname" class="form-label">Gender</label>
                                                        <div class="form-control-wrap">
                                                            <span class="studentGender"> </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="age" class="form-label">Age</label>
                                                        <div class="form-control-wrap">
                                                            <span class="age"> </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="company" class="form-label">Date of Birth
                                                        </label>
                                                        <div class="form-control-wrap">
                                                            <span class="studentDateOfBirth"> </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="firstname" class="form-label">Special Need
                                                        </label>
                                                        <div class="form-control-wrap">
                                                            <span class="specialNeed"> </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row new_hide">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                        <tr>
                                                            <th>Search Student</th>
                                                            <th>Student Name</th>
                                                            <th>Gender</th>
                                                            <th>Age</th>
                                                            <th>Year of Birth</th>
                                                            <th>Special Need</th>
                                                            <th>Remove</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="studentbody">
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-lg-10"></div>
                                                <div class="col-lg-2">
                                                  <button class="btn btn-md btn-primary auto-caddbtn mt-2" id="addStudentBtn" type="button">Add Student</button>
                                                </div>
                                            </div>


                                        <div style="display:none;" class="row g-3" id="classAddressPanel">
                                           <div class="col-lg-12">
                                              <h3>CLASS ADDRESS</h3>
                                               <label><strong>Same as Customer / Parent Address</strong> </label>
                                               <input type="checkbox" id="sameAsCustomerAddress" name="sameAsCustomerAddress">
                                           </div>
                                           <div class="col-lg-6 sameAsCustomer">
                                               <div class="form-group">
                                                   <label for="city" class="form-label">Full Address</label>
                                                   <div class="form-control-wrap">
                                                       <input type="text" class="classAddress form-control" name="classAddress" id="classAddress">
                                                   </div>
                                               </div>
                                           </div>
                                           <div class="col-lg-3 sameAsCustomer">
                                               <div class="form-group">
                                                   <label for="city" class="form-label">Latitude</label>
                                                   <div class="form-control-wrap">
                                                       <input type="text" class="classLatitude form-control" name="classLatitude" id="classLatitude" placeholder="Latitude">
                                                   </div>
                                               </div>
                                           </div>
                                           <div class="col-lg-3 sameAsCustomer">
                                               <div class="form-group">
                                                   <label for="city" class="form-label">Longitude</label>
                                                   <div class="form-control-wrap">
                                                       <input type="text" class="classLongitude form-control" id="classLongitude" name="classLongitude" placeholder="Longitude">
                                                   </div>
                                               </div>
                                           </div>
                                           <div class="col-lg-4 sameAsCustomer">
                                               <div class="form-group">
                                                   <label for="city" class="form-label">State</label>
                                                   <div class="classStateDropDown form-control-wrap">
                                                       <select class="js-select" data-search="true" data-sort="true" name="classState" id="classState">
                                                           <option value="">Select State</option>
                                                           @php $states = DB::table('states')->get(); @endphp @foreach($states as $rowStates)
                                                               <option
                                                                   value="{{$rowStates->id}}">{{$rowStates->name}}</option>
                                                           @endforeach
                                                       </select>
                                                   </div>
                                                   <div class="form-control-wrap">
                                                       <input type="text" style="display:none;" class="classStateInput form-control" name="classState" id="classState">
                                                   </div>
                                               </div>
                                           </div>
                                           <div class="col-lg-4 sameAsCustomer">
                                               <div class="form-group">
                                                   <label for="city" class="form-label">City</label>
                                                   <div class="classCityDropDown form-control-wrap">
                                                       <select class="form-control" class="" data-search="true" data-sort="true" name="classCity" id="classCity">
                                                       </select>
                                                   </div>
                                                   <div class="form-control-wrap">
                                                       <input type="text" style="display:none;" class="classCityInput form-control" name="classCity" id="classCity">
                                                   </div>
                                               </div>
                                           </div>
                                           <div class="col-lg-4 sameAsCustomer">
                                               <div class="form-group">
                                                   <label for="postalcode" class="form-label">Postal Code</label>
                                                   <div class="form-control-wrap">
                                                       <input type="text" class="classPostalCode form-control" name="classPostalCode" id="classPostalCode" placeholder="Class Postal Code">
                                                   </div>
                                               </div>
                                           </div>
                                       </div>


                                        <h3><div>CLASS INFORMATION</div></h3>
                                        <div class="row">
                                          <div class="table-responsive">
                                              <table class="table">
                                                  <thead>
                                                  <tr>
                                                      <th>Subject Name</th>
                                                      <th>Class Frequency</th>
                                                      <th>Class Duration</th>
                                                      <th>Day</th>
                                                      <th>Time (in 24 Hrs)</th>
                                                      <th>Tutor Pereference</th>
                                                      <th>Ticket Type</th>
                                                      <th>Special Request</th>
                                                      <th>Remove</th>
                                                  </tr>
                                                  </thead>
                                                  <tbody id="tbody">
                                                  <tr id="R1">
                                                  </tr>
                                                  </tbody>
                                              </table>
                                          </div>
                                          <div class="col-lg-10"></div>
                                          <div class="col-lg-2">
                                            <button class="btn btn-md btn-primary auto-caddbtn mt-2" id="addBtn"type="button">Add Subject</button>
                                          </div>
                                        </div>


                                        <div class="row mt-3">
                                            <div class="col-lg-2">
                                                <button id="submitButton" class="btn btn-primary" type="submit">Submit</button>
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
    <script src="https://code.jquery.com/jquery-3.7.1.js"
            integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key=AIzaSyAP0kk0GU1hG9_-J8ovxiOogeWaOOtwSKo&libraries=places"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyAP0kk0GU1hG9_-J8ovxiOogeWaOOtwSKo"></script>


    <script>
        $(document).ready(function () {

            //$('.js-example-basic-multiple').select2();

            // $("#classType").on("change", function () {
            //     var classType = $('#classType').find(":selected").val();
            //     if (classType == "physical") {
            //         var checkbox = $('#sameAsCustomerAddress');
            //         checkbox.prop('required', true);
            //     } else {
            //         var checkbox = $('#sameAsCustomerAddress');
            //         checkbox.removeAttr('required');

            //     }
            // });
            var rowIdx = 0;
            var rowIdxTwo = 0;
            $('#addBtn').on('click', function () {

                var classType = $('#classType').find(":selected").val();
                if (classType == "null") {
                    alert("Please select Class Type");
                    $('#classType').focus();
                    return 0;
                }

                var onlinesubjects = `<tr id="R${++rowIdx}">
                               <td style="width:20%"><select class="form-control osubje-search" class="form-control" data-search="true" data-sort="true" name="subject[]" required>
                                   <option value="">Select Subject</option>
                                   @foreach($subjectsOnline as $subjectRow)
                <option value="{{$subjectRow->id}}"> {{$subjectRow->name."(".$subjectRow->category_name.")"}}
                </option>
    @endforeach
                <select>
            </td>
    <td class=""><input class="form-control" type="number" step="any" name="classFrequency[]" required></td>
    <td class=""><select class="form-control" name="quantity[]" step="any" required>
       <option value="1">1 hour</option>
       <option value="1.5">1 hour 30 minutes</option>
       <option value="2">2 hours</option>
       <option value="2.5">2 hours 30 minutes</option>
       <option value="3">3 hours</option>
   </select></td>
    <td class="">
        <label><input type="checkbox" name="day[${rowIdx}][]" value="Mon"> Mon</label>
                                           <label><input type="checkbox" name="day[${rowIdx}][]" value="Tue"> Tue</label>
                                           <label><input type="checkbox" name="day[${rowIdx}][]" value="Wed"> Wed</label>
                                           <label><input type="checkbox" name="day[${rowIdx}][]" value="Thu"> Thu</label>
                                           <label><input type="checkbox" name="day[${rowIdx}][]" value="Fri"> Fri</label>
                                           <label><input type="checkbox" name="day[${rowIdx}][]" value="Sat"> Sat</label>
                                           <label><input type="checkbox" name="day[${rowIdx}][]" value="Sun"> Sun</label>
                                       </td>
                                       <td class=""><input class="form-control" type="time" value="22:00" id="timePicker" name="time[]" required></td>
                                       <td class=""><select class="form-control" name="tutorPereference[]" required><option value="male">Male</option><option value="Female">Female</option><select></td>
                                       <td class=""><select class="form-control" name="subscription[]" required><option value="Long-Term">Long-Term</option><option value="Short-Term">Short-Term</option><select></td>
                                       <td class=""><input class="form-control" type="text"  name="specialRequest[]" required></td>
                                 <td>
                                   <button class="btn btn-danger remove sifu-remove-btn" type="button">Remove</button>
                                   </td>
                                 </tr>`

                var physcialSubjects = `<tr id="R${++rowIdxTwo}">
                               <td style="width:20%"><select class="form-control psubje-search" class="form-control" data-search="true" data-sort="true" name="subject[]" required><option value="">Select Subject</option>
                                   @foreach($subjectsPhysical as $subjectRow)
                <option value="{{$subjectRow->id}}"> {{$subjectRow->name."(".$subjectRow->category_name.")"}}
                </option>
@endforeach
                <select>
                </td>
        <td class=""><input class="form-control" type="number" step="any" name="classFrequency[]" required></td>
        <td class=""><select class="form-control" name="quantity[]" step="any" required>
           <option value="1">1 hour</option>
           <option value="1.5">1 hour 30 minutes</option>
           <option value="2">2 hours</option>
           <option value="2.5">2 hours 30 minutes</option>
           <option value="3">3 hours</option>
       </select></td>
        <td class="">
            <label><input type="checkbox" name="day[${rowIdxTwo}][]" value="Mon"> Mon</label>
                                           <label><input type="checkbox" name="day[${rowIdxTwo}][]" value="Tue"> Tue</label>
                                           <label><input type="checkbox" name="day[${rowIdxTwo}][]" value="Wed"> Wed</label>
                                           <label><input type="checkbox" name="day[${rowIdxTwo}][]" value="Thu"> Thu</label>
                                           <label><input type="checkbox" name="day[${rowIdxTwo}][]" value="Fri"> Fri</label>
                                           <label><input type="checkbox" name="day[${rowIdxTwo}][]" value="Sat"> Sat</label>
                                           <label><input type="checkbox" name="day[${rowIdxTwo}][]" value="Sun"> Sun</label>
                                       </td>
                                       <td class=""><input class="form-control" type="time" value="22:00" id="timePicker" name="time[]" required></td>
                                       <td class=""><select class="form-control" name="tutorPereference[]" required><option value="male">Male</option><option value="Female">Female</option><select></td>
                                       <td class=""><select class="form-control" name="subscription[]" required><option value="Long-Term">Long-Term</option><option value="Short-Term">Short-Term</option><select></td>
                                       <td class=""><input class="form-control" type="text"  name="specialRequest[]" required></td>
                                 <td>
                                 <button class="btn btn-danger remove sifu-remove-btn" type="button">Remove</button>
                                   </td>
                                 </tr>`
                if (classType == "online") {
                    $('#tbody').append(onlinesubjects);
                    $(".osubje-search").select2();
                } else {
                    $('#tbody').append(physcialSubjects);
                    $(".psubje-search").select2();
                }
            });
            function validateCheckboxes() {
                var checkboxes = $('input[name="day[' + rowIdxTwo + '][]"]');
                var isChecked = false;

                checkboxes.each(function () {
                    if ($(this).is(':checked')) {
                        isChecked = true;
                        return false;
                    }
                });

                return isChecked;
            }

           $('form').submit(function (event) {
            if (!validateCheckboxes()) {
                alert('Please select at least one day.');
                event.preventDefault();
            } else {
                // Disable the submit button to avoid double clicks
                $(this).find(':submit').prop('disabled', true);
            }
        });

            // $('#tbody').on('click', '.remove', function () {
            //     rowIdx = rowIdx - 1;
            //     rowIdxTwo = rowIdxTwo - 1;
            //     var child = $(this).closest('tr').nextAll();
            //     child.each(function () {
            //         var id = $(this).attr('id');
            //         var idx = $(this).children('.row-index').children('p');
            //         var dig = parseInt(id.substring(1));
            //         idx.html(`Row ${dig - 1}`);
            //         $(this).attr('id', `R${dig - 1}`);
            //     });
            //     $(this).closest('tr').remove();
            //     rowIdx--;
            // });
            
            
            $('#tbody').on('click', '.remove', function () {
                if (rowIdx > 0) {
                    rowIdx = rowIdx - 1;
                }
                if (rowIdxTwo > 0) {
                    rowIdxTwo = rowIdxTwo - 1;
                }
                var child = $(this).closest('tr').nextAll();
                child.each(function () {
                    var id = $(this).attr('id');
                    var idx = $(this).children('.row-index').children('p');
                    var dig = parseInt(id.substring(1));
                    idx.html(`Row ${dig - 1}`);
                    $(this).attr('id', `R${dig - 1}`);
                });
                $(this).closest('tr').remove();
                // rowIdx--;
            });
            
            // var rowStudentIdx = 1;
        //     $('#addStudentBtn').on('click', function () {
        //         var selectedParent = $('#parent_id').find(":selected").val();
        //         let appendNewParentStudent=`<tr id="R${rowStudentIdx}">
        //       <td class="">
        //              <select class="form-control js-select" data-search="true" data-sort="true" id="selectStudent${rowStudentIdx}" name="student_ids[]">
                          
        //                   <option value="newStudent">New Student</option>
                           
        //             <select>
        //     </td>
        //     <td class="">
        //     <input type="text" class="studentFullName form-control" id="studentFullName${rowStudentIdx}" name="studentFullName[]"  placeholder="Student Full name">
        //         </td>
        //       <td class=""><select class="form-control" id="studentGender${rowStudentIdx}" name="studentGender[]" data-search="true" data-sort="false"><option value=""></option><option value="Male">Male</option><option value="Female">Female</option></select></td>
        //       <td class=""><input type="number" class="age form-control" id="age${rowStudentIdx}" name="age[]"  placeholder="Age"></td>
        //       <td class=""><input type="number" class="studentDateOfBirth form-control" id="studentDateOfBirth${rowStudentIdx}" name="studentDateOfBirth[]"  placeholder="Date of Birth"></td>
        //       <td class=""><select class="form-control" id="specialNeed${rowStudentIdx}" name="specialNeed[]" data-search="true" data-sort="false">
        //           <option value="">Select Option</option>
        //           <option value="Dyslexia">Dyslexia</option>
        //           <option value="Slow Learner">Slow Learner</option>
        //           <option value="Autism">Autism</option>
        //           <option value="Down Syndrome">Down Syndrome</option>
        //           <option value="OKU">OKU</option>
        //           <option value="None">None</option>
        //       </select></td>
        //  <td>
        //   <button class="btn btn-danger remove sifu-remove-btn" type="button">Remove</button>
        //   </td>
        //  </tr>`;
                
        //         let appendOldParentStudent=`<tr id="R${rowStudentIdx}">

        //       <td class="">
        //              <select class="form-control" data-search="true" data-sort="true" id="selectStudent${rowStudentIdx}" name="student_ids[]">
                     
        //                   <option value="newStudent">New Student</option>
        //                     @foreach($students as $subjectRow)<option value="{{$subjectRow->id}}"> {{$subjectRow->full_name}} - {{$subjectRow->uid}}</option>@endforeach
        //         <select>
        //     </td>
        //     <td class="">
        //     <input type="text" class="studentFullName form-control" id="studentFullName${rowStudentIdx}" name="studentFullName[]"  placeholder="Student Full name">
        //         </td>
        //       <td class=""><select class="form-control" id="studentGender${rowStudentIdx}" name="studentGender[]" data-search="true" data-sort="false"><option value=""></option><option value="Male">Male</option><option value="Female">Female</option></select></td>
        //       <td class=""><input type="number" class="age form-control" id="age${rowStudentIdx}" name="age[]"  placeholder="Age"></td>
        //       <td class=""><input type="number" class="studentDateOfBirth form-control" id="studentDateOfBirth${rowStudentIdx}" name="studentDateOfBirth[]"  placeholder="Date of Birth"></td>
        //       <td class=""><select class="form-control" id="specialNeed${rowStudentIdx}" name="specialNeed[]" data-search="true" data-sort="false">
        //           <option value="">Select Option</option>
        //           <option value="Dyslexia">Dyslexia</option>
        //           <option value="Slow Learner">Slow Learner</option>
        //           <option value="Autism">Autism</option>
        //           <option value="Down Syndrome">Down Syndrome</option>
        //           <option value="OKU">OKU</option>
        //           <option value="None">None</option>
        //       </select></td>
        //  <td>
        //   <button class="btn btn-danger remove sifu-remove-btn" type="button">Remove</button>
        //   </td>
        //  </tr>`;

        //         if(selectedParent=="newParent")
        //         {
        //             $('#studentbody').append(appendNewParentStudent);
        //         }else{
        //             $('#studentbody').append(appendOldParentStudent);
        //         }

        //         $("#selectStudent1").on("change", function () {
        //             var selectedValue1 = $(this).val();
        //             $.ajax({
        //                 url: "{{ url('/getStudentByID/') }}" + "/" + selectedValue1,
        //                 type: 'GET',
        //                 success: function (response) {
        //                     $("#studentFullName1").val(response.studentDetail.full_name);
        //                     $("#age1").val(response.studentDetail.age);
        //                     $("#studentDateOfBirth1").val(response.studentDetail.dob);
        //                     $("#studentGender1").val(response.studentDetail.gender);
        //                     $("#specialNeed1").val(response.studentDetail.specialNeed);
        //                 },
        //                 error: function () {
        //                     $("#result").text("Error fetching value from the server");
        //                 }
        //             });
        //         });
        //         $("#selectStudent2").on("change", function () {
        //             var selectedValue2 = $(this).val();
        //             $.ajax({
        //                 url: "{{ url('/getStudentByID/') }}" + "/" + selectedValue2,
        //                 type: 'GET',
        //                 success: function (response) {
        //                     $("#studentFullName2").val(response.studentDetail.full_name);
        //                     $("#age2").val(response.studentDetail.age);
        //                     $("#studentDateOfBirth2").val(response.studentDetail.dob);
        //                     $("#studentGender2").val(response.studentDetail.gender);
        //                     $("#specialNeed2").val(response.studentDetail.specialNeed);
        //                 },
        //                 error: function () {
        //                     $("#result").text("Error fetching value from the server");
        //                 }
        //             });
        //         });
        //         $("#selectStudent3").on("change", function () {
        //             var selectedValue3 = $(this).val();
        //             $.ajax({
        //                 url: "{{ url('/getStudentByID/') }}" + "/" + selectedValue3,
        //                 type: 'GET',
        //                 success: function (response) {
        //                     $("#studentFullName3").val(response.studentDetail.full_name);
        //                     $("#age3").val(response.studentDetail.age);
        //                     $("#studentDateOfBirth3").val(response.studentDetail.dob);
        //                     $("#studentGender3").val(response.studentDetail.gender);
        //                     $("#specialNeed3").val(response.studentDetail.specialNeed);
        //                 },
        //                 error: function () {
        //                     $("#result").text("Error fetching value from the server");
        //                 }
        //             });
        //         });
        //         $("#selectStudent4").on("change", function () {
        //             var selectedValue4 = $(this).val();
        //             $.ajax({
        //                 url: "{{ url('/getStudentByID/') }}" + "/" + selectedValue4,
        //                 type: 'GET',
        //                 success: function (response) {
        //                     $("#studentFullName4").val(response.studentDetail.full_name);
        //                     $("#age4").val(response.studentDetail.age);
        //                     $("#studentDateOfBirth4").val(response.studentDetail.dob);
        //                     $("#studentGender4").val(response.studentDetail.gender);
        //                     $("#specialNeed4").val(response.studentDetail.specialNeed);
        //                 },
        //                 error: function () {
        //                     $("#result").text("Error fetching value from the server");
        //                 }
        //             });
        //         });
        //         $("#selectStudent5").on("change", function () {
        //             var selectedValue5 = $(this).val();
        //             $.ajax({
        //                 url: "{{ url('/getStudentByID/') }}" + "/" + selectedValue5,
        //                 type: 'GET',
        //                 success: function (response) {
        //                     $("#studentFullName5").val(response.studentDetail.full_name);
        //                     $("#age5").val(response.studentDetail.age);
        //                     $("#studentDateOfBirth5").val(response.studentDetail.dob);
        //                     $("#studentGender5").val(response.studentDetail.gender);
        //                     $("#specialNeed5").val(response.studentDetail.specialNeed);
        //                 },
        //                 error: function () {
        //                     $("#result").text("Error fetching value from the server");
        //                 }
        //             });
        //         });
        //         $("#selectStudent6").on("change", function () {
        //             var selectedValue6 = $(this).val();
        //             $.ajax({
        //                 url: "{{ url('/getStudentByID/') }}" + "/" + selectedValue6,
        //                 type: 'GET',
        //                 success: function (response) {
        //                     $("#studentFullName6").val(response.studentDetail.full_name);
        //                     $("#age6").val(response.studentDetail.age);
        //                     $("#studentDateOfBirth6").val(response.studentDetail.dob);
        //                     $("#studentGender6").val(response.studentDetail.gender);
        //                     $("#specialNeed6").val(response.studentDetail.specialNeed);
        //                 },
        //                 error: function () {
        //                     $("#result").text("Error fetching value from the server");
        //                 }
        //             });
        //         });
        //         $("#selectStudent7").on("change", function () {
        //             var selectedValue7 = $(this).val();
        //             $.ajax({
        //                 url: "{{ url('/getStudentByID/') }}" + "/" + selectedValue7,
        //                 type: 'GET',
        //                 success: function (response) {
        //                     $("#studentFullName7").val(response.studentDetail.full_name);
        //                     $("#age7").val(response.studentDetail.age);
        //                     $("#studentDateOfBirth7").val(response.studentDetail.dob);
        //                     $("#studentGender7").val(response.studentDetail.gender);
        //                     $("#specialNeed7").val(response.studentDetail.specialNeed);
        //                 },
        //                 error: function () {
        //                     $("#result").text("Error fetching value from the server");
        //                 }
        //             });
        //         });

        //         $('#studentDateOfBirth1').on('input', function () {
        //             var dob = $("#studentDateOfBirth1").val();
        //             var today = new Date();
        //             var birthDate = new Date(dob);
        //             var age = today.getFullYear() - birthDate.getFullYear();
        //             if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) {
        //                 age--;
        //             }
        //             $('#age1').val(age);
        //         });
        //         $('#studentDateOfBirth2').on('input', function () {
        //             var dob = $("#studentDateOfBirth2").val();
        //             var today = new Date();
        //             var birthDate = new Date(dob);
        //             var age = today.getFullYear() - birthDate.getFullYear();
        //             if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) {
        //                 age--;
        //             }
        //             $('#age2').val(age);
        //         });
        //         $('#studentDateOfBirth3').on('input', function () {
        //             var dob = $("#studentDateOfBirth3").val();
        //             var today = new Date();
        //             var birthDate = new Date(dob);
        //             var age = today.getFullYear() - birthDate.getFullYear();
        //             if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) {
        //                 age--;
        //             }
        //             $('#age3').val(age);
        //         });
        //         $('#studentDateOfBirth4').on('input', function () {
        //             var dob = $("#studentDateOfBirth4").val();
        //             var today = new Date();
        //             var birthDate = new Date(dob);
        //             var age = today.getFullYear() - birthDate.getFullYear();
        //             if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) {
        //                 age--;
        //             }
        //             $('#age4').val(age);
        //         });
        //         $('#studentDateOfBirth5').on('input', function () {
        //             var dob = $("#studentDateOfBirth5").val();
        //             var today = new Date();
        //             var birthDate = new Date(dob);
        //             var age = today.getFullYear() - birthDate.getFullYear();
        //             if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) {
        //                 age--;
        //             }
        //             $('#age5').val(age);
        //         });
        //         $('#studentDateOfBirth6').on('input', function () {
        //             var dob = $("#studentDateOfBirth6").val();
        //             var today = new Date();
        //             var birthDate = new Date(dob);
        //             var age = today.getFullYear() - birthDate.getFullYear();
        //             if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) {
        //                 age--;
        //             }
        //             $('#age6').val(age);
        //         });
        //         $('#studentDateOfBirth7').on('input', function () {
        //             var dob = $("#studentDateOfBirth7").val();
        //             var today = new Date();
        //             var birthDate = new Date(dob);
        //             var age = today.getFullYear() - birthDate.getFullYear();
        //             if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) {
        //                 age--;
        //             }
        //             $('#age7').val(age);
        //         });

        //         $("#age1").on('input', function () {
        //             var ageInputValue = $("#age1").val();
        //             console.log(ageInputValue);
        //             var age = parseInt(ageInputValue);
        //             var currentDate = new Date();
        //             var birthYear = currentDate.getFullYear() - age;
        //             var dob = new Date(birthYear, 0, 1);
        //             var dobFormatted = dob.toLocaleDateString('en-US', {year: 'numeric'});
        //             $('#dobResult').text(dobFormatted);
        //             $("#studentDateOfBirth1").val(dobFormatted);
        //         });
        //         $("#age2").on('input', function () {
        //             var ageInputValue = $("#age2").val();
        //             console.log(ageInputValue);
        //             var age = parseInt(ageInputValue);
        //             var currentDate = new Date();
        //             var birthYear = currentDate.getFullYear() - age;
        //             var dob = new Date(birthYear, 0, 1);
        //             var dobFormatted = dob.toLocaleDateString('en-US', {year: 'numeric'});
        //             $('#dobResult').text(dobFormatted);
        //             $("#studentDateOfBirth2").val(dobFormatted);
        //         });
        //         $("#age3").on('input', function () {
        //             var ageInputValue = $("#age3").val();
        //             console.log(ageInputValue);
        //             var age = parseInt(ageInputValue);
        //             var currentDate = new Date();
        //             var birthYear = currentDate.getFullYear() - age;
        //             var dob = new Date(birthYear, 0, 1);
        //             var dobFormatted = dob.toLocaleDateString('en-US', {year: 'numeric'});
        //             $('#dobResult').text(dobFormatted);
        //             $("#studentDateOfBirth3").val(dobFormatted);
        //         });
        //         $("#age4").on('input', function () {
        //             var ageInputValue = $("#age4").val();
        //             console.log(ageInputValue);
        //             var age = parseInt(ageInputValue);
        //             var currentDate = new Date();
        //             var birthYear = currentDate.getFullYear() - age;
        //             var dob = new Date(birthYear, 0, 1);
        //             var dobFormatted = dob.toLocaleDateString('en-US', {year: 'numeric'});
        //             $('#dobResult').text(dobFormatted);
        //             $("#studentDateOfBirth4").val(dobFormatted);
        //         });
        //         $("#age5").on('input', function () {
        //             var ageInputValue = $("#age5").val();
        //             console.log(ageInputValue);
        //             var age = parseInt(ageInputValue);
        //             var currentDate = new Date();
        //             var birthYear = currentDate.getFullYear() - age;
        //             var dob = new Date(birthYear, 0, 1);
        //             var dobFormatted = dob.toLocaleDateString('en-US', {year: 'numeric'});
        //             $('#dobResult').text(dobFormatted);
        //             $("#studentDateOfBirth5").val(dobFormatted);
        //         });
        //         $("#age6").on('input', function () {
        //             var ageInputValue = $("#age6").val();
        //             console.log(ageInputValue);
        //             var age = parseInt(ageInputValue);
        //             var currentDate = new Date();
        //             var birthYear = currentDate.getFullYear() - age;
        //             var dob = new Date(birthYear, 0, 1);
        //             var dobFormatted = dob.toLocaleDateString('en-US', {year: 'numeric'});
        //             $('#dobResult').text(dobFormatted);
        //             $("#studentDateOfBirth6").val(dobFormatted);
        //         });
        //         $("#age7").on('input', function () {
        //             var ageInputValue = $("#age7").val();
        //             console.log(ageInputValue);
        //             var age = parseInt(ageInputValue);
        //             var currentDate = new Date();
        //             var birthYear = currentDate.getFullYear() - age;
        //             var dob = new Date(birthYear, 0, 1);
        //             var dobFormatted = dob.toLocaleDateString('en-US', {year: 'numeric'});
        //             $('#dobResult').text(dobFormatted);
        //             $("#studentDateOfBirth7").val(dobFormatted);
        //         });

        //         rowStudentIdx=rowStudentIdx+1;
        //     });
        
        // var rowStudentIdx = 1;
        // $('#addStudentBtn').on('click', function () {
        //     var selectedParent = $('#parent_id').find(":selected").val();
        //     let studentRowTemplate = `
        //         <tr id="R${rowStudentIdx}">
        //             <td class="">
        //                 <select class="form-control js-select selectStudent" data-search="true" data-sort="true" id="selectStudent${rowStudentIdx}" name="student_ids[]">
        //                     <option value="newStudent">New Student</option>
        //                     ${selectedParent !== "newParent" ? '@foreach($students as $subjectRow)<option value="{{$subjectRow->id}}"> {{$subjectRow->full_name}} - {{$subjectRow->uid}}</option>@endforeach' : ''}
        //                 </select>
        //             </td>
        //             <td><input type="text" class="studentFullName form-control" id="studentFullName${rowStudentIdx}" name="studentFullName[]" placeholder="Student Full Name"></td>
        //             <td><select class="form-control" id="studentGender${rowStudentIdx}" name="studentGender[]"><option value=""></option><option value="Male">Male</option><option value="Female">Female</option></select></td>
        //             <td><input type="number" class="age form-control" id="age${rowStudentIdx}" name="age[]" placeholder="Age"></td>
        //             <td><input type="text" class="studentDateOfBirth form-control" id="studentDateOfBirth${rowStudentIdx}" name="studentDateOfBirth[]" placeholder="Date of Birth"></td>
        //             <td><select class="form-control" id="specialNeed${rowStudentIdx}" name="specialNeed[]">
        //                     <option value="">Select Option</option>
        //                     <option value="Dyslexia">Dyslexia</option>
        //                     <option value="Slow Learner">Slow Learner</option>
        //                     <option value="Autism">Autism</option>
        //                     <option value="Down Syndrome">Down Syndrome</option>
        //                     <option value="OKU">OKU</option>
        //                     <option value="None">None</option>
        //                 </select>
        //             </td>
        //             <td><button class="btn btn-danger remove sifu-remove-btn" type="button">Remove</button></td>
        //         </tr>`;
        
        //     $('#studentbody').append(studentRowTemplate);
        
        //     // Bind dynamic event handlers to the new row's elements
        //     $('#selectStudent' + rowStudentIdx).on("change", function () {
        //         var selectedValue = $(this).val();
        //         var rowIdx = $(this).closest('tr').attr('id').replace('R', '');
        
        //         if (selectedValue !== "newStudent") {
        //             $.ajax({
        //                 url: "{{ url('/getStudentByID/') }}" + "/" + selectedValue,
        //                 type: 'GET',
        //                 success: function (response) {
        //                     $("#studentFullName" + rowIdx).val(response.studentDetail.full_name);
        //                     $("#age" + rowIdx).val(response.studentDetail.age);
        //                     $("#studentDateOfBirth" + rowIdx).val(response.studentDetail.dob);
        //                     $("#studentGender" + rowIdx).val(response.studentDetail.gender);
        //                     $("#specialNeed" + rowIdx).val(response.studentDetail.specialNeed);
        //                 },
        //                 error: function () {
        //                     $("#result").text("Error fetching value from the server");
        //                 }
        //             });
        //         }
        //     });
        
        //     $('#studentDateOfBirth' + rowStudentIdx).on('input', function () {
        //         updateAgeFromDOB($(this));
        //     });
        
        //     $('#age' + rowStudentIdx).on('input', function () {
        //         updateDOBFromAge($(this));
        //     });
        
        //     rowStudentIdx++;
        // });
        
        // // Common function to calculate age from Date of Birth
        // function updateAgeFromDOB(dobInput) {
        //     var dob = dobInput.val();
        //     var rowIdx = dobInput.attr('id').replace('studentDateOfBirth', '');
        //     var today = new Date();
        //     var birthDate = new Date(dob);
        //     var age = today.getFullYear() - birthDate.getFullYear();
        //     if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) {
        //         age--;
        //     }
        //     $('#age' + rowIdx).val(age);
        // }
        
        // // Common function to calculate Date of Birth from Age
        // function updateDOBFromAge(ageInput) {
        //     var age = parseInt(ageInput.val());
        //     var rowIdx = ageInput.attr('id').replace('age', '');
        //     var currentDate = new Date();
        //     var birthYear = currentDate.getFullYear() - age;
        //     var dob = new Date(birthYear, 0, 1); // Set to Jan 1st by default
        //     $('#studentDateOfBirth' + rowIdx).val(dob.toISOString().split('T')[0]);
        // }
        
var rowStudentIdx = 1;

// $('#addStudentBtn').on('click', function () {
//     var selectedParent = $('#parent_id').val();
//     var existingParent_id = $('#existingParent_id').val();
    
//     if (selectedParent && selectedParent !== "newParent") {
//         // Fetch students based on selected parent
//         $.ajax({
//             url: "{{ url('/getStudentsByParentID/') }}" + "/" + existingParent_id,
//             type: 'GET',
//             success: function (students) {
    
//                 // Create a new row with the dropdown options populated
//                 let studentOptions = `<option value="newStudent">New Student</option>`;
//                 students.forEach(function(student) {
//                     studentOptions += `<option value="${student.id}">${student.full_name} - ${student.uid}</option>`;
//                 });

//                 let studentRowTemplate = `
//                     <tr id="R${rowStudentIdx}">
//                         <td class="">
//                             <select class="form-control js-select selectStudent" data-search="true" data-sort="true" id="selectStudent${rowStudentIdx}" name="student_ids[]">
//                                 ${studentOptions}
//                             </select>
//                         </td>
//                         <td><input type="text" class="studentFullName form-control" id="studentFullName${rowStudentIdx}" name="studentFullName[]" placeholder="Student Full Name"></td>
//                         <td><select class="form-control" id="studentGender${rowStudentIdx}" name="studentGender[]"><option value=""></option><option value="Male">Male</option><option value="Female">Female</option></select></td>
//                         <td><input type="number" class="age form-control" id="age${rowStudentIdx}" name="age[]" placeholder="Age"></td>
//                         <td><input type="text" class="studentDateOfBirth form-control" id="studentDateOfBirth${rowStudentIdx}" name="studentDateOfBirth[]" placeholder="Date of Birth"></td>
//                         <td><select class="form-control" id="specialNeed${rowStudentIdx}" name="specialNeed[]" required>
//                                 <option value="">Select Option</option>
//                                 <option value="None">None</option>
//                                 <option value="Dyslexia">Dyslexia</option>
//                                 <option value="Slow Learner">Slow Learner</option>
//                                 <option value="Autism">Autism</option>
//                                 <option value="Down Syndrome">Down Syndrome</option>
//                                 <option value="OKU">OKU</option>
//                             </select>
//                         </td>
//                         <td><button class="btn btn-danger remove sifu-remove-btn" type="button">Remove</button></td>
//                     </tr>`;

//                 // Append the new row to the table
//                 $('#studentbody').append(studentRowTemplate);

//                 // Attach event handlers to new row elements
//                 $('#selectStudent' + rowStudentIdx).on("change", function () {
//                     var selectedValue = $(this).val();
//                     var rowIdx = $(this).closest('tr').attr('id').replace('R', '');
//                     if (selectedValue !== "newStudent") {
//                         $.ajax({
//                             url: "{{ url('/getStudentByID/') }}" + "/" + selectedValue,
//                             type: 'GET',
//                             success: function (response) {
//                                 fillStudentDetails(response, rowIdx);
//                             },
//                             error: function () {
//                                 console.error("Error fetching student details.");
//                             }
//                         });
//                     }
//                 });

//                 $('#studentDateOfBirth' + rowStudentIdx).on('input', function () {
//                     updateAgeFromDOB($(this));
//                 });
                
//                 $('#age' + rowStudentIdx).on('input', function () {
//                     updateDOBFromAge($(this));
//                 });

//                 rowStudentIdx++;
//             },
//             error: function () {
//                 console.error("Error fetching students for the selected parent.");
//             }
//         });
//     } else {
//         // Handle case where "newParent" is selected, if needed
//         console.log("New Parent selected; no students to load.");
//     }
// });

$('#addStudentBtn').on('click', function () {
    var selectedParent = $('#parent_id').val();
    var parentFullName = $('#parentFullName').val();
    var existingParent_id = $('#existingParent_id').val();

    
    // if (!parentFullName || parentFullName === "") {
    //     alert("Please select a parent before adding a student.");
    //     return; // Stop function execution if no parent is selected
    // }
    
    // Proceed if parent is selected
    if (selectedParent && selectedParent !== "newParent") {
        // Fetch students based on selected parent
        $.ajax({
            url: "{{ url('/getStudentsByParentID/') }}" + "/" + existingParent_id,
            type: 'GET',
            success: function (students) {
                
                // Capture the selected student ID
                var selectedStudentId = $('#student_id').val();
                console.log("Selected Student ID:", selectedStudentId);
                
                // Generate options excluding the selected student
                let studentOptions = `<option value="newStudent">New Student</option>`;
                students.forEach(function(student) {
                    if (student.id.toString() !== selectedStudentId) {  // Skip the selected student
                        studentOptions += `<option value="${student.id}">${student.full_name} - ${student.uid}</option>`;
                    }
                });

                let studentRowTemplate = `
                    <tr id="R${rowStudentIdx}">
                        <td class="">
                            <select class="form-control js-select selectStudent" data-search="true" data-sort="true" id="selectStudent${rowStudentIdx}" name="student_ids[]">
                                ${studentOptions}
                            </select>
                        </td>
                        <td><input type="text" class="studentFullName form-control" id="studentFullName${rowStudentIdx}" name="studentFullName[]" placeholder="Student Full Name"></td>
                        <td><select class="form-control" id="studentGender${rowStudentIdx}" name="studentGender[]"><option value=""></option><option value="Male">Male</option><option value="Female">Female</option></select></td>
                        <td><input type="number" class="age form-control" id="age${rowStudentIdx}" name="age[]" placeholder="Age"></td>
                        <td><input type="text" class="studentDateOfBirth form-control" id="studentDateOfBirth${rowStudentIdx}" name="studentDateOfBirth[]" placeholder="Date of Birth"></td>
                        <td><select class="form-control" id="specialNeed${rowStudentIdx}" name="specialNeed[]" required>
                                <option value="">Select Option</option>
                                <option value="None">None</option>
                                <option value="Dyslexia">Dyslexia</option>
                                <option value="Slow Learner">Slow Learner</option>
                                <option value="Autism">Autism</option>
                                <option value="Down Syndrome">Down Syndrome</option>
                                <option value="OKU">OKU</option>
                            </select>
                        </td>
                        <td><button class="btn btn-danger remove sifu-remove-btn" type="button">Remove</button></td>
                    </tr>`;

                // Append the new row to the table
                $('#studentbody').append(studentRowTemplate);

                // Attach event handlers to new row elements
                $('#selectStudent' + rowStudentIdx).on("change", function () {
                    var selectedValue = $(this).val();
                    var rowIdx = $(this).closest('tr').attr('id').replace('R', '');
                    if (selectedValue !== "newStudent") {
                        $.ajax({
                            url: "{{ url('/getStudentByID/') }}" + "/" + selectedValue,
                            type: 'GET',
                            success: function (response) {
                                fillStudentDetails(response, rowIdx);
                            },
                            error: function () {
                                console.error("Error fetching student details.");
                            }
                        });
                    }
                });

                $('#studentDateOfBirth' + rowStudentIdx).on('input', function () {
                    updateAgeFromDOB($(this));
                });
                
                $('#age' + rowStudentIdx).on('input', function () {
                    updateDOBFromAge($(this));
                });

                rowStudentIdx++;
            },
            error: function () {
                console.error("Error fetching students for the selected parent.");
            }
        });
    } else {
        // Handle case where "newParent" is selected, if needed
        console.log("New Parent selected; no students to load.");
    }
});


function fillStudentDetails(data, rowIdx) {
    var studentDetail = data.studentDetail; // Access the nested studentDetail object

    console.log(studentDetail); // Check if we are accessing the right object

    // Populate the fields with the student data
    $("#studentFullName" + rowIdx).val(studentDetail.full_name);
    $("#age" + rowIdx).val(studentDetail.age);
    $("#studentDateOfBirth" + rowIdx).val(studentDetail.dob);
    $("#studentGender" + rowIdx).val(studentDetail.gender);
    $("#specialNeed" + rowIdx).val(studentDetail.specialNeed);
}

function updateAgeFromDOB(dobInput) {
    var dob = dobInput.val();
    var rowIdx = dobInput.attr('id').replace('studentDateOfBirth', '');
    var today = new Date();
    var birthDate = new Date(dob);
    var age = today.getFullYear() - birthDate.getFullYear();
    if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) {
        age--;
    }
    $('#age' + rowIdx).val(age);
}

function updateDOBFromAge(ageInput) {
    var age = parseInt(ageInput.val());
    var rowIdx = ageInput.attr('id').replace('age', '');
    var currentDate = new Date();
    var birthYear = currentDate.getFullYear() - age;
    var dob = new Date(birthYear, 0, 1); // Set to Jan 1st by default
    $('#studentDateOfBirth' + rowIdx).val(dob.toISOString().split('T')[0]);
}


            $('#studentbody').on('click', '.remove', function () {

                var child = $(this).closest('tr').nextAll();

                child.each(function () {

                    var id = $(this).attr('id');

                    var idx = $(this).children('.row-index').children('p');

                    var dig = parseInt(id.substring(1));

                    idx.html(`Row ${dig - 1}`);

                    $(this).attr('id', `R${dig - 1}`);
                });

                $(this).closest('tr').remove();

                rowStudentIdx--;
            });


        });
    </script>
    <script>
        function disableButton() {
            document.getElementById("submitButton").disabled = true;
        }


        $(document).ready(function () {
            var $sourceInput = $('#mobile_code');

            var $targetInput = $('#whatsapp_code');

            $sourceInput.on('input', function () {
                var inputValue = $sourceInput.val();

                $targetInput.val(inputValue);
            });


            var $getAgeInput = $('#age');
            var $getStudentDateOfBirth = $('#studentDateOfBirth');

            $getAgeInput.on('input', function () {
                var ageInputValue = $getAgeInput.val();
                var age = parseInt(ageInputValue);
                var currentDate = new Date();
                var birthYear = currentDate.getFullYear() - age;
                var dob = new Date(birthYear, 0, 1);
                var dobFormatted = dob.toLocaleDateString('en-US', {year: 'numeric'});
                $('#dobResult').text(dobFormatted);
                $getStudentDateOfBirth.val(dobFormatted);
            });


            var $getMainAgeInput = $('#mainAge');
            var $getMainStudentDateOfBirth = $('#mainStudentDateOfBirth');

            $getMainAgeInput.on('input', function () {
                var mainAgeInputValue = $getMainAgeInput.val();
                var age = parseInt(mainAgeInputValue);
                var currentDate = new Date();
                var birthYear = currentDate.getFullYear() - age;
                var dob = new Date(birthYear, 0, 1);
                var dobFormatted = dob.toLocaleDateString('en-US', {year: 'numeric'});
                $('#dobResult').text(dobFormatted);
                $getMainStudentDateOfBirth.val(dobFormatted);
            });

            $('#mainStudentDateOfBirth').on('input', function () {
                var dob = $("#mainStudentDateOfBirth").val();

                var today = new Date();
                var birthDate = new Date(dob);
                var age = today.getFullYear() - birthDate.getFullYear();
                if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) {
                    age--;
                }
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
                    $('#customerCity').empty();
                    $('#customerCity').append(data.cities);
                }
            });
        });
        
        
        $("select#classState").change(function () {
            $("#classCity").html('');
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
                    $('#classCity').empty();
                    $('#classCity').append(data.cities);
                }
            });
        });

        $('[name="sameAsCustomerAddress"]').change(function () {
            let parent_id = null
            if ($(this).is(':checked')) {
                parent_id = $("#parent_id").val();
                if (parent_id == "newParent") {
                    var inputCustomerAddressTwo = $('.address1.new').val();
                    var inputCustomerAddressOne = $('.newCustomerAddress1.new').val();
                    $('.classAddress').val(inputCustomerAddressOne);
                    var customerLatitude = $('.customerLatitude.new').val();
                    $('.classLatitude').val(customerLatitude);
                    var customerLongitude = $(".customerLongitude.new").val();
                    $(".classLongitude").val(customerLongitude);
                    $('.classStateDropDown').hide();
                    $('.classCityDropDown').hide();
                    $('.classStateInput').show();
                    $('.classCityInput').show();
                    var customerState = $('.customerState.new').text();
                    $(".classStateInput").val(customerState);
                    var customerCity = $(".customerCity.new option:selected").text();
                    $(".classCityInput").val("");
                    $(".classCityInput").val(customerCity);
                    var customerPostalCode = $(".customerPostalCode.new").val();
                    $(".classPostalCode").val(customerPostalCode);
                } else {
                    var inputCustomerAddressTwo = $('.address1.existing').val();
                    var inputCustomerAddressOne = $('.newCustomerAddress1.new').val();
                    $('.classAddress').val(inputCustomerAddressOne);
                    $('.classAddress').val(inputCustomerAddressOne + ' ' + inputCustomerAddressTwo);
                    var customerLatitude = $('.customerLatitude.new').val();
                    $('.classLatitude').val(customerLatitude);
                    var customerLongitude = $(".customerLongitude.new").val();
                    $(".classLongitude").val(customerLongitude);
                    $('.classStateDropDown').hide();
                    $('.classCityDropDown').hide();
                    $('.classStateInput').show();
                    $('.classCityInput').show();
                    var customerState = $('.customerState.existing').val();
                    $(".classStateInput").val(customerState);
                    var customerCity = $(".customerCity.existing").val();
                    $(".classCityInput").val(customerCity);
                    var customerPostalCode = $(".customerPostalCode.existing").val();
                    $(".classPostalCode").val(customerPostalCode);
                }
            } else {
                $('.classStateDropDown').show();
                $('.classCityDropDown').show();
                $('.classStateInput').hide();
                $('.classCityInput').hide();
            }
        });

        $("select#classType").change(function () {
            var classType = $(this).children("option:selected").val();
            if (classType == "online") {
                $('#classAddressPanel').hide();
            } else {
                $('#classAddressPanel').show();
            }
        });
        google.maps.event.addDomListener(window, 'load', initialize);

        function initialize() {
            var input = document.getElementById('address');
            var studentInput = document.getElementById('classAddress');
            var autocomplete = new google.maps.places.Autocomplete(input);
            var studentAutocomplete = new google.maps.places.Autocomplete(studentInput);
            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();
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
    </script>
    <script>
        $(document).ready(function () {
            $("select#student_id").change(function () {
                var selectedStudent = $(this).children("option:selected").val();
                if (selectedStudent == 'newStudent') {
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
                    $('.customerPhone').val("+60");
                    $('.customerWhatsapp').val("+60");
                    $(".new_hide").hide();
                } else {
                    var userURL = $(this).data('url');
                $.ajax({
                    url: "{{ url('/addTicket/') }}" + "/" + selectedStudent,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        console.log(data);
                        $('.customerId').text(data.customer.uid);
                        $('.customerId').text(data.customer.uid);
                        $('.customerFullName').text(data.customer.full_name);
                        $('.customerFullName').val(data.customer.full_name);
                        $('.parentFullName').val(data.customer.full_name);
                        $('.customerEmail').text(data.customer.email);
                        $('.customerGender').text(data.customer.gender);
                        $('.customerEmail').val(data.customer.email);
                        $('.customerGender').val(data.customer.gender);
                        $('.existingParent_id').val(data.customer.id);
                        $('.customerPhone').text(data.customer.phone);
                        $('.customerPhone').val(data.customer.phone);
                        $('.customerWhatsapp').text(data.customer.whatsapp);
                        $('.customerWhatsapp').val(data.customer.whatsapp);
                        $('.address1').text(data.customer.address);
                        $('.address1').val(data.customer.address);
                        $('.landmark').val(data.customer.landmark);
                        $('.customerStreetAddress2').val(data.customer.address2);
                        $('.customerNRIC').text(data.customer.nric);
                        $('.customerDOB').text(data.customer.dob);
                        $('.customerCity').val(data.customer.cityName);
                        $('.customerState').val(data.customer.stateName);
                        
                        $('.customerLatitude').val(data.customer.latitude);
                        $('.customerLongitude').val(data.customer.longitude);
                        $('.customerPostalCode').val(data.customer.postal_code);
                        $('.customerState.existing').val(data.stateName);
                        $('.customerCity.existing').val(data.cityName);
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
                    $(".new_hide").show();
                } else {
                    $('.commitmentFee').hide();
                    $('.addNewCustomerHeading').hide();
                    $('.existingCustomertHeading').show();
                    $('.newCustomer').hide();
                    $('.existingCustomer').show();
                    console.log("Line 994");
                    $(".new_hide").show();
                }
                var userURL = $(this).data('url');
                $.ajax({
                    url: "{{ url('/addTicketAjaxCallParrent/') }}" + "/" + selectedParent,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        console.log("here is new student " + selectedParent)
                        $('.customerId').text(data.customer.uid);
                        $('.customerId').val(data.customer.uid);
                        $('.customerFullName').val(data.customer.full_name);
                        console.log(data);
                        $('.customerEmail').val(data.customer.email);
                        $('.customerGender').val(data.customer.gender);
                        $('.customerPhone').val(data.customer.phone);
                        $('.customerWhatsapp').val(data.customer.whatsapp);
                        $('.address1').val(data.customer.address);
                        $('.customerStreetAddress2').val(data.customer.address2);
                        $('.customerNRIC').text(data.customer.nric);
                        $('.customerDOB').text(data.customer.dob);
                        $('.customerCity').val(data.cityName);
                        $('.customerState').val(data.stateName);
                        $('.customerLatitude').val(data.customer.latitude);
                        $('.customerLongitude').val(data.customer.longitude);
                        $('.customerPostalCode').val(data.customer.postal_code);
                        if (data.studentLastClass === null) {
                            $('.commitmentFee').show();
                            $('.studentLastClass').text("Student didn't get any class");
                        } else {

                            $('.studentLastClass').text(data.studentLastClass);
                        }
                    }
                });
            });

            $("#email").on("focusout", function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                let customerEmail = $("#email").val();
                $.ajax({
                    url: '{{ route('checkCustomerDuplicateEmailJobTicket') }}',
                    type: 'POST',
                    data: {
                        customerEmail: customerEmail,
                    },
                    success: function (response) {
                        if (response.recordFound == true) {
                            $("#emailErrorMsg").css("display", "block");
                            $("#email").val(' ');
                        } else {
                            $("#emailErrorMsg").css("display", "none");
                        }
                    },
                    error: function (error) {
                        console.error(error);
                        alert('Error occurred during the request.');
                    }
                });
            });
        });
    </script>
@endsection
