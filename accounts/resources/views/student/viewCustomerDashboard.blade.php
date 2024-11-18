@extends('layouts.main')
@section('content')
<div class="nk-content sifu-dashboard-page">
   <div class="container-fluid">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head">
                <div class="nk-block-head-between flex-wrap gap g-2">
                   <div class="nk-block-head-content">
                      <h2 class="nk-block-title">
                         Customer Dashboard
                      </h2>
                      <nav>
                         <ol class="breadcrumb breadcrumb-arrow mb-0">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Customer List</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Customer Dashboard</li>
                         </ol>
                      </nav>
                   </div>
                   <div class="nk-block-head-content">
                      <ul class="d-flex">
                         <li><a href="{{route('editCustomer',$customer->id)}}" class="btn btn-md d-md-none btn-primary"><span>Edit Profile</span></a></li>
                         <li><a href="{{route('editCustomer',$customer->id)}}" class="btn btn-primary d-none d-md-inline-flex"><span>Edit Customer Profile</span></a></li>
                      </ul>
                   </div>
                </div>
            </div>
            <div class="nk-block-head-between flex-wrap gap g-2 align-items-start">               
                <div class="col-4">
                    <div class="card">
                      <div class="card-body">
                            <div class="dcard-status">
                               <div class="col-md-8">
                                  <img src="{{asset('public/customer.png')}}">
                               </div>
                               <div class="col-md-4">
                                  @if($commitmentFee == null)
                                  <p class='dtable-status-nostat'>Unregistered</p>
                                  @elseif($commitmentFee != null && $jobticketCheck == null)
                                  <p class='dtable-status-inactive'>Inactive</p>
                                  @elseif($commitmentFee != null && $jobticketCheck != null)
                                  <p class='dtable-status-active'>Active</p>
                                  @endif
                               </div>
                            </div>
                           <ul class="list-group list-group-borderless small">
                            <li class="list-group-item">
                               <span class="image"><img src="{{asset('public/id.png')}}"></span>
                               <span class="title">Account ID:</span>
                               <span class="text">{{$customer->uid}}</span>
                            </li>
                            <li class="list-group-item">
                               <span class="image"><img src="{{asset('public/customer-ui.png')}}"></span>
                               <span class="title">Full Name:</span>
                               <span class="text">{{$customer->full_name}}</span>
                            </li>
                            <li class="list-group-item">
                               <span class="image"><img src="{{asset('public/email.png')}}"></span>
                               <span class="title">Email:</span>
                               <span class="text">{{$customer->email}}</span>
                            </li>
                            <li class="list-group-item">
                               <span class="image"><img src="{{asset('public/location.png')}}"></span>
                               <span class="title">Address:</span>
                               <span class="text">{{$customer->address1}}</span>
                            </li>
                            <li class="list-group-item">
                               <span class="image"><img src="{{asset('public/state.png')}}"></span>
                               <span class="title">State:</span>
                               <span class="text">{{$customerState->name}}</span>
                            </li>
                            <li class="list-group-item">
                               <span class="image"><img src="{{asset('public/city.png')}}"></span>
                               <span class="title">City:</span>
                               <span class="text">{{$customerCity->name}}</span>
                            </li>
                         </ul>
                      </div>
                    </div> 
                </div>

                <div class="col-8 tabs-7">
                   <ul class="tabs">
                      <li class="active"><a href="#tab25">Overview</a></li>
                      <li><a href="#tab26">Tickets</a></li>
                      <li><a href="#tab27">Invoices & Payments</a></li>
                      <li><a href="#tab28">Commitment Fees</a></li>
                   </ul>
                   <section class="tab_content_wrapper">
                      <article class="tab_content" id="tab25">
                         <div class="card">
                            <div class="card-body">
                               <div class="bio-block">
                                  <h3 class="bio-block-title">Analytics</h3>
                               </div>
                            </div>

                            <div class="card-body">
                               <div class="bio-block">
                                     <h6>Total Subscription Hours by Subject</h6>
                                     <ul class="sifu-view-ulli">
                                        @foreach($student as $rowStudent)
                                            @php
                                            $subjects = DB::table('job_tickets')->where('student_id', $rowStudent->id)->pluck('subjects');
                                            
                                            $subjectDetails = DB::table('products')->whereIn('id', $subjects)->get();
                                            @endphp
                                            
                                            @foreach($subjectDetails as $subject)
                                                @php
                                                $hours = DB::table('class_attendeds')
                                                    ->where('studentID', $rowStudent->id)
                                                    ->where('status', 'attended')
                                                    ->where('subjectID', $subject->id)
                                                    ->sum('totalTime');
                                                @endphp
                                                <li>
                                                    <span>{{ $rowStudent->full_name }}</span>
                                                    <span>{{ $subject->name }}</span>
                                                    <span>{{ number_format($hours, 2, ".", "") }}</span>
                                                </li>
                                            @endforeach
                                        @endforeach
                                     </ul>
                                  </div>
                               </div>

                               <div class="card-body">
                                  <div class="bio-block">
                                     <h6>No of Active Month</h6>                                                
                                     
                                     @foreach($student as $rowStudent)
                                     @php
                                     $ifExist = DB::table('class_attendeds')
                                     ->where('studentID', '=', $rowStudent->id)
                                     ->where('status', '=', 'attended')
                                     ->get();
                                     if (count($ifExist) > 0) {
                                    $results = DB::table('class_attendeds')
                                            ->where('studentID', $rowStudent->id)
                                            ->where('status', 'attended')
                                            ->selectRaw('count(*) as total, sum(totalPrice) as totalPrice, sum(totalTime) as totalTime, YEAR(created_at) as year, DATE_FORMAT(created_at, "%M") as month')
                                            ->get();
                                     // Check if results are not empty before accessing properties
                                     if (!empty($results)) {
                                     $totalClasses = $results[0]->total;
                                     $totalPrice = $results[0]->totalPrice;
                                     $totalTime = $results[0]->totalTime;
                                     // Now you can use $totalClasses, $totalPrice, $totalTime as needed
                                     } else {
                                     // Handle the case where the second query didn't return any results
                                     }
                                     } else {
                                     // Handle the case where the first query didn't return any results
                                     $results = [];
                                     }
                                     @endphp
                                     @if(count($ifExist) > 0)
                                     <div class="table-responsive">
                                        <table class="table sifu-view-dashtable" id="left-table">
                                           <tr>
                                              <th>Student Name</th>
                                              <th >Year</th>
                                              <th >Month</th>
                                              <th >Total Scheduled Classes</th>
                                              <!--<th >Total Price Scheduled Classes </th>-->
                                              <th >Total Time Scheduled Classes </th>
                                           </tr>
                                           @foreach($results as $rowResult)
                                           <tr>
                                              <td>{{$rowStudent->full_name}}</td>
                                              <td>{{$rowResult->year }}</td>
                                              <td>{{$rowResult->month }}</td>
                                              <td>{{$rowResult->total}}</td>
                                              <!--<td>{{$rowResult->totalPrice}}</td>-->
                                              <td>
                                                 @php
                                                 echo number_format($rowResult->totalTime, 2, ".", "");
                                                 @endphp
                                              </td>
                                           </tr>
                                           @endforeach
                                        </table>
                                     </div>
                                     @endif
                                     @endforeach
                               </div>
                            </div>

                            <div class="card-body">
                               <div class="bio-block">
                                  <h6>Students List</h6> 
                                  <div class="table-responsive">
                                     <table class="table sifu-view-dashtable">
                                        <tr>
                                           <th> Student Name </th>
                                           <th> Subjects </th>
                                        </tr>
                                        @foreach($student as $rowStudent)
    <tr>
        <td><a href="{{ url('/viewStudent/' . $rowStudent->id) }}">{{ $rowStudent->full_name }}</a></td>
        <td>
            @php
                $subjectNames = [];

                $subjects = DB::table('job_tickets')->where('student_id', '=', $rowStudent->id)->get();
                foreach($subjects as $rowSubject){
                    $subjectDetail = DB::table('products')->where('id', '=', $rowSubject->subjects)->first();
                    $subjectNames[] = $subjectDetail->name;
                }

                $temp = DB::table('job_ticket_students')->where('student_name', '=', $rowStudent->full_name)->get();
                foreach($temp as $rowSubject){
                    $subjectDetail = DB::table('products')->where('id', '=', $rowSubject->subject_id)->first();
                    $subjectNames[] = $subjectDetail->name;
                }

                // Remove duplicate subject names
                $subjectNames = array_unique($subjectNames);

                // Print unique subject names
                foreach($subjectNames as $subjectName) {
                    echo $subjectName . "<br/>";
                }
            @endphp
        </td>
    </tr>
