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
                        View Sale Invoice: {{$saleInvoice->referenceNumber}}
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Sale Invoice</a></li>
                           <li class="breadcrumb-item active" aria-current="page">View Sale Invoice</li>
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
                        @csrf
                        <div class="row g-1 view-sindetails">
                           <div class="col-md-4 details-item">
                              <p class="item-title">Invoice Date:</p>
                              <p><strong>{{$saleInvoice->invoiceDate}}</strong></p>
                           </div>
                           <div class="col-md-4 details-item">
                              <p class="item-title">Reference Number:</p>
                              <p><strong>{{$saleInvoice->referenceNumber}}</strong></p>
                           </div>
                           <div class="col-md-4 details-item">
                              <p class="item-title">Management Status:</p>
                              <p><strong>{{$saleInvoice->managementStatus}}</strong></p>
                           </div>
                           <div class="col-md-3 details-item">
                              <p class="item-title">Payer Name:</p>
                              <p><strong>{{$saleInvoice->payerName}}</strong></p>
                           </div>
                           <div class="col-md-3 details-item">
                              <p class="item-title">Payer Email:</p>
                              <p><strong>{{$saleInvoice->payerEmail}}</strong></p>
                           </div>
                           <div class="col-md-3 details-item">
                              <p class="item-title">Payer Phone Number:</p>
                              <p><strong>{{$saleInvoice->payerPhone}}</strong></p>
                           </div>
                           <div class="col-md-3 details-item">
                              <p class="item-title">Remarks:</p>
                              <p><strong>{{$saleInvoice->remarks}}</strong></p>
                           </div>
                        </div>
                        <h3>INVOICE ITEMS</h3>
                        <div class="table-responsive">
                           <table class="table">
                              <thead>
                                 <tr>
                                    <td>Description</td>
                                    <td>Student</td>
                                    <td>Subject</td>
                                    <td>Quantity</td>
                                    <td>Unit Price</td>
                                 </tr>
                              </thead>
                              <tbody>
                                 @foreach($saleInvoiceItems as $item)
                                 <tr>
                                    <td>{{$item->description}}</td>
                                    <td>{{$item->student}}</td>
                                    <td>{{$item->subject}}</td>
                                    <td>{{$item->quantity}}</td>
                                    <td>{{$item->unitPrice}}</td>
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