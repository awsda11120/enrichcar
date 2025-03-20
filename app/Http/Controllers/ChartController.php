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
    
        // รายได้รวมจากการต่อ พ.ร.บ.
        $InsCostChart = Car::join('histories', 'cars.id', '=', 'histories.CarId')
                            ->where('histories.TypeRenewIns', 1)  // เงื่อนไขการต่อ พ.ร.บ.
                            ->where('histories.status', 1)        // กรองเฉพาะ status = 1
                            ->selectRaw('cars.InsuranceType, SUM(histories.SumCost) as total_costIns')
                            ->groupBy('cars.InsuranceType')
                            ->get();

        $TaxCostChart = Car::join('histories', 'cars.id', '=', 'histories.CarId')
                            ->where('histories.TypeRenewTax', 1)  // เงื่อนไขการต่อภาษี
                            ->where('histories.status', 1)        // กรองเฉพาะ status = 1
                            ->selectRaw('cars.TaxType, SUM(histories.SumCost) as total_costTax')
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
    

    public function getChartData(Request $request)
{
     // ตรวจสอบวันที่ที่ได้รับ
     $startDate = $request->query('start_date');
     $endDate = $request->query('end_date');
 
     if (!$startDate || !$endDate) {
         return response()->json(['error' => 'Invalid date range'], 400);
     }
 
     // ลองตรวจสอบว่าเป็นวันที่หรือไม่
     try {
         $startDate = Carbon::parse($startDate)->format('Y-m-d');
         $endDate = Carbon::parse($endDate)->format('Y-m-d');
     } catch (\Exception $e) {
         return response()->json(['error' => 'Invalid date format'], 400);
     }

    // คิวรีข้อมูลอื่นๆ และส่งกลับเป็น JSON
    $insuranceData = DB::table('histories')
    ->join('cars', 'histories.CarId', '=', 'cars.id')
    ->whereBetween(DB::raw('DATE(histories.DateRenew)'), [$startDate, $endDate])
    ->where('histories.TypeRenewIns', 1)
    ->where('histories.status', 1)
    ->groupBy('cars.InsuranceType')
    ->selectRaw('cars.InsuranceType, COUNT(*) as total')
    ->get();

dd($insuranceData);  // ตรวจสอบข้อมูลที่ได้รับ


    $taxData = DB::table('histories')
        ->join('cars', 'histories.CarId', '=', 'cars.id')
        ->whereBetween(DB::raw('DATE(histories.DateRenew)'), [$startDate, $endDate])
        ->where('histories.TypeRenewTax', 1)
        ->where('histories.status', 1)
        ->groupBy('cars.TaxType')
        ->selectRaw('cars.TaxType, COUNT(*) as total')
        ->get();

    // ส่งข้อมูลที่ได้รับไปยัง Frontend
    return response()->json(['success' => 'Data retrieved successfully']);
    return response()->json([
        'insurance' => [
            'labels' => $insuranceData->pluck('InsuranceType'),
            'data' => $insuranceData->pluck('total'),
        ],
        'tax' => [
            'labels' => $taxData->pluck('TaxType'),
            'data' => $taxData->pluck('total'),
        ]
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
    $sumFeeData = $cars->sum('SumFee'); // รายได้รวมทั้งหมด

    // คำนวณรายได้จากการต่อ พ.ร.บ.
    $sumIns = $cars->filter(function ($item) {
        return $item->InsuranceType == 1; // เฉพาะที่ต่อ พ.ร.บ.
    })->sum('SumFee'); // รายได้จากการต่อ พ.ร.บ.

    // คำนวณรายได้จากการต่อภาษี
    $sumTax = $cars->filter(function ($item) {
        return $item->TaxType == 1; // เฉพาะที่ต่อภาษี
    })->sum('SumFee'); // รายได้จากการต่อภาษี

    // ส่งข้อมูลไปยัง View
    return view('summary', [
        'insuranceData' => $insuranceData,
        'taxData' => $taxData,
        'sumIns' => $sumIns,
        'sumTax' => $sumTax,
    ]);
}

}
