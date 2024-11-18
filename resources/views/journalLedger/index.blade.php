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
               JOURNAL LEDGER</h1>
               <nav>
                 <ol class="breadcrumb breadcrumb-arrow mb-0">
                   <li class="breadcrumb-item"><a href="#">Home</a></li>
                   <li class="breadcrumb-item"><a href="#">Journal Ledger List</a></li>
                   <li class="breadcrumb-item active" aria-current="page">Journal Ledger</li>
                 </ol>
               </nav>
             </div>
             <div class="nk-block-head-content">
               <ul class="d-flex">
                 <li><a href="{{route('addJournalLedger')}}" class="btn btn-md d-md-none btn-primary"><em class="icon ni ni-plus"></em><span>Add Journal Ledger</span></a></li>
                 <li><a href="{{route('addJournalLedger')}}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Add Journal Ledger</span></a></li>
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
                     <th>S.No</th>
                     <th>Description</th>
                     <th>Amount</th>
                     <th>Transaction Date</th>
                     <th>Supporting Document Date</th>
                     <th>Supporting Document</th>
                     <th>Action</th>
                 </tr>
               </thead>
               <tbody>
                   @foreach($ledgers as $key=>$rowLedger)
                       <tr>
                           <td>{{$key+1}}</td>
                           <td>{{$rowLedger->description}}</td>
                           <td>{{number_format($rowLedger->total_amount,2)}}</td>
                           <td>{{ \Carbon\Carbon::parse($rowLedger->transactionDate)->format('D, d M Y') }}</td>
                           <td>{{ \Carbon\Carbon::parse($rowLedger->supportingDocumentDate)->format('D, d M Y') }}</td>
                           @if($rowLedger->attachment!=null)
                               <td><a target="_blank" href="{{url("public/supportingDocument"."/".$rowLedger->attachment)}}">View Document</a> </td>
                           @else
                               <td> </td>

                           @endif
                           <td>
                               <a class="dtable-cbtn bt-view dtb-tooltip" dtb-tooltip="View Journal Ledger" href="{{route('viewJournalLedger',$rowLedger->id)}}"><i class="fa fa-eye"></i></a>
                              <a class="dtable-cbtn bt-edit dtb-tooltip"  dtb-tooltip="Edit Journal Ledger"  href="{{route('editJournalLedger',$rowLedger->id)}}"><i class="fa fa-edit"></i></a>
                               <a class="dtable-cbtn bt-delete dtb-tooltip" dtb-tooltip="Delete Journal Ledger" onclick="return confirm('Are you sure you want to delete?');" href="{{route('deleteJournalLedger',$rowLedger->id)}}"><i class="fa fa-trash"></i></a>
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
