<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('imports', function (Blueprint $table) {
            $table->unsignedInteger('id',true)->primary();
            $table->timestamp('import_date');
            $table->unsignedBigInteger('staff_id');
            $table->unsignedInteger('supplier_id');
            $table->decimal('import_total',10,2);
            $table->timestamps();
            $table->foreign('staff_id')->references('user_id')->on('staff')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imports');
    }
};

