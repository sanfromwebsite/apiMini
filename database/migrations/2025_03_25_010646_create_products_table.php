<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->unsignedInteger('id',true)->primary();
            $table->string('name',100);
            $table->smallInteger('qty');
            $table->decimal('unit_price_stock',10,2);
            $table->decimal('unit_sale_stock',10,2);
            $table->string('image',250);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
