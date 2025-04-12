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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            
            // Informations du site
            $table->string('site_name')->nullable();
            $table->text('site_description')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('default_timezone')->default('UTC');
            $table->string('default_locale')->default('fr');
            $table->integer('tools_per_page')->default(12);
            $table->string('tools_order')->default('DESC');
            
            // SEO & Métadonnées
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('meta_author')->nullable();
            $table->string('opengraph_image')->nullable();
            
            // Logos et Images
            $table->string('logo_light')->nullable();
            $table->string('logo_dark')->nullable();
            $table->string('logo_email')->nullable();
            $table->string('favicon')->nullable();
            $table->string('homepage_cover')->nullable();
            
            // Tracking et Fonctionnalités
            $table->string('google_analytics_id')->nullable();
            $table->string('facebook_pixel_id')->nullable();
            $table->boolean('cookie_banner_enabled')->default(false);
            $table->boolean('dark_mode_enabled')->default(true);
            
            // Mode maintenance
            $table->boolean('maintenance_mode')->default(false);
            $table->text('maintenance_message')->nullable();
            $table->timestamp('maintenance_end_date')->nullable();
            $table->text('maintenance_allowed_ips')->nullable();
            
            // Sitemap
            $table->boolean('sitemap_auto_generation')->default(false);
            $table->boolean('sitemap_include_tools')->default(true);
            $table->boolean('sitemap_include_blog')->default(true);
            $table->string('sitemap_frequency')->default('weekly');
            $table->decimal('sitemap_priority', 2, 1)->default(0.8);
            $table->timestamp('sitemap_last_generated')->nullable();
            
            // Informations de l'entreprise
            $table->string('company_name')->nullable();
            $table->string('company_registration_number')->nullable();
            $table->string('company_vat_number')->nullable();
            $table->text('company_address')->nullable();
            $table->string('company_phone')->nullable();
            $table->string('company_email')->nullable();
            $table->text('company_opening_hours')->nullable();
            
            // Réseaux sociaux
            $table->string('social_facebook')->nullable();
            $table->string('social_twitter')->nullable();
            $table->string('social_instagram')->nullable();
            $table->string('social_linkedin')->nullable();
            $table->string('social_youtube')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
}; 