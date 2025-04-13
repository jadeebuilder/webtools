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
        Schema::create('ad_block_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('enabled')->default(true);
            $table->boolean('block_content')->default(false);
            $table->boolean('show_message')->default(true);
            $table->string('message_title');
            $table->text('message_text');
            $table->string('message_button');
            $table->integer('countdown')->default(10);
            $table->timestamps();
        });

        // Insertion des valeurs par défaut
        DB::table('ad_block_settings')->insert([
            'enabled' => true,
            'block_content' => false,
            'show_message' => true,
            'message_title' => 'Nous avons détecté que vous utilisez un bloqueur de publicités',
            'message_text' => 'Notre site est gratuit et ne survit que grâce à la publicité. Merci de désactiver votre bloqueur de publicités pour continuer.',
            'message_button' => 'J\'ai désactivé mon AdBlock',
            'countdown' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_block_settings');
    }
};
