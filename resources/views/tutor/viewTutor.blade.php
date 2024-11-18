@extends('layouts.main')
@section('content')
    <div class="nk-content sifu-view-page">
        <div class="fluid-container">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head">
                        <div class="nk-block-head-between flex-wrap gap g-2">
                            <div class="nk-block-head-content">
                                <h2 class="nk-block-title">Tutor Details</h2>
                                <nav>
                                    <ol class="breadcrumb breadcrumb-arrow mb-0">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item"><a href="#">Tutor List</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Tutor Details</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>

                    <div class="nk-block">
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
                            <div class="row mb-5">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h3>Tutor Information</h3>
                                            <div class="row g-1 view-sindetails">
                                                <div class="col-md-3 details-item">
                                                    <p class="item-title">Tutor Id</p>
                                                    <p><strong>{{ $tutor->tutor_id ?? 'Not Provided' }}</strong></p>
                                                </div>
                                                <div class="col-md-3 details-item">
                                                    <p class="item-title">Start Working Date</p>
                                                    <p>
                                                        <strong>{{ $tutor->start_date ?? 'Not Provided' }}</strong>
                                                    </p>
                                                </div>
                                                
                                                 <div class="col-md-3 details-item">
                                                    <p class="item-title">Training Date</p>
                                                    <p>
                                                        <strong>{{ $tutor->training_date ?? 'Not Provided' }}</strong>
                                                    </p>
                                                </div>
                                                
                                                 <div class="col-md-3 details-item">
                                                    <p class="item-title">Date of Birth</p>
                                                    <p>
                                                        <strong>{{ $tutor->dob ?? 'Not Provided' }}</strong>
                                                    </p>
                                                </div>
                                                
                                                <div class="col-md-3 details-item">
                                                    <p class="item-title">Status</p>
                                                    <p><strong>{{ $tutor->status ?? 'Not Provided' }}</strong></p>
                                                </div>
                                                
                                                <div class="col-md-3 details-item">
                                                    <p class="item-title">Full Name</p>
                                                    <p><strong>{{ $tutor->full_name ?? 'Not Provided' }}</strong></p>
                                                </div>
                                                <div class="col-md-3 details-item">
                                                    <p class="item-title">Gender</p>
                                                    <p><strong>{{ ucfirst($tutor->gender) ?? 'Not Provided' }}</strong></p>
                                                </div>
                                                <div class="col-md-3 details-item">
                                                    <p class="item-title">ID Number</p>
                                                    <p><strong>{{ $tutor->nric ?? 'Not Provided' }}</strong></p>
                                                </div>
                                                <div class="col-md-3 details-item">
                                                    <p class="item-title">Marital Status</p>
                                                    <p><strong>{{ $tutor->marital_status ?? 'Not Provided' }}</strong></p>
                                                </div>
                                                <div class="col-md-3 details-item">
                                                    <p class="item-title">Email</p>
                                                    <p><strong>{{ $tutor->email ?? 'Not Provided' }}</strong></p>
                                                </div>
                                                <div class="col-md-3 details-item">
                                                    <p class="item-title">Phone No.</p>
                                                    <p><strong>{{ $tutor->phoneNumber ?? 'Not Provided' }}</strong>
                                                        <a href="https://wa.me/{{ $tutor->whatsapp ?? '' }}" target="_blank"
                                                           style="margin-right:5px;"><strong><span
                                                                    class="fa fa-whatsapp text-success"></span></strong></a>
                                                        <a href="tel:{{ $tutor->phoneNumber ?? '' }}"><span
                                                                class="fa fa-phone"></span></a>
                                                    </p>
                                                </div>
                                                 <div class="col-md-3 details-item">
                                                    <p class="item-title">Age.</p>
                                                    <p><strong>{{ $tutor->age ?? 'Not Provided' }}</strong>
                                                    </p>
                                                </div>
                                                <div class="col-md-3 details-item">
                                                    <p class="item-title">Bank Name</p>
                                                    <p><strong>{{ $tutor->bank_name ?? 'Not Provided' }}</strong></p>
                                                </div>
                                                <div class="col-md-3 details-item">
                                                    <p class="item-title">Shirt Size</p>
                                                    <p><strong>{{ $tutor->shirt_size ?? 'Not Provided' }}</strong></p>
                                                </div>
                                                <div class="col-md-3 details-item">
                                                    <p class="item-title">Bank Account No.</p>
                                                    <p><strong>{{ $tutor->bank_account_number ?? 'Not Provided' }}</strong></p>
                                                </div>
                                               
                                                 <div class="col-md-3 details-item">
                                                    <p class="item-title">State</p>
                                                    <p><strong>{{ $tutor->state ?? 'Not Provided' }}</strong></p>
                                                </div>
                                                 <div class="col-md-3 details-item">
                                                    <p class="item-title">City</p>
                                                    <p><strong>{{ $tutor->city ?? 'Not Provided' }}</strong></p>
                                                </div>
                                                
                                                 <div class="col-md-3 details-item">
                                                    <p class="item-title">Address</p>
                                                    <p><strong>{{ $tutor->street_address1 ?? 'Not Provided' }}</strong></p>
                                                </div>
                                                
                                                <div class="col-md-3 details-item">
                                                    <p class="item-title">Remark</p>
                                                    <p><strong>{{ $tutor->remark ?? 'Not Provided' }}</strong></p>
                                                </div>

                                                @if(isset($bio_details))
                                                    <div class="col-md-12">
                                                        <h3>Bio Details</h3>
                                                    </div>
                                                    <div class="col-md-3 details-item">
                                                        <p class="item-title">Full Name</p>
                                                        <p><strong>{{ $bio_details->full_name ?? 'Not Provided' }}</strong></p>
                                                    </div>
                                                    <div class="col-md-3 details-item">
                                                        <p class="item-title">Phone number</p>
                                                        <p><strong>{{ $bio_details->phone_number ?? 'Not Provided' }}</strong></p>
                                                    </div>
                                                    <div class="col-md-3 details-item">
                                                        <p class="item-title">email</p>
                                                        <p><strong>{{ $bio_details->email ?? 'Not Provided' }}</strong></p>
                                                    </div>
                                                    <div class="col-md-3 details-item">
                                                        <p class="item-title">ic number</p>
                                                        <p><strong>{{ $bio_details->ic_number ?? 'Not Provided' }}</strong></p>
                                                    </div>
                                                    <div class="col-md-3 details-item">
                                                        <p class="item-title">Residential address</p>
                                                        <p><strong>{{ $bio_details->residential_address ?? 'Not Provided' }}</strong></p>
                                                    </div>

                                                    <div class="col-md-3 details-item">
                                                        <p class="item-title">Postal code</p>
                                                        <p><strong>{{ $bio_details->postal_code ?? 'Not Provided' }}</strong></p>
                                                    </div>
                                                @endif

                                                @if(isset($service_preferences))
                                                    <div class="col-md-12">
                                                        <h3>Service Preferences</h3>
                                                    </div>
                                                        <div class="col-md-4 details-item">
                                                            <p class="item-title">Category</p>
                                                            <p><strong>{{ $service_preferences->category ?? 'Not Provided' }}</strong></p>
                                                        </div>
                                                        <div class="col-md-4 details-item">
                                                            <p class="item-title">Mode</p>
                                                            <p>
                                                                <strong>{{ $service_preferences->mode_of_tutoring ?? 'Not Provided' }}</strong>
                                                            </p>
                                                        </div>
                                                        <div class="col-md-4 details-item">
                                                            <p class="item-title">Preferable location</p>
                                                            <p>
                                                                <strong>{{ $service_preferences->preferable_location ?? 'Not Provided' }}</strong>
                                                            </p>
                                                        </div>
                                                    
                                                        <div class="col-md-12 details-item">
                                                            <p class="item-title">Teaching Experience</p>
                                                            <p>
                                                                <strong>{{ $service_preferences->teaching_experience ?? 'Not Provided' }}</strong>
                                                            </p>
                                                        </div>
                                                @endif

                                                @if(isset($emergency_contacts))
                                                    <div class="col-md-12">
                                                        <h3>Emergency Contact</h3>
                                                    </div>
                                                    <div class="col-md-4 details-item">
                                                        <p class="item-title">Name</p>
                                                        <p>
                                                            <strong>{{ $emergency_contacts->emergency_contact_name ?? 'Not Provided' }}</strong>
                                                        </p>
                                                    </div>
                                                    <div class="col-md-4 details-item">
                                                        <p class="item-title">Relationship</p>
                                                        <p><strong>{{ $emergency_contacts->relationship ?? 'Not Provided' }}</strong></p>
                                                    </div>
                                                    <div class="col-md-4 details-item">
                                                        <p class="item-title">Number</p>
                                                        <p>
                                                            <strong>{{ $emergency_contacts->emergency_contact_number ?? 'Not Provided' }}</strong>
                                                        </p>
                                                    </div>
                                                @endif

                                                @if(isset($educations))
                                                    <div class="col-md-12">
                                                        <h3>Education</h3>
                                                    </div>
                                                    <div class="col-md-3 details-item">
                                                        <p class="item-title">Name</p>
                                                        <p><strong>{{ $educations->highest_education ?? 'Not Provided' }}</strong></p>
                                                    </div>
                                                    <div class="col-md-3 details-item">
                                                        <p class="item-title">Field of Study</p>
                                                        <p><strong>{{ $educations->field_of_study ?? 'Not Provided' }}</strong></p>
                                                    </div>
                                                    <div class="col-md-3 details-item">
                                                        <p class="item-title">Academic Year</p>
                                                        <p><strong>{{ $educations->academic_year ?? 'Not Provided' }}</strong></p>
                                                    </div>
                                                    <div class="col-md-3 details-item">
                                                        <p class="item-title">Institution Name</p>
                                                        <p><strong>{{ $educations->institution_name ?? 'Not Provided' }}</strong></p>
                                                    </div>
                                                @endif

                                                @if(isset($documents))
                                                    <div class="col-md-12">
                                                        <h3>Documents</h3>
                                                    </div>

                                                    @if(isset($documents->resume_url))
                                                        <div class="col-md-3 details-item">
                                                            <p class="item-title">Resume</p>
                                                            @if(isset($documents->resume_url))
                                                                <a class="dview-status-viewfile" target="_blank" href="{{url("/storage/app/public/$documents->resume_url")}}">View </a>
                                                            @endif
                                                        </div>
                                                    @endif
                                                    @if(isset($documents->education_transcript_url))
                                                        <div class="col-md-3 details-item">
                                                            <p class="item-title">Education Transcript</p>
                                                            @if(isset($documents->education_transcript_url))
                                                                <a class="dview-status-viewfile" target="_blank"  href="{{url("/storage/app/public/$documents->education_transcript_url")}}">View </a>
                                                            @endif
                                                        </div>
                                                    @endif
                                                    @if(isset($documents->identity_card_front_url))
                                                        <div class="col-md-3 details-item">
                                                            <p class="item-title">Identity Card Front</p>
                                                            @if(isset($documents->identity_card_front_url))
                                                                <a class="dview-status-viewfile" target="_blank"  href="{{url("/storage/app/public/$documents->identity_card_front_url")}}">View </a>
                                                            @endif
                                                        </div>
                                                    @endif

                                                    @if(isset($documents->formal_photo_url))
                                                        <div class="col-md-3 details-item">
                                                            <p class="item-title">Formal Photo</p>
                                                            @if(isset($documents->formal_photo_url))
                                                                <a class="dview-status-viewfile" target="_blank" href="{{url("/storage/app/public/$documents->formal_photo_url")}}">View </a>
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endif

                                                <div class="col-md-12">
                                                    <h3>Commitment Fee</h3>
                                                </div>
                                                <p>RM 100 payment receipt is required to be uploaded for a new tutor
                                                    registration.</p>
                                                @php
                                                    if($tutor)
                                                    {
                                                    $tutorCommitmentFee = DB::table('tutor_commitment_fees')->where('tutor_id','=',$tutor->id)->get();
                                                    }
                                                    
                                                @endphp
                                                @if(isset($tutorCommitmentFee))
                                                @foreach($tutorCommitmentFee as $row)
                                                    <div class="col-lg-3 details-item">
                                                        <p class="item-title">Remark</p>
                                                        <p><a class="dview-status-viewfile" data-lightbox="image"
                                                              target="_blank"
                                                              href="{{ asset('/public/tutorPaymentAttachment/' . $row->payment_attachment) }}">View
                                                                File</a></p>
                                                    </div>
                                                    <div class="col-md-3 details-item">
                                                        <p class="item-title">Fee Payment Date</p>
                                                        <p><strong>{{ $row->payment_date ?? 'Not Provided' }}</strong></p>
                                                    </div>
                                                    <div class="col-md-3 details-item">
                                                        <p class="item-title">Fees Paid</p>
                                                        <p><strong>{{ $row->payment_amount ?? 'Not Provided' }}</strong></p>
                                                    </div>
                                                    <div class="col-md-3 details-item">
                                                        <p class="item-title">Receiving Account</p>
                                                        <p><strong>{{ $row->receiving_account ?? 'Not Provided' }}</strong></p>
                                                    </div>
                                                @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
