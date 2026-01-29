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
        Schema::create('brochures', function (Blueprint $table) {
            $table->id();
            $table->string('filename')->unique(); // Generated unique name
            $table->string('original_name'); // Original file name
            $table->string('file_path'); // Storage path
            $table->unsignedBigInteger('file_size'); // Size in bytes
            $table->unsignedBigInteger('uploaded_by')->nullable(); // Admin ID
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brochures');
    }
};
