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
                                    <div class="form-control-wrap"><input type="date" required name="invoiceDate" class="form-control" id="invoice_date"></div>
                                 </div>
                              </div>
                               <input type="hidden" name="brand" value="SifuTutor">
{{--                              <div class="col-lg-3">--}}
{{--                                 <div class="form-group">--}}
{{--                                    <label for="firstname" class="form-label">Service</label>--}}
{{--                                    <div class="form-control-wrap">--}}
{{--                                       <select class="form-control" name="brand">--}}
{{--                                          <option>SifuTutor</option>--}}
{{--                                          <option>NakNgaji</option>--}}
{{--                                       </select>--}}
{{--                                    </div>--}}
{{--                                 </div>--}}
{{--                              </div>--}}
                              <div class="col-lg-3">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Payer Name</label>
{{--                                    <div class="form-control-wrap"><input type="text" name="payerName" class="form-control" id="customer_id"></div>--}}
                                    <select id="customer_id" required class="form-control">
                                        <option value="">Please select customer</option>
                                        @foreach($customers as  $customer)
                                        <option value="{{$customer->id}}">{{$customer->full_name}}</option>
                                            @endforeach
                                    </select>
                                     <input type="hidden" name="payerName" id="payerName">
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

        let selectedCustomer = $('#customer_id').val();
        // alert(selectedCustomer);
        $.ajax({
            url: "{{ url('/getParentDetails/') }}/" + selectedCustomer,
            type: 'GET',
            dataType: 'json',
            success: function (data) {

                console.log(data.students);
                // Clear previous student options
                // $("select[name='students[]']").empty();

                let studentOptions = '';
                data.students.forEach(function(student) {
                    studentOptions += `<option value="${student.id}">${student.full_name}</option>`;
                });

                $('#tbody').append(`<tr id="R${++rowIdx}">
                <td><textarea class="form-control sifu-descr" id="description${rowIdx}" name="description[]"></textarea></td>
                <td><select class="form-control js-select" data-search="true" data-sort="false" name="students[]" id="students${rowIdx}">${studentOptions}<select></td>
                <td><select class="form-control js-select" onchange="myFunction()" data-search="true" data-sort="false" name="subject[]" id="subject${rowIdx}">@foreach($subjects as $subjectRow)<option value="{{$subjectRow->id}}"> {{$subjectRow->name."(".$subjectRow->category.")"}}</option>@endforeach<select></td>
                <td><input type="text" class="form-control" id="quantity${rowIdx}" name="quantity[]"/></td>
                <td><input type="text" class="form-control" id="unitPrice${rowIdx}" name="unitPrice[]"/></td>
                <td><button class="btn remove sifu-remove-btn" type="button">Remove</button></td>
            </tr>`);
            }
        });

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

$(document).ready(function () {
   $("select#customer_id").change(function () {
      var selectedCustomer = $(this).children("option:selected").val();
      var userURL = $(this).data('url');
      $.ajax({
         url: "{{ url('/getParentDetails/') }}/" + selectedCustomer,
         type: 'GET',
         dataType: 'json',
         success: function (data) {

             $("#payerEmail").val(data.parent_data.email);
             $("#payerPhoneNumber").val(data.parent_data.phone);
             $("#payerName").val(data.parent_data.full_name);


             // Clear previous student options
             $("select[name='students[]']").empty();

             // Append new student options
             $.each(data.students, function (index, student) {
                 $("select[name='students[]']").append(
                     `<option value="${student.id}">${student.full_name}</option>`
                 );
             });

         }
      });
   });
});
</script>
@endsection
