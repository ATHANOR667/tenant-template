<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_connection_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuidMorphs('user');
            $table->string('ip_address')->nullable();
            $table->text('device_info')->nullable();
            $table->string('location')->nullable();
            $table->dateTime('session_start');
            $table->dateTime('last_activity');
            $table->dateTime('session_end')->nullable();
            $table->timestamp('session_expires_at')->nullable();
            $table->string('session_id')->nullable();
            $table->index(['user_id', 'user_type', 'session_id'], 'user_session_composite');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_connection_logs');
    }
};
