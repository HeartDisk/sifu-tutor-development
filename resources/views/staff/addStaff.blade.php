@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
   <div class="fluid-container">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head">
               <div class="nk-block-head-between flex-wrap gap g-2">
                  <div class="nk-block-head-content">
                     <h2 class="nk-block-title">Add Staff</h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Staff List</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Add Staff</li>
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
                        <form method="POST" action="{{route('submitStaff')}}">
                           @csrf
                           <div class="row g-3">
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Staff ID</label>
                                    <div class="form-control-wrap">
                                       <input type="text" class="form-control" readonly name="staffID" value="@php echo 'ST-'.date('dhis'); @endphp" id="staffID" required>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Start Working Date</label>
                                    <div class="form-control-wrap">
                                       <input type="date" class="form-control" name="registration_date" id="startWorkingDate" required>
                                    </div>
                                 </div>
                              </div>
                               <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="company" class="form-label">Attended Training Date</label>
                                    <div class="form-control-wrap">
                                      <input type="date" class="form-control" id="attended_training_date" name="attended_training_date" placeholder="Date of Birth" required>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Role</label>
                                    <div class="form-control-wrap">
                                       <select required data-search="true" data-sort="false" id="role" name="role">
                                          <option value="">Select role</option>
                                          @foreach($roles as $rowRoles)
                                          <option value="{{$rowRoles->id}}">{{$rowRoles->name}}</option>
                                          @endforeach
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Basic Salary</label>
                                    <div class="form-control-wrap">
                                      <input type="text" class="form-control" name="basic_salary" id="basic_salary" required>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="country" class="form-label">Staff Type</label>
                                    <div class="form-control-wrap">
                                       <select class="js-select" id="staff_type" data-search="true" name="type" data-sort="false">
                                          <option value="permanent" selected="selected">Permanent</option>
                                          <option value="probation">Probation</option>
                                          <option value="intern">Intern</option>
                                          <option value="part-time">Part-Time</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="country" class="form-label">Staff Status</label>
                                    <div class="form-control-wrap">
                                       <select id="status" data-search="true" name="status" required data-sort="false">
                                          <option value="">Select Status</option>
                                          <option value="Inactive">Inactive</option>
                                          <option value="Active">Active</option>
                                          <option value="Terminated">Terminated</option>
                                          <option value="part-Resigned">Resigned</option>
                                       </select>
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
                                       <input type="text" class="form-control" id="firstname" name="full_name" placeholder="First name" required>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Gender</label>
                                    <div class="form-control-wrap">
                                       <select id="gender" data-search="true" name="gender" data-sort="false" required>
                                          <option value="">Select Gender</option>
                                          <option value="Male">Male</option>
                                          <option value="Female">Female</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="email" class="form-label">Email address</label>
                                    <div class="form-control-wrap">
                                      <input autocomplete="off" type="text" class="form-control" id="email" name="email" placeholder="Email address" required>
                                       <label id="emailErrorMsg" style="color:red!important;display: none" class="form-label">Email already exist</label>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="form-control-wrap">
                                      <input type="password" class="form-control" id="password" name="password" placeholder="password" required>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="company" class="form-label">Date of Birth</label>
                                    <div class="form-control-wrap">
                                      <input type="date" class="form-control" id="dateOfBirth" name="dob" placeholder="Date of Birth" required>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="username" class="form-label">Age</label>
                                    <div class="form-control-wrap">
                                      <input type="text" class="form-control" id="age" name="age" placeholder="Age" required>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="address" class="form-label">NRIC</label>
                                    <div class="form-control-wrap">
                                      <input type="text" class="form-control" id="nric" name="nric" placeholder="NRIC" required>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="address" class="form-label">Phone Number</label>
                                    <div class="form-control-wrap">
                                      <input type="text" value="+60" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="Phone Number" required>
                                      <label id="contactErrorMsg" style="color:red!important;display: none" class="form-label">Contact Number already exist</label>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Marital Status</label>
                                    <div class="form-control-wrap">
                                       <select id="maritalStatus" name="maritalStatus" data-search="true" data-sort="false" required>
                                          <option value="">Select option</option>
                                          <option value="Single">Single</option>
                                          <option value="Married">Married</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="address" class="form-label">No. of Children</label>
                                    <div class="form-control-wrap">
                                      <input type="text" class="form-control" name="number_of_children" id="number_of_children" required>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="address" class="form-label">Shirt Size</label>
                                    <div class="form-control-wrap">
                                        <select class="form-control" name="shirt_size"id="shirt_size">
                                            <option>Select Shirt Size</option>
                                            <option value="Extra Small">Extra Small</option>
                                            <option value="Small">Small</option>
                                            <option value="Medium">Medium</option>
                                            <option value="Large">Large</option>
                                            <option value="Extra Large">Extra Large</option>    
                                        </select>
                                        
                                      <!--<input type="text" class="form-control" name="shirt_size" id="shirt_size" required>-->
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
                                          <option
                                             value="{{$Bank->bank_name}}">{{$Bank->bank_name}}</option>
                                          @endforeach
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="address" class="form-label">Bank Account Number</label>
                                    <div class="form-control-wrap">
                                      <input type="text" class="form-control" name="bankAccountNumber" id="bankAccountNumber" required>
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
                                      <input type="text" class="form-control" id="streetAddress1" name="street_address1" placeholder="Street Address" required>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="country" class="form-label">State</label>
                                    <div class="form-control-wrap">
                                       <select data-search="true" data-sort="false" name="state" id="customerState">
                                          <option value="">Please select state</option>
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
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="city" class="form-label">City</label>
                                    <div class="form-control-wrap">
                                       <select data-search="true" data-sort="false" name="city" id="customerCity" required>
                                          <option>Select City</option>
                                          <!--@php
                                          $cities = DB::table('cities')->get();
                                          @endphp
                                          @foreach($cities as $rowCities)
                                          <option value="{{$rowCities->name}}">{{$rowCities->name}}</option>
                                          @endforeach-->
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="postalcode" class="form-label">Postal Code</label>
                                    <div class="form-control-wrap">
                                      <input type="text" class="form-control" name="postal_code" id="postalcode" placeholder="Postal code" required>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="city" class="form-label">Latitude</label>
                                    <div class="form-control-wrap">
                                      <input type="text" class="form-control" name="latitude" id="customerLatitude" placeholder="Latitude" required>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="city" class="form-label">Longitude</label>
                                    <div class="form-control-wrap">
                                      <input type="text" class="form-control" name="longitude" id="customerLongitude" placeholder="Longitude" required>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <div class="form-group">
                                    <label for="aboutme" class="form-label">Remark</label>
                                    <div class="form-control-wrap"><textarea class="form-control" id="remark" name="remark" rows="3" required>Remarks Here</textarea>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-2">
                                 <button class="btn btn-primary" type="submit">Submit </button>
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
</div>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key=AIzaSyAP0kk0GU1hG9_-J8ovxiOogeWaOOtwSKo&libraries=places"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyAP0kk0GU1hG9_-J8ovxiOogeWaOOtwSKo"></script>
<script>
// google.maps.event.addDomListener(window, 'load', initialize);

