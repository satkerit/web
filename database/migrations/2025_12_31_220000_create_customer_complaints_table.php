<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_complaints', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('account_number')->nullable();
            $table->enum('category', [
                'service',      // Pelayanan
                'product',      // Produk
                'transaction',  // Transaksi
                'facility',     // Fasilitas
                'staff',        // Petugas/Karyawan
                'other'         // Lainnya
            ])->default('other');
            $table->string('subject');
            $table->text('description');
            $table->string('branch_office')->nullable();
            $table->date('incident_date')->nullable();
            $table->json('attachments')->nullable();
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->enum('status', ['pending', 'in_progress', 'resolved', 'closed'])->default('pending');
            $table->text('resolution')->nullable();
            $table->text('admin_notes')->nullable();
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_complaints');
    }
};
