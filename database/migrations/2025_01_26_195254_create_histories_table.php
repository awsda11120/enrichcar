<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->string('CarId');
            $table->date('DateRenew')->nullable();
            $table->integer('TypeRenewIns')->nullable();
            $table->integer('TypeRenewTax')->nullable();
            $table->string('Receive');
            $table->string('ProofOfReceive')->nullable();
            $table->integer('SumRenew');
            $table->integer('SumTax');
            $table->integer('InsIncome');
            $table->integer('TaxIncome');
            $table->integer('SumDelivery');
            $table->integer('SumCost');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
