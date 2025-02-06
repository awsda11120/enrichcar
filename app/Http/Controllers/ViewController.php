<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ViewController extends Controller
{
    function index($category=null)
    {
        $types = [
            "car_type" => "ประเภทรถ",
            "motor_type" => "ยี่ห้อรถ"
        ];

        $obj = DB::table($this->tblSett)
                ->select(["id","category_key as key","name"]);
        if($category){
            $obj->where('category_key',$category);
        }
        $list = $obj->orderBy('category_key', 'asc')
                ->orderBy('name', 'asc')
                ->get();
        return view('setting_general',[
            "list"=>$list,
            "types"=>$types,
            "category" => $category
        ]);
    }
}

//test test
//test ertyupip[o]5555559999
//aeng888
//pppoqweeercf
