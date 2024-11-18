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
                        View Creditor Invoice
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Creditor Invoice</a></li>
                           <li class="breadcrumb-item active" aria-current="page">View Creditor Invoicee</li>
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
                        <form method="POST" action="{{route('submitCreditorInvoice')}}">
                           @csrf
                           <div class="row g-1 view-sindetails">
                              <div class="col-md-4 details-item">
                                 <p class="item-title">Occurance Date:</p>
                                 <p><strong> {{ \Carbon\Carbon::parse($data->OccuranceDate)->format('D, d M Y') }}</strong></p>
                              </div>
                              <div class="col-md-4 details-item">
                                 <p class="item-title">Creditor Name:</p>
                                 <p><strong> {{$data->creditorName}}</strong></p>
                              </div>
                              <div class="col-md-4 details-item">
                                 <p class="item-title">Description:</p>
                                 <p><strong> {{$data->description}}</strong></p>
                              </div>
                              <div class="col-md-4 details-item">
                                 <p class="item-title">Category:</p>
                                 <p><strong> {{$data->category}}</strong></p>
                              </div>
                              <div class="col-md-4 details-item">
                                 <p class="item-title">Quantity:</p>
                                 <p><strong> {{$data->quantity}}</strong></p>
                              </div>
                              <div class="col-md-4 details-item">
                                 <p class="item-title">Cost Price:</p>
                                 <p><strong> {{$data->costPrice}}</strong></p>
                              </div>
                              <div class="col-md-4 details-item">
                                 <p class="item-title">Payment Due Date:</p>
                                 <p><strong> {{ \Carbon\Carbon::parse($data->paymentDueDate)->format('D, d M Y') }}</strong></p>
                              </div>
                              <div class="col-md-4 details-item">
                                 <p class="item-title">Attachment:</p>
                                 <p><strong><a class="dview-status-viewfile" data-lightbox="image" href="{{url("/public/creditorInvoice")."/".$data->attachment}}" target="_blank">View File</a></strong></p>
                              </div>
                              <div class="col-md-4 details-item">
                                 <p class="item-title">Remarks:</p>
                                 <p><strong> {{$data->remarks}}</strong></p>
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
<script>
function myFunction() {

   var query = $('#user').val();
   if (query != '') {
      $.ajax({
         url: "{{ url('/addStudent/') }}" + "/" + query,
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