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
        Schema::table('productos', function (Blueprint $table) {

            $table->string('codigo', 4)->unique();
            $table->foreignId('provider_id');
            $table->foreignId('categoria_id');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {

            $table->dropColumn('codigo');
            $table->dropColumn('provider_id');
            $table->dropColumn('categoria_id');
            
        });
    }
};
