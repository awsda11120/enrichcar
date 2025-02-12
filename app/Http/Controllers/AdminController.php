<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Car;
use Carbon\Carbon;

class AdminController extends Controller
{

    //test test test

    // public function index(Request $request)
    // {
    //     $types = [
    //         "info_last" => "ดูข้อมูลจากที่เพิ่มล่าสุด",
    //         "info_Ins" => "ดูข้อมูลจาก พ.ร.บ. ที่กำลังจะหมดอายุ",
    //         "info_Tax" => "ดูข้อมูลจากภาษีที่กำลังจะหมดอายุ"
    //     ];

    //     $obj = DB::table($this->tblSett)
    //             ->select(["id","category_key as key","name"]);
    //     if($category){
    //         $obj->where('category_key',$category);
    //     }
    //     $list = $obj->orderBy('category_key', 'asc')
    //             ->orderBy('name', 'asc')
    //             ->get();
    //     return view('setting_general',[
    //         "list"=>$list,
    //         "types"=>$types,
    //         "category" => $category
    //     ]);
    // }

    function showHis($id)
    {
        $car = DB::table('cars')->where('id', $id)->first();
        // $his = DB::table('histories as his')
        // // ->join('cars as c','his.id','=','c.id')
        // ->select('cars.CarNumber','cars.BookOwner','cars.SelectOption')
        // ->get();
        return view('history',compact('car'));
    }
    



    function showIn($id)
    {
        // $customer = DB::table('customers')->where('id', $id)->first();
        // $car = DB::table('cars')->where('id', $id)->first(); // ดึงข้อมูลรถที่เกี่ยวข้องกับลูกค้า
        // $total_year = session('total_year');
    $List = DB::table('cars as c')
    ->join('customers as cs', 'c.CusId', '=', 'cs.id')
    ->select('c.id','c.BookOwner', 'cs.CustomerName','cs.NationalID','cs.PhoneNumber',
    'cs.Address','c.SelectOption','c.TaxHistoryDate','c.InsHistoryDate','c.TaxId',
    'c.CarCC','c.CarWeight','c.RegistrationDate')
    ->where('c.id', $id) 
    ->first();
    // $total_year = session('total_year');



        $tax = $List->RegistrationDate;
        $ins = $List->InsHistoryDate;

        // $info = Car::findOrFail($id);
    
        // $today = Carbon::today();
    
        // $prb_expire_date = Carbon::parse($info->RegistrationDate);
        // $tax_expire_date = Carbon::parse($info->RegistrationDate);
    
        // $prb_days_left = $today->diffInDays($prb_expire_date, false);
        // $tax_days_left = $today->diffInDays($tax_expire_date, false);

            $register_day = date_create(date('Y-m-d',strtotime($tax)));
            $today = date_create(date('Y-m-d'));
            $diff = date_diff($register_day,$today);
            $days = (int)$diff->format('%a')%365;     // เศษวัน
            $total_year = floor((int)$diff->format('%a')/365);           // $regArr[1] = month
            $regArr = explode("-",$tax);  // y , m , d =>$regArr[2] = day
            // update year
            $regArr[0] +=  $total_year;
            $regArr[0] +=  ($days>0) ? 1 : 0;

            // repack renew day
            // $List[$index]->renew = implode("/",array_reverse($regArr));

            //calculate days to renew from today
            $date_renew =  date_create(date('Y-m-d',strtotime(implode("-",$regArr))));
            $due_date_diff = date_diff($today,$date_renew);
            $days = (int)$due_date_diff->format('%a')%365;
            // $List[$index]->days = $days;
            // $List[$index]->cls = ($days<=$d_danger) ? "bg_danger" : (($days>$d_danger&&$days<=$d_warning) ? "bg_warning" : "") ;
            // $List[$index]->disabled = ($days<=$d_danger) ? "" : "disabled" ;
            $date = (date('Y-m-d',strtotime($ins))); // Replace with your date
            $newDate = date('d/m/Y', strtotime('+365 days', strtotime($date)));
            // $List[$index]->next_Ins = $newDate;
            $ins = date('Y-m-d',strtotime('+365 days', strtotime($date)));
            $ins = date_create($ins);
            $diff_ins = date_diff($today,$ins);
            $days_ins = (int)$diff_ins->format('%a')%365;

            echo"<br>".$days_ins."=>".$days;


           

        // return view('infomation', compact('customer','car','total_year'));
        return view('infomation', compact('days_ins','days'),["list"=>$List]);
        
    }



