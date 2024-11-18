@extends('layouts.main')

@section('content')

<style>
    .card{
        box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;
    }
</style>


        <div class="nk-content">
            <div class="fluid-container">
              <div class="nk-content-inner">
                <div class="nk-content-body">
                  <div class="nk-block-head">
                    <div class="nk-block-head">
                      <div class="nk-block-head-between flex-wrap gap g-2 align-items-center">
                        <div class="nk-block-head-content">
                          <div class="d-flex flex-column flex-md-row align-items-md-center">
                            <div class="mt-3 mt-md-0 ms-md-3">
                                <br/><br/>
                              <h3 class="title mb-1">Edit Sale Invoice : {{$saleInvoice->referenceNumber}}</h3>
                            </div>
                          </div>
                        </div>
                        
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
                         <form method="POST" action="{{ route('updateSaleInvoice') }}">
                            @csrf  <!-- CSRF protection ke liye -->
                            <input type="hidden" name="saleInvoiceID" value="{{ $saleInvoice->id }}">
                            <div class="row g-3">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="firstname" class="form-label">Invoice Date</label>
                                    <div class="form-control-wrap">
                                        <input type="date" name="invoiceDate" value="{{ \Carbon\Carbon::parse($saleInvoice->invoiceDate)->format('Y-m-d') }}" class="form-control" id="invoice_date">
                                    </div>
                                </div>
                            </div>
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="firstname" class="form-label">Reference Number</label>
                                  <div class="form-control-wrap"><input readonly type="text" name="reference" class="form-control" value="{{$saleInvoice->referenceNumber}}" id="reference"></div>
                                </div>
                              </div>
                              
                              <!--<div class="col-lg-4">-->
                              <!--  <div class="form-group">-->
                              <!--    <label for="firstname" class="form-label">Management Status</label>-->
                              <!--    <div class="form-control-wrap">-->
                              <!--          <select class="form-control" name="managementStatus">-->
                              <!--          <option selected="selected">{{$saleInvoice->managementStatus}}</option>-->
                              <!--            <option>Normal</option>-->
                              <!--            <option>Early-Month</option>-->
                              <!--            <option>Mid-Month</option>-->
                              <!--            <option>End-Month</option>-->
                              <!--            <option>Hostel</option>-->
                              <!--          </select>-->
                              <!--        </div>-->
                              <!--  </div>-->
                              <!--</div>-->

                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="firstname" class="form-label">Payer Name</label>
                                  <div class="form-control-wrap"><input type="text" name="payerName" class="form-control" id="payerName" value="{{$saleInvoice->payerName}}" ></div>
                                </div>
                            </div>
                            
                            <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="firstname" class="form-label">Payer Email</label>
                                  <div class="form-control-wrap"><input type="text" name="payerEmail" class="form-control" id="payerEmail" value="{{$saleInvoice->payerEmail}}" ></div>
                                </div>
                            </div>
                            
                            <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="firstname" class="form-label">Payer Phone Number</label>
                                  <div class="form-control-wrap"><input type="text" name="payerPhoneNumber" class="form-control" id="payerPhoneNumber" value="{{$saleInvoice->payerPhone}}" ></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                  <label for="firstname" class="form-label">Remarks</label>
                                  <div class="form-control-wrap"><textarea name="remarks" class="form-control" style="min-height:30px;max-height:45px">{{$saleInvoice->remarks}}</textarea></div>
                                </div>
                            </div>

                            
                             <div class="row g-3">
                                <h3>INVOICE ITEMS</h3>
                                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
                                      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
                                      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
                                      <script>
                                        $(document).ready(function () {
                                      
                                          // Denotes total number of rows
                                          var rowIdx = 0;
                                      
                                          // jQuery button click event to add a row
                                          $('#addBtn').on('click', function () {
                                      
                                            // Adding a row inside the tbody.
                                            $('#tbody').append(`<tr id="R${++rowIdx}">
                                                <td class="row-index text-center"><textarea class="form-control" id="description${rowIdx}" name="description[]" style="min-height:30px;max-height:45px"></textarea></td>
                                                <td class="row-index text-center"><select class="form-control js-select" data-search="true" data-sort="false" name="students[]" id="students{rowIdx}">@foreach($students as $studentsRow)<option value="{{$studentsRow->id}}"> {{$studentsRow->full_name}}</option>@endforeach<select></td>
                                                <td class="row-index text-center"><select class="form-control js-select" data-search="true" data-sort="false" name="subject[]" id="subject${rowIdx}">@foreach($subjects as $subjectRow)<option value="{{$subjectRow->id}}"> {{$subjectRow->name}}</option>@endforeach<select></td>
                                                <td class="row-index text-center"><input type="text" class="form-control" id="quantity{rowIdx}" name="quantity[]"/></td>
                                                <td class="row-index text-center"><input type="text" class="form-control" id="unitPrice{rowIdx}" name="unitPrice[]"/></td>
                                                <td class="text-center"><button style="background-color:#2e314a; color:#fff" class="btn remove" type="button">Remove</button></td>
                                                 </tr>`);
                                          });
                                      
                                          // jQuery button click event to remove a row.
                                          $('#tbody').on('click', '.remove', function () {
                                      
                                            // Getting all the rows next to the row
                                            // containing the clicked button
                                            var child = $(this).closest('tr').nextAll();
                                      
                                            // Iterating across all the rows 
                                            // obtained to change the index
                                            child.each(function () {
                                      
                                              // Getting <tr> id.
                                              var id = $(this).attr('id');
                                      
                                              // Getting the <p> inside the .row-index class.
                                              var idx = $(this).children('.row-index').children('p');
                                      
                                              // Gets the row number from <tr> id.
                                              var dig = parseInt(id.substring(1));
                                      
                                              // Modifying row index.
                                              idx.html(`Row ${dig - 1}`);
                                      
                                              // Modifying row id.
                                              $(this).attr('id', `R${dig - 1}`);
                                            });
                                      
                                            // Removing the current row.
                                            $(this).closest('tr').remove();
                                      
                                            // Decreasing total number of rows by 1.
                                            rowIdx--;
                                          });
                                        });
                                      </script>
 
  <div class="container pt-4">
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead class="table-dark">
          <tr style="background-color:#2e314a; color:#fff">
              <th style="width:250px !important;" class="text-center">Description</th>
            <th style="width:180px !important;" class="text-center">Students</th>
            <th style="width:150px !important;"  class="text-center">Subjects</th>
            <th style="width:100px !important;" class="text-center">Quantity</th>
            <th style="width:100px !important;" class="text-center">Unit Price</th>
            <th class="text-center"></th>
          </tr>
        </thead>
        <tbody id="tbody">
           @foreach($saleInvoiceItems as $rowIdx => $item)
            <tr>
                <td class="row-index text-center">
                    <textarea class="form-control" id="description{{ $rowIdx }}" name="description[]" style="max-height:45px">
                        {{ $item->description }}
                    </textarea>
                </td>
                <td class="row-index text-center">
                    <select class="form-control" name="students[]" id="students{{ $rowIdx }}">
                        @foreach($students as $studentsRow)
                            <option value="{{ $studentsRow->id }}" {{ $item->studentID == $studentsRow->id ? 'selected' : '' }}>
                                {{ $studentsRow->full_name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td class="row-index text-center">
                    <select class="form-control" name="subject[]" id="subject{{ $rowIdx }}">
                        @foreach($subjects as $subjectRow)
                            <option value="{{ $subjectRow->id }}" {{ $item->subjectID == $subjectRow->id ? 'selected' : '' }}>
                                {{ $subjectRow->name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td class="row-index text-center">
                    <input type="text" class="form-control" id="quantity{{ $rowIdx }}" value="{{ $item->quantity }}" name="quantity[]">
                </td>
                <td class="row-index text-center">
                    <input type="text" class="form-control" id="unitPrice{{ $rowIdx }}" value="{{ $item->unitPrice }}" name="unitPrice[]">
                </td>
                <td class="text-center">
                    <button style="background-color:#2e314a; color:#fff" class="btn remove" type="button">Remove</button>
                </td>
            </tr>
        @endforeach
        </tbody>
      </table>
    </div>
   <button class="btn btn-md btn-success" 
      id="addBtn" type="button">
        Add Invoice Item
    </button>
  </div>

                              
                             
                             </div>
                              
                                <div class="row g-3">
                                    <div class="col-lg-12"><button style="background-color:#2e314a; color:#fff" class="btn btn-primary" type="submit">Update</button></div>    
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
<script>

        function myFunction() {
              
            var query = $('#user').val();  
            if(query != '')  
            {  
                $.ajax({
                    url: "{{ url('/addStudent/') }}/" + query,
                    method:"GET",  
                    dataType: 'json',
                    success:function(data)  
                    {  
                        
                        console.log(data);
                        //$('#userList').fadeIn();  
                        //$('#userList').html(data);  
                    }  
                });  
            }  
            
            }



$(document).ready(function(){
    
        $(document).on('click', 'li', function(){  
            $('#user').val($(this).text());  
            $('#userList').fadeOut();  
        });  

    $("select#customer_id").change(function(){
        var selectedCustomer = $(this).children("option:selected").val();
        
        if(selectedCustomer == '000123'){
            $('.customerInfo').show();
            $('.existingStudentInfo').hide();
            
            
            
        }else{
            $('.customerInfo').hide();
            $('.existingStudentInfo').show();
        }
        
        var userURL = $(this).data('url');
  
            $.ajax({
                url: "{{ url('/getStudent/') }}/" + selectedCustomer,

                type: 'GET',
                dataType: 'json',
                success: function(data) {
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
                            $('#subject1').html('1.<br/> <strong>Subject :</strong><span class="text">'+data.subjects[0]['subject']+'</span></br>  <strong>Day :</strong> <span class="text">'+data.subjects[0]['day']+'</span></br> <strong>Time :</strong> <span class="text">'+data.subjects[0]['time']+'</span></br> <strong>Tutor :</strong>'+' <u>'+data.subjects[0]['newstatus']+' </u>');
                            $('#subject2').html('2.<br/> <strong>Subject :</strong><span class="text">'+data.subjects[1]['subject']+'</span></br> <strong>Day :</strong> <span class="text">'+data.subjects[1]['day']+'</span></br> <strong>Time :</strong> <span class="text">'+data.subjects[1]['time']+'</span></br> <strong>Tutor :</strong>'+' <u>'+data.subjects[1]['newstatus']+' </u>');
                            $('#subject3').html('3.<br/> <strong>Subject :</strong><span class="text">'+data.subjects[2]['subject']+'</span></br> <strong>Day :</strong> <span class="text">'+data.subjects[2]['day']+'</span></br> <strong>Time :</strong> <span class="text">'+data.subjects[2]['time']+'</span></br> <strong>Tutor :</strong>'+' <u>'+data.subjects[2]['newstatus']+' </u>');
                            $('#subject4').html('4.<br/> <strong>Subject :</strong><span class="text">'+data.subjects[2]['subject']+'</span></br> <strong>Day :</strong> <span class="text">'+data.subjects[3]['day']+'</span></br> <strong>Time :</strong> <span class="text">'+data.subjects[3]['time']+'</span></br> <strong>Tutor :</strong>'+' <u>'+data.subjects[3]['newstatus']+' </u>');
                            
                            $('#subject12').html('1.<br/> <strong>Subject :</strong><span class="text">'+data.subjects[0]['subject']+'</span></br>  <strong>Day :</strong> <span class="text">'+data.subjects[0]['day']+'</span></br> <strong>Time :</strong> <span class="text">'+data.subjects[0]['time']+'</span></br> <strong>Tutor :</strong>'+' <u>'+data.subjects[0]['newstatus']+'  </u>');
                            $('#subject22').html('2.<br/> <strong>Subject :</strong><span class="text">'+data.subjects[1]['subject']+'</span></br> <strong>Day :</strong> <span class="text">'+data.subjects[1]['day']+'</span></br> <strong>Time :</strong> <span class="text">'+data.subjects[1]['time']+'</span></br> <strong>Tutor :</strong>'+' <u>'+data.subjects[1]['newstatus']+'  </u>');
                            $('#subject32').html('3.<br/> <strong>Subject :</strong><span class="text">'+data.subjects[2]['subject']+'</span></br> <strong>Day :</strong> <span class="text">'+data.subjects[2]['day']+'</span></br> <strong>Time :</strong> <span class="text">'+data.subjects[2]['time']+'</span></br> <strong>Tutor :</strong>'+' <u>'+data.subjects[2]['newstatus']+'  </u>');
                            $('#subject33').html('4.<br/> <strong>Subject :</strong><span class="text">'+data.subjects[2]['subject']+'</span></br> <strong>Day :</strong> <span class="text">'+data.subjects[3]['day']+'</span></br> <strong>Time :</strong> <span class="text">'+data.subjects[3]['time']+'</span></br> <strong>Tutor :</strong>'+' <u>'+data.subjects[3]['newstatus']+'  </u>');
                    // $('#userShowModal').modal('show');
                    // $('#user-id').text(data.id);
                    // $('#user-name').text(data.name);
                    // $('#user-email').text(data.email);
                }
            });
    });
    
     
});




</script>

@endsection
