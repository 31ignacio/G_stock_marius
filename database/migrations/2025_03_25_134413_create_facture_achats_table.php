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
            $table->float('quantite');
            $table->dateTime('date');
            $table->integer('prix');
            $table->integer('prixVente');
            $table->integer('benefice');
            $table->float('total');
            $table->text('code');
            $table->float('totalAchat');
            $table->float('totalVente');
            $table->float('totalBenefice');
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
