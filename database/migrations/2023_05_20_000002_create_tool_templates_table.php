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
        Schema::create('tool_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tool_id')->constrained()->onDelete('cascade');
            $table->foreignId('section_id')->constrained('tool_template_sections')->onDelete('cascade');
            $table->integer('order')->default(0); // Ordre d'affichage sur la page de l'outil
            $table->boolean('is_active')->default(true); // Si la section est active pour cet outil
            $table->json('settings')->nullable(); // Paramètres spécifiques pour cette section (si nécessaire)
            $table->timestamps();
            
            // Un outil ne peut avoir qu'une seule fois la même section
            $table->unique(['tool_id', 'section_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tool_templates');
    }
}; 