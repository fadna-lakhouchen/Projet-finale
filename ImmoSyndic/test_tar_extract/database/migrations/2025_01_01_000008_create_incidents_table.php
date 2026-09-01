<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('immeuble_id')->constrained('immeubles')->cascadeOnDelete();
            $table->string('titre');
            $table->text('description');
            $table->string('priorite')->default('moyenne'); // basse, moyenne, haute
            $table->string('statut')->default('nouveau'); // nouveau, en cours, résolu
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
