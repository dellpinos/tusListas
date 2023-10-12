<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('fabricantes', function (Blueprint $table) {
            $table->dropUnique('fabricantes_nombre_unique');
        });
    }

    public function down(): void
    {
        Schema::table('fabricantes', function (Blueprint $table) {
            $table->unique('nombre');
        });
    }
};