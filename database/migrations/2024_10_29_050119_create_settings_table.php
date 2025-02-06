<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('category_key');
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};





// insert into settings (category_key,name) values ('car_type','จักรยานยนต์');
// insert into settings (category_key,name) values ('car_type','รถเก๋ง');
// insert into settings (category_key,name) values ('car_type','รถกระบะ');
// insert into settings (category_key,name) values ('car_type','รถตู้');
// insert into settings (category_key,name) values ('car_type','รถ 6 ล้อ');

// insert into settings (category_key,name) values ('car_brand','Audi');
// insert into settings (category_key,name) values ('car_brand','Benz');
// insert into settings (category_key,name) values ('car_brand','Ford');
// insert into settings (category_key,name) values ('car_brand','Kawasaki');
// insert into settings (category_key,name) values ('car_brand','Yamaha');
