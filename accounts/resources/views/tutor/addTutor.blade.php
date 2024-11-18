@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
   <div class="fluid-container">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head">
               <div class="nk-block-head-between flex-wrap gap g-2">
                  <div class="nk-block-head-content">
                     <h2 class="nk-block-title">Add Tutor</h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Tutor List</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Add Tutor</li>
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
                        <form id="tutorForm" method="POST" action="{{ route('submitTutor') }}" enctype="multipart/form-data">
                           @csrf
                           <div class="row">
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Tutor ID</label>
                                    <div class="form-control-wrap"><input type="text" class="form-control" readonly name="tutorID" value="@php echo 'TU-'.date('dhis'); @endphp" id="tutorID"></div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Verification Date</label>
                                    <div class="form-control-wrap"><input type="date" class="form-control" name="registration_date" id="startWorkingDate"></div>
                                 </div>
                              </div>
                           

                              <div class="col-lg-12">
                                <h3>TUTOR PERSONAL INFORMATION</h3>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Full Name</label>
                                    <div class="form-control-wrap"><input type="text" class="form-control" id="firstname" name="full_name" placeholder="First name" required></div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Gender</label>
                                    <div class="form-control-wrap">
                                       <select class="js-select" id="gender" data-search="true" name="gender" data-sort="false" required>
                                          <option value="">Select Gender</option>
                                          <option value="male">Male</option>
                                          <option value="female">Female</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="email" class="form-label">Email address</label>
                                    <div class="form-control-wrap">
                                       <input type="text" class="form-control" id="email" name="email" placeholder="Email address" required>
                                       <label id="emailErrorMsg" style="color:red!important;display: none" class="form-label">Email already exist</label>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="address" class="form-label">NRIC</label>
                                    <div class="form-control-wrap"><input type="text" class="form-control" id="nric" name="nric" placeholder="NRIC" required ></div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group none" style="display: none;" >
                                    <label for="country" class="form-label">Country</label>
                                    <div class="form-control-wrap">
                                       <!-- Country dropdown -->
                                       <select id="country" class="form-control" onchange="updatePhoneNumber()">
                                          <option value="us">United States (+1)</option>
                                          <option value="uk">United Kingdom (+44)</option>
                                          <!-- Add more options as needed -->
                                       </select>
                                    </div>
                                 </div>
                                 <div class="form-group ">
                                    <label for="phoneNumber" class="form-label">Phone Number</label>
                                    <div class="form-control-wrap">
                                       <!-- Input for phone number -->
                                       <input type="text" class="form-control" id="mobile_code" name="phoneNumber" placeholder="Phone Number" required >
                                       <label id="contactErrorMsg" style="color:red!important;display: none" class="form-label">Contact Number already exist</label>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="address" class="form-label">Whatsapp Number</label>
                                    <div class="form-control-wrap"><input type="text" class="form-control" value="+60" id="whatsapp_code" name="whatsapp" placeholder="Whatsapp Number" required ></div>
                                    <label id="whatsappErrorMsg" style="color:red!important;display: none" class="form-label">Whatsapp Number already exist</label>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Marital Status</label>
                                    <div class="form-control-wrap">
                                       <select class="js-select" id="maritalStatus" name="maritalStatus" data-search="true" data-sort="false">
                                          <option value="">Select Marital Status</option>
                                          <option value="Single">Single</option>
                                          <option value="Married">Married</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="address" class="form-label">Bank Name</label>
                                    <div class="form-control-wrap"><input type="text" class="form-control" id="bankName" name="bankName" placeholder="Bank Name" required ></div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="address" class="form-label">Bank Account Number</label>
                                    <div class="form-control-wrap"><input type="text" class="form-control" name="bankAccountNumber" id="bankAccountNumber" placeholder="Bank Account Number" required ></div>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                <h3>TUTOR ADDRESS</h3>
                              </div>
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <label for="city" class="form-label">Street Address 1</label>
                                    <div class="form-control-wrap"><input type="text" class="form-control" id="streetAddress1" name="street_address1" placeholder="Street Address" required ></div>
                                 </div>
                              </div>
                              <div class=" col-lg-3">
                                 <div class="form-group">
                                    <label for="city" class="form-label">Latitude</label>
                                    <div class="form-control-wrap"><input readonly type="text" class="customerLatitude form-control" name="customerLatitude" id="customerLatitude" placeholder="Latitude" required ></div>
                                 </div>
                              </div>
                              <div class=" col-lg-3">
                                 <div class="form-group">
                                    <label for="city" class="form-label">Longitude</label>
                                    <div class="form-control-wrap"><input readonly type="text" class="customerLongitude form-control" id="customerLongitude" name="customerLongitude" placeholder="Longitude"></div>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <label for="city" class="form-label">State</label>
                                    <div class="form-control-wrap">
                                       <select class=" js-select" data-search="true" data-sort="false"  name="state" id="customerState"  >
                                          <option value="">Please select state</option>
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
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <label for="city" class="form-label">City</label>
                                    <div class="form-control-wrap">
                                       <select class=" form-control" data-search="true" data-sort="false"  name="city" id="customerCity" placeholder="City" >
                                          <option value="">Select City</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <label for="postalcode" class="form-label">Postal Code</label>
                                    <div class="form-control-wrap"><input type="text" class="form-control" name="postalcode" id="postalcode" placeholder="Postal code"></div>
                                 </div>
                              </div>

                              <div class="col-lg-12">
                                 <h3>COMMITMENT FEE</h3>
                                 <p>RM 100 payment receipt is required to be uploaded for a new tutor registration.</p>
                                 <div class="col-3">
                                    <button class="btn btn-md btn-primary" id="addFeePaymentBtn" type="button">Add New Fee</button> 
                                 </div>
                                 <div class="container pt-4">
                                    <div class="table-responsive">
                                       <table class="table table-bordered">
                                          <thead class="table-dark">
                                             <tr>
                                                <th class="text-center">Payment Attachment</th>
                                                <th class="text-center">Payment Amount</th>
                                                <th class="text-center">Fee Payment Date</th>
                                                <th class="text-center">Receiving Account</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Action</th>
                                             </tr>
                                          </thead>
                                          <tbody id="feePaymentBody">
                                             <tr>
                                                <td class="row-index text-center"><input type="file" class="form-control" name="paymentAttachment" id="paymentAttachment"></td>
                                                <td class="row-index text-center"><input type="number" class="form-control" name="feeAmount" id="feeAmount" placeholder="Enter the Fee Amount"></td>
                                                <td class="row-index text-center"><input type="date" class="form-control" name="feePaymentDate" id="feePaymentDate" ></td>
                                                <td class="row-index text-center">
                                                   <select class="form-control" name="receivingAccount" id="receivingAccount" data-search="true" data-sort="false">
                                                      </>
                                                      <option value="">Select Your Receiving Account</option>
                                                      <option value="Cash At Bank - My Bank">Cash At Bank - My Bank</option>
                                                      <option value="Cash in Hand">Cash in Hand</option>
                                                      <option value="Payment Gateway">Payment Gateway</option>
                                                   </select>
                                                </td>
                                                <td class="row-index text-center">
                                                   <select class="form-control" name="payment_status" id="status" data-search="true" data-sort="false">
                                                      <option value="">Select Your Status</option>
                                                      <option value="complete">Complete</option>
                                                      <option value="incomplete">Incomplete</option>
                                                   </select>
                                                </td>
                                          </tbody>
                                       </table>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-12"><button class="btn btn-primary" type="submit">Submit</button></div>
                              <script></script>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script>

  function updatePhoneNumber() {
      countryCode="+60";
    $("#mobile_code").val(countryCode);
  }
  updatePhoneNumber();

    $(document).ready(function () {
      var rowIdx = 0;
      $('#tbody').on('click', '.remove', function () {
        var child = $(this).closest('tr').nextAll();
        child.each(function () {
          var id = $(this).attr('id');
          var idx = $(this).children('.row-index').children('p');
          var dig = parseInt(id.substring(1));
          idx.html(`Row ${dig - 1}`);
          $(this).attr('id', `R${dig - 1}`);
        });
        $(this).closest('tr').remove();
        rowIdx--;
      });

      var rowFeePaymentIdx = 0;
      $('#addFeePaymentBtn').on('click', function () {
        $('#feePaymentBody').append(`<tr id="R${++rowFeePaymentIdx}">
        <td class="row-index text-center"><input type="file" class="form-control" name="paymentAttachment[]" id="paymentAttachment${rowFeePaymentIdx}" required></td>
        <td class="row-index text-center"><input type="number" class="form-control" name="feeAmount[]" id="feeAmount${rowFeePaymentIdx}" placeholder="Enter the Fee Amount" required></td>
        <td class="row-index text-center"><input type="date" class="form-control" name="feePaymentDate[]" id="feePaymentDate${rowFeePaymentIdx}" required ></td>
        <td class="row-index text-center"><select class="form-control" name="receivingAccount[]" id="receivingAccount${rowFeePaymentIdx}" data-search="true" data-sort="false" required>
        <option value="">Select Your Receiving Account</option>
        <option value="Cash At Bank - My Bank">Cash At Bank - My Bank</option>
        <option value="Cash in Hand">Cash in Hand</option>
        <option value="Payment Gateway">Payment Gateway</option>
      </select></td>
      <td class="row-index text-center"><select class="form-control" name="payment_status[]" id="receivingAccount${rowFeePaymentIdx}" data-search="true" data-sort="false" required></>
        <option value="">Select Your Receiving Account</option>
        <option value="complete">complete</option>
        <option value="incomplete">Incomplete</option>
      </select></td>
      <td class="text-center">
        <button class="btn btn-danger remove"
          type="button">Remove</button>
        </td>
      </tr>`);
      });

      $('#feePaymentBody').on('click', '.remove', function () {
        var child = $(this).closest('tr').nextAll();
        child.each(function () {
          var id = $(this).attr('id');
          var idx = $(this).children('.row-index').children('p');
          var dig = parseInt(id.substring(1));
          idx.html(`Row ${dig - 1}`);
          $(this).attr('id', `R${dig - 1}`);
        });
        $(this).closest('tr').remove();
        rowFeePaymentIdx--;
      });

    });