    // function renewCheck($id)
    // {
    //     $customer = DB::table('customers')->where('id', $id)->first();
    //     $car = DB::table('cars')->where('id', $id)->first();
    //     return view('CheckRenew', compact('customer','car'));

    // }
    // function customer()
    // {
    //     $customers = DB::table('customers')->get();
    //     return view('infomation', compact('customers'));
    // }
    // function car()
    // {
    //     $cars = DB::table('cars')->get();
    //     return view('car', compact('cars'));
    // }

    function info(){
        $List =  DB::table('cars as c')
                 ->join('customers as cs','c.CusId','=','cs.id')
                 ->select('c.CarNumber','c.RegistrationDate','c.InsHistoryDate','cs.CustomerName','cs.PhoneNumber','cs.id','c.BookOwner','c.id')
                 ->get();

        // calculate to renew ins
        foreach ($List as $index => $item) {
            $d_warning = 90;
            $d_danger = 30;
            $ins_warning = 90;
            $ins_danger = 30;


            $register_day = date_create(date('Y-m-d',strtotime($item->RegistrationDate)));
            $today = date_create(date('Y-m-d'));
            $diff = date_diff($register_day,$today);
            $days = (int)$diff->format('%a')%365;     // เศษวัน
            $total_year = floor((int)$diff->format('%a')/365);           // $regArr[1] = month
            $regArr = explode("-",$item->RegistrationDate);  // y , m , d =>$regArr[2] = day
            // update year
            $regArr[0] +=  $total_year;
            $regArr[0] +=  ($days>0) ? 1 : 0;

            // repack renew day
            $List[$index]->renew = implode("/",array_reverse($regArr));

            //calculate days to renew from today
            $date_renew =  date_create(date('Y-m-d',strtotime(implode("-",$regArr))));
            $due_date_diff = date_diff($today,$date_renew);
            $days = (int)$due_date_diff->format('%a')%365;
            // $List[$index]->days = $days;
            // $List[$index]->cls = ($days<=$d_danger) ? "bg_danger" : (($days>$d_danger&&$days<=$d_warning) ? "bg_warning" : "") ;
            // $List[$index]->disabled = ($days<=$d_danger) ? "" : "disabled" ;
            $date = (date('Y-m-d',strtotime($item->InsHistoryDate))); // Replace with your date
            $newDate = date('d/m/Y', strtotime('+365 days', strtotime($date)));
            $List[$index]->next_Ins = $newDate;
            $ins = date('Y-m-d',strtotime('+365 days', strtotime($date)));
            $ins = date_create($ins);
            $diff_ins = date_diff($today,$ins);
            $days_ins = (int)$diff_ins->format('%a')%365;
            // $List[$index]->cls2 = ($days_ins<=$d_danger) ? "bg_danger" : (($days_ins>$d_danger&&$days<=$d_warning) ? "bg_warning" : "") ;
            // $List[$index]->disabled = ($days_ins<=$d_danger) ? "" : "disabled" ;
            // echo"<br>".$diff;
            //  echo"<br>".$days_ins."=>".$days;
            if ($days <= $d_danger) {
                $List[$index]->cls = "bg_danger";
            } elseif ($days > $d_danger && $days <= $d_warning) {
                $List[$index]->cls = "bg_warning";
            }

            elseif ($days_ins <= $ins_danger) {
                $List[$index]->cls = "ins_danger";
            } elseif ($days_ins > $ins_danger && $days_ins <= $ins_warning) {
                $List[$index]->cls = "ins_warning";
            } else {
                $List[$index]->cls = ""; // no class if it doesn't match the conditions
                // $List[$index]->cls = ""; // no class if it doesn't match the conditions
            }

        }

        // foreach ($List as $index => $item) {
        //     $d_warning = 90;
        //     $d_danger = 30;
        //     $d_null;

        //     $date = (date('Y-m-d',strtotime($item->InsHistoryDate))); // Replace with your date
        //     $newDate = date('d/m/Y', strtotime('+365 days', strtotime($date)));
        //     $List[$index]->next_Ins = $newDate;
        //     // $Ins_HisDay = date_create(date('Y-m-d',strtotime($item->InsHistoryDate)));
        //     $today = date_create(date('Y-m-d'));
        //     // $diff = date_diff($today,$Ins_HisDay);
        //     // $days = (int)$diff->format('%a');
        //     // // $List[$index]->next_Ins = $diff;
        //     // if ($days >= 270 && $days <= 330) {
        //     //     $List[$index]->cls = "bg_warning";
        //     // } else if ($days >= 330 && $days <= 365) {
        //     //     $List[$index]->cls = "bg_danger";
        //     // } else if($days >= 365){
        //     //     $List[$index]->cls = "";
        //     // }
        //     // $List[$index]->disabled = ($days<=$d_danger) ? "" : "disabled" ;

        //     $ins = date('Y-m-d',strtotime('+365 days', strtotime($date)));
        //     $ins = date_create($ins);
        //     $diff_ins = date_diff($today,$ins);
        //     $days_ins = (int)$diff_ins->format('%a')%365;
        //     $List[$index]->cls2 = ($days_ins<=$d_danger) ? "bg_danger" : (($days_ins>$d_danger&&$days<=$d_warning) ? "bg_warning" : "") ;
        //     $List[$index]->disabled = ($days_ins<=$d_danger) ? "" : "disabled" ;
        //     // echo"<br>".$diff;
        //      echo"<br>".$days_ins;
        // }\

        session(['total_year' => $total_year]);
        return view('info',["list"=>$List]);

    }
    // function history(){
    //     $his = DB::table('histories as his')
    //     ->join('cars','his.CarID','=','cars.id')
    //     ->select('cars.CarNumber','cars.BookOwner','cars.SelectOption')
    //     ->get();
    //     // return view('history',["his"=>$his]);
    //     return redirect('history');
    // }

