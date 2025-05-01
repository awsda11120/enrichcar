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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('CusId');
            $table->string('CarNumber');
            $table->string('CarCity');
            $table->integer('CarWeight');
            $table->integer('CarCC');
            $table->string('InsuranceType');
            $table->string('TaxType');
            $table->string('TypeId');// ประเภทของ พ.ร.บ.
            $table->string('TaxId');// ประเภทของ ภาษี
            $table->string('BookOwner');
            $table->string('SelectOption');
            $table->date('RegistrationDate');
            $table->date('TaxHistoryDate');
            $table->date('InsHistoryDate');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
