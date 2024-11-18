@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
  <div class="fluid-container">
    <div class="nk-content-inner">
       <div class="nk-content-body">
          <div class="nk-block-head">
             <div class="nk-block-head-between flex-wrap gap g-2">
                <div class="nk-block-head-content">
                   <h2 class="nk-block-title">Tutor Payment</h2>
                   <nav>
                      <ol class="breadcrumb breadcrumb-arrow mb-0">
                         <li class="breadcrumb-item"><a href="#">Home</a></li>
                         <li class="breadcrumb-item"><a href="#">Tutor List</a></li>
                         <li class="breadcrumb-item active" aria-current="page">Tutor Payment</li>
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
                         <div class="row">
                            <div class="col-lg-3">
                               <div class="form-group">
                                  <label for="firstname" class="form-label">Tutor ID</label>
                                  <div class="form-control-wrap"><input type="text" class="form-control" required  name="tutorID" readonly value="{{$tutor->uid}}" id="tutorID"></div>
                               </div>
                            </div>
                            <div class="col-lg-12">
                               <h3>PAYMENT</h3>
                            </div>
                            <div class="col-lg-3">
                               <div class="form-group">
                                  <label for="firstname" class="form-label">Payment Date</label>
                                  <div class="form-control-wrap">
                                     <input type="date" class="form-control" required id="paymentDate" name="paymentDate" placeholder="Payment Date" value="<?php echo date('Y-m-d'); ?>">
                                  </div>
                               </div>
                            </div>
                            <div class="col-lg-3">
                               <div class="form-group">
                                  <label for="firstname" class="form-label">Comission Month</label>
                                  <div class="form-control-wrap">
                                     <select class="js-select" id="salaryMonth" data-search="true" name="salaryMonth" data-sort="false">
                                        <option value="January" <?php echo date('F') === 'January' ? 'selected' : ''; ?>>January</option>
                                        <option value="February" <?php echo date('F') === 'February' ? 'selected' : ''; ?>>February</option>
                                        <option value="March" <?php echo date('F') === 'March' ? 'selected' : ''; ?>>March</option>
                                        <option value="April" <?php echo date('F') === 'April' ? 'selected' : ''; ?>>April</option>
                                        <option value="May" <?php echo date('F') === 'May' ? 'selected' : ''; ?>>May</option>
                                        <option value="June" <?php echo date('F') === 'June' ? 'selected' : ''; ?>>June</option>
                                        <option value="July" <?php echo date('F') === 'July' ? 'selected' : ''; ?>>July</option>
                                        <option value="August" <?php echo date('F') === 'August' ? 'selected' : ''; ?>>August</option>
                                        <option value="September" <?php echo date('F') === 'September' ? 'selected' : ''; ?>>September</option>
                                        <option value="October" <?php echo date('F') === 'October' ? 'selected' : ''; ?>>October</option>
                                        <option value="November" <?php echo date('F') === 'November' ? 'selected' : ''; ?>>November</option>
                                        <option value="December" <?php echo date('F') === 'December' ? 'selected' : ''; ?>>December</option>
                                     </select>
                                  </div>
                               </div>
                            </div>
                            <div class="col-lg-3">
                               <div class="form-group">
                                  <label for="firstname" class="form-label">Comission Year</label>
                                  <div class="form-control-wrap">
                                     <select class="js-select" id="salaryYear" data-search="true" name="salaryYear" data-sort="false">
                                     <?php
                                        $currentYear = date('Y');
                                        for ($year = $currentYear - 5; $year <= $currentYear + 5; $year++) {
                                            echo "<option value='$year'";
                                            if ($year == $currentYear) {
                                                echo " selected";
                                            }
                                            echo ">$year</option>";
                                        }
                                        ?>
                                     </select>
                                  </div>
                               </div>
                            </div>
                            <div class="col-lg-3">
                               <div class="form-group">
                                  <label for="firstname" class="form-label">Paying Account</label>
                                  <div class="form-control-wrap">
                                     <select class="form-control valid" data-val="true" data-val-required="The Paying Account field is required." id="PayingAccountId" name="payingAccount" aria-required="true" aria-invalid="false" aria-describedby="PayingAccountId-error">
                                        <option value="Cash At Bank - Maybank">Cash At Bank - Maybank</option>
                                        <option value="Cash In Hand">Cash In Hand</option>
                                        <option value="Payment Gateway - BillPlz Sdn Bhd">Payment Gateway - BillPlz Sdn Bhd</option>
                                        <option value="Payment Gateway - Ipay88">Payment Gateway - Ipay88</option>
                                        <option value="Public Bank">Public Bank</option>
                                     </select>
                                  </div>
                               </div>
                            </div>
                            <div class="col-lg-6">
                               <div class="form-group">
                                  <label for="company" class="form-label">Paying Amount</label>
                                  <div class="form-control-wrap"><input type="text" readonly class="form-control" required id="payingAmount" name="payingAmount" value="{{number_format($totalCommission,2)}}"></div>
                               </div>
                            </div>
                            <div class="col-lg-6">
                               <div class="form-group">
                                  <label for="company" class="form-label">Remarks</label>
                                  <div class="form-control-wrap"><input type="text" class="form-control" id="remarks" name="remark" placeholder="Remarks"></div>
                               </div>
                            </div>
                            <div class="col-lg-12">
                               <h3>PAYMENT BREAK DOWN</h3>
                               <div class="table-responsive">
                                  <table class="table">
                                     <thead>
                                        <tr>
                                           <th>Student</th>
                                           <th>Attendance Verify</th>
                                           <th>Subject</th>
                                           <th>Date</th>
                                           <th>Attended Duration(Hrs)</th>
                                           <th>Invoice Duration</th>
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
                                              {{$studentDetail->full_name}} - {{$studentDetail->student_id}}
                                           </td>
                                           <td>
                                              {{$rowSS->status=="attended"?"Yes":"No"}}
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
                                           <td>
                                              @php
                                              echo $rowSS->totalTime;
                                              @endphp
                                              @php
                                              $count = number_format((float)$rowSS->totalTime, 2, '.', '');
                                              @endphp
                                           </td>
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
                                           <th></th>
                                           <th>Total</th>
                                           <th>{{$attendedDuration}}</th>
                                           <th>RM {{number_format($totalCommission,2)}}</th>
                                        </tr>
                                     </tfoot>
                                  </table>
                               </div>
                            </div>
                            <div class="col-lg-12">
                               <div class="nk-block-head">
                                  <div class="nk-block-head-between flex-wrap">
                                     <div class="nk-block-head-content">
                                        <h3 class="nk-block-title">ADDITIONALS</h3>
                                     </div>
                                     <div class="nk-block-head-content">
                                        <ul class="d-flex">
                                           <li><button class="btn btn-md btn-success" id="addBtnAdditional" type="button">Add Additionals</button></li>
                                        </ul>
                                     </div>
                                  </div>
                               </div>
                               <div class="table-responsive">
                                  <table class="table">
                                     <thead>
                                        <tr>
                                           <th>Description</th>
                                           <th>Amount</th>
                                           <th></th>
                                        </tr>
                                     </thead>
                                     <tbody id="tbodyAdditional">
                                     </tbody>
                                  </table>
                               </div>
                            </div>
                            <div class="col-lg-12">
                               <div class="nk-block-head">
                                  <div class="nk-block-head-between flex-wrap">
                                     <div class="nk-block-head-content">
                                        <h3 class="nk-block-title">DEDUCTIONS</h3>
                                     </div>
                                     <div class="nk-block-head-content">
                                        <ul class="d-flex">
                                           <li><button class="btn btn-success" id="addBtnDeduction" type="button">Add Deduction</button></li>
                                        </ul>
                                     </div>
                                  </div>
                               </div>
                               <script></script>
                               <div class="table-responsive">
                                  <table class="table">
                                     <thead>
                                        <tr>
                                           <th>Description</th>
                                           <th>Amount</th>
                                           <th></th>
                                        </tr>
                                     </thead>
                                     <tbody id="tbodyDeduction">
                                     </tbody>
                                  </table>
                               </div>
                            </div>
                            @if($totalCommission>0)
                            <div class="col-lg-2">
                               <input type="submit" class="btn btn-success" id="Payment Submit" name="Payment Submit" placeholder="Payment Submit">
                            </div>
                            @endif
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


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script>
  $(document).ready(function () {
    var rowIdx = 0;
    $('#addBtnDeduction').on('click', function () {
    $('#tbodyDeduction').append(`<tr id="R${++rowIdx}">
          <td><textarea class="form-control sifu-descr" id="description${rowIdx}" name="deductionDescription[]"></textarea></td>
          <td><input type="text" class="form-control" onfocusout="deductionFocus_Out${rowIdx}(event)" id="deductionAmount${rowIdx}" name="deductionAmount[]"/></td>
          <td><button class="btn remove sifu-remove-btn" type="button">Remove</button></td>
           </tr>`);
    });

    $('#tbodyDeduction').on('click', '.remove', function () {
      var child = $(this).closest('tr').nextAll();
      child.each(function () {
        var id = $(this).attr('id');
        var idx = $(this).children('.row-index').children('p');
        var dig = parseInt(id.substring(1));
        idx.html(`Row ${dig - 1}`);
        $(this).attr('id', `R${dig - 1}`);
      });

      $(this).closest('tr').remove();
      rowIdx--;
    });
  });

  $(document).ready(function () {
    var rowIdx = 0;
    $('#addBtnAdditional').on('click', function () {
    $('#tbodyAdditional').append(`<tr id="R${++rowIdx}">
      <td><textarea class="form-control sifu-descr" id="description${rowIdx}" name="AdditionalDescription[]"></textarea></td>
      <td><input type="text" class="form-control" onfocusout="additionalFocus_Out${rowIdx}(event)"  id="AdditionalAmount${rowIdx}" name="AdditionalAmount[]"/></td>
      <td><button class="btn remove sifu-remove-btn" type="button">Remove</button></td>
       </tr>`);
    });

    $('#tbodyAdditional').on('click', '.remove', function () {
      var child = $(this).closest('tr').nextAll();
      child.each(function () {
        var id = $(this).attr('id');
        var idx = $(this).children('.row-index').children('p');
        var dig = parseInt(id.substring(1));
        idx.html(`Row ${dig - 1}`);
        $(this).attr('id', `R${dig - 1}`);
      });
      $(this).closest('tr').remove();
      rowIdx--;
    });
        
  });
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
   document.getElementById('payingAmount').value = parseInt(payingAmount) - parseInt(deductionAmount1) ;
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