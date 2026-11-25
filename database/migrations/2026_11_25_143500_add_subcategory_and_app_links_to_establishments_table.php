<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('establishments', function (Blueprint $table) {
            $table->string('subcategory', 80)->nullable()->after('category');
            $table->string('android_app_url', 2048)->nullable()->after('instagram_url');
            $table->string('ios_app_url', 2048)->nullable()->after('android_app_url');
        });
    }

    public function down(): void
    {
        Schema::table('establishments', function (Blueprint $table) {
            $table->dropColumn(['subcategory', 'android_app_url', 'ios_app_url']);
        });
    }
};
