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
        Schema::create('aumentos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->float('porcentaje', 3, 2);
            $table->string('tipo');
            $table->string('nombre');
            $table->string('username')->nullable();
            $table->integer('afectados');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aumentos');
    }
};
