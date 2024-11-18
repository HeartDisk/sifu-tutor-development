@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
   <div class="fluid-container">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head">
               <div class="nk-block-head-between flex-wrap gap g-2">
                  <div class="nk-block-head-content">
                     <h2 class="nk-block-title">Edit Staff: {{$staff->full_name}}</h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Staff List</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Edit Staff</li>
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
                     @endif
                     @if (\Session::has('update'))
                     <div class="alert alert-primary">
                        <ul>
                           <li>{!! \Session::get('update') !!}</li>
                        </ul>
                     </div>
                     @endif
                     <div class="bio-block">
                        <form method="POST" action="{{route('submitEditStaff')}}">
                           @csrf
                           <input type="hidden" value="{{$staff->id}}" name="id"/>
                           <div class="row">
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Staff ID</label>
                                    <div class="form-control-wrap">
                                       <input type="text" class="form-control" readonly name="staffID" value="{{$staff->uid}}" id="staffID">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Start Working Date</label>
                                    <div class="form-control-wrap">
                                       <input type="date" class="form-control" readonly name="registration_date" value="{{$staff->start_date}}" id="startWorkingDate">
                                    </div>
                                 </div>
                              </div>
                               <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="company" class="form-label">Attended Training Date</label>
                                    <div class="form-control-wrap">
                                      <input type="date" class="form-control" id="attended_training_date" value="{{$staff->attended_training_date}}" name="attended_training_date" placeholder="Date of Birth" required>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Basic Salary</label>
                                    <div class="form-control-wrap">
                                       <input type="text" class="form-control" name="basic_salary" value="{{$staff->basic_salary}}" id="desgnation">
                                    </div>
                                 </div>
                              </div>
                              @php
                              $user=DB::table("users")->where("id",$staff->user_id)->first();
                              $role=DB::table("roles")->where("id",$user->role)->first();
                              @endphp
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Role</label>
                                    <div class="form-control-wrap">
                                       <select required data-search="true" data-sort="false" id="role" name="role">
                                          <option value="">A</option>
                                          @foreach($roles as $rowRoles)
                                          <option {{$role->id==$rowRoles->id?"selected":""}} value="{{$rowRoles->id}}">{{$rowRoles->name}}</option>
                                          @endforeach
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <input type="hidden" name="user_id" value="{{$staff->user_id}}">
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="country" class="form-label">Staff Type</label>
                                    <div class="form-control-wrap">
                                       <select id="staff_type" data-search="true" name="type" data-sort="false">
                                       <option {{$staff->type=="permanent"?"selected":""}} value="permanent">Permanent</option>
                                       <option {{$staff->type=="probation"?"selected":""}}  value="probation">Probation</option>
                                       <option {{$staff->type=="intern"?"selected":""}}  value="intern">Intern</option>
                                       <option {{$staff->type=="part-time"?"selected":""}}  value="part-time">Part-Time</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="country" class="form-label">Staff Status</label>
                                    <div class="form-control-wrap">
                                       <select id="status" data-search="true" name="status" data-sort="false">
                                       <option {{$staff->status=="Inactive"?"selected":""}}  value="Inactive">Inactive</option>
                                       <option {{$staff->status=="Active"?"selected":""}} value="Active">Active</option>
                                       <option {{$staff->status=="Terminated"?"selected":""}} value="Terminated">Terminated</option>
                                       <option {{$staff->status=="Resigned"?"selected":""}} value="Resigned">Resigned</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="country" class="form-label">Password</label>
                                    <div class="form-control-wrap">
                                       <input type="password" class="form-control" id="password" name="password">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <h3>STAFF PERSONAL INFORMATION</h3>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Full Name</label>
                                    <div class="form-control-wrap">
                                       <input type="text" class="form-control" id="firstname" value="{{$staff->full_name}}" name="full_name" placeholder="First name">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Gender</label>
                                    <div class="form-control-wrap">
                                       <select id="gender" data-search="true" name="gender" data-sort="false">
                                       <option {{$staff->gender == 'Male'?'selected':''}} value="Male">Male</option>
                                       <option {{$staff->gender == 'Female'?'selected':''}} value="Female">Female</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="username" class="form-label">Age</label>
                                    <div class="form-control-wrap">
                                       <input type="text" class="form-control" id="age" value="{{$staff->age}}" name="age" placeholder="Age">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="email" class="form-label">Email address</label>
                                    <div class="form-control-wrap">
                                       <input type="text" class="form-control" id="email" value="{{$staff->email}}" name="email" placeholder="Email address">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="company" class="form-label">Date of Birth</label>
                                    <div class="form-control-wrap">
                                       <input type="date" class="form-control" id="dateOfBirth" value="{{$staff->dob}}" name="dob" placeholder="Date of Birth">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="address" class="form-label">NRIC</label> 
                                    <div class="form-control-wrap">
                                       <input type="text" class="form-control" id="CNIC" name="nric" value="{{$staff->nric}}" placeholder="NRIC">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="address" class="form-label">Phone Number</label>
                                    <div class="form-control-wrap">
                                       <input type="text" class="form-control" id="phoneNumber" value="{{$staff->phone}}" name="phoneNumber" placeholder="Phone Number">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Marital Status</label>
                                    <div class="form-control-wrap">
                                       <select class="js-select" id="maritalStatus" name="maritalStatus" data-search="true" data-sort="false">
                                       <option {{$staff->marital_status == 'Single'? 'selected':''}} value="Single">Single</option>
                                       <option {{$staff->marital_status == 'Married'? 'selected':''}} value="Married">Married</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="address" class="form-label">Bank Name</label>
                                    <div class="form-control-wrap">
                                       <select data-search="true" data-sort="false" name="bankName" id="bankName" required>
                                            <option value="">Please select Bank</option>
                                            @php
                                                $Banks = DB::table('Banks')->get();
                                            @endphp
                                            @foreach($Banks as $Bank)
                                                <option value="{{ $Bank->bank_name }}" 
                                                    @if(isset($staff) && $staff->bank_name == $Bank->bank_name) selected @endif>
                                                    {{ $Bank->bank_name }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="address" class="form-label">Bank Account Number</label>
                                    <div class="form-control-wrap">
                                       <input type="text" class="form-control" name="bank_account_number" value="{{$staff->bank_account_number}}" id="bankAccountNumber">
                                    </div>
                                 </div>
                              </div>
                              
                              
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="address" class="form-label">Shirt Size</label>
                                    <div class="form-control-wrap">
                                        <select class="form-control" name="shirt_size"id="shirt_size">
                                            <option>Select Shirt Size</option>
                                            <option {{ $staff->shirt_size == "Extra Small" ? "selected" : "" }} value="Extra Small">Extra Small</option>
                                            <option {{ $staff->shirt_size == "Small" ? "selected" : "" }} value="Small">Small</option>
                                            <option {{ $staff->shirt_size == "Medium" ? "selected" : "" }} value="Medium">Medium</option>
                                            <option {{ $staff->shirt_size == "Large" ? "selected" : "" }} value="Large">Large</option>
                                            <option {{ $staff->shirt_size == "Extra Large" ? "selected" : "" }} value="Extra Large">Extra Large</option>
                                        </select>
                                        
                                       <!--<input type="text" class="form-control" name="shirt_size" value="{{$staff->shirt_size}}" id="bankAccountNumber">-->
                                    </div>
                                 </div>
                              </div>
                              
                              
                              <div class="col-lg-12">
                                 <h3>STAFF ADDRESS</h3>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="city" class="form-label">Street Address</label>
                                    <div class="form-control-wrap">
                                       <input type="text" class="form-control" id="streetAddress1" value="{{$staff->address}}" name="street_address1" placeholder="Street Address">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="country" class="form-label">State</label>
                                    <div class="form-control-wrap">
                                       <select id="customerState" data-search="true" name="state" data-sort="false">
                                       @foreach($states as $rowStates)
                                       <option
                                       {{ $rowStates->id==$staff->state?'selected':'' }} value="{{$rowStates->id}}">{{$rowStates->name}}</option>
                                       @endforeach
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="city" class="form-label">City</label>
                                    <div class="form-control-wrap">
                                       <select id="customerCity" data-search="true" name="city" data-sort="false">
                                       @foreach($cities as $city)
                                       <option
                                       {{ $city->id==$staff->city?'selected':'' }} value="{{$city->id}}">{{$city->name}}</option>
                                       @endforeach
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="postalcode" class="form-label">Postal Code</label>
                                    <div class="form-control-wrap">
                                       <input type="text" class="form-control" value="{{$staff->postal_code}}" name="postal_code" id="postalcode" placeholder="Postal code">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="city" class="form-label">Latitude</label>
                                    <div class="form-control-wrap">
                                       <input type="text" class="form-control" name="latitude" id="customerLatitude" placeholder="Latitude" value="{{$staff->latitude}}" required>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="city" class="form-label">Longitude</label>
                                    <div class="form-control-wrap">
                                       <input type="text" class="form-control" name="longitude" id="customerLongitude" placeholder="Longitude" value="{{$staff->longitude}}" required>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <div class="form-group">
                                    <label for="aboutme" class="form-label">Remark</label>
                                    <div class="form-control-wrap">
                                       <textarea class="form-control" id="remark" value="{{$staff->remark}}" name="remark" rows="3">{{$staff->remark}}</textarea> 
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-2">
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

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key=AIzaSyAP0kk0GU1hG9_-J8ovxiOogeWaOOtwSKo&libraries=places"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyAP0kk0GU1hG9_-J8ovxiOogeWaOOtwSKo"></script>
<script>
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

  google.maps.event.addDomListener(window, 'load', initialize);
  function initialize() {
      var input = document.getElementById('streetAddress1');
      var studentInput = document.getElementById('studentAddress');
      var autocomplete = new google.maps.places.Autocomplete(input);
      var studentAutocomplete = new google.maps.places.Autocomplete(studentInput);
      autocomplete.addListener('place_changed', function () {
          var place = autocomplete.getPlace();
          console.log(place);
          document.getElementById("customerLatitude").value = place.geometry['location'].lat();
          document.getElementById("customerLongitude").value = place.geometry['location'].lng();
      });
      studentAutocomplete.addListener('place_changed', function () {
          var place = studentAutocomplete.getPlace();
          document.getElementById("studentLatitude").value = place.geometry['location'].lat();
          document.getElementById("studentLongitude").value = place.geometry['location'].lng();
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
@endsection