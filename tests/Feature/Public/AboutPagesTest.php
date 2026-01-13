<?php

namespace Tests\Feature\Public;

use Tests\TestCase;

class AboutPagesTest extends TestCase
{
    /**
     * Test company page returns 200 status code.
     * Requirements: 2.2
     */
    public function test_company_page_returns_successful_response(): void
    {
        $response = $this->withoutSecurityMiddleware()->get('/tentang-kami/perusahaan');

        $response->assertStatus(200);
    }

    /**
     * Test komisaris page returns 200 status code.
     * Requirements: 2.2
     */
    public function test_komisaris_page_returns_successful_response(): void
    {
        $response = $this->withoutSecurityMiddleware()->get('/tentang-kami/dewan-komisaris');

        $response->assertStatus(200);
    }

    /**
     * Test direksi page returns 200 status code.
     * Requirements: 2.2
     */
    public function test_direksi_page_returns_successful_response(): void
    {
        $response = $this->withoutSecurityMiddleware()->get('/tentang-kami/dewan-direksi');

        $response->assertStatus(200);
    }

    /**
     * Test pengawas-syariah page returns 200 status code.
     * Requirements: 2.2
     */
    public function test_pengawas_syariah_page_returns_successful_response(): void
    {
        $response = $this->withoutSecurityMiddleware()->get('/tentang-kami/dewan-pengawas-syariah');

        $response->assertStatus(200);
    }

    /**
     * Test struktur page returns 200 status code.
     * Requirements: 2.2
     */
    public function test_struktur_page_returns_successful_response(): void
    {
        $response = $this->withoutSecurityMiddleware()->get('/tentang-kami/struktur-organisasi');

        $response->assertStatus(200);
    }

    /**
     * Test offices page returns 200 status code.
     * Requirements: 2.2
     */
    public function test_offices_page_returns_successful_response(): void
    {
        $response = $this->withoutSecurityMiddleware()->get('/tentang-kami/kantor');

        $response->assertStatus(200);
    }
}
