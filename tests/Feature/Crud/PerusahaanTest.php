<?php

namespace Tests\Feature\Crud;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\BaseCrudTest;
use Tests\TestCase;

class PerusahaanTest extends BaseCrudTest
{

    public function setUp(): void
    {
        parent::setUp();
    }
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
