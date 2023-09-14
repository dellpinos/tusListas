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
        Schema::table('precios', function (Blueprint $table) {

            $table->float('desc_porc', 4, 2)->nullable()->default(null);
            $table->integer('desc_duracion')->nulleable()->default(0);
            $table->integer('desc_acu')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('precios', function (Blueprint $table) {
            $table->dropColumn('desc_porc');
            $table->dropColumn('desc_duracion');
            $table->dropColumn('desc_acu');
        });
    }
};
