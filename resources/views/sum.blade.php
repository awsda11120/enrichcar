@extends('layout')

@section('content')
<div class="row">
    <div class="container mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h4>กราฟแสดงจำนวนประเภทรถที่ต่อ พ.ร.บ. และภาษี</h4>
            </div>
            <div class="col-md-6">
                <label for="monthSelect">เลือกเดือนที่แสดงข้อมูล</label>
                <select id="monthSelect" class="form-control">
                    @foreach ($months as $month)
                        <option value="{{ $month }}">{{ $month }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <hr>
    <div class="row">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js" integrity="sha512-JPcRR8yFa8mmCsfrw4TNte1ZvF1e3+1SdGMslZvmrzDYxS69J7J49vkFL8u6u8PlPJK+H3voElBtUCzaXj+6ig==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    var ctxInsurance = document.getElementById('barChartInsurance').getContext('2d');
    var ctxTax = document.getElementById('barChartTax').getContext('2d');
    var ctxPieCost = document.getElementById('pieChartCost').getContext('2d');

    var insuranceData = {
        labels: @json($InsChart->pluck('InsuranceType')),
        datasets: [{
            label: 'จำนวนรถ',
            data: @json($InsChart->pluck('total')),
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    };

    var taxData = {
        labels: @json($TaxChart->pluck('TaxType')),
        datasets: [{
            label: 'จำนวนรถ',
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
                @json($InsCostChart->pluck('total_costIns')->sum()),
                @json($TaxCostChart->pluck('total_costTax')->sum())
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
                    title: { display: true, text: 'ประเภทรถที่ต่อพรบ.', font: { size: 16 } },
                    ticks: { font: { size: 14 } },
                    grid: { display: true, color: "rgba(200, 200, 200, 0.3)" }
                },
                y: {
                    title: { display: true, text: 'จำนวนรถ (คัน)', font: { size: 16 } },
                    ticks: { stepSize: 1, font: { size: 14 } },
                    grid: { display: true, color: "rgba(200, 200, 200, 0.3)" }
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
                    title: { display: true, text: 'ประเภทรถที่ต่อภาษี', font: { size: 16 } },
                    ticks: { font: { size: 14 } },
                    grid: { display: true, color: "rgba(200, 200, 200, 0.3)" }
                },
                y: {
                    title: { display: true, text: 'จำนวนรถ (คัน)', font: { size: 16 } },
                    ticks: { stepSize: 1, font: { size: 14 } },
                    grid: { display: true, color: "rgba(200, 200, 200, 0.3)" }
                }
            }
        }
    });

    // กราฟวงกลม (Pie Chart) สำหรับรายได้รวม
    new Chart(ctxPieCost, {
        type: 'pie',
        data: costData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },

                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw.toLocaleString() + ' บาท';
                        }
                    }
                },

                DataLabels: {
                    display: true,  // ให้แสดงข้อมูลทันที
                    color: 'black',
                    formatter: function(value, context) {
                        var total = context.dataset.data.reduce(function(total, num) {
                            return total + num;
                        }, 0);
                        var percentage = (value / total * 100).toFixed(2);  // คำนวณเปอร์เซ็นต์
                        return value.toLocaleString() + ' บาท\n(' + percentage + '%)'; // แสดงจำนวนเงินและเปอร์เซ็นต์
                    },
                    font: {
                        weight: 'bold',
                        size: 14
                    }
                }

            }
        }
        // plugins : [ChartDataLabels]
    });
</script>
<script>
    document.getElementById('monthSelect').addEventListener('change', function() {
        let selectedMonth = this.value;

        fetch(`/getChartData?month=${selectedMonth}`)
            .then(response => response.json())
            .then(data => {
                updateCharts(data);
            })
            .catch(error => console.error('Error:', error));
    });

    function updateCharts(data) {
        // อัปเดตข้อมูลของแต่ละกราฟ
        barChartInsurance.data.labels = data.insurance.labels;
        barChartInsurance.data.datasets[0].data = data.insurance.data;
        barChartInsurance.update();

        barChartTax.data.labels = data.tax.labels;
        barChartTax.data.datasets[0].data = data.tax.data;
        barChartTax.update();

        pieChartCost.data.datasets[0].data = [data.insurance.total_cost, data.tax.total_cost];
        pieChartCost.update();
    }
    </script>

@endsection
