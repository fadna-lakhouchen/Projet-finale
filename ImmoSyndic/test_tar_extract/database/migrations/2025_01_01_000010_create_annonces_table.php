<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('annonces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('immeuble_id')->constrained('immeubles')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Syndic créateur
            $table->string('titre');
            $table->text('contenu');
            $table->date('date_publication');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annonces');
    }
};
