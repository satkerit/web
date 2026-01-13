<?php

namespace Tests\Feature\Public;

use App\Models\CompanyInfo;
use Tests\TestCase;

class StaticPagesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Seed CompanyInfo required by views
        CompanyInfo::create([
            'name' => 'Test Company',
            'phone' => '0717-1234567',
            'email' => 'test@example.com',
            'address' => 'Test Address',
        ]);
    }

    /**
     * Test contact page returns 200 status code.
     * Requirements: 2.10
     */
    public function test_contact_page_returns_successful_response(): void
    {
        $response = $this->withoutSecurityMiddleware()->get('/hubungi-kami');

        $response->assertStatus(200);
    }

    /**
     * Test whistleblowing page returns 200 status code.
     * Requirements: 2.10
     */
    public function test_whistleblowing_page_returns_successful_response(): void
    {
        $response = $this->withoutSecurityMiddleware()->get('/whistleblowing');

        $response->assertStatus(200);
    }

    /**
     * Test pengaduan-nasabah page returns 200 status code.
     * Requirements: 2.10
     */
    public function test_pengaduan_nasabah_page_returns_successful_response(): void
    {
        $response = $this->withoutSecurityMiddleware()->get('/pengaduan-nasabah');

        $response->assertStatus(200);
    }

    /**
     * Test download-logo page returns 200 status code.
     * Requirements: 2.10
     */
    public function test_download_logo_page_returns_successful_response(): void
    {
        $response = $this->withoutSecurityMiddleware()->get('/download-logo');

        $response->assertStatus(200);
    }
}
