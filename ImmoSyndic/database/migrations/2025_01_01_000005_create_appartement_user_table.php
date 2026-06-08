<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table pivot Résident <-> Appartement avec attributs (date_entree, date_sortie, type_resident)
        Schema::create('appartement_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('appartement_id')->constrained('appartements')->cascadeOnDelete();
            $table->string('type_resident')->nullable(); // Locataire, Propriétaire
            $table->date('date_entree');
            $table->date('date_sortie')->nullable(); // null = habite encore ici
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appartement_user');
    }
};
