@extends('layouts.main')
@section('content')
<div class="nk-content">
   <div class="fluid-container">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head">
               <div class="nk-block-head-between flex-wrap gap g-2">
                  <div class="nk-block-head-content">
                     <h2 class="nk-block-title">
                        View Policy
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Mobile App Policies List</a></li>
                           <li class="breadcrumb-item active" aria-current="page">View Policy</li>
                        </ol>
                     </nav>
                  </div>
               </div>
            </div>
            <div class="nk-block">
               <div class="card card-gutter-md">
                  <div class="card-body">
                     <div class="bio-block">
                        <div class="row g-3">
                           <!-- Title Section -->
                           <div class="col-lg-12">
                              <div class="form-group">
                                 <label for="title" class="form-label"><b>User Type</b></label>
                                 <div class="form-control-wrap">
                                    {{ $policy->user_role }} <!-- Assuming $policy->title holds the title of the policy -->
                                 </div>
                              </div>
                           </div>
                           <!-- Content Section -->
                           <div class="col-lg-12">
                              <div class="form-group">
                                 <label for="content" class="form-label"><b>Content</b></label>
                                 <div class="form-control-wrap">
                                    {!! $policy->content !!} <!-- Display the policy content -->
                                 </div>
                              </div>
                           </div>
                           <!-- Type Section -->
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <label for="type" class="form-label"><b>Type</b></label>
                                 <div class="form-control-wrap">
                                    {{ $policy->policy_type }} <!-- Assuming $policy->policy_type holds the type of the policy -->
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
</div>
@endsection
