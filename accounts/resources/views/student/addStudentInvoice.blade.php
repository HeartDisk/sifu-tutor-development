@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
   <div class="fluid-container">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head">
               <div class="nk-block-head-between flex-wrap gap g-2">
                  <div class="nk-block-head-content">
                     <h2 class="nk-block-title">Add Student Invoice</h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Students Invoices</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Add Student Invoice</li>
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
                        <form method="POST" action="{{route('submitAddInvoice')}}">
                           @csrf
                           <div class="row">
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Reference Number</label>
                                    <div class="form-control-wrap"><input readonly type="text" name="reference" class="form-control" value="@php echo 'INV-'.date('dis'); @endphp" id="reference"></div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Invoice Date</label>
                                    <div class="form-control-wrap"><input type="date" name="invoiceDate" class="form-control" id="invoice_date"></div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Service</label>
                                    <div class="form-control-wrap">
                                       <select class="form-control" name="brand">
                                          <option>SifuTutor</option>
                                          <option>NakNgaji</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Payer Name</label>
                                    <div class="form-control-wrap"><input type="text" name="payerName" class="form-control" id="payerName"></div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Payer Email</label>
                                    <div class="form-control-wrap"><input type="text" name="payerEmail" class="form-control" id="payerEmail"></div>
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Payer Phone Number</label>
                                    <div class="form-control-wrap"><input type="text" name="payerPhoneNumber" class="form-control" id="payerPhoneNumber"></div>
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Remarks</label>
                                    <div class="form-control-wrap"><textarea name="remarks" class="form-control sifu-descr"></textarea></div>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <div class="nk-block-head">
                                    <div class="nk-block-head-between flex-wrap">
                                       <div class="nk-block-head-content">
                                          <h3 class="nk-block-title">INVOICE ITEMS</h3>
                                       </div>
                                       <div class="nk-block-head-content">
                                          <ul class="d-flex">
                                             <li><button class="btn btn-md btn-success" id="addBtn" type="button">Add Invoice Item</button></li>
                                          </ul>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="table-responsive">
                                    <table class="table">
                                       <thead>
                                          <tr>
                                             <th>Description</th>
                                             <th>Students</th>
                                             <th>Subjects</th>
                                             <th>Quantity</th>
                                             <th>Unit Price</th>
                                             <th></th>
                                          </tr>
                                       </thead>
                                       <tbody id="tbody">
                                       </tbody>
                                    </table>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <div class="nk-block-head">
                                    <div class="nk-block-head-between flex-wrap">
                                       <div class="nk-block-head-content">
                                          <h3 class="nk-block-title">DEDUCTIONS</h3>
                                       </div>
                                       <div class="nk-block-head-content">
                                          <ul class="d-flex">
                                             <li><button class="btn btn-md btn-success" id="addBtnDeduction" type="button">Add Deduction</button></li>
                                          </ul>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="table-responsive">
                                    <table class="table">
                                       <thead>
                                          <tr>
                                             <th>Description</th>
                                             <th>Amount</th>
                                             <th></th>
                                          </tr>
                                       </thead>
                                       <tbody id="tbodyDeduction">
                                       </tbody>
                                    </table>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <h3>CUSTOMER REMARK</h3>
                                 <div class="form-group">
                                    <div class="form-control-wrap">
                                      <textarea class="form-control valid" data-val="true" data-val-length="The field Customer Remark must be a string with a maximum length of 3500." data-val-length-max="3500" id="customerRemark" maxlength="3500" name="customerRemark" rows="6" spellcheck="false" aria-invalid="false" aria-describedby="CustomerRemark-error">1) This invoice is computer generated and no signature is required. 2) Payment is due within 3 working days of issuance of this invoice. 3) You can pay online via online banking by clicking the button PAY NOW or alternatively can transfer to account no below :MAYBANK - 562115516678    SIFU EDU &nbsp; LEARNING SDN BHD   </textarea>
                                    </div>
                                 </div>
                              </div>
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

