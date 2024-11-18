@extends('layouts.main')

@section('content')

<div class="nk-content">
            <div class="fluid-container">
              <div class="nk-content-inner">
                <div class="nk-content-body">
                  <div class="nk-block-head">
                  <div class="nk-block">
                    <div class="card">
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
                      <div class="card">
                           
                          <div class="row">
                              <div class="col-md-6">
                                  <a href="{{url("/downloadTutorPaymentSlip")."/".$tutor_payment->id}}">
                                                <button class="btn btn-primary btn-sm">Download</button>
                                            </a>
                              </div>
                               <div class="col-md-6">
                                   <a href="{{url("/sendTutorPaymentSlip")."/".$tutor_payment->id}}">
                                            <button class="btn btn-primary btn-sm">Send Payment Slip</button>
                                            </a>
                              </div>
                          </div>
            <div class="card-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h2>{{$tutor->full_name}} - {{$tutor->uid}}</h2>
                    </div>
                </div>
                
            </div>

            <div class="card-body">

            
                <div class="row" style="padding:0 25px;">
                    <div class="col-sm-6">
                        <div class="sender-logo">
                            <img src="{{url("/template/login.png")}}" style="max-height: 100px;">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="display-3 text-right" style="font-size:2.5rem">
                            <br/>
                            Payment Slip
                        </div>
                        <div class="invoice-date text-right" style="margin-top:10px">
                            
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row Tutor-information">
                    <div class="col-md-6">
                        <p><strong>Tutor Name: </strong> {{$tutor->full_name}}</p>
                        <p> <strong>Month &amp; Year: </strong> {{$tutor_payment->comissionMonth}} {{$tutor_payment->comissionYear}} </p>
                    </div>
                    <div class="col-md-6">
                            <p><strong>Tutor NRIC: </strong> {{$tutor->nric}}</p>
                    </div>
                </div>
                
                <div style="margin-top:5px;margin-bottom:25px;">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Student</th>
                                        <th scope="col">Subject</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Attended Duration</th>
                                        <th scope="col">Commission</th>
                                    </tr>
                                </thead>
                                    <tbody>
                                            @php
                                                $total = 0;
                                            @endphp
                                        @foreach($paidClasses as $rowSS)
                                        <tr>
                                            <input type="hidden" name="classAttendedID[]" value="{{$rowSS->id}}"/>
                                            <td>
                                            @php
                                                $studentDetail = DB::table('students')->where('id','=',$rowSS->studentID)->first();
                                            @endphp
                                            {{$studentDetail->full_name}} - {{$studentDetail->student_id}}
                                            </td>
                                            <td>
                                            
                                            @php
                                                $subjectDetail = DB::table('products')->where('id','=',$rowSS->subjectID)->first();
                                            @endphp
                                            {{$subjectDetail->name}} - {{$studentDetail->uid}}
                                            </td>
                                            <td>
                                                @php
                                                    echo date("d-m-Y", strtotime($rowSS->date));
                                                @endphp
                                            </td>
                                            <td>
                                                @php
                                                    echo$rowSS->totalTime;
                                                @endphp
                                                
                                              
                                                
                                            </td>
                                            
                                            <td>RM {{number_format($rowSS->commission,2)}}</td>
                                        </tr>
                                        
                                        @endforeach
                                </tbody>
                                
                                <tfoot class="thead-light">
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col"></th>
                                        <th scope="col"></th>
                                        <th scope="col">{{$attendedDuration}}</th>
                                        <th scope="col">RM {{number_format($totalCommission,2)}}</th>
                                    </tr>
                                </tfoot>
                                
                            </table>
                            
                            @php
                            $additionaltotal=0;
                            $deductionstotal=0;
                            @endphp
                            
                            <h2>Additionals</h2>
                            <table class="table table-hover table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Description</th>
                                        <th scope="col">Amount</th>
                                    
                                    </tr>
                                </thead>
                                <tbody>
                                         
                                        @foreach($additionals as $additional)
                                        <tr>
                                           <td>{{$additional->description}}</td>
                                            <td>{{$additional->amount}}</td>
                                        </tr>
                                        @php $additionaltotal+=$additional->amount; @endphp
                                        @endforeach
                                </tbody>
                                
                                
                               
                            </table>
                            
                            
                            <h2>Deductions</h2>
                            <table class="table table-hover table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Description</th>
                                        <th scope="col">Amount</th>
                                    
                                    </tr>
                                </thead>
                                <tbody>
                                         
                                        @foreach($deductions as $deduction)
                                        <tr>
                                           <td>{{$deduction->description}}</td>
                                            <td>{{$deduction->amount}}</td>
                                        </tr>
                                        @php $deductionstotal+=$deduction->amount; @endphp
                                        @endforeach
                                </tbody>
                                
                                
                                <tfoot class="thead-light">
                                    
                                    <tr>
                                       
                                        <th scope="col">Gross Total</th>
                                        <th scope="col">RM {{number_format(($tutor_payment->payAmount+$additionalsSum)-$deductionsSum,2)}}</th>

                                    </tr>
                                </tfoot>
                                
                            </table>
                            
                          
                            
                            
                        </div>
                    </div>
                    
                    
                
            
        
                
                <hr>
                <div class="row row-details pb-5">
                    <div class="col-md-3">
                        <a class="btn btn-light waves-effect waves-light" href="{{route('TutorPayments')}}">Back</a>
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

@endsection







