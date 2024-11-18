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
                              <h3 class="title mb-1">Edit Creditor Invoice</h3>
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
                          <form method="POST" action="{{route('UpdateCreditorInvoice')}}">
                              @csrf
                            <input type="hidden" name="creditorID" value="{{$data->id}}">
                            <div class="row g-3">
                            <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="firstname" class="form-label">Occurance Date</label>
                                  <div class="form-control-wrap"><input type="date" name="occuranceDate" value="{{ \Carbon\Carbon::parse($data->OccuranceDate)->format('Y-m-d') }}" class="form-control" id="occuranceDate"></div>
                                </div>
                            </div>
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="firstname" class="form-label">Creditor Name</label>
                                  <div class="form-control-wrap"><input type="text" name="creditorName" value="{{$data->creditorName}}" class="form-control" id="creditorName"></div>
                                </div>
                              </div>
                              
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="firstname" class="form-label">Description</label>
                                  <div class="form-control-wrap"><input type="text" name="description" value="{{$data->description}}" class="form-control" id="description"></div>
                                </div>
                              </div>
                              
                               <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="firstname" class="form-label">Category</label>
                                  <div class="form-control-wrap"><input type="text" name="category" value="{{$data->category}}" class="form-control" id="category"></div>
                                </div>
                              </div>
                              
                              <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="firstname" class="form-label">Quantity</label>
                                  <div class="form-control-wrap"><input type="text" name="quantity" value="{{$data->quantity}}" class="form-control" id="quantity"></div>
                                </div>
                              </div>
                            
                            <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="firstname" class="form-label">Cost Price</label>
                                  <div class="form-control-wrap"><input type="text" name="costPrice" value="{{$data->costPrice}}" class="form-control" id="costPrice"></div>
                                </div>
                              </div>
                            
                            <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="firstname" class="form-label">Payment Due Date</label>
                                  <div class="form-control-wrap"><input type="date" name="paymentDueDate" value="{{ \Carbon\Carbon::parse($data->paymentDueDate)->format('Y-m-d') }}" class="form-control" id="paymentDueDate"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                  <label for="firstname" class="form-label">Remarks</label>
                                  <div class="form-control-wrap"><textarea name="remarks" class="form-control"  style="min-height:30px;max-height:45px">{{$data->creditorName}}</textarea></div>
                                </div>
                            </div>

                                <div class="row g-3">
                                    <div class="col-lg-12"><button style="background-color:#2e314a; color:#fff" class="btn btn-primary" type="submit">Submit</button></div>    
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
                    url: "{{ url('addStudent') }}/" + query,
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
