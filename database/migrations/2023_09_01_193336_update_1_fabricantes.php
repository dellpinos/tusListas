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
        Schema::table('fabricantes', function (Blueprint $table) {

            $table->string('telefono')->nullable()->change();
            $table->string('vendedor')->nullable()->change();
            $table->string('descripcion')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fabricantes', function (Blueprint $table) {
            $table->string('telefono')->change();
            $table->string('vendedor')->change();
            $table->string('descripcion')->change();
        });
    }
};
