@extends('layouts.main')

@section('content')

<style>
    .progress .progress-bar{
        height:4px;
    }
    .row-details{
        border-bottom:1px solid grey;
    }
    
</style>

<div style="padding-top:80px;" class="container">
            <div class="fluid-container">
              <div class="nk-content-inner">
                <div class="nk-content-body">
                  <div class="nk-block-head">
                    <div class="nk-block-head-between flex-wrap gap g-2">
                      <div class="nk-block-head-content">
                        <h2 class="nk-block-title">
                        View Journal Entry Detail</h1>
                        <nav>
                          <ol class="breadcrumb breadcrumb-arrow mb-0">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active"><a href="#">View Journal Entry Detail</a></li>
                          </ol>
                        </nav>
                      </div>
                      <div class="nk-block-head-content">
                        <ul class="d-flex">
                          <!--<li><a href="{{route('addStudent')}}" class="btn btn-md d-md-none btn-primary"><em class="icon ni ni-plus"></em><span>Add</span></a></li>-->
                          <!--<li><a href="{{route('addStudent')}}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Add Student</span></a></li>-->
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="nk-block">
                    <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-12">
                                <h2>{{$viewLedgerEntry->ledgerDescription}}</h2>
                            </div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-sm bg-gradient" role="progressbar" aria-valuenow="41.66" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                        </div>
                    </div>
        
                    <div class="card-body">
                <div class="row row-details">
                    <div class="col-md-3 details-item">
                        <p class="item-title">Description</p>
                        <p><strong>{{$viewLedgerEntry->ledgerDescription}}</strong></p>
                    </div>

                    <div class="col-md-3 details-item">
                        <p class="item-title">Transaction Date</p>
                        <p><strong>{{$viewLedgerEntry->transactionDate}}</strong></p>
                    </div>

                    <div class="col-md-3 details-item">
                        <p class="item-title">Supporting Document Date</p>
                        <p><strong>{{$viewLedgerEntry->supportingDocumentDate}}</strong></p>
                    </div>
                </div>
                
                <h3>Journal Entries</h3>
                    <div class="row row-details">
                        <div class="col-md-3 details-item">
                            <p class="item-title">Account Name</p>
                            <p><strong>{{$viewLedgerEntry->accountName}}</strong></p>
                        </div>
                        <div class="col-md-3 details-item">
                            <p class="item-title">Debit</p>
                            <p><strong>RM {{$viewLedgerEntry->debit}}.00</strong></p>
                        </div>
                        <div class="col-md-3 details-item">
                            <p class="item-title">Credit</p>
                            <p><strong>RM {{$viewLedgerEntry->credit}}.00</strong></p>
                        </div>
                    </div>
                    <div class="row row-details">
                        <div class="col-md-3 details-item">
                            <p class="item-title">Account Name</p>
                            
                            <p><strong>{{$viewLedgerEntryCredit->accountName}}</strong></p>
                        </div>
                        <div class="col-md-3 details-item">
                            <p class="item-title">Debit</p>
                            <p><strong>RM {{$viewLedgerEntryCredit->debit}}.00</strong></p>
                        </div>
                        <div class="col-md-3 details-item">
                            <p class="item-title">Credit</p>
                            <p><strong>RM {{$viewLedgerEntryCredit->credit}}.00</strong></p>
                        </div>
                    </div>
                <div class="row row-details pb-5">
                    <div class="col-md-3">
                        <a class="btn btn-light waves-effect waves-light" href="{{url('journalLedger')}}">Back</a>
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