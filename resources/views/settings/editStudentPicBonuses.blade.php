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
                                       <td><h1 class="nk-block-title">EDIT STUDENT PIC BONUS</h1></td>
                                       <td></td>
                                    </tr>
                                 </tbody></table>
                              </div>
                           </div>
                           <div class="nk-block">
                              <div class="card">
                                 <div class="card-body">
                                 <form method="POST" action="{{route('submitEditStudentPicBonuses')}}">
                              @csrf

                              <input type="hidden" name="id" value="{{$studentbonuses->id}}" >
                                 <div style="margin-top:20px;" class="row g-3">
                                
                                
                                 <div class="col-lg-4">
                                            <div class="form-group">
                                              <label for="company" class="form-label">From Date</label>
                                              <div class="form-control-wrap"><input type="date" class="form-control" name="fromDate" value="{{$studentbonuses->fromDate}}" placeholder="Enter Date"></div>
                                            </div>
                                          </div>
                                          <div class="col-lg-4">
                                            <div class="form-group">
                                              <label for="company" class="form-label">To Date</label>
                                              <div class="form-control-wrap"><input type="date" class="form-control" name="toDate" value="{{$studentbonuses->toDate}}" placeholder="Enter Date"></div>
                                            </div>
                                          </div>
                                            <div class="col-lg-4">
                                            <div class="form-group">
                                              <label for="firstname" class="form-label">Range From</label>
                                              <div class="form-control-wrap"><input type="text" class="form-control" id="firstname" value="{{$studentbonuses->rangeFrom}}" name="rangeFrom" placeholder="Enter Range"></div>
                                            </div>
                                          </div>
                                          <div class="col-lg-4">
                                            <div class="form-group">
                                              <label for="firstname" class="form-label">Range To</label>
                                              <div class="form-control-wrap">
                                              <div class="form-control-wrap"><input type="text" class="form-control" id="firstname" value="{{$studentbonuses->rangeTo}}" name="rangeTo" placeholder="Ener Range"></div>
                                                </div>
                                            </div>
                                          </div>
            
                                          <div class="col-lg-4">
                                            <div class="form-group">
                                              <label for="company" class="form-label">Bonus Amount</label>
                                              <div class="form-control-wrap"><input type="text" class="form-control" name="bonusAmount" value="{{$studentbonuses->bonusAmount}}" placeholder="Enter Bonus Amount"></div>
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
