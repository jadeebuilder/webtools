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
        // Supprimer les clés étrangères qui font référence à la table languages
        Schema::table('faq_category_translations', function (Blueprint $table) {
            $table->dropForeign('faq_category_translations_language_id_foreign');
        });

        Schema::table('faq_translations', function (Blueprint $table) {
            $table->dropForeign('faq_translations_language_id_foreign');
        });

        Schema::table('testimonial_translations', function (Blueprint $table) {
            $table->dropForeign('testimonial_translations_language_id_foreign');
        });

        // Supprimer la table languages
        Schema::dropIfExists('languages');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recréer la table languages
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 10)->unique();
            $table->string('flag', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_rtl')->default(false);
            $table->timestamps();
        });

        // Recréer les clés étrangères
        Schema::table('faq_category_translations', function (Blueprint $table) {
            $table->foreign('language_id', 'faq_category_translations_language_id_foreign')
                  ->references('id')->on('languages')->onDelete('cascade');
        });

        Schema::table('faq_translations', function (Blueprint $table) {
            $table->foreign('language_id', 'faq_translations_language_id_foreign')
                  ->references('id')->on('languages')->onDelete('cascade');
        });

        Schema::table('testimonial_translations', function (Blueprint $table) {
            $table->foreign('language_id', 'testimonial_translations_language_id_foreign')
                  ->references('id')->on('languages')->onDelete('cascade');
        });
    }
}; 