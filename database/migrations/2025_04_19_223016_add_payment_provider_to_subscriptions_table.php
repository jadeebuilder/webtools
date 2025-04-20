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
            if (!Schema::hasColumn('subscriptions', 'payment_provider')) {
                $table->string('payment_provider')->nullable();
            }
            
            if (!Schema::hasColumn('subscriptions', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }
            
            // Renommer la colonne plan_id en package_id si elle existe
            if (Schema::hasColumn('subscriptions', 'plan_id') && !Schema::hasColumn('subscriptions', 'package_id')) {
                // Supprimer d'abord la contrainte de clé étrangère
                $table->dropForeign(['plan_id']);
                $table->renameColumn('plan_id', 'package_id');
                // Ajouter la nouvelle contrainte
                $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
            }
            
            // Ajouter meta si nécessaire
            if (!Schema::hasColumn('subscriptions', 'meta')) {
                $table->json('meta')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // Supprimer les colonnes ajoutées
            $table->dropColumn(['payment_provider', 'payment_method', 'meta']);
            
            // Renommer la colonne package_id en plan_id
            if (Schema::hasColumn('subscriptions', 'package_id')) {
                $table->dropForeign(['package_id']);
                $table->renameColumn('package_id', 'plan_id');
                $table->foreign('plan_id')->references('id')->on('packages')->onDelete('cascade');
            }
        });
    }
};
