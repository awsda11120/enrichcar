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

    return view('sum', compact('InsChart', 'TaxChart', 'InsCostChart', 'TaxCostChart'));
}


}
