@extends('layout')

@section('content')
    <div class="row">
        <div class="container mb-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4>กราฟแสดงจำนวนประเภทรถที่ต่อ พ.ร.บ. และภาษี</h4>
                </div>
                <div class="col-md-6">
                    <label for="startDate">เลือกวันที่เริ่มต้น</label>
                    <input type="text" id="startDate" class="form-control datepicker">
                </div>
                <div class="col-md-6">
                    <label for="endDate">เลือกวันที่สิ้นสุด</label>
                    <input type="text" id="endDate" class="form-control datepicker">
                </div>
                <div class="col-md-12">
                    <button id="applyDateRange" class="btn btn-primary mt-3">ตกลง</button>
                </div>

                <!-- ใช้งาน Datepicker -->
                <script>
                    $(document).ready(function() {
                        // เปิดใช้งาน Bootstrap Datepicker
                        $('.datepicker').datepicker({
                            format: 'yyyy-mm-dd',
                            autoclose: true,
                            clearBtn: true // เพิ่มปุ่มเคลียร์ค่า
                        });

                        // ล้างค่า Datepicker ทุกครั้งที่กดปุ่ม
                        function resetDatepickers() {
                            $('#startDate, #endDate').val(''); // เคลียร์ค่าที่เลือก
                            $('.datepicker').datepicker('update', ''); // อัปเดต Datepicker
                        }

                        // รีเซ็ต Event ก่อนเพิ่มใหม่
                        $('#applyDateRange').off('click').on('click', function() {
                            var startDate = $('#startDate').val();
                            var endDate = $('#endDate').val();

                            console.log('Start Date:', startDate); // ตรวจสอบค่า startDate
                            console.log('End Date:', endDate); // ตรวจสอบค่า endDate

                            if (startDate && endDate) {
                                fetchData(startDate, endDate);
                                resetDatepickers();
                            } else {
                                alert('กรุณาเลือกวันที่เริ่มต้นและวันที่สิ้นสุด');
                            }
                        });



                    });




                    let barChartInsurance, barChartTax, pieChartCost;

                    function fetchData(startDate, endDate) {
                        $.ajax({
                            url: `/getChartData?start_date=${startDate}&end_date=${endDate}`, // ตรวจสอบ URL
                            method: 'GET',
                            success: function(data) {
                                console.log('Data received from backend:', data); // ดูข้อมูลที่ได้รับจาก Backend
                                updateCharts(data); // ใช้ข้อมูลเพื่ออัพเดตกราฟ
                            },
                            error: function(error) {
                                console.error('Error:', error); // หากมีข้อผิดพลาด
                            }
                        });
                    }





                    function updateCharts(data) {
                        console.log('Insurance Data:', data.insurance.data);
                        console.log('Tax Data:', data.tax.data);

                        if (myChart) {
                            myChart.destroy(); // ปิดกราฟเดิม
                        }

                        var ctx = document.getElementById('myChart').getContext('2d');

                        myChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: data.insurance.data.map(item => item.label),
                                datasets: [{
                                    label: 'Insurance Count',
                                    data: data.insurance.data.map(item => item.total),
                                    backgroundColor: '#ff6347',
                                    borderColor: '#ff6347',
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
                    }
                </script>
            </div>
        </div>

        <hr>
        <div class="row" id="chartContainer">
            <div class="col-md-8 mb-5">
                <h5>การต่อ พ.ร.บ.</h5>
                <canvas id="barChartInsurance"></canvas>
            </div>
            <hr class="my-5">

            <div class="col-md-8 mt-5 mb-5">
                <h5>การต่อภาษี</h5>
                <canvas id="barChartTax"></canvas>
            </div>
            <hr class="my-5">

            <div class="col-md-6 mt-5 mb-5">
                <h5>รายได้รวมจากการต่อ พ.ร.บ. และภาษี</h5>
                <canvas id="pieChartCost"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js"
        integrity="sha512-JPcRR8yFa8mmCsfrw4TNte1ZvF1e3+1SdGMslZvmrzDYxS69J7J49vkFL8u6u8PlPJK+H3voElBtUCzaXj+6ig=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        var ctxInsurance = document.getElementById('barChartInsurance').getContext('2d');
        var ctxTax = document.getElementById('barChartTax').getContext('2d');
        var ctxPieCost = document.getElementById('pieChartCost').getContext('2d');

        var insuranceData = {
            labels: @json($InsChart->pluck('InsuranceType')),
            datasets: [{
                label: 'จำนวนรถที่ต่อ พ.ร.บ.',
                data: @json($InsChart->pluck('total')),
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };

        var taxData = {
            labels: @json($TaxChart->pluck('TaxType')),
            datasets: [{
                label: 'จำนวนรถที่ต่อภาษี',
                data: @json($TaxChart->pluck('total')),
                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        };

        var costData = {
            labels: ['พ.ร.บ.', 'ภาษี'],
            datasets: [{
                label: 'รายได้รวม (บาท)',
                data: [
                    @json($InsCostChart->sum('total_costIns')),
                    @json($TaxCostChart->sum('total_costTax'))
                ],
                backgroundColor: ['rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)'],
                borderColor: ['rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)'],
                borderWidth: 1
            }]
        };

        new Chart(ctxInsurance, {
            type: 'bar',
            data: insuranceData,
            options: {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'ประเภทรถที่ต่อพรบ.',
                            font: {
                                size: 16
                            }
                        },
                        ticks: {
                            font: {
                                size: 14
                            }
                        },
                        grid: {
                            display: true,
                            color: "rgba(200, 200, 200, 0.3)"
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'จำนวนรถ (คัน)',
                            font: {
                                size: 16
                            }
                        },
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 14
                            }
                        },
                        grid: {
                            display: true,
                            color: "rgba(200, 200, 200, 0.3)"
                        }
                    }
                }
            }
        });

        new Chart(ctxTax, {
            type: 'bar',
            data: taxData,
            options: {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'ประเภทรถที่ต่อภาษี',
                            font: {
                                size: 16
                            }
                        },
                        ticks: {
                            font: {
                                size: 14
                            }
                        },
                        grid: {
                            display: true,
                            color: "rgba(200, 200, 200, 0.3)"
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'จำนวนรถ (คัน)',
                            font: {
                                size: 16
                            }
                        },
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 14
                            }
                        },
                        grid: {
                            display: true,
                            color: "rgba(200, 200, 200, 0.3)"
                        }
                    }
                }
            }
        });

        new Chart(ctxPieCost, {
            type: 'pie',
            data: costData,
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
                            var total = context.dataset.data.reduce((total, num) => total + num, 0);
                            return value.toLocaleString() + ' บาท (' + (value / total * 100).toFixed(2) + '%)';
                        },
                        font: {
                            weight: 'bold',
                            size: 14
                        }
                    }
                }
            }
        });
    </script>
@endsection
