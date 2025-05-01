<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon; 

class CalculateController extends Controller
{
    public function CalCosts(Request $request, $id)
    {
        $data = DB::table('cars as c')
            ->join('customers as cs', 'c.CusId', '=', 'cs.id')
            ->join('settings_renew as sr', 'sr.cartype_id', '=', 'c.TypeID')
            ->select('c.id', 'c.RegistrationDate', 'c.BookOwner', 'cs.CustomerName', 'cs.NationalID', 'cs.PhoneNumber',
                'cs.Address', 'c.SelectOption', 'c.TaxHistoryDate', 'c.InsHistoryDate', 'c.TaxId',
                'c.CarCC', 'c.CarWeight', 'c.InsuranceType', 'c.TypeId', 'sr.renew_cost', 'sr.fee', 'sr.delivery_cost')
            ->where('c.id', $id)
            ->first();

        if (!$data) {
            return redirect()->back()->with('error', 'ไม่พบข้อมูลรถยนต์');
        }

        $regis = Carbon::parse($data->RegistrationDate);
        $carYears = intval($regis->diffInYears(Carbon::now())); // คำนวณอายุรถ

        $SelectOption = $data->SelectOption;
        $tax = $data->TaxId;
        $cc = $data->CarCC;
        $weight = $data->CarWeight;
        $renew_cost = $data->renew_cost;
        $fee = $data->fee;
        $delivery_cost = $data->delivery_cost;

        // ตรวจสอบค่าที่ติ๊กมาจากฟอร์ม
        $calculateTax = $request->has('renew_tax');
        $calculateRenew = $request->has('renew_prb');

        // คำนวณค่าภาษี
    $sum_tax = 0;
    $original_tax = 0; // เก็บค่าภาษีก่อนลด

    $discountPercent = 0;
    $discountAmount = 0;

    if ($calculateTax) {
        if ($tax == 1) { // รถยนต์นั่งส่วนบุคคลไม่เกิน 7 คน
            if ($cc >= 1801) {
                $original_tax = (600 * 0.5) + ((1800 - 600) * 1.5) + (($cc - 1800) * 4);
            } else if ($cc <= 1800) {
                $original_tax = (600 * 0.5) + (($cc - 600) * 1.5);
            }
        }else if ($tax == 2) { // รถยนต์นั่งส่วนบุคคลเกิน 7 คน
            $original_tax = ($weight <= 1800) ? 1300 : 1600;

        } else if ($tax == 3) { // รถยนต์บรรทุกส่วนบุคคล
            $original_tax = match (true) {
                $weight <= 500 => 300,
                $weight <= 750 => 450,
                $weight <= 1000 => 600,
                $weight <= 1250 => 750,
                $weight <= 1500 => 900,
                $weight <= 1750 => 1050,
                $weight <= 2000 => 1350,
                $weight <= 2500 => 1650,
                default => 1950,
            };
        } 

        // คำนวณส่วนลด
        $sum_tax = $original_tax;
        if ($carYears >= 6) {
            $discountPercent = min(($carYears - 5) * 10, 50); // จำกัดส่วนลดสูงสุดที่ 50%
            $discountAmount = $sum_tax * ($discountPercent / 100);
            $sum_tax -= $discountAmount;
        } 
    }

        // คำนวณค่าพ.ร.บ. ถ้ามีการเลือก
        $sum_renew = $calculateRenew ? $renew_cost : 0;

        // คำนวณค่าบริการ
        $TaxIncome = $calculateTax ? $fee : 0;
        $InsIncome = $calculateRenew ? $fee : 0;

        // คำนวณค่าจัดส่ง (มีค่าจัดส่งถ้ามีการต่ออายุ)
        // $sum_delivery = ($calculateTax || $calculateRenew) ? $delivery_cost : 0;
        $sum_delivery = ($SelectOption == 'จัดส่งตามที่อยู่') ? $delivery_cost : 0;

        // รวมยอดเงินทั้งหมด
        $sum_cost = $sum_tax + $sum_renew + $TaxIncome + $InsIncome + $sum_delivery;

        // $data->carYears = $carYears;
        // $data->discountAmount = $discountAmount;
        // $data->discountPercent = $discountPercent;

        return view('CheckCosts', compact('carYears','original_tax','discountAmount','discountPercent','sum_tax', 'sum_renew', 'InsIncome', 'TaxIncome', 'sum_delivery', 'sum_cost', 'data', 'calculateTax', 'calculateRenew'));
    }
}
