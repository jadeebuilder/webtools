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
            // D'abord supprimer la contrainte de clé étrangère sur plan_id
            $table->dropForeign(['plan_id']);
            
            // Renommer la colonne
            $table->renameColumn('plan_id', 'package_id');
            
            // Ajouter la nouvelle contrainte de clé étrangère
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // D'abord supprimer la contrainte de clé étrangère sur package_id
            $table->dropForeign(['package_id']);
            
            // Renommer la colonne
            $table->renameColumn('package_id', 'plan_id');
            
            // Ajouter la nouvelle contrainte de clé étrangère
            $table->foreign('plan_id')->references('id')->on('packages')->onDelete('cascade');
        });
    }
};
