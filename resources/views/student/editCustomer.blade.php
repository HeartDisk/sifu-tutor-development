@extends('layouts.main')
@section('content')
<div class="nk-content sifu-aed-page">
   <div class="fluid-container">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head">
               <div class="nk-block-head-between flex-wrap gap g-2">
                  <div class="nk-block-head-content">
                     <h2 class="nk-block-title">Customer Edit</h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Customer List</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Customer Edit</li>
                        </ol>
                     </nav>
                  </div>
               </div>
            </div>
            <div class="nk-block">
               <div class="card">
                  <div class="card-body">
                     <div class="row">
                        <div class="col-md-12">
                           <form method="post" action="{{url('submitEditCustomer')}}" id="editForm"
                              name="editForm" enctype="multipart/form-data" novalidate="novalidate">
                              @csrf
                              <input name="id" type="hidden" value="{{$customers->id}}">
                              <div class="row">
                                 <div class="col-lg-12">
                                    <h3>CUSTOMER / PARENT INFORMATION</h3>
                                 </div>
                                 <div class="col-lg-3">
                                    <div class="form-group">
                                       <label for="firstname" class="form-label">Admin in Charge</label>
                                       <select id="staff_id" name="staff_id" data-search="true" data-sort="false" required>
                                          <option value=1>SelectAdmin in Charge</option>
                                          @foreach($staffs as $staff)
                                          <option
                                          {{$customers->staff_id==$staff->id?"selected":""}} value="{{$staff->id}}">{{$staff->full_name}}</option>
                                          @endforeach
                                       </select>
                                    </div>
                                 </div>
                                 <div class="col-lg-3">
                                    <div class="form-group">
                                       <label for="firstname" class="form-label">Full Name</label>
                                       <div class="form-control-wrap">
                                          <input type="text" class="form-control" id="fullName" name="customerFullName" value="{{$customers->full_name}}">
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-3">
                                    <div class="form-group">
                                       <label for="firstname" class="form-label">Gender</label>
                                       <div class="form-control-wrap">
                                          <select class="js-select" id="gender" name="customerGender" data-search="true" data-sort="false">

                                             <option {{$customers->gender=="Male"?"selected":""}} value="Male">Male</option>
                                             <option {{$customers->gender=="Female"?"selected":""}} value="Female">Female</option>
                                          </select>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-3">
                                    <div class="form-group">
                                       <label for="email" class="form-label">Email address</label>
                                       <div class="form-control-wrap">
                                          <input type="text" class="form-control" name="customerEmail" id="email" value="{{$customers->email}}" readonly>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-3">
                                    <div class="form-group">
                                       <label for="email" class="form-label">Phone Number</label>
                                       <div class="form-control-wrap">
                                          <input type="tel" minlength="9" maxlength="13" class="form-control" name="customerPhone" id="mobile_code" value="{{$customers->phone}}" readonly>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-3">
                                    <div class="form-group">
                                       <label for="email" class="form-label">Date of Birth</label>
                                       <div class="form-control-wrap">
                                          <input type="date"  class="form-control" name="dob" id="dob" value="{{$customers->dob}}">
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-3">
                                    <div class="form-group">
                                       <label for="email" class="form-label">Whatsapp Number</label>
                                       <div class="form-control-wrap">
                                          <input type="tel" minlength="9" maxlength="13" class="form-control" name="customerWhatsapp" id="whatsapp_code" value="{{$customers->whatsapp}}" readonly>
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
                                       <div class="form-control-wrap">
                                          <input type="text" class="form-control" name="customerStreetAddress1" id="address" value="{{$customers->address}}">
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-3">
                                    <div class="form-group">
                                       <label for="email" class="form-label">Landmark</label>
                                       <div class="form-control-wrap">
                                          <input type="text" class="form-control" name="landmark" id="landmark" value="{{$customers->landmark}}">
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-3">
                                    <div class="form-group">
                                       <label for="firstname" class="form-label">State</label>
                                       <div class="form-control-wrap">
                                          <select class="js-select" id="customerState" name="customerState" data-search="true" data-sort="false">
                                             @if($customerState)
                                             <option value="{{$customers->state}}">{{$customerState->name}}</option>
                                             @endif
                                             @foreach($states as $rowStates)
                                             <option value="{{$rowStates->id}}">{{$rowStates->name}}</option>
                                             @endforeach
                                          </select>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-3">
                                    <div class="form-group">
                                       <label for="firstname" class="form-label">City</label>
                                       <div class="form-control-wrap">
                                          <select class="form-control" id="customerCity"
                                             name="customerCity" data-search="true"
                                             data-sort="false">
                                             @if($customerCity)
                                             <option value="{{$customers->city}}">{{$customerCity->name}}</option>
                                             @endif
                                          </select>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-3">
                                    <div class="form-group">
                                       <label for="firstname" class="form-label">Status</label>
                                       <div class="form-control-wrap">
                                          <select id="status" class="form-control" name="status">
                                          <option {{$customers->status == 'active'? 'selected': '' }} value="active">Active</option>
                                          <option {{$customers->status == 'inactive'? 'selected': '' }}  value="inactive">Inactive</option>
                                          </select>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-3">
                                    <div class="form-group">
                                       <label for="postalcode" class="form-label">Postal Code</label>
                                       <div class="form-control-wrap">
                                          <input type="text" class="form-control" name="customerPostalcode" id="postalcode" value="{{$customers->postal_code}}">
                                       </div>
                                    </div>
                                 </div>
                                 
                                  <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="postalcode" class="form-label">Remarks</label>
                                    <div class="form-control-wrap"><input type="text" class="form-control" name="remarks" id="remarks" value="{{$customers->remarks}}" placeholder="remarks">
                                    </div>
                                 </div>
                              </div>
                                 
                                 <div class="col-lg-3">
                                    <div class="form-group">
                                       <label for="city" class="form-label">Latitude</label>
                                       <div class="form-control-wrap">
                                          <input type="text" class="form-control" name="customerLatitude" id="customerLatitude" value="{{$customers->latitude}}">
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-3">
                                    <div class="form-group">
                                       <label for="city" class="form-label">Longitude</label>
                                       <div class="form-control-wrap">
                                          <input type="text" class="form-control" id="customerLongitude" name="customerLongitude" value="{{$customers->longitude}}">
                                       </div>
                                    </div>
                                 </div>
                              </div>

                              <div class="row">
                                 <div class="col-lg-12">
                                    <h3>CUSTOMER COMMITMENT FEE</h3>
                                    <p>Payment receipt is required to be uploaded for a new tutor registration.</p>
                                 </div>
                                 <div class="col-lg-3">
                                    <div class="form-group">
                                       <label for="city" class="form-label">Payment Attachment</label>
                                       <div class="form-control-wrap">
                                          @if(isset($customerCommitmentFeeCheck) && $customerCommitmentFeeCheck->payment_attachment!=null)
                                          <input type="file" class="form-control" name="paymentAttachment" id="paymentAttachment">
                                          <img src="{{url("/public/customerCommitmentFee")."/".$customerCommitmentFeeCheck->payment_attachment}}" height="250px">
                                          @else
                                          <input type="file" class="form-control" name="paymentAttachment" id="paymentAttachment">
                                          @endif
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-3">
                                    <div class="form-group">
                                       <label for="city" class="form-label">Payment Amount</label>
                                       <div class="form-control-wrap">
                                          <input type="text" @isset($customerCommitmentFeeCheck->payment_amount) value="{{ $customerCommitmentFeeCheck->payment_amount }}" @endisset class="form-control" name="feeAmount" id="feeAmount">
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-3">
                                    <div class="form-group">
                                       <label for="city" class="form-label">Fee Payment Date</label>
                                       <div
                                          class="form-control-wrap">
                                          <input type="date" class="form-control" @isset($customerCommitmentFeeCheck->payment_date) value="{{$customerCommitmentFeeCheck->payment_date!=null?$customerCommitmentFeeCheck->payment_date:""}}" @endisset name="feePaymentDate" id="feePaymentDate">
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-3">
                                    <div class="form-group">
                                       <label for="city" class="form-label">Receiving Account</label>
                                       <div
                                          class="form-control-wrap">
                                          <select class="js-select" name="receivingAccount" id="receivingAccount" data-search="true" data-sort="false">
                                             <option value=""></option>
                                             <option @isset($customerCommitmentFeeCheck->receiving_account)
                                             {{$customerCommitmentFeeCheck->receiving_account=="Cash At Bank - My Bank"?"selected":""}}
                                             @endisset
                                             value="Cash At Bank - My Bank">Cash At Bank - My Bank
                                             </option>
                                             <option @isset($customerCommitmentFeeCheck->receiving_account)
                                             {{$customerCommitmentFeeCheck->receiving_account=="Cash in Hand"?"selected":""}}
                                             @endisset
                                             value="Cash in Hand">Cash in Hand
                                             </option>
                                             <option @isset($customerCommitmentFeeCheck->receiving_account)
                                             {{$customerCommitmentFeeCheck->receiving_account=="Payment Gateway"?"selected":""}}
                                             @endisset
                                             value="Payment Gateway">Payment Gateway.
                                             </option>
                                          </select>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <br/>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <div class="col-lg-12">
                                       <button class="btn btn-primary" type="submit">Edit Customer</button>
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
   </div>
</div>

    <!-- Add the this google map apis to webpage -->
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