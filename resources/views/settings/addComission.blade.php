@extends('layouts.main')

@section('content')

<div class="nk-content">
                  <div class="fluid-container">
                     <div class="nk-content-inner">
                        <div class="nk-content-body">
                           <div class="nk-block-head nk-page-head">
                              <div class="nk-block-head-content">
                              <table style="width:100%">
                                    <tbody><tr>
                                       <td><h1 class="nk-block-title">ADD STUDENT PIC COMMISSION</h1></td>
                                       <td></td>
                                    </tr>
                                 </tbody></table>
                              </div>
                           </div>
                           <div class="nk-block">
                              <div class="card">
                                 <div class="card-body">
                                 <form method="POST" action="{{route('submitAddComission')}}">
                              @csrf
                                 <div style="margin-top:20px;" class="row g-3">
                                
                                
                                 <div class="col-lg-6">
                                            <div class="form-group">
                                              <label for="company" class="form-label">From Date</label>
                                              <div class="form-control-wrap"><input type="date" class="form-control" name="fromDate" placeholder="Date of Birth"></div>
                                            </div>
                                          </div>
                                          <div class="col-lg-6">
                                            <div class="form-group">
                                              <label for="company" class="form-label">To Date</label>
                                              <div class="form-control-wrap"><input type="date" class="form-control" name="toDate" placeholder="Date of Birth"></div>
                                            </div>
                                          </div>
                                            <div class="col-lg-6">
                                            <div class="form-group">
                                              <label for="firstname" class="form-label">First Invoice Amount (Physical Class)</label>
                                              <div class="form-control-wrap"><input type="text" class="form-control" id="firstname" name="FirstInvoiceAmountPhysicalClass" placeholder="Enter Amount"></div>
                                            </div>
                                          </div>
                                          <div class="col-lg-6">
                                            <div class="form-group">
                                              <label for="firstname" class="form-label">Recurrence Invoice Amount (Physical Class)</label>
                                              <div class="form-control-wrap">
                                              <div class="form-control-wrap"><input type="text" class="form-control" id="firstname" name="RecurrenceInvoiceAmountPhysicalClass" placeholder="Enter Amount"></div>
                                                </div>
                                            </div>
                                          </div>
            
                                          <div class="col-lg-6">
                                            <div class="form-group">
                                              <label for="company" class="form-label">First Invoice Amount (Online Class)</label>
                                              <div class="form-control-wrap"><input type="text" class="form-control" name="FirstInvoiceAmountOnlineClass" placeholder="Enter Amount"></div>
                                            </div>
                                          </div>

                                          <div class="col-lg-6">
                                            <div class="form-group">
                                              <label for="company" class="form-label">Recurrence Invoice Amount (Online Class)</label>
                                              <div class="form-control-wrap"><input type="text" class="form-control" name="RecurrenceInvoiceAmountOnlineClass" placeholder="Enter Amount"></div>
                                            </div>
                                          </div>

                                          <div class="row g-3">
                                            <div class="col-lg-12"><button style="background-color:#2e314a; color:#fff" class="btn btn-primary" type="submit">Submit</button></div>    
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
