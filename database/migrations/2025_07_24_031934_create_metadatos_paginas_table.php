<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('metadatos_paginas', function (Blueprint $table) {
            $table->id();
            $table->string('pagina')->unique(); // ejemplo: "/servicios" o "home"
            $table->string('meta_title', 255)->nullable(); // título SEO
            $table->string('meta_description', 300)->nullable(); // descripción
            $table->text('meta_keywords')->nullable(); // palabras clave
            $table->string('canonical_url')->nullable(); // URL canónica
            $table->text('meta_robots')->nullable(); // ejemplo: index, follow
            $table->text('extra_meta')->nullable(); // para incluir metadatos personalizados si se desea
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metadatos_paginas');
    }
};
