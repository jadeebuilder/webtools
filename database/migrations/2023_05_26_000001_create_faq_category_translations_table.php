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
        Schema::create('faq_category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faq_category_id')->constrained()->onDelete('cascade');
            $table->foreignId('language_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('slug')->nullable();
            
            // Index unique pour éviter les doublons de traduction pour une catégorie et une langue
            $table->unique(['faq_category_id', 'language_id'], 'faq_cat_trans_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faq_category_translations');
    }
}; 