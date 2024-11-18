@extends('layouts.main')

@section('content')

<style>
    .card{
        box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;
    }
</style>

<br/><br/><br/><br/>

<div class="nk-content">
  <div class="container-fluid">
    <div class="nk-content-inner">
      <div class="nk-content-body">
        <div class="nk-block-head">
          <div class="nk-block-head">
            <div class="nk-block-head-between flex-wrap gap g-2 align-items-start">
              <div class="nk-block-head-content">
                <div class="d-flex flex-column flex-md-row align-items-md-center">
                  <div class="media media-huge media-circle">
                    <img src="{{url('public/template/images/avatar/a.jpg')}}" class="img-thumbnail" alt="">
                  </div>
                  <div class="mt-3 mt-md-0 ms-md-3">
                    <h3 class="title mb-1">{{$customer->full_name}}</h3>
                    <ul class="nk-list-option pt-1">
                      <li>
                        <em class="icon ni ni-map-pin"></em>
                        <span class="small">{{$customer->address1}}</span>
                      </li>
                      <li>
                        <em class="icon ni ni-building"></em>
                        <span class="small">{{$customerState->name}}</span>
                      </li>
                      <li>
                        <em class="icon ni ni-building"></em>
                        <span class="small">{{$customerCity->name}}</span>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>

                <div class="nk-block-head-content">
                <div class="d-flex gap g-3">
                  <div class="gap-col">
                    <div class="box-dotted py-2">
                      <div class="d-flex align-items-center">
                        <div class="h4 mb-0">Account ID</div>
                        <span class="change up ms-1 small">
                          <em class="icon ni ni-arrow-down"></em>
                        </span>
                      </div>
                      <div class="smaller">{{$customer->uid}}</div>
                    </div>
                  </div>
                  <div class="gap-col">
                    <div class="box-dotted py-2">
                      <div class="d-flex align-items-center">
                        <div class="h4 mb-0">Email</div>
                        <span class="change up ms-1 small">
                          <em class="icon ni ni-arrow-up"></em>
                        </span>
                      </div>
                      <div class="smaller">{{$customer->email}}</div>
                    </div>
                  </div>
                  
                  <div class="gap-col">
                    <div class="box-dotted py-2">
                      <div class="d-flex align-items-center">
                        <div class="h4 mb-0">Students</div>
                        <span class="change up ms-1 small">
                          <em class="icon ni ni-arrow-up"></em>
                        </span>
                      </div>
                      <div class="smaller">
                                {{$countStudents}}
                      </div>
                    </div>
                  </div>
                  
                  <div class="gap-col">
                    <div class="box-dotted py-2">
                      <div class="d-flex align-items-center">
                        <div class="h4 mb-0">Paid INV</div>
                        <span class="change up ms-1 small">
                          <em class="icon ni ni-arrow-up"></em>
                        </span>
                      </div>
                      <div class="smaller">
                                {{$countStudents}}
                      </div>
                    </div>
                  </div>
                  
                  <div class="gap-col">
                    <div class="box-dotted py-2">
                      <div class="d-flex align-items-center">
                        <div class="h4 mb-0">Unpaid INV</div>
                        <span class="change up ms-1 small">
                          <em class="icon ni ni-arrow-up"></em>
                        </span>
                      </div>
                      <div class="smaller">
                                {{$countStudents}}
                      </div>
                    </div>
                  </div>
                  
                  
                </div>
              </div>
              
            </div>
          </div>
          <div class="nk-block-head-between gap g-2">
            <div class="gap-col">
              <ul class="nav nav-pills nav-pills-border gap g-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <a href="{{ url('/customerDashboard/' . $customer->id) }}">Customer Dashboard</a>

                </li>
                <li class="nav-item" role="presentation">
                    <a href="{{ url('/customerTicket/' . $customer->id) }}" class="nav-link active">Tickets</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="{{ url('/customerInvoices/' . $customer->id) }}">Invoices & Payments</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="{{ url('/customerCommentmentFees/' . $customer->id) }}">Commitment Fees</a>
                </li>
              </ul>
            </div>
            <div class="gap-col">
              <ul class="d-flex gap g-2">
                <li class="d-none d-md-block">
                  <a href="{{route('editCustomer',$customer->id)}}" class="btn btn-soft btn-primary">
                    <em class="icon ni ni-edit"></em>
                    <span>Edit Profile</span>
                  </a>
                </li>
                <li class="d-md-none">
                  <a href="user-edit.html" class="btn btn-soft btn-primary btn-icon">
                    <em class="icon ni ni-edit"></em>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="nk-block">
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane show active" id="tab-1" tabindex="0" role="tabpanel">
              <div class="card card-gutter-md">
                <div class="card-row card-row-lg col-sep col-sep-lg">
                  <div class="card-aside" style="width:30%">
                    <div class="card-body">
                      <div class="bio-block">
                        <h4 class="bio-block-title">Details</h4>
                        <ul class="list-group list-group-borderless small">
                          <li class="list-group-item">
                            <span class="title fw-medium w-40 d-inline-block">Account ID:</span>
                            <span class="text">{{$customer->uid}}</span>
                          </li>
                          <li class="list-group-item">
                            <span class="title fw-medium w-40 d-inline-block">Full Name:</span>
                            <span class="text">{{$customer->full_name}}</span>
                          </li>
                          <li class="list-group-item">
                            <span class="title fw-medium w-40 d-inline-block">Email:</span>
                            <span class="text">{{$customer->email}}</span>
                          </li>
                          <li class="list-group-item">
                            <span class="title fw-medium w-40 d-inline-block">Address:</span>
                            <span class="text">{{$customer->address1}}</span>
                          </li>
                        </ul>
                      </div>
                     
                     
                    </div>
                  </div>
                  <div class="card-content col-sep" style="
    width: 70%;
">
                    <div class="card-body">
                      <div class="bio-block">
                        <h4 class="bio-block-title">Ticket's List</h4>
                        <table class="table table-striped">
                                <tr class="thead-light">
                                    <th> Ticket ID </th>
                                    <th> Subjects </th>
                                    <th> Mode </th>
                                    <th> Day </th>
                                    <th> Time</th>
                                </tr>    
                            @foreach($student as $rowStudent)
                            @php
                                $tickets = DB::table('job_tickets')->where('student_id','=',$rowStudent->id)->first();
                            @endphp
                            @if($tickets)
                            
                            <tr>
                                <td><a target="_blank" href="{{ url('/viewTicket/' . $tickets->id) }}">{{ $tickets->uid }}</a></td>
                                    <td> 
                                        @php
                                            $subjectDetail = DB::table('products')->where('id','=',$tickets->subjects)->first();
                                            echo $subjectDetail->name;
                                        @endphp
                                    </td>
                                    <td>{{$tickets->mode}} </td>
                                    <td>{{$tickets->day}}</td>
                                    <td>{{$tickets->time}}</td>
                                </tr> 
                            
                            
                            @endif
                            
                           
                            @endforeach
                            
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
                    url: "{{ url('/addStudent/' . $query) }}",
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
                url: "{{ url('/getStudent/') }}" + selectedCustomer,
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
