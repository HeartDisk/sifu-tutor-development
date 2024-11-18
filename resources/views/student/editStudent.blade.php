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
                        Edit Student
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Student List</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Edit Student</li>
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
                        <form method="POST" action="{{route('submitEditStudent')}}">
                           @csrf
                           <input type="hidden" name="id" value="{{$student->id}}"/>
                           <input type="hidden" name="customer_id" value="{{$customer->id}}"/>
                           <div class="row g-3">
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Registration Date</label>
                                    <div class="form-control-wrap"><input type="date" name="registration_date" value="{{$student->register_date}}" class="form-control" id="registrationDate"></div>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Admin in Charge</label>
                                    <select id="staff_id" name="staff_id" data-search="true" data-sort="false" required>
                                       <option value="">SelectAdmin in Charge</option>
                                       @foreach($staffs as $staff)
                                       <option {{$student->staff_id==$staff->id?"selected":""}} value="{{$staff->id}}">{{$staff->full_name}}</option>
                                       @endforeach
                                    </select>
                                 </div>
                              </div>
                              <div style="display:block;" class="customerInfo">
                              <div class="row">
                                 <div class="col-lg-12">
                                    <h3>CUSTOMER / PARENT INFORMATION</h3>
                                 </div>
                                 <div class="col-lg-2">
                                    <div class="form-group">
                                       <label for="firstname" class="form-label">Full Name</label>
                                       <p><strong>{{$customer->full_name}}</strong></p>
                                    
                                    </div>
                                 </div>
                                 <div class="col-lg-2">
                                    <div class="form-group">
                                       <label for="firstname" class="form-label">Gender</label>
                                       <p><strong>{{$customer->full_name}}</strong></p>
                                      
                                    </div>
                                 </div>
                                 <div class="col-lg-2">
                                    <div class="form-group">
                                       <label for="email" class="form-label">Email address</label>
                                       <p><strong>{{$customer->full_name}}</strong></p>
                                    
                                    </div>
                                 </div>
                                 <div class="col-lg-2">
                                    <div class="form-group">
                                       <label for="address" class="form-label">NRIC</label>
                                       <p><strong>{{$customer->full_name}}</strong></p>
                                    
                                    </div>
                                 </div>
                                 <div class="col-lg-2">
                                    <div class="form-group">
                                       <label for="address" class="form-label">Phone</label>
                                       <p><strong>{{$customer->full_name}}</strong></p>
                                    
                                    </div>
                                 </div>
                                 <div class="col-lg-2">
                                    <div class="form-group">
                                       <label for="address" class="form-label">Whats App Number</label>
                                       <p><strong>{{$customer->full_name}}</strong></p>
                                    
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-lg-12">
                                    <h3>CUSTOMER ADDRESS</h3>
                                 </div>
                                 <div class="col-lg-8">
                                    <div class="form-group">
                                       <label for="city" class="form-label">Full Address</label>
                                       <p><strong>{{$customer->address1}}</strong></p>
                                      </div>
                                 </div>
                                 <div class="col-lg-4">
                                    <div class="form-group">
                                       <label for="city" class="form-label">City</label>
                                       <p><strong>{{$customerCity->name}}</strong></p>
                                       
                                    </div>
                                 </div>
                                 <div class="col-lg-3">
                                    <div class="form-group">
                                       <label for="city" class="form-label">State</label>
                                       <p><strong>{{$customerState->name}}</strong></p>
                                       
                                    </div>
                                 </div>
                                 <div class="col-lg-3">
                                    <div class="form-group">
                                       <label for="postalcode" class="form-label">Postal Code</label>
                                       <p><strong>{{$customer->postal_code}}</strong></p>
                                    
                                    </div>
                                 </div>
                                 <div class="col-lg-3">
                                    <div class="form-group">
                                       <label for="city" class="form-label">Latitude</label>
                                       <p><strong>{{$customer->latitude}}</strong></p>
                                    
                                    </div>
                                 </div>
                                 <div class="col-lg-3">
                                    <div class="form-group">
                                       <label for="city" class="form-label">Longitude</label>
                                       <p><strong>{{$customer->longitude}}</strong></p>
                                   
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-lg-12">
                                    <h3>STUDENT INFORMATION</h3>
                                 </div>
                                 <div class="col-lg-3">
                                    <div class="form-group">
                                       <label for="firstname" >Full Name</label>
                                       <div class="form-control-wrap"><input type="text" class="form-control" id="firstname" value="{{$student->full_name}}" name="full_name" placeholder="Full Name"></div>
                                    </div>
                                 </div>
                                 <div class="col-lg-3">
                                    <div class="form-group">
                                       <label for="firstname" >Gender</label>
                                       <div class="form-control-wrap">
                                          <select class="js-select form-control" data-search="true" name="gender" data-sort="false">
                                          <option {{$student->gender=="Male"?"selected":""}} value="Male">Male</option>
                                          <option {{$student->gender=="Female"?"selected":""}} value="Female">Female</option>
                                          </select>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-3">
                                    <div class="form-group">
                                       <label for="email" >Age</label>
                                       <div class="form-control-wrap"><input type="text" class="form-control" value="{{$student->age}}" name="age" placeholder="Age"></div>
                                    </div>
                                 </div>
                                 <div class="col-lg-3">
                                    <div class="form-group">
                                       <label for="company" >Birth Year</label>
                                       <div class="form-control-wrap"><input type="date" class="form-control" name="dob" value="{{$student->dob}}" placeholder="Date of Birth"></div>
                                    </div>
                                 </div>
                                 
                                 
                                  <div class="col-lg-3 newStudent">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Special Need</label>
                                    <div class="form-control-wrap">
                                       <select id="specialNeed" name="specialNeed">
                                          <option value="">Select Special Need</option>
                                          <option {{$student->specialNeed=="None"?"selected":""}}  value="None">None</option>
                                          <option {{$student->specialNeed=="Dyslexia"?"selected":""}}  value="Dyslexia">Dyslexia</option>
                                          <option {{$student->specialNeed=="Slow Learner"?"selected":""}} value="Slow Learner">Slow Learner</option>
                                          <option {{$student->specialNeed=="Autism"?"selected":""}} value="Autism">Autism</option>
                                          <option {{$student->specialNeed=="Down Syndrome"?"selected":""}} value="Down Syndrome">Down Syndrome</option>
                                          <option {{$student->specialNeed=="OKU"?"selected":""}} value="OKU">OKU</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                                 
                              <div class="row">
                                 <div class="col-lg-12">
                                    <h3>SUBJECT SUBSCRIBES</h3>
                                 </div>
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
                                          $subject_name = DB::table('products')->where('id','=',$rowSubjectTwo->subject)->first()
                                          @endphp
                                          <input  type="hidden" name="student_subject_id[]" value="{{$rowSubjectTwo->id}}">
                                          <tr id="R{{$loop->iteration}}">
                                             <td>
                                                 <p>{{$subject_name->name}}</p>
                                                <!--<select class="form-control js-select" data-search="true" data-sort="false" name="subject[]" id="subject{{$loop->iteration}}">-->
                                                <!--<option value="{{$subject_name->id}}">{{$subject_name->name}}</option>-->
                                                <!--<select>-->
                                             </td>
                                             <td>
                                                 <p>{{$rowSubjectTwo->day}}</p>
                                                <!--<select class="form-control" id="day{{$loop->iteration}}" name="day[]">-->
                                                <!--<option value="Monday">Monday</option>-->
                                                <!--<option value="Tuesday">Tuesday</option>-->
                                                <!--<option value="Wednesday">Wednesday</option>-->
                                                <!--<option value="Thursday">Thursday</option>-->
                                                <!--<option value="Friday">Friday</option>-->
                                                <!--<option value="Saturday">Saturday</option>-->
                                                <!--<option value="Sunday">Sunday</option>-->
                                                <!--<select> -->
                                             </td>
                                             <td>
                                                 <p>{{$rowSubjectTwo->time}}</p>
                                                 <!--<input class="form-control" type="time" value="22:00" name="time[]" id="time{{$loop->iteration}}">-->
                                                 </td>
                                             <td>
                                                 <p>{{ucfirst($rowSubjectTwo->subscription)}}</p>
                                                <!--<select class="form-control" id="subscription{{$loop->iteration}}" name="subscription[]">-->
                                                <!--<option {{$rowSubjectTwo->subscription=="LongTerm"?"selected":""}} value="LongTerm">Long Term</option>-->
                                                <!--<option {{$rowSubjectTwo->subscription=="shortTerm"?"selected":""}} value="shortTerm">Short Term</option>-->
                                                <!--<select>-->
                                             </td>
                                          </tr>
                                          @endforeach
                                       </tbody>
                                    </table>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-lg-3">
                                    <button class="btn btn-primary" type="submit">Update Student</button>
                                 </div>
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
   </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script>
 $(document).ready(function () {
   var rowIdx = {{$totalSubjects}};
   $('#addBtn').on('click', function () {
     $('#tbody').append(`<tr id="R${++rowIdx}">
         <td class="row-index text-center"><select class="form-control js-select" data-search="true" data-sort="false" name="subject[]" id="subject${rowIdx}">@foreach($allSubjects as $subjectRowOne)<option value="{{$subjectRowOne->id}}"> {{$subjectRowOne->name}}</option>@endforeach<select></td>
         <td class="row-index text-center"><select class="form-control" id="day${rowIdx}" name="day[]"><option value="Monday">Monday</option><option value="Tuesday">Tuesday</option><option value="Wednesday">Wednesday</option><option value="Thursday">Thursday</option><option value="Friday">Friday</option><option value="Saturday">Saturday</option><option value="Sunday">Sunday</option><select> </td>
         <td class="row-index text-center"><input class="form-control" type="time" value="22:00" name="time[]" id="time{rowIdx}"></td>
         <td class="row-index text-center"><select class="form-control" id="subscription${rowIdx}" name="subscription[]"><option value="LongTerm">Long Term</option><option value="shortTerm">Short Term</option><select></td>
           <td class="text-center">
             <button style="background-color:#2e314a; color:#fff" class="btn remove"
               type="button">Remove</button>
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
                                      
function myFunction() {
   var query = $('#user').val();
   if (query != '') {
      $.ajax({
         url: "{{ url('/addStudent/') }}/" + query,
         method: "GET",
         dataType: 'json',
         success: function (data) {
            console.log(data);
         }
      });
   }
}
$(document).ready(function () {
   $(document).on('click', 'li', function () {
      $('#user').val($(this).text());
      $('#userList').fadeOut();
   });

   $("select#customer_id").change(function () {
      var selectedCustomer = $(this).children("option:selected").val();
      if (selectedCustomer == '000123') {
         $('.customerInfo').show();
         $('.existingStudentInfo').hide();
      } else {
         $('.customerInfo').hide();
         $('.existingStudentInfo').show();
      }

      var userURL = $(this).data('url');
      $.ajax({
         url: "{{ url('/getStudent/') }}/" + selectedCustomer,
         type: 'GET',
         dataType: 'json',
         success: function (data) {
            console.log(data);
            $('.customerId').text(data.customer.uid);
            $('.customerFullName').text(data.customer.full_name);
            $('.customerEmail').text(data.customer.email);
            $('.customerGender').text(data.customer.gender);
            $('.customerPhone').text(data.customer.phone);
            $('.customerAddress').text(data.customer.address1);

            $('.studentRegisterDate').text(data.student.register_date);
            $('.studentId').text(data.student.uid);
            $('.studentFullName').text(data.student.full_name);
            $('.studentGender').text(data.student.gender);
            $('.studentEmail').text(data.student.email);
            $('.studentPhone').text(data.student.phone);

            var json = JSON.stringify(data.subjects);
            $('#subject1').html('1.<br/> <strong>Subject :</strong><span class="text">' + data.subjects[0]['subject'] + '</span></br>  <strong>Day :</strong> <span class="text">' + data.subjects[0]['day'] + '</span></br> <strong>Time :</strong> <span class="text">' + data.subjects[0]['time'] + '</span></br> <strong>Tutor :</strong>' + ' <u>' + data.subjects[0]['newstatus'] + ' </u>');
            $('#subject2').html('2.<br/> <strong>Subject :</strong><span class="text">' + data.subjects[1]['subject'] + '</span></br> <strong>Day :</strong> <span class="text">' + data.subjects[1]['day'] + '</span></br> <strong>Time :</strong> <span class="text">' + data.subjects[1]['time'] + '</span></br> <strong>Tutor :</strong>' + ' <u>' + data.subjects[1]['newstatus'] + ' </u>');
            $('#subject3').html('3.<br/> <strong>Subject :</strong><span class="text">' + data.subjects[2]['subject'] + '</span></br> <strong>Day :</strong> <span class="text">' + data.subjects[2]['day'] + '</span></br> <strong>Time :</strong> <span class="text">' + data.subjects[2]['time'] + '</span></br> <strong>Tutor :</strong>' + ' <u>' + data.subjects[2]['newstatus'] + ' </u>');
            $('#subject4').html('4.<br/> <strong>Subject :</strong><span class="text">' + data.subjects[2]['subject'] + '</span></br> <strong>Day :</strong> <span class="text">' + data.subjects[3]['day'] + '</span></br> <strong>Time :</strong> <span class="text">' + data.subjects[3]['time'] + '</span></br> <strong>Tutor :</strong>' + ' <u>' + data.subjects[3]['newstatus'] + ' </u>');
            $('#subject12').html('1.<br/> <strong>Subject :</strong><span class="text">' + data.subjects[0]['subject'] + '</span></br>  <strong>Day :</strong> <span class="text">' + data.subjects[0]['day'] + '</span></br> <strong>Time :</strong> <span class="text">' + data.subjects[0]['time'] + '</span></br> <strong>Tutor :</strong>' + ' <u>' + data.subjects[0]['newstatus'] + '  </u>');
            $('#subject22').html('2.<br/> <strong>Subject :</strong><span class="text">' + data.subjects[1]['subject'] + '</span></br> <strong>Day :</strong> <span class="text">' + data.subjects[1]['day'] + '</span></br> <strong>Time :</strong> <span class="text">' + data.subjects[1]['time'] + '</span></br> <strong>Tutor :</strong>' + ' <u>' + data.subjects[1]['newstatus'] + '  </u>');
            $('#subject32').html('3.<br/> <strong>Subject :</strong><span class="text">' + data.subjects[2]['subject'] + '</span></br> <strong>Day :</strong> <span class="text">' + data.subjects[2]['day'] + '</span></br> <strong>Time :</strong> <span class="text">' + data.subjects[2]['time'] + '</span></br> <strong>Tutor :</strong>' + ' <u>' + data.subjects[2]['newstatus'] + '  </u>');
            $('#subject33').html('4.<br/> <strong>Subject :</strong><span class="text">' + data.subjects[2]['subject'] + '</span></br> <strong>Day :</strong> <span class="text">' + data.subjects[3]['day'] + '</span></br> <strong>Time :</strong> <span class="text">' + data.subjects[3]['time'] + '</span></br> <strong>Tutor :</strong>' + ' <u>' + data.subjects[3]['newstatus'] + '  </u>');
         }
      });
   });
});
</script>
@endsection