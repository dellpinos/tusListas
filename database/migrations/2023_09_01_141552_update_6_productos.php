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

            $table->string('nombre')->unique()->change();
            $table->float('ganancia_fraccion', 4, 2)->nullable()->change();
            $table->float('ganancia_prod', 4, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            
            $table->dropUnique('productos_nombre_unique'); // Elimina la condición de Unique
            $table->float('ganancia_fraccion', 3, 1)->change();
            $table->float('ganancia_prod', 3, 1)->change();
        });
    }
};
