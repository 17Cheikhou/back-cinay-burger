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
        Schema::create('articles_commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained('commandes')->onDelete('cascade');
            $table->foreignId('burger_id')->constrained('burgers');
            $table->integer('quantite');
            $table->decimal('prix', 8, 2);
            $table->timestamps();

            // Ajouter une contrainte d'unicité sur commande_id et burger_id
            $table->unique(['commande_id', 'burger_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles_commandes');
    }
};
