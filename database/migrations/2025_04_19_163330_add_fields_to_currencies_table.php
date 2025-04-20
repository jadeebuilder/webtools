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
        Schema::table('currencies', function (Blueprint $table) {
            if (!Schema::hasColumn('currencies', 'is_default')) {
                $table->boolean('is_default')->default(false)->after('symbol');
            }
            
            if (!Schema::hasColumn('currencies', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('is_default');
            }
            
            if (!Schema::hasColumn('currencies', 'decimal_mark')) {
                $table->string('decimal_mark', 10)->default('.')->after('is_active');
            }
            
            if (!Schema::hasColumn('currencies', 'thousands_separator')) {
                $table->string('thousands_separator', 10)->default(',')->after('decimal_mark');
            }
            
            if (!Schema::hasColumn('currencies', 'precision')) {
                $table->unsignedTinyInteger('precision')->default(2)->after('thousands_separator');
            }
            
            if (!Schema::hasColumn('currencies', 'symbol_position')) {
                $table->enum('symbol_position', ['before', 'after'])->default('before')->after('precision');
            }
            
            if (!Schema::hasColumn('currencies', 'display_order')) {
                $table->unsignedInteger('display_order')->default(1)->after('symbol_position');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('currencies', function (Blueprint $table) {
            $table->dropColumn([
                'is_default',
                'is_active',
                'decimal_mark',
                'thousands_separator',
                'precision',
                'symbol_position',
                'display_order'
            ]);
        });
    }
};
