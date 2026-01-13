<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('identity_number')->nullable();
            $table->enum('type', ['fraud', 'violation', 'ethics', 'abuse', 'safety', 'other'])->default('other');
            $table->string('subject');
            $table->text('description');
            $table->string('reported_person')->nullable();
            $table->string('reported_department')->nullable();
            $table->date('incident_date')->nullable();
            $table->string('incident_location')->nullable();
            $table->json('attachments')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->enum('status', ['pending', 'in_review', 'investigating', 'resolved', 'closed'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
