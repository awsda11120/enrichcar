@extends('layout')

@section('content')
<div class="container">
    <h2>กราฟแสดงจำนวนประเภทรถที่เข้าใช้บริการ</h2>
    <canvas id="barChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    var ctx = document.getElementById('barChart').getContext('2d');

var chartData = {
    labels: @json($CarChart->pluck('CarType')),
    datasets: [{
        label: 'จำนวนรถ',
        data: @json($CarChart->pluck('total')),
        backgroundColor: [
            'rgba(255, 99, 132, 0.5)',  // สีแดง
            'rgba(54, 162, 235, 0.5)',  // สีฟ้า
            'rgba(255, 206, 86, 0.5)',  // สีเหลือง
            'rgba(75, 192, 192, 0.5)',  // สีเขียว
            'rgba(153, 102, 255, 0.5)'  // สีม่วง
        ],
        borderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)'
        ],
        borderWidth: 1
    }]
};

var barChart = new Chart(ctx, {
    type: 'bar',
    data: chartData,
    options: {
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'ประเภทรถ',
                    font: { size: 16 }
                },
                ticks: { font: { size: 14 } },
                grid: { display: true, color: "rgba(200, 200, 200, 0.3)" }
            },
            y: {
                title: {
                    display: true,
                    text: 'จำนวนรถ (คัน)',
                    font: { size: 16 }
                },
                ticks: { stepSize: 1, font: { size: 14 } },
                grid: { display: true, color: "rgba(200, 200, 200, 0.3)" }
            }
        },
        plugins: {
            legend: {
                display: true,
                labels: { font: { size: 14 } }
            }
        }
    }
});


</script>
@endsection