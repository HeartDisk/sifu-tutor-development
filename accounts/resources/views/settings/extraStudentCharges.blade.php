@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
   <div class="fluid-container">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head">
               <div class="nk-block-head-between flex-wrap gap g-2">
                  <div class="nk-block-head-content">
                     <h2 class="nk-block-title">
                        Extra Student Charges
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Subjects</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Extra Student Charges</li>
                        </ol>
                     </nav>
                  </div>
               </div>
            </div>
            @php
            $number = 1;
            $extraStudentCharges = DB::table('extra_student_charges')->first();
            @endphp
            <div class="nk-block">
               <div class="card">
                  <div class="card-body">
                     <form method="POST" action="{{route('submitExtraStudentCharges')}}">
                        <div class="row">
                           @csrf
                           <div class="col-md-3">
                             <div class="form-group">
                                <label class="form-label" for="charges">Extra Student Charges(Online)</label>
                                <div class="form-control-wrap">
                                  <input type="number" class="form-control" name="online_additional_charges" value="{{$extraStudentCharges->online_additional_charges}}" class="form-control" id="charges" aria-describedby="emailHelp" placeholder="Enter Student Charges">
                                </div>
                              </div>
                           </div>
                           <div class="col-md-3">
                             <div class="form-group">
                                <label class="form-label" for="charges">Extra Student Charges(Physical)</label>
                                <div class="form-control-wrap">
                                  <input type="number" class="form-control" name="physical_additional_charges" value="{{$extraStudentCharges->physical_additional_charges}}" class="form-control" id="charges" aria-describedby="emailHelp" placeholder="Enter Student Charges">
                                </div>
                              </div>
                           </div>
                           <div class="col-md-3">
                             <div class="form-group">
                                <label class="form-label" for="charges">Extra Tutor Charges(Online)</label>
                                <div class="form-control-wrap">
                                  <input type="number" class="form-control" name="tutor_online" value="{{$extraStudentCharges->tutor_online}}" class="form-control" id="charges" aria-describedby="emailHelp" placeholder="Enter Student Charges">
                                </div>
                              </div>
                           </div>
                           <div class="col-md-3">
                             <div class="form-group">
                                <label class="form-label" for="charges">Extra Tutor Charges(Physical)</label>
                                <div class="form-control-wrap">
                                  <input type="number" class="form-control" name="tutor_physical" value="{{$extraStudentCharges->tutor_physical}}" class="form-control" id="charges" aria-describedby="emailHelp" placeholder="Enter Student Charges">
                                </div>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <button type="submit" class="btn btn-primary">Update Extra Student Charges</button>
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
@endsection