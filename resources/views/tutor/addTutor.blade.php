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
                              <!--<div class="col-lg-3">-->
                              <!--   <div class="form-group">-->
                              <!--      <label for="firstname" class="form-label">Verification Date</label>-->
                              <!--      <div class="form-control-wrap"><input type="date" class="form-control" name="registration_date" id="startWorkingDate"></div>-->
                              <!--   </div>-->
                              <!--</div>-->
                              
                            
                            
                              
                               <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Start Working Date</label>
                                    <div class="form-control-wrap"><input type="date" class="form-control" name="registration_date" value="" id="startWorkingDate"></div>
                                 </div>
                              </div>
                              
                              <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="attendedTrainingDate" class="form-label">Training Date</label>
                                    <div class="form-control-wrap">
                                        <input type="date" class="form-control" id="attendedTrainingDate" name="attendedTrainingDate" value="" placeholder="Training Date">
                                    </div>
                                </div>
                            </div>
                            
                              <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="dob" class="form-label">Date of Birth</label>
                                    <div class="form-control-wrap">
                                        <input type="date" class="form-control" id="dob" name="dob" value="" placeholder="Date of Birth">
                                    </div>
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
                                       <select class="" id="gender" name="gender" required>
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
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" id="nric" name="nric" placeholder="NRIC" required >
                                        <label id="nricErrorMsg" style="color:red!important;display: none" class="form-label">NRIC already exist</label>
                                    </div>
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
                                       <select id="maritalStatus" name="maritalStatus">
                                          <option value="">Select Marital Status</option>
                                          <option value="Single">Single</option>
                                          <option value="Married">Married</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              
                              <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="age" class="form-label">Age</label>
                                    <div class="form-control-wrap">
                                        <input type="number" class="form-control" id="age" name="age" value="0" placeholder="Age">
                                    </div>
                                </div>
                            </div>
                            
                            <!--<div class="col-lg-3">-->
                            <!--    <div class="form-group">-->
                            <!--        <label for="dob" class="form-label">Date of Birth</label>-->
                            <!--        <div class="form-control-wrap">-->
                            <!--            <input type="date" class="form-control" id="dob" name="dob" value="" placeholder="Date of Birth">-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--</div>-->
        
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
                                    <div class="form-control-wrap"><input type="text" class="form-control" name="bankAccountNumber" id="bankAccountNumber" placeholder="Bank Account Number" required ></div>
                                 </div>
                              </div>
                              
                              <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="shirtSize" class="form-label">Shirt Size</label>
                                    <div class="form-control-wrap">
                                        <select class="form-control" name="shirtSize"id="shirtSize">
                                            <option>Select Shirt Size</option>
                                            <option value="Extra Small">Extra Small</option>
                                            <option value="Small">Small</option>
                                            <option value="Medium">Medium</option>
                                            <option value="Large">Large</option>
                                            <option value="Extra Large">Extra Large</option>    
                                        </select>
                                        
                                        <!--<input type="text" class="form-control" id="shirtSize" name="shirtSize" value="0" placeholder="Shirt Size">-->
                                    </div>
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
                                    <div class="form-control-wrap"><input readonly type="text" class="customerLongitude form-control" id="customerLongitude" name="customerLongitude" placeholder="Longitude" required></div>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <label for="city" class="form-label">State</label>
                                    <div class="form-control-wrap">
                                       <select class="form-control" data-search="true" data-sort="false"  name="state" id="customerState" required>
                                          <option value="">Please select state</option>
                                          @php
                                          $states = DB::table('states')->get();
                                          $cities = DB::table('cities')->get();
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
                                    <div class="form-control-wrap tcustomerCity">
                                       <select class="form-control" data-search="true" data-sort="false" name="city" id="customerCity" placeholder="City" required>
                                          <option value="">Select City</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <label for="postalcode" class="form-label">Postal Code</label>
                                    <div class="form-control-wrap"><input type="text" class="form-control" name="postalcode" id="postalcode" placeholder="Postal code" required></div>
                                 </div>
                              </div>
                              
                              <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="remark" class="form-label">Remark</label>
                                    <div class="form-control-wrap"><grammarly-extension data-grammarly-shadow-root="true" style="position: absolute; top: 0px; left: 0px; pointer-events: none;" class="dnXmp"></grammarly-extension><grammarly-extension data-grammarly-shadow-root="true" style="position: absolute; top: 0px; left: 0px; pointer-events: none;" class="dnXmp"></grammarly-extension>
                                        <textarea class="form-control" id="remark" name="remark" spellcheck="false"></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            
                            
                            
                            
                            <!-- SERVICE PREFERENCE -->
                            <div class="col-lg-12">
                                <h3>SERVICE PREFERENCE</h3>
                            </div>
                    
                           
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="resume" class="form-label">Category</label>
                                    <div class="form-control-wrap">
                                       
                                         <select class="js-select" id="category" name="category[]" data-search="true" data-sort="false" multiple="multiple" required>
                                       <!--<select  name="category[]" id="category" class="js-select" multiple data-search="true" data-sort="false">-->
                                           
                                           <option value="">Select Category</option>
                                             @foreach($categories as $category)
                                           <option value="{{$category->category_name}}">{{$category->category_name}}</option>
                                           @endforeach
                                       </select>
                                    </div>
                                </div>
                            </div>
                              <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="resume" class="form-label">Mode of Training</label>
                                    <div class="form-control-wrap">
                                       
                                        <select class="js-select" id="mode_of_tutoring" name="mode_of_tutoring[]" data-search="true" data-sort="false" multiple="multiple" required>
                                            
                                       <!--<select name="mode_of_tutoring" class="form-control">-->
                                            <option value="">Select option</option>
                                           <option value="Online">Online</option>
                                             <option value="In-person">In-person</option>
                                       </select>
                                    </div>
                                </div>
                            </div>
                    
                         
                              <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="resume" class="form-label">Preferable location</label>
                                    <div class="form-control-wrap">
                                        <select class="js-select form-control" id="preferable_location" name="preferable_location[]" data-search="true" data-sort="false" multiple="multiple" required>
                                       <!--<select name="preferable_location" class="form-control">-->
                                           <option value="">Select option</option>
                                           @foreach($cities as $city)
                                           <option value="{{$city->name}}">{{$city->name}}</option>
                                           @endforeach
                                       </select>
                                    </div>
                                </div>
                            </div>
                            
                            
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="resume" class="form-label">Teaching Experience</label>
                                    <div class="form-control-wrap">
                                       
                                       <input type="text" class="form-control" id="teaching_experience" name="teaching_experience" required>
                                    </div>
                                </div>
                            </div>
                            
                            
                            
                            
                            <!-- Files -->
                            <div class="col-lg-12">
                                <h3>DOCUMENTS</h3>
                            </div>
                    
                            <!-- Resume -->
                           
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="resume" class="form-label">Resume</label>
                                    <div class="form-control-wrap">
                                       
                                        <input type="file" class="form-control" id="resume" name="resume" required>
                                    </div>
                                </div>
                            </div>
                            
                    
                            <!-- Education Transcript -->
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="education_transcript" class="form-label">Education Transcript</label>
                                    <div class="form-control-wrap">
                                       
                                        <input type="file" class="form-control" id="education_transcript" name="education_transcript" required>
                                    </div>
                                </div>
                            </div>
                    
                            <!-- Formal Photo -->
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="formal_photo" class="form-label">Formal Photo</label>
                                    <div class="form-control-wrap">
                                       
                                        <input type="file" class="form-control" id="formal_photo" name="formal_photo" required>
                                    </div>
                                </div>
                            </div>
                    
                            <!-- Identity Card Front -->
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="identity_card_front" class="form-label">Identity Card Front</label>
                                    <div class="form-control-wrap">
                                       
                                        <input type="file" class="form-control" id="identity_card_front" name="identity_card_front" required>
                                    </div>
                                </div>
                            </div>
                    
                            <!-- Emergency Contact -->
                            <div class="col-lg-12">
                                <h3>EMERGENCY CONTACT</h3>
                            </div>
                    
                            <!-- Emergency Contact Name -->
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="emergency_contact_name" class="form-label">Emergency Contact Name</label>
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" required>
                                    </div>
                                </div>
                            </div>
                    
                            <!-- Relationship -->
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="relationship" class="form-label">Relationship</label>
                                    <div class="form-control-wrap">
                                        <!--<input type="text" class="form-control" id="relationship" name="relationship">-->
                                        
                                         <select class="form-control" id="relationship" name="relationship" data-search="true" data-sort="false" required>
                                            <option value="">Select Your Receiving Account</option>
                                            <option value="Parent">Parent</option>
                                            <option value="Spouse">Spouse</option>
                                            <option value="Sibling">Sibling</option>
                                            <option value="Friend">Friend</option>
                                           
                                         </select>
                                        
                                    </div>
                                </div>
                            </div>
                    
                            <!-- Emergency Contact Number -->
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="emergency_contact_number" class="form-label">Emergency Contact Number</label>
                                    <div class="form-control-wrap">
                                        <input type="tel" class="form-control" id="emergency_contact_number" name="emergency_contact_number" required>
                                    </div>
                                </div>
                            </div>
                    
                            <!-- Education -->
                            <div class="col-lg-12">
                                <h3>EDUCATION</h3>
                            </div>
                    
                            <!-- Highest Education -->
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="highest_education" class="form-label">Highest Education</label>
                                    <div class="form-control-wrap">
                                        <!--<input type="text" class="form-control" id="highest_education" name="highest_education" >-->
                                         <select class="form-control" id="highest_education" name="highest_education"  data-search="true" data-sort="false" required>
                                            <option value="">Select Your Receiving Account</option>
                                            <option value="Diploma">Diploma</option>
                                            <option value="Bachelor’s Degree">Bachelor’s Degree</option>
                                            <option value="Master’s Degree">Master’s Degree</option>
                                            <option value="Doctorate (PhD)">Doctorate (PhD)</option>
                                            <option value="Professional Qualifications (e.g., ACCA, CPA)">Professional Qualifications (e.g., ACCA, CPA)</option>
                                         </select>
                                    </div>
                                </div>
                            </div>
                    
                            <!-- Field of Study -->
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="field_of_study" class="form-label">Field of Study</label>
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" id="field_of_study" name="field_of_study" required>
                                    </div>
                                </div>
                            </div>
                    
                            <!-- Academic Year -->
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="academic_year" class="form-label">Academic Year</label>
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" id="academic_year" name="academic_year" required>
                                    </div>
                                </div>
                            </div>
                    
                            <!-- Institution Name -->
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="institution_name" class="form-label">Institution Name</label>
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" id="institution_name" name="institution_name" required>
                                    </div>
                                </div>
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
                              <div class="col-lg-3"><button class="btn btn-primary" type="submit">Submit</button></div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script>

  function updatePhoneNumber() {
      countryCode="+60";
    $("#mobile_code").val(countryCode);
  }
  updatePhoneNumber();

    $(document).ready(function () {
        
        var $getAgeInput = $('#age');
        var $getDateOfBirth = $('#dob');
        
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
        
        $('#dob').on('input', function () {
            var dob = $getDateOfBirth.val();
            var today = new Date();
            var birthDate = new Date(dob);
            var age = today.getFullYear() - birthDate.getFullYear();
            if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) {
                age--;
            }
            $getAgeInput.val(age);
        });
        
        
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
        
     $("#nric").on("focusout",function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let nric=$("#nric").val();
        $.ajax({
            url: '{{ route('checkDuplicateNric') }}',
            type: 'POST',
            data: {
                nric: nric,
            },
            success: function (response) {
                if(response.recordFound==true)
                {
                    $("#nricErrorMsg").css("display","block");
                      $("#nric").val('');
                   
                }else{
                    $("#nricErrorMsg").css("display","none");
                }
            },
            error: function (error) {
                console.error(error);
                alert('Error occurred during the request.');
            }
        });
    });
});

// google.maps.event.addDomListener(window, 'load', initialize);
// function initialize() {
// var input = document.getElementById('streetAddress1');
// var studentInput = document.getElementById('studentAddress');
// var autocomplete = new google.maps.places.Autocomplete(input);
// var studentAutocomplete = new google.maps.places.Autocomplete(studentInput);
//     autocomplete.addListener('place_changed', function () {
//     var place = autocomplete.getPlace();
//     console.log(place);
//       document.getElementById("customerLatitude").value = place.geometry['location'].lat();
//       document.getElementById("customerLongitude").value = place.geometry['location'].lng();
//     });
//     studentAutocomplete.addListener('place_changed', function () {
//     var place = studentAutocomplete.getPlace();
//       document.getElementById("studentLatitude").value = place.geometry['location'].lat();
//       document.getElementById("studentLongitude").value = place.geometry['location'].lng();
//     });
// }

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