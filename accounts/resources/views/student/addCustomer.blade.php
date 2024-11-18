@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
   <div class="fluid-container">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head">
               <div class="nk-block-head-between flex-wrap gap g-2">
                  <div class="nk-block-head-content">
                     <h2 class="nk-block-title">Add Customer / Parent</h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Customer List</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Add Customer / Parent</li>
                        </ol>
                     </nav>
                  </div>
               </div>
            </div>
            <div class="nk-block">
               <div class="card card-gutter-md">
                  <div class="card-body overflow-hidden"> 
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
                        <form method="POST" action="{{route('submitCustomer')}}">
                           @csrf
                           <div class="row">
                              <div class="col-lg-12">
                                 <h3>CUSTOMER / PARENT INFORMATION</h3>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Admin in Charge</label>
                                    <select id="staff_id" name="staff_id" data-search="true" data-sort="false" required>
                                       <option value="">SelectAdmin in Charge</option>
                                       @foreach($staffs as $staff)
                                       <option value="{{$staff->id}}">{{$staff->full_name}}</option>
                                       @endforeach
                                    </select>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Full Name</label>
                                    <div class="form-control-wrap">
                                       <input type="text" class="form-control" id="fullName" name="customerFullName" placeholder="Full name" required>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Gender</label>
                                    <div class="form-control-wrap">
                                       <select id="gender" name="customerGender" data-search="true" data-sort="false" required>
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
                                    <div class="form-control-wrap"><input type="text" class="form-control" name="customerEmail" id="email" placeholder="Email address" required>
                                       <label id="emailErrorMsg" style="color:red!important;display: none" class="form-label">Email already exist</label>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="email" class="form-label">Landmark</label>
                                    <div class="form-control-wrap"><input type="text" class="form-control" name="landmark" id="landmark" placeholder="Enter nearby landmark" required>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="phonenumber" class="form-label">Phone Number</label>
                                    <div class="form-control-wrap">
                                       <input value="+60" type="tel" minlength="9" maxlength="10" class="form-control" name="customerPhone" id="mobile_code" placeholder="Phone" required>
                                       <label id="contactErrorMsg" style="color:red!important;display: none" class="form-label">Contact Number already exist</label>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="whatsappnumber" class="form-label">WhatsApp
                                    Number</label>
                                    <div class="form-control-wrap"><input value="+60" type="tel" minlength="9" maxlength="10" class="form-control" name="customerWhatsapp" id="whatsapp_code" placeholder="Whatsapp" required>
                                       <label id="whatsappErrorMsg" style="color:red!important;display: none" class="form-label">Whatsapp Number already exist</label>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-lg-12">
                                 <h3>CUSTOMER ADDRESS</h3>
                              </div>
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <label for="city" class="form-label">Full Address</label>
                                    <div class="form-control-wrap"><input type="text" class="form-control" name="customerStreetAddress1" id="address" placeholder="Full Address" required>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="city" class="form-label">Latitude</label>
                                    <div class="form-control-wrap"><input type="text" class="form-control" name="customerLatitude" id="customerLatitude" placeholder="Latitude" required>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="city" class="form-label">Longitude</label>
                                    <div class="form-control-wrap"><input type="text" class="form-control" id="customerLongitude" name="customerLongitude" placeholder="Longitude" required>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="city" class="form-label">State</label>
                                    <div class="form-control-wrap">
                                       <select class=" js-select" data-search="true" data-sort="false" name="customerState" id="customerState" required>
                                          <option value="">Select State</option>
                                          @php
                                          $states = DB::table('states')->get();
                                          @endphp
                                          @foreach($states as $rowStates)
                                          <option value="{{$rowStates->id}}">{{$rowStates->name}}</option>
                                          @endforeach
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="city" class="form-label">City</label>
                                    <div class="form-control-wrap">
                                       <select class=" form-control" data-search="true" data-sort="false" name="customerCity" id="customerCity">
                                          <option value="">Select City</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="postalcode" class="form-label">Postal Code</label>
                                    <div class="form-control-wrap"><input type="text" class="form-control" name="customerPostalcode" id="postalcode" placeholder="Zip code">
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
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
google.maps.event.addDomListener(window, 'load', initialize);
function initialize() {
   var input = document.getElementById('address');
   var studentInput = document.getElementById('studentAddress');
   var autocomplete = new google.maps.places.Autocomplete(input);
   var studentAutocomplete = new google.maps.places.Autocomplete(studentInput);
   autocomplete.addListener('place_changed', function () {
       var place = autocomplete.getPlace();
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
   $("#email").on("focusout", function () {
       $.ajaxSetup({
           headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }
       });

       let customerEmail = $("#email").val();
       $.ajax({
           url: '{{ route('checkCustomerDuplicateEmail') }}',
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

   $("#mobile_code").on("focusout", function () {
       $.ajaxSetup({
           headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }
       });

       let tutorMobile = $("#mobile_code").val();
       $.ajax({
           url: '{{ route('checkCustomerDuplicatePhone') }}',
           type: 'POST',
           data: {
               customerMobile: tutorMobile,
           },
           success: function (response) {

               if (response.recordFound == true) {
                   $("#contactErrorMsg").css("display", "block");
                   $("#mobile_code").val('+60');
                   $("#whatsapp_code").val('+60');

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

   $("#whatsapp_code").on("focusout", function () {
       $.ajaxSetup({
           headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }
       });

       let tutorMobile = $("#whatsapp_code").val();
       $.ajax({
           url: '{{ route('checkCustomerDuplicatePhone') }}',
           type: 'POST',
           data: {
               customerWhatsapp: tutorMobile,
           },
           success: function (response) {
               if (response.recordFound == true) {
                   $("#whatsappErrorMsg").css("display", "block");
                   $("#mobile_code").val('+60');
                   $("#whatsapp_code").val('+60');
               } else {
                   $("#whatsappErrorMsg").css("display", "none");
               }
           },
           error: function (error) {
               console.error(error);
               alert('Error occurred during the request.');
           }
       });
   });

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

var $sourceInput = $('#mobile_code');
var $targetInput = $('#whatsapp_code');
$sourceInput.on('input', function () {
   var inputValue = $sourceInput.val();
   $targetInput.val(inputValue);
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