<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['keuangan_publikasi', 'tata_kelola', 'tahunan', 'tahunan_berkelanjutan']);
            $table->integer('year');
            $table->integer('quarter')->nullable();
            $table->string('file_path');
            $table->bigInteger('file_size')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
