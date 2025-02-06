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
        Schema::create('cus_and_cars', function (Blueprint $table) {
            $table->id();
            $table->string('CarNumber');
            $table->string('CarCity');
            $table->string('RegistrationDate');
            $table->string('CustomerName');
            $table->string('PhoneNumber');

            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cus_and_cars');
    }
};
