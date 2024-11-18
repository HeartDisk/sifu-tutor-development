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
                        Customer List
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Customer List</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Add Customer</li>
                        </ol>
                     </nav>
                  </div>
                  <div class="nk-block-head-content">
                     <ul class="d-flex">
                        @can("customer-add")
                        <li><a href="{{route('addCustomer')}}" class="btn btn-md d-md-none btn-primary"><em class="icon ni ni-plus"></em><span>Add</span></a></li>
                        <li><a href="{{route('addCustomer')}}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Add Customer</span></a></li>
                        @endcan
                     </ul>
                  </div>
               </div>
            </div>
         </div>
         <div class="nk-block">
            <div class="card overflow-hidden">
               <div class="card-body">
                  <form action="{{route('Customers')}}" method="GET">
                     <div class="row justify-content-between tableper-row">
                        <input type="hidden" name="customerSearch" value="1"/>
                        <div class="col-md-5">
                           <div class="input-group input-group-md">
                              <label class="input-group-text" for="inputGroupSelect01">Status</label>
                              <!--<select name="status" class="form-select" id="inputGroupSelect01">-->
                              <!--   <option value="{{ Request::get('status')}}" > {{ Request::get('status')}}</option>-->
                              <!--   <option value="Active">Active</option>-->
                              <!--   <option value="Inactive">Inactive</option>-->
                              <!--   <option value="Pending">Pending</option>-->
                              <!--</select>-->
                              <select name="status" class="status form-select" id="inputGroupSelect01">
                                <option value="Active" {{ Request::get('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ Request::get('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="Pending" {{ Request::get('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            </select>

                           </div>
                        </div>
                        <div class="col-md-5">
                           <div class="input-group input-group-md">
                              <span class="input-group-text" id="inputGroup-sizing-sm">Search</span>
                              <input name="search" type="text" class="form-control" placeholder="Customer Name" value="{{ Request::get('search')}}" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
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
                           <th>Customer ID</th>
                           <th>Commitment Fee Status</th>
                           <th>Full Name</th>
                           <th>Phone Number</th>
                           <th>Email</th>
                           <th>Status</th>
                           <th>Commitment Fee Attachment</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php $number = 1;
                        @endphp
                        @foreach($customers as $row)
                        @php
                        // dd($row);
                        $commitmentFee=DB::table('customer_commitment_fees')->where('customer_id','=',$row->id)->first();
                        $jobticketCheck = DB::table('job_tickets')
                        ->join('students', 'job_tickets.student_id', '=', 'students.id')
                        ->join('customers', 'customers.id', '=', 'students.customer_id')
                        ->where('customers.id', $row->id)
                        ->whereDate('job_tickets.created_at', '=', now()->subDays(90)->toDateString())
                        ->get();
                        @endphp
                        @if($row->is_deleted == 0)
                        <tr>
                           <td>{{$number++}}</td>
                           <td>{{$row->uid}}</td>
                           @if($commitmentFee!=null)
                           <td>Paid</td>
                           @else
                           <td>Unpaid</td>
                           @endif
                           <td>{{$row->full_name}}</td>
                           <td>{{$row->phone}}</td>
                           <td>{{$row->email}}</td>
                           <td>
                              @if($commitmentFee == null)
                              <p class='dtable-status-pending'>Unregistered</p>
                              @elseif($commitmentFee != null && $jobticketCheck == null)
                              <p class='dtable-status-inactive'>Inactive</p>
                              @elseif($commitmentFee != null && $jobticketCheck != null)
                              <p class='dtable-status-active'>Active</p>
                              @endif
                           </td>
                           @if($commitmentFee!=null)
                           <td>
                              <a class='dtable-status-viewfile' data-lightbox="image" target="_blank" href="{{url("/public/customerCommitmentFee")."/".$commitmentFee->payment_attachment}}">
                              View File
                              </a>
                           </td>
                           @else
                           <td></td>
                           @endif
                           <td>
                              <a class="dtable-cbtn bt-dashboard dtb-tooltip" dtb-tooltip="Customer Dashboard" href="{{route('customerDashboard',$row->id)}}"><i class="fa fa-dashboard"></i> </a>
                              @can("customer-detail")
                              <a class="dtable-cbtn bt-view dtb-tooltip" dtb-tooltip="View Parent Detail" href="{{route('viewCustomer',$row->id)}}"><i class="fa fa-eye"></i> </a>
                              @endcan
                              @can("customer-edit")
                              <a class="dtable-cbtn bt-edit dtb-tooltip" dtb-tooltip="Edit Parent" href="{{route('editCustomer',$row->id)}}"><i class="fa fa-edit"></i> </a>
                              @endcan
                              @can("customer-delete")
                              <a class="dtable-cbtn bt-delete dtb-tooltip" dtb-tooltip="Delete Parent" onclick="return confirm('Are you sure you want to delete this Parrent?');" href="{{route('deleteCustomer',$row->id)}}"><i class="danger fa fa-trash"></i> </a>
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
@endsection