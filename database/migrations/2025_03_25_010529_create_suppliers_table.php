<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->unsignedInteger('id',true)->primary();
            $table->string('name',100)->unique();
            $table->string('address',100);
            $table->string('company',100)->nullable()->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
