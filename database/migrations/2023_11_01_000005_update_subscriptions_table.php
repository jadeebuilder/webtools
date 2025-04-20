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
        Schema::table('subscriptions', function (Blueprint $table) {
            // Ajout des colonnes liées au paiement
            $table->foreignId('payment_method_id')->nullable()->after('plan_id')->constrained();
            $table->foreignId('currency_id')->nullable()->after('payment_method_id')->constrained();
            $table->decimal('amount_paid', 10, 2)->nullable()->after('currency_id');
            $table->string('provider')->nullable()->after('amount_paid'); // stripe, paypal, etc.
            $table->string('provider_id')->nullable()->after('provider'); // ID de l'abonnement chez le provider
            $table->string('provider_status')->nullable()->after('provider_id');
            $table->string('payment_reference')->nullable()->after('provider_status');
            $table->timestamp('next_payment_at')->nullable()->after('trial_ends_at');
            
            // Index pour les recherches fréquentes
            $table->index(['provider', 'provider_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payment_method_id');
            $table->dropConstrainedForeignId('currency_id');
            $table->dropColumn([
                'amount_paid',
                'provider',
                'provider_id',
                'provider_status',
                'payment_reference',
                'next_payment_at'
            ]);
            
            // Supprimer l'index
            $table->dropIndex(['provider', 'provider_id']);
        });
    }
}; 