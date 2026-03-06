<?php

namespace Tests\Feature;

use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    /**
     * Test that security headers are present
     */
    public function test_security_headers_are_present(): void
    {
        $response = $this->get('/');

        // X-Frame-Options
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');

        // X-Content-Type-Options
        $response->assertHeader('X-Content-Type-Options', 'nosniff');

        // X-XSS-Protection
        $response->assertHeader('X-XSS-Protection', '1; mode=block');

        // Referrer-Policy
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Content-Security-Policy
        $this->assertTrue($response->headers->has('Content-Security-Policy'));

        // Cross-Origin-Opener-Policy
        $response->assertHeader('Cross-Origin-Opener-Policy', 'same-origin');

        // Cross-Origin-Embedder-Policy
        $response->assertHeader('Cross-Origin-Embedder-Policy', 'require-corp');

        // Cross-Origin-Resource-Policy
        $response->assertHeader('Cross-Origin-Resource-Policy', 'same-origin');
    }

    /**
     * Test CSP contains required directives
     */
    public function test_csp_contains_required_directives(): void
    {
        $response = $this->get('/');
        
        $csp = $response->headers->get('Content-Security-Policy');
        
        $this->assertStringContainsString("default-src 'self'", $csp);
        $this->assertStringContainsString("script-src", $csp);
        $this->assertStringContainsString("style-src", $csp);
        $this->assertStringContainsString("frame-ancestors 'self'", $csp);
        $this->assertStringContainsString("form-action 'self'", $csp);
        $this->assertStringContainsString("base-uri 'self'", $csp);
        $this->assertStringContainsString("object-src 'none'", $csp);
    }

    /**
     * Test HSTS in production
     */
    public function test_hsts_header_in_production(): void
    {
        if (app()->environment('production')) {
            $response = $this->get('/');
            $this->assertTrue($response->headers->has('Strict-Transport-Security'));
            
            $hsts = $response->headers->get('Strict-Transport-Security');
            $this->assertStringContainsString('max-age=31536000', $hsts);
            $this->assertStringContainsString('includeSubDomains', $hsts);
        } else {
            $this->markTestSkipped('HSTS is only enabled in production');
        }
    }
}
