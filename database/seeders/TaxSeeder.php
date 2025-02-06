<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tax;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Tax = [
            'รถยนต์ส่วนบุคคลทั่วไป (ป้ายทะเบียนพื้นขาว ตัวหนังสือดำ)',
            'รถบรรทุกส่วนบุคคลเกิน 7 ที่นั่ง (ป้ายทะเบียนพื้นขาว ตัวหนังสือสีเขียว)',
            'รถยนต์ส่วนบุคคลเกิน 7 ที่นั่ง (ป้ายทะเบียนพื้นขาว ตัวหนังสือสีน้ำเงิน)'
        ];

        foreach ($Tax as $Taxs) {
            Tax::create(['name' => $Taxs]);
        }
    }
}
