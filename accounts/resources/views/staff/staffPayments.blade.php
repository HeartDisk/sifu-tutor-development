@extends('layouts.main')

@section('content')

            <div class="fluid-container">
              <div class="nk-content-inner">
                <div class="nk-content-body">
                  <div class="nk-block-head">
                    <div class="nk-block-head">
                      <div class="nk-block-head-between flex-wrap gap g-2 align-items-center">
                        <div class="nk-block-head-content">
                          <div class="d-flex flex-column flex-md-row align-items-md-center">
                            <div class="mt-3 mt-md-0 ms-md-3">
                              <h3 class="title mb-1">Make Staff Payment</h3>
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
                          <form method="POST" action="{{route('submitPaymentStaffs')}}">
                              @csrf
                            <div class="row g-3">
                            <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="firstname" class="form-label">Staff ID</label>
                                  <div class="form-control-wrap"><input type="text" class="form-control" required  name="staffID" value="{{$staff->uid}}" id="staffID"></div>
                                </div>
                              </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="firstname" class="form-label">Start Working Date</label>
                                  <div class="form-control-wrap"><input type="date" class="form-control"  required name="registration_date"  value="{{$staff->start_date}}" id="startWorkingDate"></div>
                                </div>
                              </div>
                              
                               <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="firstname" class="form-label">Designation</label>
                                  <div class="form-control-wrap"><input type="text" class="form-control"  name="desgnation"  value="{{$staff->designation}}" id="desgnation"></div>
                                </div>
                              </div>
                              
                              <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="firstname" class="form-label">Basic Salary</label>
                                  <div class="form-control-wrap"><input type="text" class="form-control"  name="basic_salary"  value="{{$staff->basic_salary}}" id="basic_salary"></div>
                                </div>
                              </div>
                              
                              
                            </div>

                            <div class="row g-3 mt-4">
                                
                                <h3>PAYMENT</h3>
                            <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="firstname" class="form-label">Payment Date</label>
                                  <div class="form-control-wrap"><input type="date" class="form-control" id="paymentDate" name="paymentDate" placeholder="Payment Date"></div>
                                </div>
                              </div>
                              <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="firstname" class="form-label">Salary Month</label>
                                  <div class="form-control-wrap">
                                        <select class="js-select" id="salaryMonth" data-search="true" name="salaryMonth"   data-sort="false">
                                            <option value="January">January</option>
                                            <option value="February">February</option>
                                            <option value="May">May</option>
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
                              </div>

                              <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="firstname" class="form-label">Salary Year</label>
                                  <div class="form-control-wrap">
                                        <select class="js-select" id="salaryYear" data-search="true" name="salaryYear"   data-sort="false">
                                            <option value="2019">2019</option>
                                            <option value="2020">2020</option>
                                            <option value="2021">2021</option>
                                            <option value="2022">2022</option>
                                            <option value="2023">2023</option>
                                        </select>
                                    </div>
                                </div>
                              </div>
                              
                              <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="email" class="form-label">Basic Salary Description</label>
                                  <div class="form-control-wrap"><input type="text" class="form-control" id="basicSalaryDescription"  name="basicSalaryDescription" placeholder="Basic Salary Description"></div>
                                </div>
                              </div>
                              <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="company" class="form-label">Basic Salary</label>
                                  <div class="form-control-wrap"><input type="text" class="form-control" id="basicSalary" name="basicSalry" placeholder="Basic Salary"></div>
                                </div>
                              </div>
                              <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="company" class="form-label">Bonus Amount</label>
                                  <div class="form-control-wrap"><input type="text" class="form-control" id="bonusAmount" name="bonusAmount" placeholder="Bonus Amount"></div>
                                </div>
                              </div>
                              
                              <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="company" class="form-label">Comission</label>
                                  <div class="form-control-wrap"><input type="text" class="form-control" id="comission" name="comission" placeholder="Comission"></div>
                                </div>
                              </div>
                              
                              <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="company" class="form-label">Overtime Amount (Per Hour)</label>
                                  <div class="form-control-wrap"><input type="text" class="form-control" id="overTimeAmount" name="overTimeAmount" placeholder="Over Time Amount"></div>
                                </div>
                              </div>
                              
                              
                              <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="company" class="form-label">Overtime (Hour)</label>
                                  <div class="form-control-wrap"><input type="text" class="form-control" id="overTimeHour" name="overTimeHour" placeholder="Over Time Hour"></div>
                                </div>
                              </div>
                              
                              
                              <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="company" class="form-label">Claim</label>
                                  <div class="form-control-wrap"><input type="text" class="form-control" id="claim" name="claim" placeholder="Clain"></div>
                                </div>
                              </div>
                              
                              <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="company" class="form-label">No. of unpaid leave</label>
                                  <div class="form-control-wrap"><input type="text" class="form-control" id="numberOfUnpaidLeave" name="numberOfUnpaidLeave" placeholder="Number of Unpaid Leave"></div>
                                </div>
                              </div>
                              
                               <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="firstname" class="form-label">Deduction</label>
                                  <div class="form-control-wrap"><input type="text" class="form-control"  name="deduction" id="deduction"></div>
                                </div>
                              </div>
                              
                              
                              <div class="col-lg-12">
                                <div class="col-md-3 mb-3 form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="HasEpf" name="HasEpf" value="false">
                                    <label class="form-check-label" for="HasEpf">
                                        <label for="HasEpf">Has EPF</label>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="HasSocso" name="HasSocso" value="false">
                                    <label class="form-check-label" for="HasSocso">
                                        <label for="HasSocso">Has SOCSO</label>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="HasEis" name="HasEis" value="false">
                                    <label class="form-check-label" for="HasEis">
                                        <label for="HasEis">Has EIS</label>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="HasIncomeTax" name="HasIncomeTax" value="false">
                                    <label class="form-check-label" for="HasIncomeTax">
                                        <label for="HasIncomeTax">Has INCOME TAX PCB</label>
                                    </label>
                                </div>
                            </div>
                              </div>

                              <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="firstname" class="form-label">Paying Account</label>
                                  <div class="form-control-wrap">
                                        <select class="form-control valid" data-val="true" data-val-required="The Paying Account field is required." id="PayingAccountId" name="PayingAccount" aria-required="true" aria-invalid="false" aria-describedby="PayingAccountId-error">
                                            <option value="Cash At Bank - Maybank">Cash At Bank - Maybank</option>
                                            <option value="Cash In Hand">Cash In Hand</option>
                                            <option value="Payment Gateway - BillPlz Sdn Bhd">Payment Gateway - BillPlz Sdn Bhd</option>
                                            <option value="Payment Gateway - Ipay88">Payment Gateway - Ipay88</option>
                                            <option value="Public Bank">Public Bank</option>
                                        </select>
                                    </div>
                                </div>
                              </div>
                              
                              <div class="col-lg-9">
                                <div class="form-group">
                                  <label for="company" class="form-label">Remarks</label>
                                  <div class="form-control-wrap"><input type="text" class="form-control" id="remarks" name="remarks" placeholder="Remarks"></div>
                                </div>
                              </div>
                              
                              <div class="col-lg-12">
                                <div class="form-group">
                                  <div class="form-control-wrap"><input type="submit" class="form-control" id="Payment Submit" name="Payment Submit" placeholder="Payment Submit"></div>
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
@endsection