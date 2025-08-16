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
        Schema::create('facture_achats', function (Blueprint $table) {
            $table->id();
            $table->decimal('quantite', 20, 2);          // Quantité avec précision
            $table->dateTime('date');
            $table->decimal('prix', 20, 2);              // Prix d'achat
            $table->decimal('prixVente', 20, 2);         // Prix de vente
            $table->decimal('benefice', 20, 2);          // Bénéfice unitaire
            $table->decimal('total', 20, 2);             // Total de l'achat
            $table->text('code');
            $table->decimal('totalAchat', 20, 2);        // Total achat
            $table->decimal('totalVente', 20, 2);        // Total vente
            $table->decimal('totalBenefice', 20, 2);     // Total bénéfice
            $table->text('produit');
            
            $table->unsignedBigInteger('societe_id');
            $table->foreign('societe_id')->references('id')->on('societes')->onDelete('cascade');
            
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->unsignedBigInteger('produitType_id');
            $table->foreign('produitType_id')->references('id')->on('produit_types')->onDelete('cascade');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facture_achats');
    }
};
