<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('board_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('position');
            $table->enum('type', ['komisaris', 'direksi', 'pengawas_syariah']);
            $table->string('photo')->nullable();
            $table->text('biography')->nullable();
            $table->json('education')->nullable();
            $table->json('experience')->nullable();
            $table->integer('order_position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('board_members');
    }
};
