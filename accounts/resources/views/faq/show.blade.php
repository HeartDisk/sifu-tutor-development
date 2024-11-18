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
                        View FAQ
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Mobile App FAQ List</a></li>
                           <li class="breadcrumb-item active" aria-current="page">View FAQ</li>
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
                           <div class="col-lg-12">
                              <div class="form-group">
                                 <label for="subject" class="form-label"><b>Question</b></label>
                                 <div class="form-control-wrap">
                                    {!! $faq->question !!}
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <label for="preheader" class="form-label"><b>Answer</b></label>
                                 {!! $faq->answer !!}
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