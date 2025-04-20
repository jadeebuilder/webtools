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
        // Renommer expires_at en ends_at dans la table subscriptions
        if (Schema::hasColumn('subscriptions', 'expires_at')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->renameColumn('expires_at', 'ends_at');
            });
        }
        
        // Renommer expired_at en ends_at dans la table transactions si nécessaire
        if (Schema::hasColumn('transactions', 'expired_at') && !Schema::hasColumn('transactions', 'ends_at')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->renameColumn('expired_at', 'ends_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Renommer ends_at en expires_at dans la table subscriptions
        if (Schema::hasColumn('subscriptions', 'ends_at')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->renameColumn('ends_at', 'expires_at');
            });
        }
        
        // Renommer ends_at en expired_at dans la table transactions
        if (Schema::hasColumn('transactions', 'ends_at')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->renameColumn('ends_at', 'expired_at');
            });
        }
    }
}; 