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
                       <form method="POST" action="{{ route('submitEditTutor') }}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $tutor->id }}"/>
    <div class="row">
        
        
        
  <!--  	<div class="col-md-6">-->
		<!--	<div class="form-group">-->
		<!--		<label class="form-label">Multiple select input</label>-->
		<!--		<div class="form-control-wrap">-->
		<!--			<select class="js-select" multiple data-search="true" data-sort="false">-->
		<!--				<option value="">Select Payment Method</option>-->
		<!--				<option value="1">PayPal</option>-->
		<!--				<option value="2">Bank Transfer</option>-->
		<!--				<option value="3">Skrill</option>-->
		<!--				<option value="4">Moneygram</option>-->
		<!--			</select>-->
		<!--		</div>-->
		<!--	</div>-->
		<!--</div>-->
        
        
        <!-- Tutor ID -->
        <div class="col-lg-3">
            <div class="form-group">
                <label for="tutorID" class="form-label">Tutor ID</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" readonly name="tutorID" value="{{ $tutor->tutor_id }}" id="tutorID">
                </div>
            </div>
        </div>

        <!-- Start Working Date -->
        <div class="col-lg-3">
            <div class="form-group">
                <label for="startWorkingDate" class="form-label">Start Working Date</label>
                <div class="form-control-wrap">
                    <input type="date" class="form-control" name="registration_date" value="{{ $tutor->start_date }}" id="startWorkingDate">
                </div>
            </div>
        </div>
        
          <!-- Training Date -->
        <div class="col-lg-3">
            <div class="form-group">
                <label for="attendedTrainingDate" class="form-label">Training Date</label>
                <div class="form-control-wrap">
                    <input type="date" class="form-control" id="attendedTrainingDate" name="attendedTrainingDate" value="{{ $tutor->training_date }}" placeholder="Training Date">
                </div>
            </div>
        </div>

        <!-- Date of Birth -->
        <div class="col-lg-3">
            <div class="form-group">
                <label for="dob" class="form-label">Date of Birth</label>
                <div class="form-control-wrap">
                    <input type="date" class="form-control" id="dob" name="dob" value="{{ $tutor->dob }}" placeholder="Date of Birth">
                </div>
            </div>
        </div>

        <!-- Full Name -->
        <div class="col-lg-3">
            <div class="form-group">
                <label for="firstname" class="form-label">Full Name</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="firstname" name="full_name" value="{{ $tutor->full_name }}" placeholder="Full Name">
                </div>
            </div>
        </div>

        <!-- Gender -->
        <div class="col-lg-3">
            <div class="form-group">
                <label for="gender" class="form-label">Gender</label>
                <div class="form-control-wrap">
                    <select class="" id="gender" name="gender">
                        <option value="Male" {{ $tutor->gender == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ $tutor->gender == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Email Address -->
        <div class="col-lg-3">
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <div class="form-control-wrap">
                    <input type="email" class="form-control" id="email" name="email" value="{{ $tutor->email }}" placeholder="Email Address" readonly>
                </div>
            </div>
        </div>

        <!-- ID Number -->
        <div class="col-lg-3">
            <div class="form-group">
                <label for="nric" class="form-label">ID Number</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="nric" name="CNIC" value="{{ $tutor->nric }}" placeholder="NRIC">
                     <label id="nricErrorMsg" style="color:red!important;display: none" class="form-label">NRIC already exist</label>
                </div>
            </div>
        </div>

        <!-- Phone Number -->
        <div class="col-lg-3">
            <div class="form-group">
                <label for="phoneNumber" class="form-label">Phone Number</label>
                <div class="form-control-wrap">
                    <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" value="{{ isset($tutor->phoneNumber)?$tutor->phoneNumber:"" }}" placeholder="Phone Number" readonly>
                </div>
            </div>
        </div>

        <!-- Age -->
        <div class="col-lg-3">
            <div class="form-group">
                <label for="age" class="form-label">Age</label>
                <div class="form-control-wrap">
                    <input type="number" class="form-control" id="age" name="age" value="{{ isset($tutor->age)?$tutor->age:"" }}" placeholder="Age">
                </div>
            </div>
        </div>

        <!-- Whatsapp Number -->
        <div class="col-lg-3">
            <div class="form-group">
                <label for="whatsapp" class="form-label">Whatsapp Number</label>
                <div class="form-control-wrap">
                    <input type="tel" class="form-control" id="whatsapp" name="whatsapp" value="{{ isset($tutor->whatsapp)?$tutor->whatsapp:"" }}" placeholder="Whatsapp Number" readonly>
                </div>
            </div>
        </div>

        <!-- Marital Status -->
        <div class="col-lg-3">
            <div class="form-group">
                <label for="maritalStatus" class="form-label">Marital Status</label>
                <div class="form-control-wrap">
                    <select  id="maritalStatus" name="maritalStatus">
                        <option value="Single" {{ $tutor->marital_status == 'Single' ? 'selected' : '' }}>Single</option>
                        <option value="Married" {{ $tutor->marital_status == 'Married' ? 'selected' : '' }}>Married</option>
                    </select>
                </div>
            </div>
        </div>

        @can("tutor-verify")
        <!-- Status -->
        <div class="col-lg-3">
            <div class="form-group">
                <label for="status" class="form-label">Status</label>
                <div class="form-control-wrap">
                    <select class="js-select" id="status" name="status" data-search="true" data-sort="false">
                        <option value="verified" {{ $tutor->status == 'verified' ? 'selected' : '' }}>Verified</option>
                        <option value="unverified" {{ $tutor->status == 'unverified' ? 'selected' : '' }}>Unverified</option>
                        <option value="inactive" {{ $tutor->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="terminated" {{ $tutor->status == 'terminated' ? 'selected' : '' }}>Terminated</option>
                        <option value="resigned" {{ $tutor->status == 'resigned' ? 'selected' : '' }}>Resigned</option>
                    </select>
                </div>
            </div>
        </div>
        @endcan

        <!-- Bank Name -->
        <div class="col-lg-3">
            <div class="form-group">
                <label for="bankName" class="form-label">Bank Name</label>
                <div class="form-control-wrap">
                    <select data-search="true" data-sort="false" name="bankName" id="bankName" required>
                        <option value="">Please select Bank</option>
                        @php
                            $Banks = DB::table('Banks')->get();
                        @endphp
                        @foreach($Banks as $Bank)
                            <option value="{{ $Bank->bank_name }}" 
                                @if(isset($tutor) && $tutor->bank_name == $Bank->bank_name) selected @endif>
                                {{ $Bank->bank_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Bank Account Number -->
        <div class="col-lg-3">
            <div class="form-group">
                <label for="bankAccountNumber" class="form-label">Bank Account Number</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="bankAccountNumber" name="bankAccountNumber" value="{{ $tutor->bank_account_number }}" required>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <h3>TUTOR ADDRESS</h3>
        </div>


        <!-- Street Address 1 -->
        <div class="col-lg-6">
            <div class="form-group">
                <label for="streetAddress1" class="form-label">Street Address 1</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="streetAddress1" name="street_address1" value="{{ $tutor->street_address1 }}" placeholder="Street Address 1" required>
                </div>
            </div>
        </div>
        

        <!-- Latitude -->
        <div class="col-lg-3">
            <div class="form-group">
                <label for="latitude" class="form-label">Latitude</label>
                <div class="form-control-wrap">
                    <input type="text" class="customerLatitude form-control" id="customerLatitude" name="latitude" value="{{ $tutor->latitude }}" placeholder="Latitude" required>
                </div>
            </div>
        </div>

        <!-- Longitude -->
        <div class="col-lg-3">
            <div class="form-group">
                <label for="longitude" class="form-label">Longitude</label>
                <div class="form-control-wrap">
                    <input type="text" class="customerLongitude form-control" id="customerLongitude" name="longitude" value="{{ $tutor->longitude }}" placeholder="Longitude" required>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3">
             <div class="form-group">
            <label for="country" class="form-label">State</label>
         
            <div class="form-control-wrap">
               <select id="customerState" data-search="true" name="state" data-sort="false" required>
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
                <select class=" form-control" data-search="true" data-sort="false" name="city" id="customerCity" placeholder="City" required>
                   @if($cityDetail)
                   <option value="">{{$cityDetail->name}}</option>
                   @endif
                </select>
             </div>
          </div>

        <!-- Postal Code -->
        <div class="col-lg-3">
            <div class="form-group">
                <label for="postalcode" class="form-label">Postal Code</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="postalcode" name="postalcode" value="{{ $tutor->postal_code }}" placeholder="Postal Code" required>
                </div>
            </div>
        </div>

      

        <!-- Remark -->
        <div class="col-lg-6">
            <div class="form-group">
                <label for="remark" class="form-label">Remark</label>
                <div class="form-control-wrap">
                    <textarea class="form-control" id="remark" name="remark" required>{{ $tutor->remark }}</textarea>
                </div>
            </div>
        </div>

        <!-- Shirt Size -->
        <div class="col-lg-3">
            <div class="form-group">
                <label for="shirtSize" class="form-label">Shirt Size</label>
                <div class="form-control-wrap">
                    <select class="form-control" name="shirtSize"id="shirtSize" required>
                        <option>Select Shirt Size</option>
                        <option {{ $tutor->shirt_size == "Extra Small" ? "selected" : "" }} value="Extra Small">Extra Small</option>
                        <option {{ $tutor->shirt_size == "Small" ? "selected" : "" }} value="Small">Small</option>
                        <option {{ $tutor->shirt_size == "Medium" ? "selected" : "" }} value="Medium">Medium</option>
                        <option {{ $tutor->shirt_size == "Large" ? "selected" : "" }} value="Large">Large</option>
                        <option {{ $tutor->shirt_size == "Extra Large" ? "selected" : "" }} value="Extra Large">Extra Large</option>
                    </select>
                    <!--<input type="text" class="form-control" id="shirtSize" name="shirtSize" value="{{ $tutor->shirt_size }}" placeholder="Shirt Size">-->
                </div>
            </div>
        </div>

        
        
        <!-- SERVICE PREFERENCE -->
        @if(isset($servicePreferences))
        <div class="col-lg-12">
            <h3>SERVICE PREFERENCE</h3>
        </div>

       
 <!-- Category Select -->
<div class="col-md-3">
    <div class="form-group">
        <label for="category" class="form-label">Category</label>
        <div class="form-control-wrap">
            <select name="category[]" id="category"  class="js-select" multiple="multiple" data-search="true" data-sort="false">
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option {{ in_array($category->category_name, $selectedCategories) ? "selected" : "" }} value="{{$category->category_name}}">
                        {{$category->category_name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<!-- Preferable Location Select -->
<div class="col-md-3">
    <div class="form-group">
        <label for="preferable_location" class="form-label">Preferable Location</label>
        <div class="form-control-wrap">
            <select name="preferable_location[]" id="preferable_location" class="js-select" multiple="multiple" data-search="true" data-sort="false">
                <option value="">Select option</option>
                @php
                    $selectedLocations = explode(', ', $servicePreferences->preferable_location);
                @endphp
                @foreach($cities as $city)
                    <option {{ in_array($city->name, $selectedLocations) ? "selected" : "" }} value="{{$city->name}}">
                        {{$city->name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>


<!-- Mode of Training Select -->
<div class="col-md-3">
    <div class="form-group">
        <label for="mode_of_tutoring" class="form-label">Mode of Training</label>
        <div class="form-control-wrap">
            <select name="mode_of_tutoring[]" id="mode_of_tutoring" class="js-select" multiple="multiple" data-search="true" data-sort="false">
                <option value="">Select option</option>
                @php
                    $selectedModes = explode(', ', $servicePreferences->mode_of_tutoring);
                @endphp
                <option {{ in_array("Online", $selectedModes) ? "selected" : "" }} value="Online">Online</option>
                <option {{ in_array("In-person", $selectedModes) ? "selected" : "" }} value="In-person">In-person</option>
            </select>
        </div>
    </div>
</div>


        
        
       <div class="col-lg-3">
    <div class="form-group">
        <label for="resume" class="form-label">Teaching Experience</label>
        <div class="form-control-wrap">
            <input type="text" class="form-control" value="{{$servicePreferences->teaching_experience}}" id="teaching_experience" name="teaching_experience">
        </div>
    </div>
</div>

        @endif

      

        <!-- Files -->
        <div class="col-lg-12">
            <h3>DOCUMENTS</h3>
        </div>

        <!-- Resume -->
       
        <div class="col-lg-3">
            <div class="form-group">
                <label for="resume" class="form-label">Resume</label>
                <div class="form-control-wrap">
                     @if(isset($documents->resume_url))
                    <a class="dview-status-viewfile"  target="_blank" href="{{url("/storage/app/public/$documents->resume_url")}}">View Resume</a><br/><br/>
                    @endif
                    <input type="file" class="form-control" id="resume" name="resume">
                </div>
            </div>
        </div>
        

        <!-- Education Transcript -->
        <div class="col-lg-3">
            <div class="form-group">
                <label for="education_transcript" class="form-label">Education Transcript</label>
                <div class="form-control-wrap">
                     @if(isset($documents->resume_url))
                    <a class="dview-status-viewfile"  target="_blank" href="{{url("/storage/app/public/$documents->education_transcript_url")}}">View Transcript </a><br/><br/>
                    @endif
                    <input type="file" class="form-control" id="education_transcript" name="education_transcript">
                </div>
            </div>
        </div>

        <!-- Formal Photo -->
        <div class="col-lg-3">
            <div class="form-group">
                <label for="formal_photo" class="form-label">Formal Photo</label>
                <div class="form-control-wrap">
                     @if(isset($documents->resume_url))
                    <a class="dview-status-viewfile" target="_blank" href="{{url("/storage/app/public/$documents->identity_card_front_url")}}">View Formal Photo</a><br/><br/>
                    @endif
                    <input type="file" class="form-control" id="formal_photo" name="formal_photo">
                </div>
            </div>
        </div>

        <!-- Identity Card Front -->
        <div class="col-lg-3">
            <div class="form-group">
                <label for="identity_card_front" class="form-label">Identity Card Front</label>
                <div class="form-control-wrap">
                     @if(isset($documents->resume_url))
                    <a class="dview-status-viewfile"  target="_blank" href="{{url("/storage/app/public/$documents->formal_photo_url")}}">View Identity Card Front</a><br/><br/>
                    @endif
                    <input type="file" class="form-control" id="identity_card_front" name="identity_card_front">
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
                    <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" value="{{ $emergencyContact?$emergencyContact->emergency_contact_name:"" }}">
                </div>
            </div>
        </div>
        
        
       
        
        <!-- Relationship -->
        <div class="col-lg-4">
            <div class="form-group">
                <label for="relationship" class="form-label">Relationship</label>
                <div class="form-control-wrap">
                    <!--<input type="text" class="form-control" id="relationship" name="relationship" value="{{ $emergencyContact?$emergencyContact->relationship:"" }}">-->
                    
                 <select class="form-control" id="relationship" name="relationship" data-search="true" data-sort="false">
                    <option value="">Select Your Receiving Account</option>
                    <option value="Parent" {{ $emergencyContact->relationship ?? '' == "Parent" ? "selected" : "" }}>Parent</option>
                    <option value="Spouse" {{ $emergencyContact->relationship ?? '' == "Spouse" ? "selected" : "" }}>Spouse</option>
                    <option value="Sibling" {{ $emergencyContact->relationship ?? '' == "Sibling" ? "selected" : "" }}>Sibling</option>
                    <option value="Friend" {{ $emergencyContact->relationship ?? '' == "Friend" ? "selected" : "" }}>Friend</option>
                </select>
                    
                </div>
            </div>
        </div>

        <!-- Emergency Contact Number -->
        <div class="col-lg-4">
            <div class="form-group">
                <label for="emergency_contact_number" class="form-label">Emergency Contact Number</label>
                <div class="form-control-wrap">
                    <input type="tel" class="form-control" id="emergency_contact_number" name="emergency_contact_number" value="{{ $emergencyContact?$emergencyContact->emergency_contact_number:"" }}">
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
                    <!--<input type="text" class="form-control" id="highest_education" name="highest_education" value="{{ $education?$education->highest_education:"" }}">-->
                    
                  <select class="form-control" id="highest_education" name="highest_education" data-search="true" data-sort="false">
                    <option value="">Select Your Receiving Account</option>
                    <option value="Diploma" {{ $education->highest_education ?? '' == "Diploma" ? "selected" : "" }}>Diploma</option>
                    <option value="Bachelor’s Degree" {{ $education->highest_education ?? '' == "Bachelor’s Degree" ? "selected" : "" }}>Bachelor’s Degree</option>
                    <option value="Master’s Degree" {{ $education->highest_education ?? '' == "Master’s Degree" ? "selected" : "" }}>Master’s Degree</option>
                    <option value="Doctorate (PhD)" {{ $education->highest_education ?? '' == "Doctorate (PhD)" ? "selected" : "" }}>Doctorate (PhD)</option>
                    <option value="Professional Qualifications (e.g., ACCA, CPA)" {{ $education->highest_education ?? '' == "Professional Qualifications (e.g., ACCA, CPA)" ? "selected" : "" }}>Professional Qualifications (e.g., ACCA, CPA)</option>
                </select>

                    
                    
                </div>
            </div>
        </div>

        <!-- Field of Study -->
        <div class="col-lg-3">
            <div class="form-group">
                <label for="field_of_study" class="form-label">Field of Study</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="field_of_study" name="field_of_study" value="{{ $education?$education->field_of_study:"" }}">
                    
                </div>
            </div>
        </div>

        <!-- Academic Year -->
        <div class="col-lg-3">
            <div class="form-group">
                <label for="academic_year" class="form-label">Academic Year</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="academic_year" name="academic_year" value="{{ $education?$education->academic_year:"" }}">
                </div>
            </div>
        </div>

        <!-- Institution Name -->
        <div class="col-lg-3">
            <div class="form-group">
                <label for="institution_name" class="form-label">Institution Name</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="institution_name" name="institution_name" value="{{ $education?$education->institution_name:"" }}">
                </div>
            </div>
        </div>

     
    </div>
    
    
                    <div class="col-lg-12">
    <h3>COMMITMENT FEE</h3>
    <p>RM 100 payment receipt is required to be uploaded for a new tutor registration.</p>
    @php
    $tutorCommitmentFees = DB::table('tutor_commitment_fees')->where('tutor_id', '=', $tutor->id)->get();
    @endphp

    @if(isset($tutorCommitmentFees) && count($tutorCommitmentFees) > 0)
        <!-- Display existing commitment fees if available -->
        @foreach($tutorCommitmentFees as $tutorCommitmentFee)
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
        <!-- Show input table if no commitment fees are available -->
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
                        <td><input type="file" class="form-control" name="paymentAttachment" id="paymentAttachment"></td>
                        <td><input type="number" class="form-control" name="feeAmount" id="feeAmount" placeholder="Enter the Fee Amount"></td>
                        <td><input type="date" class="form-control" name="feePaymentDate" id="feePaymentDate"></td>
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
                    </tr>
                </tbody>
            </table>
        </div>
    @endif
</div>

                           
    <div class="col-lg-3">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>

               </div>
            </div>
         </div>
      </div>
   </div>
</div>

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
