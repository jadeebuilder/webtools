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
        Schema::create('ad_settings', function (Blueprint $table) {
            $table->id();
            $table->string('position');       // before_nav, after_nav, etc.
            $table->boolean('active')->default(false);
            $table->string('type');           // image, adsense
            $table->string('image')->nullable();
            $table->string('link')->nullable();
            $table->string('alt')->nullable();
            $table->text('code')->nullable();  // Pour les codes JavaScript (AdSense)
            $table->json('display_on')->nullable(); // Pages où afficher cette publicité
            $table->integer('priority')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_settings');
    }
};
