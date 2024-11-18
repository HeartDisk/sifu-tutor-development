@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
   <div class="fluid-container">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head">
               <div class="nk-block-head-between flex-wrap gap g-2">
                  <div class="nk-block-head-content">
                     <h2 class="nk-block-title">Edit Tutor</h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Tutor List</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Edit Tutor</li>
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
                        <form method="POST" action="{{route('submitEditTutor')}}" enctype="multipart/form-data">
                           @csrf
                           <input type="hidden" name="id" value="{{$tutor->id}}"/>
                           <div class="row">
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Tutor ID</label>
                                    <div class="form-control-wrap">
                                       <input type="text" class="form-control" readonly name="tutorID" value="{{$tutor->tutor_id}}" id="tutorID">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Start Working Date</label>
                                    <div class="form-control-wrap">
                                       <input type="date" class="form-control" name="registration_date" value="{{$tutor->start_date}}" id="startWorkingDate">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <h3>TUTOR PERSONAL INFORMATION</h3>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Full Name</label>
                                    <div class="form-control-wrap">
                                       <input type="text" class="form-control" id="firstname" name="full_name" value="{{$tutor->full_name}}" placeholder="First name">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Gender</label>
                                    <div class="form-control-wrap">
                                       <select class="js-select" id="gender" data-search="true" name="gender" value="{{$tutor->gender}}" data-sort="false">
                                       <option {{$tutor->gender == 'Male'? 'selected': ''}} value="Male"> Male </option>
                                       <option {{$tutor->gender == 'Female'? 'selected': ''}} value="Female"> Female </option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="email" class="form-label">Email address</label>
                                    <div class="form-control-wrap">
                                       <input type="text" class="form-control" id="email" name="email" value="{{$tutor->email}}" placeholder="Email address">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="address" class="form-label">ID Number</label>
                                    <div class="form-control-wrap">
                                       <input type="text" class="form-control" id="nric" name="CNIC" value="{{$tutor->nric}}" placeholder="NRIC">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="address" class="form-label">Phone Number</label>
                                    <div class="form-control-wrap">
                                       <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" value="{{$tutor->phoneNumber}}" placeholder="Phone Number">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="address" class="form-label">Age</label>
                                    <div class="form-control-wrap">
                                       <input type="text" class="form-control" id="age" name="age" value="{{$tutor->age}}" placeholder="Age">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="address" class="form-label">Whatsapp Number</label>
                                    <div class="form-control-wrap">
                                       <input type="text" class="form-control" id="whatsapp" name="whatsapp" value="{{$tutor->whatsapp}}" placeholder="whatsapp Number">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Marital Status</label>
                                    <div class="form-control-wrap">
                                       <select class="js-select" id="maritalStatus" name="maritalStatus" data-search="true" data-sort="false">
                                          <option value="{{$tutor->marital_status}}">{{$tutor->marital_status}}</option>
                                          <option value="Single">Single</option>
                                          <option value="Married">Married</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              @can("tutor-verify")
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Status</label>
                                    <div class="form-control-wrap">
                                       <select class="js-select" id="status" name="status" data-search="true" data-sort="false">
                                       <option {{$tutor->status=="verified"?"selected":""}} value="verified">Verified</option>
                                       <option {{$tutor->status=="unverified"?"selected":""}} value="unverified"> Unverified </option>
                                       <option {{$tutor->status=="inactive"?"selected":""}} value="inactive"> Inactive </option>
                                       <option {{$tutor->status=="terminated"?"selected":""}} value="terminated"> Terminated </option>
                                       <option {{$tutor->status=="resigned"?"selected":""}} value="resigned"> Resigned </option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              @endcan
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="address" class="form-label">Bank Name</label>
                                    <div class="form-control-wrap"><input type="text" class="form-control" id="bankName" value="{{$tutor->bank_name}}" name="bankName"></div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="address" class="form-label">Bank Account Number</label>
                                    <div class="form-control-wrap"><input type="text" class="form-control" name="bankAccountNumber" value="{{$tutor->bank_account_number}}" id="bankAccountNumber"></div>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <h3>TUTOR ADDRESS</h3>
                              </div>
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <label for="city" class="form-label">Street Address </label>
                                    <div class="form-control-wrap"><input type="text" class="form-control" id="streetAddress1" value="{{$tutor->street_address1}}" name="street_address1" placeholder="Street Address ">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="city" class="form-label">Latitude</label>
                                    <div class="form-control-wrap"><input type="text" class="form-control" name="latitude" id="latitude" value="{{$tutor->latitude}}">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="city" class="form-label">Longitude</label>
                                    <div class="form-control-wrap"><input type="text" class="form-control" name="longitude" id="longitude" value="{{$tutor->longitude}}">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="country" class="form-label">State</label>
                                    @php
                                    $states = DB::table('states')->get();
                                    $cityDetail = DB::table('cities')->where('id','=',$tutor->city)->first();
                                    $stateDetail = DB::table('states')->where('id','=',$tutor->state)->first();
                                    
                                   
                                    @endphp
                                    <div class="form-control-wrap">
                                       <select id="state" data-search="true" name="state" data-sort="false" required>
                                          <option>Select state</option>
                                          @foreach($states as $rowStates)
                                          <option {{$rowStates->id == $tutor->state? 'selected': ''}} value="{{$rowStates->id}}">{{$rowStates->name}}</option>
                                          @endforeach
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="city" class="form-label">City</label>
                                    <select class=" form-control" data-search="true" data-sort="false" name="city" id="city" placeholder="City">
                                       @if($cityDetail)
                                       <option value="">{{$cityDetail->name}}</option>
                                       @endif
                                    </select>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="postalcode" class="form-label">Postal Code</label>
                                    <div class="form-control-wrap"><input type="text" class="form-control" name="postalcode" value="{{$tutor->postal_code}}" id="postal_code" placeholder="Postal code">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <div class="form-group">
                                    <label for="aboutme" class="form-label">Remark</label>
                                    <div class="form-control-wrap"><textarea class="form-control" id="remark" name="remark" rows="3" placeholder="Enter your remarks">{{$tutor->remark}}</textarea>
                                    </div>
                                 </div>
                              </div>
                           
                           @can("tutor-verify")
                           <div class="col-lg-12">
                              <h3>COMMITMENT FEE</h3>
                              <p>RM 100 payment receipt is required to be uploaded for a new tutor registration.</p>
                              @php
                              $tutorCommitmentFees = DB::table('tutor_commitment_fees')->where('tutor_id','=',$tutor->id)->get();
                              @endphp
                              @if(isset($tutorCommitmentFees) && isset($tutorCommitmentFees[0]))
                              <div class="table-responsive">
                                 <table class="table">
                                    <thead>
                                       <tr>
                                          <th>Payment Attachment</th>
                                          <th>Payment Amount</th>
                                          <th>Fee Payment Date</th>
                                          <th>Receiving Account</th>
                                          <th>Status</th>
                                    </thead>
                                    <tbody class="feePaymentBody">
                                       <tr>
                                          <td><input type="file" class="form-control" name="paymentAttachment" id="paymentAttachment"></td>
                                          <td><input type="number" class="form-control" name="feeAmount" id="feeAmount" placeholder="Enter the Fee Amount"></td>
                                          <td><input type="date" class="form-control" name="feePaymentDate" id="feePaymentDate"></td>
                                          <td>
                                             <select class="form-control" name="receivingAccount" id="receivingAccount" data-search="true" data-sort="false">
                                                <option value="">Select Your Receiving Account</option>
                                                <option value="Cash At Bank - My Bank">Cash At Bank - My Bank </option>
                                                <option value="Cash in Hand">Cash in Hand</option>
                                                <option value="Payment Gateway">Payment Gateway</option>
                                             </select>
                                          </td>
                                          <td>
                                             <select class="form-control" name="payment_status" id="status" data-search="true" data-sort="false">
                                                <option value="">Select Your Status</option>
                                                <option value="complete">Complete</option>
                                                <option value="incomplete">Incomplete</option>
                                             </select>
                                          </td>
                                    </tbody>
                                 </table>
                              </div>
                              @foreach($tutorCommitmentFees as $tutorCommitmentFee)
                           </div>
                           </div>
                           <div class="row view-sindetails">
                              <div class="col-lg-2 details-item">
                                 <p class="item-title">Payment Attachment</p>
                                 <p><strong><a class="dview-status-viewfile" data-lightbox="image" target="_blank" href="{{ asset('/public/tutorPaymentAttachment/' . $tutorCommitmentFee->payment_attachment) }}">View File</a></strong></p>
                              </div>
                              <div class="col-lg-3 details-item">
                                 <p class="item-title">Fee Payment Date</p>
                                 <p><strong>{{$tutorCommitmentFee->payment_date}}</strong></p>
                              </div>
                              <div class="col-lg-2 details-item">
                                 <p class="item-title">Fees Paid</p>
                                 <p><strong>{{$tutorCommitmentFee->payment_amount}}</strong></p>
                              </div>
                              <div class="col-lg-3 details-item">
                                 <p class="item-title">Receiving Account</p>
                                 <p><strong>{{$tutorCommitmentFee->receiving_account}}</strong></p>
                              </div>
                              <div class="col-lg-2 details-item">
                                 <p class="item-title">Status</p>
                                 <p><strong>{{$tutorCommitmentFee->status}}</strong></p>
                              </div>
                           </div>
                           @endforeach
                           @else
                           <div class="table-responsive">
                              <table class="table">
                                 <thead>
                                    <tr>
                                       <th>Payment Attachment</th>
                                       <th>Payment Amount</th>
                                       <th>Fee Payment Date</th>
                                       <th>Receiving Account</th>
                                       <th>Status</th>
                                    </tr>
                                 </thead>
                                 <tbody class="feePaymentBody">
                                    <tr>
                                       <td><input type="file" class="form-control" name="paymentAttachment" id="paymentAttachment">
                                       </td>
                                       <td><input type="number" class="form-control" name="feeAmount" id="feeAmount" placeholder="Enter the Fee Amount"></td>
                                       <td><input type="date" class="form-control" name="feePaymentDate" id="feePaymentDate">
                                       </td>
                                       <td>
                                          <select class="form-control" name="receivingAccount" id="receivingAccount" data-search="true" data-sort="false">
                                             <option value="">Select Your Receiving Account</option>
                                             <option value="Cash At Bank - My Bank">Cash At Bank - My Bank</option>
                                             <option value="Cash in Hand">Cash in Hand</option>
                                             <option value="Payment Gateway">Payment Gateway</option>
                                          </select>
                                       </td>
                                       <td>
                                          <select class="form-control" name="payment_status" id="status" data-search="true" data-sort="false">
                                             <option value="">Select Your Status</option>
                                             <option value="complete">Complete</option>
                                             <option value="incomplete">Incomplete</option>
                                          </select>
                                       </td>
                                 </tbody>
                              </table>
                           </div>
                           @endif
                     </div>
                     @endcan
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

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key=AIzaSyAP0kk0GU1hG9_-J8ovxiOogeWaOOtwSKo&libraries=places"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyAP0kk0GU1hG9_-J8ovxiOogeWaOOtwSKo"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function () {
            var $sourceInput = $('#mobile_code');
            var $targetInput = $('#whatsapp_code');
            $sourceInput.on('input', function () {
                var inputValue = $sourceInput.val();
                $targetInput.val(inputValue);
            });

            $("select#state").change(function () {
                $("#city").html('');
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
                        console.log(data.cities);
                        $('#city').append(data.cities);
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
                document.getElementById("latitude").value = place.geometry['location'].lat();
                document.getElementById("longitude").value = place.geometry['location'].lng();
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
            document.getElementById('latitude').value = near_place.geometry.location.lat();
            document.getElementById('longitude').value = near_place.geometry.location.lng();
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
                <td><input type="file" class="form-control" name="paymentAttachment[]" id="paymentAttachment${rowFeePaymentIdx}" required></td>
                <td><input type="number" class="form-control" name="feeAmount[]" id="feeAmount${rowFeePaymentIdx}" placeholder="Enter the Fee Amount" required></td>
                <td><input type="date" class="form-control" name="feePaymentDate[]" id="feePaymentDate${rowFeePaymentIdx}" required ></td>
                <td><select class="form-control" name="receivingAccount[]" id="receivingAccount${rowFeePaymentIdx}" data-search="true" data-sort="false" required></>
                <option value="">Select Your Receiving Account</option>
                <option value="Cash At Bank - My Bank">Cash At Bank - My Bank</option>
                <option value="Cash in Hand">Cash in Hand</option>
                <option value="Payment Gateway">Payment Gateway</option>
                </select></td>
                <td><select class="form-control" name="payment_status[]" id="status${rowFeePaymentIdx}" data-search="true" data-sort="false" required>
                <option value="">Select Your Status</option>
                <option value="complete">Complete</option>
                <option value="incomplete">Incomplete</option>
                </select></td>
                <td>
                <button class="btn btn-danger remove" type="button">Remove</button>
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
    </script>
@endsection
