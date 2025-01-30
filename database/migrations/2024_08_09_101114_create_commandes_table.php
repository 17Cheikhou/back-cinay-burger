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
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->string('nom_client');
            $table->string('prenom_client');
            $table->string('telephone_client');
            $table->enum('statut', ['en_attente', 'terminer', 'annuler', 'payer'])->default('en_attente');
            $table->decimal('montant_total', 8, 2)->nullable();
            $table->date('date_paiement')->nullable();
            $table->decimal('montant_paiement', 8, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
