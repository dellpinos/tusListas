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
            $table->integer('contador_show')->default(0);
            $table->foreignId('precio_id');
            $table->string('unidad_fraccion')->nullable();
            $table->integer('contenido_total')->nullable();
            $table->float('ganancia_fraccion', 3, 1)->unsigned()->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('contador_show');
            $table->dropColumn('precio_id');
            $table->dropColumn('unidad_fraccion');
            $table->dropColumn('contenido_total');
            $table->dropColumn('ganancia_fraccion');
            
        });
    }
};
