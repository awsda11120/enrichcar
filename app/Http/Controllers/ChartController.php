<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car; // นำเข้า Model ที่ใช้ดึงข้อมูล

class ChartController extends Controller
{
    public function CarChart()
    {
        // ดึงข้อมูลจากฐานข้อมูล (เช่น นับจำนวนแต่ละประเภท)
        $CarChart = Car::selectRaw('CarType, COUNT(*) as total')
                            ->groupBy('CarType')
                            ->get();

        // ส่งข้อมูลไปที่หน้า View
        return view('sum', compact('CarChart'));
    }
}
