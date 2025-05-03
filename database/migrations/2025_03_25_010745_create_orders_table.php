<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->unsignedInteger('id',true)->primary();
            $table->dateTime('order_date');
            $table->unsignedBigInteger('staff_id');
            $table->unsignedBigInteger('customer_id');
            $table->decimal('total',10,2);
            $table->timestamps();
            $table->foreign('staff_id')->references('user_id')->on('staff')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('customer_id')->references('user_id')->on('customers')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
