<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('matricule')->unique();
            $table->string('nom');
            $table->string('prenom');
            $table->string('pays')->nullable();
            $table->string('ville')->nullable();
            $table->string('password')->nullable();
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('passcode')->nullable();
            $table->string('dateNaissance')->nullable();
            $table->string('lieuNaissance')->nullable();
            $table->string('pieceIdentiteRecto')->nullable();
            $table->string('pieceIdentiteVerso')->nullable();
            $table->string('password_changed_at')->nullable();
            $table->string('passcode_reset_status')->nullable();
            $table->string('passcode_reset_date')->nullable();
            $table->string('photoProfil')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
}
