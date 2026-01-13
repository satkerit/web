<?php

namespace Tests\Feature\Public;

use App\Models\Product;
use Tests\TestCase;

class ProductPagesTest extends TestCase
{
    /**
     * Test simpanan-syariah page returns 200 status code.
     * Requirements: 2.3
     */
    public function test_simpanan_syariah_page_returns_successful_response(): void
    {
        $response = $this->withoutSecurityMiddleware()->get('/produk-layanan/simpanan-syariah');

        $response->assertStatus(200);
    }

    /**
     * Test pembiayaan-syariah page returns 200 status code.
     * Requirements: 2.3
     */
    public function test_pembiayaan_syariah_page_returns_successful_response(): void
    {
        $response = $this->withoutSecurityMiddleware()->get('/produk-layanan/pembiayaan-syariah');

        $response->assertStatus(200);
    }

    /**
     * Test deposito-syariah page returns 200 status code.
     * Requirements: 2.3
     */
    public function test_deposito_syariah_page_returns_successful_response(): void
    {
        $response = $this->withoutSecurityMiddleware()->get('/produk-layanan/deposito-syariah');

        $response->assertStatus(200);
    }

    /**
     * Test kas-keliling page returns 200 status code.
     * Requirements: 2.3
     */
    public function test_kas_keliling_page_returns_successful_response(): void
    {
        $response = $this->withoutSecurityMiddleware()->get('/produk-layanan/kas-keliling');

        $response->assertStatus(200);
    }

    /**
     * Test product detail with valid slug returns 200 status code.
     * Requirements: 2.4
     */
    public function test_product_detail_with_valid_slug_returns_successful_response(): void
    {
        $product = Product::factory()->create([
            'is_active' => true,
        ]);

        $response = $this->withoutSecurityMiddleware()->get('/produk-layanan/detail/' . $product->slug);

        $response->assertStatus(200);
    }

    /**
     * Test product detail with invalid slug returns 404 status code.
     * Requirements: 2.5
     */
    public function test_product_detail_with_invalid_slug_returns_404(): void
    {
        $response = $this->withoutSecurityMiddleware()->get('/produk-layanan/detail/non-existent-product-slug');

        $response->assertStatus(404);
    }
}
