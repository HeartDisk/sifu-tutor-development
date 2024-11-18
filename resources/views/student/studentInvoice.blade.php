@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
   <div class="nk-content-inner">
      <div class="nk-content-body">
         <div class="nk-block-head">
            <div class="nk-block-head-between flex-wrap gap g-2">
               <div class="nk-block-head-content">
                  <h2 class="nk-block-title">
                     Students Invoices
                  </h2>
                  <nav>
                     <ol class="breadcrumb breadcrumb-arrow mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Customer List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Students Invoices</li>
                     </ol>
                  </nav>
               </div>
               <div class="nk-block-head-content">
                  <ul class="d-flex">
                     <li><a href="{{route('addStudentInvoice')}}" class="btn btn-md d-md-none btn-primary"><em class="icon ni ni-plus"></em><span>Add</span></a></li>
                     @can("student-invoice-add") 
                     <li><a href="{{route('addStudentInvoice')}}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Add Student Invoice</span></a></li>
                     @endcan 
                  </ul>
               </div>
            </div>
         </div>
         <div class="nk-block">
            <div class="card overflow-hidden">
              <div class="card-body">
               <form action="{{route('StudentInvoices')}}" method="GET">
                  <input type="hidden" name="studentInvoiceValue" value="1"/> 
                  <div class="row justify-content-between tableper-row">
                     <div class="col-md-4">
                        <div class="input-group input-group-md">
                           <label class="input-group-text" for="inputGroupSelect01">From</label>
                           <input type="date" class="form-control" name="fromDate"/>
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="input-group input-group-md">
                           <label class="input-group-text" for="inputGroupSelect01">To</label>
                           <input type="date" class="form-control" name="toDate"/>
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="input-group input-group-md">
                           <label class="input-group-text" for="inputGroupSelect01">Payment Status</label> 
                           <select name="status" class="form-control" id="inputGroupSelect01">
                              <option value="" selected=""> All</option>
                              <option value="Unpaid"> Unpaid</option>
                              <option value="Paid"> Paid</option>
                              <option value="Partially-paid"> Partially-paid</option>
                              <option value="Over-paid"> Over-paid</option>
                           </select>
                        </div>
                     </div>
                     <div class="col-md-3">
                        <div class="input-group input-group-md">
                           <input name="search" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" placeholder="Ref No.">
                        </div>
                     </div>
                     <div class="col-md-3">
                        <div class="input-group input-group-md">
                           <input name="studentID" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" placeholder="Student ID">
                        </div>
                     </div>
                     <div class="col-md-3">
                        <div class="input-group input-group-md">
                           <input name="fullName" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" placeholder="Full Name">
                        </div>
                     </div>
                     <div class="col-md-3">
                        <div class="input-group input-group-md">
                           <input type="submit" class="btn btn-primary" aria-label="Sizing example input" value="Search" aria-describedby="inputGroup-sizing-sm">
                        </div>
                     </div>
                  </div>
               </form>
               <table class="datatable-init table" data-nk-container="table-responsive">
                  <thead>
                     <tr>
                        <th>#</th>
                        <th>Invoice No.</th>
                        <th>Email Sent</th>
                        <th>StudentId</th>
                        <th>Fullname</th>
                        <th>Payer Name</th>
                        <th>Invoice Date</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Payment Status</th>
                        <th>Email Customer On</th>
                        <th>Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($invoices as $key=>$rowInvoice) 
                     @php $subjectDetail = DB::table('products')->where('id','=',$rowInvoice->subjectID)->first(); 
                     $studentDetail = DB::table('students')->where('id','=',$rowInvoice->studentID)->first(); 
                     $date_to_compare = date("d-m-Y", strtotime($rowInvoice->invoiceDate));
                     $flag=false;
                     $today_date = date("d-m-Y");
                     if ($today_date > $date_to_compare) 
                     {
                        $flag=true;
                     }
                     @endphp 
                     @if($rowInvoice->is_deleted == 0) 
                     <tr>
                        <td>{{$key+1}}</td>
                        <td>{{"INV-".$rowInvoice->id}}</p></td>
                        <td>{{$rowInvoice->sentEmail=="false"?"NO":"YES"}}</td>
                        <td>{{isset($studentDetail->uid)?$studentDetail->uid:""}}</td>
                        <td>{{isset($studentDetail->full_name)?$studentDetail->full_name:""}}</td>
                        <td>{{$rowInvoice->payerName}}</td>
                        <td>{{date("d-m-Y", strtotime($rowInvoice->invoiceDate))}}</td>
                        <td>RM{{$rowInvoice->invoiceTotal}}</td>
                        <td>
                           {{$flag==true?"Pending":$rowInvoice->invoice_status}}
                        </td>
                        <td>
                           <p class="dtb-status">{{$rowInvoice->status}}</p>
                        </td>
                        <td>{{$rowInvoice->payerEmail}}</td>
                        <td>
                            @if ($rowInvoice->status == 'unpaid')
                            <a class="dtable-cbtn bt-pay dtb-tooltip" dtb-tooltip="Pay Invoice" type="button" data-toggle="modal" data-target="#exampleModal{{$rowInvoice->id}}"><i class="fa fa-dollar"></i></a>
                           <!-- Modal --> 
                           <div class="modal fade dtable-modal" id="exampleModal{{$rowInvoice->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog">
                                 <div class="modal-content">
                                    <div class="modal-header">
                                       <h5 class="modal-title" id="exampleModalLabel">{{isset($studentDetail->uid)?$studentDetail->uid:""}} - Payment </h5>
                                       <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button> 
                                    </div>
                                    <div class="modal-body">
                                       <div class="fluid-container">
                                          <form method="POST" action="{{route('submitStudentInvoice')}}">
                                             @csrf <input type="hidden" name="id" value="{{$rowInvoice->id}}"/> 
                                             <div class="row g-3">
                                                <div class="col-lg-12">
                                                   <div class="form-group">
                                                      <label for="firstname" class="form-label">Amount</label> 
                                                      <div class="form-control-wrap">
                                                         <input readonly type="text" name="amount" class="form-control" value="{{$rowInvoice->invoiceTotal}}">
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-lg-12">
                                                   <div class="form-group">
                                                      <label for="firstname" class="form-label">Payment Date</label> 
                                                      <div class="form-control-wrap">
                                                         <input type="date" name="paymentDate" class="form-control">
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-lg-12">
                                                   <div class="form-group">
                                                      <label for="firstname" class="form-label">Receiving Account</label> 
                                                      <div class="form-control-wrap">
                                                         <select name="receivingAccount" class="form-control">
                                                            <option value="Cash At Bank - Maybank"> Cash At Bank - Maybank </option>
                                                            <option value="Cash In Hand"> Cash In Hand </option>
                                                            <option value="Payment Gateway - BillPlz Sdn Bhd"> Payment Gateway - BillPlz Sdn Bhd </option>
                                                            <option value="Payment Gateway - Ipay88"> Payment Gateway - Ipay88 </option>
                                                            <option value="Public Bank"> Public Bank </option>
                                                         </select>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-lg-12">
                                                   <div class="form-group">
                                                      <label for="firstname" class="form-label">Payment Attachment</label> 
                                                      <div class="form-control-wrap"> <input type="file" name="paymentAttachment" class="form-control"/> </div>
                                                   </div>
                                                </div>
                                                <div class="col-lg-12">
                                                   <div class="form-group">
                                                      <button type="submit" class="btn btn-primary">Pay Invoice </button>
                                                   </div>
                                                </div>
                                             </div>
                                          </form>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           @can("student-invoice-edit")
                           <a class="dtable-cbtn bt-edit dtb-tooltip" dtb-tooltip="Edit Invoice" href="{{route('editStudentInvoice',$rowInvoice->id)}}"><i class="fa fa-edit"></i></a>
                           @endcan 
                            @endif

                           @can("student-invoice-view")
                           <a class="dtable-cbtn bt-view dtb-tooltip" dtb-tooltip="View Invoice" href="{{route('viewStudentInvoiceById',$rowInvoice->id)}}"><i class="fa fa-eye"></i></a>
                           @endcan
                           
                           @can("student-invoice-delete")
                           <a class="dtable-cbtn bt-delete dtb-tooltip" dtb-tooltip="Delete Invoice" onclick="return confirm('Are you sure you want to delete this student Invoice?');" href="{{route('deleteStudentInvoice',$rowInvoice->id)}}"><i class="fa fa-trash"></i></a> 
                           @endcan
                        </td>
                     </tr>
                     @endif @endforeach 
                  </tbody>
               </table>
              </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection