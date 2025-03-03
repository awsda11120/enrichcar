<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('histories', function (Blueprint $table) {
            $table->boolean('status')->default(0); // 0 = ยังไม่เสร็จ, 1 = เสร็จสิ้น
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('histories', function (Blueprint $table) {
            //
        });
    }
};
