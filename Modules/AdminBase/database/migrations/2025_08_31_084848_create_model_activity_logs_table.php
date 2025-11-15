<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelActivityLogsTable extends Migration
{
    public function up(): void
    {
        Schema::create('model_activity_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuidMorphs('logable');
            $table->uuidMorphs('changed_by');

            $table->json('changes')->nullable();
            $table->string('operation');

            $table->index('changed_by_type');
            $table->index('logable_type');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('model_activity_logs');
    }
}
