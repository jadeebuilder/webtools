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
        // Mettre à jour les clés étrangères de faq_category_translations
        Schema::table('faq_category_translations', function (Blueprint $table) {
            $table->dropForeign(['language_id']);
            $table->foreign('language_id')->references('id')->on('site_languages')->onDelete('cascade');
        });

        // Mettre à jour les clés étrangères de faq_translations
        Schema::table('faq_translations', function (Blueprint $table) {
            $table->dropForeign(['language_id']);
            $table->foreign('language_id')->references('id')->on('site_languages')->onDelete('cascade');
        });

        // Mettre à jour les clés étrangères de testimonial_translations
        Schema::table('testimonial_translations', function (Blueprint $table) {
            $table->dropForeign(['language_id']);
            $table->foreign('language_id')->references('id')->on('site_languages')->onDelete('cascade');
        });

        // Supprimer l'ancienne table languages
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

        // Copier les données de site_languages vers languages
        DB::statement('INSERT INTO languages SELECT * FROM site_languages');

        // Restaurer les clés étrangères
        Schema::table('testimonial_translations', function (Blueprint $table) {
            $table->dropForeign(['language_id']);
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
        });

        Schema::table('faq_translations', function (Blueprint $table) {
            $table->dropForeign(['language_id']);
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
        });

        Schema::table('faq_category_translations', function (Blueprint $table) {
            $table->dropForeign(['language_id']);
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
        });
    }
};
