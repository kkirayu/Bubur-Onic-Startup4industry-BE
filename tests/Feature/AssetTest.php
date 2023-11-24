<?php

namespace Tests\Feature;

use Database\Factories\AssetCategoryFactory;
use Database\Factories\AssetFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AssetTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $response = $this->getJson('/api/keuangan/asset');

        $response->assertStatus(200);
    }
    public function testCreateAsset(): void
    {

        $category_asset = AssetCategoryFactory::new()->create();
        $user = \App\Models\User::factory()->create();

        $payload = [
            'name' => $this->faker->word,
            'purchase_date' => $this->faker->date,
            'start_depreciation_date' => $this->faker->optional()->date,
            'gross_value' => 10000,
            'salvage_value' => 5000,
            'residual_value' => 5000,
            'description' => $this->faker->sentence,
            'supplier' => $this->faker->numberBetween(1, 100),
            'asset_category_id' => $category_asset->id,
            'perusahaan_id' => $user->perusahaan_id,
            'cabang_id' => $user->cabang_id,
        ];
        $this->actingAs($user);
        $response = $this->postJson('/api/keuangan/asset', $payload);

        $response->assertStatus(201);
    }
    public function testEditAsset(): void
    {

        $category_asset = AssetCategoryFactory::new()->create();
        $user = \App\Models\User::factory()->create();

        $asset = AssetFactory::new()->create();

        $payload = [
            'name' => $this->faker->word,
            'purchase_date' => $this->faker->date,
            'start_depreciation_date' => $this->faker->optional()->date,
            'gross_value' => 10000,
            'salvage_value' => 5000,
            'residual_value' => 5000,
            'description' => $this->faker->sentence. " EDITED",
            'supplier' => $this->faker->numberBetween(1, 100),
            'asset_category_id' => $category_asset->id,
            'perusahaan_id' => $user->perusahaan_id,
            'cabang_id' => $user->cabang_id,
        ];
        $this->actingAs($user);
        $response = $this->postJson('/api/keuangan/asset/'. $asset->id, $payload);

        $response->assertStatus(200);
        $this->assertDatabaseHas('assets', [
            'id' => $asset->id,
            'description' => $payload['description']
        ]);

    }
    public function testDetailAsset(): void
    {

        $category_asset = AssetCategoryFactory::new()->create();
        $user = \App\Models\User::factory()->create();

        $asset = AssetFactory::new()->create();

        $this->actingAs($user);
        $response = $this->getJson('/api/keuangan/asset/'. $asset->id);

        $response->assertStatus(200);
        $this->assertDatabaseHas('assets', [
            'id' => $asset->id,
            'description' => $asset['description']
        ]);

    }

    public function testDeleteAsset(): void
    {

        $category_asset = AssetCategoryFactory::new()->create();
        $user = \App\Models\User::factory()->create();

        $asset = AssetFactory::new()->create();

        $this->actingAs($user);
        $response = $this->deleteJson('/api/keuangan/asset/'. $asset->id);

        $response->assertStatus(200);
    }



    public function testPostAsset(): void
    {

        $category_asset = AssetCategoryFactory::new()->create();
        $user = \App\Models\User::factory()->create();

        $asset = AssetFactory::new()->create();

        $this->actingAs($user);
        $response = $this->postJson('/api/keuangan/asset/'. $asset->id . "/post");

        $response->assertStatus(200);
    }

    public function testUnpostAsset(): void
    {

        $category_asset = AssetCategoryFactory::new()->create();
        $user = \App\Models\User::factory()->create();

        $asset = AssetFactory::new()->create();

        $this->actingAs($user);
        $response = $this->postJson('/api/keuangan/asset/'. $asset->id . "/un-post");

        $response->assertStatus(200);
    }
    public function testDepresiasiAsset(): void
    {

        $category_asset = AssetCategoryFactory::new()->create();
        $user = \App\Models\User::factory()->create();

        $asset = AssetFactory::new()->create();

        $this->actingAs($user);
        $payload = [
            "date" => "2021-01-01",
            "description" => "test depresiasi",
            "depreciation_value" => 1000
        ];
        $response = $this->postJson('/api/keuangan/asset/'. $asset->id . "/depresiasi",  $payload);

        $this->assertDatabaseHas('asset_depreciation_history', [
            'asset_id' => $asset->id,
            'description' => $payload['description']
        ]);
        $response->assertStatus(200);
    }
}
