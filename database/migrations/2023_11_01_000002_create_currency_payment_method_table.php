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
        Schema::create('currency_payment_method', function (Blueprint $table) {
            $table->id();
            $table->foreignId('currency_id')->constrained()->onDelete('cascade');
            $table->foreignId('payment_method_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable(); // Configuration spécifique à la devise
            $table->integer('display_order')->default(0);
            $table->timestamps();
            
            // Un moyen de paiement ne peut être activé qu'une fois par devise
            $table->unique(['currency_id', 'payment_method_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currency_payment_method');
    }
}; 