document.getElementById('tutorForm').addEventListener('submit', function (event) {
        resetErrors();
        if (!validateForm()) {
            event.preventDefault();
        }
    });

    function resetErrors() {
        var errorElements = document.querySelectorAll('.error-message');
        errorElements.forEach(function (element) {
            element.remove();
        });
    }

    function validateForm() {
        var isValid = true;
        var requiredFields = document.querySelectorAll('[required]');
        requiredFields.forEach(function (field) {
            if (!field.value.trim()) {
                displayErrorMessage(field, 'This field is required.');
                isValid = false;
            }
        });
        return isValid;
    }

    function displayErrorMessage(field, message) {
        var errorMessage = document.createElement('div');
        errorMessage.className = 'error-message';
        errorMessage.textContent = message;
        field.parentNode.appendChild(errorMessage);
    }

    $(document).ready(function() {
  var $sourceInput = $('#mobile_code');
  var $targetInput = $('#whatsapp_code');
  $sourceInput.on('input', function() {
    var inputValue = $sourceInput.val();
    $targetInput.val(inputValue);
  });

   $("select#customerState").change(function(){
        $("#customerCity").html('');
        var customerState = $(this).children("option:selected").val();
        $.ajax({
            url: "{{url('addTicketAjaxPOSTcustomerState')}}",
            dataType:"json",
            data: {
                        customerState: customerState,
                        _token: '{{csrf_token()}}'
                    },
            type: "post",
            success: function(data){
               $('#customerCity').append(data.cities);
            }
        });
    });

   $("#email").on("focusout",function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            let customerEmail=$("#email").val();
            $.ajax({
                url: '{{ route('checkTutorDuplicateEmail') }}',
                type: 'POST',
                data: {
                    customerEmail: customerEmail,
                },
                success: function (response) {
                    if(response.recordFound==true)
                    {
                        $("#emailErrorMsg").css("display","block");
                        $("#email").val(' ');
                    }else{
                        $("#emailErrorMsg").css("display","none");
                    }
                },
                error: function (error) {
                    console.error(error);
                    alert('Error occurred during the request.');
                }
            });
        });

   $("#mobile_code").on("focusout",function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            let tutorMobile=$("#mobile_code").val();
            $.ajax({
                url: '{{ route('checkTutorDuplicatePhone') }}',
                type: 'POST',
                data: {
                    tutorMobile: tutorMobile,
                },
                success: function (response) {
                    if(response.recordFound==true)
                    {
                        $("#contactErrorMsg").css("display","block");
                        $("#mobile_code").val('+60');
                        $("#whatsapp_code").val('+60');
                    }else{
                        $("#contactErrorMsg").css("display","none");
                    }
                },
                error: function (error) {
                    console.error(error);
                    alert('Error occurred during the request.');
                }
            });
        });

   $("#whatsapp_code").on("focusout",function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            let tutorMobile=$("#whatsapp_code").val();
            $.ajax({
                url: '{{ route('checkTutorDuplicatePhone') }}',
                type: 'POST',
                data: {
                    tutorWhatsapp: tutorMobile,
                },
                success: function (response) {
                    if(response.recordFound==true)
                    {
                        $("#whatsappErrorMsg").css("display","block");
                        $("#mobile_code").val('+60');
                        $("#whatsapp_code").val('+60');
                    }else{
                        $("#whatsappErrorMsg").css("display","none");
                    }
                },
                error: function (error) {
                    console.error(error);
                    alert('Error occurred during the request.');
                }
            });
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

    $(document).on('change', '#'+input, function () {
        document.getElementById('customerLatitude').value = '';
        document.getElementById('customerLongitude').value = '';
        document.getElementById('latitude_view').innerHTML = '';
        document.getElementById('longitude_view').innerHTML = '';
    });
    var wto;
     $(document).on('change', '#'+studentInput, function () {
        document.getElementById('customerLatitude').value = '';
        document.getElementById('customerLongitude').value = '';
        document.getElementById('latitude_view_two').innerHTML = '';
        document.getElementById('longitude_view_two').innerHTML = '';
    });
</script>
@endsection