@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
   <div class="fluid-container">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head">
               <div class="nk-block-head-between flex-wrap gap g-2">
                  <div class="nk-block-head-content">
                     <h2 class="nk-block-title">Add Creditor Invoice</h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Creditor Invoices</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Add Creditor Invoice</li>
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
                        <form method="POST" action="{{route('submitCreditorInvoice')}}" enctype="multipart/form-data">
                           @csrf
                           <div class="row g-3">
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Occurance Date</label>
                                    <div class="form-control-wrap"><input type="date" name="occuranceDate" class="form-control" id="occuranceDate"></div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Creditor Name</label>
                                    <div class="form-control-wrap"><input type="text" name="creditorName" class="form-control" id="creditorName"></div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Description</label>
                                    <div class="form-control-wrap"><input type="text" name="description" class="form-control" id="description"></div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Category</label>
                                    <div class="form-control-wrap"><input type="text" name="category" class="form-control" id="category"></div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Quantity</label>
                                    <div class="form-control-wrap"><input type="text" name="quantity" class="form-control" id="quantity"></div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Cost Price</label>
                                    <div class="form-control-wrap"><input type="text" name="costPrice" class="form-control" id="costPrice"></div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Payment Due Date</label>
                                    <div class="form-control-wrap"><input type="date" name="paymentDueDate" class="form-control" id="paymentDueDate"></div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Attachment</label>
                                    <div class="form-control-wrap"><input type="file" name="attachmentFile" class="form-control" id="attachment"></div>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Remarks</label>
                                    <div class="form-control-wrap"><textarea name="remarks" class="form-control"></textarea></div>
                                 </div>
                              </div>
                              <div class="row g-3">
                                 <div class="col-lg-2">
                                    <button class="btn btn-primary" type="submit">Submit</button>
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
<script>
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