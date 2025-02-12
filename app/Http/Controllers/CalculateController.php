<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CalculateController extends Controller
{
    public function CalCosts($id)
    {
        $data = DB::table('cars as c')
        ->join('customers as cs', 'c.CusId','=', 'cs.id')
        ->join('settings_renew as sr', 'sr.cartype_id','=', 'c.TypeID')
        ->select('c.id','c.BookOwner', 'cs.CustomerName','cs.NationalID','cs.PhoneNumber',
        'cs.Address','c.SelectOption','c.TaxHistoryDate','c.InsHistoryDate','c.TaxId',
        'c.CarCC','c.CarWeight','c.InsuranceType','c.TypeId','sr.renew_cost','sr.fee','sr.delivery_cost')
        ->where('c.id', $id)
        ->first();

        // $selectedOptions = $request->input('options', []);
        // $customer = DB::table('customers')->where('id', $id)->first();
        // $car = DB::table('cars')->where('CusId', $id)->first();
        // $settRe = DB::table('settings_renew')->get();
        // $tax = DB::table('cars')->where('CusId', $id)->value('TaxId');
        // $cc = DB::table('cars')->where('CusId', $id)->value('CarCC');
        // $weight = DB::table('cars')->where('CusId', $id)->value('CarWeight');
        
        // $type = DB::table('cars')->where('TypeId', "=", $id)->value('TypeId');
        // echo $tax;
        // echo $cc;

        $tax = $data->TaxId;
        $cc = $data->CarCC;
        $weight = $data->CarWeight;
        $rn = $data->renew_cost;
        $f = $data->fee;
        $del = $data->delivery_cost;

        $sum = 0;
        if($tax == 1){
            if($cc >= 1801){
                $sum = (600*0.5);
                $sum += (1800-600)*1.5;
                $sum += ($cc-1800)*4;
            }else if($cc <= 1800){
                $sum = (600*0.5);
                $sum += ($cc-600)*1.5;
            }
        }else if($tax == 2){
            if($weight >= 0 && $weight <= 500){
                $sum = 300;
            }else if($weight >= 501 && $weight <= 750){
                $sum = 450;
            }else if($weight >= 751 && $weight <= 1000){
                $sum  = 600;
            }else if($weight >= 1001 && $weight <= 1250){
                $sum = 750;
            }else if($weight >= 1251 && $weight <= 1500){
                $sum = 900;
            }else if($weight >= 1501 && $weight <= 1750){
                $sum = 1050;
            }else if($weight >= 1751 && $weight <= 2000){
                $sum = 1350;
            }else if($weight >= 2001 && $weight <= 2500){
                $sum = 1650;
            }else if($weight >= 3000){
                $sum = 1950;
            }
        }else if($tax == 3){
            if($weight <= 1800){
                $sum = 1300;
            }else if($weight > 1800){
                $sum = 1600;
            }
        }
        $sum_cost = 0;
        $sum_cost += $sum+$rn+$f+$del;


        // echo $tax ,"+" ,$cc ,"+", $weight, "+" ,$sum;
        return view('CheckCosts', compact('sum','sum_cost'),["data"=>$data]);
        // return view('CheckCosts', compact('customer','car','settRe','sum'));
       

        // $List =  DB::table('setting_renews as sr')
        // ->join('cars as c','c.id','=','sr.id')
        // ->select('c.CarType','c.RegistrationDate','c.InsHistoryDate','cs.CustomerName','cs.PhoneNumber','cs.id')
        // ->get();

        // // calculate to renew ins
        // foreach ($List as $index => $item) {
        //     $d_warning = 90;
        //     $d_danger = 30;
        //     $ins_warning = 90;
        //     $ins_danger = 30;
        // }
    }
}
