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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique(); // Identifiant unique pour la méthode (stripe, paypal, etc.)
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable(); // Configuration globale du proceseur de paiement
            $table->string('processor_class')->nullable(); // Classe du processeur de paiement
            $table->string('icon')->nullable(); // Classe d'icône ou chemin d'image
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
}; 