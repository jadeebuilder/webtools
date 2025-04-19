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
        Schema::table('packages', function (Blueprint $table) {
            $table->integer('basic_tools_count')->default(0)->after('trial_restrictions')
                  ->comment('Nombre d\'outils standards accessibles avec ce package');
            $table->integer('vip_tools_count')->default(0)->after('basic_tools_count')
                  ->comment('Nombre d\'outils VIP accessibles avec ce package');
            $table->integer('ai_tools_count')->default(0)->after('vip_tools_count')
                  ->comment('Nombre d\'outils IA accessibles avec ce package');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['basic_tools_count', 'vip_tools_count', 'ai_tools_count']);
        });
    }
};
