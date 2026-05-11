<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interventions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incident_id')->constrained('incidents')->cascadeOnDelete();
            $table->string('type');
            $table->text('description')->nullable();
            $table->date('date_planifiee')->nullable();
            $table->date('date_realisation')->nullable();
            $table->string('statut')->default('planifiée'); // planifiée, terminée, annulée
            $table->decimal('cout_estime', 10, 2)->nullable();
            $table->string('intervenant_nom')->nullable(); // Nom du prestataire
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interventions');
    }
};
