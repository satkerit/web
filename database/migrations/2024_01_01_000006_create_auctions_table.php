<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('asset_type');
            $table->text('location');
            $table->decimal('starting_price', 15, 2);
            $table->decimal('estimated_price', 15, 2)->nullable();
            $table->datetime('auction_date');
            $table->datetime('registration_deadline');
            $table->json('images')->nullable();
            $table->json('documents')->nullable();
            $table->enum('status', ['upcoming', 'ongoing', 'closed'])->default('upcoming');
            $table->string('contact_person');
            $table->string('contact_phone');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auctions');
    }
};
