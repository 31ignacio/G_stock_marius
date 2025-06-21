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
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->float('quantite');
            $table->dateTime('date');
            $table->integer('prix');
            $table->float('total');
            $table->text('code');
            $table->float('totalHT');
            $table->float('totalTVA');
            $table->float('totalTTC');
            $table->text('produit');
            $table->float('montantPaye')->nullable();
            $table->float('monnaie')->nullable();
            $table->float('reduction')->nullable();
            $table->float('montantRendu')->nullable();
            $table->float('montantFinal')->nullable();
            $table->unsignedBigInteger('client');
            $table->foreign('client')->references('id')->on('clients')->onDelete('cascade');
            $table->text('client_nom')->nullable();
            $table->unsignedBigInteger('produitType_id');
            $table->foreign('produitType_id')->references('id')->on('produit_types')->onDelete('cascade');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
