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
        // 1. Renommer les colonnes
        Schema::table('subscriptions', function (Blueprint $table) {
            // Renommer les colonnes existantes pour correspondre au modèle
            if (Schema::hasColumn('subscriptions', 'processor')) {
                $table->renameColumn('processor', 'payment_provider');
            }
            
            if (Schema::hasColumn('subscriptions', 'processor_id')) {
                $table->renameColumn('processor_id', 'subscription_id');
            }
            
            if (Schema::hasColumn('subscriptions', 'frequency')) {
                $table->renameColumn('frequency', 'cycle');
            }
        });
        
        // 2. Ajouter les colonnes manquantes
        Schema::table('subscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('subscriptions', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }
            
            if (!Schema::hasColumn('subscriptions', 'quantity')) {
                $table->integer('quantity')->default(1);
            }
            
            if (!Schema::hasColumn('subscriptions', 'next_billing_date')) {
                $table->timestamp('next_billing_date')->nullable();
            }
            
            if (!Schema::hasColumn('subscriptions', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable();
            }
            
            if (!Schema::hasColumn('subscriptions', 'meta')) {
                $table->json('meta')->nullable();
            }
        });
        
        // 3. Supprimer les colonnes non utilisées, une par une
        if (Schema::hasColumn('subscriptions', 'starts_at')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->dropColumn('starts_at');
            });
        }
        
        if (Schema::hasColumn('subscriptions', 'payment_method_id')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                // Supprimer d'abord la contrainte de clé étrangère
                $table->dropForeign(['payment_method_id']);
                $table->dropColumn('payment_method_id');
            });
        }
        
        if (Schema::hasColumn('subscriptions', 'amount_paid')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->dropColumn('amount_paid');
            });
        }
        
        if (Schema::hasColumn('subscriptions', 'provider')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->dropColumn('provider');
            });
        }
        
        if (Schema::hasColumn('subscriptions', 'provider_id')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->dropColumn('provider_id');
            });
        }
        
        if (Schema::hasColumn('subscriptions', 'provider_status')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->dropColumn('provider_status');
            });
        }
        
        if (Schema::hasColumn('subscriptions', 'payment_reference')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->dropColumn('payment_reference');
            });
        }
        
        if (Schema::hasColumn('subscriptions', 'next_payment_at')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->dropColumn('next_payment_at');
            });
        }
        
        if (Schema::hasColumn('subscriptions', 'auto_renew')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->dropColumn('auto_renew');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cette méthode est simplifiée car il est difficile de revenir en arrière exactement
        // Nous recréons simplement les colonnes originales
        
        Schema::table('subscriptions', function (Blueprint $table) {
            // Renommer les colonnes dans l'autre sens
            $table->renameColumn('payment_provider', 'processor');
            $table->renameColumn('subscription_id', 'processor_id');
            $table->renameColumn('cycle', 'frequency');
            
            // Recréer les colonnes supprimées
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('next_payment_at')->nullable();
            $table->boolean('auto_renew')->default(true);
        });
    }
};
