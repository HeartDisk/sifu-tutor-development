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
                        Payment Breakdown
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Tutor Payment Journal</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Payment Breakdown</li>
                        </ol>
                     </nav>
                  </div>
               </div>
            </div>
            <div class="nk-block">
               <div class="card card-gutter-md">
                  <div class="card-body">
                     @if (\Session::has('danger'))
                     <div class="alert alert-danger">
                        <ul>
                           <li>{!! \Session::get('danger') !!}</li>
                        </ul>
                     </div>
                     @endif
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
                        <form method="POST" action="{{route('submitTutorPayment')}}">
                           @csrf
                           <input type="hidden" value="{{$tutor->id}}" name="id"/>
                            
                          @php
                                $studentDetail = DB::table('students')->where('id','=',$class_attended[0]->studentID)->first();
                          @endphp
                          
                                          
                           <div class="row g-1 view-sindetails">
                              <div class="col-md-3 details-item">
                                  <p class="item-title">Tutor Name: </p>
                                  <p><strong>{{$tutor->full_name}}</strong></p>
                               </div>
                                <div class="col-md-3 details-item">
                                  <p class="item-title">Student Name: </p>
                                  <p><strong>{{$studentDetail->full_name}}</strong></p>
                               </div>
                              <div class="col-md-3 details-item">
                                  <p class="item-title">Tutor ID: </p>
                                  <p><strong>{{$tutor->uid}}</strong></p>
                               </div>
                              <div class="col-md-3 details-item">
                                  <p class="item-title">Payment Month: </p>
                                  <p><strong>{{$currentMonthName."-".$currentYear}}</strong></p>
                               </div>
                           </div>
                           <div class="row headwtb">
                            <div class="col-sm-12">
                              <h3>PAYMENT BREAK DOWN</h3>
                            </div>
                           </div>
                           <div class="table-responsive">
                              <table class="datatable-init table" data-nk-container="table-responsive">
                                 <thead>
                                    <tr>
                                       <th>Student</th>
                                       <th>Subject</th>
                                       <th>Date</th>
                                       <th>Attended Duration(Hrs)</th>
                                       {{--
                                       <th>Invoice Duration</th>
                                       --}}
                                       <th>Commision</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @foreach($class_attended as $rowSS)
                                    <tr>
                                       <input type="hidden" name="classAttendedID[]" value="{{$rowSS->id}}"/>
                                       <td>
                                          @php
                                          $studentDetail = DB::table('students')->where('id','=',$rowSS->studentID)->first();
                                          @endphp
                                           {{$studentDetail->student_id}}
                                       </td>
                                       <td>
                                          @php
                                          $subjectDetail = DB::table('products')->where('id','=',$rowSS->subjectID)->first();
                                          @endphp
                                          {{$subjectDetail->name}}
                                       </td>
                                       <td>
                                          @php
                                          echo date("d-m-Y", strtotime($rowSS->date));
                                          @endphp
                                       </td>
                                       <td>
                                          @if($rowSS->parent_verified == "YES")
                                          @php
                                          echo $rowSS->totalTime;
                                          @endphp
                                          @php
                                          $count = number_format((float)$rowSS->totalTime, 2, '.', '');
                                          @endphp
                                          @else
                                          0.00
                                          @endif
                                       </td>
                                       {{--
                                       <td>--}}
                                          {{--@php--}}
                                          {{--echo $rowSS->totalTime;--}}
                                          {{--@endphp--}}
                                          {{--@php--}}
                                          {{--$count = number_format((float)$rowSS->totalTime, 2, '.', '');--}}
                                          {{--@endphp--}}
                                          {{--
                                       </td>
                                       --}}
                                       <td>
                                          RM {{number_format($rowSS->commission,2)}}
                                       </td>
                                    </tr>
                                    @endforeach
                                 </tbody>
                                 <tfoot>
                                    <tr>
                                       <th></th>
                                       <th></th>
                                       <th></th>
                                       <th>Total</th>
                                       {{--
                                       <th>{{$attendedDuration}}</th>
                                       --}}
                                       <th>RM {{number_format($totalCommission,2)}}</th>
                                    </tr>
                                 </tfoot>
                              </table>
                           </div>
                     </div>
                  </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script type="text/javascript">
   function Focus_In (event) {
       event.srcElement.style.color = "red";
   }
   function additionalFocus_Out1(event) {
      var payingAmount = document.getElementById('payingAmount').value;
      var additionalAmount1 = document.getElementById('AdditionalAmount1').value;
      document.getElementById('payingAmount').value = parseInt(additionalAmount1) + parseInt(payingAmount);
   }
   function additionalFocus_Out2(event) {
      var payingAmount = document.getElementById('payingAmount').value;
      var additionalAmount2 = document.getElementById('AdditionalAmount2').value;
      document.getElementById('payingAmount').value = parseInt(additionalAmount2) + parseInt(payingAmount);
   }
   function additionalFocus_Out3(event) {
      var payingAmount = document.getElementById('payingAmount').value;
      var additionalAmount3 = document.getElementById('AdditionalAmount3').value;
      document.getElementById('payingAmount').value = parseInt(additionalAmount3) + parseInt(payingAmount);
   }
   function deductionFocus_Out1(event) {
      var payingAmount = document.getElementById('payingAmount').value;
      var deductionAmount1 = document.getElementById('deductionAmount1').value;
      document.getElementById('payingAmount').value = parseInt(payingAmount) - parseInt(deductionAmount1);
   }
   function deductionFocus_Out2(event) {
      var payingAmount = document.getElementById('payingAmount').value;
      var deductionAmount2 = document.getElementById('deductionAmount2').value;
      document.getElementById('payingAmount').value = parseInt(payingAmount) - parseInt(deductionAmount2);
   }
   function deductionFocus_Out3(event) {
      var payingAmount = document.getElementById('payingAmount').value;
      var deductionAmount3 = document.getElementById('deductionAmount3').value;
      document.getElementById('payingAmount').value = parseInt(payingAmount) - parseInt(deductionAmount3);
   }
</script>
@endsection