@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
   <div class="fluid-container">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head">
               <div class="nk-block-head-between flex-wrap gap g-2">
                  <div class="nk-block-head-content">
                     <h2 class="nk-block-title">Expenditures</h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Cash Flow</a></li>
                           <li class="breadcrumb-item active" aria-current="page">>Expenditures List</li>
                        </ol>
                     </nav>
                  </div>
                  <div class="nk-block-head-content">
                     <ul class="d-flex">
                        <li><a href="{{route('addExpenditure')}}" class="btn btn-md d-md-none btn-primary"><em class="icon ni ni-plus"></em><span>Add Expenditure</span></a></li>
                        <li><a href="{{route('addExpenditure')}}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Add Expenditure</span></a></li>
                     </ul>
                  </div>
               </div>
            </div>
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
                  <table class="datatable-init table" data-nk-container="table-responsive">
                     <thead>
                        <tr>
                           <th>Occurance Date</th>
                           <th>Account</th>
                           <th>Description</th>
                           <th>Quantity</th>
                           <th>Cost Per Unit</th>
                           <th>Total</th>
                           <th>Payment Date</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($expenditures as $rowExpenditures)
                        <tr>
                           <td>{{$rowExpenditures->occuranceDate}}</td>
                           <td>
                              @php
                              $chartOfAccounts = DB::table('accounts')->where('id','=',$rowExpenditures->accountId)->first();
                              echo $chartOfAccounts->name;
                              @endphp
                           </td>
                           <td>{{$rowExpenditures->description}}</td>
                           <td>{{$rowExpenditures->quantity}}</td>
                           <td>{{$rowExpenditures->costPerUnit}}</td>
                           <td>{{$rowExpenditures->quantity * $rowExpenditures->costPerUnit}}</td>
                           <td>{{$rowExpenditures->paymentDate}}</span></td>
                           <td>
                              <a class="dtable-cbtn bt-delete dtb-tooltip" dtb-tooltip="Delete" onclick="return confirm('Are you sure you want to delete?');" href="{{route('deleteExpenditures',$rowExpenditures->id)}}"><em class="icon ni ni-trash"></em></a>
                           </td>
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