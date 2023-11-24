<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asset>
 */
class AssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            'name' => $this->faker->word,
            'purchase_date' => $this->faker->date,
            'start_depreciation_date' => $this->faker->optional()->date,
            'gross_value' => 10000,
            'salvage_value' => 5000,
            'residual_value' => 5000,
            'description' => $this->faker->sentence . " EDITED",
            'supplier' => $this->faker->numberBetween(1, 100),
            'asset_category_id' => AssetCategoryFactory::new()->create(),
            'perusahaan_id' => 1,
            'cabang_id' => 1,
            //
        ];
    }
}
