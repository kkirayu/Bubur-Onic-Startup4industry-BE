<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class JournalListTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $this->actingAs(\App\Models\User::factory()->create());
        $response = $this->get('/api/journal/journal?search=2013');

        $response->assertStatus(200);
    }
    public function testListAllJournal(): void
    {
        $this->actingAs(\App\Models\User::factory()->create());
        $response = $this->getJson('/api/journal/journal?search=2013');

        dd($response->getContent());
        $response->assertStatus(200);
    }
    public function testListDetailJournal(): void
    {
        $this->actingAs(\App\Models\User::factory()->create());
        $response = $this->getJson('/api/journal/journal/105');

        dd($response->getContent());
        $response->assertStatus(200);
    }
}