// function initialize() {
//     var input = document.getElementById('streetAddress1');
//     var studentInput = document.getElementById('studentAddress');
//     var autocomplete = new google.maps.places.Autocomplete(input);
//     var studentAutocomplete = new google.maps.places.Autocomplete(studentInput);
//     autocomplete.addListener('place_changed', function () {
//         var place = autocomplete.getPlace();
//         console.log(place);
//         document.getElementById("customerLatitude").value = place.geometry['location'].lat();
//         document.getElementById("customerLongitude").value = place.geometry['location'].lng();
//     });
//     studentAutocomplete.addListener('place_changed', function () {
//         var place = studentAutocomplete.getPlace();
//         document.getElementById("studentLatitude").value = place.geometry['location'].lat();
//         document.getElementById("studentLongitude").value = place.geometry['location'].lng();
//     });
// }

// $("select#customerState").change(function () {
//     $("#customerCity").html('');
//     var customerState = $(this).children("option:selected").val();
//     $.ajax({
//         url: "{{url('addTicketAjaxPOSTcustomerState')}}",
//         dataType: "json",
//         data: {
//             customerState: customerState,
//             _token: '{{csrf_token()}}'
//         },
//         type: "post",
//         success: function (data) {
//             $('#customerCity').append(data.cities);
//         }
//     });
// });

$("#maritalStatus").on("change", function () {
    let maritalStatus = $(this).find('option:selected').text();
    if (maritalStatus== "Single") {
        $("#number_of_children").val(0);
        $("#number_of_children").prop("readonly", true);
    }else{
        $("#number_of_children").prop("readonly", false);
        $("#number_of_children").val("");
    }
});

$("#email").on("focusout", function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let customerEmail = $("#email").val();
    if (customerEmail != null) {
        $.ajax({
            url: '{{ route('checkStaffDuplicateEmail') }}',
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
    }
});

$("#phoneNumber").on("focusout", function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    let tutorMobile = $("#phoneNumber").val();
      $.ajax({
        url: '{{ route('checkStaffDuplicatePhone') }}',
        type: 'POST',
        data: {
            customerMobile: tutorMobile,
        },
        success: function (response) {
            if (response.recordFound == true) {
                $("#contactErrorMsg").css("display", "block");
                $("#phoneNumber").val('+60');
                $("#phoneNumber").val('+60');
            } else {
                $("#contactErrorMsg").css("display", "none");
            }
        },
        error: function (error) {
            console.error(error);
            alert('Error occurred during the request.');
        }
    });
});
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
    
    
    var $getAgeInput = $('#age');
    var $getDateOfBirth = $('#dateOfBirth');
    
    $getAgeInput.on('input', function () {
        var ageInputValue = $getAgeInput.val();
        var age = parseInt(ageInputValue);
        if (!isNaN(age)) {
            var currentDate = new Date();
            var birthYear = currentDate.getFullYear() - age;
            var dob = new Date(birthYear, currentDate.getMonth(), currentDate.getDate());
    
            // Format for display (mm/dd/yy)
            var monthDisplay = String(dob.getMonth() + 1).padStart(2, '0');
            var dayDisplay = String(dob.getDate()).padStart(2, '0');
            var yearDisplay = String(dob.getFullYear()).slice(-2);
            var dobDisplayFormatted = `${monthDisplay}/${dayDisplay}/${yearDisplay}`;
            
            // Format for input value (yyyy-MM-dd)
            var monthInput = String(dob.getMonth() + 1).padStart(2, '0');
            var dayInput = String(dob.getDate()).padStart(2, '0');
            var yearInput = dob.getFullYear();
            var dobInputFormatted = `${yearInput}-${monthInput}-${dayInput}`;
    
            $('#dobResult').text(dobDisplayFormatted); // Display in mm/dd/yy format
            $getDateOfBirth.val(dobInputFormatted); // Set input value in yyyy-MM-dd format
        }
    });
    
    $('#dateOfBirth').on('input', function () {
        var dob = $getDateOfBirth.val();
        var today = new Date();
        var birthDate = new Date(dob);
        var age = today.getFullYear() - birthDate.getFullYear();
        if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) {
            age--;
        }
        $getAgeInput.val(age);
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