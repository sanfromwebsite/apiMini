<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->unsignedInteger('id',true)->primary();
            $table->string('product_name',100);
            $table->tinyInteger('qty');
            $table->decimal('unit_price',10,2);
            $table->decimal('total_price',10,2);
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('product_id');
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
