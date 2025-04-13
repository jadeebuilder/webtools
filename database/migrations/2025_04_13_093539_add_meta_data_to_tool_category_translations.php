<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mettre à jour les enregistrements existants pour remplir les champs meta_title et meta_description
        DB::table('tool_category_translations')
            ->whereNull('meta_title')
            ->orWhere('meta_title', '')
            ->update(['meta_title' => DB::raw('name')]);
            
        DB::table('tool_category_translations')
            ->whereNull('meta_description')
            ->orWhere('meta_description', '')
            ->update(['meta_description' => DB::raw('description')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tool_category_translations', function (Blueprint $table) {
            //
        });
    }
};
