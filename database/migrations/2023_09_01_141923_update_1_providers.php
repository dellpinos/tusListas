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
        Schema::table('providers', function (Blueprint $table) {
            $table->float('ganancia', 4, 2)->default(1)->change();
            $table->string('email')->nullable()->change();
            $table->string('telefono')->nullable()->change();
            $table->string('vendedor')->nullable()->change();
            $table->string('web')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->float('ganancia', 3, 1)->change();
            $table->string('email')->change();
            $table->string('telefono')->change();
            $table->string('vendedor')->change();
            $table->string('web')->change();
        });
    }
};
