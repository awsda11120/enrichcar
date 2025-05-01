<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\History;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class ChartController extends Controller
{
    public function CarChart()
    {
        // กราฟที่ 1: จำนวนรถที่ต่อ พ.ร.บ. ตามประเภทรถ
        $InsChart = Car::join('histories', 'cars.id', '=', 'histories.CarId')
                        ->where('histories.TypeRenewIns', 1)
                        ->where('histories.status', 1)
                        ->selectRaw('cars.InsuranceType, COUNT(*) as total')
                        ->groupBy('cars.InsuranceType')
                        ->get();
    
        // กราฟที่ 2: จำนวนรถที่ต่อภาษี ตามประเภทรถ
        $TaxChart = Car::join('histories', 'cars.id', '=', 'histories.CarId')
                        ->where('histories.TypeRenewTax', 1)
                        ->where('histories.status', 1)
                        ->selectRaw('cars.TaxType, COUNT(*) as total')
                        ->groupBy('cars.TaxType')
                        ->get();
    
        // รายได้รวมจากการต่อ พ.ร.บ. / ภาษี
        $InsCostChart = Car::join('histories', 'cars.id', '=', 'histories.CarId')
                            ->where('histories.TypeRenewIns', 1)  // เงื่อนไขการต่อ พ.ร.บ.
                            ->where('histories.status', 1)        // กรองเฉพาะ status = 1
                            ->selectRaw('cars.InsuranceType, SUM(histories.InsIncome) as total_costIns')
                            ->groupBy('cars.InsuranceType')
                            ->get();

        $TaxCostChart = Car::join('histories', 'cars.id', '=', 'histories.CarId')
                            ->where('histories.TypeRenewTax', 1)  // เงื่อนไขการต่อภาษี
                            ->where('histories.status', 1)        // กรองเฉพาะ status = 1
                            ->selectRaw('cars.TaxType, SUM(histories.TaxIncome) as total_costTax')
                            ->groupBy('cars.TaxType')
                            ->get();


    
        // ดึงเดือนที่มีข้อมูล
        $months = DB::table('histories')
                    ->where('status', 1)
                    ->selectRaw('DATE_FORMAT(DateRenew, "%Y-%m") as month')
                    ->groupBy('month')
                    ->orderBy('month', 'desc')
                    ->pluck('month');
    
        return view('sum', compact('InsChart', 'TaxChart', 'InsCostChart', 'TaxCostChart', 'months'));
    }
    

//     public function getChartData(Request $request)
// {
//     $startDate = $request->input('start_month');
//     $endDate = $request->input('end_month');

//     $startDate = $startDate ? Carbon::parse($startDate . '-01')->startOfMonth() : null;
//     $endDate = $endDate ? Carbon::parse($endDate . '-01')->endOfMonth() : null;

//     $insQuery = Car::join('histories', 'cars.id', '=', 'histories.CarId')
//         ->where('histories.TypeRenewIns', 1)
//         ->where('histories.status', 1);

//     $taxQuery = Car::join('histories', 'cars.id', '=', 'histories.CarId')
//         ->where('histories.TypeRenewTax', 1)
//         ->where('histories.status', 1);

//     if ($startDate && $endDate) {
//         $insQuery->whereBetween('histories.DateRenew', [$startDate, $endDate]);
//         $taxQuery->whereBetween('histories.DateRenew', [$startDate, $endDate]);
//     }

//     $insData = $insQuery
//         ->selectRaw('cars.InsuranceType, COUNT(*) as total')
//         ->groupBy('cars.InsuranceType')
//         ->pluck('total', 'cars.InsuranceType');

//     $taxData = $taxQuery
//         ->selectRaw('cars.TaxType, COUNT(*) as total')
//         ->groupBy('cars.TaxType')
//         ->pluck('total', 'cars.TaxType');

//     $labels = $insData->keys()->merge($taxData->keys())->unique()->sort()->values();
//     $insCounts = $labels->map(fn($type) => $insData[$type] ?? 0);
//     $taxCounts = $labels->map(fn($type) => $taxData[$type] ?? 0);

//     // รายได้รวม
//     $totalIns = $insQuery->sum('histories.InsIncome');
//     $totalTax = $taxQuery->sum('histories.TaxIncome');

//     return response()->json([
//         'bar' => [
//             'labels' => $labels,
//             'datasets' => [
//                 [
//                     'label' => 'พรบ.',
//                     'backgroundColor' => '#4CAF50',
//                     'data' => $insCounts,
//                 ],
//                 [
//                     'label' => 'ภาษี',
//                     'backgroundColor' => '#2196F3',
//                     'data' => $taxCounts,
//                 ]
//             ]
//         ],
//         'pie' => [
//             'labels' => ['พ.ร.บ.', 'ภาษี'],
//             'datasets' => [
//                 [
//                     'backgroundColor' => ['#4CAF50', '#2196F3'],
//                     'data' => [$totalIns, $totalTax],
//                 ]
//             ]
//         ]
//     ]);
// }

public function getChartData(Request $request)
{
    $startMonth = $request->input('start_month'); // รูปแบบ 'yyyy-mm'
    $endMonth   = $request->input('end_month');     // รูปแบบ 'yyyy-mm'

    if ($startMonth && $endMonth) {
        // กรองข้อมูลตามช่วงเดือนที่เลือก
        $startDate = \Carbon\Carbon::createFromFormat('Y-m', $startMonth)->startOfMonth();
        $endDate   = \Carbon\Carbon::createFromFormat('Y-m', $endMonth)->endOfMonth();

        $insuranceData = \DB::table('histories')
            ->join('cars', 'histories.CarId', '=', 'cars.id')
            ->whereBetween('histories.DateRenew', [$startDate, $endDate])
            ->where('histories.TypeRenewIns', 1)
            ->where('histories.status', 1)
            ->groupBy('cars.InsuranceType')
            ->selectRaw('cars.InsuranceType as type, COUNT(*) as total, SUM(histories.InsIncome) as totalFee')
            ->get();

        $taxData = \DB::table('histories')
            ->join('cars', 'histories.CarId', '=', 'cars.id')
            ->whereBetween('histories.DateRenew', [$startDate, $endDate])
            ->where('histories.TypeRenewTax', 1)
            ->where('histories.status', 1)
            ->groupBy('cars.TaxType')
            ->selectRaw('cars.TaxType as type, COUNT(*) as total, SUM(histories.TaxIncome) as totalFee')
            ->get();
    } else {
        // ถ้าไม่มีการเลือกเดือน (แสดงข้อมูลทั้งหมด)
        $insuranceData = \DB::table('histories')
            ->join('cars', 'histories.CarId', '=', 'cars.id')
            ->where('histories.TypeRenewIns', 1)
            ->where('histories.status', 1)
            ->groupBy('cars.InsuranceType')
            ->selectRaw('cars.InsuranceType as type, COUNT(*) as total, SUM(histories.InsIncome) as totalFee')
            ->get();

        $taxData = \DB::table('histories')
            ->join('cars', 'histories.CarId', '=', 'cars.id')
            ->where('histories.TypeRenewTax', 1)
            ->where('histories.status', 1)
            ->groupBy('cars.TaxType')
            ->selectRaw('cars.TaxType as type, COUNT(*) as total, SUM(histories.TaxIncome) as totalFee')
            ->get();
    }

    return response()->json([
        'insurance' => $insuranceData,
        'tax'       => $taxData
    ]);
}


    



    


public function index()
{
    // ดึงข้อมูลจากตาราง cars ที่มีการต่อ พ.ร.บ. และภาษี
    $cars = Car::select('cars.*', 'settings.name as car_type_name', 'taxes.name as tax_type_name')
        ->leftJoin('settings', 'cars.InsuranceType', '=', 'settings.id') // JOIN กับตาราง settings เพื่อดึงชื่อประเภท พ.ร.บ.
        ->leftJoin('taxes', 'cars.TaxType', '=', 'taxes.id') // JOIN กับตาราง taxes เพื่อดึงชื่อประเภทภาษี
        ->get();

    // กราฟที่ 1: จำนวนรถที่ต่อ พ.ร.บ. (Group by InsuranceType)
    $insuranceData = $cars->groupBy('car_type_name')->map->count();  // นับจำนวนรถตามประเภท พ.ร.บ.

    // กราฟที่ 2: จำนวนรถที่ต่อภาษี (Group by TaxType)
    $taxData = $cars->groupBy('tax_type_name')->map->count(); // นับจำนวนรถตามประเภทภาษี

    // กราฟวงกลม: รายได้รวมจากการต่อ พ.ร.บ. และภาษี
    $sumFeeData = $cars->sum(function ($car) {
        return $car->TaxIncome + $car->InsIncome;
    });
    
    // คำนวณรายได้จากการต่อ พ.ร.บ.
    $sumIns = $cars->filter(function ($item) {
        return $item->InsuranceType == 1; // เฉพาะที่ต่อ พ.ร.บ.
    })->sum('InsIncome'); // รายได้จากการต่อ พ.ร.บ.

    // คำนวณรายได้จากการต่อภาษี
    $sumTax = $cars->filter(function ($item) {
        return $item->TaxType == 1; // เฉพาะที่ต่อภาษี
    })->sum('TaxIncome'); // รายได้จากการต่อภาษี

    // ส่งข้อมูลไปยัง View
    return view('summary', [
        'insuranceData' => $insuranceData,
        'taxData' => $taxData,
        'sumIns' => $sumIns,
        'sumTax' => $sumTax,
    ]);
}

}
