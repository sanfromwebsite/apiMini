<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->unsignedInteger('id',true)->primary();
            $table->dateTime('date');
            $table->unsignedBigInteger('staff_id');
            $table->unsignedInteger('order_id');
            $table->decimal('amount',10,2);
            $table->foreign('staff_id')->references('user_id')->on('staff')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
