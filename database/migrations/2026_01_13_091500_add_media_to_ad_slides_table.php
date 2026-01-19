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
        Schema::table('ad_slides', function (Blueprint $table) {
            $table->string('media_type')->default('text')->after('icon'); // text, image, video
            $table->string('media_path')->nullable()->after('media_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_slides', function (Blueprint $table) {
            $table->dropColumn(['media_type', 'media_path']);
        });
    }
};
