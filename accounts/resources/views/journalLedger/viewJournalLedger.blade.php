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
                        View Journal Entry Detail
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">View Journal Ledger</a></li>
                           <li class="breadcrumb-item active" aria-current="page">View Journal Entry Detail</li>
                        </ol>
                     </nav>
                  </div>
               </div>
            </div>
            <div class="nk-block">
               <div class="card">
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="table">
                           <thead>
                              <tr>
                                 <th>Description</th>
                                 <th>Transaction Date</th>
                                 <th>Supporting Document Date</th>
                                 <th>Total Amount</th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr>
                                 <td>{{$ledger->description}}</td>
                                 <td>{{$ledger->transactionDate}}</td>
                                 <td>{{$ledger->supportingDocumentDate}}</td>
                                 <td>{{number_format($ledger->total_amount,2)}}</td>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                     <h3>JOURNAL ENTRIES</h3>
                     <div class="table-responsive">
                        <table class="table">
                           <thead>
                              <tr>
                                 <th>Account Name</th>
                                 <th>Debit</th>
                                 <th>Credit</th>
                              </tr>
                           </thead>
                           <tbody>
                           @foreach($ledgerItems as $ledgerItem)
                               <tr>
                                   <td>{{$ledgerItem->accountName}}</td>
                                   @if($ledgerItem->credit==0)
                                       <td>RM {{number_format($ledgerItem->debit,2)}}</td>
                                       <td>RM {{number_format(0,2)}}</td>
                                   @else
                                       <td>RM {{number_format(0,2)}}</td>
                                       <td>RM {{number_format($ledgerItem->credit,2)}}</td>
                                   @endif


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
   </div>
</div>
@endsection
