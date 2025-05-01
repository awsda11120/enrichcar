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
            'รถยนต์นั่งส่วนบุคคลไม่เกิน 7 คน',
            'รถยนต์นั่งส่วนบุคคลเกิน 7 คน',
            'รถยนต์บรรทุกส่วนบุคคล'
        ];

        foreach ($Tax as $Taxs) {
            Tax::create(['name' => $Taxs]);
        }
    }
}
