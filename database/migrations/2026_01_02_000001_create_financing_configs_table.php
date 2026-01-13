<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financing_configs', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50);
            $table->string('name');
            $table->decimal('margin_rate', 8, 4);
            $table->bigInteger('min_principal');
            $table->bigInteger('max_principal');
            $table->json('available_tenors');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financing_configs');
    }
};
