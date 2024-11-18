@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
   <div class="nk-content-inner">
      <div class="nk-content-body">
         <div class="nk-block-head">
            <div class="nk-block-head-between flex-wrap gap g-2">
               <div class="nk-block-head-content">
                  <h2 class="nk-block-title">
                     Student PIC Commission List
                  </h2>
                  <nav>
                     <ol class="breadcrumb breadcrumb-arrow mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Staff</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Student PIC Commission List</li>
                     </ol>
                  </nav>
               </div>
            </div>
         </div>
         <div class="nk-block">
            <div class="card overflow-hidden">
               <div class="card-body">
                   <form method="POST" enctype="multipart/form-data" action="{{ url('/StaffPayments/ViewCommissionsByMonth') }}">
                       @csrf
                       <div class="row justify-content-between tableper-row">
                           <div class="col-md-3"></div>
                           <div class="col-md-4">
                               <div class="input-group input-group-md">
                                   <label class="input-group-text" for="inputGroupSelect01">Select Month</label>
                                   <select name="month" class="form-control">
                                       <option value="">Please select month</option>
                                       @php
                                           $selectedMonth = isset($selectedMonth) ? $selectedMonth : null;
                                           for ($month = 1; $month <= 12; $month++) {
                                               $monthName = date('F', mktime(0, 0, 0, $month, 1));
                                               $selectedAttribute = ($selectedMonth == $month) ? 'selected' : '';
                                               echo "<option value=\"$month\" $selectedAttribute>$monthName</option>";
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
                           <div class="col-md-3"></div>
                       </div>
                   </form>

                   <table class="datatable-init table" data-nk-container="table-responsive">
                     <thead>
                        <tr>
                           <th>S.No</th>
                           <th>Staff</th>
                           <th>Month - Year</th>
                           <th>Total Invoices</th>
                           <th>Total Invoices Amount</th>
                           <th>Total Commissions</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($data as $key=>$rows)
                        @php
                        $staff_id=DB::table("staffs")->where("full_name",$rows->staff_name)->first();
                        @endphp

                        @if($staff_id)
                        <tr>
                           <td>{{$key+1}}</td>
                           <td>{{$rows->staff_name}}</td>
                           <td>{{$rows->month_name}}</td>
                           <td>{{$rows->total_job_tickets}}</td>
                           <td>RM {{$rows->total_amount}}</td>
                           <td>RM {{$rows->bonus}}</td>
                           <td><a class="dtable-cbtn bt-view dtb-tooltip" dtb-tooltip="View Detail" href="{{url("/ViewCommissionsBreakDown")."/".$staff_id->id}}"><i class="fa fa-eye"></i> </a></td>
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
@endsection
