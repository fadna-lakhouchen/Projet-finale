<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appartements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('immeuble_id')->constrained('immeubles')->cascadeOnDelete();
            $table->string('numero');
            $table->integer('etage');
            $table->decimal('superficie', 8, 2);
            $table->string('type'); // Studio, F2, F3...
            $table->string('statut')->default('occupé'); // occupé, vacant
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appartements');
    }
};
