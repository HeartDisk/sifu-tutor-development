@extends('layouts.main')

@section('content')

    <div class="nk-content">
        <br/>
        <br/>
        <br/>
        <div class="fluid-container">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head">
                        <div class="nk-block-head-between flex-wrap gap g-2">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    MONTHLY TICKET STATUS</h1>
                                <nav>
                                    <ol class="breadcrumb breadcrumb-arrow mb-0">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item"><a href="#">Analytics</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">MONTHLY TICKET STATUS
                                        </li>
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


                            <div class="row">
                                <div class="col-md-12">
                                    <canvas id="myChart"></canvas>
                                </div>
                            </div>


                            <!--<div class="row">-->
                            <!--    <div class="col-md-12">-->
                            <!--        <div class="table-responsive">-->
                            <!--            <table class="table table-hover table-striped">-->
                            <!--                <thead class="thead-light">-->
                            <!--                <tr>-->
                            <!--                    <th scope="col"></th>-->
                            <!--                    <th scope="col">8am – 12pm</th>-->
                            <!--                    <th scope="col">12pm – 4pm</th>-->
                            <!--                    <th scope="col">4pm - 8pm</th>-->
                            <!--                    <th scope="col">8pm - 12am</th>-->
                            <!--                </tr>-->
                            <!--                </thead>-->
                            <!--                <tbody>-->
                            <!--                <tr>-->
                            <!--                    <th scope="row">Weekday</th>-->
                            <!--                    <td>9728</td>-->
                            <!--                    <td>7487</td>-->
                            <!--                    <td>4658</td>-->
                            <!--                    <td>1726</td>-->
                            <!--                </tr>-->
                            <!--                <tr>-->
                            <!--                    <th scope="row">Weekend</th>-->
                            <!--                    <td>415</td>-->
                            <!--                    <td>341</td>-->
                            <!--                    <td>319</td>-->
                            <!--                    <td>403</td>-->
                            <!--                </tr>-->
                            <!--                </tbody>-->
                            <!--                <tfoot class="tfoot-light">-->
                            <!--                <tr>-->
                            <!--                    <td>Total</td>-->
                            <!--                    <td>10143</td>-->
                            <!--                    <td>7828</td>-->
                            <!--                    <td>4977</td>-->
                            <!--                    <td>2129</td>-->
                            <!--                </tr>-->
                            <!--                </tfoot>-->
                            <!--            </table>-->
                            <!--        </div>-->
                            <!--        <p><i>**Based on tickets applied from December - 2022 to June - 2023 </i></p>-->
                            <!--    </div>-->
                            <!--</div>-->


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('myChart');
        var data = {{json_encode($data)}};
        new Chart(ctx, {
            type: 'bar',
            data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [{
                label: '# of Job Tickets',
                data: data,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
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

