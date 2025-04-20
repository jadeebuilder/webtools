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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->nullOnDelete();
            $table->foreignId('currency_id')->constrained();
            $table->foreignId('payment_method_id')->constrained();
            $table->string('reference_id')->unique(); // Identifiant unique de la transaction
            $table->string('provider_id')->nullable(); // ID chez le fournisseur de paiement
            $table->string('provider_reference')->nullable(); // Référence spécifique au fournisseur
            $table->decimal('amount', 10, 2);
            $table->string('type'); // payment, refund, subscription_payment, etc.
            $table->string('status'); // pending, completed, failed, refunded, etc.
            $table->timestamp('paid_at')->nullable();
            $table->json('metadata')->nullable(); // Données supplémentaires
            $table->timestamps();
            
            // Index pour les recherches fréquentes
            $table->index(['user_id', 'status']);
            $table->index(['subscription_id']);
            $table->index(['reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
}; 