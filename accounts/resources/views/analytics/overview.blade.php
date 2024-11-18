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
                        Overview</h1>
                        <nav>
                          <ol class="breadcrumb breadcrumb-arrow mb-0">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Analytics</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Overview</li>
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
                      
                        <div class="card-body">
                <div class="col-md-12 mb-3">
                    <h2>New Students &amp; Tutors</h2>
                </div>
                <div class="row">
                            <div class="col-md-12">
                                  <canvas id="myChart"></canvas>
                            </div>
                        </div>
                <div class="col-md-12 mb-3 mt-5">
                    <h2>Student Invoices</h2>
                </div>
                <div class="widget-row row">
                    <div class="col-md-6 col-lg-4 col-xl-4 mb-5">
                        <div class="card card-tile card-xs bg-success bg-gradient text-center">
                            <div class="card-body p-4">
                                <div class="tile-left">
                                    <i class="batch-icon batch-icon-paper-roll batch-icon-xxl"></i>
                                </div>
                                <a href="{{url("/students/StudentInvoices")}}">
                                    <div class="tile-right">
                                        <div class="tile-number">{{$invoices}}</div>
                                        <div class="tile-description">Invoice generated</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-4 mb-5">
                        <div class="card card-tile card-xs bg-secondary bg-gradient-1 text-center">
                            <div class="card-body p-4">
                                <div class="tile-left">
                                    
                                </div>
                                <a href="{{url("/students/StudentInvoices")}}">
                                    <div class="tile-right">
                                        <div class="tile-number">RM {{$invoices_amount}}</div>
                                        <div class="tile-description">Amount of generated invoices</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-4 mb-5">
                        <div class="card card-tile card-xs bg-secondary bg-gradient text-center">
                            <div class="card-body p-4">
                                <div class="tile-left">
                                    <i class="batch-icon batch-icon-paper-roll batch-icon-xxl"></i>
                                </div>
                                <a href="{{url("/students/StudentInvoices")}}">
                                    <div class="tile-right">
                                        <div class="tile-number">RM {{number_format($avg_per_invoice,2)}}</div>
                                        <div class="tile-description">Average per invoice</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="widget-row row">
                    <div class="col-md-6 col-lg-6 col-xl-6 mb-5">
                        <div class="card card-tile card-xs bg-danger text-center">
                            <div class="card-body p-4">
                                <div class="tile-left">
                                    <i class="batch-icon batch-icon-paper-roll batch-icon-xxl"></i>
                                </div>
                                <a href="{{url("/students/StudentInvoices")."?Status=Unpaid"}}">
                                    <div class="tile-right">
                                        <div class="tile-number">{{number_format($unpaid_invoice,2)}}</div>
                                        <div class="tile-description">Unpaid invoice</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-6 mb-5">
                        <div class="card card-tile card-xs bg-secondary bg-gradient-1 text-center">
                            <div class="card-body p-4">
                                <div class="tile-left">
                                    
                                </div>
                                <a  href="{{url("/students/StudentInvoices")."?Status=Unpaid"}}">
                                    <div class="tile-right">
                                        <div class="tile-number">RM {{number_format($unpaid_invoice,2)}}</div>
                                        <div class="tile-description">amount of unpaid invoices</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3 mt-5">
                        <h2>Overall Tutor Count</h2>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-5">
                        <div class="card bg-info">
                            <div class="card-body">
                                <div class="mb-2 clearfix">
                                    <div class="float-right text-right">
                                        <h6 class="m-0"><strong>Active Tutors</strong></h6>
                                    </div>
                                </div>
                                <div class="text-right clearfix">
                                    <div class="display-4">{{$tutors_active}} <small>/ {{$tutors}} </small></div>
                                    <div class="m-0">Total tutors</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-5">
                        <div class="card bg-info">
                            <div class="card-body">
                                <div class="mb-3 clearfix">
                                    <div class="float-right text-right">
                                        <h6 class="m-0"><strong>LoggedIn Tutors</strong></h6>
                                    </div>
                                </div>
                                <div class="text-right clearfix">
                                    <div class="display-4">{{$logged_in_tutors}} <small>/ {{$tutors_active}} </small></div>
                                    <div class="m-0">Active tutors</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-5">
                        <div class="card bg-info">
                            <div class="card-body">
                                <div class="mb-2 clearfix">
                                    <div class="float-right text-right">
                                        <h6 class="m-0"><strong>Tutors Scheduled Class</strong></h6>
                                    </div>
                                </div>
                                <div class="text-right clearfix">
                                    <div class="display-4">{{$tutor_scheduled_classes->total_tutors}} <small>/ {{$tutors_active}} </small></div>
                                    <div class="m-0">Active tutors</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('myChart');


                    
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['January','Febuary','March','April','May','June', 'July', 'August', 'September', 'October', 'November','December'],
      datasets: [{
        label: '# of Students',
        data: ['{{$countData[0]["students_count"]}}', '{{$countData[1]["students_count"]}}', '{{$countData[2]["students_count"]}}', '{{$countData[3]["students_count"]}}', '{{$countData[4]["students_count"]}}',
        '{{$countData[5]["students_count"]}}','{{$countData[6]["students_count"]}}','{{$countData[7]["students_count"]}}','{{$countData[8]["students_count"]}}','{{$countData[9]["students_count"]}}',
        '{{$countData[10]["students_count"]}}','{{$countData[11]["students_count"]}}'
        ],
        borderWidth: 1
      },{
        label: '# of Tutors',
        data: ['{{$countData[0]["tutors_count"]}}', '{{$countData[1]["tutors_count"]}}', '{{$countData[2]["tutors_count"]}}', '{{$countData[3]["tutors_count"]}}', '{{$countData[4]["tutors_count"]}}',
        '{{$countData[5]["tutors_count"]}}','{{$countData[6]["tutors_count"]}}','{{$countData[7]["tutors_count"]}}','{{$countData[8]["tutors_count"]}}','{{$countData[9]["tutors_count"]}}',
        '{{$countData[10]["tutors_count"]}}','{{$countData[11]["tutors_count"]}}'
        ],
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
</script>
@endsection

