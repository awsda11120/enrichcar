@extends('layout')

@section('content')
    <div class="row">
        <div class="container mb-3">
            <div class="row align-items-center">
                <div class="col-12">
                    <h4>กราฟแสดงจำนวนรถที่ต่อ พ.ร.บ. และภาษี (แยกตามประเภท)</h4>
                </div>

                @php
                    $monthNames = [
                        '01' => 'มกราคม',
                        '02' => 'กุมภาพันธ์',
                        '03' => 'มีนาคม',
                        '04' => 'เมษายน',
                        '05' => 'พฤษภาคม',
                        '06' => 'มิถุนายน',
                        '07' => 'กรกฎาคม',
                        '08' => 'สิงหาคม',
                        '09' => 'กันยายน',
                        '10' => 'ตุลาคม',
                        '11' => 'พฤศจิกายน',
                        '12' => 'ธันวาคม',
                    ];
                @endphp

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-6">
                            <label for="startYear">เลือกปีเริ่มต้น</label>
                            <select id="startYear" class="form-control">
                                <option value="">เลือกปี</option>
                                @for ($year = date('Y'); $year >= 2000; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="startMonth">เลือกเดือนเริ่มต้น</label>
                            <select id="startMonth" class="form-control">
                                <option value="">เลือกเดือน</option>
                                @foreach ($monthNames as $num => $name)
                                    <option value="{{ $num }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-6">
                            <label for="endYear">เลือกปีสิ้นสุด</label>
                            <select id="endYear" class="form-control">
                                <option value="">เลือกปี</option>
                                @for ($year = date('Y'); $year >= 2000; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="endMonth">เลือกเดือนสิ้นสุด</label>
                            <select id="endMonth" class="form-control">
                                <option value="">เลือกเดือน</option>
                                @foreach ($monthNames as $num => $name)
                                    <option value="{{ $num }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-3">
                    <button id="applyDateRange" class="btn btn-primary">ตกลง</button>
                </div>
            </div>
        </div>

        <hr>

        <div class="row" id="chartContainer">
            <div class="col-md-8 mb-5">
                <h5>กราฟการต่อ พ.ร.บ. แยกตามประเภท</h5>
                <canvas id="barChartInsurance"></canvas>
            </div>
            <div class="col-md-8 mt-5 mb-5">
                <h5>กราฟการต่อภาษี แยกตามประเภท</h5>
                <canvas id="barChartTax"></canvas>
            </div>
            <div class="col-md-6 mt-5 mb-5">
                <h5>กราฟรายได้รวม (บาท)</h5>
                <canvas id="pieChartCost"></canvas>
            </div>
        </div>
    </div>

    <!-- jQuery และ Chart.js -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script>
        var myChartInsurance, myChartTax, myChartPie;

        $(document).ready(function() {
            fetchData('', '');

            $('#applyDateRange').on('click', function() {
                var startYear = $('#startYear').val();
                var startMonth = $('#startMonth').val();
                var endYear = $('#endYear').val();
                var endMonth = $('#endMonth').val();

                if (!startYear || !startMonth || !endYear || !endMonth) {
                    alert('กรุณาเลือกปีและเดือนให้ครบถ้วน');
                    return;
                }

                var startDateParam = startYear + '-' + startMonth;
                var endDateParam = endYear + '-' + endMonth;

                fetchData(startDateParam, endDateParam);
            });
        });

        function fetchData(startMonth, endMonth) {
            var url = '/getChartData';
            if (startMonth && endMonth) {
                url += '?start_month=' + startMonth + '&end_month=' + endMonth; // แก้ไข
            }
            $.ajax({
                url: url,
                method: 'GET',
                success: function(data) {
                    if ((!data.insurance || data.insurance.length === 0) &&
                        (!data.tax || data.tax.length === 0)) {
                        alert('ไม่พบข้อมูลในช่วงเวลาที่เลือก');
                    } else {
                        updateCharts(data);
                    }
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        }

        function updateCharts(data) {
            var insuranceLabels = data.insurance.map(item => item.type);
            var insuranceCounts = data.insurance.map(item => item.total);

            if (myChartInsurance) myChartInsurance.destroy();
            var ctxInsurance = document.getElementById('barChartInsurance').getContext('2d');
            myChartInsurance = new Chart(ctxInsurance, {
                type: 'bar',
                data: {
                    labels: insuranceLabels,
                    datasets: [{
                        label: 'จำนวนรถที่ต่อ พ.ร.บ.',
                        data: insuranceCounts,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'ประเภทรถ',
                                font: {
                                    size: 14
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            max: 50,
                            title: {
                                display: true,
                                text: 'จำนวนรถ (คัน)',
                                font: {
                                    size: 14
                                }
                            },
                            ticks: {
                                autoSkip: true,
                                precision: 0,
                                callback: function(value) {
                                    return Number(value).toFixed(0);
                                }
                            }
                        }
                    }
                }
            });

            var taxLabels = data.tax.map(item => item.type);
            var taxCounts = data.tax.map(item => item.total);

            if (myChartTax) myChartTax.destroy();
            var ctxTax = document.getElementById('barChartTax').getContext('2d');
            myChartTax = new Chart(ctxTax, {
                type: 'bar',
                data: {
                    labels: taxLabels,
                    datasets: [{
                        label: 'จำนวนรถที่ต่อภาษี',
                        data: taxCounts,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'ประเภทรถ',
                                font: {
                                    size: 14
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            max: 50,
                            title: {
                                display: true,
                                text: 'จำนวนรถ (คัน)',
                                font: {
                                    size: 14
                                }
                            },
                            ticks: {
                                autoSkip: true,
                                precision: 0,
                                callback: function(value) {
                                    return Number(value).toFixed(0);
                                }
                            }
                        }
                    }
                }
            });

            var totalInsuranceFee = data.insurance.reduce((sum, item) => sum + parseFloat(item.totalFee), 0);
            var totalTaxFee = data.tax.reduce((sum, item) => sum + parseFloat(item.totalFee), 0);

            if (myChartPie) myChartPie.destroy();
            var ctxPie = document.getElementById('pieChartCost').getContext('2d');
            myChartPie = new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: ['พ.ร.บ.', 'ภาษี'],
                    datasets: [{
                        data: [totalInsuranceFee, totalTaxFee],
                        backgroundColor: ['rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)'],
                        borderColor: ['rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)'],
                        borderWidth: 1
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw.toLocaleString() + ' บาท';
                                }
                            }
                        },
                        datalabels: {
                            display: true,
                            color: 'black',
                            formatter: function(value, context) {
                                let total = context.dataset.data.reduce((total, num) => total + num, 0);
                                return value.toLocaleString() + ' บาท (' + ((value / total) * 100).toFixed(2) +
                                    '%)';
                            },
                            font: {
                                weight: 'bold',
                                size: 14
                            }
                        }
                    }
                }
            });
        }
    </script>
@endsection
