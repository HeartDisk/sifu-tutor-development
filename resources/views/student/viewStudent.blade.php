@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
   <div class="fluid-container">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head">
               <div class="nk-block-head-between flex-wrap gap g-2">
                  <div class="nk-block-head-content">
                     <h2 class="nk-block-title">View Student</h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Student List</a></li>
                           <li class="breadcrumb-item active" aria-current="page">View Student</li>
                        </ol>
                     </nav>
                  </div>
               </div>
            </div>
            <div class="nk-block">
               <div class="card">
                  <div class="card-body">
                     <h3>Student Information</h3>
                     <div class="row g-1 view-sindetails">
                        <div class="col-md-3 details-item">
                           <p class="item-title">Status</p>
                           @if($student->status == "inactive")
                           <p class="dtable-status-inactive">{{$student->status}}</p>
                           @elseif($student->status == "pending")
                           <p class="dtable-status-pending">{{$student->status}}</p>
                           @elseif($student->status == "active")
                           <p class="dtable-status-active">{{$student->status}}</p>
                           @endif
                        </div>
                        <div class="col-md-3 details-item">
                           <p class="item-title">Register Date</p>
                           <p><strong>{{$student->register_date ?? 'N/A'}}</strong></p>
                        </div>
                        @if($student->status == "inactive")
                        <div class="col-md-3 details-item">
                           <p class="item-title">Reason Category</p>
                           <p><strong>{{$student->reasonCategory ?? 'N/A'}}</strong></p>
                        </div>
                        <div class="col-md-3 details-item">
                           <p class="item-title">Reason</p>
                           <p><strong>{{$student->reasonStatus ?? 'N/A'}}</strong></p>
                        </div>
                        @endif
                        <div class="col-md-3 details-item">
                           <p class="item-title">Fullname</p>
                           <p><strong>{{$student->full_name ?? 'N/A'}}</strong></p>
                        </div>
                        <div class="col-md-3 details-item">
                           <p class="item-title">Gender</p>
                           <p><strong>{{$student->gender ?? 'N/A'}}</strong></p>
                        </div>
                        <div class="col-md-3 details-item">
                           <p class="item-title">Age</p>
                           <p><strong>{{$student->age ?? 'N/A'}}</strong></p>
                        </div>
                        <div class="col-md-3 details-item">
                           <p class="item-title">Year of Birth</p>
                           <p><strong>{{$student->dob ?? 'N/A'}}</strong></p>
                        </div>
                        <div class="col-md-3 details-item">
                           <p class="item-title">Special Need</p>
                           <p><strong>{{$student->specialNeed ?? 'N/A'}}</strong></p>
                        </div>
                     </div>
                     
                     <h3>Contact Person</h3>
                     <div class="row g-1 view-sindetails">
                        <div class="col-md-3 details-item">
                           <p class="item-title">Fullname</p>
                           <p><strong>{{$customer->full_name ?? 'N/A'}}</strong></p>
                        </div>
                        <div class="col-md-3 details-item">
                           <p class="item-title">Email</p>
                           <p><strong>{{$customer->email ?? 'N/A'}}</strong></p>
                        </div>
                        <div class="col-md-3 details-item">
                           <p class="item-title">Phone No.</p>
                           <p><strong>{{$customer->phone ?? 'N/A'}}</strong>
                              @if(!empty($customer->phone))
                              <a href="tel:{{$customer->phone}}"><span class="fa fa-phone"></span></a>
                              @endif
                           </p>
                        </div>
                        
                        <div class="col-md-3 details-item">
                           <p class="item-title">Whatsapp</p>
                           <p><strong>{{$customer->whatsapp ?? 'N/A'}}</strong>
                              @if(!empty($customer->whatsapp))
                              <a href="https://wa.me/{{$customer->whatsapp}}" target="_blank"><strong><span class="fa fa-whatsapp text-success"></span></strong></a>
                              @endif
                           </p>
                        </div>
                        
                        <div class="col-md-3 details-item">
                           <p class="item-title">Gender</p>
                           <p><strong>{{$customer->gender ?? 'N/A'}}</strong></p>
                        </div>
                        
                       
                        
                        <div class="col-md-3 details-item">
                           <p class="item-title">Latitude</p>
                           <p><strong>{{$customer->latitude ?? 'N/A'}}</strong></p>
                        </div>
                        <div class="col-md-3 details-item">
                           <p class="item-title">Longitude</p>
                           <p><strong>{{$customer->longitude ?? 'N/A'}}</strong></p>
                        </div>
                        
                         <div class="col-md-3 details-item">
                           <p class="item-title">Nearby Landmark</p>
                           <p><strong>{{$customer->landmark ?? 'N/A'}}</strong></p>
                        </div>
                        
                         <div class="col-md-6 details-item">
                           <p class="item-title">Address</p>
                           <address>
                              <span><strong>{{ $customer->address ?? $customer->address1 ?? 'N/A' }}</strong>,</span><br>
                              <span><strong>{{$customerCity->name ?? 'N/A'}}</strong>,</span><br>
                              <span><strong>{{$customer->postal_code ?? 'N/A'}}</strong>,</span><br>
                              <span><strong>{{$customerState->name ?? 'N/A'}}</strong>,</span><br>
                           </address>
                        </div>
                        
                        <h3>Assign Admin Incharge</h3>
                        <form method="post" action="{{url("/assignAdminInChargeStudent")}}">
                           <div class="row">
                              <input type="hidden" name="student_id" value="{{$student->id}}">
                              @csrf
                              <div class="col-md-4">
                                 <select class="form-control" name="staff_id">
                                    <option value="">Please select Admin Incharge</option>
                                    @foreach($staffs as $staff)
                                    <option {{$student->staff_id == $staff->id ? "selected" : ""}} value="{{$staff->id}}">{{$staff->full_name}}</option>
                                    @endforeach
                                 </select>
                              </div>
                              <div class="col-md-3">
                                 <button type="submit" class="btn btn-primary">
                                    Assign Admin Incharge
                                 </button>
                              </div>
                           </div>
                        </form>
                        
                        <h3>SUBJECT SUBSCRIBES</h3>
                        <div class="table-responsive">
                           <table class="table">
                              <thead>
                                 <tr>
                                    <th>Subject Name</th>
                                    <th>Day</th>
                                    <th>Time (in 24 Hrs)</th>
                                    <th>Subscription Duration Term</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @foreach($subjects as $rowSubjectTwo)
                                 @php
                                 $subject_name = DB::table('products')->where('id', '=', $rowSubjectTwo->subject)->first();
                                 @endphp
                                 <tr id="R{{$loop->iteration}}">
                                    <td><p>{{$subject_name->name ?? 'N/A'}}</p></td>
                                    <td><p>{{$rowSubjectTwo->day ?? 'N/A'}}</p></td>
                                 </tr>
                                 @endforeach
                              </tbody>
                           </table>
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
