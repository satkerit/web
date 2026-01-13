<?php

namespace Tests\Feature\Public;

use Tests\TestCase;

class HomePageTest extends TestCase
{
    /**
     * Test home page returns 200 status code.
     * Requirements: 2.1
     */
    public function test_home_page_returns_successful_response(): void
    {
        $response = $this->withoutSecurityMiddleware()->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test home page uses correct view.
     * Requirements: 2.1
     */
    public function test_home_page_uses_correct_view(): void
    {
        $response = $this->withoutSecurityMiddleware()->get('/');

        $response->assertViewIs('frontend.home');
    }
}
