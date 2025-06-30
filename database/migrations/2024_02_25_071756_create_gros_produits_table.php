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
        Schema::create('gros_produits', function (Blueprint $table) {
            $table->id();
            $table->text('code');
            $table->string('libelle');
            $table->float('quantite')->nullable();
            $table->dateTime('dateReception');
            $table->decimal('prix', 10, 2)->nullable();
            $table->decimal('prixAchat', 10, 2)->default(0);
            $table->dateTime('dateExpiration');
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
        Schema::dropIfExists('gros_produits');
    }
};
