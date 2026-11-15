<?php

namespace Tests\Feature;

use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    public function test_html_responses_include_browser_security_headers(): void
    {
        $response = $this->withHeader('X-Forwarded-Proto', 'https')->get('/login');

        $response->assertOk()
            ->assertHeader('Content-Security-Policy', "default-src 'self'; base-uri 'self'; connect-src 'self'; font-src 'self'; form-action 'self'; frame-ancestors 'none'; img-src 'self' data: blob:; object-src 'none'; script-src 'self'; style-src 'self'; worker-src 'self' blob:; upgrade-insecure-requests")
            ->assertHeader('X-Frame-Options', 'DENY')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeader('Permissions-Policy', 'camera=(), geolocation=(), microphone=(), payment=(), usb=()');
    }

    public function test_http_responses_do_not_send_hsts(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertHeaderMissing('Strict-Transport-Security');
    }
}
