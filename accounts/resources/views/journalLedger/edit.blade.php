@extends('layouts.main')
@section('content')
    <div class="nk-content sifu-view-page">
        <div class="fluid-container">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head">
                        <div class="nk-block-head-between flex-wrap gap g-2">
                            <div class="nk-block-head-content">
                                <h2 class="nk-block-title">Edit Journal Ledger</h2>
                                <nav>
                                    <ol class="breadcrumb breadcrumb-arrow mb-0">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item"><a href="#">Journal Ledger</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Edit Journal Ledger</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="nk-block">
                        <div class="card card-gutter-md">
                            <div class="card-body">
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
                                    <form method="POST" action="{{route('updateJournalLedger',$ledger->id)}}">
                                        @csrf
                                        <div class="row g-3">
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="firstname" class="form-label">Transaction Date</label>
                                                    <div class="form-control-wrap"><input type="date"
                                                                                          value="{{ \Carbon\Carbon::parse($ledger->transactionDate)->format('Y-m-d')}}"
                                                                                          name="transactionDate"
                                                                                          class="form-control"
                                                                                          id="reference"></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="firstname" class="form-label">Supporting Document
                                                        Date</label>
                                                    <div class="form-control-wrap"><input type="date"
                                                                                          value="{{ \Carbon\Carbon::parse($ledger->transactionDate)->format('Y-m-d')}}"
                                                                                          name="supportingDocumentDate"
                                                                                          class="form-control"
                                                                                          id="reference"></div>
                                                </div>
                                            </div>

                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="firstname" class="form-label">Supporting
                                                        Document</label>
                                                    <div class="form-control-wrap"><input type="file"
                                                                                          name="supportingDocument"
                                                                                          class="form-control"
                                                                                          id="reference"></div>
                                                </div>
                                            </div>
                                           

                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="firstname" class="form-label">Description</label>
                                                    <div class="form-control-wrap"><textarea name="description"
                                                                                             class="form-control">{{$ledger->description}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="nk-block-head">
                                                    <div class="nk-block-head-between flex-wrap">
                                                        <div class="nk-block-head-content">
                                                            <h3 class="nk-block-title">CHART OF ACCOUNTS</h3>
                                                        </div>
                                                        <div class="nk-block-head-content pt-5">
                                                            <ul class="d-flex">
                                                                <li>
                                                                    <button class="btn btn-md btn-success" id="addBtn"
                                                                            type="button">Add New Row
                                                                    </button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                        <tr>
                                                            <th>Chart of Account</th>
                                                            <th>Debit</th>
                                                            <th>Credit</th>
                                                            <th></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="tbody">
                                                        @foreach($ledgerItems as $key=>$ledgerItem)

                                                            <tr id="R.{{$key+1}}">
                                                                <td class="row-index text-center">
                                                                    <select class="form-control js-select"
                                                                            data-search="true" data-sort="false"
                                                                            name="chartOfAccounts[]">
                                                                        @foreach($chartOfAccounts as $account)
                                                                            <option
                                                                                {{$account->id==$ledgerItem->account_id?"selected":""}}
                                                                                value="{{$account->id}}"> {{$account->name}}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td class="row-index text-center"><input value="{{$ledgerItem->debit}}"
                                                                                                         type="text"
                                                                                                         class="form-control"
                                                                                                         name="debit[]"/>
                                                                </td>
                                                                <td class="row-index text-center"><input value="{{$ledgerItem->credit}}"
                                                                                                         type="text"
                                                                                                         class="form-control"
                                                                                                         name="credit[]"/>
                                                                </td>
                                                                <td class="text-center">
                                                                    <button style="background-color:#2e314a; color:#fff"
                                                                            class="btn remove" type="button">Remove
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-lg-2">
                                                    <button class="btn btn-primary" type="submit">Submit</button>
                                                </div>
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
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"
            integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            var rowIdx = '{{count($ledgerItems)}}';
            $('#addBtn').on('click', function () {
                $('#tbody').append(`<tr id="R${++rowIdx}">
         <td class="row-index text-center"><select class="form-control js-select" data-search="true" data-sort="false" name="chartOfAccounts[]" id="account_id${rowIdx}">@foreach($chartOfAccounts as $account)<option value="{{$account->id}}"> {{$account->name}}</option>@endforeach<select></td>
         <td class="row-index text-center"><input value="0" type="text" class="form-control" id="debit{rowIdx}" name="debit[]"/></td>
         <td class="row-index text-center"><input value="0" type="text" class="form-control" id="credit{rowIdx}" name="credit[]"/></td>
         <td class="text-center"><button style="background-color:#2e314a; color:#fff" class="btn remove" type="button">Remove</button></td>
          </tr>`);
            });
            $('#tbody').on('click', '.remove', function () {
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


    </script>
@endsection
