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
            $table->dropColumn('categoria');
            $table->dropColumn('codigo');
            $table->dropColumn('distribuidora_id');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {

            $table->string('categoria');
            $table->string('codigo');
            $table->foreignId('distribuidora_id');

        });
    }
};
