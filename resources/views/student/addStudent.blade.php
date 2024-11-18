@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
   <div class="fluid-container">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head">
               <div class="nk-block-head-between flex-wrap gap g-2">
                  <div class="nk-block-head-content">
                     <h2 class="nk-block-title">Add Student</h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Student List</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Add Student</li>
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
                        <form method="POST" action="{{route('submitStudent')}}" enctype="multipart/form-data">
                           @csrf
                           <div class="row">
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Registration Date</label>
                                    <div class="form-control-wrap"><input type="date" name="registration_date" class="form-control" id="registrationDate"></div>
                                 </div>
                              </div>
                              <div  class="newCustomerDD col-lg-4">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Select Parent/Customer</label>
                                    <div class="form-control-wrap">
                                       <select class="js-select"  id="parent_id" name="parent_id" data-search="true" data-sort="false">
                                          <option value="">Select Select Parent/Customer</option>
                                          <option value="newParent">New Customer</option>
                                          @foreach($customers as $rowCustomers)
                                          <option value="{{$rowCustomers->id}}">{{$rowCustomers->uid}} - {{$rowCustomers->full_name}}</option>
                                          @endforeach
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div  class="col-lg-4">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Admin in Charge</label>
                                    <div class="form-control-wrap">
                                       <select   class="js-select" id="staff_id" name="staff_id" data-search="true" data-sort="false">
                                          <option value="">SelectAdmin in Charge</option>
                                          @foreach($staffs as $staff)
                                          <option value="{{$staff->id}}">{{$staff->full_name}}</option>
                                          @endforeach
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <h3>
                                    <div style="display:none;" class="addNewCustomerHeading"> ADD NEW CUSTOMER / PARENT INFORMATION</div>
                                    <div style="display:none;" class="existingCustomertHeading"> CUSTOMER / PARENT INFORMATION</div>
                                 </h3>
                              </div>
                              <div class="col-lg-3 bg-citem existingCustomer">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Full Name</label>
                                    <div style="" class="form-control-wrap">
                                       <span class="customerFullName"></span>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-2 bg-citem existingCustomer">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Customer Gender</label>
                                    <div style="" class="form-control-wrap">
                                       <span class="customerGender"></span>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3 bg-citem existingCustomer">
                                 <div class="form-group">
                                    <label for="email" class="form-label">Email address</label>
                                    <div style="" class="form-control-wrap">
                                       <span class="customerEmail"></span>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-2 bg-citem existingCustomer">
                                 <div class="form-group">
                                    <label for="email" class="form-label">Phone Number</label>
                                    <div style="" class="form-control-wrap">
                                       <span class="customerPhone"></span>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-2 bg-citem existingCustomer">
                                 <div class="form-group">
                                    <label for="email" class="form-label">Whatsapp Number</label>
                                    <div style="" class="form-control-wrap">
                                       <span class="customerWhatsapp"></span>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-6 bg-citem existingCustomer">
                                 <div class="input-box">
                                    <div class="form-group">
                                       <label for="email" class="form-label">Address</label>
                                       <div style="" class="form-control-wrap">
                                          <span class="address1"></span>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-2 bg-citem existingCustomer">
                                 <div class="form-group">
                                    <label for="city" class="form-label">State</label>
                                    <div style="" class="form-control-wrap">
                                       <span class="customerState"></span>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-2 bg-citem existingCustomer">
                                 <div class="form-group">
                                    <label for="city" class="form-label">City</label>
                                    <div style="" class="form-control-wrap">
                                       <span class="customerCity"></span>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-2 bg-citem existingCustomer">
                                 <div class="form-group">
                                    <label for="postalcode" class="form-label">Postal Code</label>
                                    <div style="" class="form-control-wrap">
                                       <span class="customerPostalCode"></span>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3 newCustomer" style="display:none;">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Full Name</label>
                                    <div class="form-control-wrap">
                                       <input type="text" class="customerFullName form-control" id="fullName" name="customerFullName"  placeholder="Full name">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3 newCustomer" style="display:none;">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Customer Gender</label>
                                    <div class="form-control-wrap">
                                       <div class="">
                                          <select id="customerGender" name="customerGender" data-search="true" data-sort="false" >
                                             <option value="">Select Gender</option>
                                             <option value="Male">Male</option>
                                             <option value="Female">Female</option>
                                          </select>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3 newCustomer" style="display:none;">
                                 <div class="form-group">
                                    <label for="email" class="form-label">Phone Number</label>
                                    <div class="form-control-wrap">
                                       <input type="text" class=" customerPhone form-control" name="customerPhone" id="mobile_code" placeholder="Customer Phone">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3 newCustomer" style="display:none;">
                                 <div class="form-group">
                                    <label for="email" class="form-label">Whatsapp Number</label>
                                    <div class="form-control-wrap">
                                       <input type="text" class=" customerWhatsapp form-control" name="customerWhatsapp" id="whatsapp_code" placeholder="Whatsapp Number">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3 newCustomer" style="display:none;">
                                 <div class="form-group">
                                    <label for="email" class="form-label">Email address</label>
                                    <div class="form-control-wrap">
                                       <input type="text" class=" customerEmail form-control" name="customerEmail" id="email" placeholder="Email address">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-6 newCustomer" style="display:none;">
                                 <div class="input-box">
                                    <label class="form-label" for="address"> Address</label>
                                    <div class="form-group">
                                       <span class="la la-map-marker form-icon"></span>
                                       <div class="form-control-wrap">
                                          <div class="form-control-wrap"><input type="text" class="form-control" name="customerStreetAddress1" id="address" placeholder="Full Address"></div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3 newCustomer" style="display:none;">
                                 <div class="form-group">
                                    <label for="city" class="form-label">Latitude</label>
                                    <div class="form-control-wrap"><input type="text" class="customerLatitude form-control" name="customerLatitude" id="customerLatitude" placeholder="Latitude"></div>
                                 </div>
                              </div>
                              <div class="col-lg-3 newCustomer" style="display:none;">
                                 <div class="form-group">
                                    <label for="city" class="form-label">Longitude</label>
                                    <div class="form-control-wrap"><input type="text" class="customerLongitude form-control" id="customerLongitude" name="customerLongitude" placeholder="Longitude"></div>
                                 </div>
                              </div>
                              <div class="col-lg-3 newCustomer" style="display:none;">
                                 <div class="form-group">
                                    <label for="city" class="form-label">State</label>
                                    <div class="form-control-wrap">
                                       <select class=" js-select" data-search="true" data-sort="false"  name="customerState" id="customerState" >
                                          @php
                                          $states = DB::table('states')->get();
                                          @endphp
                                          <option selected>Please select state</option>
                                          @foreach($states as $rowStates)
                                          <option value="{{$rowStates->id}}">{{$rowStates->name}}</option>
                                          @endforeach
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3 newCustomer" style="display:none;">
                                 <div class="form-group">
                                    <label for="city" class="form-label">City</label>
                                    <div class="form-control-wrap">
                                       <select class=" form-control" data-search="true" data-sort="false"  name="customerCity" id="customerCity" >
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3 newCustomer" style="display:none;">
                                 <div class="form-group">
                                    <label for="postalcode" class="form-label">Postal Code</label>
                                    <div class="form-control-wrap">
                                       <input type="text" class=" customerPostalCode form-control" name="customerPostalcode" id="postalcode" placeholder="Zip code">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <h3>
                                    <div class="addNewStudentHeading"> ADD NEW STUDENT INFORMATION</div>
                                 </h3>
                              </div>
                              <div class="col-lg-3 newStudent">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Full Name</label>
                                    <div class="form-control-wrap"><input type="text" class="studentFullName form-control" id="fullName" name="studentFullName"  placeholder="Full name"></div>
                                 </div>
                              </div>
                              <div class="col-lg-2 newStudent">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Gender</label>
                                    <div class="form-control-wrap">
                                       <select id="studentGender" name="studentGender" data-search="true" data-sort="false">
                                          <option value="">Select Gender</option>
                                          <option value="Male">Male</option>
                                          <option value="Female">Female</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-2 newStudent">
                                 <div class="form-group">
                                    <label for="company" class="form-label">Date of Birth</label>
                                    <div class="form-control-wrap">
                                       <input type="date" class="studentDateOfBirth form-control" id="studentDateOfBirth" name="studentDateOfBirth"  placeholder="Date of Birth">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-2 newStudent">
                                 <div class="form-group">
                                    <label for="age" class="form-label">Age</label>
                                    <div class="form-control-wrap"><input type="text" class="age form-control" id="age" name="age"  placeholder="Age"></div>
                                 </div>
                              </div>
                              <div class="col-lg-3 newStudent">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Special Need</label>
                                    <div class="form-control-wrap">
                                       <select id="specialNeed" name="specialNeed" required>
                                          <option value="">Select Special Need</option>
                                          <option value="None">None</option>
                                          <option value="Dyslexia">Dyslexia</option>
                                          <option value="Slow Learner">Slow Learner</option>
                                          <option value="Autism">Autism</option>
                                          <option value="Down Syndrome">Down Syndrome</option>
                                          <option value="OKU">OKU</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-2">
                                 <button class="btn btn-primary" type="submit">Submit</button>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyAP0kk0GU1hG9_-J8ovxiOogeWaOOtwSKo"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script>
