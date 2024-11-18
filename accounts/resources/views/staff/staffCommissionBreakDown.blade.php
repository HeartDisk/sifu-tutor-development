@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
   <div class="nk-content-inner">
      <div class="nk-content-body">
         <div class="nk-block-head">
            <div class="nk-block-head-between flex-wrap gap g-2">
               <div class="nk-block-head-content">
                  <h2 class="nk-block-title">
                     Commission Breakdown
                  </h2>
                  <nav>
                     <ol class="breadcrumb breadcrumb-arrow mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Student PIC Commission</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Commission Breakdown</li>
                     </ol>
                  </nav>
               </div>
            </div>
         </div>
         <div class="nk-block">
            <div class="card overflow-hidden">
               <div class="card-body">
                  <form method="post" enctype="multipart/form-data"
                  action="{{url("/StaffPayments/ViewCommissionsByMonth")}}">
                  @csrf
                  <div class="row justify-content-between tableper-row">
                     <div class="col-md-6 sstab-name">
                      <h2>Staff Name: {{$staff->full_name}}</h2>
                    </div>
                     <div class="col-md-4">
                        <div class="input-group input-group-md">
                           <label class="input-group-text" for="inputGroupSelect01">Select Month</label>
                           <select name="month" class="form-control">
                              <option value="">Please select month</option>
                              @php
                              $selectedMonth = isset($selectedMonth) ? $selectedMonth : null;
                              for ($month = 1; $month <= 12; $month++) {
                              $monthName = date('F', mktime(0, 0, 0, $month, 1));
                              $selectedAttribute = ($selectedMonth == $monthName) ? 'selected' : '';
                              echo "
                              <option value=\"$monthName\" $selectedAttribute>$monthName</option>
                              ";
                              }
                              @endphp
                           </select>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="input-group input-group-md">
                           <input type="submit" class="btn btn-primary" aria-label="Sizing example input" value="Search" aria-describedby="inputGroup-sizing-sm">
                        </div>
                     </div>
                  </div>
                  </form>
                  <table class="datatable-init table" data-nk-container="table-responsive">
                     <thead>
                        <tr>
                           <th>S.No</th>
                           <th>Invoice No</th>
                           <th>Invoice Date</th>
                           <th>Payment Date</th>
                           <th>Customer</th>
                           <th>Class Type</th>
                           <th>Invoice Amount</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($data as $key=>$rows)
                        <tr>
                           <td>{{$key+1}}</td>
                           <td>{{"INV".$rows->invoice_id}}</td>
                           <td>{{$rows->created_at}}</td>
                           <td>{{$rows->paymentDate}}</td>
                           <td>{{$rows->customer_name}}</td>
                           <td>{{$rows->mode}}</td>
                           <td>{{$rows->invoiceTotal}}</td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection