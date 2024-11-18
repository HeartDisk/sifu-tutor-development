@extends('layouts.main')

@section('content')

<div class="nk-content">
            <div class="fluid-container">
              <div class="nk-content-inner">
                <div class="nk-content-body">
                  <div class="nk-block-head">
                    <div class="nk-block-head-between flex-wrap gap g-2">
                      <div class="nk-block-head-content">
                        <h2 class="nk-block-title">
                        INCOME BY PRODUCT </h1>
                        <nav>
                          <ol class="breadcrumb breadcrumb-arrow mb-0">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Financial Report</a></li>
                            <li class="breadcrumb-item active" aria-current="page">INCOME BY PRODUCT</li>
                          </ol>
                        </nav>
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
                        
                        <form action="{{ route('financialReport/incomeByProduct') }}" method="GET">
    @csrf
    <div class="row justify-content-between tableper-row">
        <div class="col-md-3">
            <div class="input-group input-group-md">
                <label class="input-group-text" for="SelectedMonth">Month</label>
               <select class="form-control" id="SelectedMonth" name="SelectedMonth">
                    <option value="13">All</option>
                    <option value="1" @if($selectedMonth == '1') selected @endif>January</option>
                    <option value="2" @if($selectedMonth == '2') selected @endif>February</option>
                    <option value="3" @if($selectedMonth == '3') selected @endif>March</option>
                    <option value="4" @if($selectedMonth == '4') selected @endif>April</option>
                    <option value="5" @if($selectedMonth == '5') selected @endif>May</option>
                    <option value="6" @if($selectedMonth == '6') selected @endif>June</option>
                    <option value="7" @if($selectedMonth == '7') selected @endif>July</option>
                    <option value="8" @if($selectedMonth == '8') selected @endif>August</option>
                    <option value="9" @if($selectedMonth == '9') selected @endif>September</option>
                    <option value="10" @if($selectedMonth == '10') selected @endif>October</option>
                    <option value="11" @if($selectedMonth == '11') selected @endif>November</option>
                    <option value="12" @if($selectedMonth == '12') selected @endif>December</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group input-group-md">
                <label class="input-group-text" for="SelectedYear">Year</label>
               <select class="form-control" id="SelectedYear" name="SelectedYear">
                    <option value="13">All</option>
                    <option value="2023" @if($selectedYear == '2023') selected @endif>2023</option>
                    <option value="2024" @if($selectedYear == '2024') selected @endif>2024</option>
                    <option value="2025" @if($selectedYear == '2025') selected @endif>2025</option>
                    <option value="2026" @if($selectedYear == '2026') selected @endif>2026</option>
                    <option value="2027" @if($selectedYear == '2027') selected @endif>2027</option>
                    <!-- Add more years as needed -->
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="input-group input-group-md">
                <input type="submit" class="btn btn-primary" aria-label="Sizing example input" value="Search" aria-describedby="inputGroup-sizing-sm">
            </div>
        </div>
        <input type="hidden" name="all" value="13"> <!-- Hidden input for 'all' parameter -->
    </div>
</form>

                        
                        <table class="datatable-init table" data-nk-container="table-responsive">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Product</th>
                                    <!--<th scope="col">Cost of Good Sale</th>-->
                                    <th scope="col">Income</th>
                                    <!--<th scope="col">Gross Profit</th>-->
                                </tr>
                            </thead>
                            <tbody>
                            
                                @foreach($products as $key=>$rowProducts)
                                    <tr>
                                        <th scope="row">{{$key+1}}</th>
                                        <td>{{$rowProducts->name." (".$rowProducts->category_name.") "."- ".strtoupper($rowProducts->mode)}}</td>
                                        <!--<td>RM 0.00</td>-->
                                        <td>RM {{number_format($rowProducts->total_price,2)}}</td>
                                        <!--<td>RM 56,225.00</td>-->
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