    // function InsView(){
    //     $List =  DB::table('cars as c')
    //              ->join('customers as cs','c.CusId','=','cs.id')
    //              ->select('c.CarNumber','c.RegistrationDate','c.InsHistoryDate','cs.CustomerName','cs.PhoneNumber','cs.id')
    //              ->get();

    //     // calculate to renew ins
    //     // foreach ($List as $index => $item) {
    //     //     $d_warning = 60;
    //     //     $d_danger = 30;

    //     //     $register_day = date_create(date('Y-m-d',strtotime($item->RegistrationDate)));
    //     //     $today = date_create(date('Y-m-d'));
    //     //     $diff = date_diff($register_day,$today);
    //     //     $days = (int)$diff->format('%a')%365;     // เศษวัน
    //     //     $total_year = floor((int)$diff->format('%a')/365);           // $regArr[1] = month
    //     //     $regArr = explode("-",$item->RegistrationDate);  // y , m , d =>$regArr[2] = day
    //     //     // update year
    //     //     $regArr[0] +=  $total_year;
    //     //     $regArr[0] +=  ($days>0) ? 1 : 0;

    //     //     // repack renew day
    //     //     $List[$index]->renew = implode("/",array_reverse($regArr));

    //     //     //calculate days to renew from today
    //     //     $date_renew =  date_create(date('Y-m-d',strtotime(implode("-",$regArr))));
    //     //     $due_date_diff = date_diff($today,$date_renew);
    //     //     $days = (int)$due_date_diff->format('%a')%365;
    //     //     // $List[$index]->days = $days;
    //     //     $List[$index]->cls = ($days<=$d_danger) ? "bg_danger" : (($days>$d_danger&&$days<=$d_warning) ? "bg_warning" : "") ;
    //     //     $List[$index]->disabled = ($days<=$d_danger) ? "" : "disabled" ;

    //     // }

    //     foreach ($List as $index => $item) {
    //         $date = (date('Y-m-d',strtotime($item->InsHistoryDate))); // Replace with your date
    //         $newDate = date('d/m/Y', strtotime('+365 days', strtotime($date)));
    //         // $newDate = date("d/m/Y", strtotime($date));
    //         //echo $newDate; // Outputs: 2026-01-21\
    //         $List[$index]->next_Ins = $newDate;

    //     }

    //     return view('InsView',["list"=>$List]);

    // }

    function sum()
    {


        $month = ['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $barChart = [

        ];


        return view('sum',["barChart"=>$barChart]);
    }