<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script>
$(document).ready(function () {
  var rowIdx = 0;
  $('#addBtn').on('click', function () {
    $('#tbody').append(`<tr id="R${++rowIdx}">
    <td><textarea class="form-control sifu-descr" id="description${rowIdx}" name="description[]"></textarea></td>
    <td><select class="form-control js-select" data-search="true" data-sort="false" name="students[]" id="students${rowIdx}">@foreach($students as $studentsRow)<option value="{{$studentsRow->id}}"> {{$studentsRow->full_name}}</option>@endforeach<select></td>
    <td><select class="form-control js-select" onchange="myFunction()" data-search="true" data-sort="false" name="subject[]" id="subject${rowIdx}">@foreach($subjects as $subjectRow)<option value="{{$subjectRow->id}}"> {{$subjectRow->name}}</option>@endforeach<select></td>
    <td><input type="text" class="form-control" id="quantity${rowIdx}" name="quantity[]"/></td>
    <td><input type="text" class="form-control" id="unitPrice${rowIdx}" name="unitPrice[]"/></td>
    <td><button class="btn remove sifu-remove-btn" type="button">Remove</button></td>
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

$(document).ready(function () {
   var rowIdx = 0;
   $('#addBtnDeduction').on('click', function () {
      $('#tbodyDeduction').append(`<tr id="R${++rowIdx}">
      <td><textarea class="form-control sifu-descr" id="description${rowIdx}" name="deductionDescription[]"></textarea></td>
      <td><input type="text" class="form-control" id="deductionAmount{rowIdx}" name="deductionAmount[]"/></td>
      <td><button class="btn remove sifu-remove-btn" type="button">Remove</button></td>
       </tr>`);
   });
   $('#tbodyDeduction').on('click', '.remove', function () {
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
   var subjectID1 = $('#subject1').val();
   var subjectID2 = $('#subject2').val();
   var subjectID3 = $('#subject3').val();
   var subjectID4 = $('#subject4').val();
   var subjectID5 = $('#subject5').val();
   var subjectID6 = $('#subject6').val();
   var subjectID7 = $('#subject7').val();
   var subjectID8 = $('#subject8').val();
   if (subjectID1 != '') {
      $.ajax({
         url: "{{ url('/getSubjectById/') }}/" + subjectID1,
         method: "GET",
         dataType: 'json',
         success: function (data) {
            $('#unitPrice1').val(data.subjects.price);
         }
      });
   }
   if (subjectID2 != '') {
      $.ajax({
         url: "{{ url('/getSubjectById/') }}/" + subjectID2,
         method: "GET",
         dataType: 'json',
         success: function (data) {
            $('#unitPrice2').val(data.subjects.price);
         }
      });
   }
   if (subjectID3 != '') {
      $.ajax({
         url: "{{ url('/getSubjectById/') }}/" + subjectID3,
         method: "GET",
         dataType: 'json',
         success: function (data) {
            $('#unitPrice3').val(data.subjects.price);
         }
      });
   }
   if (subjectID4 != '') {
      $.ajax({
         url: "{{ url('/getSubjectById/') }}/" + subjectID4,
         method: "GET",
         dataType: 'json',
         success: function (data) {
            $('#unitPrice4').val(data.subjects.price);
         }
      });
   }
   if (subjectID5 != '') {
      $.ajax({
         url: "{{ url('/getSubjectById/') }}/" + subjectID5,
         method: "GET",
         dataType: 'json',
         success: function (data) {
            $('#unitPrice5').val(data.subjects.price);
         }
      });
   }
   if (subjectID6 != '') {
      $.ajax({
         url: "{{ url('/getSubjectById/') }}/" + subjectID6,
         method: "GET",
         dataType: 'json',
         success: function (data) {
            $('#unitPrice6').val(data.subjects.price);
         }
      });
   }
   if (subjectID7 != '') {
      $.ajax({
         url: "{{ url('/getSubjectById/') }}/" + subjectID7,
         method: "GET",
         dataType: 'json',
         success: function (data) {
            $('#unitPrice7').val(data.subjects.price);
         }
      });
   }
   if (subjectID8 != '') {
      $.ajax({
         url: "{{ url('/getSubjectById/') }}/" + subjectID8,
         method: "GET",
         dataType: 'json',
         success: function (data) {
            $('#unitPrice8').val(data.subjects.price);
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
   $("select#subject1").change(function () {
      var selectedSubjectByID = $(this).children("option:selected").val();
      CONSOLE.LOG(selectedSubjectByID);
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