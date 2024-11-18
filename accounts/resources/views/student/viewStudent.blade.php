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
                        <div class="col-md-2 details-item">
                           <p class="item-title">Status</p>
                           @if($student->status == "inactive")
                           <p class="dtable-status-inactive">{{$student->status}}</p>
                           @elseif($student->status == "pending")
                           <p class="dtable-status-pending">{{$student->status}}</p>
                           @elseif($student->status == "active")
                           <p class="dtable-status-active">{{$student->status}}</p>
                           @endif
                        </div>
                        <div class="col-md-2 details-item">
                           <p class="item-title">Register Date</p>
                           <p><strong>{{$student->register_date}}</strong></p>
                        </div>
                        @if($student->status == "inactive")
                        <div class="col-md-2 details-item">
                           <p class="item-title">Reason Category</p>
                           <p><strong>{{$student->reasonCategory}}</strong></p>
                        </div>
                        <div class="col-md-2 details-item">
                           <p class="item-title">Reason</p>
                           <p><strong>{{$student->reasonStatus}}</strong></p>
                        </div>
                        @endif
                        <div class="col-md-2 details-item">
                           <p class="item-title">Fullname</p>
                           <p><strong>{{$student->full_name}}</strong></p>
                        </div>
                        <div class="col-md-2 details-item">
                           <p class="item-title">Gender</p>
                           <p><strong>{{$student->gender}}</strong></p>
                        </div>
                        <div class="col-md-2 details-item">
                           <p class="item-title">Age</p>
                           <p><strong>{{$student->age}}</strong></p>
                        </div>
                        <div class="col-md-2 details-item">
                           <p class="item-title">Year of Birth</p>
                           <p><strong>{{$student->dob}}</strong></p>
                        </div>
                        <div class="col-md-6 details-item">
                           <p class="item-title">Address</p>
                           <address>
                              <span><strong>{{$customer->address1}}</strong>,</span><br>
                              <span><strong>{{$customerCity->name}}</strong>,</span><br>
                              <span><strong>{{$customer->postal_code}}</strong>,</span><br>
                              <span><strong>{{$customerState->name}}</strong>,</span><br>
                           </address>
                        </div>
                        <div class="col-md-3 details-item">
                           <p class="item-title">Latitude</p>
                           <p><strong>{{$customer->latitude}}</strong></p>
                        </div>
                        <div class="col-md-3 details-item">
                           <p class="item-title">Longitude</p>
                           <p><strong>{{$customer->longitude}}</strong></p>
                        </div>
                     </div>
                     <h3>Contact Person</h3>
                     <div class="row g-1 view-sindetails">
                        <div class="col-md-3 details-item">
                           <p class="item-title">Fullname</p>
                           <p><strong>{{$customer->full_name}}</strong></p>
                        </div>
                        <div class="col-md-3 details-item">
                           <p class="item-title">Email</p>
                           <p><strong>{{$customer->email}}</strong></p>
                        </div>
                        <div class="col-md-2 details-item">
                           <p class="item-title">Phone No.</p>
                           <p><strong>{{$customer->phone}}</strong>
                              <a href="https://wa.me/{{$customer->whatsapp}}" target="_blank"><strong><span class="fa fa-whatsapp text-success"></span></strong></a>
                              <a href="tel:{{$customer->phone}}"><span class="fa fa-phone"></span></a>
                           </p>
                        </div>
                        <div class="col-md-2 details-item">
                           <p class="item-title">Gender</p>
                           <p><strong>{{$customer->gender}}</strong></p>
                        </div>
                        <div class="col-md-2 details-item">
                           <p class="item-title">Nric</p>
                           <p><strong>{{$customer->nric}}</strong></p>
                        </div>
                     </div>
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
                              $subject_name = DB::table('products')->where('id','=',$rowSubjectTwo->subject)->first()
                              @endphp
                              <tr id="R{{$loop->iteration}}">
                                 <td>
                                    <select class="form-control js-select" data-search="true" data-sort="false" name="subject[]" id="subject{{$loop->iteration}}">
                                    <option value="{{$subject_name->id}}">{{$subject_name->name}}</option>
                                    <select>
                                 </td>
                                 <td>
                                    <select class="form-control" id="day{{$loop->iteration}}" name="day[]">
                                    <option value="Monday">Monday</option>
                                    <option value="Tuesday">Tuesday</option>
                                    <option value="Wednesday">Wednesday</option>
                                    <option value="Thursday">Thursday</option>
                                    <option value="Friday">Friday</option>
                                    <option value="Saturday">Saturday</option>
                                    <option value="Sunday">Sunday</option>
                                    <select> 
                                 </td>
                                 <td><input class="form-control" type="time" value="22:00" name="time[]" id="time{{$loop->iteration}}"></td>
                                 <td>
                                    <select class="form-control" id="subscription{{$loop->iteration}}" name="subscription[]">
                                    <option value="LongTerm">Long Term</option>
                                    <option value="shortTerm">Short Term</option>
                                    <select>
                                 </td>
                              </tr>
                              @endforeach
                           </tbody>
                        </table>
                     </div>
                     <div class="row row-details pb-5">
                        <div class="col-md-2">
                           <a class="btn btn-primary" href="{{url("/Students")}}">Back</a>
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
<script>
function myFunction() {
   var query = $('#user').val();
   if (query != '') {
      $.ajax({
         url: "{{ url('/addStudent/') }}" + query,
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
         url: "{{ url('/getStudent/') }}" + selectedCustomer,
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