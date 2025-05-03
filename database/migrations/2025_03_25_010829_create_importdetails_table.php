<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('importdetails', function (Blueprint $table) {
            $table->unsignedInteger('id',true)->primary();
            $table->string('product_name');
            $table->tinyInteger('qty');
            $table->decimal('unit_price',10,2);
            $table->integer('total_product');
            $table->unsignedInteger('import_id');
            $table->unsignedInteger('product_id');
            $table->foreign('import_id')->references('id')->on('imports')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('importdetails');
    }
};
