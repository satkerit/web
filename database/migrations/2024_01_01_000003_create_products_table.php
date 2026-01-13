<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Backup existing data if table exists
        $existingProducts = [];
        if (Schema::hasTable('products')) {
            $existingProducts = DB::table('products')->get()->toArray();
            Schema::dropIfExists('products');
        }

        // Create fresh products table with all fields
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type', 50)->default('simpanan_syariah'); // simpanan_syariah, pembiayaan_syariah, deposito
            $table->string('short_description', 500)->nullable();
            $table->text('description');
            $table->string('interest_rate', 100)->nullable();
            $table->json('features')->nullable();
            $table->json('requirements')->nullable();
            $table->json('benefits')->nullable();
            $table->string('image')->nullable();
            $table->string('image_alt')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order_position')->default(0);
            $table->timestamps();
        });

        // Restore data if any existed
        if (!empty($existingProducts)) {
            foreach ($existingProducts as $product) {
                $productArray = (array) $product;

                // Normalize type values
                $type = $productArray['type'] ?? 'simpanan_syariah';
                if ($type === 'Simpanan') $type = 'simpanan_syariah';
                if ($type === 'Pembiayaan') $type = 'pembiayaan_syariah';

                DB::table('products')->insert([
                    'id' => $productArray['id'] ?? null,
                    'name' => $productArray['name'],
                    'slug' => $productArray['slug'],
                    'type' => $type,
                    'short_description' => $productArray['short_description'] ?? null,
                    'description' => $productArray['description'] ?? '',
                    'interest_rate' => isset($productArray['interest_rate']) ? (string) $productArray['interest_rate'] : null,
                    'features' => $productArray['features'] ?? null,
                    'requirements' => $productArray['requirements'] ?? null,
                    'benefits' => $productArray['benefits'] ?? null,
                    'image' => $productArray['image'] ?? null,
                    'image_alt' => $productArray['image_alt'] ?? null,
                    'is_active' => $productArray['is_active'] ?? true,
                    'order_position' => $productArray['order_position'] ?? 0,
                    'created_at' => $productArray['created_at'] ?? now(),
                    'updated_at' => $productArray['updated_at'] ?? now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
