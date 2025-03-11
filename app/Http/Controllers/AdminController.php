<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Car;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    

//     public function updateDateRenew(Request $request)
// {
    
//     try {
//         $request->validate([
//             'history_id' => 'required|integer|exists:histories,id',
//             'date_renew' => 'required|date',
//         ]);

//         // อัปเดตวันที่และสถานะ
//         $updated = DB::table('histories')->where('id', $request->history_id)
//                     ->update([
//                         'DateRenew' => $request->date_renew,
//                         'status' => 1 // เปลี่ยนสถานะเป็น "เสร็จสิ้น"
//                     ]);

//         if ($updated) {
//             return response()->json(['success' => true, 'message' => 'บันทึกสำเร็จ']);
//         } else {
//             return response()->json(['success' => false, 'message' => 'บันทึกไม่สำเร็จ']);
//         }
//     } catch (\Exception $e) {
//         return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
//     }

// }

// public function updateDateRenew(Request $request)
// {
//     try {
//         $request->validate([
//             'history_id' => 'required|integer|exists:histories,id',
//             'date_renew' => 'required|date',
//         ]);

//         // ดึงข้อมูลจากตาราง histories โดยใช้ history_id
//         $historyRecord = DB::table('histories')->where('id', $request->history_id)->first();

//         if (!$historyRecord) {
//             return response()->json(['success' => false, 'message' => 'ไม่พบข้อมูล history']);
//         }

//         $carId = $historyRecord->CarId;

//         // เช็คว่ามี CarId หรือไม่
//         if (!$carId) {
//             return response()->json(['success' => false, 'message' => 'ไม่พบ CarId']);
//         }

//         // ตรวจสอบค่า TypeRenewIns และ TypeRenewTax
//         $carUpdateData = [];

//         // ถ้า TypeRenewIns = 1 ให้เพิ่ม InsHistoryDate ใน carUpdateData
//         if ($historyRecord->TypeRenewIns == 1) {
//             $carUpdateData['InsHistoryDate'] = $request->date_renew;
//         }

//         // ถ้า TypeRenewTax = 1 ให้เพิ่ม TaxHistoryDate ใน carUpdateData
//         if ($historyRecord->TypeRenewTax == 1) {
//             $carUpdateData['TaxHistoryDate'] = $request->date_renew;
//         }

//         // ถ้ามีข้อมูลที่จะอัปเดตใน cars
//         if (!empty($carUpdateData)) {
//             // อัปเดตตาราง cars
//             DB::table('cars')->where('id', $carId)->update($carUpdateData);
//         }

//         // อัปเดตวันที่และสถานะในตาราง histories
//         DB::table('histories')->where('id', $request->history_id)
//             ->update([
//                 'DateRenew' => $request->date_renew,
//                 'status' => 1 // เปลี่ยนสถานะเป็น "เสร็จสิ้น"
//             ]);

//         // รีเฟรชข้อมูล
//         $this->info();

//         return response()->json(['success' => true, 'message' => 'บันทึกสำเร็จ']);
//     } catch (\Exception $e) {
//         return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
//     }
// }


