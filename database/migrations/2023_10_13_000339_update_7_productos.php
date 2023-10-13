<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropUnique('productos_nombre_unique');
            $table->dropUnique('productos_codigo_unique');
            $table->string('codigo', 5)->change();
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->unique('nombre');
            $table->unique('codigo');
            $table->string('codigo', 4)->change();
        });
    }
};
