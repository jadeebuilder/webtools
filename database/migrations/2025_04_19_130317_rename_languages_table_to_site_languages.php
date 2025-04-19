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
        // Renommer la table
        Schema::rename('languages', 'site_languages');

        // Mettre à jour les clés étrangères
        Schema::table('faq_category_translations', function (Blueprint $table) {
            $table->dropForeign('faq_category_translations_language_id_foreign');
            $table->foreign('language_id')->references('id')->on('site_languages')->onDelete('cascade');
        });

        Schema::table('faq_translations', function (Blueprint $table) {
            $table->dropForeign('faq_translations_language_id_foreign');
            $table->foreign('language_id')->references('id')->on('site_languages')->onDelete('cascade');
        });

        Schema::table('testimonial_translations', function (Blueprint $table) {
            $table->dropForeign('testimonial_translations_language_id_foreign');
            $table->foreign('language_id')->references('id')->on('site_languages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurer les clés étrangères
        Schema::table('testimonial_translations', function (Blueprint $table) {
            $table->dropForeign('testimonial_translations_language_id_foreign');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
        });

        Schema::table('faq_translations', function (Blueprint $table) {
            $table->dropForeign('faq_translations_language_id_foreign');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
        });

        Schema::table('faq_category_translations', function (Blueprint $table) {
            $table->dropForeign('faq_category_translations_language_id_foreign');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
        });

        // Renommer la table en retour
        Schema::rename('site_languages', 'languages');
    }
};
