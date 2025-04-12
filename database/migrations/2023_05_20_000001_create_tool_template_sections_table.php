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
        Schema::create('tool_template_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');               // Nom de la section (testimonials, faq, etc.)
            $table->string('partial_path');       // Chemin vers le partial (partials.home.testimonials)
            $table->string('icon');               // Icône représentant la section
            $table->text('description')->nullable(); // Description de la section
            $table->boolean('is_active')->default(true); // Si la section est active
            $table->integer('order')->default(0); // Ordre d'affichage dans l'admin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tool_template_sections');
    }
}; 