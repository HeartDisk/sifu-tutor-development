@extends('layouts.main')

@section('content')

        <div class="nk-content">
            <div class="fluid-container">
              <div class="nk-content-inner">
                <div class="nk-content-body">
                  <div class="nk-block-head">
                    <div class="nk-block-head">
                      <div class="nk-block-head-between flex-wrap gap g-2 align-items-center">
                        <div class="nk-block-head-content">
                          <div class="d-flex flex-column flex-md-row align-items-md-center">
                            <div class="mt-3 mt-md-0 ms-md-3">
                              <h3 class="title mb-1">Add Chart Of Accounts</h3>
                            </div>
                          </div>
                        </div>

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
                          <form method="POST" action="{{route('submitChartOfAccounts')}}">
                              @csrf
                            <div class="row g-3">
                            <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="firstname" class="form-label">Account ID</label>
                                  <div class="form-control-wrap"><input type="text" class="form-control" readonly name="accountID" value="@php echo 'AC-'.date('dhis'); @endphp" id="tutorID"></div>
                                </div>
                              </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="firstname" class="form-label">Account Code</label>
                                  <div class="form-control-wrap"><input type="text" class="form-control" name="account_code" id="account_code"></div>
                                </div>
                              </div>

                               <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="firstname" class="form-label">Account Name</label>
                                  <div class="form-control-wrap"><input type="text" class="form-control" name="account_name" id="account_name"></div>
                                </div>
                              </div>

                               <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="country" class="form-label">Account Type</label>
                                  <div class="form-control-wrap">
                                    <select class="js-select" id="account_type" data-search="true" name="account_type" data-sort="false">
                                     @foreach($chartofaccounts as $chartofaccount)
                                            <option value="{{$chartofaccount->id}}">{{$chartofaccount->name}}</option>
                                        @endforeach
                                    </select>
                                  </div>
                                </div>
                              </div>

                               <div class="col-lg-3" style="margin-top:40px;">
                                <div class="form-group" style="display:flex;">
                                  <label for="firstname" class="form-label" style="width:50%;">Is a Cash Source</label>
                                  <div class="form-control-wrap"><input type="checkbox"  name="cash_source" id="cash_source"></div>
                                </div>
                              </div>

                              <div class="col-lg-9">
                                <div class="form-group">
                                  <label for="firstname" class="form-label">Description</label>
                                  <div class="form-control-wrap"><input type="text" class="form-control" name="description" id="description"></div>
                                </div>
                              </div>


                            </div>
                            <div class="col-lg-12"><button class="btn btn-primary" type="submit">Submit</button></div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

@endsection
