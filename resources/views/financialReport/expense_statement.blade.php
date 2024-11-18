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
                        Expense Ledger
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Accounts Section</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Expense Ledger</li>
                        </ol>
                     </nav>
                  </div>
               </div>
            </div>
            <div class="nk-block">
               <div class="card overflow-hidden">
                  <div class="card-body">
                     <form action="{{url("/financialReport/expense_statement")}}" method="GET">
                     @csrf
                     <div class="row flex-row align-items-center justify-content-between">
                        <div class="col-md-4">
                           <div class="form-group">
                              <label class="form-label">Select Expense</label>
                              <select class="form-control selectpicker" id="account_id" name="account_id" data-live-search="true" data-live-search-style="true" title="Select customer">
                                 <option value="">Select Expense</option>
                                 @foreach($expenses as $expense)
                                 <option {{Request::get("account_id")==$expense->id?"selected":""}} value="{{$expense->id}}">{{$expense->name}}</option>
                                 @endforeach
                              </select>
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                              <label class="form-label">From Date</label>
                              <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                              <label class="form-label">To Date</label>
                              <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
                           </div>
                        </div>
                        <div class="col-md-2">
                           <div class="form-group">
                              <button type="submit" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Get Ledger</span></button>
                           </div>
                        </div>
                     </div>
                     </form>
                     @if(isset($opening_balance))
                     <div class="row headwtb">
                        <div class="col-md-12">
                           <h3>Opening Balance: {{$opening_balance}}</h3>
                        </div>
                     </div>
                     <table class="datatable-init table" id='userTable' data-nk-container="table-responsive">
                        <thead>
                           <tr>
                              <th>S.No</th>
                              <th>Date</th>
                              <th>Description</th>
                              <th>Debit</th>
                              <th>Credit</th>
                              <th>Balance</th>
                           </tr>
                        </thead>
                        <tbody>
                           @php
                           $current_balance = $opening_balance; // Initialize current balance;
                           $net_balance=0;
                           $x=1;
                           @endphp
                           @foreach($sorted_data as $key => $data)
                           <tr>
                              <td>{{ $x }}</td>
                              <td>{{ \Carbon\Carbon::createFromDate($data->created_at)->format("d-m-Y")}}</td>
                              <td>{{ $data->reference_no }}</td>
                              <td>{{ $data->type == "payment" ? $data->amount : "RM 0" }}</td>
                              <td>{{ $data->type == "receiving" ? $data->amount :"RM 0" }}</td>
                              <td>@php
                                 $amount =  (float)$data->amount;
                                 $current_balance = $data->type == "receiving" ? $current_balance + $amount : $current_balance - $amount;
                                 @endphp
                                 RM {{ number_format($current_balance, 2) }}
                              </td>
                           </tr>
                           @php
                           $x++;
                           @endphp
                           @endforeach
                        </tbody>
                        <tfoot>
                           <tr>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th><span id="total_debit">{{number_format($totalDebit,2)}}</span></th>
                              <th><span id="total_credit">{{number_format($totalCredit,2)}}</span></th>
                              <th><span id="total_balance">{{number_format(($totalCredit-$totalDebit)+$opening_balance,2)}}</span></th>
                           </tr>
                        </tfoot>
                     </table>
                     @else
                     <p>Please select Expense</p>
                     @endif
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection