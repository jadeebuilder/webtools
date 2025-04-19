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
        Schema::create('tool_types', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique()->comment('Identifiant unique du type d\'outil');
            $table->string('icon')->nullable()->comment('Classe d\'icône (ex: fa-star)');
            $table->string('color')->default('#000000')->comment('Couleur hexadécimale associée au type');
            $table->boolean('is_active')->default(true)->comment('Indique si le type est actif');
            $table->unsignedSmallInteger('order')->default(0)->comment('Ordre d\'affichage');
            $table->timestamps();
        });

        // Table de traduction pour les types d'outils
        Schema::create('tool_type_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tool_type_id')->constrained()->onDelete('cascade');
            $table->string('locale')->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unique(['tool_type_id', 'locale']);
            $table->timestamps();
        });

        // Table pivot pour lier les outils aux types
        Schema::create('tool_type_tools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tool_id')->constrained()->onDelete('cascade');
            $table->foreignId('tool_type_id')->constrained()->onDelete('cascade');
            $table->unique(['tool_id', 'tool_type_id']);
            $table->timestamps();
        });

        // Table pivot pour lier les packages aux types d'outils
        Schema::create('package_tool_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->foreignId('tool_type_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('tools_limit')->default(0)->comment('Limite d\'outils de ce type pour le package (0 = illimité)');
            $table->unique(['package_id', 'tool_type_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_tool_types');
        Schema::dropIfExists('tool_type_tools');
        Schema::dropIfExists('tool_type_translations');
        Schema::dropIfExists('tool_types');
    }
}; 