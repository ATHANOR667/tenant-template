<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBansTable extends Migration
{
    public function up(): void
    {
        Schema::create('bans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('motif');
            $table->uuidMorphs('bannable');
            $table->uuidMorphs('banned_by');
            $table->nullableUuidMorphs('unbanned_by');
            $table->string('ban_level_id')->nullable();
            $table->timestamp('expires_at')->nullable();
            //$table->index(['expires_at', 'deleted_at']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bans');
    }
}