@endforeach
                                     </table>
                                  </div>
                               </div>
                            </div>
                         </div>
                      </article>

                      <article class="tab_content" id="tab26">
                         <div class="card">
                            <div class="card-body">
                               <div class="bio-block">
                                  <h6 class="bio-block-title">Tickets List</h6>
                                  <div class="table-responsive">
                                     <table class="table sifu-view-dashtable">
                                        <tr>
                                           <th> Ticket ID </th>
                                           <th> Subjects </th>
                                           <th> Mode </th>
                                           <th> Day </th>
                                           <th> Time</th>
                                        </tr>
                                        @foreach($customerTickets as $customerTicket)
                                        @php
                                        $tickets = DB::table('job_tickets')->where('id','=',$customerTicket->id)->first();
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
                      </article>

                      <article class="tab_content" id="tab27">
                         <div class="card">
                            <div class="card-body">
                               <div class="bio-block">
                                  <h6 class="bio-block-title">Invoice List</h6>
                                  <div class="table-responsive">
                                     <table class="table sifu-view-dashtable">
                                        <tr class="thead-light">
                                           <th> Invoice Reference No </th>
                                             <th> Student Name</th>
                                             <th> Student ID</th>
                                            <th> Subjects </th>
                                            <th> Date </th>
                                            <th> Status </th>
                                           <th> Total</th>
                                          
                                        </tr>    
                                        @foreach($student as $rowStudent)
                                        @php
                                        $tickets = DB::table('invoices')->where('studentID','=',$rowStudent->id)->get();
                                        $studentDetail = DB::table('students')->where('id','=',$rowStudent->id)->first(); 
                                        
                                        
                                        @endphp
                                        @if($tickets)
                                        
                                        @foreach($tickets as $ticket)
                                         <tr>
                                           <td> <a target="_blank" href="{{route('viewStudentInvoiceById',$ticket->ticketID)}}">{{"INV-".$ticket->id}}</a> </td>
                                            <td>{{isset($studentDetail->full_name)?$studentDetail->full_name:""}}</td>
                                            <td>{{isset($studentDetail->uid)?$studentDetail->uid:""}}</td>
                                           <td> 
                                           @php
                                           $subjectDetail = DB::table('products')->where('id','=',$ticket->subjectID)->first();
                                           echo $subjectDetail->name;
                                           @endphp
                                           </td>
                                           <td>{{date("d-m-Y", strtotime($ticket->invoiceDate))}}</td>
                                            <td>
                                               <p class="dtb-status">{{$ticket->status}}</p>
                                            </td>
                                           <td>{{$ticket->invoiceTotal}}</td>
                                        </tr> 
                                        @endforeach
                                       
                                     @endif
                                     @endforeach
                                     </table>
                                  </div>
                               </div>
                            </div>
                            <div class="card-body">
                               <div class="bio-block">
                                  <h6 class="bio-block-title">Payment List</h6>
                                  <div class="table-responsive">
                                     <table class="table sifu-view-dashtable">
                                        <tr class="thead-light">
                                           <th> Ticket ID </th>
                                           <th> Subjects </th>
                                           <th> Invoice Amount </th>
                                        </tr>    
                                        @foreach($student as $rowStudent)
                                        @php
                                        $tickets = DB::table('studentinvoices')->where('isPaid','=','paid')->where('studentID','=',$rowStudent->id)->first();
                                        @endphp
                                        @if($tickets)
                                        <tr>
                                           <td><a target="_blank" href="{{ url('/viewTicket/' . $tickets->ticketID) }}">{{ $tickets->ticketID }}</a></td>
                                           <td> 
                                           @php
                                           $subjectDetail = DB::table('products')->where('id','=',$tickets->subjectID)->first();
                                           echo $subjectDetail->name;
                                           @endphp
                                           </td>
                                           <td>{{$tickets}}</td>
                                        </tr> 
                                        @endif
                                        @endforeach
                                     </table>
                                  </div>
                               </div>
                            </div>
                         </div>
                      </article>

                      <article class="tab_content" id="tab28">
                         <div class="card">
                            <div class="card-body">
                               <div class="bio-block">
                                  <h6 class="bio-block-title">Commitment fee List</h6>
                                  <div class="table-responsive">
                                     <table class="table sifu-view-dashtable">
                                        <tr class="thead-light">
                                           <th>Amount</th>
                                           <th>Date</th>
                                           <th>Commitment Fee Attachment</th>
                                        </tr>    
                                        @php
                                        $data = DB::table('customer_commitment_fees')->where('customer_id',$customer->id)->first();
                                        @endphp
                                        @if($data)
                                        <tr>
                                           <td>{{$data->payment_amount}}</td>
                                           <td>{{$data->payment_date}}</td>
                                           <td> <a target="_blank" href="{{url("/public/customerCommitmentFee")."/".$data->payment_attachment}}">View File</a></td>
                                        </tr> 
                                        @endif
                                     </table>
                                  </div>
                               </div>
                            </div>
                         </div>
                      </article>

                   </section>
                </div>
            </div>
         </div>
      </div>
   </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="{{asset('public/css/jQueryTab.js')}}"></script>
<script type="text/javascript">
   $('.tabs-7').jQueryTab({
    initialTab: 1,
    tabInTransition: 'fadeIn',
    tabOutTransition: 'fadeOut',
    cookieName: 'active-tab-7'
   });

   var _gaq = _gaq || [];
   _gaq.push(['_setAccount', 'UA-36251023-1']);
   _gaq.push(['_setDomainName', 'jqueryscript.net']);
   _gaq.push(['_trackPageview']);
   (function() {
    var ga = document.createElement('script');
    ga.type = 'text/javascript';
    ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(ga, s);
   })();

   function myFunction() {
    var query = $('#user').val();
    if (query != '') {
      $.ajax({
        url: "{{ url('/addStudent/') }}" + query,
        method: "GET",
        dataType: 'json',
        success: function(data) {
          console.log(data);  
        }
      });
    }
   }

   $(document).ready(function() {
    $(document).on('click', 'li', function() {
      $('#user').val($(this).text());
      $('#userList').fadeOut();
    });
    $("select#customer_id").change(function() {
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
