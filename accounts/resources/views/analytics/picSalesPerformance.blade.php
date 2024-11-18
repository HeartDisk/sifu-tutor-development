@extends('layouts.main')

@section('content')
    <div class="nk-content">
        <div class="fluid-container">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head">
                        <div class="nk-block-head-between flex-wrap gap g-2">
                            <div class="nk-block-head-content">
                                <h2 class="nk-block-title">STUDENT PIC SALES PERFORMANCE</h2>
                                <nav>
                                    <ol class="breadcrumb breadcrumb-arrow mb-0">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item"><a href="#">Analytics</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">STUDENT PIC SALES PERFORMANCE</li>
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
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="fromMonth">From:</label>
                                        <select id="fromMonth" class="form-control">
                                            <!-- Add options dynamically from your data -->
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="toMonth">To:</label>
                                        <select id="toMonth" class="form-control">
                                            <!-- Add options dynamically from your data -->
                                        </select>
                                    </div>
                                </div>
                                <canvas id="salesChart" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const salesData = @json($data);

            // Function to populate dropdowns
            function populateDropdowns(data) {
                const fromDropdown = document.getElementById('fromMonth');
                const toDropdown = document.getElementById('toMonth');
                const uniqueMonths = [...new Set(data.map(item => item.month_name))];

                uniqueMonths.forEach(month => {
                    const optionFrom = document.createElement('option');
                    const optionTo = document.createElement('option');
                    optionFrom.value = month;
                    optionTo.value = month;
                    optionFrom.text = month;
                    optionTo.text = month;
                    fromDropdown.add(optionFrom);
                    toDropdown.add(optionTo);
                });

                fromDropdown.value = uniqueMonths[0];
                toDropdown.value = uniqueMonths[uniqueMonths.length - 1];
            }

            // Function to filter data based on dropdown values
            function filterData(data, fromMonth, toMonth) {
                const fromIndex = data.findIndex(item => item.month_name === fromMonth);
                const toIndex = data.findIndex(item => item.month_name === toMonth);
                if (fromIndex === -1 || toIndex === -1 || fromIndex > toIndex) {
                    return [];
                }
                return data.slice(fromIndex, toIndex + 1);
            }

            // Function to generate random colors
            function generateColors(num) {
                const colors = [];
                for (let i = 0; i < num; i++) {
                    const r = Math.floor(Math.random() * 255);
                    const g = Math.floor(Math.random() * 255);
                    const b = Math.floor(Math.random() * 255);
                    colors.push(`rgba(${r}, ${g}, ${b}, 0.2)`);
                }
                return colors;
            }

            // Function to render chart
            function renderChart(filteredData) {
                const ctx = document.getElementById('salesChart').getContext('2d');
                const labels = filteredData.map(item => `${item.staff_name} (${item.month_name})`);
                const data = filteredData.map(item => item.total_amount);
                const backgroundColors = generateColors(filteredData.length);

                // Destroy previous chart instance if it exists
                if (window.myChart) {
                    window.myChart.destroy();
                }

                window.myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Amount',
                            data: data,
                            backgroundColor: backgroundColors,
                            borderColor: backgroundColors.map(color => color.replace('0.2', '1')),
                            borderWidth: 1
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        scales: {
                            x: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Initial population and chart rendering
            populateDropdowns(salesData);
            const initialFromMonth = document.getElementById('fromMonth').value;
            const initialToMonth = document.getElementById('toMonth').value;
            renderChart(filterData(salesData, initialFromMonth, initialToMonth));

            // Event listeners for dropdowns
            document.getElementById('fromMonth').addEventListener('change', () => {
                const fromMonth = document.getElementById('fromMonth').value;
                const toMonth = document.getElementById('toMonth').value;
                const filteredData = filterData(salesData, fromMonth, toMonth);
                renderChart(filteredData);
            });

            document.getElementById('toMonth').addEventListener('change', () => {
                const fromMonth = document.getElementById('fromMonth').value;
                const toMonth = document.getElementById('toMonth').value;
                const filteredData = filterData(salesData, fromMonth, toMonth);
                renderChart(filteredData);
            });
        });
    </script>
@endsection
