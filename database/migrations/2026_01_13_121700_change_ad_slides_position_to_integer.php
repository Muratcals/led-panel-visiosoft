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
        Schema::table('ad_slides', function (Blueprint $table) {
            $table->dropColumn('position');
        });

        Schema::table('ad_slides', function (Blueprint $table) {
            $table->integer('position')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_slides', function (Blueprint $table) {
            $table->dropColumn('position');
        });

        Schema::table('ad_slides', function (Blueprint $table) {
            $table->enum('position', ['top', 'bottom'])->default('top');
        });
    }
};
