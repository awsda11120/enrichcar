<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CalculateController extends Controller
{
    public function CalCosts($id)
    {
        // $selectedOptions = $request->input('options', []);
        $customer = DB::table('customers')->where('id', $id)->first();
        $car = DB::table('cars')->where('CusId', $id)->first();
        $settRe = DB::table('settings_renew')->get();
        // $type = DB::table('cars')->where('TypeId', "=", $id)->value('TypeId');
        // echo $type;
        


        return view('CheckCosts', compact('customer','car','settRe'));

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