// google.maps.event.addDomListener(window, 'load', initialize);

// function initialize() {
//   var input = document.getElementById('address');
//   var studentInput = document.getElementById('studentAddress');
//   var autocomplete = new google.maps.places.Autocomplete(input);
//   var studentAutocomplete = new google.maps.places.Autocomplete(studentInput);
//   autocomplete.addListener('place_changed', function () {
//       var place = autocomplete.getPlace();
//       document.getElementById("customerLatitude").value = place.geometry['location'].lat();
//       document.getElementById("customerLongitude").value = place.geometry['location'].lng();
//   });
//   studentAutocomplete.addListener('place_changed', function () {
//       var place = studentAutocomplete.getPlace();
//       document.getElementById("studentLatitude").value = place.geometry['location'].lat();
//       document.getElementById("studentLongitude").value = place.geometry['location'].lng();
//   });
// }

$(document).ready(function () {
    
    var $getAgeInput = $('#age');
    var $getDateOfBirth = $('#studentDateOfBirth');
    
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
    
    $('#studentDateOfBirth').on('input', function () {
        var dob = $getDateOfBirth.val();
        var today = new Date();
        var birthDate = new Date(dob);
        var age = today.getFullYear() - birthDate.getFullYear();
        if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) {
            age--;
        }
        $getAgeInput.val(age);
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
<script>
  $(document).ready(function () {
   var rowIdx = 0;
   $('#addBtn').on('click', function () {
      $('#tbody').append(`<tr id="R${++rowIdx}">
           <td class=""><select class="form-control js-select" data-search="true" data-sort="true" name="subject[]"><option value=""></option>@foreach($subjects as $subjectRow)<option value="{{$subjectRow->id}}"> {{$subjectRow->name}}</option>@endforeach<select></td>
           <td class=""><input class="form-control" type="text" value="" name="quantity[]"></td>
           <td class=""><select class="form-control js-select" name="day[]"><option value="Monday">Monday</option><option value="Tuesday">Tuesday</option><option value="Wednesday">Wednesday</option><option value="Thursday">Thursday</option><option value="Friday">Friday</option><option value="Saturday">Saturday</option><option value="Sunday">Sunday</option><select> </td>
           <td class=""><input class="form-control" type="time" value="22:00" name="time[]"></td>
           <td class=""><select class="form-control js-select" name="tutorPereference[]"><option value="male">Male</option><option value="Female">Female</option><select></td>
           <td class=""><select class="form-control js-select" name="subscription[]"><option value="LongTerm">Long Term</option><option value="shortTerm">Short Term</option><select></td>
           <td class=""><input class="form-control" type="text"  name="specialRequest[]"></td>
           <td class="text-center">
             <button style="background-color:red; color:#fff" class="btn btn-sm remove"
               type="button"> X </button>
             </td>
           </tr>`);
   });

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
});
</script>
<script>

$(document).ready(function () {
    var $sourceInput = $('#mobile_code');
    var $targetInput = $('#whatsapp_code');
    $sourceInput.on('input', function () {
    var inputValue = $sourceInput.val();
    $targetInput.val(inputValue);
});

var $getAgeInput = $('#age');
   var $getStudentDateOfBirth = $('#studentDateOfBirth');
   Listen
   for input changes in the source input field
   $getAgeInput.on('input', function () {
      var ageInputValue = $getAgeInput.val();
      var age = parseInt(ageInputValue);
      var currentDate = new Date();
      var birthYear = currentDate.getFullYear() - age;
      var dob = new Date(birthYear, 0, 1); // Assuming January 1st as the birthdate
      var dobFormatted = dob.toLocaleDateString('en-US', {
         year: 'numeric',
         month: 'long',
         day: 'numeric'
      });
      $('#dobResult').text(dobFormatted);
      $getStudentDateOfBirth.val(dobFormatted);
   });
});

$("select#customerState").change(function () {
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
      var address1 = $('.address1').val()
      console.log(address1);
      $('.classAddress').val(address1)
      $('.sameAsCustomer').hide();
   } else {
      $('.sameAsCustomer').show();
   };
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
         url: "{{url('/addTicket/')}}" + "/" + selectedStudent,
         type: 'GET',
         dataType: 'json',
         success: function (data) {
            console.log(data);
            $('.customerId').text(data.customer.uid);
            $('.customerId').text(data.customer.uid);
            $('.customerFullName').text(data.customer.full_name);
            $('.parentFullName').val(data.customer.full_name);
            $('.customerEmail').text(data.customer.email);
            $('.customerGender').text(data.customer.gender);
            $('.existingParent_id').val(data.customer.id);
            $('.customerPhone').text(data.customer.phone);
            $('.customerWhatsapp').text(data.customer.whatsapp);
            $('#customerWhatsapp').text(data.customer.whatsapp);
            $('#address').text(data.customer.address);
            $('.customerStreetAddress2').val(data.customer.address2);
            $('.customerNRIC').text(data.customer.nric);
            $('.customerDOB').text(data.customer.dob);
            $('.customerCity').text(data.customer.city);
            $('.customerLatitude').text(data.customer.latitude);
            $('.customerLongitude').text(data.customer.longitude);
            $('.customerPostalCode').text(data.customer.postal_code);
            $('.customerState').text(data.customer.state);
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
         $('.newCustomer').show();
         $('.existingCustomer').hide();
         $('.addNewCustomerHeading').show();
         $('.existingCustomertHeading').hide();
         console.log("Line 984");
      } else {
         $('.addNewCustomerHeading').hide();
         $('.existingCustomertHeading').show();
         $('.newCustomer').hide();
         $('.existingCustomer').show();
         console.log("Line 994");
      }
      var userURL = $(this).data('url');
      $.ajax({
         url: "{{url('/addTicketAjaxCallParrent/')}}" + "/" + selectedParent,
         type: 'GET',
         dataType: 'json',
         success: function (data) {
            console.log(data);
            $('.customerId').text(data.customer.uid);
            $('.customerId').val(data.customer.uid);
            $('.customerFullName').text(data.customer.full_name);
            $('.customerEmail').text(data.customer.email);
            $('.customerGender').text(data.customer.gender);
            $('.customerPhone').text(data.customer.phone);
            $('.customerWhatsapp').text(data.customer.whatsapp);
            $('.address1').text(data.customer.address1);
            $('.customerStreetAddress2').text(data.customer.address2);
            $('.customerNRIC').text(data.customer.nric);
            $('.customerDOB').text(data.customer.dob);
            $('.customerCity').text(data.cityName);
            $('.customerState').text(data.stateName);
            $('.customerLatitude').text(data.customer.latitude);
            $('.customerLongitude').text(data.customer.longitude);
            $('.customerPostalCode').text(data.customer.postal_code);
         }
      });
   });
});
</script>
@endsection