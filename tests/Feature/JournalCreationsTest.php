<?php

namespace Tests\Feature;

use App\Models\Journal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class JournalCreationsTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testCreateJournal(): void
    {

        $payload =  [
            "deskripsi" => "Journal description",
            "tanggal_transaksi" => "2021-10-01",
            "judul" => "Journal title",
            "akuns" => [
                [
                    "id" => 1,
                    "debit" => 1000,
                    "credit" => 0,
                    "description" => "Debit description"
                ],
                [
                    "id" => 2,
                    "debit" => 0,
                    "credit" => 1000,
                ]
            ]
        ];


        
        dump('/api/journal/journal/create-journal');
        dump(json_encode($payload));
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $response = $this->postJson('/api/journal/journal/create-journal', $payload);

        dump($response->getContent());
        $response->assertStatus(201);
    }
    public function testCreateInvalidJournal(): void
    {

        $payload =  [
            "deskripsi" => "Journal description",
            "tanggal_transaksi" => "2021-10-01",
            "judul" => "Journal title",
            "akuns" => [
                [
                    "id" => 1,
                    "debit" => 10020,
                    "credit" => 0,
                    "description" => "Debit description"
                ],
                [
                    "id" => 2,
                    "debit" => 0,
                    "credit" => 1000,
                    "description" => "Credit description"
                ]
            ]
        ];


        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $response = $this->postJson('/api/journal/journal/create-journal', $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(["akuns"]);
    }
    public function testTestValidationJournal(): void
    {

        $payload =  [
            "akuns" => [
                [
                    "id" => 1,
                    "debit" => 10020,
                    "credit" => 0,
                    "description" => "Debit description"
                ],
                [
                    "id" => 2,
                    "debit" => 0,
                    "credit" => 1000,
                    "description" => "Credit description"
                ]
            ]
        ];


        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $response = $this->postJson('/api/journal/journal/create-journal', $payload);


        $response->assertJsonValidationErrors(["judul", "tanggal_transaksi"]);
        $response->assertStatus(422);
    }

    public  function testPostJournal(): void
    {

        $this->testCreateJournal();
        $journal = Journal::orderBy("id",  "desc")->first();
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        
        dump('/api/journal/journal/' . $journal->id . '/post');
        $response = $this->postJson('/api/journal/journal/' . $journal->id . '/post');


        $this->assertDatabaseHas("journals", [
            "id" => $journal->id,
            "posted_at" => date("Y-m-d H:i:s"),
            "posted_by" => $user->id,
        ]);
        $response->assertStatus(200);
    }   
    
    public  function testUnPostJournal(): void
    {

        $this->testCreateJournal();
        $journal = Journal::orderBy("id",  "desc")->first();
        $journal->posted_at =  date("Y-m-d H:i:s");
        $journal->posted_by = 1;
        $journal->save();
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        
        dump('/api/journal/journal/' . $journal->id . '/un-post');
        $response = $this->postJson('/api/journal/journal/' . $journal->id . '/un-post');


        $this->assertDatabaseHas("journals", [  
            "id" => $journal->id,
            "posted_at" => null,
            "posted_by" => null,
        ]);
        $response->assertStatus(200);
    }
}
