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
                                    MONTHLY PRODUCT VS COMMISSION</h1>
                                    <nav>
                                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                                            <li class="breadcrumb-item"><a href="#">Operation Report</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">MONTHLY PRODUCT VS
                                                COMMISSION
                                            </li>
                                        </ol>
                                    </nav>
                            </div>

                        </div>
                    </div>
                    <div class="nk-block">
                        <div class="card">
                            <div class="card-body">
                                <form method="post" enctype="multipart/form-data"
                                      action="{{url("/monthlyProductVsComissionByMonth")}}">
                                    @csrf
                                    <div class="row justify-content-end">
                                        <div class="col-md-2 offset-6">
                                            <select name="month" class="form-control">
                                                <option value="">Please select month</option>
                                                @php
                                                    $selectedMonth = isset($selectedMonth) ? $selectedMonth : null;
                                                    for ($month = 1; $month <= 12; $month++) {
                                                        $monthName = date('F', mktime(0, 0, 0, $month, 1));
                                                        $selectedAttribute = ($selectedMonth == $monthName) ? 'selected' : '';

                                                        echo "<option value=\"$monthName\" $selectedAttribute>$monthName</option>";
                                                    }
                                                @endphp
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-primary">Search</button>
                                        </div>

                                    </div>
                                </form>

                                <table class="datatable-init table" data-nk-container="table-responsive table-border">
                                    <thead class="table-dark">
                                    <tr>
                                        <th><span class="overline-title">S.No</span></th>
                                        <th><span class="overline-title">Staff</span></th>
                                          <th><span class="overline-title">Product</span></th>
                                        <th><span class="overline-title">Month - Year</span></th>
                                        <th><span class="overline-title">Total Invoices</span></th>
                                        <th><span class="overline-title">Total Invoices Amount</span></th>
                                        <th><span class="overline-title">Total Commissions</span></th>
                                        <!--<th><span class="overline-title">Action</span></th>-->
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data as $key=>$rows)
                                        @php
                                        
                                           
                                        @endphp
                                       
                                            <tr>
                                                
                                                <td>{{$key+1}}</td>
                                                <td>{{$rows->staff_name}}</td>
                                                 <td>{{$rows->product_name}}</td>
                                                <td>{{$rows->month_name}}</td>
                                                <td>{{$rows->total_job_tickets}}</td>
                                                <td>{{$rows->total_amount}}</td>
                                                <td>{{$rows->bonus}}</td>
                                               
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

@endsection

