<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\History;


class ChartController extends Controller
{
    public function CarChart()
{
    $InsChart = Car::selectRaw('InsuranceType, COUNT(*) as total')
                        ->groupBy('InsuranceType')
                        ->get();

    $TaxChart = Car::selectRaw('TaxType, COUNT(*) as total')
                        ->groupBy('TaxType')
                        ->get();

    $InsCostChart = Car::join('histories', 'cars.id', '=', 'histories.CarId')
                        ->selectRaw('cars.InsuranceType, SUM(histories.SumCost) as total_costIns')
                        ->groupBy('cars.InsuranceType')
                        ->get();


    $TaxCostChart = Car::join('histories', 'cars.id', '=', 'histories.CarId')
                        ->selectRaw('cars.TaxType, SUM(histories.SumCost) as total_costTax')
                        ->groupBy('cars.TaxType')
                        ->get();

    $months = \DB::table('histories')
                        ->selectRaw('DATE_FORMAT(DateRenew, "%Y-%m") as month')
                        ->groupBy('month')
                        ->orderBy('month', 'desc')
                        ->pluck('month');


    return view('sum', compact('InsChart', 'TaxChart', 'InsCostChart', 'TaxCostChart','months'));
}

public function getChartData(Request $request)
{
    $month = $request->query('month');

    // ดึงข้อมูลจากฐานข้อมูล
    $insuranceData = DB::table('histories')
        ->whereRaw('DATE_FORMAT(DateRenew, "%Y-%m") = ?', [$month])
        ->groupBy('InsuranceType')
        ->selectRaw('InsuranceType, COUNT(*) as total')
        ->get();

    $taxData = DB::table('histories')
        ->whereRaw('DATE_FORMAT(DateRenew, "%Y-%m") = ?', [$month])
        ->groupBy('TaxType')
        ->selectRaw('TaxType, COUNT(*) as total')
        ->get();

    $insuranceCost = DB::table('histories')
        ->whereRaw('DATE_FORMAT(DateRenew, "%Y-%m") = ?', [$month])
        ->sum('total_costIns');

    $taxCost = DB::table('histories')
        ->whereRaw('DATE_FORMAT(DateRenew, "%Y-%m") = ?', [$month])
        ->sum('total_costTax');

    return response()->json([
        'insurance' => [
            'labels' => $insuranceData->pluck('InsuranceType'),
            'data' => $insuranceData->pluck('total'),
            'total_cost' => $insuranceCost
        ],
        'tax' => [
            'labels' => $taxData->pluck('TaxType'),
            'data' => $taxData->pluck('total'),
            'total_cost' => $taxCost
        ]
    ]);
}
}
