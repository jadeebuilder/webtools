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
            $table->renameColumn('name', 'lastname')->after('id');
            $table->string('firstname')->after('id');
            $table->string('phone')->nullable()->after('lastname');
            $table->boolean('newsletter_subscribed')->default(false)->after('email');
            $table->string('profile_photo_path')->nullable()->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('lastname', 'name');
            $table->dropColumn(['firstname', 'phone', 'newsletter_subscribed', 'profile_photo_path']);
        });
    }
};
