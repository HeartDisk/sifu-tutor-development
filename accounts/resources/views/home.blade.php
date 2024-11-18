@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
   <div class="container-fluid">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head">
               <div class="nk-block-head-between flex-wrap gap g-2">
                  <div class="nk-block-head-content">
                     <h2 class="nk-block-title">
                        Dashboard
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">General</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                        </ol>
                     </nav>
                  </div>
               </div>
            </div>
            <div class="row g-gs">
               <div class="col-md-12">
                  <div class="card">
                     <div class="card-body">
                        <h2 class="" style="margin-bottom: 2rem">Welcome, {{$userName->name}} !</h2>
                        <p><i class="fa fa-calendar"></i> Today's date
                           is {{date("d/m/Y")}}
                        </p>
                        <p class="">SifuTutor, your ultimate companion in mastering new skills and
                           knowledge! SifuTutor seamlessly connects you with expert tutors in various
                           subjects, offering personalized and interactive sessions tailored to your unique
                           learning needs. .
                        </p>
                     </div>
                  </div>
               </div>
               <div class="col-sm-6 col-xl-6 col-xxl-3">
                  <div
                     style="color: #FFFFFF !important; background: linear-gradient(135deg, #7e5ad5 0%, #1d1143 100%);"
                     class="card">
                     <div class="card-body">
                        <div class="card-title-group align-items-start">
                           <div class="card-title">
                              <h4 style="color:#fff" class="title"><span
                                 style="font-weight:bold; font-size:27px;">INCOME</span> <br/> This
                                 Month
                              </h4>
                           </div>
                        </div>
                        <div class="mt-2 mb-4">
                           <div style="color:#fff" class="amount h1">
                              RM {{number_format($incomeCollected,2)}}
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-sm-6 col-xl-6 col-xxl-3">
                  <div style="    color: #FFFFFF !important;
                     background: #07a7e3;
                     background: -moz-linear-gradient(-45deg, #07a7e3 0%, #32dac3 100%);
                     background: -webkit-linear-gradient(-45deg, #07a7e3 0%, #32dac3 100%);
                     background: linear-gradient(135deg, #07a7e3 0%, #32dac3 100%);
                     filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=$qp-color-1, endColorstr=$qp-color-2,GradientType=1 );
                     -webkit-transition: opacity 0.2s ease-out;
                     -moz-transition: opacity 0.2s ease-out;
                     -o-transition: opacity 0.2s ease-out;
                     transition: opacity 0.2s ease-out;" class="card">
                     <div class="card-body">
                        <div class="card-title-group align-items-start">
                           <div class="card-title">
                              <h4 style="color:#fff" class="title"><span
                                 style="font-weight:bold; font-size:27px;">EXPENSE </span><br/> This
                                 Month
                              </h4>
                           </div>
                        </div>
                        <div class="mt-2 mb-4">
                           <div style="color:#fff" class="amount h1">
                              RM {{number_format($expenditures,2)}}
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-sm-6 col-xl-6 col-xxl-3">
                  <div
                     style="color: #FFFFFF !important; background: linear-gradient(135deg, #7e5ad5 0%, #1d1143 100%);"
                     class="card">
                     <div class="card-body">
                        <div class="card-title-group align-items-start">
                           <div class="card-title">
                              <h4 style="color:#fff" class="title"><span
                                 style="font-weight:bold; font-size:21px;">INCOME COLLECTED</span>
                                 <br/> This Month
                              </h4>
                           </div>
                        </div>
                        <div class="mt-2 mb-4">
                           <div style="color:#fff" class="amount h1">
                              RM {{number_format($incomeCollected* 50,2)}}
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-sm-6 col-xl-6 col-xxl-3">
                  <div style="color: #FFFFFF !important;
                     background: #07a7e3;
                     background: -moz-linear-gradient(-45deg, #07a7e3 0%, #32dac3 100%);
                     background: -webkit-linear-gradient(-45deg, #07a7e3 0%, #32dac3 100%);
                     background: linear-gradient(135deg, #07a7e3 0%, #32dac3 100%);
                     filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=$qp-color-1, endColorstr=$qp-color-2,GradientType=1 );
                     -webkit-transition: opacity 0.2s ease-out;
                     -moz-transition: opacity 0.2s ease-out;
                     -o-transition: opacity 0.2s ease-out;
                     transition: opacity 0.2s ease-out;" class="card">
                     <div class="card-body">
                        <div class="card-title-group align-items-start">
                           <div class="card-title">
                              <h4 style="color:#fff" class="title"><span
                                 style="font-weight:bold; font-size:21px;">AVG PER INVOICE</span>
                                 <br/> This Month
                              </h4>
                           </div>
                        </div>
                        <div class="mt-2 mb-3">
                           <div style="color:#fff" class="amount h1">
                              RM {{number_format($average_per_invoice,2)}}
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-sm-6 col-xl-6 col-xxl-3">
                  <div
                     style="color: #FFFFFF !important; background: linear-gradient(135deg, #e4d063 0%, #e8571fbd 100%);"
                     class="card  card-xs ">
                     <div style="padding:0px !important" class="card-body">
                        <div class="card-title-group align-items-start">
                           <div class="card-title">
                              <h4 style="color:#fff" class="title">Active Staffs</h4>
                           </div>
                        </div>
                        <div class="mt-2 mb-4">
                           <div style="color:#fff"
                              class="amount h1">@php $allTutors = DB::table('users')->where('role','!=','1')->where('role','!=','4')->count('id'); @endphp {{$allTutors}}</div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-sm-6 col-xl-6 col-xxl-3">
                  <div
                     style="color: #FFFFFF !important;background: linear-gradient(135deg, #60aecc 0%, #397d77 100%);"
                     class="card card-xs ">
                     <div style="padding:0px !important" class="card-body">
                        <div class="card-title-group align-items-start">
                           <div class="card-title">
                              <h4 style="color:#fff" class="title">Active Tutors</h4>
                           </div>
                        </div>
                        <div class="mt-2 mb-4">
                           <div style="color:#fff"
                              class="amount h1">@php $allTutors = DB::table('tutors')->count('id'); @endphp {{$allTutors}} </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-sm-6 col-xl-6 col-xxl-3">
                  <div
                     style="color: #FFFFFF !important; background: linear-gradient(135deg, #e4d063 0%, #e8571fbd 100%);"
                     class="card  card-xs ">
                     <div style="padding:0px !important" class="card-body">
                        <div class="card-title-group align-items-start">
                           <div class="card-title">
                              <h4 style="color:#fff" class="title">Active Students</h4>
                           </div>
                        </div>
                        <div class="mt-2 mb-4">
                           <div style="color:#fff"
                              class="amount h1">@php $allStudents = DB::table('students')->count('id'); @endphp {{$allStudents}} </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-sm-6 col-xl-6 col-xxl-3">
                  <div
                     style="color: #FFFFFF !important; background: linear-gradient(135deg, #60aecc 0%, #397d77 100%);"
                     class="card card-xs ">
                     <div style="padding:0px !important" class="card-body">
                        <div class="card-title-group align-items-start">
                           <div class="card-title">
                              <h4 style="color:#fff" class="title">This month new students</h4>
                           </div>
                        </div>
                        <div class="mt-2 mb-4">
                           <div style="color:#fff" class="amount h1"></div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-xxl-8 col-md-8">
                  <div class="card h-100">
                     <div class="card-body">
                        <div class="card-title-group flex-wrap">
                           <div class="card-title">
                              <h5 class="title">REVENUE VS EXPENSE</h5>
                           </div>
                        </div>
                        <div>
                           <canvas id="myChartRevenueVsExpenses"></canvas>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-4 col-xxl-4">
                  <div class="card h-100">
                     <div class="card-body">
                        <div class="card-title-group">
                           <div class="card-title">
                              <h5 class="title">EXPENSES CATEGORY</h5>
                           </div>
                           <div class="card-tools">
                              <em class="icon-hint icon ni ni-help-fill" data-bs-toggle="tooltip"
                                 data-bs-placement="left" title="EXPENSES CATEGORY"></em>
                           </div>
                        </div>
                        <div class=" mt-4">
                           <div class="chart-container"
                              style="position: relative; height:100%; width:100%">
                              <canvas style="height:100% !important; width:100% !important;" id="expensesCategoryChart"></canvas>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-4 col-xxl-4">
                  <div class="card h-100">
                     <div style="padding:0px !important" class="card-body">
                        <div class="card-title-group">
                           <div class="card-title">
                              <h5 class="title">Last User Activity</h5>
                           </div>
                        </div>
                        @php
                        $user = DB::table('loggedInUsers')->orderBy("id","desc")->get();
                        @endphp
                        <div class="nk-timeline nk-timeline-center mt-4">
                           <div style="overflow-y: auto; height:500px; " class="nk-timeline-group">
                              <div class="nk-timeline-heading">
                              </div>
                              <ul class="nk-timeline-list">
                                 @foreach($user as $rowUser)
                                 @php
                                 $userDetail = DB::table('users')->where('id','=',$rowUser->user_id)->first();
                                 @endphp
                                 @if($userDetail)
                                 <li class="nk-timeline-item">
                                    <div class="nk-timeline-item-inner">
                                       <div class="nk-timeline-symbol">
                                          <div
                                             class="media media-md media-middle media-circle text-bg-info">
                                             <em class="icon ni ni-user"></em>
                                          </div>
                                       </div>
                                       <div class="nk-timeline-content">
                                          <p class="small">
                                             <strong>{{$userDetail->name}}</strong> viewed
                                             <strong>{{$rowUser->detail}}</strong>
                                             <br/>
                                             {{$rowUser->last_login}}
                                          </p>
                                       </div>
                                    </div>
                                 </li>
                                 @endif
                                 @endforeach
                              </ul>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-8 col-xxl-8">
                  <div class="card h-100">
                     <div class="card-body">
                        <div class="card-title-group">
                           <div class="card-title">
                              <h5 class="title">CASH FLOW</h5>
                           </div>
                        </div>
                        <div class="chart-legend-group justify-content-around pt-4 flex-wrap gap g-2">
                           <div class="chart-container"
                              style="position: relative; height:100%; width:100%">
                              <canvas id="myChart"></canvas>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-8 col-xxl-8">
                  <div class="card h-100">
                     <div class="card-body">
                        <div class="card-title-group">
                           <div class="card-title">
                              <h5 class="title">UNPAID SALES INVOICE</h5>
                           </div>
                        </div>
                        <div class="chart-legend-group justify-content-around pt-4 flex-wrap gap g-2">
                           <div class="chart-container"
                              style="position: relative; height:100%; width:100%">
                              <canvas id="unPaidAmountChart"></canvas>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-4 col-xxl-4">
                  <div class="card h-100">
                     <div class="card-body">
                        <div class="card-title-group">
                           <div class="card-title">
                              <h5 class="title">ACTIVE USERS</h5>
                           </div>
                        </div>
                        <div class="chart-legend-group justify-content-left pt-4 flex-wrap gap g-2">
                           <table>
                              @php
                              $activUsers = DB::table('loggedInUsers')->groupBy('user_id')->select('user_id')->get();
                              @endphp
                              @foreach($activUsers as $row)
                              @php
                              $userLastLoginDetail = DB::table('loggedInUsers')->where('user_id','=',$row->user_id)->first();
                              $userDetail = DB::table('users')->where('id','=',$row->user_id)->first();
                              @endphp
                              @if($userDetail)
                              <tr>
                                 <td>
                                    <div
                                       class="media media-md media-middle media-circle text-bg-info">
                                       <em class="icon ni ni-user"></em>
                                    </div>
                                 </td>
                                 <td style="font-size:12px;">{{$userDetail->name}}
                                    <br/> {{$userLastLoginDetail->last_login}}
                                 </td>
                              </tr>
                              @endif
                              @endforeach
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
   integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="{{asset('library/moment.min.js')}}"></script>
<script src="{{asset('library/daterangepicker.min.js')}}"></script>
<script src="{{asset('library/Chart.bundle.min.js')}}"></script>
<script src="{{asset('library/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('library/dataTables.bootstrap5.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
   const ctxExpensesCategoryChart = document.getElementById('expensesCategoryChart');
   new Chart(ctxExpensesCategoryChart, {
       type: 'doughnut',
       data: {
           labels: ['Tutor Payments', 'Expenditures'],
           datasets: [
               {
                   data: [{{$totalTutorPaidTime}}, {{$expenses}}],
                   borderWidth: 1
               }]
       },
       options: {
           scales: {
               y: {
                   beginAtZero: true
               }
           }
       }
   });
   
   const ctxUnPaidAmountChart = document.getElementById('unPaidAmountChart');
   const unpaidData = @json($dataArray);
   new Chart(ctxUnPaidAmountChart, {
       type: 'bar',
       data: {
           labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
           datasets: [{
               label: 'UnPaid Amount',
               data: unpaidData,
               borderWidth: 1
           }]
       },
       options: {
           scales: {
               y: {
                   beginAtZero: true
               }
           }
       }
   });
   
   const ctx = document.getElementById('myChart');
   new Chart(ctx, {
       type: 'bar',
       data: {
           labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
           datasets: [
               {
                   label: 'Money In',
                   data: [1, 2, 3, 4, 5, 6, 12, 19, 3, 5, 2, 3],
                   borderWidth: 1
               },
               {
                   label: 'Money Out',
                   data: [4, 5, 6, 7, 8, 7, 12, 19, 3, 5, 2, 3],
                   borderWidth: 1
               }
           ]
       },
       options: {
           scales: {
               y: {
                   beginAtZero: true
               }
           }
       }
   });
   
   const ctxMyChartRevenueVsExpenses = document.getElementById('myChartRevenueVsExpenses');
   new Chart(ctxMyChartRevenueVsExpenses, {
       type: 'line',
       data: {
           labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
           datasets: [
               {
                   label: 'Revenue',
                   data: [0, 0, 0, 0, 0, 0, 0, {{ $revenue }}, 0, 0, 0, 0],
                   borderWidth: 1
               },
               {
                   label: 'Expense',
                   data: [0, 0, 0, 0, 0, 0, 0, {{$expenses}}, 0, 0, 0, 0],
                   borderWidth: 1
               }
           ]
       },
       options: {}
   });
   
</script>
<script>
   $(document).ready(function () {
       fetch_data();
       var sale_chart;
       function fetch_data(start_date = '', end_date = '') {
           var dataTable = $('#order_table').DataTable({
               "processing": true,
               "serverSide": true,
               "order": [],
               "ajax": {
                   url: "action.php",
                   type: "POST",
                   data: {
                       action: 'fetch',
                       start_date: start_date,
                       end_date: end_date
                   }
               },
               "drawCallback": function (settings) {
                   var sales_date = [];
                   var sale = [];
                   for (var count = 0; count < settings.aoData.length; count++) {
                       sales_date.push(settings.aoData[count]._aData[2]);
                       sale.push(parseFloat(settings.aoData[count]._aData[1]));
                   }
   
                   var chart_data = {
                       labels: sales_date,
                       datasets: [{
                           label: 'Sales',
                           color: 'rgb(255,192,203)',
                           backgroundColor: 'transparent',
                           data: sale
                       },
                           {
                               label: 'Sales',
                               backgroundColor: 'transparent',
                               color: 'rgb(0, 0, 255)',
                               data: sale
                           }
                       ]   
                   };

                   var group_chart3 = $('#bar_chart');
                   if (sale_chart) {
                       sale_chart.destroy();
                   }
                   sale_chart = new Chart(group_chart3, {
                       type: 'line',
                       data: chart_data,
                   });
               },
           });
       }
   
       $('#daterange_textbox').daterangepicker({
           ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month')
                   .endOf('month')
               ]
           },
           format: 'YYYY-MM-DD'
       }, function (start, end) {
           $('#order_table').DataTable().destroy();
           fetch_data(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
           console.log(start.format('YYYY-MM-DD'), "   space     ", end.format('YYYY-MM-DD'))
       });
   });
</script>
@endsection