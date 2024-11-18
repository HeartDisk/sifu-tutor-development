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
                        Banner Ads List
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Mobile App</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Banner Ads List</li>
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
                     <form method="post" enctype="multipart/form-data" action="{{route('submitBannerAds')}}" novalidate="novalidate">
                        @csrf
                        <div class="row">
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <label class="form-label " for="Title">Title</label>
                                 <input class="form-control text-box single-line" id="Title" name="Title" type="text" value="">
                                 <span class="field-validation-valid text-danger" data-valmsg-for="Title" data-valmsg-replace="true"></span>
                              </div>
                           </div>
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <label class="form-label " for="BannerImage">Banner Image</label>
                                 <input accept="image/*" class="form-control input-file text-box single-line" data-val="true" data-val-required="The Banner Image field is required." id="BannerImage" name="BannerImage" type="file">
                                 <small><i>Recommended size: 780px x 1280px</i></small> <br>
                                 <small><i>Supported extensions: jpg,jpeg.png. Max Size: 10MB </i></small>
                                 <span class="field-validation-valid text-danger" data-valmsg-for="BannerImage" data-valmsg-replace="true"></span>
                              </div>
                           </div>
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <label class="form-label " for="DisplayOnPage">Display On Page</label>
                                 <select class="form-control" data-val="true" data-val-length="The field Display On Page must be a string with a maximum length of 50." data-val-length-max="50" data-val-required="The Display On Page field is required." id="DisplayOnPage" name="DisplayOnPage" style="width: 100%;">
                                    <option>Dashboard</option>
                                    <option>Profile</option>
                                    <option>Cumulative Commission</option>
                                    <option>Payment History</option>
                                    <option>Inbox</option>
                                    <option>Job Ticket List</option>
                                    <option>Class Schedule List</option>
                                    <option>Submission History</option>
                                    <option>Student List</option>
                                    <option>Pending Actions</option>
                                    <option>Faq</option>
                                 </select>
                                 <span class="field-validation-valid text-danger" data-valmsg-for="DisplayOnPage" data-valmsg-replace="true"></span>
                              </div>
                           </div>
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <label class="form-label " for="TutorStatusCriteria">Tutor Status Criteria</label>
                                 <select class="form-control" data-val="true" data-val-length="The field Tutor Status Criteria must be a string with a maximum length of 50." data-val-length-max="50" data-val-required="The Tutor Status Criteria field is required." id="TutorStatusCriteria" name="TutorStatusCriteria">
                                    <option>All</option>
                                    <option>Verified</option>
                                    <option>Unverified</option>
                                 </select>
                                 <span class="field-validation-valid text-danger" data-valmsg-for="TutorStatusCriteria" data-valmsg-replace="true"></span>
                              </div>
                           </div>
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <label class="form-label " for="CallToActionType">Call To Action Type</label>
                                 <select class="form-control" data-val="true" data-val-length="The field Call To Action Type must be a string with a maximum length of 50." data-val-length-max="50" data-val-required="The Call To Action Type field is required." id="CallToActionType" name="CallToActionType">
                                    <option>None</option>
                                    <option>Open URL</option>
                                    <option>Open Page</option>
                                 </select>
                                 <span class="field-validation-valid text-danger" data-valmsg-for="CallToActionType" data-valmsg-replace="true"></span>
                              </div>
                           </div>
                           <div class="col-lg-4" style="display:none">
                              <div class="form-group">
                                 <div id="UrlToOpen">
                                    <label class="form-label " for="UrlToOpen">Url To Open</label>
                                    <input class="form-control text-box single-line" data-val="true" data-val-length="The field Url To Open must be a string with a maximum length of 500." data-val-length-max="500" id="UrlToOpen" maxlength="500" name="UrlToOpen" type="text" value="">
                                    <span class="field-validation-valid text-danger" data-valmsg-for="UrlToOpen" data-valmsg-replace="true"></span>
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-4" style="display:none">
                              <div class="form-group">
                                 <div id="PageToOpen">
                                    <label class="form-label " for=" PageToOpen">Page To Open</label>
                                    <select class="form-control" data-val="true" data-val-length="The field Page To Open must be a string with a maximum length of 150." data-val-length-max="150" id="PageToOpen" name="PageToOpen" style="width: 100%;">
                                       <option>Dashboard</option>
                                       <option>Profile</option>
                                       <option>Cumulative Commission</option>
                                       <option>Payment History</option>
                                       <option>Inbox</option>
                                       <option>Job Ticket List</option>
                                       <option>Class Schedule List</option>
                                       <option>Submission History</option>
                                       <option>Student List</option>
                                       <option>Pending Actions</option>
                                       <option>Faq</option>
                                    </select>
                                    <span class="field-validation-valid text-danger" data-valmsg-for="PageToOpen" data-valmsg-replace="true"></span>
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-4 mt-5">
                              <div class="form-group">
                                 <input class="form-check-input" type="checkbox" id="DisplayOnce" name="DisplayOnce">
                                 <label class="form-label form-check-label" for="DisplayOnce">Display Once?</label>
                              </div>
                           </div>
                           <div class="col-lg-2">
                              <div class="form-group">
                                 <button class="btn btn-primary waves-effect waves-light" type="submit">Submit</button>
                              </div>
                           </div>
                        </div>
                        <input name="__RequestVerificationToken" type="hidden" value="CfDJ8OymwcO564JNjRWfEqtZSIQ0PEt2yEQ7r1_VWS_XAg0euri83jO7XqTFZGZB5mpxd4XqtzZ-Plfv5TrVGn06Tck7B4UktSCuUHr2_EvKk5QSuBL9yp5i5MGgtkZV3ubtDfEvcxKrXqm6NKXaFdQLhwToUaCr5YuGenEBoOCBvtF8R2nK1Fx2dld-fL48quisOQ">
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
   $(document).ready(function(){
       $('#CallToActionType').on('change', function() {
         if(this.value == 'Open URL'){
             $('#UrlToOpen').show();
             $('#PageToOpen').hide();
         }else if(this.value == 'Open Page'){
           $('#UrlToOpen').hide();
           $('#PageToOpen').show();
         }else{
             $('#UrlToOpen').hide();
           $('#PageToOpen').hide();
         }
       });
   });
</script>
@endsection