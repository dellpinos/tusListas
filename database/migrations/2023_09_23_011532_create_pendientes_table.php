<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('pendientes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nombre');
            $table->decimal('precio');
            $table->integer('stock')->nullable()->default(0);
            $table->float('desc_porc', 4, 2)->nullable()->default(null);
            $table->integer('desc_duracion')->nulleable()->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendientes');
    }
};