public function updateDateRenew(Request $request)
{
    try {
        $request->validate([
            'history_id' => 'required|integer|exists:histories,id',  // ตรวจสอบ history_id จากฐานข้อมูล histories
            'date_renew' => 'required|date',
        ]);

        // ดึงข้อมูลจากตาราง histories โดยใช้ history_id
        $history = DB::table('histories')->where('id', $request->history_id)->first();

        if (!$history) {
            return response()->json(['success' => false, 'message' => 'ไม่พบข้อมูลประวัติ']);
        }

        // อัปเดตข้อมูลในตาราง histories
        $updated = DB::table('histories')->where('id', $history->id)
            ->update([
                'DateRenew' => $request->date_renew,
                'status' => 1 // เปลี่ยนสถานะเป็น "เสร็จสิ้น"
            ]);

        if ($updated) {
            // อัปเดตข้อมูลในตาราง cars
            $carUpdateData = [];
            if ($history->TypeRenewIns == 1) {
                $carUpdateData['InsHistoryDate'] = $request->date_renew;
            }
            if ($history->TypeRenewTax == 1) {
                $carUpdateData['TaxHistoryDate'] = $request->date_renew;
            }

            // ตรวจสอบ CarId
            if (isset($history->CarId)) {
                DB::table('cars')->where('id', $history->CarId)->update($carUpdateData);
            } else {
                return response()->json(['success' => false, 'message' => 'ไม่พบหมายเลขรถ']);
            }

            return response()->json(['success' => true, 'message' => 'บันทึกสำเร็จ']);
        } else {
            return response()->json(['success' => false, 'message' => 'บันทึกไม่สำเร็จ']);
        }
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}







public function storeHistory(Request $request)
{
    $type_ins = null;
    $type_tax = null;
    
    if ($request->input('calculateRenew') == 1) {
        $type_ins = 1;
    } else if ($request->input('calculateRenew') == 0) {
        $type_ins = 0;
    }

    if ($request->input('calculateTax') == 1) {
        $type_tax = 1;
    } else if ($request->input('calculateTax') == 0) {
        $type_tax = 0;
    }

    $receive_option = $request->input('receive_option', '');

    // ตรวจสอบว่ามีข้อมูลที่เหมือนกันแล้วในฐานข้อมูลหรือยัง
    $existingHistory = DB::table('histories')
                         ->where('CarId', $request->car_id)
                         ->where('TypeRenewIns', $type_ins)  // ตรวจสอบ TypeRenewIns
                         ->where('TypeRenewTax', $type_tax)  // ตรวจสอบ TypeRenewTax
                         ->whereNull('DateRenew')  // ตรวจสอบว่า DateRenew เป็น null หรือไม่
                         ->first();

    if ($existingHistory) {
        return redirect()->route('history.index')->with('error', 'ข้อมูลนี้มีอยู่แล้วในระบบ');
    }

    // ถ้ายังไม่มีข้อมูลซ้ำ ก็สามารถเพิ่มข้อมูลได้
    $dataData = [
        'CarId' => $request->car_id, // บันทึก Car ID ที่ส่งมาจากฟอร์ม
        'DateRenew' => null, // บันทึกวันที่ทำรายการ
        'TypeRenewIns' => $type_ins,
        'TypeRenewTax' => $type_tax,
        'Receive' => $receive_option, // บันทึกการรับเอกสาร
        'ProofOfReceive' => null, // ยังไม่มีหลักฐานการรับ
        'SumCost' => $request->total_cost,
    ];

    DB::table('histories')->insert($dataData);

    // ดึงข้อมูลล่าสุดจากฐานข้อมูล
    $List = DB::table('histories as htr')
              ->join('cars as c', 'htr.CarId', '=', 'c.id')
              ->select('c.CarNumber', 'htr.status', 'htr.Receive', 'htr.id as history_id', 'htr.TypeRenewIns', 'htr.TypeRenewTax', 'c.id as car_id', 'htr.DateRenew', 'c.BookOwner')
              ->get();

    return view('history', ['list' => $List]);
}



    function showHis()
    {
        $List =  DB::table('histories as htr')
                 ->join('cars as c','htr.CarId','=','c.id')
                 ->select('c.CarNumber','htr.status','htr.Receive','htr.id as history_id','htr.TypeRenewIns','htr.TypeRenewTax','c.id as car_id','htr.DateRenew','c.BookOwner')
                 ->get();
    // $cID = $List->id;
        // $his_id = $List->id;

        return view('history',['list' => $List]);
    }




    public function showIn($id)
{
    $List = DB::table('cars as c')
        ->join('customers as cs', 'c.CusId', '=', 'cs.id')
        ->select('c.id','c.BookOwner', 'cs.CustomerName','cs.NationalID','cs.PhoneNumber',
            'cs.Address','c.SelectOption','c.TaxHistoryDate','c.InsHistoryDate','c.TaxId',
            'c.CarCC','c.CarWeight','c.RegistrationDate')
        ->where('c.id', $id)
        ->first();

    $registrationDate = Carbon::parse($List->RegistrationDate);
    $carYears = intval($registrationDate->diffInYears(Carbon::now()));
    $months = intval($registrationDate->diffInMonths(Carbon::now()) % 12); // หาจำนวนเดือน

    // คำนวณวันหมดอายุภาษี
    $today = date_create(date('Y-m-d')); // วันที่ปัจจุบัน
    $regArr = explode("-", $List->RegistrationDate);
    $regDay = $regArr[2];  // วัน
    $regMonth = $regArr[1]; // เดือน

    // คำนวณวันหมดอายุภาษี
    $taxExpireDate = date_create($List->TaxHistoryDate); // วันต่อภาษีครั้งล่าสุด
    $taxExpireDate->modify('+1 year'); // เพิ่ม 1 ปีจากวันต่อภาษี
    $taxExpireDate->setDate($taxExpireDate->format('Y'), $regMonth, $regDay); // เปลี่ยนปีจาก TaxHistoryDate และใช้เดือน/วันจาก RegistrationDate
    $List->tax_expiry_date = $taxExpireDate->format('d/m/Y'); // วันหมดอายุภาษีในรูปแบบ วัน/เดือน/ปี

    // คำนวณจำนวนวันที่เหลือจนถึงวันหมดอายุภาษี
    $diff_tax = date_diff($today, $taxExpireDate); // ความต่างระหว่างวันที่ปัจจุบันและวันหมดอายุภาษี
    $days_left = (int)$diff_tax->format('%a'); // จำนวนวันเหลือจนถึงวันหมดอายุภาษี

    // ตรวจสอบว่า $taxExpireDate อยู่ในอดีตหรือไม่
    if ($taxExpireDate < $today) {
        // ถ้า $taxExpireDate เก่ากว่า $today ให้ผลลัพธ์เป็นค่าลบ
        $days_left = -(int)$diff_tax->format('%a') ;
    } else {
        // ถ้า $taxExpireDate อยู่ในอนาคตหรือวันนี้
        $days_left = (int)$diff_tax->format('%a') ;
    }

    // คำนวณจำนวนวันจนถึงวันหมดอายุทะเบียน
    $register_day = date_create(date('Y-m-d', strtotime($List->RegistrationDate)));
    $diff_register = date_diff($register_day, $today);
    $days = (int)$diff_register->format('%a') % 365; // เศษวัน

    // คำนวณวันหมดอายุพรบ.
    $date = (date('Y-m-d', strtotime($List->InsHistoryDate))); // Replace with your date
    $newDate = date('d/m/Y', strtotime('+365 days', strtotime($date)));
    $List->next_Ins = $newDate;
    $ins = date('Y-m-d', strtotime('+365 days', strtotime($date)));
    $ins = date_create($ins);
    $diff_ins = date_diff($today, $ins);

    // ตรวจสอบว่า $ins อยู่ในอดีตหรือไม่ ถ้าอยู่ในอดีตให้แสดงค่าเป็นลบ
    if ($ins < $today) {
        // ถ้า $ins เก่ากว่า $today ให้ผลลัพธ์เป็นค่าลบ
        $days_ins = -(int)$diff_ins->format('%a') % 365;
    } else {
        // ถ้า $ins อยู่ในอนาคตหรือวันนี้
        $days_ins = (int)$diff_ins->format('%a') % 365;
    }

    $total_year = floor((int)$diff_register->format('%a') / 365); // จำนวนปีที่เกิน

    // ส่งข้อมูลไปยัง view
    // $List->days = $days;
    $List->days_ins = $days_ins;
    $List->days = $days_left; // เพิ่มการส่งค่าจำนวนวันเหลือภาษี
    echo "<br>" . $days_ins . " => " . $days_left;

    // ส่งไปยังหน้า view
    return view('infomation', compact('days_left','days_ins', 'days', 'carYears'), ["list" => $List]);
} 




    public function info()
    {
        try {
            $List = DB::table('cars as c')
                     ->join('customers as cs', 'c.CusId', '=', 'cs.id')
                     ->select('c.CarNumber', 'c.TaxHistoryDate', 'c.RegistrationDate', 'c.InsHistoryDate', 'cs.CustomerName', 'cs.PhoneNumber', 'cs.id', 'c.BookOwner', 'c.id')
                     ->get();
    
            // คำนวณการต่ออายุภาษีและประกัน
            foreach ($List as $index => $item) {
                $d_warning = 90;
                $d_danger = 30;
                $d_expire = 0;
                // $ins_warning = 90;
                // $ins_danger = 30;
    
    
                $today = date_create(date('Y-m-d')); // วันที่ปัจจุบัน
    
                // แยกวัน, เดือน, ปี จาก RegistrationDate
                $regArr = explode("-", $item->RegistrationDate);
                $regDay = $regArr[2];  // วัน
                $regMonth = $regArr[1]; // เดือน
                $regYear = $regArr[0]; // ปี
        
                // ใช้ TaxHistoryDate เป็นฐานในการคำนวณปี
                $taxExpireDate = date_create($item->TaxHistoryDate); // วันต่อภาษีครั้งล่าสุด
                $taxExpireDate->modify('+1 year'); // เพิ่ม 1 ปีจากวันต่อภาษี
        
                // ใช้ปีจาก TaxHistoryDate แต่ใช้วันเดือนจาก RegistrationDate
                $taxExpireDate->setDate($taxExpireDate->format('Y'), $regMonth, $regDay); // เปลี่ยนปีจาก TaxHistoryDate และใช้เดือน/วันจาก RegistrationDate
        
                // เก็บวันที่หมดอายุภาษี
                $List[$index]->tax_expiry_date = $taxExpireDate->format('d/m/Y'); // วันหมดอายุภาษีในรูปแบบ วัน/เดือน/ปี
        
                // คำนวณจำนวนวันที่เหลือจนถึงวันหมดอายุภาษี
                $diff = date_diff($today, $taxExpireDate); // ความต่างระหว่างวันที่ปัจจุบันและวันหมดอายุภาษี
                $days_left = (int)$diff->format('%a'); // จำนวนวันเหลือจนถึงวันหมดอายุภาษี
        
                // เก็บจำนวนวันที่เหลือ
                $List[$index]->tax_days_left = $days_left;
    
                //calculate days to renew from today
                // $date_renew =  date_create(date('Y-m-d',strtotime(implode("-",$regArr))));
                // $due_date_diff = date_diff($today,$date_renew);
                // $days = (int)$due_date_diff->format('%a')%365;
    
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
    
                $register_day = date_create(date('Y-m-d',strtotime($item->RegistrationDate)));
                // $today = date_create(date('Y-m-d'));
                $diff_register = date_diff($register_day,$today);
                $days = (int)$diff_register->format('%a')%365;     // เศษวัน
                $total_year = floor((int)$diff_register->format('%a')/365);

                // $List[$index]->days = $days;
                // $List[$index]->days_ins = $days_ins;
                
            }
    
            session(['total_year' => $total_year]);
    
            // ส่งข้อมูลไปยัง view
            // dd($List);
            return view('info', ["list" => $List]);
    
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    // function info(){
    //     $List =  DB::table('cars as c')
    //              ->join('customers as cs','c.CusId','=','cs.id')
    //              ->select('c.CarNumber','c.TaxHistoryDate','c.RegistrationDate','c.InsHistoryDate','cs.CustomerName','cs.PhoneNumber','cs.id','c.BookOwner','c.id')
    //              ->get();

    //     // calculate to renew ins
    //     foreach ($List as $index => $item) {
    //         $d_warning = 90;
    //         $d_danger = 30;
    //         $d_expire = 0;
    //         // $ins_warning = 90;
    //         // $ins_danger = 30;


    //         $today = date_create(date('Y-m-d')); // วันที่ปัจจุบัน

    //         // แยกวัน, เดือน, ปี จาก RegistrationDate
    //         $regArr = explode("-", $item->RegistrationDate);
    //         $regDay = $regArr[2];  // วัน
    //         $regMonth = $regArr[1]; // เดือน
    //         $regYear = $regArr[0]; // ปี
    
    //         // ใช้ TaxHistoryDate เป็นฐานในการคำนวณปี
    //         $taxExpireDate = date_create($item->TaxHistoryDate); // วันต่อภาษีครั้งล่าสุด
    //         $taxExpireDate->modify('+1 year'); // เพิ่ม 1 ปีจากวันต่อภาษี
    
    //         // ใช้ปีจาก TaxHistoryDate แต่ใช้วันเดือนจาก RegistrationDate
    //         $taxExpireDate->setDate($taxExpireDate->format('Y'), $regMonth, $regDay); // เปลี่ยนปีจาก TaxHistoryDate และใช้เดือน/วันจาก RegistrationDate
    
    //         // เก็บวันที่หมดอายุภาษี
    //         $List[$index]->tax_expiry_date = $taxExpireDate->format('d/m/Y'); // วันหมดอายุภาษีในรูปแบบ วัน/เดือน/ปี
    
    //         // คำนวณจำนวนวันที่เหลือจนถึงวันหมดอายุภาษี
    //         $diff = date_diff($today, $taxExpireDate); // ความต่างระหว่างวันที่ปัจจุบันและวันหมดอายุภาษี
    //         $days_left = (int)$diff->format('%a'); // จำนวนวันเหลือจนถึงวันหมดอายุภาษี
    
    //         // เก็บจำนวนวันที่เหลือ
    //         $List[$index]->tax_days_left = $days_left;

    //         //calculate days to renew from today
    //         // $date_renew =  date_create(date('Y-m-d',strtotime(implode("-",$regArr))));
    //         // $due_date_diff = date_diff($today,$date_renew);
    //         // $days = (int)$due_date_diff->format('%a')%365;

    //         // $List[$index]->days = $days;
    //         // $List[$index]->cls = ($days<=$d_danger) ? "bg_danger" : (($days>$d_danger&&$days<=$d_warning) ? "bg_warning" : "") ;
    //         // $List[$index]->disabled = ($days<=$d_danger) ? "" : "disabled" ;
    //         $date = (date('Y-m-d',strtotime($item->InsHistoryDate))); // Replace with your date
    //         $newDate = date('d/m/Y', strtotime('+365 days', strtotime($date)));
    //         $List[$index]->next_Ins = $newDate;
    //         $ins = date('Y-m-d',strtotime('+365 days', strtotime($date)));
    //         $ins = date_create($ins);
    //         $diff_ins = date_diff($today,$ins);
    //         $days_ins = (int)$diff_ins->format('%a')%365;

    //         $register_day = date_create(date('Y-m-d',strtotime($item->RegistrationDate)));
    //         // $today = date_create(date('Y-m-d'));
    //         $diff_register = date_diff($register_day,$today);
    //         $days = (int)$diff_register->format('%a')%365;     // เศษวัน
    //         $total_year = floor((int)$diff_register->format('%a')/365);
            
    //     }
    //     return response()->json(['success' => false, 'message' => $e->getMessage()], 500);

    //     session(['total_year' => $total_year]);
    //     return view('info',["list"=>$List]);

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
        $List = DB::table('cars as c')
        ->join('customers as cs', 'c.CusId', '=', 'cs.id')
        ->join('settings_renew as sr', 'sr.cartype_id', '=', 'c.TypeId')
        ->join('taxes as t', 't.id', '=', 'c.TaxId')
        ->select('c.id','c.CarNumber','c.CarCity','c.RegistrationDate','c.BookOwner', 'cs.CustomerName', 'cs.NationalID', 'cs.PhoneNumber',
            'cs.Address', 'c.SelectOption', 'c.TaxHistoryDate', 'c.InsHistoryDate', 'c.TaxId',
            'c.CarCC', 'c.CarWeight','t.name','c.TaxType', 'c.InsuranceType', 'c.TypeId', 'sr.renew_cost', 'sr.fee', 'sr.delivery_cost')
        ->where('c.id', $id)
        ->first();
        $tax = $List->TaxId;

        // if($tax==1)
        // $customer=DB::table('customers')->where('id',$id)->first();
        // $car=DB::table('cars')->where('id',$id)->first();
        $provinces = DB::table('provinces')->get();
        $taxes = DB::table('taxes')->get();
        $settings["type"] = DB::table('settings')->where('category_key','=','car_type')->get();
        // $settings["brand"] = DB::table('settings')->where('category_key','=','car_brand')->get();
        return view('editInfo',["list"=>$List,'prov'=>$provinces,'sett'=>$settings,'taxes'=>$taxes]);

        // return view('editInfo', compact('customer','car'),['prov'=>$provinces,'sett'=>$settings]);
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
        // $findCus =  DB::table('customers')->where('NationalID',$requestInfo->NationalID)->first();
        // $rows = count((array)$findCus);
        // if(!$rows){
        //     $CusID = DB::table('customers')->insertGetId($dataCus);
        //     $dataCar["CusId"] = $CusID;
        // }else{
        //     $dataCar["CusId"] = $findCus->id;
        //     $errorMsg[] = 'พบข้อมูลบัตรประชาชนถูกใช้งานแล้ว';
        // }

        // // find duplicate the car by plate number.
        // $findCar =  DB::table('cars')->where('CarNumber',$PlateNumber)->first();
        // $rows = count((array)$findCar);
        // if(!$rows){
        //     DB::table('cars')->insert($dataCar);
        // }else{
        //     $errorMsg[] = 'ป้ายทะเบียนนี้ถูกใช้งานแล้ว';
        // }
        // $Url = (count($errorMsg)) ? '/info' : '/info';



        // DB::table('cus_and_cars')->insert($dataCusAndCar);
        DB::table('customers')->where('id',$id)->update($dataCus);
        DB::table('cars')->where('id',$id)->update($dataCar);
        return redirect('/info');

    }
}
