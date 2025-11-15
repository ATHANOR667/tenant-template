<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanLevelsTable extends Migration
{
    public function up(): void
    {
        Schema::create('ban_levels', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->integer('duration_days')->nullable();
            $table->string('color');
            $table->softDeletes();
            $table->timestamps();

            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ban_levels');
    }
}
