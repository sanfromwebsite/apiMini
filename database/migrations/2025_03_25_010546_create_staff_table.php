<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id',false)->primary();
            $table->tinyInteger('gender');
            $table->date('dob');
            $table->unsignedInteger('position_id');
            $table->decimal('salary',10,2);
            $table->string('photo');
            $table->tinyInteger('stopWork')->nullable();
            $table->timestamps();
            $table->foreign('position_id')->references('id')->on('positions')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
