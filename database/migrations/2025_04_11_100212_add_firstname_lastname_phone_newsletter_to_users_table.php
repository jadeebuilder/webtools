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
        Schema::table('users', function (Blueprint $table) {
            $table->string('firstname')->after('id');
            $table->string('lastname')->after('firstname');
            $table->string('phone')->nullable()->after('lastname');
            $table->boolean('newsletter_subscribed')->default(false)->after('email');
            $table->string('profile_photo_path')->nullable()->after('password');
        });

        // Suppression de la colonne name après avoir ajouté les nouvelles colonnes
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recréer la colonne name avant de supprimer les autres
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->after('id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['firstname', 'lastname', 'phone', 'newsletter_subscribed', 'profile_photo_path']);
        });
    }
};