    function add(){
        $provinces = DB::table('provinces')->get();
        $tax = DB::table('taxes')->get();
        $settings["type"] = DB::table('settings')->whereIn('category_key', ['car_type', 'car_brand'])->get();
        // $settings["brand"] = DB::table('settings')->where('category_key','=','car_brand')->get();
        return view('add',['prov'=>$provinces,'sett'=>$settings,'tax'=>$tax]);
    }
    function insertInfo(Request $requestInfo){

        $errorMsg = [];
        $requestInfo->validate([
            'CustomerName'=>'required',
            'NationalID'=>'required|min:13|max:13',
            'PhoneNumber'=>'required|min:10|max:10',
            'Address'=>'required',

            'CarNumber'=>'required',
            'CarCity'=>'required',
            'CarWeight'=>'required',
            'CarCC'=>'required',
            'InsuranceType'=>'required',
            'TaxType'=>'required',
            'RegistrationDate'=>'required',
            //'BookOwner'=>'required',
            'TaxHistoryDate'=>'required',
            'SelectOption'=>'required',
            'InsHistoryDate'=>'required',

        ],
        [
            'CustomerName.required'=>'*กรุณาระบุชื่อและนามสกุล*',
            'NationalID.required'=>'*กรุณาระบุเลขบัตรประชาชน*',
            'NationalID.min'=>'*กรุณาระบุเลขบัตรประชาชน 13 หลัก*',
            'NationalID.max'=>'*กรุณาระบุเลขบัตรประชาชน 13 หลัก*',
            'PhoneNumber.required'=>'*กรุณาระบุเบอร์โทร*',
            'PhoneNumber.min'=>'*กรุณาระบุเบอร์โทร 10 หลัก*',
            'PhoneNumber.max'=>'*กรุณาระบุเบอร์โทร 10 หลัก*',
            'Address.required'=>'*กรุณาระบุที่อยู่ปัจจุบัน*',

            'CarNumber.required'=>'*กรุณาระบุเลขทะเบียน*',
            'CarCity.required'=>'*กรุณาระบุจังหวัด*',
            'CarWeight.required'=>'*กรุณาระบุน้ำหนัก*',
            'CarCC.required'=>'*กรุณาระบุขนาดกำลัง*',
            'InsuranceType.required'=>'*กรุณาระบุประเภท พ.ร.บ.*',
            'TaxType.required'=>'*กรุณาระบุประเภทภาษี*',
            'RegistrationDate.required'=>'*กรุณาระบุวันที่จดทะเบียน*',
            //'BookOwner'=>'required',
            'TaxHistoryDate.required'=>'*กรุณาระบุวันที่ต่อภาษีครั้งล่าสุด*',
            'SelectOption.required'=>'*กรุณาเลือกการรับเอกสาร*',
            'InsHistoryDate.required'=>'*กรุณาระบุวันที่ต่อ พ.ร.บ. ครั้งล่าสุด*',
        ]);



    $carTypeId = DB::table('settings')
    ->whereIn('category_key', ['car_type', 'car_brand'])
    ->where('name', '=', $requestInfo->InsuranceType)
    ->value('id');

    $carTaxId = DB::table('taxes')
    ->where('name', '=', $requestInfo->TaxType)
    ->value('id');


        $dataCus=[
            'CustomerName'=>$requestInfo->CustomerName,
            'NationalID'=>$requestInfo->NationalID,
            'PhoneNumber'=>$requestInfo->PhoneNumber,
            'Address'=>$requestInfo->Address
        ];

        $PlateNumber = $requestInfo->CarNumber." ".$requestInfo->CarCity;
        $dataCar=[
            'CarNumber'=>$PlateNumber,
            'CarCity'=>$requestInfo->CarCity,
            'CarWeight'=>$requestInfo->CarWeight,
            'CarCC'=>$requestInfo->CarCC,
            'InsuranceType'=>$requestInfo->InsuranceType,
            'TaxType'=>$requestInfo->TaxType,
            'RegistrationDate'=>$requestInfo->RegistrationDate,
            //'BookOwner'=>$requestInfo->RegistrationDate,
            'TaxHistoryDate'=>$requestInfo->TaxHistoryDate,
            'SelectOption'=>$requestInfo->SelectOption,
            'InsHistoryDate'=>$requestInfo->InsHistoryDate,
            'TypeId' => $carTypeId,
            'TaxId' => $carTaxId,


        ];
        // $dataCusAndCar=[
        //     'CarNumber'=>$requestInfo->CarNumber,
        //     'CarCity'=>$requestInfo->CarCity,
        //     'CustomerName'=>$requestInfo->CustomerName,
        //     'PhoneNumber'=>$requestInfo->PhoneNumber,
        //     'RegistrationDate'=>$requestInfo->RegistrationDate

        // ];
        // $file = $requestInfo->file('singleFile');
        // $name =  $file->getClientOriginalExtension();
        // validate and upload single file
            $Img = $_FILES["singleFile"];
            $ext = pathinfo($Img["name"], PATHINFO_EXTENSION);
            // validate file extension
            $ext_avi = ['jpg','png','gif','pdf'];
            $limit_size = 2097152; // bytes
            if(!in_array(strtolower($ext),$ext_avi)){
                $errorMsg[] = "อนุญาตเฉพาะไฟล์ที่มีนามสกุล ".implode(",",$ext_avi)." เท่านั้น";
            }
            //validate file uploaded size
            if($Img["size"]>$limit_size){
                $errorMsg[] = "ขนาดไฟล์ที่อัพโหลดไม่ควรเกิน 2 MB";
            }

            // start upload if no error    !count($x) = ไม่มี errors
            if(!count($errorMsg)){
                $upload_path = public_path()."/upload/doc";
                if (!file_exists($upload_path)) {
                    mkdir($upload_path, 0755, true);   // mkdir == make directory
                    // directory permission code   https://blog.openlandscape.cloud/chmod-777
                }
                if(move_uploaded_file($Img["tmp_name"],$upload_path."/".$Img["name"])){
                    $dataCar["BookOwner"] = $Img["name"];
                }
            }


        // find duplicate the customer by  national id number.
        $findCus =  DB::table('customers')->where('NationalID',$requestInfo->NationalID)->first();
        $rows = count((array)$findCus);
        if(!$rows){
            $CusID = DB::table('customers')->insertGetId($dataCus);
            $dataCar["CusId"] = $CusID;
        }else{
            $dataCar["CusId"] = $findCus->id;
            $errorMsg[] = 'พบข้อมูลบัตรประชาชนถูกใช้งานแล้ว';
        }


        // find duplicate the car by plate number.
        $findCar =  DB::table('cars')->where('CarNumber',$PlateNumber)->first();
        $rows = count((array)$findCar);
        if(!$rows){
            DB::table('cars')->insert($dataCar);
        }else{
            $errorMsg[] = 'ป้ายทะเบียนนี้ถูกใช้งานแล้ว';
        }
        $Url = (count($errorMsg)) ? '/info' : '/info';

        // DB::table('cus_and_cars')->insert($dataCusAndCar);
        return redirect($Url);
    }


