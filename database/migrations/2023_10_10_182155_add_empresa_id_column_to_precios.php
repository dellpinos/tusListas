<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('precios', function (Blueprint $table) {
            $table->foreignId('empresa_id');
        });
    }

    public function down(): void
    {
        Schema::table('precios', function (Blueprint $table) {
            $table->dropColumn('empresa_id');
        });
    }
};
