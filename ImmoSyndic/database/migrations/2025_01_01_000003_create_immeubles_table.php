<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('immeubles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('syndic_id')->constrained('users')->cascadeOnDelete();
            $table->string('nom');
            $table->string('adresse');
            $table->string('ville');
            $table->integer('nombre_etages');
            $table->integer('nombre_appartements');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('immeubles');
    }
};