    function editInfo($id){
        $customer=DB::table('customers')->where('id',$id)->first();
        $car=DB::table('cars')->where('id',$id)->first();
        $provinces = DB::table('provinces')->get();
        $settings["type"] = DB::table('settings')->where('category_key','=','car_type')->get();
        // $settings["brand"] = DB::table('settings')->where('category_key','=','car_brand')->get();

        return view('editInfo', compact('customer','car'),['prov'=>$provinces,'sett'=>$settings]);
    }

    function updateInfo(Request $requestInfo, $id){


        $errorMsg = [];
        $requestInfo->validate([
            'CustomerName'=>'required',
            'NationalID'=>'required|min:13|max:13',
            'PhoneNumber'=>'required|min:10|max:10',
            'Address'=>'required',

            'CarNumber'=>'required',
            'CarCity'=>'required',
            'CarWeight'=>'required',
            'CarCC'=>'required',
            'InsuranceType'=>'required',
            'TaxType'=>'required',
            'RegistrationDate'=>'required',
            // 'BookOwner'=>'required',
            'TaxHistoryDate'=>'required',
            'SelectOption'=>'required',
            'InsHistoryDate'=>'required',

        ],
        [
            'CustomerName.required'=>'*กรุณาระบุชื่อและนามสกุล*',
            'NationalID.required'=>'*กรุณาระบุเลขบัตรประชาชน*',
            'NationalID.min'=>'*กรุณาระบุเลขบัตรประชาชน 13 หลัก*',
            'NationalID.max'=>'*กรุณาระบุเลขบัตรประชาชน 13 หลัก*',
            'PhoneNumber.required'=>'*กรุณาระบุเบอร์โทร*',
            'PhoneNumber.min'=>'*กรุณาระบุเบอร์โทร 10 หลัก*',
            'PhoneNumber.max'=>'*กรุณาระบุเบอร์โทร 10 หลัก*',
            'Address.required'=>'*กรุณาระบุที่อยู่ปัจจุบัน*',

            'CarNumber.required'=>'*กรุณาระบุเลขทะเบียน*',
            'CarCity.required'=>'*กรุณาระบุจังหวัด*',
            'CarWeight.required'=>'*กรุณาระบุน้ำหนัก*',
            'CarCC.required'=>'*กรุณาระบุขนาดกำลัง*',
            'InsuranceType.required'=>'*กรุณาระบุประเภท พ.ร.บ.*',
            'TaxType.required'=>'*กรุณาระบุประเภทภาษี*',
            'RegistrationDate.required'=>'*กรุณาระบุวันที่จดทะเบียน*',
            // 'BookOwner'=>'required',
            'TaxHistoryDate.required'=>'*กรุณาระบุวันที่ต่อภาษีครั้งล่าสุด*',
            'SelectOption.required'=>'*กรุณาเลือกการรับเอกสาร*',
            'InsHistoryDate.required'=>'*กรุณาระบุวันที่ต่อ พ.ร.บ. ครั้งล่าสุด*',
        ]);
        $dataCus=[
            'CustomerName'=>$requestInfo->CustomerName,
            'NationalID'=>$requestInfo->NationalID,
            'PhoneNumber'=>$requestInfo->PhoneNumber,
            'Address'=>$requestInfo->Address
        ];

        $PlateNumber = $requestInfo->CarNumber." ".$requestInfo->CarCity;
        $dataCar=[
            'CarNumber'=>$PlateNumber,
            'CarCity'=>$requestInfo->CarCity,
            'CarWeight'=>$requestInfo->CarWeight,
            'CarCC'=>$requestInfo->CarCC,
            'InsuranceType'=>$requestInfo->InsuranceType,
            'TaxType'=>$requestInfo->TaxType,
            'RegistrationDate'=>$requestInfo->RegistrationDate,
            // 'BookOwner'=>$requestInfo->RegistrationDate,
            'TaxHistoryDate'=>$requestInfo->TaxHistoryDate,
            'SelectOption'=>$requestInfo->SelectOption,
            'InsHistoryDate'=>$requestInfo->InsHistoryDate,

        ];
        // $dataCusAndCar=[
        //     'CarNumber'=>$requestInfo->CarNumber,
        //     'CarCity'=>$requestInfo->CarCity,
        //     'CustomerName'=>$requestInfo->CustomerName,
        //     'PhoneNumber'=>$requestInfo->PhoneNumber,
        //     'RegistrationDate'=>$requestInfo->RegistrationDate

        // ];
        // $file = $requestInfo->file('singleFile');
        // $name =  $file->getClientOriginalExtension();
        // validate and upload single file

            $Img = $_FILES["singleFile"];
            $ext = pathinfo($Img["name"], PATHINFO_EXTENSION);
            // validate file extension
            $ext_avi = ['jpg','png','gif','pdf'];
            $limit_size = 2097152; // bytes
            if(!in_array(strtolower($ext),$ext_avi)){
                $errorMsg[] = "อนุญาตเฉพาะไฟล์ที่มีนามสกุล ".implode(",",$ext_avi)." เท่านั้น";
            }
            //validate file uploaded size
            if($Img["size"]>$limit_size){
                $errorMsg[] = "ขนาดไฟล์ที่อัพโหลดไม่ควรเกิน 2 MB";
            }

            // start upload if no error    !count($x) = ไม่มี errors
            if(!count($errorMsg)){
                $upload_path = public_path()."/upload/doc";
                if (!file_exists($upload_path)) {
                    mkdir($upload_path, 0755, true);   // mkdir == make directory
                    // directory permission code   https://blog.openlandscape.cloud/chmod-777
                }
                if(move_uploaded_file($Img["tmp_name"],$upload_path."/".$Img["name"])){
                    $dataCar["BookOwner"] = $Img["name"];
                }
            }
        // find duplicate the customer by  national id number.
        $findCus =  DB::table('customers')->where('NationalID',$requestInfo->NationalID)->first();
        $rows = count((array)$findCus);
        if(!$rows){
            $CusID = DB::table('customers')->insertGetId($dataCus);
            $dataCar["CusId"] = $CusID;
        }else{
            $dataCar["CusId"] = $findCus->id;
            $errorMsg[] = 'พบข้อมูลบัตรประชาชนถูกใช้งานแล้ว';
        }

        // find duplicate the car by plate number.
        $findCar =  DB::table('cars')->where('CarNumber',$PlateNumber)->first();
        $rows = count((array)$findCar);
        if(!$rows){
            DB::table('cars')->insert($dataCar);
        }else{
            $errorMsg[] = 'ป้ายทะเบียนนี้ถูกใช้งานแล้ว';
        }
        $Url = (count($errorMsg)) ? '/info' : '/info';



        // DB::table('cus_and_cars')->insert($dataCusAndCar);
        DB::table('customers')->where('id',$id)->update($dataCustomer);
        DB::table('cars')->where('id',$id)->update($dataCar);
        return redirect('infomation');

    }
}
