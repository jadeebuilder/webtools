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
        Schema::table('transactions', function (Blueprint $table) {
            // Supprimer les contraintes de clé étrangère existantes
            if (Schema::hasColumn('transactions', 'payment_method_id')) {
                try {
                    $table->dropForeign(['payment_method_id']);
                } catch (\Exception $e) {
                    // La contrainte n'existe peut-être pas
                }
                $table->dropColumn('payment_method_id');
            }
            
            if (Schema::hasColumn('transactions', 'provider_id')) {
                $table->dropColumn('provider_id');
            }
            
            if (Schema::hasColumn('transactions', 'provider_reference')) {
                $table->dropColumn('provider_reference');
            }
            
            if (Schema::hasColumn('transactions', 'metadata') && !Schema::hasColumn('transactions', 'meta')) {
                $table->renameColumn('metadata', 'meta');
            } else if (!Schema::hasColumn('transactions', 'meta')) {
                $table->json('meta')->nullable();
            }
            
            // Modifier la colonne reference_id si elle existe
            if (Schema::hasColumn('transactions', 'reference_id') && !Schema::hasColumn('transactions', 'uuid')) {
                // Enlever l'unicité sur reference_id si elle existe
                try {
                    $table->dropUnique(['reference_id']);
                } catch (\Exception $e) {
                    // L'index unique n'existe peut-être pas
                }
            }
            
            // Ajouter les colonnes manquantes
            if (!Schema::hasColumn('transactions', 'uuid')) {
                $table->uuid('uuid')->after('id');
            }
            
            if (!Schema::hasColumn('transactions', 'package_id')) {
                $table->foreignId('package_id')->nullable()->after('user_id')
                    ->constrained('packages')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('transactions', 'package_price_id')) {
                $table->foreignId('package_price_id')->nullable()->after('package_id')
                    ->constrained('package_prices')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('transactions', 'payment_provider')) {
                $table->string('payment_provider')->nullable()->after('currency_id');
            }
            
            if (!Schema::hasColumn('transactions', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('payment_provider');
            }
            
            if (!Schema::hasColumn('transactions', 'payment_id')) {
                $table->string('payment_id')->nullable()->after('payment_method');
            }
            
            if (!Schema::hasColumn('transactions', 'invoice_id')) {
                $table->string('invoice_id')->nullable()->after('payment_id');
            }
            
            if (!Schema::hasColumn('transactions', 'cycle')) {
                $table->string('cycle')->nullable()->after('type');
            }
            
            if (!Schema::hasColumn('transactions', 'ends_at')) {
                $table->timestamp('ends_at')->nullable()->after('paid_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Cette méthode est simplifiée car il est difficile de revenir en arrière exactement
            // Suppression des colonnes ajoutées
            $table->dropColumn([
                'uuid',
                'package_id',
                'package_price_id',
                'payment_provider',
                'payment_method',
                'payment_id',
                'invoice_id',
                'cycle',
                'ends_at'
            ]);
            
            // Ajout des colonnes supprimées
            $table->foreignId('payment_method_id')->constrained('payment_methods');
            $table->string('provider_id')->nullable();
            $table->string('provider_reference')->nullable();
            
            // Renommer meta en metadata
            $table->renameColumn('meta', 'metadata');
        });
    }
}; 