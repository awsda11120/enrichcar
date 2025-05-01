<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    protected $tblRenew,$tblSett;
    function __construct(){
        $this->tblRenew = "settings_renew";
        $this->tblSett = "settings";
    }

    function index($category=null)
    {
        $types = [
            "car_type" => "ประเภทรถยนต์",
            "car_brand" => "ประเภทรถจักรยานยนต์"
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

    function cost()
    {
        $types = DB::table('settings')->whereIn('category_key', ['car_type', 'car_brand'])->get();
        // $costs = DB::table($this->tblRenew)->orderBy('id', 'asc')->get();
        $costs =  DB::table($this->tblRenew.' as rn')
        ->join($this->tblSett.' as sett','sett.id','=','rn.cartype_id')
        ->select('rn.*','sett.name')
        ->orderBy('sett.name', 'asc')
        ->get();
        // echo"<pre>";
        // print_r($costs);
        // echo"</pre>";
        return view('setting_cost',["costs"=>$costs,"types"=>$types]);
    }

    function addcost(Request $req){
        $cost = (object) $req->cost;
        $d=[
            'cartype_id'=> $cost->type,
            'renew_cost'=>$cost->renew,
            'fee'=>$cost->service,
            'delivery_cost'=>$cost->deliver
        ];

        $obj  =  DB::table($this->tblRenew)
                 ->where('cartype_id',$cost->type);

        $findCost = $obj->first();
        $rows = count((array)$findCost);
        $result = 0; // fail to update or insert new record
        if(!$rows){
            //insert new cost
             $result = DB::table($this->tblRenew)->insert($d);
        }else{
            //update
            $result = $obj->update($d);
        }
        return redirect('/settings/cost');
    }


    function remove_cost($id){
        DB::table($this->tblRenew)->where('id',$id)->delete();
        return redirect('/settings/cost');
    }


    function addgeneral(Request $req){
        $sett = (object) $req->sett;
        $d=[
            'category_key'=> $sett->type,
            'name'=>$sett->name
        ];
        $editId = 0;
        $result = 0; // fail to update or insert new record
        if(!$editId){
            //insert new cost
             $result = DB::table($this->tblSett)->insert($d);
        }else{
            //update
            $result = $obj->update($d);
        }
        return redirect('/settings/general');
    }

    function remove_general($id){
        DB::table($this->tblSett)->where('id',$id)->delete();
        return redirect('/settings/general');
    }

    function CalTax($id){
        DB::table($this->tblSett)->where('id',$id)->delete();
        return redirect('/settings/general');
    }
}
