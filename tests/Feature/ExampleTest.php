<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_root_redirects_to_sale(): void
    {
        $this->get('/')->assertRedirect('/sales/create');
    }
}
