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
                        Tutor Payments
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Tutors</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Tutor Payments</li>
                        </ol>
                     </nav>
                  </div>
               </div>
            </div>
            <div class="nk-block">
               <div class="card overflow-hidden">
                <div class="card-body">
                  <form action="{{route('TutorPayments')}}" method="GET">
                     @csrf
                     <div class="row justify-content-between tableper-row">
                        <div class="col-md-3">
                           <div class="input-group  input-group-md">
                              <label class="input-group-text" for="inputGroupSelect01">Month</label>
                              <select class="form-control" id="SelectedMonth" name="SelectedMonth">
                                 <option selected="selected"></option>
                                 <option value="January">January</option>
                                 <option value="February">February</option>
                                 <option value="March">March</option>
                                 <option value="April">April</option>
                                 <option value="May">May</option>
                                 <option value="June">June</option>
                                 <option value="July">July</option>
                                 <option value="August">August</option>
                                 <option value="September">September</option>
                                 <option value="October">October</option>
                                 <option value="November">November</option>
                                 <option value="December">December</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="input-group  input-group-md">
                              <label class="input-group-text" for="inputGroupSelect01">Year</label>
                              <select class="form-control" id="SelectedYear" name="SelectedYear">
                                 <option selected="selected"></option>
                                 <option value="2023">2023</option>
                                 <option value="2024">2024</option>
                                 <option value="2025">2025</option>
                                 <option value="2026">2026</option>
                                 <option value="2027">2027</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="input-group input-group-md">
                              <span class="input-group-text" id="inputGroup-sizing-sm">Search</span>
                              <input name="search" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" placeholder="Tutor Name">
                           </div>
                        </div>
                        <div class="col-md-2">
                           <div class="input-group input-group-md">
                              <input type="submit" class="btn btn-primary" aria-label="Sizing example input" value="Search" aria-describedby="inputGroup-sizing-sm">
                           </div>
                        </div>
                     </div>
                  </form>
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
                  <table class="datatable-init table" data-nk-container="table-responsive">
                     <thead>
                        <tr>
                           <th>#</th>
                           <th>Tutor Id</th>
                           <th>Fullname</th>
                           <th>Month - Year</th>
                           <th>Total Amount</th>
                           <th>Payment Date</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php
                        $number = 1;
                        
                        
                        @endphp
                        @foreach($tutorPayments as $rowTutorPayment)
                        @if(isset($rowTutorPayment->tutor))
                        <tr>

                           <th>{{$number++}}</th>
                           <td>{{$rowTutorPayment->tutor->uid}}
                           </td>
                           <td>
                              {{$rowTutorPayment->tutor->full_name}}
                           </td>
                           <td>{{$rowTutorPayment->comissionMonth}}
                              - {{$rowTutorPayment->comissionYear}}
                           </td>
                           <td>{{$rowTutorPayment->payAmount}}</td>
                           <td>{{$rowTutorPayment->paymentDate}}</td>
                           <td>
                              @can("tutor-payment-slip")
                              <a class="dtable-cbtn bt-view dtb-tooltip" dtb-tooltip="View Slip" href="{{route('tutorPaymentSlip',$rowTutorPayment->id)}}"><i class="fa fa-eye"></i></a>
                              @endcan
                              @can("tutor-payment-download-slip")
                              <a class="dtable-cbtn bt-download dtb-tooltip" dtb-tooltip="Download Slip" href="{{route('downloadTutorPaymentSlip',$rowTutorPayment->id)}}"><i class="fa fa-download"></i></a>
                              @endcan
                           </td>
                        </tr>
                        @endif
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
@endsection